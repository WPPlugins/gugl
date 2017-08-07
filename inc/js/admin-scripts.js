jQuery(document).ready(function(){
    jQuery("#gugl_color").change(function () {
        if (jQuery(this).val() == 'Custom') {
            jQuery("#gugl_button").hide();
            jQuery("#button-preview").hide();
        } else{
            var Color = jQuery(this).val();
            jQuery("#gugl_button").show().removeClass().addClass("gugl_button_" + Color);
            jQuery("#button-preview").show();
        };
    });

    jQuery("#gugl_text").keyup(function () {
        var Caption = jQuery(this).val();
        jQuery('#button-text').text(Caption);
    });

    jQuery("#gugl_own_color_checkbox").change(function () {
        if (this.checked) {
            jQuery("#additional-class").show('slow');
            jQuery('#gugl_color').append('<option value="Custom" selected="selected">Custom</option>');
    	} else {
            jQuery("#additional-class").hide('slow');
            jQuery("#gugl_color option[value='Custom']").remove();
    	};
    });
});

jQuery(document).ready(function(){
    if (jQuery("#gugl_own_color_checkbox").delay(100).is(':checked')) {
        jQuery("#additional-class").show();
    } else {
        jQuery("#additional-class").hide();
    };
});

jQuery(document).ready(function(){
    if (jQuery("#gugl_color").delay(100).val() == 'Custom') {
        jQuery("#gugl_button").hide();
        jQuery("#button-preview").hide();
    };
});

