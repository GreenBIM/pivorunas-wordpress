<?php
/**
 * Home photographs. Stored here so they survive a theme change.
 *
 * @package PivorunasPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Portfolio menu and the home-photo screen.
 */
function pivorunas_portfolio_menu() {
	add_menu_page(
		__( 'Portfolio', 'pivorunas-portfolio' ),
		__( 'Portfolio', 'pivorunas-portfolio' ),
		'edit_posts',
		'pivorunas-portfolio',
		'pivorunas_portfolio_settings_page',
		'dashicons-camera',
		5
	);
}
add_action( 'admin_menu', 'pivorunas_portfolio_menu' );

/**
 * Register the two home image IDs.
 */
function pivorunas_portfolio_register_setting() {
	register_setting(
		'pivorunas_portfolio',
		'pivorunas_home_ids',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'pivorunas_portfolio_sanitize_ids',
			'default'           => array(),
			'capability'        => 'edit_posts',
		)
	);
}
add_action( 'admin_init', 'pivorunas_portfolio_register_setting' );

/**
 * Keep only real image attachments.
 *
 * @param mixed $value Submitted value.
 * @return int[]
 */
function pivorunas_portfolio_sanitize_ids( $value ) {
	if ( ! is_array( $value ) ) {
		return array();
	}
	$ids = array();
	foreach ( $value as $id ) {
		$id = absint( $id );
		if ( $id && wp_attachment_is_image( $id ) ) {
			$ids[] = $id;
		}
	}
	return array_slice( $ids, 0, 2 );
}

/**
 * Settings screen.
 */
function pivorunas_portfolio_settings_page() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	$ids = get_option( 'pivorunas_home_ids', array() );
	if ( ! is_array( $ids ) ) {
		$ids = array();
	}
	$ids = array_pad( array_map( 'absint', $ids ), 2, 0 );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Home photographs', 'pivorunas-portfolio' ); ?></h1>
		<p><?php esc_html_e( 'These two pictures crossfade on the front page. The first one shows first.', 'pivorunas-portfolio' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'pivorunas_portfolio' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php esc_html_e( 'First photograph', 'pivorunas-portfolio' ); ?></th>
					<td data-pivorunas-home="0"><?php pivorunas_portfolio_home_field( 0, $ids[0] ); ?></td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'Second photograph', 'pivorunas-portfolio' ); ?></th>
					<td data-pivorunas-home="1"><?php pivorunas_portfolio_home_field( 1, $ids[1] ); ?></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * One home-image picker.
 *
 * @param int $index Field index.
 * @param int $id    Attachment ID.
 */
function pivorunas_portfolio_home_field( $index, $id ) {
	?>
	<div class="pivorunas-home-field">
		<div data-pivorunas-home-preview><?php echo $id ? wp_get_attachment_image( $id, 'medium' ) : ''; ?></div>
		<button type="button" class="button" data-pivorunas-home-choose><?php esc_html_e( 'Choose photograph', 'pivorunas-portfolio' ); ?></button>
		<button type="button" class="button-link" data-pivorunas-home-clear><?php esc_html_e( 'Remove', 'pivorunas-portfolio' ); ?></button>
		<input type="hidden" name="pivorunas_home_ids[]" value="<?php echo esc_attr( (string) $id ); ?>" />
	</div>
	<?php
}
