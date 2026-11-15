<?php
/**
 * Fields stored on series, films, and books.
 *
 * @package PivorunasPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Allowed frame shapes. The value is the width divided by the height.
 *
 * @return array<string, string>
 */
function pivorunas_portfolio_aspects() {
	return array(
		'0.75'   => __( '3 : 4 portrait', 'pivorunas-portfolio' ),
		'1'      => __( 'Square', 'pivorunas-portfolio' ),
		'1.5'    => __( '3 : 2 landscape', 'pivorunas-portfolio' ),
		'1.7778' => __( '16 : 9 landscape', 'pivorunas-portfolio' ),
	);
}

/**
 * Register the edit boxes.
 */
function pivorunas_portfolio_meta_boxes() {
	add_meta_box( 'pivorunas-frames', __( 'Photographs', 'pivorunas-portfolio' ), 'pivorunas_portfolio_frames_box', 'series', 'normal', 'high' );
	add_meta_box( 'pivorunas-video', __( 'Video file', 'pivorunas-portfolio' ), 'pivorunas_portfolio_video_box', 'film', 'normal', 'high' );
	add_meta_box( 'pivorunas-volume', __( 'Volume', 'pivorunas-portfolio' ), 'pivorunas_portfolio_volume_box', 'book', 'side', 'high' );
}
add_action( 'add_meta_boxes', 'pivorunas_portfolio_meta_boxes' );

/**
 * Series photographs and shape.
 *
 * @param WP_Post $post Current series.
 */
function pivorunas_portfolio_frames_box( $post ) {
	wp_nonce_field( 'pivorunas_series', 'pivorunas_series_nonce' );
	$ids    = pivorunas_portfolio_frame_ids( $post->ID );
	$aspect = get_post_meta( $post->ID, '_pivorunas_aspect', true );
	if ( ! isset( pivorunas_portfolio_aspects()[ $aspect ] ) ) {
		$aspect = '0.75';
	}
	?>
	<p>
		<label for="pivorunas-aspect"><strong><?php esc_html_e( 'Frame shape', 'pivorunas-portfolio' ); ?></strong></label><br />
		<select id="pivorunas-aspect" name="pivorunas_aspect">
			<?php foreach ( pivorunas_portfolio_aspects() as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $aspect, $value ); ?>><?php echo esc_html( $label ); ?></option>
			<?php endforeach; ?>
		</select>
	</p>
	<p class="description">
		<?php esc_html_e( 'Add the photographs in the order they should be seen. One upload is one frame. A caption typed on the image in the Media Library is used. If you leave the caption empty, the site uses the series title and the frame number, such as SUNRISE #4.', 'pivorunas-portfolio' ); ?>
	</p>
	<ul class="pivorunas-frames" data-pivorunas-frames>
		<?php foreach ( $ids as $id ) : ?>
			<?php pivorunas_portfolio_frame_row( $id ); ?>
		<?php endforeach; ?>
	</ul>
	<p><button type="button" class="button" data-pivorunas-add-frames><?php esc_html_e( 'Add photographs', 'pivorunas-portfolio' ); ?></button></p>
	<?php
}

/**
 * One row in the frame list.
 *
 * @param int $id Attachment ID.
 */
function pivorunas_portfolio_frame_row( $id ) {
	$id = absint( $id );
	if ( ! $id ) {
		return;
	}
	?>
	<li data-id="<?php echo esc_attr( (string) $id ); ?>">
		<?php echo wp_get_attachment_image( $id, array( 72, 72 ) ); ?>
		<span><?php echo esc_html( get_the_title( $id ) ); ?></span>
		<button type="button" class="button-link" data-pivorunas-remove><?php esc_html_e( 'Remove', 'pivorunas-portfolio' ); ?></button>
		<input type="hidden" name="pivorunas_frames[]" value="<?php echo esc_attr( (string) $id ); ?>" />
	</li>
	<?php
}

/**
 * Film file and audio flag. The still is the Featured Image.
 *
 * @param WP_Post $post Current film.
 */
function pivorunas_portfolio_video_box( $post ) {
	wp_nonce_field( 'pivorunas_film', 'pivorunas_film_nonce' );
	$video = absint( get_post_meta( $post->ID, '_pivorunas_video', true ) );
	$audio = (bool) get_post_meta( $post->ID, '_pivorunas_audio', true );
	$url   = $video ? wp_get_attachment_url( $video ) : '';
	?>
	<p class="description"><?php esc_html_e( 'Set the Featured Image as the still that shows before play. Upload the video here. If you leave the video empty, the still plays with a slow drift.', 'pivorunas-portfolio' ); ?></p>
	<p data-pivorunas-video>
		<button type="button" class="button" data-pivorunas-add-video><?php esc_html_e( 'Choose video', 'pivorunas-portfolio' ); ?></button>
		<button type="button" class="button-link" data-pivorunas-clear-video><?php esc_html_e( 'Remove video', 'pivorunas-portfolio' ); ?></button>
		<span data-pivorunas-video-name><?php echo esc_html( $url ? wp_basename( $url ) : '' ); ?></span>
		<input type="hidden" name="pivorunas_video" value="<?php echo esc_attr( (string) $video ); ?>" />
	</p>
	<p>
		<label>
			<input type="checkbox" name="pivorunas_audio" value="1" <?php checked( $audio ); ?> />
			<?php esc_html_e( 'This film has audio', 'pivorunas-portfolio' ); ?>
		</label>
	</p>
	<?php
}

