<?php
/**
 * One series: a grid of frames, or one frame when ?frame= is set.
 *
 * @package Pivorunas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_the_ID();
$frames  = pivorunas_frames( $post_id );
$count   = count( $frames );
$asked   = isset( $_GET['frame'] ) ? absint( wp_unslash( $_GET['frame'] ) ) : 0;
$open    = ( $asked >= 1 && $asked <= $count ) ? $asked : 0;
$aspect  = get_post_meta( $post_id, '_pivorunas_aspect', true );
if ( ! in_array( $aspect, array( '0.75', '1', '1.5', '1.7778' ), true ) ) {
	$aspect = '0.75';
}

get_header();
pivorunas_header_bar();
?>
<main id="content" class="sheet">
	<h1 class="title"><?php esc_html_e( 'Photos', 'pivorunas' ); ?></h1>
	<?php if ( ! $open ) : ?>
		<p class="series-label"><?php the_title(); ?></p>
	<?php endif; ?>
	<div class="archive-grid">
		<?php pivorunas_side_index( 'series', $post_id ); ?>
		<div class="<?php echo $open ? 'stage' : ''; ?>">
			<?php if ( ! $count ) : ?>
				<p class="body"><?php esc_html_e( 'This series has no photographs yet.', 'pivorunas' ); ?></p>
			<?php elseif ( ! $open ) : ?>
				<div class="frame-grid">
					<?php foreach ( $frames as $index => $id ) : ?>
						<?php $number = $index + 1; ?>
						<a class="frame-thumb" href="<?php echo esc_url( add_query_arg( 'frame', $number ) ); ?>" aria-label="<?php echo esc_attr( pivorunas_frame_caption( $post_id, $id, $number ) ); ?>">
							<?php echo wp_get_attachment_image( $id, 'medium' ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<?php
				$prev    = $open <= 1 ? $count : $open - 1;
				$next    = $open >= $count ? 1 : $open + 1;
				$current = $frames[ $open - 1 ];
				?>
				<div class="viewer" data-viewer>
					<div class="viewer-row">
						<a class="step" href="<?php echo esc_url( add_query_arg( 'frame', $prev ) ); ?>" data-frame-link aria-label="<?php esc_attr_e( 'Previous photograph', 'pivorunas' ); ?>">
							<svg viewBox="0 0 40 40" aria-hidden="true"><rect x="1.5" y="1.5" width="37" height="37"></rect><path d="M23 12 L15 20 L23 28"></path></svg>
						</a>
						<div class="plate-slot">
							<div class="plate" style="--ar: <?php echo esc_attr( $aspect ); ?>">
								<?php echo wp_get_attachment_image( $current, 'large' ); ?>
							</div>
						</div>
						<a class="step" href="<?php echo esc_url( add_query_arg( 'frame', $next ) ); ?>" data-frame-link aria-label="<?php esc_attr_e( 'Next photograph', 'pivorunas' ); ?>">
							<svg viewBox="0 0 40 40" aria-hidden="true"><rect x="1.5" y="1.5" width="37" height="37"></rect><path d="M17 12 L25 20 L17 28"></path></svg>
						</a>
					</div>
					<p class="frame-caption"><?php echo esc_html( pivorunas_frame_caption( $post_id, $current, $open ) ); ?></p>
					<a class="frame-back" href="<?php echo esc_url( remove_query_arg( 'frame' ) ); ?>" data-frame-close><?php esc_html_e( 'All frames', 'pivorunas' ); ?></a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php
get_footer();
