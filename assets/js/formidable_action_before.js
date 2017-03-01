function frmActionBefore() {
	var action_id,
		controller = false;

	/**
	 * Get the form fields into the action
	 */
	function onSelectForm() {
		action_id = jQuery(this).attr('action_id');
		var fieldID = this.id.replace('fab_target_form_', ''),
			fieldFilter = document.getElementById('fab_target_field_filter_' + fieldID),
			fieldReplace = document.getElementById('fab_target_field_replace_' + fieldID),
			fieldType = this.getAttribute('data-fieldtype'),
			loading = jQuery('#fab_loading_' + action_id);

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

	function myToggleAllowedShortCodes(id) {
		if (typeof (id) == 'undefined') {
			id = '';
		}
		var c = id;

		if (id !== '') {
			var $ele = jQuery(document.getElementById(id));
			if ($ele.attr('class') && id !== 'wpbody-content' && id !== 'content' && id !== 'dyncontent' && id != 'success_msg') {
				var d = $ele.attr('class').split(' ')[0];
				if (d == 'frm_long_input' || typeof d == 'undefined') {
					d = '';
				} else {
					id = jQuery.trim(d);
				}
				c = c + ' ' + d;
			}
		}
		jQuery('#frm-insert-fields-box,#frm-conditionals,#frm-adv-info-tab,#frm-html-tags,#frm-layout-classes,#frm-dynamic-values').removeClass().addClass('tabs-panel ' + c);
	}

	function save_values() {
		if (action_id) {
			var $form = jQuery(this);
			var fab_targets = jQuery("[name='frm_formidable_action_before_action[" + action_id + "][post_content][fab_targets]']");

			var fields_values = jQuery('table.fab_table_'+action_id+' tr.fab_row').map(function (i, v) {
				var $form_target = jQuery('.fab_form_target', this),
					$field_filter = jQuery('.fab_field_filter', this),
					$field_filter_value = jQuery('.fab_field_filter_value', this),
					$field_replace = jQuery('.fab_field_replace', this),
					$field_replace_value = jQuery('.fab_field_replace_value', this);

				return {
					'form_target': $form_target.val(),
					'field_filter': $field_filter.val(),
					'field_filter_value': $field_filter_value.val(),
					'field_replace': $field_replace.val(),
					'field_replace_value': $field_replace_value.val(),
				}
			}).get();

			var json = JSON.stringify(fields_values);
			fab_targets.val(json);
		}
	}

	return {
		init: function () {
			if (document.getElementById('frm_notification_settings') !== null) {
				// Bind event handlers for form Settings page
				frmActionBeforeBackend.formActionsInit();
			}
		},

		formActionsInit: function () {
			var $formActions = jQuery(document.getElementById('frm_notification_settings')),
				$form = jQuery(document.getElementsByClassName('frm_form_settings'));

			$formActions.on('change', '.fab_get_target', onSelectForm);
			$form.on('submit', save_values);

			jQuery(document).bind('ajaxComplete ', function (event, xhr, settings) {
				console.log(settings);
				if (controller == false) {
					if (settings.data.indexOf('frm_form_action_fill') != 0 && settings.data.indexOf('formidable_action_before') != 0) {
						action_id = jQuery('.fab_action_id').val();
						jQuery(document).on('focusin click', 'form input, form textarea, #wpcontent', function (e) {
							e.stopPropagation();
							if (jQuery(this).is(':not(:submit, input[type=button])') && jQuery(this).hasClass("fab_field")) {
								var id = jQuery(this).attr('id');
								myToggleAllowedShortCodes(id);
								jQuery('.frm_code_list a').removeClass('frm_noallow').addClass('frm_allow');
								jQuery('.frm_code_list a.hide_' + id).addClass('frm_noallow').removeClass('frm_allow');
							} else {
								jQuery('.frm_code_list a').addClass('frm_noallow').removeClass('frm_allow');
							}
						});
						controller = true;
					}
				}
			});
		}
	};
}

var frmActionBeforeBackend = frmActionBefore();
jQuery(document).ready(function ($) {
	frmActionBeforeBackend.init();
});