/**
 * Book volume number.
 *
 * @param WP_Post $post Current book.
 */
function pivorunas_portfolio_volume_box( $post ) {
	wp_nonce_field( 'pivorunas_book', 'pivorunas_book_nonce' );
	$volume = absint( get_post_meta( $post->ID, '_pivorunas_volume', true ) );
	?>
	<label for="pivorunas-volume"><?php esc_html_e( 'Volume number', 'pivorunas-portfolio' ); ?></label>
	<input id="pivorunas-volume" type="number" min="1" step="1" name="pivorunas_volume" value="<?php echo esc_attr( (string) $volume ); ?>" />
	<?php
}

/**
 * Save series fields.
 *
 * @param int $post_id Post ID.
 */
function pivorunas_portfolio_save_series( $post_id ) {
	if ( ! isset( $_POST['pivorunas_series_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pivorunas_series_nonce'] ) ), 'pivorunas_series' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$raw = isset( $_POST['pivorunas_frames'] ) ? wp_unslash( $_POST['pivorunas_frames'] ) : array();
	$ids = array();
	if ( is_array( $raw ) ) {
		foreach ( $raw as $id ) {
			$id = absint( $id );
			if ( $id && 'attachment' === get_post_type( $id ) ) {
				$ids[] = $id;
			}
		}
	}
	update_post_meta( $post_id, '_pivorunas_frames', $ids );

	$aspect = isset( $_POST['pivorunas_aspect'] ) ? sanitize_text_field( wp_unslash( $_POST['pivorunas_aspect'] ) ) : '0.75';
	if ( ! isset( pivorunas_portfolio_aspects()[ $aspect ] ) ) {
		$aspect = '0.75';
	}
	update_post_meta( $post_id, '_pivorunas_aspect', $aspect );
}
add_action( 'save_post_series', 'pivorunas_portfolio_save_series' );

/**
 * Save film fields.
 *
 * @param int $post_id Post ID.
 */
function pivorunas_portfolio_save_film( $post_id ) {
	if ( ! isset( $_POST['pivorunas_film_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pivorunas_film_nonce'] ) ), 'pivorunas_film' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$video = isset( $_POST['pivorunas_video'] ) ? absint( wp_unslash( $_POST['pivorunas_video'] ) ) : 0;
	if ( $video && 'attachment' !== get_post_type( $video ) ) {
		$video = 0;
	}
	update_post_meta( $post_id, '_pivorunas_video', $video );
	update_post_meta( $post_id, '_pivorunas_audio', empty( $_POST['pivorunas_audio'] ) ? '' : '1' );
}
add_action( 'save_post_film', 'pivorunas_portfolio_save_film' );

/**
 * Save the book volume.
 *
 * @param int $post_id Post ID.
 */
function pivorunas_portfolio_save_book( $post_id ) {
	if ( ! isset( $_POST['pivorunas_book_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pivorunas_book_nonce'] ) ), 'pivorunas_book' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$volume = isset( $_POST['pivorunas_volume'] ) ? absint( wp_unslash( $_POST['pivorunas_volume'] ) ) : 0;
	update_post_meta( $post_id, '_pivorunas_volume', $volume );
}
add_action( 'save_post_book', 'pivorunas_portfolio_save_book' );

/**
 * Ordered photograph IDs for a series.
 *
 * @param int $post_id Series ID.
 * @return int[]
 */
function pivorunas_portfolio_frame_ids( $post_id ) {
	$ids = get_post_meta( $post_id, '_pivorunas_frames', true );
	if ( ! is_array( $ids ) ) {
		return array();
	}
	return array_values( array_filter( array_map( 'absint', $ids ) ) );
}

/**
 * Admin styles and the media picker.
 *
 * @param string $hook Current admin page.
 */
function pivorunas_portfolio_admin_assets( $hook ) {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	$types  = array( 'series', 'film', 'book' );
	$needed = ( $screen && in_array( $screen->post_type, $types, true ) ) || 'toplevel_page_pivorunas-portfolio' === $hook;
	if ( ! $needed ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style( 'pivorunas-portfolio-admin', plugins_url( 'assets/admin.css', PIVORUNAS_PORTFOLIO_FILE ), array(), PIVORUNAS_PORTFOLIO_VERSION );
	wp_enqueue_script( 'pivorunas-portfolio-admin', plugins_url( 'assets/admin.js', PIVORUNAS_PORTFOLIO_FILE ), array( 'jquery' ), PIVORUNAS_PORTFOLIO_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'pivorunas_portfolio_admin_assets' );
