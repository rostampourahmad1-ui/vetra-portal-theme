(function ($) {
  'use strict';

  wp.customize.bind('ready', function () {
    $('.vetra-multi-checkbox').on('change', function () {
      var $control = $(this).closest('li').parent().siblings('input[type="hidden"]');
      var values = [];
      $(this).closest('ul').find('.vetra-multi-checkbox:checked').each(function () {
        values.push($(this).val());
      });
      $control.val(values.join(',')).trigger('change');
    });
  });
}(jQuery));
