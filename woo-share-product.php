<?php

/**
 * Plugin Name: Woocommerce Share Product
 * Description: Add share button social into woocommerce single product
 * Version: 1.0.0
 * Author: Harun
 * Author URI: https://github.com/harunalrashyid
 * 
 * WC requires at least: 4.0
 * WC tested up to: 4.0
 */

if ( !defined('ABSPATH') ) {
  die('-1');
}

$plugin_file = plugin_basename(__FILE__);

define( 'WOOSP_PLUGIN_FILE', $plugin_file );
define('WOOSP_VERSION', '1.0.0');

require_once( __DIR__ . '/includes/woo_share_product.php' );

if ( class_exists('WooShareProduct') ) {
  $wooShareProduct = new WooShareProduct();
}
