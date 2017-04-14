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
    <tr class="faa_row">
        <th>
            <label for="<?php echo esc_attr( $action_control->get_field_name( 'faa_delete_post' ) ) ?>"><b><?php _e( 'Post ID', 'gfirem_action_after' ); ?></b> </label>
        </th>
        <td>
            <input type="text" class="faa_field" value="<?php echo esc_attr( $form_action->post_content['faa_delete_post'] ); ?>" name="<?php echo esc_attr( $action_control->get_field_name( 'faa_delete_post' ) ) ?>" id="<?php echo esc_attr( $action_control->get_field_name( 'faa_delete_post' ) ) ?>">
        </td>
    </tr>
    </tbody>
</table>