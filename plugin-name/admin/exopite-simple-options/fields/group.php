<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Group
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
/*
 *
 * - check defults
 * - design
 * - add/remove group item
 * - accrodion (open/close) items (after clone, new item open)
 *   https://codepen.io/brenden/pen/Kwbpyj
 *   https://www.jquery-az.com/detailed-guide-use-jquery-accordion-plugin-examples/
 *   http://inspirationalpixels.com/tutorials/creating-an-accordion-with-html-css-jquery
 *   https://www.w3schools.com/howto/howto_js_accordion.asp
 *   http://uniondesign.ca/simple-accordion-without-jquery-ui/
 * - clean up
 *
 * - remove name if empty unique from all fields
 *   so this->name() should include name="" and
 *   fields are not
 */
if( ! class_exists( 'Exopite_Simple_Options_Framework_Field_group' ) ) {
    class Exopite_Simple_Options_Framework_Field_group extends Exopite_Simple_Options_Framework_Fields {

        public function __construct( $field, $value = '', $unique = '', $where = '' ) {
            parent::__construct( $field, $value, $unique, $where );

            $defaults = array(
                'group_title'     => 'Group Title',
                'repeater'        => false,
                'accordion'       => true,
                'limit'           => 0,
                'button_title'    => esc_attr( 'Add new', 'exopite-sof' ),
            );

            $options = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : array();

            $this->field['options'] = wp_parse_args( $options, $defaults );
        }

        public function output() {

            echo $this->element_before();

            $unallows    = array( 'editor', 'group' );
            $fields      = array_values( $this->field['fields'] );
            $unique_id   = ( ! empty( $this->unique ) ) ? $this->unique : $this->field['id'];
            $base_id = ( $this->field['options']['repeater'] ) ? array( 'id' => $this->unique . '[' . $this->field['id'] . '][]' ) : array( 'id' => $this->unique . '[' . $this->field['id'] . ']' );
            $muster_class = ( $this->field['options']['repeater'] ) ? ' exopite-sof-accordion--hidden exopite-sof-cloneable__muster exopite-sof-cloneable__muster--hidden' : '';

            echo '<div class="exopite-sof-group" data-limit="' . $this->field['options']['limit'] . '">';

            echo '<div class="exopite-sof-cloneable__item exopite-sof-accordion__item' . $muster_class . '">';

            if ( $this->field['options']['repeater'] || ( isset( $this->field['options']['group_title'] ) && ! empty( $this->field['options']['group_title'] ) ) ) {

                echo '<h4 class="exopite-sof-cloneable__title exopite-sof-accordion__title"><span class="exopite-sof-cloneable__text">'. $this->field['options']['group_title'] .'</span>';
                echo '<span class="exopite-sof-cloneable--helper">';
                echo '<i class="exopite-sof-cloneable--remove fa fa-times disabled"></i>';
                echo '</span>';
                echo '</h4>';

            }

            echo '<div class="exopite-sof-cloneable__content ';
            if ( ! $this->field['options']['repeater'] ) echo 'exopite-sof-sub-dependencies ';
            echo 'exopite-sof-accordion__content">';

            $self = new Exopite_Simple_Options_Framework( $base_id, null );

            $num = 0;

            foreach ( $fields as $field ) {

                if( in_array( $field['type'], $unallows ) ) {
                    $field['_notice'] = true;
                    continue;
                }

                $field['sub'] = true;
                // $field['wrap_class'] = ( ! empty( $field['wrap_class'] ) ) ? $field['wrap_class'] .' exopite-sof-no-script' : 'exopite-sof-no-script';

                $field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';

                // // If repeater, template field has no values
                // if ( ! $this->field['options']['repeater'] ) $field_value = ( $this->field['options']['repeater'] ) ? null : $this->value[$field['id']];

                // Set repeater default field fields as disabled,
                // to prevent save them.
                // If repeater, template field has no values
                if ( $this->field['options']['repeater'] ) {

                    $field_value = '';

                    $field_attributes = array(
                        'disabled'  => 'only-key',
                    );

                    if ( isset( $field['attributes'] ) && is_array( $field['attributes'] ) ) {
                        $field['attributes'] += $field_attributes;
                    } else {
                        $field['attributes'] = $field_attributes;
                    }

                } else {

                    $field_value = ( isset( $this->value[$field['id']] ) ) ? $this->value[$field['id']] : '';
                    $field_value = ( $this->field['options']['repeater'] ) ? null : $field_value;

                }

                // echo '<pre>';
                // var_export( $field_value );
                // echo '</pre>';

                $self->add_field( $field, $field_value );


            }

            echo '</div>'; // exopite-sof-cloneable-content

            echo '</div>'; // exopite-sof-cloneable__item

            // IF REPEATER

            if ( $this->field['options']['repeater'] ) {

                echo '<div class="exopite-sof-cloneable__wrapper exopite-sof-accordion__wrapper" data-name="' . $this->unique . '[' . $this->field['id'] . ']' . '">';

                if ( $this->value ) {

                    $num = 0;

                    foreach ( $this->value as $key => $value ) {

                        echo '<div class="exopite-sof-cloneable__item exopite-sof-accordion__item exopite-sof-accordion--hidden">';

                        echo '<h4 class="exopite-sof-cloneable__title exopite-sof-accordion__title"><span class="exopite-sof-cloneable__text">'. $this->field['options']['group_title'] .'</span>';
                        echo '<span class="exopite-sof-cloneable--helper">';
                        echo '<i class="exopite-sof-cloneable--remove fa fa-times"></i>';
                        echo '</span>';
                        echo '</h4>';
                        echo '<div class="exopite-sof-cloneable__content exopite-sof-sub-dependencies exopite-sof-accordion__content">';

                        $self->unique = $this->unique . '[' . $this->field['id'] . '][' . $num . ']';

                        foreach ( $fields as $field ) {

                            $field['sub'] = true;

                            if( in_array( $field['type'], $unallows ) ) {
                                continue;
                            }

                            $self->add_field( $field, $this->value[$num][$field['id']] );

                        }

                        echo '</div>'; // exopite-sof-cloneable__content
                        echo '</div>'; // exopite-sof-cloneable__item

                        $num++;

                    }

                }

                echo '</div>'; // exopite-sof-cloneable__wrapper

                echo '<div class="exopite-sof-cloneable-data" data-unique-id="'. $unique_id .'" data-limit="'. $this->field['options']['limit'] .'">'. __( 'Max items:', 'exopite-sof' ) .' '. $this->field['options']['limit'] .'</div>';

                echo '<a href="#" class="button button-primary exopite-sof-cloneable--add">'. $this->field['options']['button_title'] .'</a>';

            }

            echo '</div>'; // exopite-sof-group

            // echo '<pre>';
            // var_export( $this->value );
            // echo '</pre>';

        echo $this->element_after();

    }



    }



}
