<?php

if ( !defined('ABSPATH') ) {
  die('-1');
}

class WooShareProduct
{
  public $setting_section;

  public function __construct()
  {
    $this->setting_section = 'wooshareproduct';

    add_action( 'init', array($this, 'woosp_init') );
  }

  public function woosp_init()
  {
    if ( $this->is_woocommerce_active() ) {

      add_filter( 'woocommerce_get_sections_products', array($this, 'woosp_add_section') );
      add_filter( 'woocommerce_get_settings_products', array($this, 'woosp_settings'), 10, 2 );

    } else {

      add_action( 'admin_notices', array($this, 'admin_notice_woocommerce_not_active') );

    }
  }

  public function woosp_add_section( $sections )
  {
    $sections[$this->setting_section] = __('Share Product', 'woo-share-product');

    return $sections;
  }

  public function woosp_settings( $settings, $current_section )
  {
    if ( $this->setting_section == $current_section ) {
      $settings_share = array(
        array(
          'title' => __('Share product settings', 'woo-share-product'),
          'type'  => 'title',
          'desc'  => '',
          'id'    => 'wooshareproduct_options'
        ),

        array(
          'title'    => __( 'Enable share product?', 'woo-share-product' ),
          'desc'     => __( 'Enable Share', 'woo-share-product' ),
          'id'       => 'wooshareproduct_enable',
          'default'  => true,
          'type'     => 'checkbox',
          'checkboxgroup'   => 'start',
        ),

        array(
          'title'    => __( 'Share label', 'woo-share-product' ),
          'desc'     => __( 'Change default share label, eq: Share on ', 'woo-share-product' ),
          'id'       => 'wooshareproduct_label',
          'type'     => 'text',
          'default'  => 'Share'
        ),

        array(
          'title'           => __( 'Share Lists', 'woo-share-product' ),
          'desc'            => __( 'Facebook', 'woo-share-product' ),
          'id'              => 'wooshareproduct_fb',
          'default'         => 'no',
          'type'            => 'checkbox',
          'checkboxgroup'   => 'start',
          'autoload'        => false,
        ),

        array(
          'desc'            => __( 'Twitter', 'woo-share-product' ),
          'id'              => 'wooshareproduct_twitter',
          'default'         => 'no',
          'type'            => 'checkbox',
          'checkboxgroup'   => '',
          'autoload'        => false,
        ),

        array(
          'desc'            => __( 'LinkedIn', 'woo-share-product' ),
          'id'              => 'wooshareproduct_li',
          'default'         => 'no',
          'type'            => 'checkbox',
          'checkboxgroup'   => '',
          'autoload'        => false,
        ),

        array(
          'desc'            => __( 'WhatsApp', 'woo-share-product' ),
          'id'              => 'wooshareproduct_wa',
          'default'         => 'no',
          'type'            => 'checkbox',
          'checkboxgroup'   => 'end',
          'autoload'        => false,
        ),

        array(
          'type' => 'sectionend',
          'id'   => 'wooshareproduct_options',
        ),
      );

      return $settings_share;
    } else {
      return $settings;
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

  private function is_woocommerce_active()
  {
    if ( in_array('woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) )) ) {
      return true;
    } else {
      return false;
    }
  }
}
