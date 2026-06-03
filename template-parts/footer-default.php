<?php
/**
 * Default theme footer shown when no Elementor footer is assigned.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<section class="cta-band">
	<div class="container">
		<h2>Sie brauchen eine Offerte?</h2>
		<p>Kontaktieren Sie uns und lassen Sie sich beraten oder fordern Sie direkt eine unverbindliche Offerte an.</p>
		<a class="btn btn-gold" href="<?php echo esc_url( besibau_url( 'kontakt' ) ); ?>">Kontaktieren Sie uns</a>
	</div>
</section>

<footer class="site-footer">
	<div class="container footer-grid">
		<div class="f-col f-brand">
			<?php
			if ( has_custom_logo() ) {
				echo '<a class="footer-logo" href="' . esc_url( home_url( '/' ) ) . '" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . '">';
				echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array(
					'class' => 'custom-logo',
					'alt'   => get_bloginfo( 'name' ),
				) );
				echo '</a>';
			} else {
				echo '<span class="logo-text">Besi<b>Bau</b></span>';
			}
			?>
			<p>BesiBau ist Ihr Partner für hochwertiges Bauen, Sanieren und Renovieren in Zug und der Region.</p>
			<div class="f-soc">
				<a href="<?php echo esc_url( besibau_info( 'facebook' ) ); ?>" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
				<a href="<?php echo esc_url( besibau_info( 'instagram' ) ); ?>" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
				<a href="<?php echo esc_url( besibau_info( 'linkedin' ) ); ?>" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
			</div>
		</div>
		<div class="f-col">
			<h4>Renovierung</h4>
			<ul>
				<li><i class="fa-solid fa-angle-right"></i>Verputzarbeiten</li>
				<li><i class="fa-solid fa-angle-right"></i>Spachtel- &amp; Malerarbeiten</li>
				<li><i class="fa-solid fa-angle-right"></i>Trockenbau</li>
				<li><i class="fa-solid fa-angle-right"></i>Fliesenverlegung</li>
				<li><i class="fa-solid fa-angle-right"></i>Boden- &amp; Parkettverlegung</li>
				<li><i class="fa-solid fa-angle-right"></i>Entsorgung</li>
			</ul>
		</div>
		<div class="f-col">
			<h4>Sanierung</h4>
			<ul>
				<li><i class="fa-solid fa-angle-right"></i>Bausanierung</li>
				<li><i class="fa-solid fa-angle-right"></i>Altbausanierung</li>
				<li><i class="fa-solid fa-angle-right"></i>Wohnungssanierung</li>
				<li><i class="fa-solid fa-angle-right"></i>Badsanierung</li>
			</ul>
		</div>
		<div class="f-col">
			<h4>Kontakt</h4>
			<p><strong>BesiBau</strong><br><?php echo esc_html( besibau_info( 'address' ) ); ?></p>
			<p><a href="tel:<?php echo esc_attr( besibau_info( 'phone_href' ) ); ?>"><i class="fa-solid fa-phone" style="color:var(--gold);margin-right:8px"></i><?php echo esc_html( besibau_info( 'phone' ) ); ?></a><br>
			<a href="mailto:<?php echo esc_attr( besibau_info( 'email' ) ); ?>"><i class="fa-solid fa-envelope" style="color:var(--gold);margin-right:8px"></i><?php echo esc_html( besibau_info( 'email' ) ); ?></a></p>
		</div>
	</div>
	<div class="footer-bottom"><div class="container">
		<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> BesiBau. Alle Rechte vorbehalten.</span>
		<span>Bauen &middot; Sanieren &middot; Renovieren</span>
	</div></div>
</footer>
