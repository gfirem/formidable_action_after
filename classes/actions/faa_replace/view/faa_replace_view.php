<input type="hidden" value="<?php echo esc_attr( $form_action->post_content['faa_targets'] ); ?>" name="<?php echo esc_attr( $action_control->get_field_name( 'faa_targets' ) ) ?>">
<table action_id="<?php echo esc_attr( $action_control->number ) ?>" class="form-table frm-no-margin faa_replace faa_table_<?php echo esc_attr( $action_control->number ) ?>">
    <tbody id="fab-table-body">
	<?php foreach ( $rows as $row ) : ?>
        <tr class="faa_row">
            <th>
                <label for="">
                    <b><?php _e( 'Combination:', 'gfirem_action_after' ); ?></b>
                    <span class="frm_help frm_icon_font frm_tooltip_icon" title="" data-original-title="<?php _e( 'Create a name for your table here. When you update the form, it will be created in the DB for you and you can change the name later.', 'gfirem_action_after' ); ?>"></span>
                </label>
            </th>
            <td>
                <p>
                    <select class="faa_get_target faa_field faa_form_target" action_id="<?php echo esc_attr( $action_control->number ) ?>" id="faa_target_form_<?php echo esc_attr( $action_control->number ) ?>" name="faa_target_form_<?php echo esc_attr( $action_control->number ) ?>">
                        <option value="">&mdash; <?php _e( 'Select Form', 'formidable' ) ?> &mdash;</option>
						<?php foreach ( $row['data']['form_list'] as $form_opts ) {
							$target = ( ! empty( $row['values']->form_target ) ) ? $row['values']->form_target : '';
							?>
                            <option value="<?php echo absint( $form_opts->id ) ?>"<?php selected( $form_opts->id, $target ) ?>><?php echo FrmAppHelper::truncate( $form_opts->name, 30 ) ?></option>
						<?php } ?>
                    </select>
					<?php _e( 'This is the form target, where the nexts fields will get.', 'gfirem_action_after' ); ?>
                    <img id="faa_loading_<?= $action_control->number ?>" src="/wp-content/plugins/formidable/images/ajax_loader.gif" class="faa_loading"/>
                </p>
                <p><strong><?php _e( 'Filter/Search by:', 'gfirem_action_after' ); ?></strong><br/>
                    <i><?php _e( 'Select the field to filter and set the value to search.', 'gfirem_action_after' ); ?></i><br/>
                    <select action_id="<?php echo esc_attr( $action_control->number ) ?>" class="faa_field faa_field_filter" id="faa_target_field_filter_<?php echo esc_attr( $action_control->number ) ?>" name="faa_target_field_filter_<?php echo esc_attr( $action_control->number ) ?>">
						<?php faa_replace::show_options_for_get_values_field( $row['data']['form_fields'], $row['field_filter'] ); ?>
                    </select>
                    <input action_id="<?php echo esc_attr( $action_control->number ) ?>" class="faa_field faa_field_filter_value" type="text" name="faa_target_field_filter_value_<?php echo esc_attr( $action_control->number ) ?>"
                           id="faa_target_field_filter_value_<?php echo esc_attr( $action_control->number ) ?>" value="<?php echo ( ! empty( $row['values']->field_filter_value ) ) ? esc_attr( $row['values']->field_filter_value ) : '' ?>"/>
                </p>
                <p><strong><?php _e( 'Replace by:', 'gfirem_action_after' ); ?></strong><br/>
                    <i><?php _e( 'Select the field to replace the value and set the value, if you leave the value empty the task will clear the value in the target.', 'gfirem_action_after' ); ?></i><br>
                    <select action_id="<?php echo esc_attr( $action_control->number ) ?>" class="faa_field faa_field_replace" id="faa_target_field_replace_<?php echo esc_attr( $action_control->number ) ?>" name="faa_target_field_replace_<?php echo esc_attr( $action_control->number ) ?>">
						<?php faa_replace::show_options_for_get_values_field( $row['data']['form_fields'], $row['field_replace'] ); ?>
                    </select>
                    <input action_id="<?php echo esc_attr( $action_control->number ) ?>" class="faa_field faa_field_replace_value" type="text" name="faa_target_field_replace_value_<?php echo esc_attr( $action_control->number ) ?>"
                           id="faa_target_field_replace_value_<?php echo esc_attr( $action_control->number ) ?>" value="<?php echo ( ! empty( $row['values']->field_replace_value ) ) ? esc_attr( $row['values']->field_replace_value ) : '' ?>"/>
                </p>
            </td>
        </tr>
	<?php endforeach; ?>
    </tbody>
</table>