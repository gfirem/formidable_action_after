<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class faa_delete_user extends faa_base {
	public function __construct() {
		parent::__construct( 'Delete User', 'This Task delete a User if the id exist', 'faa_delete_user', 'professional' );
	}
	/**
	 * Set the default value for the options inside the action
	 *
	 * @return array
	 */
	public function get_defaults() {
		return array( 'faa_delete_user' => '' );
		return array( 'faa_delete_user_email' => '' );
	}
	/**
	 * Add new content into the action view
	 *
	 * @param $form
	 * @param $form_action
	 * @param $action_control
	 */
	public function view( $form, $form_action, $action_control ) {
		include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'delete_user.php';
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
		if ( ! empty( $action->post_content ) ) {
			$exist = false;
			if ( ! empty( $action->post_content['faa_delete_user'] ) ) {
				$id    = $this->replace_shortcode( $entry, $action->post_content['faa_delete_user'] );
				$exist = get_user_by( 'id', $id );
			} elseif ( ! empty( $action->post_content['faa_delete_user_email'] ) ) {
				$email = $this->replace_shortcode( $entry, $action->post_content['faa_delete_user_email'] );
				$exist = get_user_by( 'email', $email );
			}
			if ( ! empty( $exist ) ) {
				$redirect_to_home = false;
				$current_user_id  = get_current_user_id();
				if ( $current_user_id === $exist->ID ) {
					$redirect_to_home = true;
					wp_logout();
				}
				require_once( ABSPATH . 'wp-admin/includes/user.php' );
				wp_delete_user( $exist->ID, apply_filters( 'formidable_action_after_delete_user_reassign', null ) );
				if ( $redirect_to_home ) {
					wp_safe_redirect( get_home_url() );
				}
			}
		}
	}
}