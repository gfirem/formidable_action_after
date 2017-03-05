<?php
/**
 *
 * @since             1.0.0
 * @package           formidable_action_after
 *
 * @wordpress-plugin
 * Plugin Name:       Formidable Action After
 * Description:       Add an Action with different action to execute inside an action
 * Version:           1.0.0
 * Author:            gfirem
 * License:           Apache License 2.0
 * License URI:       http://www.apache.org/licenses/
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'formidable_action_after' ) ) {
	
	class formidable_action_after {
		
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
			define( 'FAA_BASE_NAME', plugin_basename( __FILE__ ) );
			define( 'FAA_BASE_FILE', trailingslashit( str_replace( "\\", "/", plugin_dir_path( __FILE__ ) ) ) . 'formidable_action_after.php' );
			define( 'FAA_URLPATH', trailingslashit( str_replace( "\\", "/", plugin_dir_url( __FILE__ ) ) ) );
			define( 'FAA_CSS_PATH', FAA_URLPATH . 'assets/css/' );
			define( 'FAA_JS_PATH', FAA_URLPATH . 'assets/js/' );
			define( 'FAA_VIEW_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR );
			define( 'FAA_CLASSES_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR );
			define( 'FAA_ACTIONS_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'actions' . DIRECTORY_SEPARATOR );
			
			self::$freemius = $this->start_freemius();
			
			require_once FAA_CLASSES_PATH . 'faa_override.php';
			$this->load_plugin_textdomain();
			
			require_once FAA_CLASSES_PATH . '/include/WP_Requirements.php';
			require_once FAA_CLASSES_PATH . 'faa_requirements.php';
			$this->requirements = new faa_requirements( 'formidable_action_after' );
			if ( $this->requirements->satisfied() ) {
				require_once FAA_CLASSES_PATH . 'faa_manager.php';
				new faa_manager();
			} else {
				$fauxPlugin = new WP_Faux_Plugin( _faa( 'Formidable Action Before' ), $this->requirements->getResults() );
				$fauxPlugin->show_result( FAA_BASE_NAME );
			}
		}
		
		/**
		 * @return Freemius
		 */
		public static function getFreemius() {
			return self::$freemius;
		}
		
		public function start_freemius() {
			global $act_fs;
			
			if ( ! isset( $act_fs ) ) {
				// Include Freemius SDK.
				require_once FAA_CLASSES_PATH . '/include/freemius/start.php';
				
				$act_fs = fs_dynamic_init( array(
					'id'                  => '838',
					'slug'                => 'formidable_action_after',
					'type'                => 'plugin',
					'public_key'          => 'pk_2d9d898b7a365dd4d7fabd1e72b85',
					'is_premium'          => true,
					'is_premium_only'     => true,
					'has_premium_version' => false,
					'has_addons'          => false,
					'has_paid_plans'      => true,
					'is_org_compliant'    => false,
					'menu'                => array(
						'slug'       => 'formidable_action_after',
						'first-path' => 'admin.php?page=formidable_action_after',
						'support'    => false,
					),
					// Set the SDK to work in a sandbox mode (for development & testing).
					// IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
					'secret_key'          => 'sk_6l4wmhM2&-XuD8e66;Kl+yir@:=<U',
				) );
			}
			
			return $act_fs;
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
			load_plugin_textdomain( 'formidable_action_after', false, basename( dirname( __FILE__ ) ) . '/languages' );
		}
	}
	
	add_action( 'plugins_loaded', array( 'formidable_action_after', 'get_instance' ) );
}
