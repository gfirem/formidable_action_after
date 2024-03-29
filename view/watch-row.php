<div id="fac_frm_watch_lookup_<?php echo esc_attr( $field_id . '_' . $row_key ) ?>">
    <select name="field_options[fac_watch_lookup_<?php echo esc_attr( $field_id ) ?>][]">
        <option value=""><?php _e( '&mdash; Select Field &mdash;', 'formidable' ) ?></option>
        <option value="id"><?php _e( 'Entry ID', 'gfirem_action_after' ) ?></option>
		<?php foreach ( $lookup_fields as $field_option ) {
			if ( $field_option->id == $field_id ) {
				continue;
			}
			$selected = ( $field_option->id == $selected_field ) ? ' selected="selected"' : '';
			?>
            <option value="<?php echo esc_attr( $field_option->id ) ?>"<?php echo esc_attr( $selected ) ?>><?php echo ( '' == $field_option->name ) ? $field_option->id . ' ' . __( '(no label)', 'formidable' ) : esc_html( $field_option->name );
				?></option>
		<?php } ?>
    </select>
    <a href="javascript:void(0)" class="fac_frm_remove_tag frm_icon_font" data-removeid="fac_frm_watch_lookup_<?php echo esc_attr( $field_id . '_' . $row_key ) ?>" data-fieldid="<?php echo esc_attr( $field_id ) ?>"></a>
    <a href="javascript:void(0)" class="fac_frm_add_tag frm_icon_font fac_frm_add_watch_lookup_row"></a>
</div>