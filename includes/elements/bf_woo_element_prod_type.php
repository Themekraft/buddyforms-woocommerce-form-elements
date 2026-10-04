<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/*
 * @package WordPress
 * @subpackage BuddyPress, Woocommerce, BuddyForms
 * @author ThemKraft Dev Team
 * @copyright 2017, Themekraft
 * @link http://buddyforms.com/downloads/buddyforms-woocommerce-form-elements/
 * @license GPLv2 or later
 */
if ( class_exists( 'Element_Select' ) ) {
	class bf_woo_elem_prod_type extends Element_Select {

		public static function definition() {
			return array(
				'product-type' => array(
					'label'  => __( 'Product Type', 'buddyforms-woocommerce-form-elements' ),
					'unique' => 'unique',
				),
			);
		}

		public function render() {
			global $buddyforms;

			$this->_attributes['id']    = 'product-type';
			$this->_attributes['name']  = 'product-type';
			$form_slug                  = $this->options['data-form'];
			$field_id                   = $this->options['field_id'];
			$form_fields                = $buddyforms[ $form_slug ]['form_fields'];
			$this->_attributes['value'] = isset( $form_fields[ $field_id ]['product_type_default'] ) ? $form_fields[ $field_id ]['product_type_default'] : 'simple';
			$product_type_selector      = wc_get_product_types();
			$this->options              = $product_type_selector;
			parent::render();
		}

		function renderJS() {
			$value = $this->_attributes['product_type_default'];
			echo 'jQuery(function() {';
			echo 'jQuery("#product-type").val("' . esc_js( $value ) . '");';
			echo '});';
		}

		public static function builder_element_options( $form_fields, $form_slug, $field_type, $field_id, $buddyforms ) {
			unset( $form_fields['advanced']['slug'] );
			unset( $form_fields['advanced']['metabox_enabled'] );
			$form_fields['hidden']['slug'] = new Element_Hidden( 'buddyforms_options[form_fields][' . $field_id . '][slug]', 'product-type' );
			$form_fields['hidden']['type'] = new Element_Hidden( 'buddyforms_options[form_fields][' . $field_id . '][type]', $field_type );
			$product_type_default          = apply_filters( 'default_product_type', 'simple' );
			if ( isset( $buddyforms['form_fields'][ $field_id ]['product_type_default'] ) ) {
				$product_type_default = $buddyforms['form_fields'][ $field_id ]['product_type_default'];
			}
			$product_type = apply_filters( 'default_product_type', 'simple' );

			$product_type_selector = wc_get_product_types();

			$form_fields['general']['product_type_default'] = new Element_Select(
				'<b>' . __( 'Default Product Type: ', 'buddyforms-woocommerce-form-elements' ) . '</b>',
				'buddyforms_options[form_fields][' . $field_id . '][product_type_default]',
				$product_type_selector,
				array(
					'id'       => 'product-type',
					'class'    => '',
					'value'    => $product_type_default,
					'selected' => isset( $product_type_default ) ? $product_type_default : 'false',
				)
			);

			$hidden                                  = isset( $buddyforms['form_fields'][ $field_id ]['hidden_field'] ) ? $buddyforms['form_fields'][ $field_id ]['hidden_field'] : false;
			$form_fields['advanced']['hidden_field'] = new Element_Checkbox( '<b>' . __( 'Hidden?', 'buddyforms-woocommerce-form-elements' ) . '</b>', 'buddyforms_options[form_fields][' . $field_id . '][hidden_field]', array( 'hidden_field' => '<b>' . __( 'Make this field Hidden', 'buddyforms-woocommerce-form-elements' ) . '</b>' ), array( 'value' => $hidden ) );

			return $form_fields;
		}
	}
}
