<?php
/**
 * BesiBau theme functions.
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

	wp_enqueue_style(
		'besibau-fonts',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'besibau-fontawesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
		array(),
		'6.5.1'
	);
	wp_enqueue_style(
		'besibau-style',
		get_stylesheet_uri(),
		array( 'besibau-fonts', 'besibau-fontawesome' ),
		$theme_version
	);

	wp_register_script(
		'besibau-interactions',
		false,
		array(),
		$theme_version,
		true
	);
	wp_enqueue_script( 'besibau-interactions' );
	wp_add_inline_script(
		'besibau-interactions',
		"(function(){var b=document.querySelector('.burger');if(b){b.addEventListener('click',function(){document.body.classList.toggle('nav-open');});}document.querySelectorAll('.mobile-nav a').forEach(function(a){a.addEventListener('click',function(){document.body.classList.remove('nav-open');});});function run(el){var t=+el.getAttribute('data-count'),s=Math.max(1,Math.ceil(t/45)),c=0;var i=setInterval(function(){c+=s;if(c>=t){c=t;clearInterval(i);}el.textContent=c+(el.getAttribute('data-suffix')||'');},26);}var counters=document.querySelectorAll('[data-count]');if(!('IntersectionObserver' in window)){counters.forEach(run);return;}var io=new IntersectionObserver(function(e){e.forEach(function(x){if(x.isIntersecting){run(x.target);io.unobserve(x.target);}});});counters.forEach(function(el){io.observe(el);});})();"
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
	$choices = array(
		0 => __( 'Theme default', 'besibau' ),
	);

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

		$choices[ $template->ID ] = sprintf(
			/* translators: 1: template title, 2: Elementor template type. */
			__( '%1$s (%2$s)', 'besibau' ),
			$template->post_title,
			$type
		);
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

	$wp_customize->add_setting( 'besibau_elementor_header_template', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'besibau_elementor_header_template', array(
		'label'   => __( 'Header template', 'besibau' ),
		'section' => 'besibau_elementor_templates',
		'type'    => 'select',
		'choices' => $template_choices,
	) );

	$wp_customize->add_setting( 'besibau_elementor_footer_template', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'besibau_elementor_footer_template', array(
		'label'   => __( 'Footer template', 'besibau' ),
		'section' => 'besibau_elementor_templates',
		'type'    => 'select',
		'choices' => $template_choices,
	) );
}
add_action( 'customize_register', 'besibau_customize_register' );

/**
 * Company details (edit here in one place).
 */
function besibau_info( $key ) {
	$info = array(
		'phone'      => '+41 76 449 91 40',
		'phone_href' => '+41764499140',
		'email'      => 'info@besibau.ch',
		'address'    => 'Pilatusstrasse 6, 6312 Steinhausen (ZG)',
		'phone_de'   => '+49 178 6682002',
		'phone_de_h' => '+491786682002',
		'email_de'   => 'info@besibau.de',
		'address_de' => 'Zur Aumühle 39, 89257 Illertissen',
		'facebook'   => '#',
		'instagram'  => '#',
		'linkedin'   => '#',
	);
	return isset( $info[ $key ] ) ? $info[ $key ] : '';
}

/**
 * Contact form handler. Sends to the company email via wp_mail.
 */
function besibau_handle_contact() {
	$nonce = isset( $_POST['besibau_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['besibau_nonce'] ) ) : '';
	$back  = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	if ( ! wp_verify_nonce( $nonce, 'besibau_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'sent', 'error', $back ) . '#kontakt' );
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

	$to      = besibau_info( 'email' );
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( is_email( $email ) ) {
		$headers[] = 'Reply-To: ' . $name . ' <' . $email . '>';
	}

	wp_mail( $to, 'Website-Anfrage: ' . $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'sent', '1', $back ) . '#kontakt' );
	exit;
}
add_action( 'admin_post_nopriv_besibau_contact', 'besibau_handle_contact' );
add_action( 'admin_post_besibau_contact', 'besibau_handle_contact' );

require_once get_template_directory() . '/inc/github-theme-updater.php';
