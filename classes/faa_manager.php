<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class faa_manager {
	
	private static $plugin_slug = 'gfirem_action_after';
	private static $plugin_short = 'faa';
	protected static $version;
	
	public function __construct() {
		self::$version = '1.1.6';
		
		require_once GFIREM_ACTION_AFTER_CLASSES_PATH . 'faa_log.php';
		new faa_log();
		try {
			if ( self::is_formidable_active() ) {
				include GFIREM_ACTION_AFTER_CLASSES_PATH . 'faa_admin.php';
				new faa_admin();
				
				include GFIREM_ACTION_AFTER_ACTIONS_PATH . 'faa_base.php';
				
				include GFIREM_ACTION_AFTER_ACTIONS_PATH . 'faa_reset_pass/faa_reset_pass.php';
				new faa_reset_pass();
				include GFIREM_ACTION_AFTER_ACTIONS_PATH . 'faa_mycred/faa_mycred.php';
				new faa_mycred();
				include GFIREM_ACTION_AFTER_ACTIONS_PATH . 'faa_replace/faa_replace.php';
				new faa_replace();
				include GFIREM_ACTION_AFTER_ACTIONS_PATH . 'faa_delete_entry/faa_delete_entry.php';
				new faa_delete_entry();
				include GFIREM_ACTION_AFTER_ACTIONS_PATH . 'faa_delete_post/faa_delete_post.php';
				new faa_delete_post();
				
				add_action( 'frm_registered_form_actions', array( $this, 'register_action' ) );
			} else {
				//TODO need a meesages here to notice the user
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
		$actions['gfirem_action_after'] = 'faa_action';
		require_once 'faa_action.php';
		
		return $actions;
	}
	
	/**
	 * Get plugins short
	 *
	 * @return string
	 */
	public static function getShort() {
		return self::$plugin_short;
	}
	
	/**
	 * Get plugins version
	 *
	 * @return mixed
	 */
	public static function getVersion() {
		return self::$version;
	}
	
	/**
	 * Get plugins slug
	 *
	 * @return string
	 */
	public static function getSlug() {
		return self::$plugin_slug;
	}
}