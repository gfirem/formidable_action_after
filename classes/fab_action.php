<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class fab_action extends FrmFormAction {
	
	protected $form_default = array( 'wrk_name' => '' );
	
	public function __construct() {
		try {
			if ( class_exists( "FrmProAppController" ) ) {

//			add_action( 'frm_trigger_formidable_action_before_create_action', array( $this, 'f2m_action_create' ), 10, 3 );
//			add_action( 'frm_trigger_formidable_action_before_update_action', array( $this, 'f2m_action_update' ), 10, 3 );
				add_action( 'frm_trigger_formidable_action_before_action', array( $this, 'process_action' ), 10, 4 );
				
				add_action( 'admin_head', array( $this, 'add_admin_styles' ) );
//				add_filter( 'wp_kses_allowed_html', array( $this, 'wp_kses_allowed_html' ), 10, 2 );
//				add_shortcode( "form-f2m-security", array( $this, 'form_f2m_security_content' ) );
				
				$action_ops = array(
					'classes'  => 'dashicons dashicons-admin-generic',
					'limit'    => 99,
					'active'   => true,
					'priority' => 50,
					'event'    => array( 'create', 'update' ),
				);
				
				$this->FrmFormAction( 'formidable_action_before', 'Action Before', $action_ops );
			}
		} catch ( Exception $ex ) {
			fab_log::log( array(
				'action'         => get_class( $this ),
				'object_type'    => fab_manager::getShort(),
				'object_subtype' => '__construct',
				'object_name'    => $ex->getMessage(),
			) );
		}
	}
	
	public function process_action( $action, $entry, $form, $event ) {
		try {
			if ( ! empty( $action->post_content ) && ! empty( $action->post_content['fab_targets'] ) ) {
				$fab_target = json_decode( $action->post_content['fab_targets'] );
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
		} catch ( Exception $ex ) {
			fab_log::log( array(
				'action'         => get_class( $this ),
				'object_type'    => fab_manager::getShort(),
				'object_subtype' => 'process_action',
				'object_name'    => $ex->getMessage(),
			) );
		}
	}
	
	private function replace_shortcode( $entry, $value ) {
		$shortCodes = FrmFieldsHelper::get_shortcodes( $value, $entry->form_id );
		$content    = apply_filters( 'frm_replace_content_shortcodes', $value, $entry, $shortCodes );
		FrmProFieldsHelper::replace_non_standard_formidable_shortcodes( array(), $content );
		
		return do_shortcode( $content );
	}
	
	public function add_admin_styles() {
		$current_screen = get_current_screen();
		if ( ! empty( $current_screen ) && $current_screen->id === 'toplevel_page_formidable' ) {
			?>
            <style>
                .frm_formidable_action_before_action.frm_bstooltip.frm_active_action {
                    font-size: 13px;
                    display: inline-table;
                    height: 24px;
                    width: 24px;
                }

                .frm_form_action_icon.dashicons.dashicons-admin-generic {
                    font-size: 13px;
                    margin-right: 8px;
                }
            </style>
			<?php
		}
	}
	
	public function form( $form_action, $args = array() ) {
		try {
			global $wpdb;
			extract( $args );
			$form           = $args['form'];
			$action_control = $this;
			$main_form      = $form->id;
			$rows           = array();
			$fab_target     = "";
			if ( ! empty( $form_action->post_content['fab_targets'] ) ) {
				$fab_target = json_decode( $form_action->post_content['fab_targets'] );
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
			
			include FAB_VIEW_PATH . 'action.php';
			
		} catch ( Exception $ex ) {
			fab_log::log( array(
				'action'         => get_class( $this ),
				'object_type'    => fab_manager::getShort(),
				'object_subtype' => 'form',
				'object_name'    => $ex->getMessage(),
			) );
		}
	}
	
	public function get_defaults() {
		$result = array(
			'form_id'                        => $this->get_field_name( 'form_id' ),
			'fab_targets'                    => '',
			'fab_target_form_id'             => '',
			'fab_target_field_filter'        => '',
			'fab_target_field_filter_value'  => '',
			'fab_target_field_replace'       => '',
			'fab_target_field_replace_value' => '',
		);
		
		if ( $this->form_id != null ) {
			$result['form_id'] = $this->form_id;
		}
		
		return $result;
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
	
	
}