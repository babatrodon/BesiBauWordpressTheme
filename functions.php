<?php
/**
 * BesiBau theme functions (multi-page, Elementor pre-loaded).
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! function_exists( 'besibau_setup' ) ) {
	function besibau_setup() {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'elementor' );
		add_theme_support( 'custom-logo', array(
			'height'      => 60,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		) );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
		register_nav_menus( array(
			'primary' => __( 'Hauptmenü', 'besibau' ),
		) );
	}
}
add_action( 'after_setup_theme', 'besibau_setup' );

/**
 * Register Elementor Pro Theme Builder locations.
 */
function besibau_register_elementor_locations( $elementor_theme_manager ) {
	$elementor_theme_manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'besibau_register_elementor_locations' );

/**
 * Styles and scripts.
 */
function besibau_assets() {
	$theme_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'besibau-fonts', 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap', array(), null );
	wp_enqueue_style( 'besibau-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1' );
	wp_enqueue_style( 'besibau-style', get_stylesheet_uri(), array( 'besibau-fonts', 'besibau-fontawesome' ), $theme_version );

	wp_register_script( 'besibau-interactions', false, array(), $theme_version, true );
	wp_enqueue_script( 'besibau-interactions' );
	wp_add_inline_script(
		'besibau-interactions',
		"(function(){var b=document.querySelector('.burger');function closeNav(){document.body.classList.remove('nav-open');if(b){b.setAttribute('aria-expanded','false');}}if(b){b.setAttribute('aria-expanded','false');b.addEventListener('click',function(){var open=document.body.classList.toggle('nav-open');b.setAttribute('aria-expanded',open?'true':'false');});}document.querySelectorAll('.mobile-nav a').forEach(function(a){a.addEventListener('click',closeNav);});var mq=window.matchMedia('(min-width:1001px)');function syncNav(e){if(e.matches){closeNav();}}if(mq.addEventListener){mq.addEventListener('change',syncNav);}else if(mq.addListener){mq.addListener(syncNav);}syncNav(mq);function run(el){if(el.getAttribute('data-counted')){return;}el.setAttribute('data-counted','1');var t=+el.getAttribute('data-count'),s=Math.max(1,Math.ceil(t/45)),c=0;var i=setInterval(function(){c+=s;if(c>=t){c=t;clearInterval(i);}el.textContent=c+(el.getAttribute('data-suffix')||'');},26);}var counters=document.querySelectorAll('[data-count]');if(!('IntersectionObserver' in window)){counters.forEach(run);return;}var io=new IntersectionObserver(function(e){e.forEach(function(x){if(x.isIntersecting){run(x.target);io.unobserve(x.target);}});});counters.forEach(function(el){io.observe(el);});})();"
	);
}
add_action( 'wp_enqueue_scripts', 'besibau_assets' );

/**
 * Elementor header/footer helpers.
 */
function besibau_do_elementor_location( $location ) {
	if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( $location ) ) {
		return true;
	}
	return false;
}

function besibau_render_elementor_template( $location ) {
	if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
		return false;
	}
	$template_id = absint( get_theme_mod( 'besibau_elementor_' . $location . '_template' ) );
	if ( ! $template_id || 'elementor_library' !== get_post_type( $template_id ) ) {
		return false;
	}
	echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return true;
}

function besibau_get_elementor_template_choices() {
	$choices = array( 0 => __( 'Theme default', 'besibau' ) );
	if ( ! post_type_exists( 'elementor_library' ) ) {
		return $choices;
	}
	$templates = get_posts( array(
		'post_type'      => 'elementor_library',
		'post_status'    => 'publish',
		'posts_per_page' => 100,
		'orderby'        => 'title',
		'order'          => 'ASC',
	) );
	foreach ( $templates as $template ) {
		$type = get_post_meta( $template->ID, '_elementor_template_type', true );
		$type = $type ? ucfirst( str_replace( '_', ' ', $type ) ) : __( 'Template', 'besibau' );
		$choices[ $template->ID ] = sprintf( __( '%1$s (%2$s)', 'besibau' ), $template->post_title, $type );
	}
	return $choices;
}

