<?php
/**
 * Video index.
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
	<h1 class="title"><?php esc_html_e( '(Silent short) Videos', 'pivorunas' ); ?></h1>
	<div class="archive-grid">
		<?php pivorunas_side_index( 'film' ); ?>
		<div class="stage">
			<img class="preview" alt="" data-preview-stage hidden />
		</div>
	</div>
</main>
<?php
get_footer();
