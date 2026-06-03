<?php
/**
 * Editable theme footer location and document close.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! besibau_do_elementor_location( 'footer' ) && ! besibau_render_elementor_template( 'footer' ) ) {
	get_template_part( 'template-parts/footer', 'default' );
}

wp_footer();
?>
</body>
</html>