function besibau_customize_register( $wp_customize ) {
	$wp_customize->add_section( 'besibau_elementor_templates', array(
		'title'       => __( 'BesiBau Elementor', 'besibau' ),
		'description' => __( 'Choose saved Elementor templates to replace the theme header or footer. Elementor Pro Theme Builder locations are used automatically when assigned.', 'besibau' ),
		'priority'    => 35,
	) );
	$template_choices = besibau_get_elementor_template_choices();
	$wp_customize->add_setting( 'besibau_elementor_header_template', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'besibau_elementor_header_template', array(
		'label'   => __( 'Header template', 'besibau' ),
		'section' => 'besibau_elementor_templates',
		'type'    => 'select',
		'choices' => $template_choices,
	) );
	$wp_customize->add_setting( 'besibau_elementor_footer_template', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( 'besibau_elementor_footer_template', array(
		'label'   => __( 'Footer template', 'besibau' ),
		'section' => 'besibau_elementor_templates',
		'type'    => 'select',
		'choices' => $template_choices,
	) );
}
add_action( 'customize_register', 'besibau_customize_register' );

/**
 * Company details (Switzerland only). Edit here in one place.
 */
function besibau_info( $key ) {
	$info = array(
		'phone'      => '+41 76 552 55 55',
		'phone_href' => '+41765525555',
		'email'      => 'info@besibau.ch',
		'address'    => 'Sinserstrasse 67, 6330 Cham',
		'facebook'   => '#',
		'instagram'  => '#',
		'linkedin'   => '#',
	);
	return isset( $info[ $key ] ) ? $info[ $key ] : '';
}

/**
 * Pages this theme manages, navigation, and helpers.
 */
function besibau_pages() {
	return array(
		'ueber-uns'        => 'Über Uns',
		'dienstleistungen' => 'Dienstleistungen',
		'team'             => 'Unser Team',
		'unsere-arbeit'    => 'Unsere Arbeit',
		'kontakt'          => 'Kontakt',
	);
}

function besibau_url( $slug ) {
	if ( '' === $slug || 'home' === $slug ) {
		return home_url( '/' );
	}
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page->ID ) : home_url( '/' . $slug . '/' );
}

function besibau_nav_items() {
	return array(
		array( '', 'Home' ),
		array( 'ueber-uns', 'Über Uns' ),
		array( 'dienstleistungen', 'Dienstleistungen' ),
		array( 'team', 'Unser Team' ),
		array( 'unsere-arbeit', 'Unsere Arbeit' ),
		array( 'kontakt', 'Kontakt' ),
	);
}

function besibau_is_active( $slug ) {
	if ( '' === $slug ) {
		return is_front_page();
	}
	return is_page( $slug );
}

/**
 * Pre-load a page with the bundled Elementor design (local images). Never overwrites edits.
 */
function besibau_preload_elementor( $id, $slug ) {
	if ( ! $id || is_wp_error( $id ) ) {
		return;
	}
	$existing = get_post_meta( $id, '_elementor_data', true );
	if ( ! empty( $existing ) && '[]' !== $existing ) {
		return;
	}
	$file = get_template_directory() . '/inc/elementor/' . $slug . '.json';
	if ( ! file_exists( $file ) ) {
		return;
	}
	$json = file_get_contents( $file ); // phpcs:ignore
	if ( ! $json ) {
		return;
	}
	$json = str_replace( 'THEME_URI', get_template_directory_uri(), $json );
	update_post_meta( $id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $id, '_elementor_template_type', 'wp-page' );
	if ( defined( 'ELEMENTOR_VERSION' ) ) {
		update_post_meta( $id, '_elementor_version', ELEMENTOR_VERSION );
	}
	update_post_meta( $id, '_elementor_data', wp_slash( $json ) );
	if ( class_exists( '\\Elementor\\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}
}

/**
 * On activation: create the pages, pre-load the Elementor design, set the homepage,
 * enable pretty permalinks.
 */
function besibau_activate() {
	$home = get_page_by_path( 'home' );
	if ( $home ) {
		$home_id = $home->ID;
	} else {
		$home_id = wp_insert_post( array(
			'post_title'   => 'Home',
			'post_name'    => 'home',
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_content' => '',
		) );
	}
	besibau_preload_elementor( $home_id, 'home' );

	foreach ( besibau_pages() as $slug => $title ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			$new_id = wp_insert_post( array(
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_content' => '',
			) );
			besibau_preload_elementor( $new_id, $slug );
		} else {
			besibau_preload_elementor( $page->ID, $slug );
		}
	}

	if ( $home_id && ! is_wp_error( $home_id ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );
	}
	if ( '' === get_option( 'permalink_structure' ) ) {
		update_option( 'permalink_structure', '/%postname%/' );
	}
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'besibau_activate' );

/**
 * Safety net: pre-load once on admin load (covers updating files without a full re-activation).
 */
function besibau_maybe_preload() {
	if ( get_option( 'besibau_preloaded_v4' ) ) {
		return;
	}
	$home = get_page_by_path( 'home' );
	if ( $home ) {
		besibau_preload_elementor( $home->ID, 'home' );
	}
	foreach ( besibau_pages() as $slug => $title ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			besibau_preload_elementor( $page->ID, $slug );
		}
	}
	update_option( 'besibau_preloaded_v4', '1' );
}
add_action( 'admin_init', 'besibau_maybe_preload' );

