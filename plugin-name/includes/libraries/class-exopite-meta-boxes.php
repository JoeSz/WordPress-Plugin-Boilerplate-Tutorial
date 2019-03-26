<?php

/**
 * ToDos:
 * - add multi select
 * - move sanitize to his own function
 * - add error messages
 */

/**
 * Register custom post type
 *
 * @link       http://joe.szalai.org
 * @since      1.0.0
 *
 * @package    Exopite_Portfolio
 * @subpackage Exopite_Portfolio/includes
 */
if ( ! class_exists( 'Exopite_Meta_Boxes' ) ) :

class Exopite_Meta_Boxes {

    /**
     * HOW TO USE
     *
     * Specific to fields:
     *     select:
     *         - options
     *     checkbox, radio:
     *         - config
     *         - options
     *     for all:
     *         - attributes
     *         - unit
     *         - before
     *         - after
     *         - default
     *
	 *	 // Add metabox to custom post type
	 *	$metabox_args = array(
     *	    'cpt_name' => array(
     *	        'meta-box-id' => 'meta_box_id',
     *	        'meta-box-title' => 'title',
     *          'meta-box-post-context' => 'advanced' | 'normal' | 'side',
     *          'meta-box-post-priority' => 'high' | 'low',
     *	        'meta-box-fields' => array(
     *	            'content_id' => array(
     *	                'label' => 'Something to say',
	 *					'type' => 'content',
	 *					'default' => 'This is the text of the content field.',
     *	            ),
     *	            'text_id' => array(
     *	                'label' => 'Price',
	 *					'type' => 'text',
	 *					'unit' => '€',
	 *					'attributes' => array(
	 *						'style' => 'max-width:375px;',
	 *					),
     *	            ),
     *	            'hidden_id' => array(
     *	                'label' => 'Hidden',
	 *					'type' => 'hidden',
	 *					'default' => 'hidden_text'
     *	            ),
     *	            'submit_id' => array(
     *	                'label' => 'Submit',
	 *					'type' => 'submit',
	 *					'default' => 'Submit button',
     *	            ),
     *	            'number_id' => array(
     *	                'label' => 'Number',
	 *					'type' => 'number',
	 *					'default' => 5,
	 *					'attributes' => array(
	 *						'min' => '3',
	 *						'max' => '8',
	 *					),
     *	            ),
     *	            'text_with_attrs' => array(
     *	                // 'label' => 'Label is optional',
	 *					'type' => 'text',
	 *					'attributes' => array(
	 *						'placeholder' => 'Placeholder',
	 *						'disabled' => 'disabled',
	 *					),
     *
     *	            ),
     *	            'select_id' => array(
     *	                'label' => 'Select',
	 *					'type' => 'select',
	 *					'options' => $this->some_function(),
	 *				),
	 *				'checkbox' => array(
	 *					'label' => 'Checkbox',
	 *					'type' => 'checkbox',
	 *				),
	 *				'checkbox_multiple' => array(
	 *					'label' => 'Multiple Checkboxes',
	 *					'type' => 'checkbox',
	 *					'options' => array(
	 *					    'option-1' => 'Option 1',
	 *						'option-2' => 'Option 2',
	 *						'option-3' => 'Option 3',
	 *					),
	 *					// 'config' => array(
	 *					// 	'style' => 'vertical',
	 *					// ),
	 *				),
	 *				'radio' => array(
	 *					'label' => 'Radio',
	 *					'type' => 'radio',
	 *					'options' => array(
	 *					    'option-1' => 'Option 1',
	 *						'option-2' => 'Option 2',
	 *						'option-3' => 'Option 3',
	 *					),
	 *					'config' => array(
	 *						'style' => 'vertical',
	 *					),
	 *				),
	 *				'radio_default' => array(
	 *					'label' => 'Radio',
	 *					'type' => 'radio',
	 *					'options' => array(
	 *					    'option-1' => 'Option 1',
	 *						'option-2' => 'Option 2',
	 *						'option-3' => 'Option 3',
	 *					),
	 *					'default' => 'option-3',
	 *				),
     *	        ),
     *	        'meta-box-wrapper-class' => 'wrapper',
     *	        'meta-box-wrapper-selector' => 'div',
     *	        'meta-box-row-class' => 'row',
	 *			'meta-box-row-selector' => 'div',
	 *			// 'meta-box-debug' => true,
     *
     *	   	),
	 *	);
     *
	 *	$plugin_meta_boxes = new Exopite_Meta_Boxes( $metabox_args, PLUGIN_URL );
     */
    protected $args = array();

