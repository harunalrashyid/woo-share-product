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

      add_filter( 'woocommerce_get_sections_products', array($this, 'woosp_admin_add_section') );
      add_filter( 'woocommerce_get_settings_products', array($this, 'woosp_admin_settings'), 10, 2 );
      add_filter( 'plugin_action_links_'. WOOSP_PLUGIN_FILE, array($this, 'woosp_admin_link_setting'), 10, 5 );

      add_action( 'wp_enqueue_scripts', array($this, 'woosp_front_load_style') );
      add_action( 'woocommerce_single_product_summary', array($this, 'woosp_front_add_buttons'), 40 );

    } else {

      add_action( 'admin_notices', array($this, 'woosp_admin_notice_woocommerce_not_active') );

    }
  }

  public function woosp_admin_add_section( $sections )
  {
    $sections[$this->setting_section] = __('Share Product', 'woo-share-product');

    return $sections;
  }

  public function woosp_admin_settings( $settings, $current_section )
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
          'id'              => 'wooshareproduct_facebook',
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
          'id'              => 'wooshareproduct_linkedin',
          'default'         => 'no',
          'type'            => 'checkbox',
          'checkboxgroup'   => '',
          'autoload'        => false,
        ),

        array(
          'desc'            => __( 'WhatsApp', 'woo-share-product' ),
          'id'              => 'wooshareproduct_whatsapp',
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

  public function woosp_admin_link_setting( $links )
  {
    $links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=products&section=wooshareproduct' ) . '">Settings</a>';

    return $links;
  }

  public function woosp_admin_notice_woocommerce_not_active()
  {
    $message = __( 'Woocommerce Share Product requires woocommerce. Please activate/install WooCommerce.', 'woo-share-product' );
    
    $notice  = '<div id="message" class="notice notice-warning is-dismissible">';
    $notice .= '<p>';
    $notice .= esc_html( $message );
    $notice .= '</p>';
    $notice .= '</div>';

    echo $notice;
  }

  public function woosp_front_add_buttons()
  {
    global $post;

    $product_share_enable = $this->parse_bool(get_option( 'wooshareproduct_enable' ));

    if ( !$product_share_enable ) {
      return;
    }

    $product_title      = get_the_title($post->ID);
    $product_permalink  = get_permalink($post->ID);

    $product_share_label    = get_option( 'wooshareproduct_label', 'Share ' );
    $product_share_facebook = $this->parse_bool(get_option( 'wooshareproduct_facebook' ));
    $product_share_twitter  = $this->parse_bool(get_option( 'wooshareproduct_twitter' ));
    $product_share_linkedin = $this->parse_bool(get_option( 'wooshareproduct_linkedin' ));
    $product_share_whatsapp = $this->parse_bool(get_option( 'wooshareproduct_whatsapp' ));

    $product_share_markup = '<div class="woosp">';

    if ( $product_share_label && $product_share_label !== '' ) {
      $product_share_markup .= '<span class="woosp__title">' . $product_share_label . '</span>';
    }

    if ( $product_share_facebook ) {
      $facebook_url_share = 'https://www.facebook.com/sharer/sharer.php?u=' . $product_permalink;

      $product_share_markup .= '<span class="woosp__item"><a class="woosp__link" href="'. $facebook_url_share .'" target="__blank"><svg xmlns="http://www.w3.org/2000/svg" class="woosp__icon" viewBox="0 0 24 24"><path d="M14.66 5.99h1.7V3.13A23.5 23.5 0 0013.88 3c-2.45 0-4.13 1.5-4.13 4.23v2.36H7v3.2h2.76V21h3.31v-8.2h2.76l.41-3.21h-3.16V7.55c0-.95.25-1.56 1.58-1.56z"/></svg></a></span>';
    }

    if ( $product_share_twitter ) {
      $twitter_url_params  = 'source=webclient';
      $twitter_url_params .= '&original_referer=' . urlencode($product_permalink);
      $twitter_url_params .= '&text=' . urlencode($product_title);
      $twitter_url_params .= '&url=' . urlencode($product_permalink);

      $twitter_url_share = 'https://twitter.com/intent/tweet?' . $twitter_url_params;

      $product_share_markup .= '<span class="woosp__item"><a class="woosp__link" href="'. $twitter_url_share .'" target="__blank"><svg xmlns="http://www.w3.org/2000/svg" class="woosp__icon" viewBox="0 0 24 24"><path d="M21 6.42c-.66.3-1.37.5-2.12.58a3.7 3.7 0 001.62-2.04c-.71.42-1.5.73-2.34.9a3.7 3.7 0 00-6.3 3.36 10.47 10.47 0 01-7.6-3.86A3.69 3.69 0 005.4 10.3a3.67 3.67 0 01-1.68-.46v.05A3.7 3.7 0 006.7 13.5a3.68 3.68 0 01-1.67.06 3.7 3.7 0 003.45 2.57A7.4 7.4 0 013 17.65a10.44 10.44 0 005.66 1.67c6.8 0 10.5-5.63 10.5-10.51v-.48A7.56 7.56 0 0021 6.42z"/></svg></a></span>';
    }

    if ( $product_share_linkedin ) {
      $linkedin_url_params  = 'url=' . urlencode($product_permalink);
      $linkedin_url_params .= '&title=' . urlencode($product_title);

      $linkedin_url_share = 'https://www.linkedin.com/shareArticle?' . $linkedin_url_params;

      $product_share_markup .= '<span class="woosp__item"><a class="woosp__link" href="'. $linkedin_url_share .'" target="__blank"><svg xmlns="http://www.w3.org/2000/svg" class="woosp__icon" viewBox="0 0 24 24"><path d="M6.48 3c-1.1 0-1.98.89-1.98 1.98a2 2 0 001.98 2 2 2 0 001.98-2C8.46 3.88 7.58 3 6.48 3zm9.67 5.25c-1.66 0-2.62.87-3.07 1.74h-.05v-1.5H9.75V19.5h3.42v-5.45c0-1.44.1-2.83 1.88-2.83 1.76 0 1.78 1.64 1.78 2.92v5.36h3.42v-6.05c0-2.96-.64-5.2-4.1-5.2zm-11.38.23V19.5H8.2V8.48H4.77z"/></svg></a></span>';
    }

    if ( $product_share_whatsapp ) {
      $whatsapp_url_params = 'text=' . urlencode($product_title) . '%0A%0A' . urlencode($product_permalink);
      $whatsapp_url_share = 'https://api.whatsapp.com/send?' . $whatsapp_url_params;

      $product_share_markup .= '<span class="woosp__item"><a class="woosp__link" href="'. $whatsapp_url_share .'" target="__blank"><svg xmlns="http://www.w3.org/2000/svg" class="woosp__icon" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M18.38 5.63a8.97 8.97 0 00-14.1 10.81L3 21.08l4.75-1.25a8.97 8.97 0 0010.63-14.2zM12.04 19.4a7.45 7.45 0 01-3.8-1.04l-.27-.16-2.82.74.75-2.75-.17-.28a7.46 7.46 0 116.31 3.49zm4.08-5.58a35.7 35.7 0 00-1.53-.72c-.2-.08-.35-.11-.5.1-.15.23-.58.74-.71.89-.13.14-.26.16-.48.05-.23-.1-.95-.35-1.8-1.11a6.7 6.7 0 01-1.25-1.55c-.13-.23-.02-.35.1-.46.1-.1.22-.26.33-.39.11-.13.15-.22.23-.37.07-.15.03-.28-.02-.4-.06-.1-.5-1.21-.7-1.66-.17-.43-.36-.37-.5-.38h-.42c-.15 0-.4.05-.6.27-.2.23-.78.77-.78 1.87s.8 2.17.9 2.32c.12.14 1.59 2.4 3.84 3.38.53.23.94.36 1.27.47.54.17 1.03.14 1.41.09.43-.07 1.33-.54 1.51-1.07.19-.52.19-.97.13-1.06-.05-.1-.2-.15-.43-.27z" clip-rule="evenodd"/></svg></a></span>';
    }

    $product_share_markup .= '</div>';

    echo $product_share_markup;
  }

  public function woosp_front_load_style()
  {
    if ( is_product() ) {
      wp_enqueue_style( 'woosp-styles', plugins_url('assets/css/style.min.css', dirname(__FILE__)), array(), WOOSP_VERSION );
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

  private function parse_bool($value)
  {
    return filter_var($value, FILTER_VALIDATE_BOOLEAN);
  }
}
