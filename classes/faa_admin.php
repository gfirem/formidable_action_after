<?php


class faa_admin {
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_js' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style' ) );
	}
	
	/**
	 * Include styles in admin
	 */
	public function enqueue_style() {
		wp_enqueue_style( 'jquery' );
		wp_enqueue_style( 'formidable_action_after', FAA_CSS_PATH . 'formidable_action_after.css', array(), faa_manager::getVersion() );
	}
	
	/**
	 * Include script
	 */
	public function enqueue_js() {
		wp_register_script( 'formidable_action_after', FAA_JS_PATH . 'formidable_action_after.js', array( "jquery" ), true );
		wp_enqueue_script( 'formidable_action_after' );
	}
}