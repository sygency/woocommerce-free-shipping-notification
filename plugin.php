<?php

/*
Plugin Name: Woocommerce Free Shipping Cost Remaining
Plugin URI: https://github.com/sygency/woocommerce-free-shipping-remaining-cost
Description: Shows how much money user should spend in order to get free shipping if available.
Version: 1.0
Author: Synergy Technologies
Author URI: http://sygency.com
Requires at least: 3.7
Tested up to: 4.2.2
*/



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
        echo '<div class="woocommerce-message">' . __('You need to add $', 'remaining_cost_message'). ($maximum - $current) .__(' more to your cart, in order to use free shipping.', 'remaining_cost_message'). '</div>';
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
            echo '<div class="woocommerce-message">' . __('You need to add  $', 'remaining_cost_message'). ($maximum - $current) .__(' more to your cart, in order to use free shipping.', 'remaining_cost_message'). '</div>';
        }

        // Show Default Cart View
        $defaults = array( 'list_class' => '' );
        $args = wp_parse_args( $args, $defaults );
        woocommerce_get_template( 'cart/mini-cart.php', $args );

    }

}
