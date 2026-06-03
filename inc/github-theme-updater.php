<?php
/**
 * GitHub release updater for the BesiBau theme.
 *
 * Configure the repository through the Update URI header in style.css:
 * Update URI: https://github.com/OWNER/REPOSITORY
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function besibau_normalize_github_repository( $repository ) {
	$repository = trim( (string) $repository );

	if ( preg_match( '#github\.com/([^/\s]+/[^/\s?#]+)#i', $repository, $matches ) ) {
		$repository = $matches[1];
	}

	$repository = preg_replace( '/\.git$/', '', $repository );

	return trim( $repository, " \t\n\r\0\x0B/" );
}

function besibau_github_repository() {
	$repository = defined( 'BESIBAU_GITHUB_REPOSITORY' ) ? BESIBAU_GITHUB_REPOSITORY : '';
	$repository = apply_filters( 'besibau_github_repository', $repository );
	$repository = besibau_normalize_github_repository( $repository );

	if ( $repository && false === strpos( $repository, 'YOUR-GITHUB-USERNAME' ) ) {
		return $repository;
	}

	$update_uri = wp_get_theme()->get( 'UpdateURI' );
	$repository = besibau_normalize_github_repository( $update_uri );

	if ( $repository && false === strpos( $repository, 'YOUR-GITHUB-USERNAME' ) ) {
		return $repository;
	}

	return '';
}

function besibau_github_request( $url ) {
	$headers = array(
		'Accept'     => 'application/vnd.github+json',
		'User-Agent' => 'BesiBau WordPress Theme',
	);

	if ( defined( 'BESIBAU_GITHUB_TOKEN' ) && BESIBAU_GITHUB_TOKEN ) {
		$headers['Authorization'] = 'Bearer ' . BESIBAU_GITHUB_TOKEN;
	}

	$response = wp_remote_get( $url, array(
		'timeout' => 10,
		'headers' => $headers,
	) );

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return null;
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	return is_array( $data ) ? $data : null;
}

function besibau_github_latest_release() {
	static $release = null;

	if ( null !== $release ) {
		return $release;
	}

	$repository = besibau_github_repository();
	if ( ! $repository ) {
		$release = false;
		return false;
	}

	$release = besibau_github_request( 'https://api.github.com/repos/' . $repository . '/releases/latest' );

	return $release;
}

function besibau_github_release_version( $release ) {
	if ( empty( $release['tag_name'] ) ) {
		return '';
	}

	return ltrim( $release['tag_name'], 'vV' );
}

function besibau_github_release_package( $release ) {
	if ( ! empty( $release['assets'] ) && is_array( $release['assets'] ) ) {
		foreach ( $release['assets'] as $asset ) {
			if ( ! empty( $asset['name'] ) && 'besibau-theme.zip' === $asset['name'] && ! empty( $asset['browser_download_url'] ) ) {
				return $asset['browser_download_url'];
			}
		}
	}

	return ! empty( $release['zipball_url'] ) ? $release['zipball_url'] : '';
}

function besibau_update_theme_transient( $transient ) {
	if ( empty( $transient->checked ) ) {
		return $transient;
	}

	$stylesheet = get_stylesheet();
	if ( empty( $transient->checked[ $stylesheet ] ) ) {
		return $transient;
	}

	$release = besibau_github_latest_release();
	$version = $release ? besibau_github_release_version( $release ) : '';
	$package = $release ? besibau_github_release_package( $release ) : '';

	if ( ! $version || ! $package || ! version_compare( $version, $transient->checked[ $stylesheet ], '>' ) ) {
		return $transient;
	}

	$transient->response[ $stylesheet ] = array(
		'theme'        => $stylesheet,
		'new_version'  => $version,
		'url'          => ! empty( $release['html_url'] ) ? $release['html_url'] : wp_get_theme()->get( 'ThemeURI' ),
		'package'      => $package,
		'requires'     => wp_get_theme()->get( 'RequiresWP' ),
		'requires_php' => wp_get_theme()->get( 'RequiresPHP' ),
	);

	return $transient;
}
add_filter( 'pre_set_site_transient_update_themes', 'besibau_update_theme_transient' );

function besibau_theme_api_info( $result, $action, $args ) {
	if ( 'theme_information' !== $action || empty( $args->slug ) || get_stylesheet() !== $args->slug ) {
		return $result;
	}

	$release = besibau_github_latest_release();
	if ( ! $release ) {
		return $result;
	}

	$theme = wp_get_theme();
	$info = new stdClass();
	$info->name = $theme->get( 'Name' );
	$info->slug = get_stylesheet();
	$info->version = besibau_github_release_version( $release );
	$info->author = $theme->get( 'Author' );
	$info->homepage = ! empty( $release['html_url'] ) ? $release['html_url'] : $theme->get( 'ThemeURI' );
	$info->requires = $theme->get( 'RequiresWP' );
	$info->requires_php = $theme->get( 'RequiresPHP' );
	$info->download_link = besibau_github_release_package( $release );
	$info->sections = array(
		'description' => $theme->get( 'Description' ),
		'changelog'  => ! empty( $release['body'] ) ? wp_kses_post( wpautop( $release['body'] ) ) : __( 'Automatic GitHub release.', 'besibau' ),
	);

	return $info;
}
add_filter( 'themes_api', 'besibau_theme_api_info', 10, 3 );

function besibau_github_source_selection( $source, $remote_source, $upgrader, $hook_extra = null ) {
	if ( empty( $hook_extra['theme'] ) || get_stylesheet() !== $hook_extra['theme'] ) {
		return $source;
	}

	if ( basename( untrailingslashit( $source ) ) === get_stylesheet() ) {
		return $source;
	}

	global $wp_filesystem;

	$target = trailingslashit( $remote_source ) . get_stylesheet();
	if ( $wp_filesystem->exists( $target ) ) {
		$wp_filesystem->delete( $target, true );
	}

	if ( $wp_filesystem->move( $source, $target, true ) ) {
		return $target;
	}

	return $source;
}
add_filter( 'upgrader_source_selection', 'besibau_github_source_selection', 10, 4 );
