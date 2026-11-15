<?php
/**
 * Fallback template.
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
	<h1 class="title"><?php esc_html_e( 'Index', 'pivorunas' ); ?></h1>
</main>
<?php
get_footer();
