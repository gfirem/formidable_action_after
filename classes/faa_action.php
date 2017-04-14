<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class faa_action extends FrmFormAction {
	
	protected $form_default = array( 'wrk_name' => '' );
	protected $faa_actions = array();
	protected $faa_actions_to_load = array();
	
	public function __construct() {
		try {
			// check if is paying
			if ( class_exists( "FrmProAppController" ) ) {
				
				$this->faa_actions = apply_filters( 'faa_action_subscribe', array() );
				
				/** @var faa_base $faa_action */
				foreach ( $this->faa_actions as $faa_action ) {
					if ( $faa_action->plan == faa_fs::$free ) {
						$this->faa_actions_to_load[ faa_fs::$free ][ $faa_action->getSlug() ] = $faa_action;
					}
					if ( $faa_action->plan == faa_fs::$free || $faa_action->plan == faa_fs::$professional ) {
						$this->faa_actions_to_load[ faa_fs::$professional ][ $faa_action->getSlug() ] = $faa_action;
					}
				}
				
				add_action( 'frm_trigger_gfirem_action_after_action', array( $this, 'process_action' ), 10, 4 );
				add_action( 'admin_head', array( $this, 'add_admin_styles' ) );
				
				$action_ops = array(
					'classes'  => 'dashicons dashicons-redo',
					'limit'    => 99,
					'active'   => true,
					'priority' => 50,
					'event'    => array( 'create', 'update', 'delete' ),
				);
				
				$this->FrmFormAction( 'gfirem_action_after', __( 'After Of', 'gfirem_action_after' ), $action_ops );
			}
		} catch ( Exception $ex ) {
			faa_log::log( array(
				'action'         => get_class( $this ),
				'object_type'    => faa_manager::getShort(),
				'object_subtype' => '__construct',
				'object_name'    => $ex->getMessage(),
			) );
		}
	}
	
	
	public function add_admin_styles() {
		$current_screen = get_current_screen();
		if ( ! empty( $current_screen ) && $current_screen->id === 'toplevel_page_formidable' ) {
			?>
            <style>
                .frm_gfirem_action_after_action.frm_bstooltip.frm_active_action {
                    font-size: 13px;
                    display: inline-table;
                    height: 24px;
                    width: 24px;
                }

                .frm_form_action_icon.dashicons.dashicons-redo {
                    font-size: 13px;
                    margin-right: 8px;
                }
            </style>
			<?php
		}
	}
	
	public function process_action( $action, $entry, $form, $event ) {
		try {
			$actions_control = array();
			if ( ! empty( $action->post_content ) && ! empty( $action->post_content['faa_action_enabled'] ) ) {
				$actions_control = $this->get_the_enabled_actions( $action->post_content['faa_action_enabled'] );
			}
			
			$plan            = faa_fs::get_current_plan();
			$actions_to_load = $this->faa_actions_to_load[ $plan ];
			/** @var faa_base $faa_action */
			foreach ( $this->faa_actions as $faa_action ) {
				if ( ! array_key_exists( $faa_action->getSlug(), $actions_to_load ) ) {
					continue;
				}
				if ( array_key_exists( $faa_action->getSlug(), $actions_control ) && $actions_control[ $faa_action->getSlug() ] == 1 ) {
					$faa_action->process( $action, $entry, $form, $event );
				}
			}
		} catch ( Exception $ex ) {
			faa_log::log( array(
				'action'         => get_class( $this ),
				'object_type'    => faa_manager::getShort(),
				'object_subtype' => 'process_action',
				'object_name'    => $ex->getMessage(),
			) );
		}
	}
	
	public function form( $form_action, $args = array() ) {
		try {
			global $wpdb;
			extract( $args );
			$form            = $args['form'];
			$action_control  = $this;
			$actions_control = array();
			if ( ! empty( $form_action->post_content['faa_action_enabled'] ) ) {
				$actions_control = $this->get_the_enabled_actions( $form_action->post_content['faa_action_enabled'] );
			}
			
			include GFIREM_ACTION_AFTER_VIEW_PATH . 'action_top.php';
			
		} catch ( Exception $ex ) {
			faa_log::log( array(
				'action'         => get_class( $this ),
				'object_type'    => faa_manager::getShort(),
				'object_subtype' => 'form',
				'object_name'    => $ex->getMessage(),
			) );
		}
	}
	
	public function get_defaults() {
		$result = array(
			'form_id'            => $this->get_field_name( 'form_id' ),
			'faa_action_enabled' => ''
		);
		
		$plan            = faa_fs::get_current_plan();
		$actions_to_load = $this->faa_actions_to_load[ $plan ];
		/** @var faa_base $faa_action */
		foreach ( $this->faa_actions as $faa_action ) {
			if ( ! array_key_exists( $faa_action->getSlug(), $actions_to_load ) ) {
				continue;
			}
			$result = array_merge( $result, $faa_action->get_defaults() );
		}
		
		if ( $this->form_id != null ) {
			$result['form_id'] = $this->form_id;
		}
		
		return $result;
	}
	
	/**
	 * Get all the inside actions
	 *
	 * @param $json_actions
	 *
	 * @return mixed
	 */
	private function get_the_enabled_actions( $json_actions ) {
		return self::get_enabled_tasks( $json_actions );
	}
	
	/**
     * Get all active task or one if provide a task name to filter it
     *
	 * @param string $json_actions
	 * @param string $task_filter
	 *
	 * @return array
	 */
	public static function get_enabled_tasks( $json_actions, $task_filter = '' ) {
		$result      = array();
		$faa_enabled = json_decode( $json_actions );
		if ( ! empty( $faa_enabled ) && is_array( $faa_enabled ) ) {
			if ( empty( $task_filter ) ) {
				foreach ( $faa_enabled as $key => $item ) {
					$result[ $item->slug ] = $item->enabled;
				}
			} else {
				foreach ( $faa_enabled as $key => $item ) {
					if ( $item->slug == $task_filter ) {
						$result[ $item->slug ] = $item->enabled;
						
						return $result;
					}
				}
			}
		}
		
		return $result;
	}
	
	public function disable_class_tag( $tag, $plan = 'professional', $force = false ) {
		if ( $force || ( ! faa_fs::getFreemius()->is_plan( $plan ) ) ) {
			switch ( $tag ) {
				default:
					$class = 'faa-disabled';
			}
			
			return 'class="' . $class . '"';
		}
		
		return '';
	}
	
	
	public function disable_input_tag( $type, $plan = 'professional', $force = false ) {
		if ( $force || ( ! faa_fs::getFreemius()->is_plan( $plan ) ) ) {
			switch ( $type ) {
				case 'button':
					$attr = 'disabled';
					break;
				default:
					$attr = 'disabled="disabled"';
			}
			
			return $attr;
		}
		
		return '';
	}
	
}