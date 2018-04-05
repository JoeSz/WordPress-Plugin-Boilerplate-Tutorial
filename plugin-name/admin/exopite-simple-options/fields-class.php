<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Options Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'Exopite_Simple_Options_Framework_Fields' ) ) {

    abstract class Exopite_Simple_Options_Framework_Fields {

        public function __construct( $field = array(), $value = '', $unique = '', $where = '' ) {

            $this->field     = $field;
            $this->value     = $value;
            $this->org_value = $value;
            $this->unique    = $unique;
            $this->where     = $where;

        }

        abstract public function output();

        public function element_before() {

            return ( isset( $this->field['before'] ) ) ? '<div class="exopite-sof-before">' . $this->field['before'] . '</div>' : '';

        }

        public function element_after() {

            $out  = ( isset( $this->field['info'] ) ) ? '<span class="exopite-sof-text-desc">'. $this->field['info'] .'</span>' : '';
            $out .= $this->element_help();
            $out .= ( isset( $this->field['after'] ) ) ? '<div class="exopite-sof-after">' . $this->field['after'] . '</div>' : '';
            // $out .= $this->element_get_error();

            return $out;

        }

        public function element_help() {
          return ( isset( $this->field['help'] ) ) ? '<span class="exopite-sof-help" title="'. $this->field['help'] .'" data-title="'. $this->field['help'] .'"><span class="fa fa-question-circle"></span></span>' : '';
        }

        public function element_type() {

            return $this->field['type'];

        }

        public function element_name( $extra_name = '' ) {

            return ( ! empty( $this->unique ) ) ? $this->unique .'['. $this->field['id'] .']' . $extra_name : '';

        }

        public function element_attributes( $el_attributes = array() ) {

            $attributes = ( isset( $this->field['attributes'] ) ) ? $this->field['attributes'] : array();
            $element_id = ( isset( $this->field['id'] ) ) ? $this->field['id'] : '';

            if( $el_attributes !== false ) {
                $sub_elemenet   = ( isset( $this->field['sub'] ) ) ? 'sub-': '';
                $el_attributes  = ( is_string( $el_attributes ) || is_numeric( $el_attributes ) ) ? array('data-'. $sub_elemenet .'depend-id' => $element_id . '_' . $el_attributes ) : $el_attributes;
                $el_attributes  = ( empty( $el_attributes ) && isset( $element_id ) ) ? array('data-'. $sub_elemenet .'depend-id' => $element_id ) : $el_attributes;
            }

            $attributes = wp_parse_args( $attributes, $el_attributes );

            $atts = '';

            if( ! empty( $attributes ) ) {
                foreach ( $attributes as $key => $value ) {
                    if( $value === 'only-key' ) {
                        $atts .= ' '. $key;
                    } else {
                        $atts .= ' '. $key . '="'. $value .'"';
                    }
                }
            }

            return $atts;

        }
        public function element_value( $value = '' ) {

            $value = $this->value;

            if ( empty( $value ) && ! empty( $this->field['default'] ) ) {

                $default = $this->field['default'];

                if( is_array( $default ) ) {

                    if( is_callable( $default['function'] ) ) {
                        $args = ( isset( $default['args'] ) ) ? $default['args'] : '';
                        return call_user_func( $default['function'], $args );
                    }

                }

                return $default;

            }

            return $this->value;

        }

        public function element_class( $el_class = '' ) {

            $field_class = '';


            $classes = ( isset( $this->field['class'] ) ) ? array_merge( explode( ' ', $el_class ), explode( ' ', $this->field['class'] ) ) : explode( ' ', $el_class );
            $classes = array_filter( $classes );
            $field_class = implode( ' ', $classes );

            return ( ! empty( $field_class ) ) ? ' class="'. $field_class .'"' : '';

        }

        public function checked( $helper = '', $current = '', $type = 'checked', $echo = false ) {

            if ( is_array( $helper ) && in_array( $current, $helper ) ) {
                $result = ' '. $type .'="'. $type .'"';
            } else if ( $helper == $current ) {
                $result = ' '. $type .'="'. $type .'"';
            } else {
                $result = '';
            }

            if ( $echo ) {
                echo $result;
            }

            return $result;

        }


    }

}
