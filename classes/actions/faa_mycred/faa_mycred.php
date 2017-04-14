<?php
/**
 * @package    WordPress
 * @subpackage Formidable, gfirem_action_after
 * @author     GFireM
 * @copyright  2017
 * @link       http://www.gfirem.com
 * @license    http://www.apache.org/licenses/
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class faa_mycred extends faa_base {
	
	public function __construct() {
		parent::__construct(
			'MyCred',
			'This Task interact with MyCred function mycred_add to add and deduct users balance',
			'faa_mycred', 'professional'
		);
	}
	
	public function get_defaults() {
		return array( 'faa_mycred_amount' => '', 'faa_mycred_user' => '', 'faa_mycred_message' => '' );
	}
	
	/**
	 * Add new content into the action view
	 *
	 * @param $form
	 * @param $form_action
	 * @param $action_control
	 */
	public function view( $form, $form_action, $action_control ) {
		include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'faa_mycred_view.php';
	}
	
	/**
	 * Process the action
	 *
	 * @param $action
	 * @param $entry
	 * @param $form
	 * @param $event
	 */
	public function process( $action, $entry, $form, $event ) {
		if ( $this->is_mycred_active() ) {
			if ( ! empty( $action->post_content ) && ! empty( $action->post_content['faa_mycred_amount'] )
			     && ! empty( $action->post_content['faa_mycred_user'] ) && ! empty( $action->post_content['faa_mycred_message'] )
			) {
				if ( function_exists( 'mycred_add' ) && function_exists( 'mycred_get_user_id' ) && function_exists( 'mycred' ) ) {
					$amount  = $this->replace_shortcode( $entry, $action->post_content['faa_mycred_amount'] );
					$user    = $this->replace_shortcode( $entry, $action->post_content['faa_mycred_user'] );
					$message = $this->replace_shortcode( $entry, $action->post_content['faa_mycred_message'] );
					if ( empty( $message ) ) {
						$message = '';
					}
					if ( ! is_int( $user ) ) {
						$user = mycred_get_user_id( $user );
					}
					$ref  = apply_filters( 'gfirem_action_after_mycred_reference', $form->name );
					$type = apply_filters( 'gfirem_action_after_mycred_point_type_key', MYCRED_DEFAULT_TYPE_KEY );
					if ( ! empty( $amount ) && is_numeric( $amount ) && ! empty( $user ) ) {
						$my_cred = mycred( $type );
						if ( ! $my_cred->exclude_user( $user ) ) {
							$r = mycred_add( $ref, $user, $amount, $message, '', '', $type );
						}
					}
				}
			}
		}
	}
	
	public function is_mycred_active() {
		faa_manager::load_plugins_dependency();
		
		return is_plugin_active( 'mycred/mycred.php' );
	}
}