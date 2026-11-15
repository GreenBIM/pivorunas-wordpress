<?php
/**
 * One film.
 *
 * @package Pivorunas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_the_ID();
$video   = absint( get_post_meta( $post_id, '_pivorunas_video', true ) );
$audio   = (bool) get_post_meta( $post_id, '_pivorunas_audio', true );
$still   = get_the_post_thumbnail_url( $post_id, 'large' );
$file    = $video ? wp_get_attachment_url( $video ) : '';

get_header();
pivorunas_header_bar();
?>
<main id="content" class="sheet">
	<h1 class="title"><?php esc_html_e( '(Silent short) Videos', 'pivorunas' ); ?></h1>
	<div class="archive-grid">
		<?php pivorunas_side_index( 'film', $post_id ); ?>
		<div class="stage player" data-player>
			<?php if ( $file ) : ?>
				<video <?php echo $still ? 'poster="' . esc_url( $still ) . '"' : ''; ?> controls playsinline src="<?php echo esc_url( $file ); ?>"></video>
			<?php elseif ( $still ) : ?>
				<img src="<?php echo esc_url( $still ); ?>" alt="" data-still />
				<button type="button" data-play aria-label="<?php echo esc_attr( sprintf( /* translators: %s film title */ __( 'Play %s', 'pivorunas' ), get_the_title() ) ); ?>">
					<svg viewBox="0 0 64 64" aria-hidden="true"><rect x="2" y="2" width="60" height="60"></rect><path d="M26 20 L44 32 L26 44"></path></svg>
				</button>
				<span class="playhead" data-playhead hidden></span>
			<?php else : ?>
				<p class="body"><?php esc_html_e( 'This film has no still or video yet.', 'pivorunas' ); ?></p>
			<?php endif; ?>
			<?php if ( $audio ) : ?>
				<p class="audio-note"><?php esc_html_e( 'With audio', 'pivorunas' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php
get_footer();
