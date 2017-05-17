<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class faa_admin {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'fs_is_submenu_visible_' . faa_manager::getSlug(), array( $this, 'handle_sub_menu' ), 10, 2 );
		
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_js' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style' ) );
	}
	
	public function handle_sub_menu( $is_visible, $menu_id ) {
		if ( $menu_id == 'account' ) {
			$is_visible = false;
		}
		
		return $is_visible;
	}
	
	/**
	 * Adding the Admin Page
	 */
	public function admin_menu() {
		add_menu_page( __( 'Action After for','gfirem_action_after' ), __( 'Action After for','gfirem_action_after' ), 'manage_options', faa_manager::getSlug(), array( $this, 'screen' ), 'dashicons-redo' );
	}
	
	public function screen() {
		faa_fs::getFreemius()->get_logger()->entrance();
		if (faa_fs::getFreemius()->is_registered()) {
			faa_fs::getFreemius()->_account_page_load();
			faa_fs::getFreemius()->_account_page_render();
		}
	}
	
	/**
	 * Include styles in admin
	 */
	public function enqueue_style() {
		//TODO hacer que solo se incluya cuando es necesario
		wp_enqueue_style( 'jquery' );
		wp_enqueue_style( 'gfirem_action_after', GFIREM_ACTION_AFTER_CSS_PATH . 'formidable_action_after.css', array(), faa_manager::getVersion() );
	}
	
	/**
	 * Include script
	 */
	public function enqueue_js() {
		//TODO hacer que solo se incluya cuando es necesario
		wp_register_script( 'gfirem_action_after', GFIREM_ACTION_AFTER_JS_PATH . 'formidable_action_after.js', array( "jquery" ), true );
		wp_enqueue_script( 'gfirem_action_after' );
	}
}