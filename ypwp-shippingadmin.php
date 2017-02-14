<?php

class YPWP_ShippingAdmin {
	public function __construct(){
		add_action( 'woocommerce_product_options_general_product_data', array($this, 'woo_add_custom_general_fields') );
		add_action( 'woocommerce_process_product_meta', array($this,'woo_add_custom_general_fields_save') );
	}

	// - - - - - Add custom field to Product to determine if price is calculated by Standard, Square Foot, Cubic Yard
	public function woo_add_custom_general_fields() {
		global $woocommerce, $post;  
		echo '<div class="options_group">';
		// Select Standard, Square Foot, Cubic Yard
		woocommerce_wp_select( 
			array( 
				'id'      => '_select', 
				'label'   => __( 'Charge By', 'woocommerce' ), 
				'options' => array(
					'st'   => __( 'Standard', 'woocommerce' ),
					'sf'   => __( 'Square Foot', 'woocommerce' ),
					'cu' => __( 'Cubic Yard', 'woocommerce' )
					)
				)
			);
		echo '</div>';	
	}
	// - - - - end - - - - - //

	// - - - - - Save custom field for Product
	public function woo_add_custom_general_fields_save( $post_id ){
		// Select
		$woocommerce_select = $_POST['_select'];
		if( !empty( $woocommerce_select ) )
			update_post_meta( $post_id, '_select', esc_attr( $woocommerce_select ) );		
	}
	// - - - - end - - - - - //
	
}
$YPWP_ShippingAdmin = new YPWP_ShippingAdmin();

?>