<?php

/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */

class bf_woo_elem_form_elements_save {

	private $bf_wc_save_meta = false;

	private $bf_wc_save_gallery = false;

	public function __construct() {
		add_action( 'buddyforms_update_post_meta', array( $this, 'buddyforms_woocommerce_update_post_meta' ), 99, 2 );
		add_action( 'buddyforms_after_save_post', array( $this, 'buddyforms_woocommerce_update_wc_post_meta' ), 991, 1 );
	}

	public function buddyforms_woocommerce_update_post_meta( $customfield, $post_id = 0 ) {
		if ( $customfield['type'] === 'woocommerce' || $customfield['type'] === '_regular_price'
		|| $customfield['type'] === '_sale_price' ) {
			$this->bf_wc_save_meta = true;
		}
		if ( $customfield['type'] === 'product-gallery' ) {
			$this->bf_wc_save_gallery = true;
		}
	}

	public function buddyforms_woocommerce_update_wc_post_meta( $post_id = 0 ) {

		if ( $this->bf_wc_save_meta || $this->bf_wc_save_gallery ) {
			$post = get_post( $post_id );

			$form_slug   = buddyforms_get_form_slug_by_post_id( $post_id );
			if ( ! bf_woo_elem_form_element::form_creates_products( $form_slug ) ) {
				return;
			}
			$post_status = 'publish';
			if ( ! empty( $form_slug ) ) {
				global $buddyforms;
				if ( ! empty( $buddyforms ) && ! empty( $buddyforms[ $form_slug ] ) ) {
					$post_status = ! empty( $buddyforms[ $form_slug ]['status'] ) ? $buddyforms[ $form_slug ]['status'] : 'publish';
				}

				/**
				 * Add support for BF Moderation
				 */
				if ( isset( $post->post_status ) && ( $post->post_status === 'edit-draft' || $post->post_status === 'awaiting-review' || $post->post_status === 'approved' ) ) {
					$post_status = $post->post_status;
				}
			}

			$update_post_type = array(
				'ID'          => $post_id,
				'post_name'   => $post->post_title,
				'post_type'   => 'product',
				'post_status' => $post_status,
			);
			wp_update_post( $update_post_type, true );
			update_post_meta( $post_id, '_visibility', 'visible' );

			if ( $this->bf_wc_save_meta ) {
				$this->apply_form_product_settings( $form_slug, $post_id );
				$_POST['_visibility'] = 'visible';
				delete_post_meta( $post_id, '_regular_price' );
				delete_post_meta( $post_id, '_sale_price' );
				WC_Meta_Box_Product_Data::save( $post_id, $post );
				update_post_meta( $post_id, 'woocommerce', $post_id );
			}

			if ( $this->bf_wc_save_gallery ) {
				WC_Meta_Box_Product_Images::save( $post_id, $post );
				update_post_meta( $post_id, 'product-gallery', $post_id );
			}
		}
	}

	/**
	 * Apply the product settings the form fixes (product type, prices, sale dates, SKU) to the
	 * submitted data, so a submission cannot change what the form hides from the submitter.
	 *
	 * @param string $form_slug
	 * @param int    $post_id
	 */
	private function apply_form_product_settings( $form_slug, $post_id ) {
		global $buddyforms;

		if ( empty( $buddyforms[ $form_slug ]['form_fields'] ) || ! is_array( $buddyforms[ $form_slug ]['form_fields'] ) ) {
			return;
		}

		foreach ( $buddyforms[ $form_slug ]['form_fields'] as $field ) {
			if ( empty( $field['type'] ) || 'woocommerce' !== $field['type'] ) {
				continue;
			}

			// Product type and its options.
			$type_hidden = ! empty( $field['product_type_hidden'] ) ? (array) $field['product_type_hidden'] : array();
			if ( 'hide_product_type' === reset( $type_hidden ) ) {
				if ( ! empty( $field['product_type_default'] ) && 'false' !== $field['product_type_default'] ) {
					$_POST['product-type'] = $field['product_type_default'];
				}
				$type_options = ! empty( $field['product_type_options'] ) && is_array( $field['product_type_options'] ) ? $field['product_type_options'] : array();
				if ( isset( $type_options['_virtual'] ) ) {
					$_POST['_virtual'] = 'on';
				}
				if ( isset( $type_options['_downloadable'] ) ) {
					$_POST['_downloadable'] = 'on';
				} else {
					unset( $_POST['_downloadable'] );
				}
			}

			// Regular price.
			if ( ! empty( $field['product_regular_price'] ) && 'hidden' === $field['product_regular_price'] ) {
				$_POST['_regular_price'] = isset( $field['regular_price_amount'] ) && '' !== $field['regular_price_amount'] ? wc_format_decimal( $field['regular_price_amount'] ) : '';
			}

			// Sale price: the builder saves 0 as the default amount, which would make the product free.
			if ( ! empty( $field['product_sales_price'] ) && 'hidden' === $field['product_sales_price'] ) {
				$_POST['_sale_price'] = isset( $field['sales_price_amount'] ) && (float) $field['sales_price_amount'] > 0 ? wc_format_decimal( $field['sales_price_amount'] ) : '';
			}

			// Sale price dates.
			if ( ! empty( $field['product_sales_price_dates'] ) && 'hidden' === $field['product_sales_price_dates'] ) {
				$has_dates                        = ! empty( $field['product_sales_start_date'] ) && ! empty( $field['product_sales_end_date'] );
				$_POST['_sale_price_dates_from'] = $has_dates ? $field['product_sales_start_date'] : '';
				$_POST['_sale_price_dates_to']   = $has_dates ? $field['product_sales_end_date'] : '';
			}

			// SKU.
			if ( ! empty( $field['product_sku'] ) && 'hidden' === $field['product_sku'] ) {
				$_POST['_sku'] = ! empty( $field['sku_value'] ) ? $field['sku_value'] : (string) get_post_meta( $post_id, '_sku', true );
			}
		}
	}
}
