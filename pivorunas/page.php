<?php
/**
 * About, Contact, Links, and any other page.
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
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<h1 class="title"><?php the_title(); ?></h1>
		<div class="body page-copy">
			<?php the_content(); ?>
		</div>
	<?php endwhile; ?>
</main>
<?php
get_footer();
