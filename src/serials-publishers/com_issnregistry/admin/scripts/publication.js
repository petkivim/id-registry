jQuery(document).ready(function ($) {
    var publication_id = $("#jform_id").val();
    var url = window.location.pathname;

    $("#jform_language, #jform_publisher_id, #jform_publication_type, #jform_medium, #jform_frequency, #jform_issued_from_year").chosen({
        disable_search_threshold: 10,
        width: "17em"
    });

    $("button[data-target='#modal-generate-marc']").click(function () {
        SqueezeBox.open(url + '?option=com_issnregistry&view=publication&id=' + publication_id + '&layout=edit&format=preview', {handler: 'iframe', size: {x: 800, y: 600}});
    });

    $("button[data-target='#modal-print']").click(function () {
        SqueezeBox.open(url + '?option=com_issnregistry&view=publication&layout=print&tmpl=component&id=' + publication_id, {handler: 'iframe', size: {x: 1200, y: 600}}
        );
    });
});