    protected $plugin_url;
    // protected $unique;

    public function __construct( $args, $plugin_url ) {

        $this->args = $args;
        $this->plugin_url = $plugin_url;

        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

        /**
         * Add metabox and register custom fields
         *
         * @link https://code.tutsplus.com/articles/rock-solid-wordpress-30-themes-using-custom-post-types--net-12093
         */
        add_action( 'admin_init', array( $this, 'render_meta_options' ) );
        add_action( 'save_post', array( $this, 'save_meta_options' ) );

    }

    public function admin_enqueue_scripts() {

        wp_enqueue_style( 'exopite_meta_box_style_css', join( '/', array( rtrim( $this->plugin_url, '/' ), 'admin', 'css', 'exopite-meta-box-style.css' ) ) );

    }

    /* Create a meta box for our custom fields */
    public function render_meta_options() {

        foreach ( $this->args as $cpt_name => $options ) {

            /**
             *  add_meta_box(
             *      string $id,
             *      string $title,
             *      callable $callback,
             *      string|array|WP_Screen $screen = null,
             *      string $context = 'advanced',
             *      string $priority = 'default',
             *      array $callback_args = null
             *  )
             *
             * @link https://developer.wordpress.org/reference/functions/add_meta_box/
             */
            add_meta_box(
                $options["meta-box-id"],
                $options["meta-box-title"],
                array( $this, "render_meta_box" ),
                $cpt_name,
                $options["meta-box-post-context"],
                $options["meta-box-post-priority"]
            );

        }

    }

    public function get_wrapper_start( $options ) {

        ?>
        <<?php echo $options['meta-box-wrapper-selector']; ?> class="exopite-meta-boxes-wrapper <?php echo $options['meta-box-wrapper-class']; ?>">
        <?php

    }

    public function get_wrapper_end( $options ) {

        ?>
        </<?php echo $options['meta-box-wrapper-selector']; ?>>
        <?php

    }

    public function get_row_start( $name, $options, $field ) {

        $row_classes = 'meta-row';
        if ( isset( $options['meta-box-row-class'] ) ) {
            $row_classes .= ' ' . $options['meta-box-row-class'];
        }

        ?>
        <<?php echo $options['meta-box-row-selector']; ?> class="<?php echo $row_classes; ?>"><label for="<?php echo $name; ?>"><?php echo $field['label']; ?></label><span>
        <?php

    }

    public function get_row_end( $options ) {

        ?>
        </span></<?php echo $options['meta-box-row-selector']; ?>>
        <?php

    }

    public function get_field_attributes( $name, $field ) {

        $id = 'id="' . $name .'"';

        $field_classes = '';
        if ( isset( $field['classes'] ) ) {
            $field_classes = ' class="' . $field['classes'] . '" ';
        }

        $attributes = array();
        if ( isset( $field['attributes'] ) ) {
            foreach ( $field['attributes'] as $key => $value ) {
                $attributes[] = $key . '="' . $value . '"';
            }
        }

        if ( $field['type'] == 'checkbox' && isset( $field['options'] ) ) {
            $name = $name . '[]';
        }

        /**
         * Remove id from multiple fields
         */
        if ( ( $field['type'] == 'checkbox' || $field['type'] == 'radio' ) && isset( $field['options'] ) ) {
            $id = '';
        }

        echo $id .  $field_classes . ' name="' . $name . '" ' . implode( ' ', $attributes );

    }

    public function get_field_before( $field ) {

        if ( isset( $field['before'] ) ) {

            echo $field['before'];

        }

    }

    public function get_field_after( $field ) {

        if ( isset( $field['after'] ) ) {

            echo $field['after'];

        }

    }

