<?php
/**
 * Class containing functions used all over the plugin.
 *
 * @package  KWA/Global-Function
 * @category Classes
 */

 if ( ! class_exists( 'kwa_functions' ) ) {
	/**
	 * Contains functions used all over the plugin.
	 *
	 * @class kwa_functions
	 */
	class kwa_functions {

		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'woocommerce_applied_coupon', array( &$this, 'apply_coupon_and_add_extra_product' ) );
		}

		public static function apply_coupon_and_add_extra_product($coupon_code) {
			$target_coupon_code = 'coudouble';

			if ( $coupon_code == $target_coupon_code ) {
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$product_id = $cart_item['product_id'];
					$quantity = $cart_item['quantity'];
					$new_quantity = $quantity + 1;
					
					// Calculate the discounted price
					$original_price = wc_get_product($product_id)->get_price();
					$discounted_price = $original_price * 0.5;

					// Update cart item quantity
					WC()->cart->set_quantity($cart_item_key, $new_quantity, true);
					$coupon = new WC_Coupon($target_coupon_code);
					$coupon->set_amount($discounted_price);
					$coupon->save();
				}
				// Cart totals are recalculated.
				WC()->cart->calculate_totals();
			}
		}

	}
	new kwa_functions();
}