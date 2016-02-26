jQuery(document).ready(function ($) {
    var publication_id = $("#jform_id").val();
    var publisher_link_label = $('#jform_link_to_publisher').text();
    var show_label = publisher_link_label.split('|')[0];
    var edit_label = publisher_link_label.split('|')[1];
    var url = window.location.pathname;

    updatePublisherLink();

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
