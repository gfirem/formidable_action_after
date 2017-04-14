<?php
/**
 * @package WordPress
 * @subpackage Formidable, gfirem_action_after
 * @author GFireM
 * @copyright 2017
 * @link http://www.gfirem.com
 * @license http://www.apache.org/licenses/
 *
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

class faa_fs {
	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;
	protected static $freemius;
	public static $free = 'free';
	public static $professional = 'professional';
	
	public function __construct() {
		$this->start_fs();
	}
	
	/**
	 * @return Freemius
	 */
	public static function getFreemius() {
		return self::$freemius;
	}
	
	public function start_fs() {
		if ( ! isset( self::$freemius ) ) {
			// Include Freemius SDK.
			require_once dirname( __FILE__ ) . '/include/freemius/start.php';
			
			self::$freemius = fs_dynamic_init( array(
				'id'               => '838',
				'slug'             => 'gfirem_action_after',
				'type'             => 'plugin',
				'public_key'       => 'pk_2d9d898b7a365dd4d7fabd1e72b85',
				'is_premium'       => true,
				'has_addons'       => false,
				'has_paid_plans'   => true,
				'is_org_compliant' => false,
				'menu'             => array(
					'slug'       => 'gfirem_action_after',
					'first-path' => 'admin.php?page=gfirem_action_after',
					'support'    => false,
				),
				// Set the SDK to work in a sandbox mode (for development & testing).
				// IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
				'secret_key'       => 'sk_6l4wmhM2&-XuD8e66;Kl+yir@:=<U',
			) );
		}
		
		return self::$freemius;
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
	
	public static function get_current_plan() {
		$site = faa_fs::getFreemius()->get_site();
		if ( ! empty( $site ) ) {
			if ( ! empty( $site->plan ) ) {
				if ( ! empty( $site->plan ) ) {
					return $site->plan->name;
				} else {
					return 'free';
				}
			}
		}
		
		return 'free';
	}
}