<?php

/**
 * Created by PhpStorm.
 * User: GFireM
 * Date: 2/27/2017
 * Time: 10:29 AM
 */
class fab_admin {
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'front_enqueue_js' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style' ) );
		//Get autocomplete row fields
		add_action( "wp_ajax_nopriv_get_autocomplete_row", array( $this, "get_autocomplete_row" ) );
		add_action( "wp_ajax_get_autocomplete_row", array( $this, "get_autocomplete_row" ) );
	}
	
	/**
	 * Include styles in admin
	 */
	public function enqueue_style() {
		wp_enqueue_style( 'jquery' );
		wp_enqueue_style( 'formidable_action_before', FAB_CSS_PATH . 'formidable_action_before.css', array(), fab_manager::getVersion() );
	}
	
	/**
	 * Include script
	 */
	public function front_enqueue_js() {
		wp_register_script( 'formidable_action_before', FAB_JS_PATH . 'formidable_action_before.js', array( "jquery" ), true );
		wp_enqueue_script( 'formidable_action_before' );
	}
	
	public function get_autocomplete_row() {
		check_ajax_referer( 'frm_ajax', 'nonce' );
		
		$row_key  = FrmAppHelper::get_post_param( 'row_key', '', 'absint' );
		$field_id = FrmAppHelper::get_post_param( 'field_id', '', 'absint' );
		$form_id  = FrmAppHelper::get_post_param( 'form_id', '', 'absint' );
		
		$selected_field = '';
		$current_field  = FrmField::getOne( $field_id );// Maybe (for efficiency) change this to a specific database call
		$lookup_fields  = self::get_limited_lookup_fields_in_form( $form_id, $current_field->form_id );
		
		require( FAB_VIEW_PATH . '/watch-row.php' );
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