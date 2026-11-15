<?php
/**
 * Markup shared by the templates.
 *
 * @package Pivorunas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Name shown in the header, uppercased.
 */
function pivorunas_display_name() {
	$name = get_theme_mod( 'pivorunas_display_name', 'JEFF PIVORUNAS' );
	$name = trim( (string) $name );
	if ( '' === $name ) {
		$name = 'JEFF PIVORUNAS';
	}
	return function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $name ) : strtoupper( $name );
}

/**
 * The name, with the first letter in its own face.
 *
 * @param bool $on_photo White, when it sits on a photograph.
 */
function pivorunas_name( $on_photo = false ) {
	$name  = pivorunas_display_name();
	$first = function_exists( 'mb_substr' ) ? mb_substr( $name, 0, 1 ) : substr( $name, 0, 1 );
	$rest  = function_exists( 'mb_substr' ) ? mb_substr( $name, 1 ) : substr( $name, 1 );
	$class = 'name' . ( $on_photo ? ' on-photo' : '' );
	echo '<a class="' . esc_attr( $class ) . '" href="' . esc_url( home_url( '/' ) ) . '">';
	echo '<span class="font-name-noto">' . esc_html( $first ) . '</span>';
	echo '<span class="font-name-ancizar">' . esc_html( $rest ) . '</span>';
	echo '</a>';
}

/**
 * Menu items, from the assigned menu or a built-in list.
 *
 * @return array<int, array{url: string, label: string, active: bool, photos: bool}>
 */
function pivorunas_menu_items() {
	$locations = get_nav_menu_locations();
	$photos    = get_post_type_archive_link( 'series' );
	if ( ! empty( $locations['primary'] ) ) {
		$raw = wp_get_nav_menu_items( $locations['primary'] );
		if ( is_array( $raw ) && function_exists( '_wp_menu_item_classes_by_context' ) ) {
			_wp_menu_item_classes_by_context( $raw );
		}
		$items = array();
		if ( is_array( $raw ) ) {
			foreach ( $raw as $item ) {
				if ( '0' !== (string) $item->menu_item_parent ) {
					continue;
				}
				$items[] = array(
					'url'    => $item->url,
					'label'  => $item->title,
					'active' => in_array( 'current-menu-item', (array) $item->classes, true ) || in_array( 'current-menu-ancestor', (array) $item->classes, true ),
					'photos' => $photos && untrailingslashit( $item->url ) === untrailingslashit( $photos ),
				);
			}
		}
		if ( $items ) {
			return $items;
		}
	}

	$pages = array(
		array( 'videos', __( 'Videos', 'pivorunas' ), get_post_type_archive_link( 'film' ) ),
		array( 'about', __( 'About', 'pivorunas' ), '' ),
		array( 'publications', __( 'Publications', 'pivorunas' ), get_post_type_archive_link( 'book' ) ),
		array( 'contact', __( 'Contact', 'pivorunas' ), '' ),
		array( 'links', __( 'Links', 'pivorunas' ), '' ),
	);
	$items = array(
		array(
			'url'    => $photos ? $photos : home_url( '/photos/' ),
			'label'  => __( 'Photos', 'pivorunas' ),
			'active' => is_post_type_archive( 'series' ) || is_singular( 'series' ),
			'photos' => true,
		),
	);
	foreach ( $pages as $page ) {
		$url = $page[2];
		if ( ! $url ) {
			$found = get_page_by_path( $page[0] );
			$url   = $found ? get_permalink( $found ) : home_url( '/' . $page[0] . '/' );
		}
		$items[] = array(
			'url'    => $url,
			'label'  => $page[1],
			'active' => is_page( $page[0] ) || ( 'videos' === $page[0] && ( is_post_type_archive( 'film' ) || is_singular( 'film' ) ) ) || ( 'publications' === $page[0] && ( is_post_type_archive( 'book' ) || is_singular( 'book' ) ) ),
			'photos' => false,
		);
	}
	return $items;
}

/**
 * Print one menu link.
 *
 * @param array{url: string, label: string, active: bool} $item     Item.
 * @param bool                                            $on_photo White text.
 */
function pivorunas_menu_link( $item, $on_photo = false ) {
	$class = 'menu';
	if ( $on_photo ) {
		$class .= ' on-photo';
	}
	if ( ! empty( $item['active'] ) ) {
		$class .= ' is-active';
	}
	echo '<a class="' . esc_attr( $class ) . '" href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['label'] ) . '</a>';
}

/**
 * Interior header: name at the left, menu at the right, tops aligned.
 */
function pivorunas_header_bar() {
	echo '<header class="site-header">';
	pivorunas_name( false );
	echo '<nav class="site-nav" aria-label="' . esc_attr__( 'Primary', 'pivorunas' ) . '">';
	foreach ( pivorunas_menu_items() as $item ) {
		pivorunas_menu_link( $item, false );
	}
	echo '</nav></header>';
}

/**
 * Photograph IDs for a series.
 *
 * @param int $post_id Series ID.
 * @return int[]
 */
function pivorunas_frames( $post_id ) {
	if ( function_exists( 'pivorunas_portfolio_frame_ids' ) ) {
		$ids = pivorunas_portfolio_frame_ids( $post_id );
		if ( $ids ) {
			return $ids;
		}
	}
	$thumb = get_post_thumbnail_id( $post_id );
	return $thumb ? array( (int) $thumb ) : array();
}

/**
 * Caption for one frame.
 *
 * @param int $post_id Series ID.
 * @param int $frame_id Attachment ID.
 * @param int $number 1-based frame number.
 */
function pivorunas_frame_caption( $post_id, $frame_id, $number ) {
	$caption = wp_get_attachment_caption( $frame_id );
	if ( $caption ) {
		return $caption;
	}
	return get_the_title( $post_id ) . ' #' . $number;
}

/**
 * Side list of a post type.
 *
 * @param string $post_type series or film.
 * @param int    $current   Current post ID.
 */
function pivorunas_side_index( $post_type, $current = 0 ) {
	$query = new WP_Query(
		array(
			'post_type'      => $post_type,
			'posts_per_page' => -1,
			'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		)
	);
	echo '<ul class="side-index">';
	while ( $query->have_posts() ) {
		$query->the_post();
		$class = 'menu-side';
		if ( get_the_ID() === (int) $current ) {
			$class .= ' is-active';
		}
		$thumb = get_the_post_thumbnail_url( get_the_ID(), 'large' );
		echo '<li><a class="' . esc_attr( $class ) . '" href="' . esc_url( get_permalink() ) . '"';
		if ( $thumb ) {
			echo ' data-preview="' . esc_url( $thumb ) . '"';
		}
		echo '>' . esc_html( get_the_title() ) . '</a></li>';
	}
	echo '</ul>';
	wp_reset_postdata();
}
