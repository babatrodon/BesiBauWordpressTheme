<?php
/**
 * GitHub release updater for the BesiBau theme.
 *
 * Configure the repository through the Update URI header in style.css:
 * Update URI: OWNER/REPOSITORY
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

	return 'babatrodon/BesiBauWordpressTheme';
}

function besibau_github_repository_path() {
	$repository = besibau_normalize_github_repository( besibau_github_repository() );

	if ( ! preg_match( '#^[A-Za-z0-9_.-]+/[A-Za-z0-9_.-]+$#', $repository ) ) {
		return 'babatrodon/BesiBauWordpressTheme';
	}

	return $repository;
}

function besibau_github_token() {
	return ( defined( 'BESIBAU_GITHUB_TOKEN' ) && BESIBAU_GITHUB_TOKEN ) ? BESIBAU_GITHUB_TOKEN : '';
}

function besibau_github_headers( $download = false ) {
	$headers = array(
		'Accept'     => $download ? 'application/octet-stream' : 'application/vnd.github+json',
		'User-Agent' => 'BesiBau WordPress Theme',
	);

	$token = besibau_github_token();
	if ( $token ) {
		$headers['Authorization'] = 'Bearer ' . $token;
	}

	return $headers;
}

function besibau_github_request( $url ) {
	$response = wp_remote_get( $url, array(
		'timeout' => 10,
		'headers' => besibau_github_headers(),
	) );

	if ( is_wp_error( $response ) ) {
		$GLOBALS['besibau_github_last_error'] = $response->get_error_message();
		return null;
	}

	$code = wp_remote_retrieve_response_code( $response );
	$GLOBALS['besibau_github_last_response_code'] = $code;

	if ( 200 !== $code ) {
		$GLOBALS['besibau_github_last_error'] = trim( wp_remote_retrieve_body( $response ) );
		return null;
	}

	$GLOBALS['besibau_github_last_error'] = '';

	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	return is_array( $data ) ? $data : null;
}

function besibau_github_tag_release() {
	$repository = besibau_github_repository_path();
	if ( ! $repository ) {
		return false;
	}

	$tags = besibau_github_request( 'https://api.github.com/repos/' . $repository . '/tags?per_page=100' );
	if ( empty( $tags ) || ! is_array( $tags ) ) {
		return false;
	}

	usort(
		$tags,
		function ( $a, $b ) {
			$a_version = ! empty( $a['name'] ) ? ltrim( $a['name'], 'vV' ) : '';
			$b_version = ! empty( $b['name'] ) ? ltrim( $b['name'], 'vV' ) : '';
			return version_compare( $b_version, $a_version );
		}
	);

	$tag = reset( $tags );
	if ( empty( $tag['name'] ) ) {
		return false;
	}

	return array(
		'tag_name'    => $tag['name'],
		'html_url'    => 'https://github.com/' . $repository . '/releases/tag/' . rawurlencode( $tag['name'] ),
		'zipball_url' => ! empty( $tag['zipball_url'] ) ? $tag['zipball_url'] : 'https://api.github.com/repos/' . $repository . '/zipball/' . rawurlencode( $tag['name'] ),
		'body'        => __( 'Automatic GitHub tag package.', 'besibau' ),
	);
}

function besibau_github_raw_release() {
	$repository = besibau_github_repository_path();
	if ( ! $repository ) {
		return false;
	}

	foreach ( array( 'main', 'master' ) as $branch ) {
		$response = wp_remote_get(
			'https://raw.githubusercontent.com/' . $repository . '/' . $branch . '/style.css',
			array(
				'timeout' => 10,
				'headers' => array( 'User-Agent' => 'BesiBau WordPress Theme' ),
			)
		);

		if ( is_wp_error( $response ) ) {
			$GLOBALS['besibau_github_last_error'] = $response->get_error_message();
			continue;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$GLOBALS['besibau_github_last_response_code'] = $code;
		if ( 200 !== $code ) {
			$GLOBALS['besibau_github_last_error'] = 'raw.githubusercontent.com returned HTTP ' . $code . ' for branch ' . $branch . '.';
			continue;
		}

		$body = wp_remote_retrieve_body( $response );
		if ( ! preg_match( '/^\s*\*?\s*Version:\s*([0-9][0-9a-zA-Z.\-]*)/mi', $body, $matches ) ) {
			$GLOBALS['besibau_github_last_error'] = 'Could not read the Version header from style.css on GitHub.';
			continue;
		}

		$GLOBALS['besibau_github_last_error'] = '';

		return array(
			'tag_name' => $matches[1],
			'html_url' => 'https://github.com/' . $repository . '/releases/latest',
			'package'  => 'https://github.com/' . $repository . '/releases/latest/download/besibau-theme.zip',
			'body'     => __( 'Latest GitHub release.', 'besibau' ),
		);
	}

	return false;
}

function besibau_github_latest_release() {
	static $release = null;

	if ( null !== $release ) {
		return $release;
	}

	$cached = get_site_transient( 'besibau_github_release' );
	if ( is_array( $cached ) && ! empty( $cached['tag_name'] ) ) {
		$release = $cached;
		return $release;
	}

	$repository = besibau_github_repository_path();
	if ( ! $repository ) {
		$release = false;
		return false;
	}

	// Primary: no GitHub API involved, so shared-hosting API rate limits cannot block updates.
	$release = besibau_github_raw_release();

	// Fallback: GitHub API (define BESIBAU_GITHUB_TOKEN in wp-config.php for higher limits).
	if ( ! $release ) {
		$release = besibau_github_request( 'https://api.github.com/repos/' . $repository . '/releases/latest' );
		if ( ! $release ) {
			$release = besibau_github_tag_release();
		}
	}

	if ( $release ) {
		set_site_transient( 'besibau_github_release', $release, 10 * MINUTE_IN_SECONDS );
	}

	return $release;
}

function besibau_github_release_version( $release ) {
	if ( empty( $release['tag_name'] ) ) {
		return '';
	}

	return ltrim( $release['tag_name'], 'vV' );
}

function besibau_github_release_package( $release ) {
	if ( ! empty( $release['package'] ) ) {
		return $release['package'];
	}

	if ( ! empty( $release['assets'] ) && is_array( $release['assets'] ) ) {
		foreach ( $release['assets'] as $asset ) {
			if ( empty( $asset['name'] ) || 'besibau-theme.zip' !== $asset['name'] ) {
				continue;
			}
			if ( besibau_github_token() && ! empty( $asset['url'] ) ) {
				return $asset['url'];
			}
			if ( ! empty( $asset['browser_download_url'] ) ) {
				return $asset['browser_download_url'];
			}
		}
	}

	return ! empty( $release['zipball_url'] ) ? $release['zipball_url'] : '';
}

function besibau_github_download_args( $args, $url ) {
	$token = besibau_github_token();
	if ( ! $token || false === strpos( $url, 'https://api.github.com/repos/' ) ) {
		return $args;
	}

	$repository = besibau_github_repository_path();
	if ( ! $repository || false === strpos( $url, 'https://api.github.com/repos/' . $repository . '/' ) ) {
		return $args;
	}

	if ( empty( $args['headers'] ) || ! is_array( $args['headers'] ) ) {
		$args['headers'] = array();
	}

	$args['headers'] = array_merge( besibau_github_headers( true ), $args['headers'] );
	$args['redirection'] = 5;

	return $args;
}
add_filter( 'http_request_args', 'besibau_github_download_args', 10, 2 );

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
add_filter( 'site_transient_update_themes', 'besibau_update_theme_transient' );

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

function besibau_github_update_admin_menu() {
	add_theme_page(
		__( 'BesiBau Update', 'besibau' ),
		__( 'BesiBau Update', 'besibau' ),
		'manage_options',
		'besibau-update',
		'besibau_github_update_admin_page'
	);
}
add_action( 'admin_menu', 'besibau_github_update_admin_menu' );

function besibau_github_update_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_POST['besibau_refresh_updates'] ) && check_admin_referer( 'besibau_refresh_updates' ) ) {
		delete_site_transient( 'besibau_github_release' );
		delete_site_transient( 'update_themes' );
		wp_update_themes();
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Theme update check refreshed.', 'besibau' ) . '</p></div>';
	}

	$theme       = wp_get_theme();
	$stylesheet  = get_stylesheet();
	$release     = besibau_github_latest_release();
	$version     = $release ? besibau_github_release_version( $release ) : '';
	$package     = $release ? besibau_github_release_package( $release ) : '';
	$has_update  = $version && version_compare( $version, $theme->get( 'Version' ), '>' );
	$repository  = besibau_github_repository_path();
	$update_data = get_site_transient( 'update_themes' );

	echo '<div class="wrap"><h1>' . esc_html__( 'BesiBau GitHub Update', 'besibau' ) . '</h1>';
	echo '<p>' . esc_html__( 'Use this page to verify what WordPress can see from GitHub.', 'besibau' ) . '</p>';
	echo '<table class="widefat striped" style="max-width:900px"><tbody>';
	echo '<tr><th>' . esc_html__( 'Theme slug', 'besibau' ) . '</th><td><code>' . esc_html( $stylesheet ) . '</code></td></tr>';
	echo '<tr><th>' . esc_html__( 'Installed version', 'besibau' ) . '</th><td><code>' . esc_html( $theme->get( 'Version' ) ) . '</code></td></tr>';
	echo '<tr><th>' . esc_html__( 'GitHub repository', 'besibau' ) . '</th><td><code>' . esc_html( $repository ? $repository : __( 'Not detected', 'besibau' ) ) . '</code></td></tr>';
	echo '<tr><th>' . esc_html__( 'Latest GitHub version', 'besibau' ) . '</th><td><code>' . esc_html( $version ? $version : __( 'Not found', 'besibau' ) ) . '</code></td></tr>';
	echo '<tr><th>' . esc_html__( 'Package URL', 'besibau' ) . '</th><td><code style="word-break:break-all">' . esc_html( $package ? $package : __( 'Not found', 'besibau' ) ) . '</code></td></tr>';
	echo '<tr><th>' . esc_html__( 'Update available', 'besibau' ) . '</th><td>' . esc_html( $has_update ? __( 'Yes', 'besibau' ) : __( 'No', 'besibau' ) ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'Update transient has BesiBau response', 'besibau' ) . '</th><td>' . esc_html( ( isset( $update_data->response ) && isset( $update_data->response[ $stylesheet ] ) ) ? __( 'Yes', 'besibau' ) : __( 'No', 'besibau' ) ) . '</td></tr>';
	echo '<tr><th>' . esc_html__( 'Last GitHub HTTP code', 'besibau' ) . '</th><td><code>' . esc_html( isset( $GLOBALS['besibau_github_last_response_code'] ) ? $GLOBALS['besibau_github_last_response_code'] : __( 'No response', 'besibau' ) ) . '</code></td></tr>';
	echo '<tr><th>' . esc_html__( 'Last GitHub error', 'besibau' ) . '</th><td><code style="word-break:break-all">' . esc_html( ! empty( $GLOBALS['besibau_github_last_error'] ) ? $GLOBALS['besibau_github_last_error'] : __( 'None', 'besibau' ) ) . '</code></td></tr>';
	echo '</tbody></table>';
	echo '<form method="post" style="margin-top:20px">';
	wp_nonce_field( 'besibau_refresh_updates' );
	submit_button( __( 'Refresh Theme Update Check', 'besibau' ), 'primary', 'besibau_refresh_updates' );
	echo '</form></div>';
}
