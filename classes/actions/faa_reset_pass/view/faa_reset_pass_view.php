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
?>
<table action_id="<?php echo esc_attr( $action_control->number ) ?>" class="form-table frm-no-margin">
    <tbody id="fab-table-body">
    <tr>
        <th>
            <label for="<?php echo esc_attr( $action_control->get_field_name( 'faa_reset_pass_user_id' ) ) ?>">
                <b><?php _e( 'User ID:', 'gfirem_action_after' ); ?></b>
                <span class="frm_help frm_icon_font frm_tooltip_icon" title="" data-original-title="<?php _e( 'Select the field where the user id will store, you can use [user_id] to get the current user id.', 'gfirem_action_after' ); ?>"></span>
            </label>
        </th>
        <td>
            <input id="<?php echo esc_attr( $action_control->get_field_name( 'faa_reset_pass_user_id' ) ) ?>" type="text" class="faa_field" value="<?php echo esc_attr( $form_action->post_content['faa_reset_pass_user_id'] ); ?>" name="<?php echo esc_attr( $action_control->get_field_name( 'faa_reset_pass_user_id' ) ) ?>">
            <p><?php _e( 'You can use this shortcode to get the user id from email: [password-get-user-id][field_id][/password-get-user-id]', 'gfirem_action_after' ); ?></p>
        </td>
    </tr>
    <tr>
        <th>
            <label for="<?php echo esc_attr( $action_control->get_field_name( 'faa_reset_pass_user_password' ) ) ?>">
                <b><?php _e( 'Password', 'gfirem_action_after' ); ?></b>
                <span class="frm_help frm_icon_font frm_tooltip_icon" title="" data-original-title="<?php _e( 'This message will be record into the MyCred Log.', 'gfirem_action_after' ); ?>"></span>
            </label>
        </th>
        <td>
            <input id="<?php echo esc_attr( $action_control->get_field_name( 'faa_reset_pass_user_password' ) ) ?>" type="text" class="faa_field" value="<?php echo esc_attr( $form_action->post_content['faa_reset_pass_user_password'] ); ?>" name="<?php echo esc_attr( $action_control->get_field_name( 'faa_reset_pass_user_password' ) ) ?>">
            <p><?php _e( 'If you leave this field in blank the system auto generate a password.', 'gfirem_action_after' ); ?></p>
        </td>
    </tr>
    </tbody>
</table>
