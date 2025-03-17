<?php
/**
 * Plugin Name:       Mailist Subscribe Block
 * Description:       A simple block to subscribe to a mail list managed by Mailist.
 * Category:          widgets
 * Icon:              email
 * Keywords:          mailist, subscribe, email, newsletter
 * Version:           0.2.0
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
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_mailist_subscribe_block_block_init() {
	register_block_type( __DIR__ . '/build/mailist-subscribe-block' );
}

add_action( 'init', 'create_block_mailist_subscribe_block_block_init' );
