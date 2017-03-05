function frmActionReplace() {
	var action_id;

	/**
	 * Get the form fields into the action
	 */
	function onSelectForm() {
		action_id = jQuery(this).attr('action_id');
		var fieldID = this.id.replace('faa_target_form_', ''),
			fieldFilter = document.getElementById('faa_target_field_filter_' + fieldID),
			fieldReplace = document.getElementById('faa_target_field_replace_' + fieldID),
			fieldType = this.getAttribute('data-fieldtype'),
			loading = jQuery('#faa_loading_' + action_id);

		if (this.value === '') {
			fieldFilter.options.length = 1;
			fieldReplace.options.length = 1;
		} else {
			var formID = this.value;
			loading.show();
			jQuery.ajax({
				type: 'POST', url: ajaxurl,
				data: {
					action: 'get_autocomplete_row',
					form_id: formID,
					field_type: fieldType,
					nonce: frmGlobal.nonce
				},
				success: function (fields) {
					fieldFilter.innerHTML = fields;
					fieldReplace.innerHTML = fields;
				}
			}).always(function () {
				loading.hide();
			});
		}
	}

	function save_values() {
		jQuery('table.faa_replace').each(function (i, obj) {
			var action_id = jQuery(this).attr('action_id');
			if (action_id) {
				var faa_targets = jQuery("[name='frm_formidable_action_after_action[" + action_id + "][post_content][faa_targets]']");
				var fields_values = jQuery('table.faa_table_' + action_id + ' tr.faa_row').map(function (i, v) {
					var $form_target = jQuery('.faa_form_target', this),
						$field_filter = jQuery('.faa_field_filter', this),
						$field_filter_value = jQuery('.faa_field_filter_value', this),
						$field_replace = jQuery('.faa_field_replace', this),
						$field_replace_value = jQuery('.faa_field_replace_value', this);

					return {
						'form_target': $form_target.val(),
						'field_filter': $field_filter.val(),
						'field_filter_value': $field_filter_value.val(),
						'field_replace': $field_replace.val(),
						'field_replace_value': $field_replace_value.val(),
					}
				}).get();

				var json = JSON.stringify(fields_values);
				faa_targets.val(json);
			}
		});
	}

	return {
		init: function () {
			if (document.getElementById('frm_notification_settings') !== null) {
				// Bind event handlers for form Settings page
				frmActionReplace.formActionsInit();
			}
		},

		formActionsInit: function () {
			var $formActions = jQuery(document.getElementById('frm_notification_settings')),
				$form = jQuery(document.getElementsByClassName('frm_form_settings'));

			$formActions.on('change', '.faa_get_target', onSelectForm);
			$form.on('submit', save_values);
		}
	};
}

var frmActionReplace = frmActionReplace();
jQuery(document).ready(function ($) {
	frmActionReplace.init();
});