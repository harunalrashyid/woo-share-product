<?php
/**
 * Plugin Uninstall
 *
 * Uninstalling plugin data options.
 *
 */
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'wooshareproduct_enable' );
delete_option( 'wooshareproduct_label' );
delete_option( 'wooshareproduct_facebook' );
delete_option( 'wooshareproduct_twitter' );
delete_option( 'wooshareproduct_linkedin' );
delete_option( 'wooshareproduct_whatsapp' );
