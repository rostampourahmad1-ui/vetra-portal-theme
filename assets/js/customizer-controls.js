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
