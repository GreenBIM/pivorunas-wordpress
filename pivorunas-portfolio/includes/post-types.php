<?php
/**
 * Content types. These stay when the theme is switched.
 *
 * @package PivorunasPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register series, films, and books.
 */
function pivorunas_portfolio_register_post_types() {
	register_post_type(
		'series',
		array(
			'labels'       => array(
				'name'          => __( 'Series', 'pivorunas-portfolio' ),
				'singular_name' => __( 'Series', 'pivorunas-portfolio' ),
				'add_new_item'  => __( 'Add series', 'pivorunas-portfolio' ),
				'edit_item'     => __( 'Edit series', 'pivorunas-portfolio' ),
				'menu_name'     => __( 'Photos', 'pivorunas-portfolio' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'has_archive'  => 'photos',
			'rewrite'      => array( 'slug' => 'photos' ),
			'menu_icon'    => 'dashicons-format-gallery',
			'show_in_menu' => 'pivorunas-portfolio',
			'supports'     => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'film',
		array(
			'labels'       => array(
				'name'          => __( 'Films', 'pivorunas-portfolio' ),
				'singular_name' => __( 'Film', 'pivorunas-portfolio' ),
				'add_new_item'  => __( 'Add film', 'pivorunas-portfolio' ),
				'edit_item'     => __( 'Edit film', 'pivorunas-portfolio' ),
				'menu_name'     => __( 'Videos', 'pivorunas-portfolio' ),
			),
			'public'       => true,
			'show_in_rest' => true,
			'has_archive'  => 'videos',
			'rewrite'      => array( 'slug' => 'videos' ),
			'menu_icon'    => 'dashicons-video-alt3',
			'show_in_menu' => 'pivorunas-portfolio',
			'supports'     => array( 'title', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'book',
		array(
			'labels'              => array(
				'name'          => __( 'Books', 'pivorunas-portfolio' ),
				'singular_name' => __( 'Book', 'pivorunas-portfolio' ),
				'add_new_item'  => __( 'Add book', 'pivorunas-portfolio' ),
				'edit_item'     => __( 'Edit book', 'pivorunas-portfolio' ),
				'menu_name'     => __( 'Books', 'pivorunas-portfolio' ),
			),
			'public'              => true,
			'show_in_rest'        => true,
			'has_archive'         => 'publications',
			'rewrite'             => array( 'slug' => 'book' ),
			'exclude_from_search' => true,
			'menu_icon'           => 'dashicons-book',
			'show_in_menu'        => 'pivorunas-portfolio',
			'supports'            => array( 'title' ),
		)
	);
}
add_action( 'init', 'pivorunas_portfolio_register_post_types' );

/**
 * Keep archive order under the owner's control.
 *
 * @param WP_Query $query Main query.
 */
function pivorunas_portfolio_archive_order( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( $query->is_post_type_archive( array( 'series', 'film' ) ) ) {
		$query->set( 'posts_per_page', -1 );
		$query->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
	}

	if ( $query->is_post_type_archive( 'book' ) ) {
		$query->set( 'posts_per_page', -1 );
		$query->set( 'meta_key', '_pivorunas_volume' );
		$query->set( 'orderby', 'meta_value_num' );
		$query->set( 'order', 'ASC' );
	}
}
add_action( 'pre_get_posts', 'pivorunas_portfolio_archive_order' );
