<input type="hidden" value="<?php echo esc_attr( $this->number ) ?>" name="fab_action_id" id="fab_action_id" class="fab_action_id">
<input type="hidden" value="<?= esc_attr( $form_action->post_content['form_id'] ); ?>" name="<?php echo esc_attr( $action_control->get_field_name( 'form_id' ) ) ?>">
<input type="hidden" value="<?php echo esc_attr( $form_action->post_content['fab_targets'] ); ?>" name="<?php echo esc_attr( $action_control->get_field_name( 'fab_targets' ) ) ?>">
<h3 id="fab_section"><?php _e_fab( 'Set your Configurations' ) ?></h3>
<span><?php _e_fab( "With the next configuration you can find and entry in the selected form searching for one field and replace other field with the set value." ); ?></span>
<hr/>
<table class="form-table frm-no-margin fab_table_<?php echo esc_attr( $this->number ) ?>">
    <tbody id="fab-table-body">
    <?php foreach ( $rows as $row ) : ?>
    <tr class="fab_row">
        <th>
            <label for="">
                <b><?php _e_fab( 'Combination:' ); ?></b>
                <span class="frm_help frm_icon_font frm_tooltip_icon" title="" data-original-title="<?php _e_fab( 'Create a name for your table here. When you update the form, it will be created in the DB for you and you can change the name later.' ); ?>"></span>
            </label>
        </th>
        <td>
            <p>
                <select class="fab_get_target fab_field fab_form_target" action_id="<?php echo esc_attr( $this->number ) ?>" id="fab_target_form_<?php echo esc_attr( $this->number ) ?>" name="fab_target_form_<?php echo esc_attr( $this->number ) ?>">
                    <option value="">&mdash; <?php _e( 'Select Form', 'formidable' ) ?> &mdash;</option>
					<?php foreach ( $row['data']['form_list'] as $form_opts ) { ?>
                        <option value="<?php echo absint( $form_opts->id ) ?>"<?php selected( $form_opts->id, $row['values']->form_target ) ?>><?php echo FrmAppHelper::truncate( $form_opts->name, 30 ) ?></option>
					<?php } ?>
                </select>
				<?php _e_fab( 'This is the form target, where the nexts fields will get.' ); ?>
                <img id="fab_loading_<?= $this->number ?>" src="/wp-content/plugins/formidable/images/ajax_loader.gif" class="fab_loading"/>
            </p>
            <p>
                <select action_id="<?php echo esc_attr( $this->number ) ?>" class="fab_field fab_field_filter" id="fab_target_field_filter_<?php echo esc_attr( $this->number ) ?>" name="fab_target_field_filter_<?php echo esc_attr( $this->number ) ?>">
					<?php fab_admin::show_options_for_get_values_field( $row['data']['form_fields'], $row['field_filter'] ); ?>
                </select>
                <input action_id="<?php echo esc_attr( $this->number ) ?>" class="fab_field fab_field_filter_value" type="text" name="fab_target_field_filter_value_<?php echo esc_attr( $this->number ) ?>" id="fab_target_field_filter_value_<?php echo esc_attr( $this->number ) ?>" value="<?php echo esc_attr($row['values']->field_filter_value) ?>"/>
				<?php _e_fab( 'Select the field to filter and set the value to search.' ); ?>
            </p>
            <p>
                <select action_id="<?php echo esc_attr( $this->number ) ?>" class="fab_field fab_field_replace" id="fab_target_field_replace_<?php echo esc_attr( $this->number ) ?>" name="fab_target_field_replace_<?php echo esc_attr( $this->number ) ?>">
					<?php fab_admin::show_options_for_get_values_field( $row['data']['form_fields'], $row['field_replace'] ); ?>
                </select>
                <input action_id="<?php echo esc_attr( $this->number ) ?>" class="fab_field fab_field_replace_value" type="text" name="fab_target_field_replace_value_<?php echo esc_attr( $this->number ) ?>" id="fab_target_field_replace_value_<?php echo esc_attr( $this->number ) ?>" value="<?php echo esc_attr($row['values']->field_replace_value) ?>"/>
				<?php _e_fab( 'Select the field to replace the value and set the value itself.' ); ?>
            </p>
        </td>
    </tr>
	<?php endforeach; ?>
    </tbody>
</table>