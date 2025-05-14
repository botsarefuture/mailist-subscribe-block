<?php
/**
 * Plugin Name:       Mailist Subscribe Block
 * Description:       A simple block to subscribe to a mail list managed by Mailist.
 * Category:          widgets
 * Icon:              email
 * Keywords:          mailist, subscribe, email, newsletter
 * Version:           1.0.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            LuovaClub
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mailist-subscribe-block
 *
 * @package CreateBlock
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Load plugin textdomain for translations.
 *
 * Returns
 * -------
 * void
 */
function mailist_subscribe_block_load_textdomain() {
	load_plugin_textdomain( 'mailist-subscribe-block', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'mailist_subscribe_block_load_textdomain' );

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * Parameters
 * ----------
 * None
 *
 * Returns
 * -------
 * void
 *
 * Notes
 * -----
 * The privacy_policy_url attribute should be set in the block attributes and used in the frontend render.
 *
 * See Also
 * --------
 * https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_mailist_subscribe_block_block_init() {
	register_block_type( __DIR__ . '/build/mailist-subscribe-block' );
}

add_action( 'init', 'create_block_mailist_subscribe_block_block_init' );
