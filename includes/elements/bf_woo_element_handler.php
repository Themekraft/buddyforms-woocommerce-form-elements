<?php
/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */


class bf_woo_element_handler {

	public function __construct() {
		// Add to field list
		add_filter( 'buddyforms_add_form_element_select_option', array( $this, 'bf_woo_elem_price_form_builder_elements_select' ), 10 );
		// Create field options inside after it is added to teh form
		add_filter( 'buddyforms_form_element_add_field', array( $this, 'bf_woo_elem_price_create_new_form_builder_form_element' ), 999, 5 );
		// Add element to the frontend
		add_filter( 'buddyforms_create_edit_form_display_element', array( $this, 'bf_woo_elem_price_create_new_form_builder' ), 1, 2 );
		// Default value to show in the submission view from the backend
		// add_filter( 'bf_submission_column_default', array( $this, 'bf_woo_elem_price_custom_column_default' ), 10, 4 );
	}

	/**
	 * Return array of filed to show inside the dropdown
	 *
	 * @param $elements_select_options
	 *
	 * @return array
	 */
	public function bf_woo_elem_price_form_builder_elements_select( $elements_select_options ) {
		global $post;

		if ( $post->post_type !== 'buddyforms' ) {
			return $elements_select_options;
		}

		$bf_version = floatval( BUDDYFORMS_VERSION );
		if ( $bf_version < 2.5 ) {
			return $elements_select_options;
		}

		if ( ! empty( $elements_select_options['woocommerce'] ) && ! empty( $elements_select_options['woocommerce']['fields'] ) ) {

			$elements_select_options['woocommerce']['fields'] = array_merge( $elements_select_options['woocommerce']['fields'], bf_woo_elem_regular_price::definition() );
			$elements_select_options['woocommerce']['fields'] = array_merge( $elements_select_options['woocommerce']['fields'], bf_woo_elem_sale_price::definition() );
			$elements_select_options['woocommerce']['fields'] = array_merge( $elements_select_options['woocommerce']['fields'], bf_woo_elem_prod_type::definition() );

		}

		return $elements_select_options;
	}

	/**
	 * Create the form element options to use in the backend builder
	 *
	 * @param Form      $form
	 * @param $form_args
	 *
	 * @return mixed
	 * @var $name
	 */
	public function bf_woo_elem_price_create_new_form_builder( $form, $form_args ) {
		global $post;
		$slug         = '';
		$name         = '';
		$customfield  = array();
		$element_attr = array();

		extract( $form_args );

		if (
			! empty( $slug ) &&
			! empty( $name ) &&
			! empty( $customfield ) &&
			! empty( $element_attr )
		) {

			bf_woo_elem_form_element::add_scripts( $post );
			bf_woo_elem_form_element::add_styles();
			/** @var string $field_type */
			switch ( $field_type ) {
				case '_regular_price':
					$form->addElement( new bf_woo_elem_regular_price( $name, $slug, $element_attr, $customfield ) );
					break;
				case '_sale_price':
					$form->addElement( new bf_woo_elem_sale_price( $name, $slug, $element_attr, $customfield ) );
					break;
				case 'product-type':
					if ( isset( $customfield['hidden_field'] ) ) {
						$form->addElement( new Element_Hidden( $slug, $customfield['product_type_default'] ) );
					} else {
						$form->addElement( new bf_woo_elem_prod_type( $name, $slug, $element_attr, $customfield ) );
					}

					break;
			}
		}

		return $form;
	}

	/**
	 * Create the form element to render in the final field
	 *
	 * @param $form_fields
	 * @param $form_slug
	 * @param $field_type
	 * @param $field_id
	 *
	 * @return array
	 */
	public function bf_woo_elem_price_create_new_form_builder_form_element( $form_fields, $form_slug, $field_type, $field_id ) {
		global $post, $buddyform;

		switch ( $field_type ) {
			case 'product-type':
				$form_fields = bf_woo_elem_prod_type::builder_element_options( $form_fields, $form_slug, $field_type, $field_id, $buddyform );
				break;
			case '_regular_price':
				$form_fields = bf_woo_elem_regular_price::builder_element_options( $form_fields, $form_slug, $field_type, $field_id, $buddyform );
				break;
			case '_sale_price':
				$form_fields = bf_woo_elem_sale_price::builder_element_options( $form_fields, $form_slug, $field_type, $field_id, $buddyform );
				break;

		}

		return $form_fields;
	}

	// TODO need implementation
	public function bf_woo_elem_price_custom_column_default( $bf_value, $item, $column_name, $field_slug ) {
		global $buddyforms;
		if ( $column_name === 'woocommerce' ) {
			$url           = get_permalink( $item->ID );
			$product_title = get_the_title( $item->ID );

			return " <a style='vertical-align: top;' target='_blank' href='" . $url . "'>${product_title}</a>";
		}

		if ( $column_name === 'product-gallery' ) {
			$result  = '';
			$gallery = $column_val = get_post_meta( intval( $item->ID ), '_product_image_gallery', true );
			$src     = wp_get_attachment_url( $gallery );

			if ( ! empty( $gallery ) && ! empty( $src ) ) {
				$result = wp_get_attachment_image( $gallery, array( 50, 50 ), true ) . " <a style='vertical-align: top;' target='_blank' href='" . $src . "'>" . __( 'Full Image', 'buddyforms-woocommerce-form-elements' ) . '</a>';
			}

			return $result;
		}

		return $bf_value;
	}
}
