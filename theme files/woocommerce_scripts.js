(function($) {
    wp.customize('button_color', function(value) {
        value.bind(function(newVal) {
            $('.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button').css('background-color', newVal);
        });
    });
})(jQuery);
