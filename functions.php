<?php
/**
 * Snaakontwerp Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Snaakontwerp
 * @since 1.1.8
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_SNAAKONTWERP_VERSION', '1.1.7' );

/**
 * Enqueue styles
 */
function child_enqueue_styles() {

	wp_enqueue_style( 'snaakontwerp-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_SNAAKONTWERP_VERSION, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );


// On cart page
// add_action( 'woocommerce_cart_collaterals', 'remove_cart_totals', 9 );
// function remove_cart_totals(){
    // Remove cart totals block
//    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );

    // Add back "Proceed to checkout" button (and hooks)
//    echo '<div class="cart_totals">';
//    do_action( 'woocommerce_before_cart_totals' );

//    echo '<div class="wc-proceed-to-checkout">';
//    do_action( 'woocommerce_proceed_to_checkout' );
//    echo '</div>';

//    do_action( 'woocommerce_after_cart_totals' );
//    echo '</div><br clear="all">';
// }

// On checkout page
// add_action( 'woocommerce_checkout_order_review', 'remove_checkout_totals', 1 );
// function remove_checkout_totals(){
//     // Remove cart totals block
//     remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
// };

/**
 * @snippet       Hide one shipping rate when Free Shipping is available
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 6
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
// add_filter( 'woocommerce_package_rates', 'bbloomer_unset_shipping_when_free_is_available_in_zone', 9999, 2 );
   
// function bbloomer_unset_shipping_when_free_is_available_in_zone( $rates, $package ) {
   // Only unset rates if free_shipping is available
//   if ( isset( $rates['free_shipping:8'] ) ) {
//      unset( $rates['flat_rate:1'] );
//   }     
//   return $rates;
//}

// not needed as basic cart does Dutch and display
// add_filter('wc_add_to_cart_message', 'handler_function_name', 10, 2);
// function handler_function_name($message, $product_id) {
//     return "Bekijk winkelmand" . ' ' .wc_get_product( get_the_id() )    ;
// }

remove_filter( 'the_content', 'convert_smilies', 20 );

function add_custom_text_after_product_title_single() {
    if ( ( is_front_page() || is_shop() ) ) {
        // Get the value of the ACF field (replace 'your_acf_field_name' with the actual field name)
            $acf_field_value = get_field( 'kleding_prijs_vanaf' );
            if ($acf_field_value) {
                echo '<div class="acf-field-content">' . esc_html($acf_field_value) . '</div>';
            }
    }
}
add_action('woocommerce_after_shop_loop_item', 'add_custom_text_after_product_title_single', 15);

function dynamic_product_link_button_shortcode($atts) {
    // Parse attributes
    $atts = shortcode_atts(array(
        'text' => 'Fitting & Maat', // Default button text
    ), $atts);

    // Check if WooCommerce is active and a product is available
    if (class_exists('WooCommerce') && is_product()) {
        // Get the current product ID
        $product_id = get_the_ID();

        // Check if $product_id is not null
        if ($product_id) {
            // Get the product slug
            $product_slug = get_post_field('post_name', $product_id);

            // Generate the dynamic hyperlink
            $dynamic_link = home_url('/wp-content/uploads/sizes/' . sanitize_title($product_slug)) . '.pdf';

            // Output the dynamic link as a button
            return '<a href="' . esc_url($dynamic_link) . '" class="button dynamic-product-link-button" target="_blank">' . esc_html($atts['text']) . '</a>';
        }
    }

    // If no product is available, return a message or an empty string
    return ''; // You can customize this part as needed
}
add_shortcode('dynamic_product_link_button', 'dynamic_product_link_button_shortcode');

// no zoom for product images
// remove gallery or lightbox

// add_action( 'after_setup_theme', function() { 
// 	remove_theme_support( 'wc-product-gallery-zoom' );
// 	remove_theme_support( 'wc-product-gallery-lightbox' );
// 	remove_theme_support( 'wc-product-gallery-slider' );
// }, 99 );

// function wc_remove_image_effect_support() {

//     remove_theme_support( 'wc-product-gallery-zoom' );
//     remove_theme_support( 'wc-product-gallery-lightbox' );
//     remove_theme_support( 'wc-product-gallery-slider' );

// }
// add_action( 'after_setup_theme', 'wc_remove_image_effect_support', 100 );

add_filter( 'woocommerce_defer_transactional_emails', '__return_true' );

/**
 * @snippet       Product Category Switcher @ Product Category Pages
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 8
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
 
add_action( 'woocommerce_before_shop_loop', 'bbloomer_filter_by_tag', 31 );
 
function bbloomer_filter_by_tag() {    
   if ( is_shop() || is_product_category ()) {      
      wc_product_dropdown_categories();   
   } 
   wc_enqueue_js( "
      $('#product_cat').change(function () {
         location.href = '/product-categorie/' + $(this).val();
      });
   " );
}

/**
 * @snippet       Remove Sorting Dropdown @ WooCommerce Shop & Archives
 * @how-to        Get CustomizeWoo.com FREE
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 7
 * @donate $9     https://businessbloomer.com/bloomer-armada/
 */
  
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

/* remove result counts before and after shop loop */
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_after_shop_loop', 'woocommerce_result_count', 20);

/* remove shop page pagination */
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );


// change number of  products per page
add_filter( 'loop_shop_per_page', 'my_remove_pagination', 20 );
 
function my_remove_pagination( $cols ) {
 
$cols = 32;
 
return $cols;
 
}