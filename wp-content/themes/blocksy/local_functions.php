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
	// wp_enqueue_script('jquery-3.3.1-min-js', get_template_directory_uri() . '/assets/js/jquery.min.js', false);
	// wp_enqueue_script('jquery-3.6.0-min-js', get_template_directory_uri() . '/assets/js/jquery-3.6.0.min.js', false);
	wp_enqueue_script('jquery-3.7.1-min-js', get_template_directory_uri() . '/assets/js/jquery-3.7.1.min.js', false);
	wp_enqueue_script('owl-carousel-js', get_template_directory_uri() . '/assets/js/owl.carousel.js', false);
	wp_enqueue_script('jquery-blockUI-js', get_template_directory_uri() . '/assets/js/jquery.blockUI.js', false);
	wp_enqueue_script('custom-js', get_template_directory_uri() . '/assets/js/custom.js', false);

	// Localize the script with the ajaxurl
	wp_localize_script('custom-js', 'my_ajax_object', array(
		'ajaxurl' => admin_url('admin-ajax.php'),
	));
}


?>

<?php
// add options for header & footer
if (function_exists('acf_add_options_page')) {

	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Header Settings',
		'menu_title'	=> 'Header',
		'parent_slug'	=> 'theme-general-settings',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Footer Settings',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
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
function ds_change_sale_text()
{
	return '<span class="onsale">%</span>';
}

//ADDED PERCENTAGE IN PRODUCT LISTING PAGE (SHOP PAGE) 
add_action('woocommerce_after_shop_loop_item_title', 'display_discount_percentage', 20);

function display_discount_percentage()
{
	global $product;

	if ($product->is_on_sale()) {
		$regular_price = (float) $product->get_regular_price();
		$sale_price    = (float) $product->get_sale_price();

		if ($regular_price > 0 && !empty($sale_price)) {
			// Calculate discount percentage
			$discount_percentage = round(($regular_price - $sale_price) / $regular_price * 100) . '%';

			// Calculate discount price
			$discount_price = wc_price($regular_price - $sale_price);

			// Display the regular, sale, and discount prices along with the discount percentage
			echo '<span class="price"><del>' . wc_price($regular_price) . '</del> <ins><span class="sale-per-wrapper">' . wc_price($sale_price) . '</span><span class="discount-per-wrapper">' . esc_html__('-', 'text-domain') . ' ' . $discount_percentage . '</span></ins></span>';
		}
	}

	if ($product->is_type('variable')) {
		// Get the product variations
		$variations = $product->get_available_variations();
		// Initialize an array to hold the discount information
		$discounts = array();

		foreach ($variations as $variation) {
			$variation_obj = wc_get_product($variation['variation_id']);
			$regular_price = (float) $variation_obj->get_regular_price();
			$sale_price = (float) $variation_obj->get_sale_price();

			if ($regular_price > 0 && !empty($sale_price)) {
				// Calculate the discount percentage or amount
				$discount_percentage = round(($regular_price - $sale_price) / $regular_price * 100, 2);
				$discounts[] = $discount_percentage;

				// Break after the first iteration to get the price of the first variation
				break;
			}
		}

		// Now $regular_price and $sale_price contain the regular and sale prices of the first variation


		if (!empty($discounts)) {
			// Get the maximum discount percentage or amount
			$max_discount = max($discounts);
			// Display the discount information
			echo '<span class="pricedemo"><del>' . wc_price($regular_price) . '</del> <ins><span class="sale-per-wrapper">' . wc_price($sale_price) . '</span><span class="discount-per-wrapper">' . esc_html__('', 'text-domain') . ' ' . $max_discount . '%</span></ins></span>';
		} else {
			// No discounts applied, display only the sale price
			echo '<span class="regularpricedemo">' . wc_price($sale_price) . '</span>';
		}
	} else {
		echo '<span class="regularpricedemo">' . wc_price($sale_price) . '</span>';
	}
}



//ADDED PERCENTAGE IN VARIABLE PRODUCT DETAILS PAGE 
// Always display the selected variation price for variable products
add_filter('woocommerce_show_variation_price', 'filter_show_variation_price', 10, 3);
function filter_show_variation_price($condition, $product, $variation)
{
	if ($variation->get_price() === "") {
		return false;
	} else {
		return true;
	}
}

// Remove the displayed price from variable products in single product pages only
add_action('woocommerce_single_product_summary', 'remove_the_displayed_price_from_variable_products', 9);
function remove_the_displayed_price_from_variable_products()
{
	global $product;
	if (!$product->is_type('variable')) {
		return;
	}
	remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
}

// Display the selected variation discounted price with the discounted percentage for variable products
add_filter('woocommerce_format_sale_price', 'woocommerce_custom_sales_price', 10, 3);
function woocommerce_custom_sales_price($price, $regular_price, $sale_price)
{
	global $product;
	// Check if $product is set and is a valid object
	if (isset($product) && is_a($product, 'WC_Product') && $product->is_type('variable') && is_product()) {
		$regular_price = floatval(strip_tags($regular_price));
		$sale_price = floatval(strip_tags($sale_price));
		$percentage = round(($regular_price - $sale_price) / $regular_price * 100) . '%';
		$percentage_txt = __(' -', 'woocommerce') . $percentage;
		return '<del>' . wc_price($regular_price) . '</del> <ins>' . wc_price($sale_price) . '<span class="product-details-percentage">' . $percentage_txt . '</span></ins>';
	}
	return $price;
}



//ADDED PERCENTAGE IN SIMPLE PRODUCT DETAILS PAGE 
add_action('woocommerce_single_product_summary', 'display_discount_percentage_details', 25);

function display_discount_percentage_details()
{
	global $product;

	if ($product->is_on_sale()) {
		$regular_price = (float) $product->get_regular_price();
		$sale_price    = (float) $product->get_sale_price();

		if ($regular_price > 0 && !empty($sale_price)) {
			// Calculate discount percentage
			$discount_percentage = round(100 - ($sale_price / $regular_price * 100)) . '%';

			// Calculate discount price
			$discount_price = wc_price($regular_price - $sale_price);

			// Get stock quantity
			$stock_quantity = $product->get_stock_quantity();

			// Display the regular, sale, and discount prices along with the discount percentage and stock quantity
			echo '<div class="woocommerce-product-details__short-description"><span class="price"><del>' . wc_price($regular_price) . '</del> <ins>' . wc_price($sale_price) . '<span class="discount-per-wrapper">' . esc_html__('-', 'text-domain') . ' ' . $discount_percentage . '</span></ins></span>';

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
function add_custom_class_to_body($classes)
{
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
add_filter('woocommerce_get_availability_text', 'bbloomer_custom_get_availability_text', 99, 2);

function bbloomer_custom_get_availability_text($availability, $product)
{
	$stock = $product->get_stock_quantity();
	if ($product->is_in_stock() && $product->managing_stock()) $availability = 'Seulement ' . $stock . ' articles disponible en stock quantity';
	return $availability;
}




//LEFT SIDE PRODUCT TITLE
add_action('woocommerce_before_single_product', 'custom_product_title', 5);
function custom_product_title()
{
	global $product;
	if ($product) {
		echo "<h3 class='single_pro_ext'>Extentions</h3>";
		echo '<h2 class="custom-product-title">' . get_the_title() . '</h2>';
	}
}


//ADDED Foule: TITLE
add_action('woocommerce_before_add_to_cart_quantity', 'custom_product_title_before_quantity', 5);
function custom_product_title_before_quantity()
{
	global $product;
	if ($product) {
		echo '<h5 class="custom-product">Foule:</h5>';
	}
}


// To change add to cart text on single product page
add_filter('woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_single_add_to_cart_text');
function woocommerce_custom_single_add_to_cart_text()
{
	return __('Ajouter au chariot', 'woocommerce');
}

//ADDED TEXT RECENT PRODUCT
function change_related_products_title($translated_text, $text, $domain)
{
	if ($text === 'Related products') {
		$translated_text = __('Quoi d’autre pourriez-vous aimer:', 'woocommerce');
	}
	return $translated_text;
}
add_filter('gettext', 'change_related_products_title', 20, 3);


function add_product_attributes_to_shop_loop()
{ ?>
	<div class="color_radio_btns">
		<div class="radio">
			<input id="radio-1" name="radio" type="radio" checked>
			<label for="radio-1" class="radio-label"></label>
		</div>
		<div class="radio">
			<input id="radio-2" name="radio" type="radio">
			<label for="radio-2" class="radio-label"></label>
		</div>
		<div class="radio">
			<input id="radio-3" name="radio" type="radio">
			<label for="radio-3" class="radio-label"></label>
		</div>

		<div class="radio">
			<input id="radio-4" name="radio" type="radio">
			<label for="radio-4" class="radio-label"></label>
		</div>
	</div>
<?php
}

// Hook this function to display the swatches in the product listing
add_action('woocommerce_after_shop_loop_item_title', 'add_product_attributes_to_shop_loop', 15);

//Display only product name and after that variation with label in CART PAGE
add_filter('woocommerce_product_variation_title_include_attributes', 'custom_product_variation_title', 10, 2);
function custom_product_variation_title($should_include_attributes, $product)
{
	$should_include_attributes = false;
	return $should_include_attributes;
}



//ADDED PERCENTAGE IN VARIABLE PRODUCT IN CART PAGE
add_action('woocommerce_cart_item_price', 'display_discount_percentage_cart', 20, 2);
function display_discount_percentage_cart($price_html, $cart_item)
{
	$product = $cart_item['data'];
	if ($product->is_on_sale()) {
		$regular_price = (float) $product->get_regular_price();
		$sale_price    = (float) $product->get_sale_price();
		if ($regular_price > 0 && !empty($sale_price)) {
			$discount_percentage = round(100 - ($sale_price / $regular_price * 100)) . '%';
			$price_html = '<span class="pricedemo"><del>' . wc_price($regular_price) . '</del> <ins><span class="sale-per-wrapper">' . wc_price($sale_price) . '</span><span class="discount-per-wrapper-cart">' . esc_html__('-', 'text-domain') . ' ' . $discount_percentage . '</span></ins></span>';
		}
	} else {
		$price_html = '<span class="pricedemoone">' . wc_price($regular_price) . '</span>';
	}
	return $price_html;
}

//Added Cart products Count
add_action('woocommerce_before_cart_totals', 'display_cart_item_count_and_total_before_totals');

function display_cart_item_count_and_total_before_totals()
{
	global $woocommerce;
	echo '<div class="cart-summary">';
	echo '<h3 class="cart-item-count">';
	echo sprintf(_n('%d Article', '%d Articles', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);
	echo '</h3>';
	echo '<h3 class="cart-total">';
	echo __('', 'woothemes') . $woocommerce->cart->get_cart_total();
	echo '</h3>';
	echo '</div>'; // closing cart-summary div
}

remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20);
add_action('woocommerce_proceed_to_checkout', 'custom_button_proceed_to_checkout', 20);
function custom_button_proceed_to_checkout()
{
	echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="checkout-button button alt wc-forward">' .
		__("Passer à la caisse", "woocommerce") . '</a>';
}


//ADDED PRICE ABOVE VARIATION (PRICE POSITION CHANGES)
function move_variation_price()
{
	remove_action('woocommerce_single_variation', 'woocommerce_single_variation', 10);
	add_action('woocommerce_before_variations_form', 'woocommerce_single_variation', 10);
}
add_action('woocommerce_before_add_to_cart_form', 'move_variation_price');


// Change to title shipping
function change_shipping_address_text($translated_text, $text, $domain)
{
	if ($text === 'Ship to a different address?') {
		$translated_text = 'L’adresse de facturation est différente de l’adresse de livraison.';
	}
	return $translated_text;
}
add_filter('gettext', 'change_shipping_address_text', 20, 3);



if (!function_exists('wpmc_add_delivery_time_step')) {
	function wpmc_add_delivery_time_step($steps)
	{
		$steps['delivery'] = array(
			'title'     => __(''),
			'position'  => 10,
			'class'     => 'wpmc-step-delivery',
			'sections'  => array('delivery'),
		);
		return $steps;
	}
}
add_filter('wpmc_modify_steps', 'wpmc_add_delivery_time_step');

/**
 * Add content to the Delivery Time step
 */
if (!function_exists('wmsc_step_content_delivery')) {
	function wmsc_step_content_delivery()
	{
		$checkout = WC()->checkout();
		$billing_fields = $checkout->checkout_fields['billing'];
		foreach ($billing_fields as $field_key => $field) {
			woocommerce_form_field($field_key, $field, $checkout->get_value($field_key));
		}
	}
}
add_action('wmsc_step_content_delivery', 'wmsc_step_content_delivery');

/**
 * Add your validation rules to the billing fields
 */
function wmsc_validate_billing_fields()
{
	$billing_fields = WC()->checkout()->checkout_fields['billing'];
	foreach ($billing_fields as $field_key => $field) {
		if (isset($field['required']) && $field['required'] && (!isset($_POST[$field_key]) || empty($_POST[$field_key]))) {
			wc_add_notice(sprintf(__('Please fill in %s.'), $field['label']), 'error');
		}
	}
}
add_action('woocommerce_checkout_process', 'wmsc_validate_billing_fields');

/** Save the billing fields with the order **/
function wmsc_save_billing_fields($order_id)
{
	$billing_fields = WC()->checkout()->checkout_fields['billing'];
	foreach ($billing_fields as $field_key => $field) {
		if (isset($_POST[$field_key])) {
			update_post_meta($order_id, $field_key, sanitize_text_field($_POST[$field_key]));
		}
	}
}
add_action('woocommerce_checkout_update_order_meta', 'wmsc_save_billing_fields');



add_action('woocommerce_review_order_before_payment', 'wpdesk_checkout_hello', 5);
function wpdesk_checkout_hello()
{
	echo '<div class="details-text-wrapper">';
	echo '<h2>Details de paiement</h2>';
	echo '<h3>Moyen de paiement</h3>';
	echo '</div>';
	echo '<div class="form-check-radio-wrapper">';
	echo '<div class="form-check">';
	echo '<input type="radio" name="payment_method" value="Paiement instantané">';
	echo '<label class="form-check-label" for="flexRadioDefault1">';
	echo 'Paiement instantané';
	echo '</label>';
	echo '</div>';
	echo '<div class="form-check">';
	echo '<input type="radio" name="payment_method" value="Paiement échelonné">';
	echo '<label class="form-check-label" for="flexRadioDefault1">';
	echo 'Paiement échelonné';
	echo '</label>';
	echo '<p>(Eligiblé à partir d’une commande de 300€ et livraison après le dernier paiement.)</p>';
	echo '</div>';
	echo '<div id="dummyText1" class="dummy-one" style="display: none;"><h2>Dummy text one</h2></div>';
	echo '<div id="dummyText2" class="dummy-two" style="display: none;"><h2>Dummy text two</h2></div>';
	echo '</div>';
?>
	<script>
		jQuery(document).ready(function($) {
			jQuery('input[name="payment_method"]').change(function() {
				var selectedValue = $(this).val();
				if (selectedValue === 'Paiement instantané') {
					$('#dummyText1').show();
					$('#dummyText2').hide();
				} else if (selectedValue === 'Paiement échelonné') {
					$('.dummy-one').hide();
					$('.dummy-two').show();
				}
			});
		});
	</script>
<?php
}


// Add custom text after the payment section on the checkout page
add_action('woocommerce_review_order_after_payment', 'custom_text_after_payment');
function custom_text_after_payment()
{

	echo '<div class="frais-text-wrapper">';
	echo '<h3>Frais de livraison</h3>';
	echo '<div class="form-check-radio-wrapper">';
	echo '<div class="form-check">';
	echo '<input type="radio" class="standard-hide test-one" name="payment_method_demo" value="Standard">';
	echo '<label class="form-check-label" for="flexRadioDefault1">';
	echo 'Standard (15 euros, livraison en 3-4 jours)';
	echo '</label>';
	echo '</div>';
	echo '</div>';

	// Added closing div for form-check-radio-wrapper
	echo '<div class="form-check-radio-wrapper">'; // Fixed opening div
	echo '<div class="form-check">';
	echo '<input type="radio" class="premium-show test-two" name="payment_method_demo" value="Premium">';
	echo '<label class="form-check-label" for="flexRadioDefault2">'; // Changed for attribute
	echo 'Premium (23 euros, livraison en 1 jour)';
	echo '</label>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}


add_action('woocommerce_review_order_after_payment', 'display_total_to_pay_after_payment');
function display_total_to_pay_after_payment()
{
	global $woocommerce;
	$cart = WC()->cart;
	$total_cost = $cart->get_subtotal();
	$applied_coupons = $woocommerce->cart->get_coupons();
	if (!empty($applied_coupons)) {
		foreach ($applied_coupons as $coupon) {
			$coupon_code = $coupon->get_code();
			$coupon_amount = $coupon->get_amount();
			// echo '<tr><td>Code de réduction (' . $coupon_code . ')</td><td><strong>' . $coupon_amount . '</strong><span class="woocommerce-Price-currencySymbol">€</span></td></tr>';
		}
	}
	$standard_delivery_increase = $total_cost + 15 + $coupon_amount;
	$premium_delivery_increase = $total_cost + 23 + $coupon_amount;
	$delivery_costs = 'Standard Delivery';
	echo '<form class="checkout_coupon woocommerce-form-coupon" method="post" style="">
         	<p>If you have a coupon code, please apply it below.</p>
         	<p class="form-row form-row-first"><label for="coupon_code" class="screen-reader-text">Coupon:</label><input type="text" name="coupon_code" class="input-text" placeholder="Coupon code" id="coupon_code" value=""></p>
         	<p class="form-row form-row-last"><button type="submit" class="button" name="apply_coupon" value="Apply coupon">Apply coupon</button></p>
        	<div class="clear"></div>
    	</form>';
	echo '<div class="total-to-pay-wrapper">';
	echo '<h3>Total à payer</h3>';
	echo '<div class="total_payer_content">';
	echo '<table class="your-container">';
	echo '<tr><td>' . WC()->cart->get_cart_contents_count() . ' Articles</td><td><strong>' . $total_cost . ',00<span class="woocommerce-Price-currencySymbol">€</span> </strong></td></tr>';
	echo '<tr><td>Code de réduction</td><td><strong>' . $coupon_amount . '</strong><span class="woocommerce-Price-currencySymbol">€</span></td></tr>';
	// echo '<tr><td>Code de réduction</td><td><strong>' . $coupon_amount . '</strong><span class="woocommerce-Price-currencySymbol">€</span></td></tr>';
	echo '<tr id="standardprice" class="standard-demo standard-test"><td>Frais de livraison (Standard)</td><td><strong>' . '15<span class="woocommerce-Price-currencySymbol">€</span>' . '</strong></td></tr>';
	echo '<tr id="premiumprice" class="premium-demo premium-test"><td>Frais de livraison (Premium)</td><td><strong>' . '23<span class="woocommerce-Price-currencySymbol">€</span>' . '</strong></td></tr>';
	echo '<tr id="standardprice" class="standard-demo standard-price"><td>Coût total</td><td><strong>' . $standard_delivery_increase . ',00<span class="woocommerce-Price-currencySymbol">€</span>' . '</strong></td></tr>';
	echo '<tr id="premiumprice" class="premium-demo premium-price"><td>Coût total</td><td><strong>' . $premium_delivery_increase . ',00<span class="woocommerce-Price-currencySymbol">€</span>' . '</strong></td></tr>';
	echo '</table>';
	echo '</div>';
	echo '</div>';
}


//Added Price Range label on shop
add_filter('woocommerce_format_price_range', 'custom_format_price_range', 10, 3);
function custom_format_price_range($price, $from, $to)
{
	$text_max = '<span class="wine-price-range">' . __("Price Range") . '</span>';
	$price = sprintf(_x('%1$s: %2$s &ndash; %3$s', 'Price range: label, from, to', 'woocommerce'), $text_max, is_numeric($from) ? wc_price($from) : $from, is_numeric($to) ? wc_price($to) : $to);
	return $price;
}

if (!function_exists('wpmc_add_cross_sales_step')) {
	function wpmc_add_cross_sales_step($steps)
	{
		$steps['cross_sales'] = array(
			'title'     => __('Cross-Sales'),
			'position'  => 40,
			'class'    => 'four-custom-tab',
			'sections'  => array('cross_sales'),
			'parent_class' => '	',
			'unique_class' => 'wpmc-unique-cross-sales',
		);
		return $steps;
	}
}
add_filter('wpmc_modify_steps', 'wpmc_add_cross_sales_step');

if (!function_exists('wmsc_step_content_cross_sales')) {
	function wmsc_step_content_cross_sales()
	{
		$cart_contents = WC()->cart->get_cart();

		if (!empty($cart_contents)) {

			echo '<h2>Aperçu</h2>';
			echo '<div class="woocoomerce_preview_main_section">';
			echo '<div class="left_side_parent_section">';
			echo '<div class="woocoomerce_left_side_content">';
			echo '<h3 class="product-name" colspan="2">Votre achat.</h3>';
			echo '</div>';


			// Loop through each item in the cart
			foreach ($cart_contents as $cart_item_key => $cart_item) {
				// Retrieve necessary information from the cart item
				$product_id = $cart_item['product_id'];
				$product = wc_get_product($product_id);
				$product_name = $product->get_name();
				$product_permalink = $product->get_permalink();
				$product_image = $product->get_image();
				$product_price = wc_price($product->get_price());
				$product_quantity = $cart_item['quantity'];
				// Output the HTML markup for the cart item
				echo '<tr class="woocommerce-cart-form__cart-item cart_item"><div class="parent-main-feature-content-img">';
				echo '<td class="checkout-feature-img product-thumbnail"><div class="parent-feature-img">' . $product_image . '</div></td>';
				echo '<td class="product-name" data-title="Product"><div class="parent-content-all-div">';
				echo '<a href="' . $product_permalink . '">' . $product_name . '</a>';

				if (!empty($cart_item['variation'])) {
					echo '<dl class="variation">';
					foreach ($cart_item['variation'] as $variation_key => $variation_value) {
						// Replace "attribute_pa_color" with "Couleurs" and "attribute_pa_longueur" with "Longueur"
						$variation_key = str_replace(array('attribute_pa_color', 'attribute_pa_longueur'), array('Couleurs', 'Longueur'), $variation_key);
						echo '<dt class="variation-' . esc_attr($variation_key) . '">' . wc_attribute_label($variation_key) . ':</dt>';
						echo '<dd class="variation-' . esc_attr($variation_key) . '"><p>' . esc_html($variation_value) . '</p></dd>';
					}
					echo '</dl>';
				}

				$product = $cart_item['data'];
				if ($product->is_on_sale()) {
					$regular_price = (float) $product->get_regular_price();
					$sale_price    = (float) $product->get_sale_price();
					if ($regular_price > 0 && !empty($sale_price)) {
						$discount_percentage = round(100 - ($sale_price / $regular_price * 100)) . '%';
					}
				}
				echo  '<span class="pricedemo"><del>' . wc_price($regular_price) . '</del> <ins><span class="sale-per-wrapper">' . wc_price($sale_price) . '</span><span class="discount-per-wrapper-cart">' . esc_html__('-', 'text-domain') . ' ' . $discount_percentage . '</span></ins></span>';
				echo '</td>';
				echo '<td class="product-quantity" data-title="Quantity">';
				echo '<div class="quantity-label-demo">Quantité:';
				echo '<div class="quantity" data-type="type-2">';
				echo '<span class="ct-increase"></span>';
				echo '<span class="ct-decrease"></span>';
				echo '<label class="screen-reader-text" for="quantity_' . esc_attr($cart_item_key) . '">' . $product_name . ' quantity</label>';
				echo '<input type="number" id="quantity_' . esc_attr($cart_item_key) . '" class="input-text qty text" name="cart[' . esc_attr($cart_item_key) . '][qty]" value="' . esc_attr($product_quantity) . '" aria-label="Product quantity" size="4" min="0" max="2" step="1" placeholder="" inputmode="numeric" autocomplete="off">';
				echo '</div>';
				echo '</div>';
				echo '</td>';
				echo '<td class="product-remove" data-title="Remove">';
				echo '<a href="' . esc_url(wc_get_cart_remove_url($cart_item_key)) . '" class="remove" aria-label="Remove ' . $product_name . ' from cart" data-product_id="' . esc_attr($product_id) . '" data-product_sku="">';
				echo '<svg class="ct-icon" width="10px" height="10px" viewBox="0 0 24 24"><path d="M9.6,0l0,1.2H1.2v2.4h21.6V1.2h-8.4l0-1.2H9.6z M2.8,6l1.8,15.9C4.8,23.1,5.9,24,7.1,24h9.9c1.2,0,2.2-0.9,2.4-2.1L21.2,6H2.8z"></path></svg>';
				echo '</a>';
				echo '</td></div></div>';
				echo '</tr>';
			}
			echo "</div>";

			// Total à payer
			echo '<div class="woocoomerce_right_side_content">';
			global $woocommerce;
			$cart = WC()->cart;
			$total_cost = $cart->get_subtotal();
			$applied_coupons = $woocommerce->cart->get_coupons();
			$coupon_amount = 0;
			if (!empty($applied_coupons)) {
				foreach ($applied_coupons as $coupon) {
					$coupon_amount += $coupon->get_amount();
				}
			}
			$standard_delivery_increase = $total_cost + 15 + $coupon_amount;
			$premium_delivery_increase = $total_cost + 23 + $coupon_amount;
			$delivery_costs = 'Standard Delivery';
			echo '<div class="total-to-pay-wrapper">';
			echo '<h3>Total à payer</h3>';
			echo '<div class="total_payer_content">';
			echo '<table>';
			echo '<tr><td>' . WC()->cart->get_cart_contents_count() . ' Articles</td><td><strong>' . $total_cost . ',00<span class="woocommerce-Price-currencySymbol">€</span> </strong></td></tr>';
			echo '<tr><td>Code de réduction</td><td><strong>' . $coupon_amount . '</strong><span class="woocommerce-Price-currencySymbol">€</span></td></tr>';
			echo '<tr id="standardprice" class="standard-demo"><td>Frais de livraison (Standard)</td><td><strong>' . '15<span class="woocommerce-Price-currencySymbol">€</span>' . '</strong></td></tr>';
			echo '<tr id="premiumprice" class="premium-demo hidden"><td>Frais de livraison (Premium)</td><td><strong>' . '23<span class="woocommerce-Price-currencySymbol">€</span>' . '</strong></td></tr>';
			echo '<tr id="standardprice" class="standard-demo standard-price"><td>Coût total</td><td><strong>' . $standard_delivery_increase . ',00<span class="woocommerce-Price-currencySymbol">€</span>' . '</strong></td></tr>';
			echo '<tr id="premiumprice" class="premium-demo premium-price"><td>Coût total</td><td><strong>' . $premium_delivery_increase . ',00<span class="woocommerce-Price-currencySymbol">€</span>' . '</strong></td></tr>';
			echo '</table>';
			echo '</div>';
			echo '</div>';

			//Moyen de paiement
			echo '<div class="delivery_details_section">';
			echo '<h3 class="get_payment_details">Moyen de paiement</h3>';
			echo '<img id="codimg">';
			echo '<div class="delivery_details_section">';
			echo '<h3>Details de la livraison</h3>';
			echo '<p id="name"></p>';
			echo '<p id="city"></p>';
			echo '<p id="addressone"></p>';
			echo '<p id="addresstwo"></p>';
			echo '</div>';
			echo '<div class="delivery_details_section">';
			echo '<h3>Details d’adresse de fracturation</h3>';
			echo '<p id="shippingname"></p>';
			echo '<p id="shippingville"></p>';
			echo '<p id="shippingrue"></p>';
			echo '<p id="shippingsupplement"></p>';
			echo '</div>';
			echo '<div class="details-text-wrapper">';
			echo '<h5>Traitement de données personnelles</h5>';
			echo '<div class="form-check-radio-wrapper">';
			echo '<div class="form-check">';
			echo '<input type="radio" class="user-register" name="payment_method" value="Supprimer mes">';
			echo '<label class="form-check-label" for="flexRadioDefault1">';
			echo 'Supprimer mes informations après livraison';
			echo '</label>';
			echo '</div>';
			echo '<div class="form-check">';
			echo '<input type="radio" name="payment_method" value="Sauvegarder la">';
			echo '<label class="form-check-label" for="flexRadioDefault1">';
			echo 'Sauvegarder la commande dans mon historique';
			echo '</div>';
			echo '</br>';
			echo '</label>';
			echo '</div>';
			echo '</div>';
			echo '<div id="dummy_one" class="dummy-one-text" style="display: none;"><h2>Dummy text one</h2></div>';
			echo '<div id="dummy_two" class="dummy-two-text" style="display: none;"><h2>Dummy text two</h2></div>';
			echo '</div>';
			echo '</div>';
		}
	}
}
add_action('wmsc_step_content_cross_sales', 'wmsc_step_content_cross_sales');


//Added COD ICON in Checkout page
function cod_gateway_icon($gateways)
{
	if (isset($gateways['cod'])) {
		$gateways['cod']->icon = get_stylesheet_directory_uri() . '/assets/images/cod.png';
	}
	return $gateways;
}
add_filter('woocommerce_available_payment_gateways', 'cod_gateway_icon');


// function custom_registration_function($customer_id) {
//     // Perform actions after user registration
//     // For example, you can add the user to a specific group or send a welcome email
//     // You can access the user's data using $customer_id
// }
// add_action('woocommerce_created_customer', 'custom_registration_function');


// Add custom name field to registration form
function custom_register_form()
{
?>
	<p>
		<label for="first_name"><?php _e('First Name', 'textdomain'); ?><br />
			<input type="text" name="first_name" id="first_name" class="input" value="<?php if (!empty($_POST['first_name'])) esc_attr_e($_POST['first_name']); ?>" size="25" /></label>
	</p>
<?php
}
add_action('register_form', 'custom_register_form');

// Validate custom name fields during registration
// function custom_validate_registration($errors, $sanitized_user_login, $user_email) {
//     if (empty($_POST['first_name'])) {
//         $errors->add('first_name_error', __('Please enter your first name.', 'textdomain'));
//     }
//     return $errors;
// }
// add_filter('registration_errors', 'custom_validate_registration', 10, 3);

function text_domain_woo_reg_form_fields() { ?>
    <p class="form-row form-row-first">
        <label for="billing_phone_number"><?php _e('First name', 'text_domain'); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_phone_number" id="billing_phone_number" value="<?php if (!empty($_POST['billing_phone_number'])) esc_attr_e($_POST['billing_phone_number']); ?>" />
    </p>
    <div class="clear"></div>
<?php
}
add_action('woocommerce_register_form_start', 'text_domain_woo_reg_form_fields');


/**
 * To add WooCommerce registration form custom fields.
 */

 function text_domain_woo_validate_extra_register_fields($username, $email, $validation_errors) {
    if (isset($_POST['billing_phone_number']) && empty($_POST['billing_phone_number'])) {
        $validation_errors->add('billing_phone_number_error', __('First name is a required field.', 'text_domain'));
    }
    return $validation_errors;
}
add_action('woocommerce_register_post', 'text_domain_woo_validate_extra_register_fields', 10, 3);

function text_domain_woo_save_extra_register_fields($customer_id) {
    if (isset($_POST['billing_phone_number'])) {
        update_user_meta($customer_id, 'billing_phone_number', sanitize_text_field($_POST['billing_phone_number']));
    }
}
add_action('woocommerce_created_customer', 'text_domain_woo_save_extra_register_fields');

function text_domain_woo_create_user_on_checkout($order_id) {
    $order = wc_get_order($order_id);
    $user_id = $order->get_customer_id();
	// echo '<pre>';
	// print_r($user_id);
	// echo '</pre>';

    if (!$user_id) {
        $user_email = $order->get_billing_email();
        $username   = $order->get_billing_first_name(); // You can adjust this according to your requirements.
        $password   = wp_generate_password();

        // Create the user
        $user_id = wp_create_user($username, $password, $user_email);

        // Get customer from order
        $customer = new WC_Customer($order->get_customer_id());

        // Assign billing phone number to the user meta
        $billing_phone_number = $customer->get_meta('billing_phone_number');
        if ($billing_phone_number) {
            update_user_meta($user_id, 'billing_phone_number', $billing_phone_number);
        }

        // Optionally, you can log in the user after creation
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
    }
}
add_action('woocommerce_checkout_order_processed', 'text_domain_woo_create_user_on_checkout');
