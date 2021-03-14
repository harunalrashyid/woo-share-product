<?php

if ( !defined('ABSPATH') ) {
  die('-1');
}

class WooShareProduct
{
  public function __construct()
  {
    if ( $this->is_woocommerce_active() ) {

    } else {
      add_action( 'admin_notices', array($this, 'admin_notice_woocommerce_not_active') );
    }
  }

  private function is_woocommerce_active()
  {
    if ( in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) )) ) {
      return true;
    } else {
      return false;
    }
  }

  public function admin_notice_woocommerce_not_active()
  {
    $message = __( 'Woocommerce Share Product requires woocommerce. Please activate/install WooCommerce.', 'woo-share-product' );
    
    $notice  = '<div id="message" class="notice notice-warning is-dismissible">';
    $notice .= '<p>';
    $notice .= esc_html( $message );
    $notice .= '</p>';
    $notice .= '</div>';

    echo $notice;
  }
}
