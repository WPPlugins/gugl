function gugl_hits_counter() {
    jQuery.ajax({
    url: gugl_call_to_update.ajaxurl,
    data: ({action : 'gugl_update_clicks'}),
    async: false
    });
}