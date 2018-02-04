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

/** @var faa_mycred $this */
if ( ! $this->is_mycred_active() ):
    _e('This task need myCRED be active!', 'gfirem_action_after');
else:
	?>
    <table action_id="<?php echo esc_attr( $action_control->number ) ?>" class="form-table frm-no-margin">
        <tbody id="fab-table-body">
        <tr>
            <th>
                <label for="<?php echo esc_attr( $action_control->get_field_name( 'faa_mycred_amount' ) ) ?>">
                    <b><?php _e( 'Amount:', 'gfirem_action_after' ); ?></b>
                    <span class="frm_help frm_icon_font frm_tooltip_icon" title="" data-original-title="<?php _e( 'This amount is to deduct the user profile, is possible to use a field. Is possible to set as negative amount', 'gfirem_action_after' ); ?>"></span>
                </label>
            </th>
            <td>
                <input id="<?php echo esc_attr( $action_control->get_field_name( 'faa_delete_user' ) ) ?>" type="text" class="faa_field" value="<?php echo esc_attr( $form_action->post_content['faa_mycred_amount'] ); ?>" name="<?php echo esc_attr( $action_control->get_field_name( 'faa_mycred_amount' ) ) ?>">
            </td>
        </tr>
        <tr>
            <th>
                <label for="<?php echo esc_attr( $action_control->get_field_name( 'faa_mycred_user' ) ) ?>">
                    <b><?php _e( 'User ID:', 'gfirem_action_after' ); ?></b>
                    <span class="frm_help frm_icon_font frm_tooltip_icon" title="" data-original-title="<?php _e( 'Select the field where the user id will store, you can use [user_id] to get the current user id.', 'gfirem_action_after' ); ?>"></span>
                </label>
            </th>
            <td>
                <input id="<?php echo esc_attr( $action_control->get_field_name( 'faa_mycred_user' ) ) ?>" type="text" class="faa_field" value="<?php echo esc_attr( $form_action->post_content['faa_mycred_user'] ); ?>" name="<?php echo esc_attr( $action_control->get_field_name( 'faa_mycred_user' ) ) ?>">
            </td>
        </tr>
        <tr>
            <th>
                <label for="<?php echo esc_attr( $action_control->get_field_name( 'faa_mycred_message' ) ) ?>">
                    <b><?php _e( 'Message:', 'gfirem_action_after' ); ?></b>
                    <span class="frm_help frm_icon_font frm_tooltip_icon" title="" data-original-title="<?php _e( 'This message will be record into the MyCred Log.', 'gfirem_action_after' ); ?>"></span>
                </label>
            </th>
            <td>
                <input id="<?php echo esc_attr( $action_control->get_field_name( 'faa_mycred_message' ) ) ?>" type="text" class="faa_field" value="<?php echo esc_attr( $form_action->post_content['faa_mycred_message'] ); ?>" name="<?php echo esc_attr( $action_control->get_field_name( 'faa_mycred_message' ) ) ?>">
            </td>
        </tr>
        </tbody>
    </table>
<?php endif; ?>