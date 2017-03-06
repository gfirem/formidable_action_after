<?php


class faa_admin {
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'fs_is_submenu_visible_' . faa_manager::getSlug(), array( $this, 'handle_sub_menu' ), 10, 2 );
		
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_js' ) );
//		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style' ) );
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
		add_menu_page( _faa( 'Action After for' ), _faa( 'Action After for' ), 'manage_options', faa_manager::getSlug(), array( $this, 'screen' ), 'dashicons-redo' );
	}
	
	public function screen() {
		formidable_action_after::getFreemius()->get_logger()->entrance();
		formidable_action_after::getFreemius()->_account_page_load();
		formidable_action_after::getFreemius()->_account_page_render();
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