<?php
/**
 * Book list.
 *
 * @package Pivorunas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
pivorunas_header_bar();
?>
<main id="content" class="sheet">
	<h1 class="title"><?php esc_html_e( 'Books', 'pivorunas' ); ?></h1>
	<ul class="books">
		<?php
		while ( have_posts() ) :
			the_post();
			$volume = absint( get_post_meta( get_the_ID(), '_pivorunas_volume', true ) );
			?>
			<li>
				<span><?php echo esc_html( sprintf( /* translators: %d volume number */ __( 'Volume %d:', 'pivorunas' ), $volume ) ); ?></span>
				<span><?php the_title(); ?></span>
			</li>
		<?php endwhile; ?>
	</ul>
</main>
<?php
get_footer();
