<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Options Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Sanitize' ) ) {

	class Exopite_Simple_Options_Framework_Sanitize {

		public $is_multilang;
		public $lang_current;
		public $config;

		public function __construct( $is_multilang, $lang_current, $config ) {
			/**
			 * Sanitization order:
			 * - save
			 * - get_sanitized_fields_values
			 * - get_sanitized_field_value_from_global_post
			 * - sanitize
			 * - get_sanitized_field_value_by_type
			 */
			$this->is_multilang = $is_multilang;
			$this->lang_current = $lang_current;
			$this->config = $config;
		}

		/**
		 * FOR TEST
		 */
        public static function flat_concatenate_array( $array, $prefix = '' ) {
            //
            $result = array();
            foreach( $array as $key => $value ) {
                if( is_array( $value ) ) {
                    $result = $result + self::flat_concatenate_array( $value, $prefix . $key . '.' );
                }
                else {
                    $result[$prefix . $key] = $value;
                }
            }
            return $result;
        }

		/**
		 * Array infos:
		 * https://stackoverflow.com/questions/7003559/use-strings-to-access-potentially-large-multidimensional-arrays
		 * https://stackoverflow.com/questions/6625808/how-can-i-use-a-php-array-as-a-path-to-target-a-value-in-another-array/6625931#6625931
		 */
		/**
		 * Loop fields and create a POST like array
		 *
		 */
		function loop_field_items( $fields, &$path ) {

			foreach( $fields as $key => $value ) {

				/**
				 * If sub array, call self, need to keep position
				 *
				 * Loop fields
				 * if element has fields and ID then work with (save id and loop fields)
				 * if not loop fields but do not save ID (ID is the path)
				 *
				 * 'group_t2.0.group_t3.0.group_t4.0.editor_trumbowyg_l4'
				 * path = array( group_t2, group_t3, group_t4 );
				 *
				 */
				if ( is_array( $value ) ) {

					$this->loop_field_items( $value, $path );

				} else {

					/**
					 * Add field to path
					 */

				}

			}

		}
		/**
		 * END: FOR TEST
		 */

		//DEGUB
		public function write_log( $type, $log_line ) {

			$hash        = '';
			$fn          = plugin_dir_path( __FILE__ ) . '/' . $type . '-' . $hash . '.log';
			$log_in_file = file_put_contents( $fn, date( 'Y-m-d H:i:s' ) . ' - ' . $log_line . PHP_EOL, FILE_APPEND );

		}

		public function get_array_nested_value( array $main_array, array $keys_array, $default_value = null ) {

			$length = count( $keys_array );

			for ( $i = 0; $i < $length; $i ++ ) {

				$is_set = ( isset( $main_array[ $keys_array[ $i ] ] ) ) ? true : false;

				if ( ! $is_set ) {
					// if the array key is not set, we break out of loop and return $$default_value
					break;
				} else {
					// Reset the $main_array to the sub array that we know exit
					$main_array = $main_array[ $keys_array[ $i ] ];

					if ( $i === $length - 1 ) { // We are at the last item of array
						// $main_array is now the required value / array
						return $main_array;
					}

				}

			} // end for loop

			return $default_value;

		}

		/**
		 * Get default config for group type field
		 * @return array $default
		 */
		public function get_config_default_group_options() {

			$default = array(
				//
				'repeater'        => true,
				'accordion'       => true,
				'button_title'    => __( 'Add New', 'exopite-options-framework' ),
				'accordion_title' => __( 'Accordion Title', 'exopite-options-framework' ),
				'limit'           => 50,
				'sortable'        => true,
			);

			return apply_filters( 'exopite_sof_filter_config_default_group_array', $default );
		}

		public function get_sanitized_fields_values( $fields, $posted_data ) {

			$sanitized_fields_data = array();
			foreach ( $fields as $index => $field ) :

				$field_type = ( isset( $field['type'] ) ) ? $field['type'] : false;
				$field_id   = ( isset( $field['id'] ) ) ? $field['id'] : false;

				// if do not have $field_id or $field_type, we continue to next field
				if ( ! $field_id || ! $field_type ) {
					continue;
				}

				// if field is not a group
				if ( $field_type !== 'group' ) {

					$sanitized_fields_data[ $field['id'] ] = $this->get_sanitized_field_value_from_global_post( $field, $posted_data );

				} // ( $field_type !== 'group' )

				// If the field is group
				if ( $field_type === 'group' ) {

					$group = $field;

					$group_id = ( isset( $field['id'] ) ) ? $field['id'] : false;

					$group_fields = isset( $field['fields'] ) && is_array( $field['fields'] ) ? $field['fields'] : false;

					// We are not processing if group_id is not there
					if ( $group_id && $group_fields ):

						// Normalise the group options (so we dont need to check for isset()
						$default_group_options = $this->get_config_default_group_options();
						$group_options         = ( isset( $group['options'] ) ) ? $group['options'] : $default_group_options;
						$group['options']      = $group_options = wp_parse_args( $group_options, $default_group_options );

						$is_repeater = ( isset( $group['options']['repeater'] ) ) ? (bool) $group['options']['repeater'] : false;

						// If the group is NOT a repeater
						if ( ! $is_repeater ) :

							foreach ( $group_fields as $sub_field ) :

								$sub_field_id = ( isset( $sub_field['id'] ) ) ? $sub_field['id'] : false;

								$sanitized_fields_data[ $group_id ][ $sub_field_id ] = $this->get_sanitized_field_value_from_global_post( $sub_field, $posted_data, $group_id );

							endforeach;

						endif; // ( ! $is_repeater )  // If the group is NOT a repeater

						// If the group is a repeater
						if ( $is_repeater ):

							$repeater_count = 0;

							if ( $this->is_multilang ) {
								// How many times $_POST has this

								$repeater_count = ( isset( $posted_data[ $this->lang_current ][ $group_id ] ) && is_array( $posted_data[ $this->lang_current ][ $group_id ] ) ) ? count( $posted_data[ $this->lang_current ][ $group_id ] ) : 0;

							} // $this->is_multilang

							/**
							 * ToDos:
							 * - On simple options NEED to disable multilang! (if meta)
							 */
							if ( ! $this->is_multilang ) {

								$repeater_count = ( isset( $posted_data[ $group_id ] ) && is_array( $posted_data[ $group_id ] ) ) ? count( $posted_data[ $group_id ] ) : 0;

							}

							for ( $i = 0; $i < $repeater_count; $i ++ ) {

								foreach ( $group_fields as $sub_field ) :

									$sub_field_id = ( isset( $sub_field['id'] ) ) ? $sub_field['id'] : false;

									// sub field id is required
									if ( ! $sub_field_id ) {
										continue;
									}

									$sanitized_fields_data[ $group_id ][ $i ][ $sub_field_id ] = $this->get_sanitized_field_value_from_global_post( $sub_field, $posted_data, $group_id, $i );

								endforeach; // $group_fields
							}

						endif; // ( $is_repeater )

					endif; // ( ! $group_id )

				} // ( $field_type === 'group' )

			endforeach; // $fields array

			return $sanitized_fields_data;

		} // get_sanitized_fields_values

		/**
		 * Get the clean value from single field
		 *
		 * @param array $field
		 *
		 * @return mixed $clean_value
		 */
		public function get_sanitized_field_value_from_global_post( $field, $posted_data = array(), $group_id = null, $group_field_index = null ) {

			if ( ! isset( $field['id'] ) || ! isset( $field['type'] ) ) {
				return '';
			} else {
				$field_id   = $field['id'];
				$field_type = $field['type'];
			}

			// Initialize array
			$keys_array = array();

			// Adding elements to $keys_array
			// order matters!!!

			if ( $this->is_multilang ) {
				$keys_array[] = $this->lang_current;
			}

			if ( $group_id !== null ) {
				$keys_array[] = $group_id;
			}

			if ( $group_field_index !== null ) {
				$keys_array[] = $group_field_index;
			}

			$keys_array[] = $field_id;

			// Get $dirty_value from global $_POST
			$dirty_value = $this->get_array_nested_value( $posted_data, $keys_array, '' );

			$clean_value = $this->sanitize( $field, $dirty_value );

			return $clean_value;

		}

		/**
		 * Validate and sanitize values
		 *
		 * @param $field
		 * @param $value
		 *
		 * @return mixed
		 */
		public function sanitize( $field, $dirty_value ) {

			$dirty_value = isset( $dirty_value ) ? $dirty_value : '';

			// if $config array has sanitize function, then call it
			if ( isset( $field['sanitize'] ) && ! empty( $field['sanitize'] ) && function_exists( $field['sanitize'] ) ) {

				// TODO: in future, we can allow for sanitize functions array as well
				$sanitize_func_name = $field['sanitize'];

				$clean_value = call_user_func( $sanitize_func_name, $dirty_value );

			} else {

				// if $config array doe not have sanitize function, do sanitize on field type basis
				$clean_value = $this->get_sanitized_field_value_by_type( $field, $dirty_value );

			}

			return apply_filters( 'exopite_sof_sanitize_value', $clean_value, $dirty_value, $field, $this->config );

		}

		/**
		 * Pass the field and value to run sanitization by type of field
		 *
		 * @param array $field
		 * @param mixed $value
		 *
		 * $return mixed $value after sanitization
		 */
		public function get_sanitized_field_value_by_type( $field, $value ) {

			$field_type = ( isset( $field['type'] ) ) ? $field['type'] : '';

			switch ( $field_type ) {

				case 'panel':
					// no break
				case 'notice':
					/**
					 * This fields has nothing to send
					 */
					break;
				case 'image_select':
					// no break
				case 'select':
					// no break
				case 'typography':
					// no break
				case 'tap_list':
					/**
					 * Need to check array values.
					 */
					// if( ! is_array( $value ) ) {
					// 	maybe_unserialize( $value );
					// }
					// if ( is_array( $value ) ) {
					// 	foreach( $value as &$item ) {
					// 		$item = sanitize_text_field( $item );
					// 	}
					// }

					break;
				case 'tab':
					// no break
				case 'group':
					/**
					 * This nested elements need recursive sanitation.
					 */
					break;
				case 'editor':
					// no break
				case 'textarea':
					/**
					 * HTML excepted accept <textarea>.
					 * @link https://codex.wordpress.org/Function_Reference/wp_kses_allowed_html
					 */
					$allowed_html = wp_kses_allowed_html( 'post' );
					// Remove '<textarea>' tag
					unset ( $allowed_html['textarea'] );
					/**
					 * wp_kses_allowed_html return the wrong values for wp_kses,
					 * need to change "true" -> "array()"
					 */
					array_walk_recursive(
						$allowed_html,
						function (&$value) {
							if (is_bool($value)) {
								$value = array();
							}
						}
					);
					// Run sanitization.
					$value = wp_kses( $value, $allowed_html );
					break;

				case 'ace_editor':
					/**
					 * TODO:
					 * This is basically also a textarea.
					 * Here user can save code, like JS or CSS or even PHP
					 * depense of the use of the field, this can be like
					 * "paste your google analytics code here".
					 *
					 * What we could do, is escape for SQL
					 *
					 * esc_textarea, wp_kses or wp_kses_post will remove this.
					 * textarea will escape all ´'´, ´"´ or ´<´ (<li></li> etc...)
					 * $value = esc_textarea( $value );
					 * $value = wp_kses_post( $value );
					 */
					$value = stripslashes( $value );
					break;

				case 'switcher':
					// no break
				case 'checkbox':
					/**
					 * In theory this will be never an array.
					 * Maybe in the future?
					 */
					if ( is_array( $value ) ) {
						foreach( $value as &$item ) {
							$item = ( $value === 'yes' ) ? 'yes' : 'no';
						}
					} else {
						$value = ( $value === 'yes' ) ? 'yes' : 'no';
					}
					break;

				case 'range':
					// no break
				case 'number':

					$value = ( is_numeric( $value ) ) ? $value : 0;
					if ( isset( $field['min'] ) && $value < $field['min'] ) {
						$value = $field['min'];
					}
					if ( isset( $field['max'] ) && $value > $field['max'] ) {
						$value = $field['max'];
					}

					break;

				default:
					$value = ( ! empty( $value ) ) ? sanitize_text_field( $value ) : '';

			}

			return $value;

		}

	}

}
