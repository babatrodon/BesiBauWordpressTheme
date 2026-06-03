<?php
/**
 * Front page: the full one-page BesiBau design.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$u = get_template_directory_uri();
?>

<section class="hero" id="home">
	<div class="container"><div class="hero-in">
		<span class="eyebrow">Bauen &middot; Sanieren &middot; Renovieren</span>
		<h1>Hochwertiges Bauen, Sanieren und Renovieren in Zug und Region</h1>
		<p>Ihr Partner für Verputz-, Maler- und Trockenbauarbeiten, Fliesen- und Bodenverlegung sowie komplette Bau- und Wohnungssanierungen. Alles aus einer Hand.</p>
		<div class="hero-cta">
			<a class="btn btn-gold" href="#leistungen">Zu den Dienstleistungen</a>
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

<section class="section" id="ueber-uns">
	<div class="container about-grid">
		<div class="about-imgs">
			<img class="ai-main" src="<?php echo esc_url( $u . '/assets/img/about1.jpg' ); ?>" alt="BesiBau Malerarbeiten">
			<img class="ai-sub" src="<?php echo esc_url( $u . '/assets/img/about2.jpg' ); ?>" alt="BesiBau Verputzarbeiten">
		</div>
		<div class="about-text">
			<span class="kicker">Über BesiBau</span>
			<h2 class="sec-title">Ihr zuverlässiger Partner</h2><div class="accent-line"></div>
			<p>Unser Team besteht aus hochmotivierten Fachleuten mit jahrelanger Erfahrung und handwerklichem Geschick. Wir arbeiten für <strong>Privatkunden, Unternehmen und Verwaltungen</strong> und setzen jedes Projekt sauber und termingerecht um.</p>
			<p>Von der Beratung über die Planung bis zur Ausführung sind wir an Ihrer Seite. Alles aus einer Hand.</p>
			<div class="vp-grid">
				<div class="vp"><i class="fa-solid fa-helmet-safety"></i><span>Erfahrene Fachmänner</span></div>
				<div class="vp"><i class="fa-solid fa-headset"></i><span>Erstklassige Beratung</span></div>
				<div class="vp"><i class="fa-solid fa-face-smile"></i><span>Zufriedene Kundschaft</span></div>
				<div class="vp"><i class="fa-solid fa-leaf"></i><span>Langlebig &amp; Nachhaltig</span></div>
			</div>
			<a class="btn btn-gold" href="#kontakt">Projekt besprechen</a>
		</div>
	</div>
</section>

<section class="section soft" id="leistungen">
	<div class="container center"><span class="kicker">Unsere Dienstleistungen</span><h2 class="sec-title">Alles rund um Renovierung und Sanierung</h2><div class="accent-line"></div><p class="lead">Ob Verputz, Malerarbeiten, Trockenbau oder komplette Sanierung. Wir sind Ihr vielseitiger Partner.</p></div>
	<div class="container srv-grid">
		<?php
		$services = array(
			array( 'fa-trowel', 'Verputzarbeiten', 'Innen &amp; Fassade', 'Saubere, präzise Verputzarbeiten für glatte, langlebige Oberflächen.' ),
			array( 'fa-paint-roller', 'Spachtel- &amp; Malerarbeiten', 'Frische Räume', 'Hochwertige, umweltfreundliche Farben und makellose Anstriche.' ),
			array( 'fa-layer-group', 'Trockenbau', 'Wände &amp; Decken', 'Trennwände, Decken und Innenausbau, flexibel und sauber umgesetzt.' ),
			array( 'fa-border-all', 'Fliesenverlegung', 'Bad, Küche &amp; Boden', 'Exakte Fliesenarbeiten mit sauberen Fugen und langlebigem Finish.' ),
			array( 'fa-ruler-combined', 'Boden- &amp; Parkettverlegung', 'Warm &amp; wohnlich', 'Parkett, Laminat und Bodenbeläge fachgerecht und sauber verlegt.' ),
			array( 'fa-building-circle-check', 'Sanierung', 'Bau &middot; Wohnung &middot; Bad', 'Komplette Sanierungen aus einer Hand, termingerecht und fair.' ),
		);
		foreach ( $services as $s ) {
			echo '<div class="srv-card"><div class="srv-ic"><i class="fa-solid ' . esc_attr( $s[0] ) . '"></i></div>';
			echo '<h3>' . wp_kses_post( $s[1] ) . '</h3><span class="srv-sub">' . wp_kses_post( $s[2] ) . '</span>';
			echo '<p>' . wp_kses_post( $s[3] ) . '</p></div>';
		}
		?>
	</div>
</section>

<section class="section stats">
	<div class="container"><div class="stats-grid">
		<div class="stat"><span class="num" data-count="450" data-suffix="+">0</span><label>Abgeschlossene Projekte</label><div class="dot"></div></div>
		<div class="stat"><span class="num" data-count="15" data-suffix="+">0</span><label>Fachmänner im Team</label><div class="dot"></div></div>
		<div class="stat"><span class="num" data-count="12" data-suffix="+">0</span><label>Jahre Erfahrung</label><div class="dot"></div></div>
		<div class="stat"><span class="num" data-count="400" data-suffix="+">0</span><label>Zufriedene Kunden</label><div class="dot"></div></div>
	</div></div>
</section>

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
		<a class="btn btn-gold" href="#kontakt">Kontaktieren Sie uns</a>
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

<section class="section" id="team">
	<div class="container center"><span class="kicker">Unser Team</span><h2 class="sec-title">Die Menschen hinter BesiBau</h2><div class="accent-line"></div></div>
	<div class="container team-grid leads">
		<div class="team-card"><div class="team-ph"><i class="fa-solid fa-user-tie"></i></div><h3>Besnik Spahiu</h3><span>CEO</span></div>
		<div class="team-card"><div class="team-ph"><i class="fa-solid fa-user-gear"></i></div><h3>Lorent Salihu</h3><span>CTO</span></div>
		<div class="team-card"><div class="team-ph"><i class="fa-solid fa-clipboard-check"></i></div><h3>Fadil Maliqi</h3><span>Projektleiter</span></div>
	</div>
	<div class="container team-grid">
		<?php
		$crew = array( 'Argjend', 'Alfos', 'Panajot', 'Avni', 'Eldion', 'Emin', 'Niko', 'Xavit', 'Blerim', 'Dani', 'Andre', 'Muharrem' );
		foreach ( $crew as $name ) {
			echo '<div class="team-card"><div class="team-ph sm"><i class="fa-solid fa-person-digging"></i></div><h3>' . esc_html( $name ) . '</h3><span>Mitarbeiter</span></div>';
		}
		?>
	</div>
</section>

<section class="section soft" id="projekte">
	<div class="container center"><span class="kicker">Unsere Arbeit</span><h2 class="sec-title">Ein Einblick in unsere Projekte</h2><div class="accent-line"></div><p class="lead">Lassen Sie uns gemeinsam Ihre Ideen verwirklichen. Ob Renovierung, Neugestaltung oder Sanierung.</p></div>
	<div class="container proj-grid">
		<?php
		$projects = array(
			array( 'proj1.jpg', 'Sanierung', 'Wohnungssanierung Zug', 'Komplette Sanierung mit Verputz-, Maler- und Bodenarbeiten.' ),
			array( 'proj2.jpg', 'Badsanierung', 'Badsanierung Steinhausen', 'Modernes Bad mit präziser Fliesen- und Trockenbauarbeit.' ),
			array( 'proj3.jpg', 'Verputz', 'Innenausbau Illertissen', 'Trockenbau, Spachtel- und Malerarbeiten für einen Neubau.' ),
			array( 'proj4.jpg', 'Fassade', 'Fassadenrenovierung Cham', 'Verputz und Anstrich für eine langlebige, schöne Fassade.' ),
			array( 'proj5.jpg', 'Malerei', 'Malerarbeiten Baar', 'Frische Farben und makellose Anstriche für mehrere Wohnungen.' ),
			array( 'proj6.jpg', 'Boden', 'Parkett Hünenberg', 'Warmer Parkettboden, fachgerecht und sauber verlegt.' ),
		);
		foreach ( $projects as $p ) {
			echo '<div class="proj-card"><div class="proj-img"><span class="tag">' . esc_html( $p[1] ) . '</span>';
			echo '<img src="' . esc_url( $u . '/assets/img/' . $p[0] ) . '" alt="' . esc_attr( $p[2] ) . '"></div>';
			echo '<div class="proj-body"><h3>' . esc_html( $p[2] ) . '</h3><p>' . esc_html( $p[3] ) . '</p></div></div>';
		}
		?>
	</div>
</section>

<section class="section" id="kontakt">
	<div class="container contact-grid">
		<div class="contact-info">
			<span class="kicker">Nehmen Sie Kontakt auf</span>
			<h2 class="sec-title">Wir sind für Sie da</h2><div class="accent-line"></div>
			<p>Schicken Sie uns Ihre Anfrage über das Formular. Wir leiten sie an den richtigen Ansprechpartner weiter und melden uns so schnell wie möglich bei Ihnen zurück.</p>
			<ul class="ci-list">
				<li><i class="fa-solid fa-location-dot"></i><div><strong>Schweiz</strong><br><?php echo esc_html( besibau_info( 'address' ) ); ?></div></li>
				<li><i class="fa-solid fa-phone"></i><div><a href="tel:<?php echo esc_attr( besibau_info( 'phone_href' ) ); ?>"><?php echo esc_html( besibau_info( 'phone' ) ); ?></a></div></li>
				<li><i class="fa-solid fa-envelope"></i><div><a href="mailto:<?php echo esc_attr( besibau_info( 'email' ) ); ?>"><?php echo esc_html( besibau_info( 'email' ) ); ?></a></div></li>
				<li><i class="fa-solid fa-location-dot"></i><div><strong>Deutschland</strong><br><?php echo esc_html( besibau_info( 'address_de' ) ); ?></div></li>
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

<?php get_footer(); ?>
