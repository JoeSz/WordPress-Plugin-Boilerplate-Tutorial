<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Tab
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_tab' ) ) {

	class Exopite_Simple_Options_Framework_Field_tab extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array(), $multilang ) {
			parent::__construct( $field, $value, $unique, $config, $multilang );
		}

		public function output() {

			echo $this->element_before();

			$unallows = array( 'tab', 'group' );
			$tabs    = array_values( $this->field['tabs'] );
			$unique_id = ( ! empty( $this->unique ) ) ? $this->unique : $this->field['id'];

			$self  = new Exopite_Simple_Options_Framework( array(
				'id' => $this->element_name(),
				'multilang' => $this->config['multilang'],
				'is_options_simple' => $this->config['is_options_simple'],
			), null );

			echo '<div class="exopite-sof-tabs">';

			$i = 0;
			foreach ( $tabs as $key => $tab ) {

				$tab_id = $this->field['id'] . '-' . $i;

				reset( $tabs );
				$tab_active = ( $key === key( $tabs ) ) ? ' checked="checked"' : '';
				$equal_width = ( isset( $this->field['options']['equal_width'] ) && $this->field['options']['equal_width'] ) ? ' equal-width' : '';

				/**
				 * @link https://codepen.io/mikestreety/pen/yVNNNm
				 */

				echo '<input name="' . $this->field['id'] . '" type="radio" id="' . $tab_id . '" class="input"' . $tab_active . '>';
				echo '<label for="' . $tab_id . '" class="label' . $equal_width . '">' . $tab['title'] . '</label>';
				echo '<div class="tab">';

				foreach ( $tab['fields'] as $field ) {

					if ( in_array( $field['type'], $unallows ) ) {
						$field['_notice'] = true;
						continue;
					}

					$self->include_field_class( array( 'type' => $field['type'] ) );
					$self->enqueue_field_class( array( 'type' => $field['type'] ) );

					$field_value = '';
					if ( isset( $this->value[ $field['id'] ] ) ) {
						$field_value = $this->value[ $field['id'] ];
					} elseif ( isset( $field['default'] ) ) {
						$field_value = $field['default'];
					}

					echo $self->add_field( $field, $field_value );

				}

				echo '</div>';

				$i++;

			}

			echo '</div>';

			echo $this->element_after();

		}

	}

}
