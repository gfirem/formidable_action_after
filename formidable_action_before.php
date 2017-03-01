<?php
/**
 *
 * @since             1.0.0
 * @package           formidable_action_before
 *
 * @wordpress-plugin
 * Plugin Name:       Formidable Action Before
 * Description:       Add an Action to update data into other form, using filters
 * Version:           1.0.0
 * Author:            gfirem
 * License:           Apache License 2.0
 * License URI:       http://www.apache.org/licenses/
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'formidable_action_before' ) ) {
	
	class formidable_action_before {
		
		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;
		
		/**
		 * Initialize the plugin.
		 */
		private function __construct() {
			define( 'FAB_BASE_NAME', plugin_basename( __FILE__ ) );
			define( 'FAB_BASE_FILE', trailingslashit( str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) ) . 'formidable_action_before.php' );
			define( 'FAB_URLPATH', trailingslashit( str_replace( "\\", "/", plugin_dir_url( __FILE__ ) ) ) );
			define( 'FAB_CSS_PATH', FAB_URLPATH . 'assets/css/' );
			define( 'FAB_JS_PATH', FAB_URLPATH . 'assets/js/' );
			define( 'FAB_VIEW_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR );
			define( 'FAB_CLASSES_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR );
			
			require_once FAB_CLASSES_PATH . 'fab_override.php';
			$this->load_plugin_textdomain();
			
			require_once FAB_CLASSES_PATH . '/include/WP_Requirements.php';
			require_once FAB_CLASSES_PATH . 'fab_requirements.php';
			$this->requirements = new fab_requirements('formidable_action_before');
			if ( $this->requirements->satisfied() ) {
				require_once FAB_CLASSES_PATH . 'fab_manager.php';
				new fab_manager();
			} else {
				$fauxPlugin = new WP_Faux_Plugin( _fab( 'Formidable Action Before'), $this->requirements->getResults() );
				$fauxPlugin->show_result(FAB_BASE_NAME);
			}
		}
		
		/**
		 * Return an instance of this class.
		 *
		 * @return object A single instance of this class.
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			
			return self::$instance;
		}
		
		/**
		 * Load the plugin text domain for translation.
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'formidable_action_before', false, basename( dirname( __FILE__ ) ) . '/languages' );
		}
	}
	
	add_action( 'plugins_loaded', array( 'formidable_action_before', 'get_instance' ) );
}
