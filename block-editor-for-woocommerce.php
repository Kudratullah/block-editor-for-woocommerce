<?php
/**
 * Use Block Editor (Gutenberg) for WooCommerce Product Editing
 *
 * @author KD <webmaster@pixelhive.pro>
 * @package PxH_WC_Block_Editor
 * @version 1.0.0
 * @copyright 2019 PixelHive.PRO
 * @license GPL-v2 or later
 *
 * @wordpress
 * Plugin Name: Block Editor For WooCommerce
 * Description: Enable Block Editor (Gutenberg) for WooCommerce
 * Plugin URI: https://pixelhive.pro/go/wcblockeditor/
 * Author: PixelHive
 * Author URI: https://pixelhive.pro
 * Version: 1.0.0
 * License: GPL v3
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: pxh-wc-block-editor
 * Domain Path: /languages/
 * Requires at least: 5.0
 * Tested up to: 5.3
 * WC tested up to: 3.8
 */
/**
 * Copyright (c) 2019 PixelHive.PRO (email: info@wpixelhive.pro). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

if ( ! defined( 'ABSPATH' ) ) {
	// !silence
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

if( ! defined( 'PxH_WC_BE_VERSION' ) ) {
	/**
	 * Plugin Version
	 * @var string
	 * @since 1.0.0
	 */
	define( 'PxH_WC_BE_VERSION', '1.0.0' );
}
if( ! defined( 'PxH_WC_BE_FILE' ) ) {
	/**
	 * Absolute path to this plugin main file
	 * @var string
	 * @since 1.0.0
	 */
	define( 'PxH_WC_BE_FILE', __FILE__ );
}
if( ! defined( 'PxH_WC_BE_PATH' ) ) {
	/**
	 * Absolute path to this plugin directory without trailing slash
	 * @var string
	 * @since 1.0.0
	 */
	define( 'PxH_WC_BE_PATH', dirname( PxH_WC_BE_FILE ) );
}
if( ! defined( 'PxH_WC_BE_URL' ) ) {
	/**
	 * URL Pointing to this plugin directory with trailing slash
	 * @var string
	 * @since 1.0.0
	 */
	define( 'PxH_WC_BE_URL', plugins_url( '/', PxH_WC_BE_FILE ) );
}

// Initialize after WC Loaded
add_action( 'woocommerce_loaded', 'PxH_WC_Enable_Block_Editor', 9999 );

/**
 * Initialize Plugin after WooCommerce.
 *
 * Remove filters applied by WooCommerce for disabling block editor on Product Edit Page
 *
 * @since 1.0.0
 *
 * @return void
 */
function PxH_WC_Enable_Block_Editor() {
	remove_filter( 'gutenberg_can_edit_post_type', 'WC_Post_Types::gutenberg_can_edit_post_type', 10 );
	remove_filter( 'use_block_editor_for_post_type', 'WC_Post_Types::gutenberg_can_edit_post_type', 10 );
	add_action( 'admin_enqueue_scripts', 'PxH_WC_Block_Editor_Scripts', 10 );
	// set show_in_rest = true for product_cat & product_tag for showing in block editor taxonomy selector
	add_filter( 'woocommerce_taxonomy_args_product_cat', 'PxH_WC_BE_Product_Taxonomy_Show_In_Rest');
	add_filter( 'woocommerce_taxonomy_args_product_tag', 'PxH_WC_BE_Product_Taxonomy_Show_In_Rest');
}

/**
 * Hooked callback for admin script enqueueing
 *
 * @since 1.0.0
 *
 * @return void
 */
function PxH_WC_Block_Editor_Scripts() {
	$screen       = get_current_screen();
	$screen_id    = $screen ? $screen->id : '';
	$suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	if ( in_array( $screen_id, array( 'product', 'edit-product' ) ) ) {
		wp_enqueue_script(
			'pxh_wc_enable_block_editor',
			PxH_WC_BE_URL . 'assets/js/admin' . $suffix . '.js',
			[ 'jquery', 'postbox','wc-admin-product-meta-boxes' ], // make sure it loaded after wp postbox and wc product meta box scripts
			defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? filemtime( PxH_WC_BE_PATH . '/assets/js/admin.js' ) : PxH_WC_BE_VERSION,
			true
		);
	}
}

/**
 * Allow WooCommerce Product Category And Product Tags in rest.
 * Allowing in rest will allow these taxonomy to be shown in block editor.
 *
 * @since 1.0.1
 *
 * @param array $args
 * @return  array
 */
function PxH_WC_BE_Product_Taxonomy_Show_In_Rest( $args ) {
	$args['show_in_rest'] = true;
	return $args;
}
// End of file wc-block-editor.php
