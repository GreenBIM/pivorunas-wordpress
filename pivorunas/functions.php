<?php
/**
 * Pivorunas theme setup.
 *
 * @package Pivorunas
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_theme_file_path( 'inc/template-tags.php' );

/**
 * Theme supports, menu, and assets.
 */
function pivorunas_setup() {
	load_theme_textdomain( 'pivorunas', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/site.css' );
	register_nav_menus(
		array(
			'primary' => __( 'Main menu', 'pivorunas' ),
		)
	);
}
add_action( 'after_setup_theme', 'pivorunas_setup' );

/**
 * Front-end CSS and the small interaction script.
 */
function pivorunas_assets() {
	$version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'pivorunas-site', get_theme_file_uri( 'assets/css/site.css' ), array(), $version );
	wp_enqueue_script( 'pivorunas-site', get_theme_file_uri( 'assets/js/site.js' ), array(), $version, true );
}
add_action( 'wp_enqueue_scripts', 'pivorunas_assets' );

/**
 * The displayed name. The first letter is set apart in the template.
 */
function pivorunas_customize( $wp_customize ) {
	$wp_customize->add_setting(
		'pivorunas_display_name',
		array(
			'default'           => 'JEFF PIVORUNAS',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);
	$wp_customize->add_control(
		'pivorunas_display_name',
		array(
			'label'   => __( 'Name in the header', 'pivorunas' ),
			'section' => 'title_tagline',
			'type'    => 'text',
		)
	);
}
add_action( 'customize_register', 'pivorunas_customize' );

/**
 * Ask for the content plugin if it is missing.
 */
function pivorunas_plugin_notice() {
	if ( post_type_exists( 'series' ) ) {
		return;
	}
	echo '<div class="notice notice-warning"><p>';
	echo esc_html__( 'The Pivorunas theme needs the Pivorunas Portfolio plugin. Activate the plugin, then add photographs and films under Portfolio.', 'pivorunas' );
	echo '</p></div>';
}
add_action( 'admin_notices', 'pivorunas_plugin_notice' );
