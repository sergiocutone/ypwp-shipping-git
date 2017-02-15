<?php

/*
Plugin Name: YPWP Shipping
Plugin URI:  https://google.com
Description: YP Web Prestige Shipping
Version:     1.0
Author:      Web Prestige
Author URI:  https://google.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: https://google.com
Domain Path: https://google.co
*/

if (is_admin()) {
	require 'plugin-update-checker/plugin-update-checker.php';
	$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
		'https://github.com/sergiocutone/ypwp-shipping-git',
		__FILE__,
		'ypwp-shipping-git'
		);

	require ("ypwp-shippingadmin.php");
}

if (!is_admin()):

	class YPWP_Shipping {

		public function __construct(){
			add_action( 'wp_enqueue_scripts', array($this, 'wpse_enqueue_datepicker') );
			add_action( 'wp_enqueue_scripts', array($this, 'customScripts') );
			// - - - - - Trigger Before Cart - - - - - //
			add_action("woocommerce_before_add_to_cart_button", array($this,'setShipping') );
			// - - - - - - - - - - //

			add_action('woocommerce_process_product_meta', array($this, 'woo_add_custom_general_fields_save') );
			add_filter('woocommerce_add_cart_item_data', array($this, "changeDescription"), 10, 2);
			add_filter('woocommerce_get_cart_item_from_session', array($this,'wdm_get_cart_items_from_session'), 1, 3 );
			add_filter('woocommerce_cart_item_name', array($this,'add_user_custom_session'),1,3);
			add_action('woocommerce_add_order_item_meta', array($this, 'wdm_add_values_to_order_item_meta'),1,2);
			add_action('woocommerce_before_calculate_totals', array($this,'update_custom_price'), 1, 1 );

		}

		public function preorder_is_purchasable( $is_purchasable, $object ) {
			return false;
		}

		// - - - - - Load datepicker script
		public function wpse_enqueue_datepicker() {
    	// Load the datepicker script (pre-registered in WordPress).
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_register_style( 'jquery-ui', 'http://code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css' );
			wp_enqueue_style( 'jquery-ui' );  
		}
		// - - - - end - - - - - //

		// - - - - - Load custom JS Scripts
		public function customScripts(){
			wp_enqueue_style( 'customStyle', plugin_dir_url( __FILE__ ) . 'css/yp_style.css' );
			wp_enqueue_script( 'customScripts', plugin_dir_url( __FILE__ ) . 'js/yp_script.js', array( 'jquery' ), '20160816', true );
		}

		// - - - - - Set shipping before cart - - - - - //
		public function setShipping(){
			include ("templates/calculators.php");
		}
		// - - - - - - - - - - //
		//
		// - - - - - Get category of product - - - - - //
		public function getCategory(){
			global $product;
			$product_cats = wp_get_post_terms(get_the_ID(), 'product_cat');
			$count = count($product_cats);
			foreach($product_cats as $key => $cat){
				if ($cat->name == "Bulk"){
					return $cat->name;
					break;
				}elseif ($cat->name == "Bags"){
					return $cat->name;
					break;
				}
			}
		}
		// - - - - - - - - - - //

		public function setTime(){
			date_default_timezone_set('America/Edmonton');
			echo date('M d, Y h:i:s A');
		}


		public function changeDescription($cart_item_data, $product_id){
			global $woocommerce;
			$options = array();
			if ($_POST["yp_calctype"] === 'cu'){
				$calctype = "Cubic Yards";
			}elseif ($_POST["yp_calctype"] === 'sf'){
				$calctype = "Square Feet";
			}

			if ($_POST["yp_cubicyards"]){
				$calcsize = $_POST["yp_cubicyards"];
			}elseif($_POST["yp_sf"]){
				$calcsize = $_POST["yp_sf"];
			}
			$options["description"] = " - ".$calcsize." ".$calctype;
			$options["custom_price"] = $_POST["yp_price"];
			$options["delivery_date"] = $_POST["yp_deliverydate"];
			$options["shipping_price"] = $_POST["yp_shippingprice"];
			$new_value = array();
			$new_value['_custom_options'] = $options;
			return array_merge($cart_item_data, $new_value);
		}

		public function wdm_get_cart_items_from_session($item,$values,$key) {
			if (array_key_exists( '_custom_options', $values ) ) {
				$item['_custom_options'] = $values['_custom_options'];
			}
			return $item;
		}

		public function add_user_custom_session($product_name, $values, $cart_item_key ) {
			$deliveryDate = $values['_custom_options']['delivery_date'] ? ", Includes Delivery For: ".$values['_custom_options']['delivery_date'] : ", Next Day Delivery";
			$return_string = $product_name . "<br />" . $values['_custom_options']['description'] . $deliveryDate;
			return $return_string;
		}

		/** Add custom meta to product **/
		public function wdm_add_values_to_order_item_meta($item_id, $values) {
			global $woocommerce,$wpdb;
			wc_add_order_item_meta($item_id,'item_details',$values['_custom_options']['description']);
			wc_add_order_item_meta($item_id,'customer_image',$values['_custom_options']['another_example_field']);
			wc_add_order_item_meta($item_id,'_hidden_field',$values['_custom_options']['hidden_info']);
		}

		/** Custom price override **/	
		public function update_custom_price( $cart_object ) {
			foreach ( $cart_object->cart_contents as $cart_item_key => $value ) {
				$shipping_price = $value['_custom_options']['shipping_price'] ? $value['_custom_options']['shipping_price'] : 0;
				$value['data']->price = ($value['_custom_options']['custom_price']+$shipping_price);      
			}
		}

	}
	$YPWP_Shipping = new YPWP_Shipping();

	endif;
