<?php
/**
 * Pages. Editable with Elementor (the_content). Shows the built-in section as a
 * fallback until you build the page in Elementor.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$u    = get_template_directory_uri();
$slug = get_post_field( 'post_name', get_queried_object_id() );

ob_start();
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		the_content();
	}
}
$besibau_content = ob_get_clean();
echo $besibau_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

$besibau_plain = trim( wp_strip_all_tags( $besibau_content ) );
$besibau_empty = ( '' === $besibau_plain
	&& false === strpos( $besibau_content, '<img' )
	&& false === strpos( $besibau_content, '<iframe' )
	&& false === strpos( $besibau_content, 'background-image' ) );
$besibau_editing = ( isset( $_GET['elementor-preview'] ) || ( isset( $_GET['action'] ) && 'elementor' === $_GET['action'] ) );

if ( ! $besibau_empty || $besibau_editing ) {
	get_footer();
	return;
}

if ( 'ueber-uns' === $slug ) :
	besibau_banner( 'Über Uns', 'Lernen Sie das Team und die Werte hinter BesiBau kennen.', 'Über Uns' );
	?>
	<section class="section">
		<div class="container about-grid">
			<div class="about-imgs">
				<img class="ai-main" src="<?php echo esc_url( $u . '/assets/img/about1.jpg' ); ?>" alt="BesiBau bei der Arbeit">
				<img class="ai-sub" src="<?php echo esc_url( $u . '/assets/img/about2.jpg' ); ?>" alt="Innenausbau">
			</div>
			<div class="about-text">
				<span class="kicker">Wir machen es möglich</span>
				<h2 class="sec-title">Erfahrung, auf die Sie sich verlassen können</h2><div class="accent-line"></div>
				<p>Unser Team bei BesiBau besteht aus hochmotivierten Fachleuten, die jahrelange Erfahrung und handwerkliches Geschick mitbringen. Bei unseren Dienstleistungen legen wir grossen Wert auf eine zuverlässige und qualitativ hochwertige Ausführung.</p>
				<p>Wir sind stolz darauf, unseren Kunden stets zuverlässige und effektive Lösungen anzubieten, damit alle Projekte reibungslos und erfolgreich abgeschlossen werden. Haussanierung und Wohnungsrenovierung in Zug, alles aus einer Hand.</p>
				<a class="btn btn-gold" href="<?php echo esc_url( besibau_url( 'kontakt' ) ); ?>">Projekt besprechen</a>
			</div>
		</div>
	</section>
	<?php besibau_stats(); ?>
	<section class="section soft">
		<?php besibau_sec_head( 'Unsere Werte', 'Wofür wir stehen' ); ?>
		<div class="container trio-grid" style="margin-top:0">
			<div class="trio-card"><div class="trio-ic"><i class="fa-solid fa-helmet-safety"></i></div><h3>Fachkompetenz</h3><p>Langjährige Expertise in allen Bau- und Renovationsarbeiten.</p></div>
			<div class="trio-card feat"><div class="trio-ic"><i class="fa-solid fa-clock"></i></div><h3>Termintreue</h3><p>Saubere, effiziente und pünktliche Umsetzung jedes Projekts.</p></div>
			<div class="trio-card"><div class="trio-ic"><i class="fa-solid fa-handshake-angle"></i></div><h3>Kundennähe</h3><p>Individuelle Beratung, denn jedes Projekt ist einzigartig.</p></div>
		</div>
	</section>
	<?php

elseif ( 'dienstleistungen' === $slug ) :
	besibau_banner( 'Dienstleistungen', 'Vom Verputz bis zur kompletten Sanierung, alles aus einer Hand.', 'Dienstleistungen' );
	?>
	<section class="section">
		<?php besibau_sec_head( 'Renovierung', 'Handwerk in jedem Detail', 'Präzise ausgeführte Arbeiten für Innenräume, Bäder und Fassaden.' ); ?>
		<?php besibau_services( 'renovation' ); ?>
	</section>
	<section class="section soft">
		<?php besibau_sec_head( 'Sanierung', 'Neues Leben für Ihre Immobilie', 'Massgeschneiderte Sanierungen, wir arbeiten eng mit Ihnen zusammen.' ); ?>
		<?php besibau_services( 'sanierung' ); ?>
	</section>
	<?php

elseif ( 'team' === $slug ) :
	besibau_banner( 'Unser Team', 'Die Menschen, die Ihr Projekt zum Erfolg bringen.', 'Unser Team' );
	?>
	<section class="section">
		<?php besibau_sec_head( 'Unser Team', 'Die Menschen hinter BesiBau' ); ?>
		<?php besibau_team(); ?>
	</section>
	<?php

elseif ( 'unsere-arbeit' === $slug ) :
	besibau_banner( 'Unsere Arbeit', 'Referenzen aus Zug und der Region.', 'Unsere Arbeit' );
	?>
	<section class="section">
		<?php besibau_sec_head( 'Referenzen', 'Projekte, die für sich sprechen', 'Ein Auszug aus unseren Arbeiten und unserem Leistungsumfang.' ); ?>
		<?php besibau_projects(); ?>
	</section>
	<?php

elseif ( 'kontakt' === $slug ) :
	besibau_banner( 'Kontakt', 'Wir freuen uns auf Ihre Anfrage, melden Sie sich bei uns.', 'Kontakt' );
	?>
	<section class="section">
		<div class="container contact-grid">
			<div class="contact-info">
				<span class="kicker">Nehmen Sie Kontakt auf</span>
				<h2 class="sec-title">Wir sind für Sie da</h2><div class="accent-line"></div>
				<p>Schicken Sie uns Ihre Anfrage über das Formular. Wir leiten sie an den richtigen Ansprechpartner weiter und melden uns so schnell wie möglich bei Ihnen zurück.</p>
				<ul class="ci-list">
					<li><i class="fa-solid fa-location-dot"></i><div><strong>Adresse</strong><br><?php echo esc_html( besibau_info( 'address' ) ); ?></div></li>
					<li><i class="fa-solid fa-phone"></i><div><strong>Telefon</strong><br><a href="tel:<?php echo esc_attr( besibau_info( 'phone_href' ) ); ?>"><?php echo esc_html( besibau_info( 'phone' ) ); ?></a></div></li>
					<li><i class="fa-solid fa-envelope"></i><div><strong>E-Mail</strong><br><a href="mailto:<?php echo esc_attr( besibau_info( 'email' ) ); ?>"><?php echo esc_html( besibau_info( 'email' ) ); ?></a></div></li>
				</ul>
			</div>
			<form class="contact-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
				<input type="hidden" name="action" value="besibau_contact">
				<?php wp_nonce_field( 'besibau_contact', 'besibau_nonce' ); ?>
				<?php if ( isset( $_GET['sent'] ) && '1' === $_GET['sent'] ) : ?>
					<div class="form-note ok">Vielen Dank! Ihre Anfrage wurde verschickt. Wir melden uns in Kürze.</div>
				<?php endif; ?>
				<div class="cf-row"><input type="text" name="name" placeholder="Ihr Name*" required><input type="email" name="email" placeholder="E-Mail*" required></div>
				<div class="cf-row"><input type="tel" name="phone" placeholder="Telefon"><input type="text" name="subject" placeholder="Betreff"></div>
				<textarea name="message" placeholder="Ihre Nachricht*" required></textarea>
				<button class="btn btn-gold" type="submit">Anfrage verschicken</button>
			</form>
		</div>
	</section>
	<section class="map-wrap">
		<iframe title="Karte" loading="lazy" src="https://www.google.com/maps?q=Sinserstrasse%2067,%206330%20Cham&output=embed"></iframe>
	</section>
	<?php

else :
	besibau_banner( get_the_title(), '', get_the_title() );
	?>
	<section class="section"><div class="container narrow"><div class="entry-content"></div></div></section>
	<?php

endif;

get_footer();
