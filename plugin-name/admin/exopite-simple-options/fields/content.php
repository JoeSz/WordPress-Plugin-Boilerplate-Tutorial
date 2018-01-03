<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Content
 *
 */
if( ! class_exists( 'Exopite_Simple_Options_Framework_Field_content' ) ) {

    class Exopite_Simple_Options_Framework_Field_content extends Exopite_Simple_Options_Framework_Fields {

        public function __construct( $field, $value = '', $unique = '', $where = '' ) {
            parent::__construct( $field, $value, $unique, $where );
        }

        public function output() {

            echo $this->element_before();
            echo '<div'. $this->element_class() . $this->element_attributes() .'>' . $this->field['content'] . '</div>';
            echo $this->element_after();

        }

    }

}
