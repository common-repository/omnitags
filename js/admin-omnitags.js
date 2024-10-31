jQuery( document ).ajaxStart(function() {
    jQuery( "#loading" ).show();
});

jQuery( document ).ajaxStop(function() {
    jQuery( "#loading" ).hide();
});

function jsSaveConfigValue(field_key, value, wp_hook) {
    jQuery.ajax({
        url: omnitags_key_value.ajaxurl,
        method: "POST",
        data: {
            action: omnitags_key_value.action,
            nonce: omnitags_key_value.nonce,
            field_key: field_key,
            value: value,
            wp_hook: wp_hook
        },
        dataType: 'json'
    }).fail(function( data ) {
        alert(data);
    });
}