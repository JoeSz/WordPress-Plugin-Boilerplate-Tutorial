<?php if ( ! defined( 'ABSPATH' ) ) {
	die;
} // Cannot access pages directly.
/**
 *
 * Field: Group
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
/**
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
if ( ! class_exists( 'Exopite_Simple_Options_Framework_Field_group' ) ) {
	class Exopite_Simple_Options_Framework_Field_group extends Exopite_Simple_Options_Framework_Fields {

		// public $is_repeater;
		// public $limit;
		// public $is_sortable;
		// public $is_accordion;
		// public $is_multilang;

		public function __construct( $field, $value = '', $unique = '', $config = array() ) {
			parent::__construct( $field, $value, $unique, $config );

			$defaults = array(
				'group_title'  => esc_attr( 'Group Title', 'exopite-sof' ),
				'repeater'     => false,
				'sortable'     => true,
				'accordion'    => true,
				'limit'        => 0,
				'button_title' => esc_attr( 'Add new', 'exopite-sof' ),
			);


			$options = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : array();

			$this->field['options'] = wp_parse_args( $options, $defaults );

			$this->is_repeater  = ( isset( $this->field['options']['repeater'] ) ) ? (bool) $this->field['options']['repeater'] : $defaults['repeater'];
			$this->is_sortable  = ( isset( $this->field['options']['sortable'] ) ) ? (bool) $this->field['options']['sortable'] : $defaults['sortable'];
			$this->is_accordion = ( isset( $this->field['options']['accordion'] ) ) ? (bool) $this->field['options']['accordion'] : $defaults['accordion'];
			$this->limit        = ( isset( $this->field['options']['limit'] ) ) ? (int) $this->field['options']['limit'] : $defaults['limit'];
			$this->is_multilang = ( isset( $this->config['is_multilang'] ) ) ? (bool) $this->config['is_multilang'] : false;


			$this->group_title = ( isset( $this->field['options']['group_title'] ) ) ? $this->field['options']['group_title'] : $defaults['group_title'];


		}

		public function output() {

//			var_dump( $this->value );

			echo $this->element_before();

			$unallows  = array( 'group' );
			$fields    = array_values( $this->field['fields'] );
			$unique_id = ( ! empty( $this->unique ) ) ? $this->unique : $this->field['id'];

			// TODO: Account for options = 'simple'
//			if ( $this->config['type'] == 'metabox' && isset( $this->config['options'] ) && $this->config['options'] == 'simple' ) {
//				// $base_id = ( $this->field['options']['repeater'] ) ? array( 'id' => $this->field['id'] . '[REPLACEME]' ) : array( 'id' => $this->field['id'] );
//				//exopite-pers-mgmt[festlegung_zeitraum_group][0][testtitle][hu]
//				//festlegung_zeitraum_group[][0][hu]
//				$base_id = ( $this->field['options']['repeater'] ) ? array( 'id' => $this->element_name() . '[REPLACEME]' ) : array( 'id' => $this->element_name() );
//				// echo "1<br>";
//			} else {
//				$base_id = ( $this->field['options']['repeater'] ) ? array( 'id' => $this->unique . '[' . $this->field['id'] . '][REPLACEME]' ) : array( 'id' => $this->unique . '[' . $this->field['id'] . ']' );
//				// echo "2<br>";
//			}


//			$base_id = ( $this->is_repeater )
//				? array( 'id' => $this->unique . '[' . $this->field['id'] . '][REPLACEME]' )
//				: array( 'id' => $this->unique . '[' . $this->field['id'] . ']' );

			$multilang_array_index = ( $this->is_multilang ) ? "[{$this->config['multilang']['current']}]" : "";


//			var_dump( $this->config['is_options_simple']); die();

			if ( $this->config['is_options_simple'] ) {
				$parent_array = $this->field['id'];
			} else {
				$parent_array = $this->unique . '[' . $this->field['id'] . ']';
			}


			if ( $this->is_repeater ) {


				if ( $this->config['is_options_simple'] ) {

					$base_id = array(
						'id'                => "{$this->field['id']}[REPLACEME]",
						'is_options_simple' => true
					);

				} else {
					// This is Working
//					$base_id = array(
//						'id' => "$this->unique{$multilang_array_index}[{$this->field['id']}][REPLACEME]"
//					);

					$base_id = array(
						'id' => $this->unique . $multilang_array_index . '[' . $this->field['id'] . ']' . '[REPLACEME]'
					);


				}

			} else {

				if ( $this->config['is_options_simple'] ) {
					$base_id = array(
						'id'                => "{$this->field['id']}",
						'is_options_simple' => true
					);

				} else {


					$base_id = array(
						'id' => $this->unique . $multilang_array_index . '[' . $this->field['id'] . ']'
					);
//
//					var_dump( $base_id );

				}

			}

//			$base_id = ( $this->is_repeater )
//				? array( 'id' => "$this->unique{$multilang_array_index}[{$this->field['id']}][REPLACEME]" )
//				: array( 'id' => "$this->unique{$multilang_array_index}[$this->field['id']]" );


//			echo '<pre>';
//			var_export( $base_id );
//			echo '</pre>';

			$muster_class = ( $this->is_repeater ) ? ' exopite-sof-accordion--hidden exopite-sof-cloneable__muster exopite-sof-cloneable__muster--hidden' : '';
			$limit        = $this->limit;
			$sortable     = $this->is_sortable;

			echo '<div class="exopite-sof-group" data-limit="' . $limit . '">';

			echo '<div class="exopite-sof-cloneable__item exopite-sof-accordion__item' . $muster_class . '">';

			if ( $this->is_repeater || ! empty( $this->group_title ) ) {

				echo '<h4 class="exopite-sof-cloneable__title exopite-sof-accordion__title"><span class="exopite-sof-cloneable__text">' . $this->group_title . '</span>';
				if ( $this->is_repeater ) {
					echo '<span class="exopite-sof-cloneable--helper">';
					echo '<i class="exopite-sof-cloneable--remove fa fa-times disabled"></i>';
					echo '</span>';
				}
				echo '</h4>';

			}

			echo '<div class="exopite-sof-cloneable__content ';
			if ( ! $this->is_repeater ) {
				echo 'exopite-sof-sub-dependencies ';
			}
			echo 'exopite-sof-accordion__content">';

			// $base_id['multilang'] = ( isset( $this->config['multilang'] ) && $this->config['multilang'] == false ) ? false : true;

			$self                      = new Exopite_Simple_Options_Framework( $base_id, null );
			$self->config['multilang'] = $this->config['multilang'];
//			$self->config['is_multilang'] = $this->config['is_multilang'];


			// echo '<pre>';
			// var_export( $self->config );
			// echo '</pre>';

			$num = 0;

			foreach ( $fields as $field ) {

				if ( in_array( $field['type'], $unallows ) ) {
					$field['_notice'] = true;
					continue;
				}

				if ( $this->config['is_options_simple'] ) {
					$field['is_options_simple'] = true;
				}


				$field['sub'] = true;


				$field_default = ( isset( $field['default'] ) ) ? $field['default'] : '';

				// Set repeater default field fields as disabled,
				// to prevent save them.
				// If repeater, template field has no values
				if ( $this->is_repeater ) {

//					var_dump( $this->is_multilang );

					$field_value = null;
//


					$field_attributes = array(
						'disabled' => 'only-key',
					);

					if ( isset( $field['attributes'] ) && is_array( $field['attributes'] ) ) {
						$field['attributes'] += $field_attributes;
					} else {
						$field['attributes'] = $field_attributes;
					}

				} else {


					if ( is_serialized( $this->value ) ) {
						$this->value = unserialize( $this->value );
					}


					$field_value = ( isset( $this->value[ $field['id'] ] ) ) ? $this->value[ $field['id'] ] : '';

					$field_value = ( $this->is_repeater ) ? null : $field_value;

				}

				$self->add_field( $field, $field_value );

				$num ++;

			}

//			var_dump( $fields);


			echo '</div>'; // exopite-sof-cloneable-content

			echo '</div>'; // exopite-sof-cloneable__item

			// IF REPEATER

			if ( $this->field['options']['repeater'] ) {

				if ( $this->config['type'] == 'metabox' && isset( $this->config['options'] ) && $this->config['options'] == 'simple' ) {
					// echo '<div class="exopite-sof-cloneable__wrapper exopite-sof-accordion__wrapper" data-sortable="' . $sortable . '" data-name="' . $this->field['id'] . '">';
					echo '<div class="exopite-sof-cloneable__wrapper exopite-sof-accordion__wrapper" data-sortable="' . $sortable . '" data-name="' . $this->element_name() . '">';
					// echo "3<br>";
				} else {
					echo '<div class="exopite-sof-cloneable__wrapper exopite-sof-accordion__wrapper" data-sortable="' . $sortable . '" data-name="' . $base_id['id'] . '">';
					// echo '<div class="exopite-sof-cloneable__wrapper exopite-sof-accordion__wrapper" data-sortable="' . $sortable . '" data-name="' . $this->unique . '[' . $this->field['id'] . ']' . '">';
					// echo "4<br>";
				}


				if ( $this->value ) {

					if ( $this->config['is_options_simple'] ) {

						if ( is_serialized( $this->value ) ) {
							$this->value = unserialize( $this->value );
						}

					}


					$num = 0;

					foreach ( $this->value as $key => $value ) {

						/**
						 * If multilang, then
						 * - check if first element is current language is exist
						 * - is a string (if changed from single language) but not current language
						 * then skip.
						 * (without this check group will display from other languages elements as empty)
						 */
						// if ( is_array( $this->multilang ) ) {
						// 	if ( ! is_string( reset( $value ) ) && ! reset( $value )[ $this->multilang['current'] ] ) {
						// 		continue;
						// 	}
						// 	if ( is_string( reset( $value ) ) && $this->multilang['current'] != $this->multilang['default'] ) {
						// 		continue;
						// 	}
						// } else {
						// 	$first_value      = reset( $value );
						// 	$default_language = mb_substr( get_locale(), 0, 2 );
						// 	if ( isset( $first_value['multilang'] ) && ! isset( $first_value[ $default_language ] ) ) {
						// 		continue;
						// 	}
						// }

						echo '<div class="exopite-sof-cloneable__item exopite-sof-accordion__item exopite-sof-accordion--hidden">';

						echo '<h4 class="exopite-sof-cloneable__title exopite-sof-accordion__title"><span class="exopite-sof-cloneable__text">' . $this->field['options']['group_title'] . '</span>';
						echo '<span class="exopite-sof-cloneable--helper">';
						echo '<i class="exopite-sof-cloneable--remove fa fa-times"></i>';
						echo '</span>';
						echo '</h4>';
						echo '<div class="exopite-sof-cloneable__content exopite-sof-sub-dependencies exopite-sof-accordion__content">';

						// if ( $this->config['type'] == 'metabox' && isset( $this->config['options'] ) && $this->config['options'] == 'simple' ) {
						// 	// $self->unique = $this->field['id'] . '[' . $num . ']';
						// 	$self->unique = $this->element_name() . '[' . $num . ']';
						// } else {
						// 	$self->unique = $this->unique . '[' . $this->field['id'] . '][' . $num . ']';
						// }

						// $self->unique = $this->element_name() . '[' . $num . ']';
						// $self->unique = $this->unique . '[' . $this->field['id'] . '][' . $num . ']';

						if ( $this->config['is_options_simple'] ) {


							$self->unique = $this->field['id'] . '[' . $num . ']';

//							var_dump($self->unique);


						} else {
							$self->unique = $this->unique . $multilang_array_index . '[' . $this->field['id'] . '][' . $num . ']';

						}


						foreach ( $fields as $field ) {

							$field['sub'] = true;

							if ( $this->config['is_options_simple'] ) {
								$field['is_options_simple'] = true;
							}

							if ( in_array( $field['type'], $unallows ) ) {
								continue;
							}

//							var_dump( $this->value );

							$self->add_field( $field, $this->value[ $num ][ $field['id'] ] );

						}

						echo '</div>'; // exopite-sof-cloneable__content
						echo '</div>'; // exopite-sof-cloneable__item

						$num ++;

					}

				}

				echo '</div>'; // exopite-sof-cloneable__wrapper

				echo '<div class="exopite-sof-cloneable-data" data-unique-id="' . $unique_id . '" data-limit="' . $this->field['options']['limit'] . '">' . esc_attr__( 'Max items:', 'exopite-sof' ) . ' ' . $this->field['options']['limit'] . '</div>';
				// echo '<div class="exopite-sof-cloneable-data" data-unique-id="'. $this->element_name() .'" data-limit="'. $this->field['options']['limit'] .'">'. __( 'Max items:', 'exopite-sof' ) .' '. $this->field['options']['limit'] .'</div>';

				echo '<a href="#" class="button button-primary exopite-sof-cloneable--add">' . $this->field['options']['button_title'] . '</a>';

			}

			echo '</div>'; // exopite-sof-group

			echo $this->element_after();

		}

	}

}