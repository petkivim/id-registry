jQuery(document).ready(function ($) {
    // Get publisher link label
    var publisher_link_label = $('#jform_link_to_publisher').text();
    var show_label = publisher_link_label.split('|')[0];
    var edit_label = publisher_link_label.split('|')[1];
    var label_confirm_add_publication = $('#label_confirm_add_publication').text();
    // Get form id
    var form_id = $("#jform_id").val();
    // Get current URL
    var url = window.location.pathname;

    updatePublisherLink();

    $("#jform_lang_code, #jform_status, #jform_publisher_id").chosen({
        disable_search_threshold: 10,
        width: "17em",
        search_contains: true
    });

    $("button[data-target='#modal-print']").click(function () {
        SqueezeBox.open(url + '?option=com_issnregistry&view=form&layout=print&tmpl=component&id=' + form_id, {handler: 'iframe', size: {x: 1200, y: 600}}
        );
    });

    $("button[data-target='#modal-generate-message']").click(function () {
        SqueezeBox.open(url + '?option=com_issnregistry&view=message&layout=send&tmpl=component&code=form_handled'
                + '&formId=' + form_id, {handler: 'iframe', size: {x: 1200, y: 850}}
        );
    });

    $("a#add_publication_link").click(function () {
        return confirm(label_confirm_add_publication);
    });
    
    $("#jform_publisher_id").change(function () {
        updatePublisherLink();
    });

    function updatePublisherLink() {
        var publisher_id = $("#jform_publisher_id").val();
        if (publisher_id.length > 0) {
            var link = '<a href="' + url + '?option=com_issnregistry&view=publisher&layout=edit&id=' + publisher_id + '&tmpl=component"';
            link += ' class="modal" rel="{size: {x: 1200, y: 600}, handler:\'iframe\'}">';
            link += show_label.trim() + '</a> | ';
            link += '<a href="' + url + '?option=com_issnregistry&view=publisher&layout=edit&id=' + publisher_id + '" target="new">';
            link += edit_label + '</a>';
            $('#jform_link_to_publisher').html(link);
            // Joomla behavior assignment must be reloaded
            SqueezeBox.assign($$('a.modal'), {parse: 'rel'});
        } else {
            $('#jform_link_to_publisher').html(publisher_link_label);
        }
    }
});