/**
 * Self-healing asset URLs: if the theme folder name changes (e.g. installed under a
 * different directory), rewrite the image URLs stored inside the Elementor page data
 * to the current theme folder and clear the Elementor CSS cache.
 */
function besibau_fix_elementor_asset_urls( $value ) {
	if ( is_array( $value ) ) {
		foreach ( $value as $key => $item ) {
			$value[ $key ] = besibau_fix_elementor_asset_urls( $item );
		}
		return $value;
	}
	if ( is_string( $value ) && false !== strpos( $value, '/assets/img/' ) && false !== strpos( $value, '/wp-content/themes/' ) ) {
		$value = preg_replace(
			'#https?://[^"\'\s]*?/wp-content/themes/[^/]+/assets/img/#',
			get_template_directory_uri() . '/assets/img/',
			$value
		);
	}
	return $value;
}

function besibau_migrate_asset_urls() {
	$current = get_template_directory_uri();
	if ( get_option( 'besibau_assets_base' ) === $current ) {
		return;
	}

	$slugs = array_merge( array( 'home' ), array_keys( besibau_pages() ) );
	foreach ( $slugs as $slug ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			continue;
		}
		$data = get_post_meta( $page->ID, '_elementor_data', true );
		if ( empty( $data ) ) {
			continue;
		}
		$decoded = json_decode( $data, true );
		if ( ! is_array( $decoded ) ) {
			continue;
		}
		$fixed = besibau_fix_elementor_asset_urls( $decoded );
		update_post_meta( $page->ID, '_elementor_data', wp_slash( wp_json_encode( $fixed ) ) );
	}

	if ( class_exists( '\\Elementor\\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}

	update_option( 'besibau_assets_base', $current );
}
add_action( 'admin_init', 'besibau_migrate_asset_urls' );
add_action( 'after_switch_theme', 'besibau_migrate_asset_urls', 20 );

/**
 * Keep existing Elementor pages in sync with corrected company details.
 */
function besibau_update_contact_text_value( $value ) {
	if ( ! is_string( $value ) ) {
		return $value;
	}

	$replacements = array(
		'Schweiz: Pilatusstrasse 6, 6312 Steinhausen (ZG)' => besibau_info( 'address' ),
		'Pilatusstrasse 6, 6312 Steinhausen (ZG)'          => besibau_info( 'address' ),
		'Pilatusstrasse 6, 6312 Steinhausen'               => besibau_info( 'address' ),
		'+41 76 449 91 40'                                 => besibau_info( 'phone' ),
		'tel:+41764499140'                                 => 'tel:' . besibau_info( 'phone_href' ),
		'Schweiz: '                                        => '',
	);

	return strtr( $value, $replacements );
}

function besibau_is_removed_contact_item( $item ) {
	if ( ! is_array( $item ) || empty( $item['text'] ) ) {
		return false;
	}

	$text = (string) $item['text'];
	return false !== strpos( $text, 'Deutschland:' )
		|| false !== strpos( $text, 'Zur Aumühle' )
		|| false !== strpos( $text, 'Zur AumÃ¼hle' )
		|| false !== strpos( $text, 'Illertissen' )
		|| false !== strpos( $text, '+49 178 6682002' )
		|| false !== strpos( $text, 'info@besibau.de' );
}

function besibau_update_elementor_contact_data( $data ) {
	if ( is_string( $data ) ) {
		return besibau_update_contact_text_value( $data );
	}

	if ( ! is_array( $data ) ) {
		return $data;
	}

	foreach ( $data as $key => $value ) {
		if ( 'icon_list' === $key && is_array( $value ) ) {
			$value = array_values( array_filter( $value, function ( $item ) {
				return ! besibau_is_removed_contact_item( $item );
			} ) );
		}

		$data[ $key ] = besibau_update_elementor_contact_data( $value );
	}

	return $data;
}

function besibau_migrate_saved_contact_details() {
	if ( get_option( 'besibau_contact_details_migrated_v2' ) ) {
		return;
	}

	$posts = get_posts( array(
		'post_type'      => array( 'page', 'elementor_library' ),
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'meta_query'     => array(
			array(
				'key'     => '_elementor_data',
				'compare' => 'EXISTS',
			),
		),
	) );

	foreach ( $posts as $post_id ) {
		$raw = get_post_meta( $post_id, '_elementor_data', true );
		if ( ! is_string( $raw ) || '' === $raw ) {
			continue;
		}

		$decoded = json_decode( $raw, true );
		if ( is_array( $decoded ) ) {
			$updated = wp_json_encode( besibau_update_elementor_contact_data( $decoded ) );
			if ( $updated && $updated !== $raw ) {
				update_post_meta( $post_id, '_elementor_data', wp_slash( $updated ) );
			}
			continue;
		}

		$updated = besibau_update_contact_text_value( $raw );
		if ( $updated !== $raw ) {
			update_post_meta( $post_id, '_elementor_data', wp_slash( $updated ) );
		}
	}

	if ( class_exists( '\\Elementor\\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}

	update_option( 'besibau_contact_details_migrated_v2', '1' );
}
add_action( 'admin_init', 'besibau_migrate_saved_contact_details' );

/**
 * Contact form handler. Sends to the company email via wp_mail.
 */
function besibau_handle_contact() {
	$nonce = isset( $_POST['besibau_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['besibau_nonce'] ) ) : '';
	$back  = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	if ( ! wp_verify_nonce( $nonce, 'besibau_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'sent', 'error', $back ) );
		exit;
	}

	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : 'Anfrage über die Website';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	$body  = "Name: $name\n";
	$body .= "E-Mail: $email\n";
	$body .= "Telefon: $phone\n";
	$body .= "Betreff: $subject\n\n";
	$body .= "Nachricht:\n$message\n";

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( is_email( $email ) ) {
		$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
	}

	wp_mail( besibau_info( 'email' ), 'Website-Anfrage: ' . $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'sent', '1', $back ) );
	exit;
}
add_action( 'admin_post_nopriv_besibau_contact', 'besibau_handle_contact' );
add_action( 'admin_post_besibau_contact', 'besibau_handle_contact' );

/* ===== Reusable section renderers (fallback when Elementor is off) ===== */

function besibau_banner( $title, $sub, $crumb ) {
	?>
	<section class="page-hero"><div class="container">
		<h1><?php echo esc_html( $title ); ?></h1>
		<?php if ( $sub ) : ?><p><?php echo esc_html( $sub ); ?></p><?php endif; ?>
		<div class="crumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span>/</span> <?php echo esc_html( $crumb ); ?></div>
	</div></section>
	<?php
}

function besibau_sec_head( $kick, $title, $lead = '', $center = true ) {
	$cls = $center ? ' center' : '';
	echo '<div class="container' . esc_attr( $cls ) . '"><span class="kicker">' . esc_html( $kick ) . '</span><h2 class="sec-title">' . wp_kses_post( $title ) . '</h2><div class="accent-line"></div>';
	if ( $lead ) {
		echo '<p class="lead">' . wp_kses_post( $lead ) . '</p>';
	}
	echo '</div>';
}

function besibau_services( $set = 'renovation' ) {
	if ( 'sanierung' === $set ) {
		$items = array(
			array( 'fa-building', 'Bausanierung', 'Gebäude rundum', 'Wählen Sie BesiBau für alle Gebäuderenovierungen und erleben Sie den Unterschied einer professionellen Umsetzung.' ),
			array( 'fa-house-chimney-crack', 'Altbausanierung', 'Behutsam erneuert', 'Ihr Altbau braucht eine Erneuerung? Kein Problem, vereinbaren Sie noch heute einen Termin mit uns.' ),
			array( 'fa-couch', 'Wohnungssanierung', 'Massgeschneidert', 'Jedes Zuhause ist einzigartig. Wir erstellen Renovierungspläne nach Ihren Bedürfnissen und Vorlieben.' ),
			array( 'fa-bath', 'Badsanierung', 'Moderne Oase', 'Wir verwandeln Ihr veraltetes Badezimmer in eine moderne, wohnliche Oase zum Wohlfühlen.' ),
		);
	} else {
		$items = array(
			array( 'fa-trowel', 'Verputzarbeiten', 'Innen &amp; Fassade', 'Saubere, präzise Verputzarbeiten für glatte, langlebige Oberflächen.' ),
			array( 'fa-paint-roller', 'Spachtel- &amp; Malerarbeiten', 'Frische Räume', 'Hochwertige, umweltfreundliche Farben und makellose Anstriche.' ),
			array( 'fa-layer-group', 'Trockenbau', 'Wände &amp; Decken', 'Trennwände, Decken und Innenausbau, flexibel und sauber umgesetzt.' ),
			array( 'fa-border-all', 'Fliesenverlegung', 'Bad, Küche &amp; Boden', 'Exakte Fliesenarbeiten mit sauberen Fugen und langlebigem Finish.' ),
			array( 'fa-ruler-combined', 'Boden- &amp; Parkettverlegung', 'Warm &amp; wohnlich', 'Parkett, Laminat und Bodenbeläge fachgerecht und sauber verlegt.' ),
			array( 'fa-truck-ramp-box', 'Entsorgung', 'Sauber &amp; korrekt', 'Fachgerechte Entsorgung von Bauschutt und alten Materialien.' ),
		);
	}
	echo '<div class="container srv-grid">';
	foreach ( $items as $s ) {
		echo '<div class="srv-card"><div class="srv-ic"><i class="fa-solid ' . esc_attr( $s[0] ) . '"></i></div>';
		echo '<h3>' . wp_kses_post( $s[1] ) . '</h3><span class="srv-sub">' . wp_kses_post( $s[2] ) . '</span>';
		echo '<p>' . wp_kses_post( $s[3] ) . '</p></div>';
	}
	echo '</div>';
}

function besibau_stats() {
	$stats = array(
		array( 450, 'Abgeschlossene Projekte' ),
		array( 15, 'Fachmänner im Team' ),
		array( 12, 'Jahre Erfahrung' ),
		array( 400, 'Zufriedene Kunden' ),
	);
	echo '<section class="section stats"><div class="container"><div class="stats-grid">';
	foreach ( $stats as $s ) {
		echo '<div class="stat"><span class="num" data-count="' . esc_attr( $s[0] ) . '" data-suffix="+">0</span><label>' . esc_html( $s[1] ) . '</label><div class="dot"></div></div>';
	}
	echo '</div></div></section>';
}

function besibau_projects() {
	$u = get_template_directory_uri();
	$projects = array(
		array( 'proj1.jpg', 'Sanierung', 'Wohnungssanierung Zug', 'Komplette Sanierung mit Verputz-, Maler- und Bodenarbeiten.' ),
		array( 'proj2.jpg', 'Badsanierung', 'Badsanierung Steinhausen', 'Modernes Bad mit präziser Fliesen- und Trockenbauarbeit.' ),
		array( 'proj3.jpg', 'Verputz', 'Innenausbau Cham', 'Trockenbau, Spachtel- und Malerarbeiten für einen Neubau.' ),
		array( 'proj4.jpg', 'Fassade', 'Fassadenrenovierung Baar', 'Verputz und Anstrich für eine langlebige, schöne Fassade.' ),
		array( 'proj5.jpg', 'Malerei', 'Malerarbeiten Hünenberg', 'Frische Farben und makellose Anstriche für mehrere Wohnungen.' ),
		array( 'proj6.jpg', 'Boden', 'Parkett Rotkreuz', 'Warmer Parkettboden, fachgerecht und sauber verlegt.' ),
	);
	echo '<div class="container proj-grid">';
	foreach ( $projects as $p ) {
		echo '<div class="proj-card"><div class="proj-img"><span class="tag">' . esc_html( $p[1] ) . '</span>';
		echo '<img src="' . esc_url( $u . '/assets/img/' . $p[0] ) . '" alt="' . esc_attr( $p[2] ) . '"></div>';
		echo '<div class="proj-body"><h3>' . esc_html( $p[2] ) . '</h3><p>' . esc_html( $p[3] ) . '</p></div></div>';
	}
	echo '</div>';
}

function besibau_team() {
	$leads = array(
		array( 'fa-user-tie', 'Besnik Spahiu', 'CEO' ),
		array( 'fa-user-gear', 'Lorent Salihu', 'CTO' ),
		array( 'fa-clipboard-check', 'Fadil Maliqi', 'Projektleiter' ),
	);
	echo '<div class="container team-grid leads">';
	foreach ( $leads as $m ) {
		echo '<div class="team-card"><div class="team-ph"><i class="fa-solid ' . esc_attr( $m[0] ) . '"></i></div><h3>' . esc_html( $m[1] ) . '</h3><span>' . esc_html( $m[2] ) . '</span></div>';
	}
	echo '</div>';
	$crew = array( 'Argjend', 'Alfos', 'Panajot', 'Avni', 'Eldion', 'Emin', 'Niko', 'Xavit', 'Blerim', 'Dani', 'Andre', 'Muharrem' );
	echo '<div class="container team-grid">';
	foreach ( $crew as $name ) {
		echo '<div class="team-card"><div class="team-ph sm"><i class="fa-solid fa-person-digging"></i></div><h3>' . esc_html( $name ) . '</h3><span>Mitarbeiter</span></div>';
	}
	echo '</div>';
}

require_once get_template_directory() . '/inc/github-theme-updater.php';
