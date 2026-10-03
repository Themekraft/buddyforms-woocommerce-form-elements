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

if ( class_exists( 'Element_Textbox' ) ) {
	class bf_woo_elem_regular_price extends Element_Textbox {

		public static function definition() {
			return array(
				'_regular_price' => array(
					'label'  => __( 'Regular Price', 'buddyforms-woocommerce-form-elements' ),
					'unique' => 'unique',
				),
			);
		}

		public function render() {

			$description = $this->field_options['description'];
			if ( ! empty( $this->field_options ) ) {
				$this->_attributes['class'] = 'short wc_input_price';
				$this->_attributes['name']  = '_regular_price';
			}
			echo '<div class="pricing show_if_simple show_if_external">';

			echo '<input', esc_attr( $this->getAttributes() ), '/>';
			echo '<span class="help-inline">' . esc_html( $description ) . '</span>';
			echo '</div>';
			$this->shortDesc = '';
		}

		public static function builder_element_options( $form_fields, $form_slug, $field_type, $field_id, $buddyforms ) {
			unset( $form_fields['advanced']['slug'] );
			unset( $form_fields['advanced']['metabox_enabled'] );
			$form_fields['hidden']['slug'] = new Element_Hidden( 'buddyforms_options[form_fields][' . $field_id . '][slug]', '_regular_price' );
			$form_fields['hidden']['type'] = new Element_Hidden( 'buddyforms_options[form_fields][' . $field_id . '][type]', $field_type );

			return $form_fields;
		}
	}
}
