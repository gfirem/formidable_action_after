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
class faa_webmerge extends faa_base {
	private $option = array();
	
	public function __construct() {
		parent::__construct( 'WebMerge Integration', 'This Task interact with webmerge.', 'faa_webmerge', 'professional' );
		//Add field option to set the webmerge field name
		add_action( 'frm_field_options_form', array( $this, 'field_formidable_field_option_form' ), 10, 3 );
		add_action( 'frm_update_field_options', array( $this, 'update_formidable_field_options' ), 10, 3 );
		$this->option = array( 'webmerge_name' => '' );
	}
	
	/**
	 * @see $this->field_option_form
	 */
	public function field_formidable_field_option_form( $field, $display, $values ) {
		foreach ( $this->option as $k => $v ) {
			if ( ! isset( $field[ $k ] ) ) {
				$field[ $k ] = $v;
			}
		}
		include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'field_option.php';
	}
	
	/**
	 * @see $this->update_field_options
	 */
	public function update_formidable_field_options( $field_options, $field, $values ) {
		foreach ( $this->option as $opt => $default ) {
			$field_options[ $opt ] = isset( $values['field_options'][ $opt . '_' . $field->id ] ) ? $values['field_options'][ $opt . '_' . $field->id ] : $default;
		}
		
		return $field_options;
	}
	
	public function get_defaults() {
		return array( 'faa_webmerge_url' => '' );
	}
	
	/**
	 * Add new content into the action view
	 *
	 * @param $form
	 * @param $form_action
	 * @param $action_control
	 */
	public function view( $form, $form_action, $action_control ) {
		if ( faa_fs::getFreemius()->is_plan__premium_only( faa_fs::$professional ) ) {
			include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'faa_webmerge_view.php';
		}
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
		if ( faa_fs::getFreemius()->is_plan__premium_only( faa_fs::$professional ) ) {
			if ( ! empty( $action->post_content ) && ! empty( $action->post_content['faa_webmerge_url'] ) && filter_var( $action->post_content['faa_webmerge_url'], FILTER_VALIDATE_URL ) !== false ) {
				$fields = FrmField::get_all_for_form( $form->id );
				$args   = array();
				foreach ( $fields as $field ) {
					if ( ! empty( $_POST['item_meta'][ $field->id ] ) ) {
						$webmerge_name = $field->field_key;
						$name          = FrmField::get_option( $field, 'webmerge_name' );
						if ( ! empty( $name ) ) {
							$webmerge_name = $name;
						}
						$args[ $webmerge_name ] = $_POST['item_meta'][ $field->id ];
					}
				}
				if ( ! empty( $args ) ) {
					$result = wp_remote_post( $action->post_content['faa_webmerge_url'], array( 'body' => $args ) );
				}
			}
		}
	}
}