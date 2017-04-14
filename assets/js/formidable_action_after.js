function frmActionAfter() {
	var controller = false;

	function onShowContainer() {
		var action_id = this.getAttribute('action_id'),
			containerId = this.getAttribute('slug'),
			container = jQuery('#' + containerId + '_' + action_id);

		if (jQuery(this).is(':checked')) {
			container.show();
			jQuery(this).val('1');
		}
		else {
			container.hide();
			jQuery(this).val('0');
		}
	}

	function save_values() {
		jQuery('.faa_action_container').each(function (i, obj) {
			var action_id = jQuery(this).attr('action_id');
			if (action_id) {
				var faa_targets = jQuery("[name='frm_gfirem_action_after_action[" + action_id + "][post_content][faa_action_enabled]']");
				var fields_values = jQuery('#faa_action_container_' + action_id + ' div.faa_action_inside').map(function (i, v) {
					var $control = jQuery('.faa_action', this);
					return {
						'enabled': $control.val(),
						'slug': $control.attr('slug'),
						'name': $control.attr('name')
					}
				}).get();

				var json = JSON.stringify(fields_values);
				faa_targets.val(json);
			}
		});
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


	return {
		init: function () {
			if (document.getElementById('frm_notification_settings') !== null) {
				// Bind event handlers for form Settings page
				frmActionAfter.formActionsInit();
			}
		},

		formActionsInit: function () {
			var $formActions = jQuery(document.getElementById('frm_notification_settings')),
				$form = jQuery(document.getElementsByClassName('frm_form_settings'));

			$form.on('submit', save_values);

			jQuery(document).bind('ajaxComplete ', function (event, xhr, settings) {
				if (controller == false) {
					if (settings.data.indexOf('frm_form_action_fill') != 0 && settings.data.indexOf('gfirem_action_after') != 0) {
						$formActions.on('click', '.faa_action', onShowContainer);
						jQuery(document).on('focusin click', 'form input, form textarea, #wpcontent', function (e) {
							e.stopPropagation();
							if (jQuery(this).is(':not(:submit, input[type=button])') && jQuery(this).hasClass("faa_field")) {
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

var frmActionAfter = frmActionAfter();
jQuery(document).ready(function ($) {
	frmActionAfter.init();
});