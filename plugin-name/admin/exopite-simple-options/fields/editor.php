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

            $classes = ( isset( $this->field['class'] ) ) ? explode( ' ', $this->field['class'] ) : array();//$this->element_class()
            $editor = ( isset( $this->field['editor'] ) ) ? $this->field['editor'] : 'tinymce';

            echo $this->element_before();

            if ( $editor == 'tinymce' && isset( $this->field['sub'] ) && $this->field['sub'] ) {

                $classes[] = 'tinymce-js';
                $classes = implode( ' ', $classes );

                echo '<textarea id="' . $this->field['id'] . '" name="'. $this->element_name() .'" class="' . $classes . '"' . $this->element_attributes() .'>'. $this->element_value() .'</textarea>';

            } elseif ( $editor == 'trumbowyg' ) {

                $classes[] = 'trumbowyg-js';
                $classes = implode( ' ', $classes );

                echo '<textarea id="' . $this->field['id'] . '" name="'. $this->element_name() .'" class="' . $classes . '"' . $this->element_attributes() .'>'. $this->element_value() .'</textarea>';

            } else {

                $args = array(
                    'textarea_rows' => 15,
                    'editor_class' => implode( ' ', $classes ),
                    'textarea_name' => $this->element_name(),
                    'teeny' => false, // output the minimal editor config used in Press This
                    'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
                    'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
                    'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
                );

                wp_editor( $this->element_value(), $this->field['id'], $args );

            }

            echo $this->element_after();

        }

        public static function enqueue( $args ) {

            if ( isset( $args['field'] ) && isset( $args['field']['editor'] ) ) {

                switch ( $args['field']['editor'] ) {

                    case 'trumbowyg':

                        wp_enqueue_style( 'trumbowyg', '//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/ui/trumbowyg.min.css', array(), '2.10.0', 'all' );
                        wp_enqueue_style( 'trumbowyg-colors', '//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/plugins/colors/ui/trumbowyg.colors.min.css', array(), '2.10.0', 'all' );

                        /**
                         * From local
                         */
                        // wp_enqueue_style( 'trumbowyg', $args['plugin_sof_url'] . join( '/', array( 'assets', 'editors', 'trumbowyg', 'trumbowyg.min.css' ) ), array(), '2.10.0', 'all' );

                        // $script_files = array(
                        //     'trumbowyg' => 'trumbowyg.min.js',
                        //     'trumbowyg-base64' => 'trumbowyg.base64.min.js',
                        //     'trumbowyg-colors' => 'trumbowyg.colors.min.js',
                        //     'trumbowyg-fontfamily' => 'trumbowyg.fontfamily.min.js',
                        //     'trumbowyg-fontsize' => 'trumbowyg.fontsize.min.js',
                        // );

                        // foreach ( $script_files as $handle => $filename ) {

                        //     wp_enqueue_script( $handle, $args['plugin_sof_url'] . join( '/', array( 'assets', 'editors', 'trumbowyg', $filename ) ), array( 'jquery' ), '2.10.0', true );

                        // }

                        wp_enqueue_style( 'trumbowyg-user', $args['plugin_sof_url'] . join( '/', array( 'assets', 'editors', 'trumbowyg', 'trumbowyg.user.min.css' ) ), array(), '2.10.0', 'all' );

                        wp_enqueue_script( 'trumbowyg', '//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/trumbowyg.min.js', array( 'jquery' ), '2.10.0', true );

                        wp_enqueue_script( 'trumbowyg-base64', '//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/plugins/base64/trumbowyg.base64.min.js', array( 'trumbowyg' ), '2.10.0', true );

                        wp_enqueue_script( 'trumbowyg-colors', '//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/plugins/colors/trumbowyg.colors.min.js', array( 'trumbowyg' ), '2.10.0', true );

                        wp_enqueue_script( 'trumbowyg-fontfamily', '//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/plugins/fontfamily/trumbowyg.fontfamily.min.js', array( 'trumbowyg' ), '2.10.0', true );

                        wp_enqueue_script( 'trumbowyg-fontsize', '//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.10.0/plugins/fontsize/trumbowyg.fontsize.min.js', array( 'trumbowyg' ), '2.10.0', true );

                        $script_file = 'loader-jquery-trumbowyg.min.js';
                        $script_name = 'exopite-sof-trumbowyg-loader';

                        wp_enqueue_script( $script_name, $args['plugin_sof_url'] . 'assets/' . $script_file, array( 'trumbowyg' ), filemtime( join( DIRECTORY_SEPARATOR, array( $args['plugin_sof_path'] . 'assets', $script_file ) ) ), true );

                        break;

                }

            }


        }

    }

}
