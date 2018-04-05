<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Select
 *
 */
if( ! class_exists( 'Exopite_Simple_Options_Framework_Field_select' ) ) {

    class Exopite_Simple_Options_Framework_Field_select extends Exopite_Simple_Options_Framework_Fields {

        public function __construct( $field, $value = '', $unique = '', $where = '' ) {
            parent::__construct( $field, $value, $unique, $where );
        }

        public function output(){

            echo $this->element_before();

            if( isset( $this->field['options'] ) ) {

                $options    = $this->field['options'];
                $options    = ( is_array( $options ) ) ? $options : array_filter( $this->element_data( $options ) );
                $class      = $this->element_class();
                $extra_name = ( isset( $this->field['attributes']['multiple'] ) ) ? '[]' : '';

                echo '<select name="'. $this->element_name( $extra_name ) .'"'. $this->element_class() . $this->element_attributes() .'>';

                echo ( isset( $this->field['default_option'] ) ) ? '<option value="">'.$this->field['default_option'].'</option>' : '';

                if( ! empty( $options ) ) {

                    foreach ( $options as $key => $value ) {
                        echo '<option value="'. $key .'" '. $this->checked( $this->element_value(), $key, 'selected' ) .'>'. $value .'</option>';

                    }
                }

                echo '</select>';

            }

            echo $this->element_after();

        }

        /*
         * Populate select from wp_query
         */
        public function element_data( $type = '' ) {

            $options = array();
            $query_args = ( isset( $this->field['query_args'] ) ) ? $this->field['query_args'] : array();

            switch( $type ) {

                case 'pages':
                case 'page':

                    $pages = get_pages( $query_args );

                    if ( ! is_wp_error( $pages ) && ! empty( $pages ) ) {
                        foreach ( $pages as $page ) {
                            $options[$page->ID] = $page->post_title;
                        }
                    }

                break;

                case 'posts':
                case 'post':

                    $posts = get_posts( $query_args );

                    if ( ! is_wp_error( $posts ) && ! empty( $posts ) ) {
                        foreach ( $posts as $post ) {
                            $options[$post->ID] = $post->post_title;
                        }
                    }

                break;

                case 'categories':
                case 'category':

                    $categories = get_categories( $query_args );

                    if ( ! is_wp_error( $categories ) && ! empty( $categories ) && ! isset( $categories['errors'] ) ) {
                        foreach ( $categories as $category ) {
                            $options[$category->term_id] = $category->name;
                        }
                    }

                break;

                case 'tags':
                case 'tag':

                    $taxonomies = ( isset( $query_args['taxonomies'] ) ) ? $query_args['taxonomies'] : 'post_tag';
                    $tags = get_terms( $taxonomies, $query_args );

                    if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) {
                        foreach ( $tags as $tag ) {
                            $options[$tag->term_id] = $tag->name;
                        }
                    }

                break;

                case 'menus':
                case 'menu':

                    $menus = wp_get_nav_menus( $query_args );

                    if ( ! is_wp_error( $menus ) && ! empty( $menus ) ) {
                        foreach ( $menus as $menu ) {
                            $options[$menu->term_id] = $menu->name;
                        }
                    }

                break;

                case 'post_types':
                case 'post_type':

                    $post_types = get_post_types( array(
                        'show_in_nav_menus' => true
                    ) );

                    if ( ! is_wp_error( $post_types ) && ! empty( $post_types ) ) {
                        foreach ( $post_types as $post_type ) {
                            $options[$post_type] = ucfirst($post_type);
                        }
                    }

                break;

                case 'custom':
                case 'callback':

                    if( is_callable( $query_args['function'] ) ) {
                        $args = ( isset( $query_args['args'] ) ) ? $query_args['args'] : '';
                        $options = call_user_func( $query_args['function'], $args );
                    }

                break;

            }

            return $options;
        }


        public static function enqueue( $plugin_sof_url, $plugin_sof_path ) {

            /*
             * https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.jquery.min.js
             * https://www.sitepoint.com/jquery-select-box-components-chosen-vs-select2/
             */
            wp_enqueue_style( 'jquery-chosen', '//cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.min.css',  array(), '1.8.2', 'all' );

            wp_enqueue_script( 'jquery-chosen', '//cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.jquery.min.js',  array( 'jquery' ), '1.8.2', true );

            $script_file = 'loader-jquery-chosen.min.js';
            $script_name = 'exopite-sof-jquery-chosen-loader';

            wp_enqueue_script( $script_name, $plugin_sof_url . 'assets/' . $script_file, array( 'jquery-chosen' ), filemtime( join( DIRECTORY_SEPARATOR, array( $plugin_sof_path . 'assets', $script_file ) ) ), true );

        }

    }

}
