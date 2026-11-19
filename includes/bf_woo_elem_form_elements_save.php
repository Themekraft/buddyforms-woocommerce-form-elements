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
}
