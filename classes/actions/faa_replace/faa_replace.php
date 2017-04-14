<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class faa_replace extends faa_base {
	private $assets_url;
	
	public function __construct() {
		$this->assets_url = trailingslashit( str_replace( "\\", "/", plugin_dir_url( __FILE__ ) ) ) . 'assets/';
		parent::__construct(
			'Replace value',
			'This Task replace a value inside the selected form using a search value',
			'faa_replace', 'professional'
		);
		
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_js' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style' ) );
		//Get autocomplete row fields
		add_action( "wp_ajax_nopriv_get_autocomplete_row", array( $this, "get_autocomplete_row" ) );
		add_action( "wp_ajax_get_autocomplete_row", array( $this, "get_autocomplete_row" ) );
	}
	
	public function get_defaults() {
		return array( 'faa_targets' => '' );
	}
	
	/**
	 * Add new content into the action view
	 *
	 * @param $form
	 * @param $form_action
	 * @param $action_control
	 */
	public function view( $form, $form_action, $action_control ) {
		$main_form  = $form->id;
		$rows       = array();
		$fab_target = "";
		if ( ! empty( $form_action->post_content['faa_targets'] ) ) {
			$fab_target = json_decode( $form_action->post_content['faa_targets'] );
			if ( ! empty( $fab_target ) && is_array( $fab_target ) ) {
				foreach ( $fab_target as $key => $item ) {
					$rows[ $key ]['data']   = $this->get_forms( $item->form_target );
					$rows[ $key ]['values'] = $item;
					if ( ! empty( $item->field_filter ) ) {
						$rows[ $key ]['field_filter'] = $this->get_field( intval( $item->field_filter ) );
					}
					if ( ! empty( $item->field_replace ) ) {
						$rows[ $key ]['field_replace'] = $this->get_field( intval( $item->field_replace ) );
					}
				}
			}
		} else {
			$rows[0]['data']          = $this->get_forms( '' );
			$rows[0]['field_filter']  = '';
			$rows[0]['field_replace'] = '';
		}
		
		include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'action.php';
	}
	
	/**
	 *
	 * @param $fab_target_form_id
	 *
	 * @return array
	 */
	private function get_forms( $fab_target_form_id ) {
		$lookup_args = array();
		
		// Get all forms for the -select form- option
		$lookup_args['form_list'] = FrmForm::get_published_forms();
		
		if ( is_numeric( $fab_target_form_id ) ) {
			$lookup_args['form_fields'] = FrmField::get_all_for_form( $fab_target_form_id );
		} else {
			$lookup_args['form_fields'] = array();
		}
		
		return $lookup_args;
	}
	
	private function get_field( $field_id ) {
		$field_target = array();
		if ( ! empty( $field_id ) && is_numeric( $field_id ) ) {
			$field_target = FrmField::getOne( $field_id );
		}
		
		return $field_target;
	}
	
	/**
	 * Process the action
	 *
	 * @param $action
	 * @param $entry
	 * @param $form
	 * @param $event
	 */
	public function process( $action, $entry, $form, $event ) {
		if ( ! empty( $action->post_content ) && ! empty( $action->post_content['faa_targets'] ) ) {
			$fab_target = json_decode( $action->post_content['faa_targets'] );
			if ( ! empty( $fab_target ) && is_array( $fab_target ) ) {
				foreach ( $fab_target as $target ) {
					$target->field_filter_value  = $this->replace_shortcode( $entry, $target->field_filter_value );
					$target->field_replace_value = $this->replace_shortcode( $entry, $target->field_replace_value );
					$repeat_fields               = FrmProFormsHelper::has_repeat_field( $target->form_target, false );
					$existing_repeat_field       = array();
					foreach ( $repeat_fields as $id => $field ) {
						$existing_repeat_field[] = $field->id;
					}
					
					if ( ! empty( $entry->metas ) ) {
						$search_field_type = FrmField::get_type( $target->field_filter );
						$result            = array();
						if ( $search_field_type != 'data' ) {
							$result = FrmEntryMeta::search_entry_metas( $target->field_filter_value, $target->field_filter, "LIKE" );
						} else {
							$search_field        = FrmField::getOne( $target->field_filter );
							$search_target_field = FrmField::get_option( $search_field, 'form_select' );
							if ( ! empty( $search_target_field ) ) {
								$sub_result = FrmEntryMeta::search_entry_metas( $target->field_filter_value, $search_target_field, "LIKE" );
								if ( ! empty( $sub_result ) && is_array( $sub_result ) ) {
									foreach ( $sub_result as $sub_item ) {
										$result = array_merge( $result, FrmEntryMeta::search_entry_metas( $sub_item, $target->field_filter, "LIKE" ) );
									}
								}
							}
						}
						if ( ! empty( $result ) && is_array( $result ) ) {
							foreach ( $result as $item ) {
								$full_item = FrmEntry::getOne( $item, true );
								if ( ! empty( $full_item ) && ! empty( $full_item ) && is_array( $full_item->metas )
								     && array_key_exists( $target->field_replace, $full_item->metas )
								) {
									$full_item->metas[ $target->field_replace ] = $target->field_replace_value;
									FrmEntryMeta::update_entry_metas( $item, $full_item->metas );
								}
							}
						}
					}
				}
			}
		}
	}
	
	/**
	 * Include styles in admin
	 */
	public function enqueue_style() {
		wp_enqueue_style( 'jquery' );
		wp_enqueue_style( 'faa_replace', $this->assets_url . 'css/' . 'faa_replace.css', array(), faa_manager::getVersion() );
	}
	
	/**
	 * Include script
	 */
	public function enqueue_js() {
		wp_register_script( 'faa_replace', $this->assets_url . 'js/' . 'faa_replace.js', array( "jquery" ), true );
		wp_enqueue_script( 'faa_replace' );
	}
	
	public function get_autocomplete_row() {
		check_ajax_referer( 'frm_ajax', 'nonce' );
		
		$row_key  = FrmAppHelper::get_post_param( 'row_key', '', 'absint' );
		$field_id = FrmAppHelper::get_post_param( 'field_id', '', 'absint' );
		$form_id  = FrmAppHelper::get_post_param( 'form_id', '', 'absint' );
		
		$selected_field = '';
		$current_field  = FrmField::getOne( $field_id );// Maybe (for efficiency) change this to a specific database call
		$lookup_fields  = self::get_limited_lookup_fields_in_form( $form_id, $current_field->form_id );
		
		require( GFIREM_ACTION_AFTER_VIEW_PATH . '/watch-row.php' );
		wp_die();
	}
	
	private static function get_limited_lookup_fields_in_form( $parent_form_id, $current_form_id ) {
		if ( $parent_form_id == $current_form_id ) {
			// If the current field's form ID matches $form_id, only get fields in that form (not embedded or repeating)
			$inc_repeating = 'exclude';
		} else {
			// If current field is repeating, get lookup fields in repeating section and outside of it
			$inc_repeating = 'include';
		}
		
		$lookup_fields = FrmField::get_all_for_form( $parent_form_id, '', $inc_repeating );
		
		return $lookup_fields;
	}
	
	/**
	 * Fill option for field drop down in field options
	 *
	 * @param $form_fields
	 * @param array $field
	 */
	public static function show_options_for_get_values_field( $form_fields, $field = array() ) {
		$select_field_text = __( '&mdash; Select Field &mdash;', 'formidable' );
		echo '<option value="">' . esc_html( $select_field_text ) . '</option>';
		
		foreach ( $form_fields as $field_option ) {
			if ( FrmField::is_no_save_field( $field_option->type ) ) {
				continue;
			}
			
			if ( ! empty( $field ) && $field_option->id == $field->id ) {
				$selected = ' selected="selected"';
			} else {
				$selected = '';
			}
			
			$field_name = FrmAppHelper::truncate( $field_option->name, 30 );
			echo '<option value="' . esc_attr( $field_option->id ) . '"' . esc_attr( $selected ) . '>' . esc_html( $field_name ) . '</option>';
		}
	}
}