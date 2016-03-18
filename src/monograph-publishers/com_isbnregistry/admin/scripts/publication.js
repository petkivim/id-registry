jQuery(document).ready(function ($) {
    // Get session ID
    var sessionId = $("input[type='hidden'][value='1'][name!='jform[id]']").attr('name');
    var publication_id = $("#jform_id").val();
    var publisher_link_label = $('#jform_link_to_publisher').text();
    var show_label = publisher_link_label.split('|')[0];
    var edit_label = publisher_link_label.split('|')[1];
    var url = window.location.pathname;
    var label_confirm_delete = $("#label_confirm_delete").text();

    updatePublisherLink();

    $("select[id^='jform_role_'], #jform_fileformat").chosen({
        max_selected_options: 4,
        width: "17em",
        placeholder_text_multiple: " "
    });
    $("#jform_type").chosen({
        max_selected_options: 3,
        width: "17em",
        placeholder_text_multiple: " "
    });
    $("#jform_publisher_id, #jform_lang_code, #jform_publication_type, #jform_publication_format, #jform_language, #jform_year, #jform_month,#jform_edition").chosen({
        disable_search_threshold: 10,
        width: "17em"
    });

    $("#jform_publisher_id").change(function () {
        updatePublisherLink();
    });

    function updatePublisherLink() {
        var publisher_id = $("#jform_publisher_id").val();
        if (publisher_id.length > 0) {
            var link = '<a href="' + url + '?option=com_isbnregistry&view=publisher&layout=edit&id=' + publisher_id + '&tmpl=component"';
            link += ' class="modal" rel="{size: {x: 1200, y: 600}, handler:\'iframe\'}">';
            link += show_label.trim() + '</a> | ';
            link += '<a href="' + url + '?option=com_isbnregistry&view=publisher&layout=edit&id=' + publisher_id + '" target="new">';
            link += edit_label + '</a>';
            $('#jform_link_to_publisher').html(link);
            // Joomla behavior assignment must be reloaded
            SqueezeBox.assign($$('a.modal'), {parse: 'rel'});
        } else {
            $('#jform_link_to_publisher').html(publisher_link_label);
        }
    }

    $("button[data-target='#modal-generate-marc']").click(function () {
        SqueezeBox.open(url + '?option=com_isbnregistry&view=publication&id=' + publication_id + '&layout=edit&format=preview', {handler: 'iframe', size: {x: 800, y: 600}});
    });

    $("button[data-target='#modal-print']").click(function () {
        SqueezeBox.open(url + '?option=com_isbnregistry&view=publication&layout=print&tmpl=component&id=' + publication_id, {handler: 'iframe', size: {x: 1200, y: 600}}
        );
    });

    $("span.icon-delete").click(function () {
        if (confirm(label_confirm_delete) === true) {
            // Get identifier
            var identifier = $(this).closest('div').attr('id');
            deleteIdentifier(identifier);
        }
    });

    function deleteIdentifier(identifier) {
        // Set post parameterts
        var postData = {};
        // Session ID
        postData[sessionId] = 1;
        // Component that's called
        postData['option'] = 'com_isbnregistry';
        postData['task'] = 'identifier.delete';
        // Set identifier
        postData['identifier'] = identifier;
        // Add request parameters
        $.post(url, postData)
                .done(function (data) {
                    // If operation was successfull, update view
                    if (data.success == true) {
                        $('#system-message-container').html(showNotification('success', data.title, data.message));
                        $("#" + identifier).remove();
                    } else {
                        $('#system-message-container').html(showNotification('error', data.title, data.message));
                    }
                })
                .fail(function (xhr, textStatus, errorThrown) {
                    var json = jQuery.parseJSON(xhr.responseText);
                    $('#system-message-container').html(showNotification('error', json.title, json.message));
                });
    }
});

function showNotification(type, title, message) {
    var html = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
    html += '<div class="alert alert-' + type + '">';
    html += '<h4 class="alert-heading">' + title + '</h4>';
    html += '<p class="alert-message">' + message + '</p>';
    html += '</div>';
    return html;
}