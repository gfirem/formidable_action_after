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
            <label for="<?php echo esc_attr( $action_control->get_field_name( 'faa_delete_entry' ) ) ?>"><b><?php _e( 'Entry ID', 'gfirem_action_after' ); ?></b> </label>
        </th>
        <td>
            <input type="text" class="faa_field" value="<?php echo esc_attr( $form_action->post_content['faa_delete_entry'] ); ?>" name="<?php echo esc_attr( $action_control->get_field_name( 'faa_delete_entry' ) ) ?>" id="<?php echo esc_attr( $action_control->get_field_name( 'faa_delete_entry' ) ) ?>">
        </td>
    </tr>
    </tbody>
</table>