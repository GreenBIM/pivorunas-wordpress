<?php
/**
 * Front page. The photograph sits under the name and the Photos link.
 *
 * @package Pivorunas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ids   = get_option( 'pivorunas_home_ids', array() );
$ids   = is_array( $ids ) ? array_filter( array_map( 'absint', $ids ) ) : array();
$items = pivorunas_menu_items();
$photo = null;
$rest  = array();
foreach ( $items as $item ) {
	if ( null === $photo && ! empty( $item['photos'] ) ) {
		$photo = $item;
		continue;
	}
	$rest[] = $item;
}

get_header();
?>
<main id="content" class="home">
	<div class="home-photo" data-home-photo>
		<?php foreach ( $ids as $index => $id ) : ?>
			<?php echo wp_get_attachment_image( $id, 'full', false, array( 'class' => 0 === $index ? 'is-on' : '' ) ); ?>
		<?php endforeach; ?>
	</div>
	<div class="home-shade" aria-hidden="true"></div>

	<header class="home-bar home-bar-small">
		<?php pivorunas_name( true ); ?>
		<nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'pivorunas' ); ?>">
			<?php foreach ( $items as $item ) : ?>
				<?php pivorunas_menu_link( $item, true ); ?>
			<?php endforeach; ?>
		</nav>
	</header>

	<header class="home-bar home-bar-wide">
		<div class="home-left">
			<?php pivorunas_name( true ); ?>
			<?php if ( $photo ) : ?>
				<?php pivorunas_menu_link( $photo, true ); ?>
			<?php endif; ?>
		</div>
		<nav class="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'pivorunas' ); ?>">
			<?php foreach ( $rest as $item ) : ?>
				<?php pivorunas_menu_link( $item, false ); ?>
			<?php endforeach; ?>
		</nav>
	</header>
</main>
<?php
get_footer();
