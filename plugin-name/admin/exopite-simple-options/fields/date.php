<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Date
 *
 */
if( ! class_exists( 'Exopite_Simple_Options_Framework_Field_date' ) ) {

    class Exopite_Simple_Options_Framework_Field_date extends Exopite_Simple_Options_Framework_Fields {

        public function __construct( $field, $value = '', $unique = '', $where = '' ) {
            parent::__construct( $field, $value, $unique, $where );
        }

        public function output(){

            $date_format = ( ! empty( $this->field['format'] ) ) ? $this->field['format'] : 'mm/dd/yy';
            $classes = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', $this->field['class'] ) ) : '';

            echo $this->element_before();
            if( isset( $this->field['picker'] ) && $this->field['picker'] == 'html5' ) {
                echo '<input type="date" ';
            } else {
                echo '<input type="text" ';
                echo 'class="datepicker ' . $classes . '" ';
            }
            echo 'name="'. $this->element_name() .'" ';
            if( isset( $this->field['picker'] ) && $this->field['picker'] == 'html5' ) {
                echo 'value="'. $this->element_value() . '"' . $this->element_class() . $this->element_attributes() .' ';
            } else {
                echo 'value="'. $this->element_value() . '"' . $this->element_attributes() .' ';
                echo 'data-format="' . $date_format . '"';
            }
            echo '>';
            echo $this->element_after();

        }

        public static function enqueue( $plugin_sof_url, $plugin_sof_path ) {

            $script_file = 'loader-datepicker.min.js';
            $script_name = 'exopite-sof-datepicker-loader';

            wp_enqueue_script( $script_name, $plugin_sof_url . 'assets/' . $script_file, array( 'jquery' ), filemtime( join( DIRECTORY_SEPARATOR, array( $plugin_sof_path . 'assets', $script_file ) ) ), true );

            // wp_enqueue_script( 'exopite-sof-datepicker-loader', $plugin_sof_url . 'assets/loader-datepicker.min.js', array( 'wp-color-picker' ), '2.1.3', true );

        }

    }

}
