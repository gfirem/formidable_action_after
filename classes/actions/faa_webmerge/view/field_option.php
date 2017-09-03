<?php
/**
 * @package WordPress
 * @subpackage Formidable, gfirem_after_of
 * @author GFireM
 * @copyright 2017
 * @link http://www.gfirem.com
 * @license http://www.apache.org/licenses/
 *
 */
?>
<tr>
	<td>
		<label for="field_options[webmerge_name_<?php echo esc_attr($field['id']) ?>]"><?php _e( 'Webmerge name', 'gfirem_action_after' ); ?></label>
		<span class="frm_help frm_icon_font frm_tooltip_icon" title="" data-original-title="<?php _e( 'This name need to match with the name of field in the webmerge template. If you leave blank the field will use the field key as name to send to webmerge.', 'gfirem_action_after' ); ?>"></span>
	</td>
	<td>
		<input type="text" name="field_options[webmerge_name_<?php echo esc_attr($field['id']) ?>]" id="field_options[webmerge_name_<?php echo esc_attr($field['id']) ?>]" value="<?php echo esc_attr( $field['webmerge_name'] ) ?>"/>
	</td>
</tr>