<?php

if ( ! class_exists( 'GFForms' ) ) {
	die();
}


class GF_Field_HiddenProduct extends GF_Field {

	public $type = 'hiddenproduct';

	/**
	 * Indicates if this field supports state validation.
	 *
	 * @since 2.5.11
	 *
	 * @var bool
	 */
	protected $_supports_state_validation = true;

	function get_form_editor_field_settings() {
		return array(
			'base_price_setting',
		);
	}

	public function get_form_editor_button() {
		return array();
	}

	public function validate( $value, $form ) {
		$quantity_id = $this->id . '.3';
		$quantity    = rgget( $quantity_id, $value );

		if ( $this->isRequired && rgblank( $quantity ) && ! $this->disableQuantity ) {
			$this->failed_validation  = true;
			$this->validation_message = empty($this->errorMessage) ? esc_html__( 'This field is required.', 'gravityforms' ) : $this->errorMessage;
		} elseif ( ! empty( $quantity ) && ( ! is_numeric( $quantity ) || intval( $quantity ) != floatval( $quantity ) || intval( $quantity ) < 0 ) ) {
			$this->failed_validation  = true;
			$this->validation_message = esc_html__( 'Please enter a valid quantity', 'gravityforms' );
		}
	}
	
	public function get_value_default() {
		$value = array();
		if ( is_array( $this->inputs ) ) {
			foreach ( $this->inputs as $index => $input ) {
				$input_value = $this->is_form_editor() ? rgar( $input, 'defaultValue' ) : GFCommon::replace_variables_prepopulate( rgar( $input, 'defaultValue' ) );
				if ( rgblank( $input_value ) && $input['id'] == "{$this->id}.2" ) {
					$input_value = $this->basePrice;
				}
				$value[ strval( $input['id'] ) ] = $input_value;
			}
		}
		return $value;
	}

	public function get_field_input( $form, $value = '', $entry = null ) {
		$form_id         = $form['id'];
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();

		$id = (int) $this->id;

		$product_name = ! is_array( $value ) || empty( $value[ $this->id . '.1' ] ) ? esc_attr( $this->label ) : esc_attr( $value[ $this->id . '.1' ] );
		$price        = ! is_array( $value ) || empty( $value[ $this->id . '.2' ] ) ? $this->basePrice : esc_attr( $value[ $this->id . '.2' ] );
		$quantity     = is_array( $value ) ? esc_attr( $value[ $this->id . '.3' ] ) : '';

		if ( rgblank( $quantity ) ) {
			$quantity = 1;
		}

		if ( empty( $price ) ) {
			$price = 0;
		}

		$price = esc_attr( $price );

		$has_quantity_field = sizeof( GFCommon::get_product_fields_by_type( $form, array( 'quantity' ), $this->id ) ) > 0;
		if ( $has_quantity_field ) {
			$this->disableQuantity = true;
		}

		$quantity_field     = $has_quantity_field ? '' : "<input type='hidden' name='input_{$id}.3' value='" . esc_attr( $quantity ) . "' id='ginput_quantity_{$form_id}_{$this->id}' class='gform_hidden' />";
		$product_name_field = "<input type='hidden' name='input_{$id}.1' value='{$product_name}' class='gform_hidden' />";

		$disabled_text = $is_form_editor ? 'disabled="disabled"' : '';

		$field_type = $is_entry_detail || $is_form_editor ? 'text' : 'hidden';

		return $quantity_field . $product_name_field . "<input name='input_{$id}.2' id='ginput_base_price_{$form_id}_{$this->id}' type='{$field_type}' value='{$price}' class='gform_hidden ginput_amount' {$disabled_text}/>";
	}

	public function get_value_entry_detail( $value, $currency = '', $use_text = false, $format = 'html', $media = 'screen' ) {
		if ( is_array( $value ) && ! empty( $value ) ) {
			$product_name = trim( $value[ $this->id . '.1' ] );
			$price        = trim( $value[ $this->id . '.2' ] );
			$quantity     = trim( $value[ $this->id . '.3' ] );

			$product_details = $product_name;

			if ( ! rgblank( $quantity ) ) {
				$product_details .= ', ' . esc_html__( 'Qty: ', 'gravityforms' ) . $quantity;
			}

			if ( ! rgblank( $price ) ) {
				$product_details .= ', ' . esc_html__( 'Price: ', 'gravityforms' ) . GFCommon::format_number( $price, 'currency', $currency );
			}

			return $product_details;
		} else {
			return '';
		}
	}

	public function sanitize_settings() {
		parent::sanitize_settings();

		$price_number    = GFCommon::to_number( $this->basePrice );
		$this->basePrice = GFCommon::to_money( $price_number );
	}

}

GF_Fields::register( new GF_Field_HiddenProduct() );