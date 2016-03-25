<?php
/*
Plugin Name: Woocommerce Free Shipping Notification
Plugin URI: https://github.com/sygency/woocommerce-free-shipping-notification
Description: This plugin shows how much money user should spend in order to get free shipping if available.
Version: 1.2
Author: UAB Synergy Technologies
Author URI: http://www.sygency.com
Requires at least: 3.7
Tested up to: 4.4.2 Wordpress and 2.5.5 WooCommerce
License: GPL2
*/

// block direct access to plugin file
defined('ABSPATH') or die( __("No script kiddies please!", 'tinyrelated' ) );

/**
 * Cart Notice of Free Shipping
 *
 */
function cart_notice() {
    global $wpdb, $woocommerce;

    $mydbselect = $wpdb->get_var( "SELECT option_value FROM  ".$wpdb->prefix."options WHERE option_name = 'woocommerce_free_shipping_settings'" );
    $array_temp = unserialize ($mydbselect);
    $maximum = $array_temp['min_amount'];
    $current = WC()->cart->subtotal;
    if ( $current < $maximum ) {
        $number = $maximum - $current;
        echo '<div class="woocommerce-message">' . __('You need to add ', 'syg') .woo_currency(). woo_round_price($number) .__(' more to your cart, in order to use free shipping.', 'syg'). '</div>';
    }
}
add_action( 'woocommerce_before_cart', 'cart_notice' );

/**
 * Mini Cart Notice of Free Shipping
 *
 */
if ( ! function_exists( 'woocommerce_mini_cart' ) ) {

    function woocommerce_mini_cart( $args = array() ) {

        global $wpdb, $woocommerce;
        // Select Data From Database
        $mydbselect = $wpdb->get_var( "SELECT option_value FROM  ".$wpdb->prefix."options WHERE option_name = 'woocommerce_free_shipping_settings'" );
        $array_temp = unserialize ($mydbselect);
        // Get Amount from input in WooCommerce > Shipping > Free Shipping
        $maximum = $array_temp['min_amount'];
        $current = WC()->cart->subtotal;
        // Display Message
        if ( $current < $maximum ) {
            $number = $maximum - $current;
            echo '<div class="woocommerce-message">' . __('You need to add  ', 'syg') .woo_currency(). woo_round_price($number) .__(' more to your cart, in order to use free shipping.', 'syg'). '</div>';
        }

        // Show Default Cart View
        $defaults = array( 'list_class' => '' );
        $args = wp_parse_args( $args, $defaults );
        wc_get_template( 'cart/mini-cart.php', $args );

    }

}
/**
 * Helper Function to display current Currency
 * @return string
 *
 */
function woo_currency( ) {
    global  $woocommerce;
    return get_woocommerce_currency_symbol();
}

/**
 * Helper Function to round number
 * @param $number
 * @return string
 */
function woo_round_price($number) {
    $numberformatted = number_format($number, 2, '.', '');
    return $numberformatted;
}