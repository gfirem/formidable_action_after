<input type="hidden" value="<?php echo esc_attr( $this->number ) ?>" name="faa_action_id" id="faa_action_id" class="faa_action_id">
<input type="hidden" value="<?= esc_attr( $form_action->post_content['form_id'] ); ?>" name="<?php echo esc_attr( $action_control->get_field_name( 'form_id' ) ) ?>">
<input type="hidden" value="<?php echo esc_attr( $form_action->post_content['faa_action_enabled'] ); ?>" name="<?php echo esc_attr( $action_control->get_field_name( 'faa_action_enabled' ) ) ?>">
<h3 id="faa_section"><?php _e_faa( 'Select the action you want to execute' ) ?></h3>
<span><?php _e_faa( "You can select multiple actions, to execute in the set trigger." ); ?></span>
<div action_id="<?php echo esc_attr( $this->number ) ?>" id="faa_action_container_<?php echo esc_attr( $this->number ) ?>" class="faa_action_container">
	<?php
	/** @var faa_base $faa_action */
	foreach ( $this->faa_actions as $faa_action ) {
		if ( array_key_exists( $faa_action->getSlug(), $actions_control ) ) {
			$faa_control = $actions_control[ $faa_action->getSlug() ];
		}
		$checked = checked( $faa_control, 1, false );
		$style   = '';
		if ( empty( $checked ) ) {
			$style = 'style="display:none;"';
		}
		echo "<div class='faa_action_inside'>";
		echo "<h3 class='faa_action_head'>" . esc_attr( $faa_action->getName() ) . "</h3>";
		echo '<input  action_id="' . esc_attr( $action_control->number ) . '" name="' . esc_attr( $faa_action->getName() ) . '" slug="' . esc_attr( $faa_action->getSlug() ) . '" ' . $checked . ' type="checkbox" class="faa_action" name="' . esc_attr( $faa_action->getSlug() ) . '" id="' . esc_attr( $faa_action->getSlug() ) . '" value="' . esc_attr( $faa_control ) . '"/>';
		echo "<label for='" . esc_attr( $faa_action->getSlug() ) . "'>" . $faa_action->getDescription() . "</label>";
		echo "<hr/><div " . $style . " id='" . esc_attr( $faa_action->getSlug() ) . "_" . esc_attr( $this->number ) . "'>";
		$faa_action->view( $form, $form_action, $action_control );
		echo "</div></div>";
	}
	?>
</div>

