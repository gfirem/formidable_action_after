<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class faa_manager {
	
	private static $plugin_slug = 'formidable_action_after';
	private static $plugin_short = 'faa';
	protected static $version;
	
	public function __construct() {
		self::load_plugins_dependency();
		$plugins_header = get_plugin_data( FAA_BASE_FILE );
		self::$version  = $plugins_header['Version'];
		
		require_once FAA_CLASSES_PATH . 'faa_log.php';
		
		try {
			if ( self::is_formidable_active() && formidable_action_after::getFreemius()->is_paying() ) {
				include FAA_CLASSES_PATH . 'faa_admin.php';
				new faa_admin();
				
				include FAA_ACTIONS_PATH . 'faa_base.php';
				include FAA_ACTIONS_PATH . 'faa_replace/faa_replace.php';
				new faa_replace();
				include FAA_ACTIONS_PATH . 'faa_delete_entry/faa_delete_entry.php';
				new faa_delete_entry();
				include FAA_ACTIONS_PATH . 'faa_delete_post/faa_delete_post.php';
				new faa_delete_post();
				
				add_action( 'frm_registered_form_actions', array( $this, 'register_action' ) );
			}
		} catch ( Exception $ex ) {
			faa_log::log( array(
				'action'         => get_class( $this ),
				'object_type'    => faa_manager::getShort(),
				'object_subtype' => 'loading_dependency',
				'object_name'    => $ex->getMessage(),
			) );
		}
	}
	
	public static function load_plugins_dependency() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	
	public static function is_formidable_active() {
		self::load_plugins_dependency();
		
		return is_plugin_active( 'formidable/formidable.php' );
	}
	
	/**
	 * Register action
	 *
	 * @param $actions
	 *
	 * @return mixed
	 */
	public function register_action( $actions ) {
		$actions['formidable_action_after'] = 'faa_action';
		require_once 'faa_action.php';
		
		return $actions;
	}
	
	/**
	 * Get plugins short
	 *
	 * @return string
	 */
	static function getShort() {
		return self::$plugin_short;
	}
	
	/**
	 * Get plugins version
	 *
	 * @return mixed
	 */
	static function getVersion() {
		return self::$version;
	}
	
	/**
	 * Get plugins slug
	 *
	 * @return string
	 */
	static function getSlug() {
		return self::$plugin_slug;
	}
}