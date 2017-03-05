<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class faa_delete_entry extends faa_base {
	public function __construct() {
		parent::__construct(
			'Delete Entry',
			'This action delete an entry if the id exist',
			'faa_delete_entry'
		);
	}
	
	/**
	 * Set the default value for the options inside the action
	 *
	 * @return array
	 */
	public function get_defaults() {
		return array( 'faa_delete_entry' => '' );
	}
	
	/**
	 * Add new content into the action view
	 *
	 * @param $form
	 * @param $form_action
	 * @param $action_control
	 */
	public function view( $form, $form_action, $action_control ) {
		include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'delete_entry.php';
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
		if ( ! empty( $action->post_content ) && ! empty( $action->post_content['faa_delete_entry'] ) ) {
			$id    = $this->replace_shortcode( $entry, $action->post_content['faa_delete_entry'] );
			$exist = FrmEntry::getOne( $id );
			if ( ! empty( $exist ) ) {
				FrmEntry::destroy( $id );
			}
		}
	}
}