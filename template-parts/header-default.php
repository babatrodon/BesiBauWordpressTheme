<?php
/**
 * Default theme header shown when no Elementor header is assigned.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<div class="topbar"><div class="container topbar-in">
	<div class="tb-left">
		<span><i class="fa-solid fa-location-dot"></i> <?php echo esc_html( besibau_info( 'address' ) ); ?></span>
		<a href="mailto:<?php echo esc_attr( besibau_info( 'email' ) ); ?>"><i class="fa-solid fa-envelope"></i> <?php echo esc_html( besibau_info( 'email' ) ); ?></a>
		<a href="tel:<?php echo esc_attr( besibau_info( 'phone_href' ) ); ?>"><i class="fa-solid fa-phone"></i> <?php echo esc_html( besibau_info( 'phone' ) ); ?></a>
	</div>
	<div class="tb-soc">
		<a href="<?php echo esc_url( besibau_info( 'facebook' ) ); ?>" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
		<a href="<?php echo esc_url( besibau_info( 'instagram' ) ); ?>" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
		<a href="<?php echo esc_url( besibau_info( 'linkedin' ) ); ?>" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
	</div>
</div></div>

<header class="site-header"><div class="container header-in">
	<?php if ( has_custom_logo() ) : ?>
		<a class="brand brand-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<?php
			echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array(
				'class' => 'custom-logo',
				'alt'   => get_bloginfo( 'name' ),
			) );
			?>
		</a>
	<?php else : ?>
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<span class="logo-ic"><i class="fa-solid fa-trowel-bricks"></i></span>
			<span class="logo-text">Besi<b>Bau</b><small>Bau &middot; Sanierung &middot; Renovation</small></span>
		</a>
	<?php endif; ?>

	<nav class="main-nav">
		<?php foreach ( besibau_nav_items() as $item ) : ?>
			<a class="<?php echo besibau_is_active( $item[0] ) ? 'active' : ''; ?>" href="<?php echo esc_url( besibau_url( $item[0] ) ); ?>"><?php echo esc_html( $item[1] ); ?></a>
		<?php endforeach; ?>
	</nav>
	<a class="btn btn-gold nav-cta" href="<?php echo esc_url( besibau_url( 'kontakt' ) ); ?>">Offerte anfordern</a>
	<button class="burger" aria-label="Menü öffnen"><i class="fa-solid fa-bars"></i></button>
</div>
<nav class="mobile-nav">
	<?php foreach ( besibau_nav_items() as $item ) : ?>
		<a href="<?php echo esc_url( besibau_url( $item[0] ) ); ?>"><?php echo esc_html( $item[1] ); ?></a>
	<?php endforeach; ?>
	<a href="<?php echo esc_url( besibau_url( 'kontakt' ) ); ?>">Offerte anfordern</a>
</nav>
</header>
