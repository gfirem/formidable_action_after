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
			<label for="<?php echo esc_attr( $action_control->get_field_name( 'faa_webmerge_url' ) ) ?>">
				<b><?php _e( 'WebMerge URL:', 'gfirem_action_after' ); ?></b>
				<span class="frm_help frm_icon_font frm_tooltip_icon" title="" data-original-title="<?php _e( 'This amount is to deduct the user profile, is possible to use a field. Is possible to set as negative amount', 'gfirem_action_after' ); ?>"></span>
			</label>
		</th>
		<td>
			<input id="<?php echo esc_attr( $action_control->get_field_name( 'faa_webmerge_url' ) ) ?>" type="text" class="faa_field" value="<?php echo esc_attr( $form_action->post_content['faa_webmerge_url'] ); ?>" name="<?php echo esc_attr( $action_control->get_field_name( 'faa_webmerge_url' ) ) ?>">
		</td>
	</tr>
	</tbody>
</table>