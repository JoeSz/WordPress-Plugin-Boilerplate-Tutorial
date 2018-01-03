<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Editor
 *
 */
if( ! class_exists( 'Exopite_Simple_Options_Framework_Field_editor' ) ) {

    class Exopite_Simple_Options_Framework_Field_editor extends Exopite_Simple_Options_Framework_Fields {

        public function __construct( $field, $value = '', $unique = '', $where = '' ) {
            parent::__construct( $field, $value, $unique, $where );
        }

        public function output(){

            $classes = ( isset( $this->field['class'] ) ) ? implode( ' ', explode( ' ', $this->field['class'] ) ) : '';

            echo $this->element_before();

            $args = array(
                'textarea_rows' => 15,
                'editor_class' => $classes,
                'textarea_name' => $this->element_name(),
                'teeny' => false, // output the minimal editor config used in Press This
                'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
                'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
                'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
            );

            wp_editor( $this->element_value(), $this->field['id'], $args );

            echo $this->element_after();

        }

    }

}
