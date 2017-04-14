<?php
/**
 * @package    WordPress
 * @subpackage Formidable, gfirem
 * @author     GFireM
 * @copyright  2017
 * @link       http://www.gfirem.com
 * @license    http://www.apache.org/licenses/
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class faa_reset_pass extends faa_base {
	
	public function __construct() {
		parent::__construct(
			'User Password Reset',
			'This Task set a new user password',
			'faa_reset_pass', 'professional'
		);
	}
	
	public function get_defaults() {
		return array( 'faa_reset_pass_user_id' => '', 'faa_reset_pass_user_password' => '' );
	}
	
	/**
	 * Add new content into the action view
	 *
	 * @param $form
	 * @param $form_action
	 * @param $action_control
	 */
	public function view( $form, $form_action, $action_control ) {
		include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'faa_reset_pass_view.php';
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
		try {
			if ( ! empty( $action->post_content ) && ! empty( $action->post_content['faa_reset_pass_user_id'] )
			     && ! empty( $action->post_content['faa_reset_pass_user_password'] )
			) {
				$args                         = array();
				$faa_reset_pass_user_id       = "";
				$faa_reset_pass_user_password = "";
				$action_fields                = array( "faa_reset_pass_user_id", "faa_reset_pass_user_password" );
				
				foreach ( $action_fields as $act_field ) {
					$act_content = $action->post_content[ $act_field ];
					$shortCodes  = FrmFieldsHelper::get_shortcodes( $act_content, $entry->form_id );
					$content     = apply_filters( 'frm_replace_content_shortcodes', $act_content, FrmEntry::getOne( $entry->id ), $shortCodes );
					FrmProFieldsHelper::replace_non_standard_formidable_shortcodes( array(), $content );
					$args[ $act_field ] = do_shortcode( $content );
				}
				
				extract( $args );
				$user_obj = get_userdata( $faa_reset_pass_user_id );
				if ( ! empty( $faa_reset_pass_user_id ) && $user_obj != false ) {
					if ( empty( $faa_reset_pass_user_password ) ) {
						$faa_reset_pass_user_password = wp_generate_password();
					}
					wp_set_password( $faa_reset_pass_user_password, $faa_reset_pass_user_id );
				}
			}
		} catch ( Exception $ex ) {
			$this->show_error( $ex->getMessage() );
		}
	}
	
	public function show_error( $string ) {
		echo '<div class="error fade"><p>' . $string . '</p></div>';
	}
}