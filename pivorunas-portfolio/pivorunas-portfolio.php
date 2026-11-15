<?php
/**
 * Plugin Name:       Pivorunas Portfolio
 * Description:       Series, films, and books for the Pivorunas site. The theme only draws this content.
 * Version:           1.0.0
 * Requires at least: 6.5
 * Requires PHP:      7.4
 * Author:            Pivorunas
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       pivorunas-portfolio
 *
 * @package PivorunasPortfolio
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PIVORUNAS_PORTFOLIO_VERSION', '1.0.0' );
define( 'PIVORUNAS_PORTFOLIO_FILE', __FILE__ );

require_once __DIR__ . '/includes/post-types.php';
require_once __DIR__ . '/includes/meta.php';
require_once __DIR__ . '/includes/settings.php';

/**
 * Load translations.
 */
function pivorunas_portfolio_load_textdomain() {
	load_plugin_textdomain( 'pivorunas-portfolio', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'pivorunas_portfolio_load_textdomain', 0 );

/**
 * Flush rewrite rules once, after the content types exist.
 */
function pivorunas_portfolio_activate() {
	pivorunas_portfolio_register_post_types();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'pivorunas_portfolio_activate' );

/**
 * Flush again on deactivation so the old URLs do not linger.
 */
function pivorunas_portfolio_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'pivorunas_portfolio_deactivate' );
