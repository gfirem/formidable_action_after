<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class faa_requirements extends gfirem_requirements {
	
	public function __construct( $text_domain = 'gfirem_requirements' ) {
		parent::__construct( $text_domain );
	}
	
	/**
	 * Set the plugins requirements
	 *
	 * @return array
	 */
	function getRequirements() {
		$requirements                = array();
		$requirement                 = new WP_PHP_Requirement();
		$requirement->minimumVersion = '5.3.0';
		array_push( $requirements, $requirement );
		$requirement                 = new WP_WordPress_Requirement();
		$requirement->minimumVersion = '4.6.2';
		array_push( $requirements, $requirement );
		$requirement          = new WP_Plugins_Requirement();
		$requirement->plugins = array(
			array( 'id' => 'formidable/formidable.php', 'name' => 'Formidable', 'min_version' => '2.0.0' )
		);
		array_push( $requirements, $requirement );
		
		return $requirements;
	}
}