(function ($) {
  'use strict';

  wp.customize.bind('ready', function () {
    $('.vetra-multi-checkbox').on('change', function () {
      var $control = $(this).closest('li').parent().siblings('input[type="hidden"]');
      var values = [];
      $(this).closest('ul').find('.vetra-multi-checkbox:checked').each(function () {
        values.push($(this).val());
      });
      $control.val(JSON.stringify(values)).trigger('change');
    });

    toggleControls('vetra_color_palette_enabled', [
      'vetra_light_bg', 'vetra_light_surface', 'vetra_light_text', 'vetra_light_muted',
      'vetra_light_accent', 'vetra_light_accent_soft', 'vetra_dark_bg', 'vetra_dark_surface',
      'vetra_dark_text', 'vetra_dark_muted', 'vetra_dark_accent', 'vetra_dark_accent_soft'
    ]);

    toggleControls('vetra_background_enabled', [
      'vetra_bg_apply_mode', 'vetra_bg_type', 'vetra_bg_color', 'vetra_bg_gradient_start',
      'vetra_bg_gradient_end', 'vetra_bg_gradient_angle', 'vetra_bg_image', 'vetra_bg_image_size',
      'vetra_bg_image_position', 'vetra_bg_image_repeat', 'vetra_bg_image_attachment',
      'vetra_bg_pattern', 'vetra_bg_overlay_color', 'vetra_bg_overlay_opacity'
    ]);

    var backgroundType = wp.customize('vetra_bg_type');
    if (backgroundType) {
      var backgroundEnabled = wp.customize('vetra_background_enabled');
      function updateBackgroundType(value) {
        var enabled = backgroundEnabled ? !!backgroundEnabled.get() : true;
        var groups = {
          color: ['vetra_bg_color'],
          gradient: ['vetra_bg_gradient_start', 'vetra_bg_gradient_end', 'vetra_bg_gradient_angle'],
          image: ['vetra_bg_image', 'vetra_bg_image_size', 'vetra_bg_image_position', 'vetra_bg_image_repeat', 'vetra_bg_image_attachment']
        };
        Object.keys(groups).forEach(function (key) {
          groups[key].forEach(function (id) {
            var control = wp.customize.control(id);
            if (control) control.container.toggle(enabled && key === value);
          });
        });
      }
      updateBackgroundType(backgroundType.get());
      backgroundType.bind(updateBackgroundType);
      if (backgroundEnabled) backgroundEnabled.bind(function () { updateBackgroundType(backgroundType.get()); });
    }

    var featureGroups = {
      vetra_header_enabled: ['vetra_header_sticky', 'vetra_show_theme_switch', 'vetra_header_cta_enabled'],
      vetra_header_cta_enabled: ['vetra_header_cta_text', 'vetra_header_cta_url'],
      vetra_footer_enabled: ['vetra_footer_text', 'vetra_contact_phone', 'vetra_contact_email', 'vetra_contact_address'],
      vetra_home_hero_enabled: ['vetra_hero_eyebrow', 'vetra_hero_title', 'vetra_hero_description', 'vetra_hero_primary_text', 'vetra_hero_primary_url', 'vetra_hero_secondary_text', 'vetra_hero_secondary_url', 'vetra_hero_image'],
      vetra_home_stats_enabled: ['vetra_stat_1_number', 'vetra_stat_1_label', 'vetra_stat_2_number', 'vetra_stat_2_label', 'vetra_stat_3_number', 'vetra_stat_3_label', 'vetra_stat_4_number', 'vetra_stat_4_label'],
      vetra_home_services_enabled: ['vetra_services_title', 'vetra_services_intro', 'vetra_service_1_title', 'vetra_service_1_text', 'vetra_service_2_title', 'vetra_service_2_text', 'vetra_service_3_title', 'vetra_service_3_text'],
      vetra_home_about_enabled: ['vetra_about_title', 'vetra_about_text', 'vetra_about_image'],
      vetra_home_projects_enabled: ['vetra_projects_title', 'vetra_projects_intro', 'vetra_project_1_title', 'vetra_project_1_meta', 'vetra_project_1_image', 'vetra_project_2_title', 'vetra_project_2_meta', 'vetra_project_2_image', 'vetra_project_3_title', 'vetra_project_3_meta', 'vetra_project_3_image'],
      vetra_home_cta_enabled: ['vetra_cta_title', 'vetra_cta_text', 'vetra_cta_button_text', 'vetra_cta_button_url']
    };
    Object.keys(featureGroups).forEach(function (settingName) {
      toggleControls(settingName, featureGroups[settingName]);
    });
  });

  function toggleControls(settingName, controlIds) {
    var setting = wp.customize(settingName);
    if (!setting) return;
    function update(value) {
      controlIds.forEach(function (id) {
        var control = wp.customize.control(id);
        if (control) control.container.toggle(!!value);
      });
    }
    update(setting.get());
    setting.bind(update);
  }

}(jQuery));
