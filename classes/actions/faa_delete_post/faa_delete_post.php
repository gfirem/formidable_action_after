<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class faa_delete_post extends faa_base {
	public function __construct() {
		parent::__construct(
			'Delete Post',
			'This action delete a post if the id exist',
			'faa_delete_post'
		);
	}
	
	/**
	 * Set the default value for the options inside the action
	 *
	 * @return array
	 */
	public function get_defaults() {
		return array( 'faa_delete_post' => '' );
	}
	
	/**
	 * Add new content into the action view
	 *
	 * @param $form
	 * @param $form_action
	 * @param $action_control
	 */
	public function view( $form, $form_action, $action_control ) {
		include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'delete_post.php';
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
		if ( ! empty( $action->post_content ) && ! empty( $action->post_content['faa_delete_post'] ) ) {
			$id    = $this->replace_shortcode( $entry, $action->post_content['faa_delete_post'] );
			$exist = get_post( $id );
			if ( ! empty( $exist ) ) {
				wp_delete_post( $id, apply_filters( 'formidable_action_after_trash_post', false ) );
			}
		}
	}
}