<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Color
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_color' ) ) {

	class Exopite_Simple_Options_Framework_Field_color extends Exopite_Simple_Options_Framework_Fields {

		public function __construct( $field, $value = '', $unique = '', $config = array(), $multilang ) {

			parent::__construct( $field, $value, $unique, $config, $multilang );
		}

		public function output() {

			$classes = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', $this->field['class'] ) ) : '';

			/*
			 * Color Picker
			 *
			 * @link https://paulund.co.uk/adding-a-new-color-picker-with-wordpress-3-5
			 */

			echo $this->element_before();
			echo '<input type="';
			if ( isset( $this->field['picker'] ) && $this->field['picker'] == 'html5' ) {
				echo 'color';
			} else {
				echo 'text';
			}
			echo '" ';
			if ( ! isset( $this->field['picker'] ) || $this->field['picker'] != 'html5' ) {
				echo 'class="colorpicker ' . $classes . '" ';
			}
			if ( isset( $this->field['rgba'] ) && $this->field['rgba'] ) {
				echo 'data-alpha="true" ';
			}
			echo 'name="' . $this->element_name() . '" value="' . $this->element_value() . '"';
			if ( isset( $this->field['picker'] ) && $this->field['picker'] == 'html5' ) {
				echo $this->element_class();
			}
			echo $this->element_attributes() . '/>';

		}

		public static function enqueue( $args ) {

			// Add the color picker css file from WordPress
			wp_enqueue_style( 'wp-color-picker' );

			// wp_enqueue_script( 'wp-color-picker-alpha', $args['plugin_sof_url'] . 'assets/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), '2.1.3', true );

			// $script_file = 'loader-color-picker.min.js';
			// $script_name = 'exopite-sof-wp-color-picker-loader';

			// wp_enqueue_script( $script_name, $args['plugin_sof_url'] . 'assets/' . $script_file, array( 'wp-color-picker-alpha' ), filemtime( join( DIRECTORY_SEPARATOR, array(
			// 	$args['plugin_sof_path'] . 'assets',
			// 	$script_file
			// ) ) ), true );

			$resources = array(
				array(
					'name'       => 'wp-color-picker-alpha',
					'fn'         => 'wp-color-picker-alpha.min.js',
					'type'       => 'script',
					'dependency' => array( 'wp-color-picker' ),
					'version'    => '2.1.3',
					'attr'       => true,
				),
				array(
					'name'       => 'exopite-sof-wp-color-picker-loader',
					'fn'         => 'loader-color-picker.min.js',
					'type'       => 'script',
					'dependency' => array( 'wp-color-picker-alpha' ),
					'version'    => '',
					'attr'       => true,
				),
			);

			parent::do_enqueue( $resources, $args );

		}

	}

}
