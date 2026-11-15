<?php
/**
 * Photos index. Hovering a title shows its featured image.
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
	<h1 class="title"><?php esc_html_e( 'Photos', 'pivorunas' ); ?></h1>
	<div class="archive-grid">
		<?php pivorunas_side_index( 'series' ); ?>
		<div class="stage">
			<img class="preview" alt="" data-preview-stage hidden />
		</div>
	</div>
</main>
<?php
get_footer();
