<?php /** @var faa_action $this */ ?>
<input type="hidden" value="<?php echo esc_attr( $this->number ) ?>" name="faa_action_id" id="faa_action_id" class="faa_action_id">
<input type="hidden" value="<?= esc_attr( $form_action->post_content['form_id'] ); ?>" name="<?php echo esc_attr( $action_control->get_field_name( 'form_id' ) ) ?>">
<input type="hidden" value="<?php echo esc_attr( $form_action->post_content['faa_action_enabled'] ); ?>" name="<?php echo esc_attr( $action_control->get_field_name( 'faa_action_enabled' ) ) ?>">
<h3 id="faa_section"><?php _e( 'Select the action you want to execute', 'gfirem_action_after' ) ?></h3>
<span><?php _e( "You can select multiple actions, to execute in the set trigger.", 'gfirem_action_after' ); ?></span>
<div action_id="<?php echo esc_attr( $this->number ) ?>" id="faa_action_container_<?php echo esc_attr( $this->number ) ?>" class="faa_action_container">
	<?php
	$plan            = faa_fs::get_current_plan();
	$actions_to_load = $this->faa_actions_to_load[ $plan ];
	/** @var faa_base $faa_action */
	foreach ( $this->faa_actions as $faa_action ) {
		$faa_control = 0;
		if ( array_key_exists( $faa_action->getSlug(), $actions_control ) ) {
			$faa_control = $actions_control[ $faa_action->getSlug() ];
		}
		$checked = checked( $faa_control, 1, false );
		$style   = '';
		if ( empty( $checked ) ) {
			$style = 'style="display:none;"';
		}
		
		$disabled     = '';
		$disabled_css = '';
		if ( ! array_key_exists( $faa_action->getSlug(), $actions_to_load ) ) {
			$disabled     = $this->disable_input_tag( 'input', $faa_action->plan );
			$disabled_css = $this->disable_class_tag( 'p', $faa_action->plan );
		}
		
		echo "<div class='faa_action_inside'>";
		echo "<h3 class='faa_action_head'>" . esc_attr( $faa_action->getName() ) . "</h3>";
		echo "<p " . $disabled_css . ">";
		echo '<input ' . $disabled . ' action_id="' . esc_attr( $action_control->number ) . '" name="' . esc_attr( $faa_action->getSlug() ) . '" slug="' . esc_attr( $faa_action->getSlug() ) . '" ' . $checked . ' type="checkbox" class="faa_action" name="' . esc_attr( $faa_action->getSlug() ) . '" id="' . esc_attr( $faa_action->getSlug() ) . '" value="' . esc_attr( $faa_control ) . '"/>';
		echo "<label for='" . esc_attr( $faa_action->getSlug() ) . "'>" . $faa_action->getDescription() . "</label>";
		echo "</p>";
		if ( array_key_exists( $faa_action->getSlug(), $actions_to_load ) ) {
			echo "<div " . $style . " id='" . esc_attr( $faa_action->getSlug() ) . "_" . esc_attr( $this->number ) . "'>";
			$faa_action->view( $form, $form_action, $action_control );
			echo "</div>";
		}
		echo "</div>";
	}
	?>
</div>

