<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class fab_manager{
	
	private static $plugin_slug = 'formidable_action_before';
	private static $plugin_short = 'fab';
	protected static $version;
	
	public function __construct() {
		self::load_plugins_dependency();
		$plugins_header = get_plugin_data( FAB_BASE_FILE );
		self::$version  = $plugins_header['Version'];
		
		require_once FAB_CLASSES_PATH . 'fab_log.php';
		
		try {
			if ( self::is_formidable_active() ) {
				include FAB_CLASSES_PATH.'fab_admin.php';
				new fab_admin();
				
				add_action( 'frm_registered_form_actions', array( $this, 'register_action' ) );
			}
		} catch ( Exception $ex ) {
			fab_log::log( array(
				'action'         => get_class($this),
				'object_type'    => fab_manager::getShort(),
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
		$actions['formidable_action_before'] = 'fab_action';
		require_once 'fab_action.php';
		
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