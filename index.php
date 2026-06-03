<?php
/**
 * Fallback template (used for posts, inner pages and archives).
 * The homepage uses front-page.php.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<section class="section">
	<div class="container">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class(); ?> style="max-width:820px;margin:0 auto">
					<?php if ( get_the_title() ) : ?>
						<h1 class="sec-title" style="margin-bottom:8px"><?php the_title(); ?></h1>
						<div class="accent-line"></div>
					<?php endif; ?>
					<div class="entry-content"><?php the_content(); ?></div>
				</article>
				<?php
			endwhile;
			the_posts_pagination();
		else :
			?>
			<div class="center" style="max-width:640px;margin:0 auto">
				<h1 class="sec-title">Nichts gefunden</h1>
				<div class="accent-line" style="margin:0 auto 22px"></div>
				<p class="lead">Die gewünschte Seite ist nicht verfügbar. Kehren Sie zur <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:var(--gold)">Startseite</a> zurück.</p>
			</div>
			<?php
		endif;
		?>
	</div>
</section>
<?php get_footer(); ?>