    public function get_field_unit( $field ) {

        if ( isset( $field['unit'] ) ) {

            $unit_classes = 'exopite-meta-boxes-unit muted';

            switch ( $field['type'] ) {
                case 'text':
                case 'textarea':
                    // $unit_classes .= ' exopite-meta-boxes-unit-fixed';
                    break;

            }
            echo '<span class="' . $unit_classes . '">' . $field['unit'] . '</span>';

        }

    }

    public function get_field_value( $name, $custom, $field ) {

        /**
         * ToDos:
         * - deal with defaults
         */

        $value = maybe_unserialize( $custom[$name][0] );

        if ( ! isset( $value ) ) {

            if ( isset( $field['default'] ) ) {
                $value = $field['default'];
            } else {
                $value = null;
            }

        }

        switch ( $field['type'] ) {

            case 'checkbox':

                if ( is_array( $value ) ) {

                    return $value;

                } else {

                    return esc_attr( $value );

                }
                break;

            case 'content':
                return $value;
                break;

            case 'textarea':
                return esc_textarea( $value );
                break;

            default:
                return esc_attr( $value );
                break;

        }

    }

    public function checked( $value, $key ) {

        if ( isset( $value ) && is_array( $value ) && in_array( $key, $value ) ) {
            echo " checked";
        }

    }

