jQuery(document).ready(function ($) {
    // Get form id
    var form_id = $("#jform_id").val();
    // Get current URL
    var url = window.location.pathname;

    $("#jform_lang_code, #jform_status, #jform_publisher_id").chosen({
        disable_search_threshold: 10,
        width: "17em"
    });

    $("button[data-target='#modal-print']").click(function () {
        SqueezeBox.open(url + '?option=com_issnregistry&view=form&layout=print&tmpl=component&id=' + form_id, {handler: 'iframe', size: {x: 1200, y: 600}}
        );
    });
});
