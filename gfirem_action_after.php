<?php
/**
 *
 * @since             1.0.0
 * @package           gfirem_action_after
 *
 * @wordpress-plugin
 * Plugin Name:       GFireM Action After
 * Description:       Add a Formidable Action with different task to execute inside, like delete user or post, replace value...
 * Version:           1.1.5
 * Author:            gfirem
 * License:           Apache License 2.0
 * License URI:       http://www.apache.org/licenses/
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'gfirem_action_after' ) ) {
	
	require_once dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'faa_fs.php';
	faa_fs::get_instance();
	
	class gfirem_action_after {
		
		/**
		 * Instance of this class.
		 *
		 * @var object
		 */
		protected static $instance = null;
		
		/**
		 * @var Freemius
		 */
		public static $freemius;
		
		/**
		 * Initialize the plugin.
		 */
		private function __construct() {
			define( 'GFIREM_ACTION_AFTER_CSS_PATH', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/css/' );
			define( 'GFIREM_ACTION_AFTER_JS_PATH', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/js/' );
			define( 'GFIREM_ACTION_AFTER_VIEW_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR );
			define( 'GFIREM_ACTION_AFTER_CLASSES_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR );
			define( 'GFIREM_ACTION_AFTER_ACTIONS_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'actions' . DIRECTORY_SEPARATOR );
			
			$this->load_plugin_textdomain();
			
			require_once GFIREM_ACTION_AFTER_CLASSES_PATH . '/include/WP_Requirements.php';
			require_once GFIREM_ACTION_AFTER_CLASSES_PATH . 'faa_requirements.php';
			$this->requirements = new faa_requirements( 'gfirem_action_after' );
			if ( $this->requirements->satisfied() ) {
				require_once GFIREM_ACTION_AFTER_CLASSES_PATH . 'faa_manager.php';
				new faa_manager();
			} else {
				$fauxPlugin = new WP_Faux_Plugin( __( 'GFireM Action After', 'gfirem_action_after' ), $this->requirements->getResults() );
				$fauxPlugin->show_result( plugin_basename( __FILE__ ) );
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
			load_plugin_textdomain( 'gfirem_action_after', false, basename( dirname( __FILE__ ) ) . '/languages' );
		}
	}
	
	add_action( 'plugins_loaded', array( 'gfirem_action_after', 'get_instance' ) );
}