    // Display meta box and custom fields
    public function render_meta_box() {

        // Get Post object
        global $post;

        // Get post custom
        $custom = get_post_custom( $post->ID );

        $post_type = get_post_type();

        // Generate nonce
        wp_nonce_field( 'meta_box_nonce', 'meta_box_nonce' );

        foreach ( $this->args as $cpt_name => $options ) {

            if ( isset( $options['meta-box-debug'] ) && $options['meta-box-debug'] ) {
                echo '<pre>';
                var_export( $custom );
                echo '</pre>';
            }

            if ( $post_type == $cpt_name ) {

                $this->get_wrapper_start( $options );

                foreach ( $options['meta-box-fields'] as $name => $field ) {

                    $this->get_row_start( $name, $options, $field );

                    $this->get_field_before( $field );

                    switch ( $field['type'] ) {

                        case 'content':
                            echo $this->get_field_value( $name, $custom, $field );
                            break;

                        case 'password':
                            ?>
                            <input type="password" <?php $this->get_field_attributes( $name, $field ); ?> value="<?php echo $this->get_field_value( $name, $custom, $field ); ?>">
                            <?php
                            break;

                        case 'textarea':
                            ?>
                            <textarea <?php $this->get_field_attributes( $name, $field ); ?>><?php echo $this->get_field_value( $name, $custom, $field ); ?></textarea>
                            <?php
                            break;

                        case 'radio':
                            if ( isset( $field['options'] ) ) {

                                foreach ( $field['options'] as $key => $element ) :

                                    $label_attr = '';

                                    if ( isset( $field['config'] ) && isset( $field['config']['style'] ) && $field['config']['style'] == 'vertical' ) {
                                        $label_attr = ' style="display:block;"';
                                    }

                                    ?>
                                    <label<?php echo $label_attr; ?>>
                                        <input type="radio" <?php $this->get_field_attributes( $name, $field ); ?> value="<?php echo $key; ?>" <?php checked( $this->get_field_value( $name, $custom, $field ), $key ); ?> />
                                        <?php
                                            echo $element;
                                        ?>
                                    </label>
                                    <?php

                                endforeach;

                            }
                            break;

                        case 'checkbox':
                            if ( isset( $field['options'] ) ) {

                                foreach ( $field['options'] as $key => $element ) :

                                    $label_attr = '';

                                    if ( isset( $field['config'] ) && isset( $field['config']['style'] ) && $field['config']['style'] == 'vertical' ) {
                                        $label_attr = ' style="display:block;"';
                                    }

                                    ?>
                                    <label<?php echo $label_attr; ?>>
                                        <input type="checkbox" <?php $this->get_field_attributes( $name, $field ); ?> value="<?php echo $key; ?>" <?php $this->checked( $this->get_field_value( $name, $custom, $field ), $key ); ?> />
                                        <?php
                                            echo $element;
                                        ?>
                                    </label>
                                    <?php

                                endforeach;

                            } else {
                                ?>
                                <input type="checkbox" <?php $this->get_field_attributes( $name, $field ); ?> value="yes" <?php checked( $this->get_field_value( $name, $custom, $field ), 'yes' ); ?> />
                                <?php
                            }
                            break;

                        case 'select':
                            ?>
                            <select <?php $this->get_field_attributes( $name, $field ); ?>>
                                <?php
                                foreach ( $field['options'] as $key => $value ) :
                                    ?>
                                    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $this->get_field_value( $name, $custom, $field ) ); ?>><?php echo $value; ?></option>
                                    <?php
                                endforeach;
                                ?>
                            </select>
                            <?php
                            break;

                       case 'upload':

                            $field['classes'] .= ' exopite-meta-boxes-upload-url';

                            $value = $this->get_field_value( $name, $custom, $field );

                            if ( isset( $field['config']['preview'] ) && $field['config']['preview'] ) :

                                $preview_attrs = '';
                                if ( isset( $value ) && ! empty( trim( $value ) ) && $this->is_image( $value ) ) {
                                    $preview_attrs = ' style="display:block;background-image:url(' . $value . ')"';
                                }

                                ?>
                                <span class="exopite-meta-boxes-upload-preview"<?php echo $preview_attrs; ?>><span class="exopite-meta-boxes-upload-preview-close">×</span></span>
                                <?php

                            endif;

                            /**
                             * The actual field that will hold the URL for our file
                             */
                            ?>
                            <input type="url" <?php $this->get_field_attributes( $name, $field ); ?> value="<?php echo $value; ?>">
                            <?php
                                /**
                                 * The button that opens our media uploader
                                 * The `data-media-uploader-target` value should match the ID/unique selector of your field.
                                 * We'll use this value to dynamically inject the file URL of our uploaded media asset into your field once successful (in the myplugin-media.js file)
                                 */
                            ?>
                            <button type="button" class="button exopite-meta-boxes-upload-button"><?php echo ( isset( $field['config']['button_text'] ) ) ? $field['config']['button_text'] : 'Upload'; ?></button>

                            <?php
                            break;

                        default:

                            ?>
                            <input type="<?php echo $field['type']; ?>" <?php $this->get_field_attributes( $name, $field ); ?> value="<?php echo $this->get_field_value( $name, $custom, $field ); ?>">
                            <?php
                            break;

                    }

                    $this->get_field_unit( $field );

                    $this->get_field_after( $field );

                    $this->get_row_end( $options );


                }

                $this->get_wrapper_end( $options );

            }

        }

    }

    public function check_rights() {

        // Check nonce
        if( ! isset( $_POST['meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['meta_box_nonce'], 'meta_box_nonce' ) ) return false;

       // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return false;

        // Prevent quick edit from clearing custom fields
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) return false;

        // Check the user's permissions.
        if ( ! current_user_can( 'edit_post', $post->ID ) ) return false;

        return true;

    }

    // Save custom fields
    public function save_meta_options() {

        // Get Post object
        global $post;

        if ( ! $this->check_rights() ) return;

        foreach ( $this->args as $cpt_name => $options ) {

            foreach ( $options['meta-box-fields'] as $name => $value ) {

                // 'text' | 'password' | 'textarea' | 'select' | 'radio' | 'checkbox'
                switch ( $value['type'] ) {

                    case 'checkbox':

                        if ( is_array( $_POST[$name] ) ) {

                            /**
                             * ToDos:
                             * - sanitize array each
                             */
                            update_post_meta( $post->ID, $name, $_POST[$name] );

                        } else {

                            // Sanitize user input.
                            $sanitized_value = $_POST[$name] ? 'yes' : 'no';

                            // Update the meta field in the database.
                            update_post_meta( $post->ID, $name, $sanitized_value );

                        }

                        break;
                    case 'textarea':
                        update_post_meta( $post->ID, $name, sanitize_textarea_field( $_POST[$name] ) );
                        break;

                    default:
                        update_post_meta( $post->ID, $name, sanitize_text_field( $_POST[$name] ) );
                        break;

                }

            }

        }

    }

}
endif;
