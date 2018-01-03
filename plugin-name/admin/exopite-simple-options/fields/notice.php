<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Notice
 *
 */
if( ! class_exists( 'Exopite_Simple_Options_Framework_Field_notice' ) ) {

    class Exopite_Simple_Options_Framework_Field_notice extends Exopite_Simple_Options_Framework_Fields {

        public function __construct( $field, $value = '', $unique = '', $where = '' ) {
            parent::__construct( $field, $value, $unique, $where );
        }

        public function output() {

            $classes = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', $this->field['class'] ) ) : '';

            echo $this->element_before();
            echo '<div class="exopite-sof-notice ' . $classes . '">'. $this->field['content'] .'</div>';
            echo $this->element_after();

        }

    }

}
