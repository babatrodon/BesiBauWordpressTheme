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
		<a class="btn btn-gold" href="#kontakt">Kontaktieren Sie uns</a>
	</div>
</section>

<footer class="site-footer">
	<div class="container footer-grid">
		<div class="f-col f-brand">
			<?php if ( has_custom_logo() ) { the_custom_logo(); } else { echo '<span class="logo-text">Besi<b>Bau</b></span>'; } ?>
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
			<h4>Standorte</h4>
			<p><strong>Schweiz</strong><br><?php echo esc_html( besibau_info( 'address' ) ); ?><br>
			<a href="tel:<?php echo esc_attr( besibau_info( 'phone_href' ) ); ?>"><?php echo esc_html( besibau_info( 'phone' ) ); ?></a><br>
			<a href="mailto:<?php echo esc_attr( besibau_info( 'email' ) ); ?>"><?php echo esc_html( besibau_info( 'email' ) ); ?></a></p>
			<p><strong>Deutschland</strong><br><?php echo esc_html( besibau_info( 'address_de' ) ); ?><br>
			<a href="tel:<?php echo esc_attr( besibau_info( 'phone_de_h' ) ); ?>"><?php echo esc_html( besibau_info( 'phone_de' ) ); ?></a><br>
			<a href="mailto:<?php echo esc_attr( besibau_info( 'email_de' ) ); ?>"><?php echo esc_html( besibau_info( 'email_de' ) ); ?></a></p>
		</div>
	</div>
	<div class="footer-bottom"><div class="container">
		<span>&copy; <?php echo esc_html( date( 'Y' ) ); ?> BesiBau. Alle Rechte vorbehalten.</span>
		<span>Bauen &middot; Sanieren &middot; Renovieren</span>
	</div></div>
</footer>
