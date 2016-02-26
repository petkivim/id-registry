jQuery(document).ready(function ($) {
    // Get publisher id
    var publisher_id = $("#jform_id").val();
    // Get current URL
    var url = window.location.pathname;

    $("#jform_lang_code").chosen({
        disable_search_threshold: 10,
        width: "17em"
    });

    $("button[data-target='#modal-print']").click(function () {
        SqueezeBox.open(url + '?option=com_issnregistry&view=publisher&layout=print&tmpl=component&id=' + publisher_id, {handler: 'iframe', size: {x: 1200, y: 600}}
        );
    });
});
