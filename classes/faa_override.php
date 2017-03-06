<?php

function _faa( $str ) {
	return __( $str, 'formidable_action_after' );
}

function _e_faa( $str ) {
	_e( $str, 'formidable_action_after' );
}

function act_fs() {
	global $act_fs;
	
	if ( ! isset( $act_fs ) ) {
		// Include Freemius SDK.
		require_once dirname( __FILE__ ) . '/include/freemius/start.php';
		
		$act_fs = fs_dynamic_init( array(
			'id'               => '838',
			'slug'             => 'formidable_action_after',
			'type'             => 'plugin',
			'public_key'       => 'pk_2d9d898b7a365dd4d7fabd1e72b85',
			'is_premium'       => true,
			'is_premium_only'  => true,
			'has_addons'       => false,
			'has_paid_plans'   => true,
			'is_org_compliant' => false,
			'menu'             => array(
				'slug'       => 'formidable_action_after',
				'first-path' => 'admin.php?page=formidable_action_after',
				'support'    => false,
			),
			// Set the SDK to work in a sandbox mode (for development & testing).
			// IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
			'secret_key'       => 'sk_6l4wmhM2&-XuD8e66;Kl+yir@:=<U',
		) );
	}
	
	return $act_fs;
}