<?php
/**
 * Home. Editable with Elementor (the_content). Shows the built-in design as a
 * fallback until you build the page in Elementor.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$u = get_template_directory_uri();

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

if ( $besibau_empty && ! $besibau_editing ) :
?>

<section class="hero">
	<div class="container"><div class="hero-in">
		<span class="eyebrow">Bauen &middot; Sanieren &middot; Renovieren</span>
		<h1>Hochwertiges Bauen, Sanieren und Renovieren in Zug und Region</h1>
		<p>Ihr Partner für Verputz-, Maler- und Trockenbauarbeiten, Fliesen- und Bodenverlegung sowie komplette Bau- und Wohnungssanierungen. Alles aus einer Hand.</p>
		<div class="hero-cta">
			<a class="btn btn-gold" href="<?php echo esc_url( besibau_url( 'dienstleistungen' ) ); ?>">Zu den Dienstleistungen</a>
			<a class="btn btn-ghost" href="tel:<?php echo esc_attr( besibau_info( 'phone_href' ) ); ?>"><i class="fa-solid fa-phone"></i> <?php echo esc_html( besibau_info( 'phone' ) ); ?></a>
		</div>
	</div></div>
</section>

<section class="section welcome">
	<div class="container narrow center">
		<span class="kicker">Willkommen bei BesiBau</span>
		<h2 class="sec-title">Ihr Baupartner in Steinhausen, Zug und der Region</h2>
		<div class="accent-line"></div>
		<p>BesiBau steht für ein breites Leistungsspektrum im Bau-, Sanierungs- und Renovationsbereich. Unser Fokus liegt darauf, <strong>Qualität, Funktionalität und Ästhetik</strong> zu vereinen. Jede Leistung führen wir mit höchster Präzision, Termintreue und <strong>fairen Preisen</strong> aus.</p>
	</div>
</section>

<section class="section soft">
	<div class="container">
		<div class="center"><span class="kicker">So arbeiten wir</span><h2 class="sec-title">In vier Schritten zum Ziel</h2><div class="accent-line" style="margin:0 auto"></div></div>
		<div class="steps-grid">
			<div class="step"><div class="step-ic"><i class="fa-solid fa-comments"></i></div><span class="step-num">1</span><h3>Kontakt &amp; Beratung</h3><p>Wir hören zu und beraten Sie ehrlich und individuell.</p></div>
			<div class="step"><div class="step-ic"><i class="fa-solid fa-pen-ruler"></i></div><span class="step-num">2</span><h3>Planung des Projekts</h3><p>Massgeschneiderte Lösung mit klarer Offerte und Zeitplan.</p></div>
			<div class="step"><div class="step-ic"><i class="fa-solid fa-trowel-bricks"></i></div><span class="step-num">3</span><h3>Saubere Ausführung</h3><p>Termingerechte Umsetzung durch unser erfahrenes Fachteam.</p></div>
			<div class="step"><div class="step-ic"><i class="fa-solid fa-house-chimney"></i></div><span class="step-num">4</span><h3>Ihr neues Zuhause</h3><p>Übergabe eines Ergebnisses, das überzeugt und Bestand hat.</p></div>
		</div>
	</div>
</section>

<section class="section">
	<div class="container about-grid">
		<div class="about-imgs">
			<img class="ai-main" src="<?php echo esc_url( $u . '/assets/img/about1.jpg' ); ?>" alt="BesiBau Malerarbeiten">
			<img class="ai-sub" src="<?php echo esc_url( $u . '/assets/img/about2.jpg' ); ?>" alt="BesiBau Verputzarbeiten">
		</div>
		<div class="about-text">
			<span class="kicker">Über BesiBau</span>
			<h2 class="sec-title">Ihr zuverlässiger Partner</h2><div class="accent-line"></div>
			<p>Unser Team besteht aus hochmotivierten Fachleuten mit jahrelanger Erfahrung und handwerklichem Geschick. Wir arbeiten für <strong>Privatkunden, Unternehmen und Verwaltungen</strong> und setzen jedes Projekt sauber und termingerecht um.</p>
			<div class="vp-grid">
				<div class="vp"><i class="fa-solid fa-helmet-safety"></i><span>Erfahrene Fachmänner</span></div>
				<div class="vp"><i class="fa-solid fa-headset"></i><span>Erstklassige Beratung</span></div>
				<div class="vp"><i class="fa-solid fa-face-smile"></i><span>Zufriedene Kundschaft</span></div>
				<div class="vp"><i class="fa-solid fa-leaf"></i><span>Langlebig &amp; Nachhaltig</span></div>
			</div>
			<a class="btn btn-gold" href="<?php echo esc_url( besibau_url( 'ueber-uns' ) ); ?>">Mehr über uns</a>
		</div>
	</div>
</section>

<section class="section soft">
	<?php besibau_sec_head( 'Unsere Dienstleistungen', 'Alles rund um Renovierung und Sanierung', 'Ob Verputz, Malerarbeiten, Trockenbau oder komplette Sanierung. Wir sind Ihr vielseitiger Partner.' ); ?>
	<?php besibau_services( 'renovation' ); ?>
	<div class="container center"><a class="btn btn-dark" href="<?php echo esc_url( besibau_url( 'dienstleistungen' ) ); ?>">Alle Dienstleistungen</a></div>
</section>

<?php besibau_stats(); ?>

<section class="why">
	<div class="container"><div class="why-card">
		<span class="kicker">Warum BesiBau?</span>
		<h2 class="sec-title">Die richtige Wahl für Ihr Projekt</h2><div class="accent-line"></div>
		<p class="lead">Die Wahl der richtigen Baufirma entscheidet über den Erfolg. Mit BesiBau setzen Sie auf Zuverlässigkeit, Fachkompetenz und Kundennähe.</p>
		<ul class="check">
			<li><i class="fa-solid fa-check"></i><div><strong>Regionale Verankerung</strong><span>Wir kennen die Anforderungen im Raum Zug bestens.</span></div></li>
			<li><i class="fa-solid fa-check"></i><div><strong>Termingerechte Arbeit</strong><span>Wir arbeiten präzise, sauber und effizient.</span></div></li>
			<li><i class="fa-solid fa-check"></i><div><strong>Nachhaltigkeit</strong><span>Einsatz hochwertiger, langlebiger Materialien.</span></div></li>
			<li><i class="fa-solid fa-check"></i><div><strong>Alles aus einer Hand</strong><span>Von Verputz bis Sanierung, ein Ansprechpartner.</span></div></li>
		</ul>
		<a class="btn btn-gold" href="<?php echo esc_url( besibau_url( 'kontakt' ) ); ?>">Kontaktieren Sie uns</a>
	</div></div>
</section>

<section class="section trio">
	<div class="container center"><span class="kicker">Unser Versprechen</span><h2 class="sec-title">Darauf können Sie zählen</h2><div class="accent-line" style="margin:0 auto"></div></div>
	<div class="container trio-grid">
		<div class="trio-card"><div class="trio-ic"><i class="fa-solid fa-award"></i></div><h3>Hervorragende Qualität</h3><p>Hochwertige Materialien, moderne Technik und sorgfältige Verarbeitung für perfekte Ergebnisse.</p></div>
		<div class="trio-card feat"><div class="trio-ic"><i class="fa-solid fa-tags"></i></div><h3>Faire Preise</h3><p>Transparente Offerten und faire Konditionen, ohne Kompromisse bei Qualität und Zuverlässigkeit.</p></div>
		<div class="trio-card"><div class="trio-ic"><i class="fa-solid fa-recycle"></i></div><h3>Langlebig &amp; Nachhaltig</h3><p>Nachhaltige Materialien und bewährte Techniken, die den Wert Ihrer Immobilie erhalten.</p></div>
	</div>
</section>

<section class="section">
	<?php besibau_sec_head( 'Unsere Projekte', 'Ein Einblick in unsere Arbeit', 'Lassen Sie uns gemeinsam Ihre Ideen verwirklichen.' ); ?>
	<div class="container proj-grid">
		<?php
		$teasers = array(
			array( 'proj1.jpg', 'Sanierung', 'Wohnungssanierung Zug', 'Verputz-, Maler- und Bodenarbeiten.' ),
			array( 'proj2.jpg', 'Badsanierung', 'Badsanierung Steinhausen', 'Präzise Fliesen- und Trockenbauarbeit.' ),
			array( 'proj3.jpg', 'Verputz', 'Innenausbau Cham', 'Trockenbau, Spachtel- und Malerarbeiten.' ),
		);
		foreach ( $teasers as $p ) {
			echo '<div class="proj-card"><div class="proj-img"><span class="tag">' . esc_html( $p[1] ) . '</span>';
			echo '<img src="' . esc_url( $u . '/assets/img/' . $p[0] ) . '" alt="' . esc_attr( $p[2] ) . '"></div>';
			echo '<div class="proj-body"><h3>' . esc_html( $p[2] ) . '</h3><p>' . esc_html( $p[3] ) . '</p></div></div>';
		}
		?>
	</div>
	<div class="container center"><a class="btn btn-gold" href="<?php echo esc_url( besibau_url( 'unsere-arbeit' ) ); ?>">Zu den Projekten</a></div>
</section>

<?php
endif;
get_footer();
