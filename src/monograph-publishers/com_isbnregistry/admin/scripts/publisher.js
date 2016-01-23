jQuery(document).ready(function ($) {
    // Init variables
    // Get publisher id
    var publisher_id = $("#jform_id").val();
    // Get current URL
    var url = window.location.pathname;
    // Get session ID
    var sessionId = $("input[type='hidden'][value='1'][name!='jform[id]']").attr('name');
    // Variables for batch ids
    var batch_id_isbns = 0;
    var batch_id_ismns = 0;
    var batch_id_isbn = 0;
    var batch_id_ismn = 0;
    var publication_id_isbn = 0;
    var publication_id_ismn = 0;
    // Get labels
    var label_active = $("#label_active").text();
    var label_closed = $("#label_closed").text();
    var label_activate = $("#label_activate").text();

    // Run async functions when page loads
    loadPublisherIsbnRanges('isbn');
    loadPublisherIsbnRanges('ismn');
    loadPublicationsWithoutIdentifier('isbn');
    loadPublicationsWithoutIdentifier('ismn');
    updatePreviousNames();
    observePreviousNamesChanges();

    $(document).ajaxStart(function () {
        $(document.body).css({'cursor': 'wait'});
    });
    $(document).ajaxComplete(function () {
        $(document.body).css({'cursor': 'default'});
    });

    $("#jform_question_7").chosen({
        max_selected_options: 4,
        width: "17em",
        placeholder_text_multiple: " "
    });
    $("#jform_lang_code").chosen({
        disable_search_threshold: 10,
        width: "17em"
    });
    $("#jform_isbn_range, #jform_ismn_range").chosen({
        disable_search_threshold: 10,
        width: "22em"
    });
    $("#jform_publications_without_isbn, #jform_publications_without_ismn").chosen({
        disable_search_threshold: 10,
        width: "17em"
    });

    $("#jform_get_publisher_identifier_isbn").click(function () {
        // Get ISBN range id
        var range_id = $("#jform_isbn_range").chosen().val();
        getPublisherIdentifier(range_id, 'isbnrange.getRange');
    });

    $("#jform_get_publisher_identifier_ismn").click(function () {
        // Get ISMN range id
        var range_id = $("#jform_ismn_range").chosen().val();
        getPublisherIdentifier(range_id, 'ismnrange.getRange');
    });

    function getPublisherIdentifier(range_id, task) {
        // Set post parameterts
        var postData = {};
        // Session ID
        postData[sessionId] = 1;
        // Component that's called
        postData['option'] = 'com_isbnregistry';
        postData['task'] = task;
        // Set publisher id
        postData['publisherId'] = publisher_id;
        // Set isbn range id
        postData['rangeId'] = range_id;
        // If publisher is not new and isbn range has been selected
        // call API that generates new publisher identifiers
        if (range_id.length > 0 && publisher_id.length > 0) {
            // Add request parameters
            $.post(url, postData)
                    .done(function (data) {
                        if (data.success == true) {
                            location.reload();
                        } else {
                            $('#system-message-container').html(showNotification('error', data.title, data.message));
                        }
                    })
                    .fail(function (xhr, textStatus, errorThrown) {
                        var json = jQuery.parseJSON(xhr.responseText);
                        $('#system-message-container').html(showNotification('error', json.title, json.message));
                    });
        }
    }

    $('div#publisherIsbnRanges').on('click', 'td.isbn_range_col_5_activate', function () {
        // Get publisher isbn range id
        var range_id = $(this).closest('tr').attr('id').replace('row-', '');
        activatePublisherIdentifierRange(range_id, 'isbn');
    });

    $('div#publisherIsmnRanges').on('click', 'td.ismn_range_col_5_activate', function () {
        // Get publisher isbn range id
        var range_id = $(this).closest('tr').attr('id').replace('row-', '');
        activatePublisherIdentifierRange(range_id, 'ismn');
    });


    function activatePublisherIdentifierRange(publisherRangeId, type) {
        // Set post parameterts
        var postData = {};
        // Session ID
        postData[sessionId] = 1;
        // Component that's called
        postData['option'] = 'com_isbnregistry';
        postData['task'] = 'publisher' + type + 'range.activate';
        // Set publisher id
        postData['publisherId'] = publisher_id;
        // Set publisher isbn range id
        postData['publisherRangeId'] = publisherRangeId;
        // If publisher is not new, activate the publisher isbn range
        if (publisher_id.length > 0) {
            // Add request parameters
            $.post(url, postData)
                    .done(function (data) {
                        // If operation was successfull, update view
                        if (data.success == true) {
                            $('#system-message-container').html(showNotification('success', data.title, data.message));
                            loadPublisherIsbnRanges(type);
                        } else {
                            $('#system-message-container').html(showNotification('error', data.title, data.message));
                        }
                    })
                    .fail(function (xhr, textStatus, errorThrown) {
                        var json = jQuery.parseJSON(xhr.responseText);
                        $('#system-message-container').html(showNotification('error', json.title, json.message));
                    });
        }
    }

    function loadPublisherIsbnRanges(type) {
        // Set post parameterts
        var postData = {};
        // Session ID
        postData[sessionId] = 1;
        // Component that's called
        postData['option'] = 'com_isbnregistry';
        postData['task'] = 'publisher' + type + 'range.getRanges';
        // Set publisher id
        postData['publisherId'] = publisher_id;
        // Load ISBN ranges if publisher is not new
        if (publisher_id.length > 0) {
            // Add request parameters
            $.post(url, postData)
                    .done(function (data) {
                        var content = '';
                        for (var i = 0; i < data.length; i++) {
                            var padding = pad(data[i].category, 'X');
                            content += '<tr id="row-' + data[i].id + '"';
                            if (i == 0 && data[i].is_active == 1) {
                                content += ' class="' + type + '_range_active_row"';
                            } else {
                                content += ' class="' + type + '_range_row"';
                            }
                            content += '>';
                            content += '<td class="' + type + '_range_col_1">' + data[i].publisher_identifier + '-' + padding + '-X' + '</td>';
                            content += '<td class="' + type + '_range_col_2">' + data[i].created.split(' ')[0] + ' (' + data[i].created_by + ')</td>';
                            content += '<td class="' + type + '_range_col_3">' + data[i].free + '</td>';
                            content += '<td class="' + type + '_range_col_4">' + data[i].next + '</td>';
                            content += '<td class="' + type + '_range_col_5';
                            if (data[i].is_active == 1) {
                                content += '">' + label_active;
                                // If range hasn't been used yet, add delete icon
                                if (data[i].range_begin == data[i].next) {
                                    content += ' <span class="icon-delete"></span>';
                                }
                            } else if (data[i].is_closed == 1) {
                                content += '">' + label_closed;
                            } else {
                                content += '_activate">' + label_activate;
                            }
                            content += '</td>';
                            content += '</tr>';
                        }
                        // Remove current content
                        $('tr.' + type + '_range_active_row, tr.' + type + '_range_row').remove();
                        // Add new content
                        $(content).insertAfter('#' + type + '_ranges_header');
                    })
                    .fail(function (xhr, textStatus, errorThrown) {
                        var json = jQuery.parseJSON(xhr.responseText);
                        $('#system-message-container').append(showNotification('error', json.title, json.message));
                    });
        }
    }

    function observePreviousNamesChanges() {
        // select the target node
        var target = document.querySelector('#jform_previous_names');
        // create an observer instance
        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                updatePreviousNames();
            });
        });
        // configuration of the observer:
        var config = {attributes: true};
        // pass in the target node, as well as the observer options
        observer.observe(target, config);
    }

    function updatePreviousNames() {
        // Get previous names value, which is a JSON string
        var previous_names = $('#jform_previous_names').val();
        // Parse JSON
        var json = jQuery.parseJSON(previous_names);
        // Check for null value
        if (json != null) {
            // Put all the names inside a div
            var content = '<div id="previous_names">' + json.name.toString().replace(/,/g, ", ") + '</div>';
            // Remove earlier values
            $('div#previous_names').remove();
            // Add new values
            $(content).insertBefore('#jform_previous_names_button');
            // Add some margin
            $('div#previous_names').css("margin-bottom", "1em");
            $('div#previous_names').css("margin-top", ".4em");
            // Set width to 17em
            $('div#previous_names').css("width", "17em");
        }
    }

    $('div#publisherIsbnRanges').on('click', 'span.icon-delete', function () {
        // Get publisher isbn range id
        var range_id = $(this).closest('tr').attr('id').replace('row-', '');
        deletePublisherIdentifierRange(range_id, 'isbn');
    });

    $('div#publisherIsmnRanges').on('click', 'span.icon-delete', function () {
        // Get publisher ismn range id
        var range_id = $(this).closest('tr').attr('id').replace('row-', '');
        deletePublisherIdentifierRange(range_id, 'ismn');
    });

    function deletePublisherIdentifierRange(rangeId, type) {
        // Set post parameterts
        var postData = {};
        // Session ID
        postData[sessionId] = 1;
        // Component that's called
        postData['option'] = 'com_isbnregistry';
        postData['task'] = 'publisher' + type + 'range.delete';
        // Set publisher isbn range id
        postData['publisherRangeId'] = rangeId;
        // Add request parameters
        $.post(url, postData)
                .done(function (data) {
                    // If operation was successfull, update view
                    if (data.success == true) {
                        $('#system-message-container').html(showNotification('success', data.title, data.message));
                        loadPublisherIsbnRanges(type);
                    } else {
                        $('#system-message-container').html(showNotification('error', data.title, data.message));
                    }
                })
                .fail(function (xhr, textStatus, errorThrown) {
                    var json = jQuery.parseJSON(xhr.responseText);
                    $('#system-message-container').html(showNotification('error', json.title, json.message));
                });
    }

    $("#jform_get_isbns").click(function () {
        // Get isbn count
        var isbn_count = $("#jform_isbn_count").val();
        getIdentifiers(isbn_count, 'isbn');

    });

    $("#jform_get_ismns").click(function () {
        // Get ismn count
        var ismn_count = $("#jform_ismn_count").val();
        getIdentifiers(ismn_count, 'ismn');

    });

    function getIdentifiers(count, type) {
        // Set post parameterts
        var postData = {};
        // Session ID
        postData[sessionId] = 1;
        // Component that's called
        postData['option'] = 'com_isbnregistry';
        postData['task'] = 'publisher' + type + 'range.getIdentifiers';
        // Set publisher id
        postData['publisherId'] = publisher_id;
        // Set isbn count
        postData['count'] = count;
        // If publisher is not new, try to generate isbn numbers
        if (publisher_id.length > 0 && count > 0) {
            // Add request parameters
            $.post(url, postData)
                    .done(function (data) {
                        if (data.success == true) {
                            var identifiers = '';
                            $.each(data.identifiers, function (key, value) {
                                identifiers += value + '\n';
                            });
                            $("textarea#jform_created_" + type + "s").html(identifiers);
                            $('#system-message-container').html(showNotification('success', data.title, data.message));
                            loadPublisherIsbnRanges(type);
                            if (type === 'ismn') {
                                batch_id_ismns = data.identifier_batch_id;
                            } else {
                                batch_id_isbns = data.identifier_batch_id;
                            }
                            $('#jform_notify_' + type + 's').prop("disabled", false);
                        } else {
                            $('#system-message-container').html(showNotification('error', data.title, data.message));
                        }
                    })
                    .fail(function (xhr, textStatus, errorThrown) {
                        var json = jQuery.parseJSON(xhr.responseText);
                        $('#system-message-container').html(showNotification('error', json.title, json.message));
                    });
        }
    }

    $("#jform_get_isbn").click(function () {
        // Get selected publication
        var publication_id = $('#jform_publications_without_isbn').val();
        getIdentifier(publication_id, 'isbn');
    });

    $("#jform_get_ismn").click(function () {
        // Get selected publication
        var publication_id = $('#jform_publications_without_ismn').val();
        getIdentifier(publication_id, 'ismn');
    });

    function getIdentifier(publicationId, type) {
        // Set post parameterts
        var postData = {};
        // Session ID
        postData[sessionId] = 1;
        // Component that's called
        postData['option'] = 'com_isbnregistry';
        postData['task'] = 'publisher' + type + 'range.getIdentifier';
        // Set publisher id
        postData['publisherId'] = publisher_id;
        // Set publication id
        postData['publicationId'] = publicationId;
        // If publisher is not new and publication is selected, try to get isbn number
        if (publisher_id.length > 0 && publicationId.length > 0) {
            // Add request parameters
            $.post(url, postData)
                    .done(function (data) {
                        if (data.success == true) {
                            // Get selected label
                            var label = jQuery('#jform_publications_without_' + type + ' option:selected').text();
                            $('#system-message-container').html(showNotification('success', data.title, data.message));
                            loadPublisherIsbnRanges(type);
                            loadPublicationsWithoutIdentifier(type);
                            var link = '<a href="' + url + '?option=com_isbnregistry&view=publication&layout=edit&id=' + publicationId + '" target="new">';
                            link += label + ' (' + data.publication_identifiers[0];
                            link += data.publication_identifiers.length === 2 ? ', ' + data.publication_identifiers[1] : '';
                            link += ')</a>';

                            $('#jform_link_to_publication_' + type).html(link);
                            if (type === 'ismn') {
                                batch_id_ismn = data.identifier_batch_id;
                                publication_id_ismn = publicationId;
                            } else {
                                batch_id_isbn = data.identifier_batch_id;
                                publication_id_isbn = publicationId;
                            }
                            $('#jform_notify_' + type).prop("disabled", false);
                            // Update publications iframe
                            $('#publications_iframe').attr("src", $('#publications_iframe').attr("src"));
                        } else {
                            $('#system-message-container').html(showNotification('error', data.title, data.message));
                            $('#jform_link_to_publication_' + type).html('');
                        }
                    })
                    .fail(function (xhr, textStatus, errorThrown) {
                        var json = jQuery.parseJSON(xhr.responseText);
                        $('#system-message-container').html(showNotification('error', json.title, json.message));
                        $('#jform_link_to_publication_' + type).html('');
                    });
        }
    }

    function loadPublicationsWithoutIdentifier(type) {
        // Set post parameterts
        var postData = {};
        // Session ID
        postData[sessionId] = 1;
        // Component that's called
        postData['option'] = 'com_isbnregistry';
        postData['task'] = 'publication.getPublicationsWithoutIdentifier';
        // Set publisher id
        postData['publisherId'] = publisher_id;
        // Set type
        postData['type'] = type;
        // Load publications if publisher is not new
        if (publisher_id.length > 0) {
            // Add request parameters
            $.post(url, postData)
                    .done(function (data) {
                        // Check that query was succesfull
                        if (data.success == true) {
                            // Remove all options except the first one
                            $('#jform_publications_without_' + type).find('option:not(:first)').remove();
                            // Go through the publications
                            for (var i = 0; i < data.publications.length; i++) {
                                // Add results to dropdown list
                                $('#jform_publications_without_' + type).append($('<option>', {
                                    value: data.publications[i].id,
                                    text: data.publications[i].title
                                }));
                            }
                            // Update Chosen jQuery plugin
                            $('#jform_publications_without_' + type).trigger('liszt:updated');
                        } else {
                            $('#system-message-container').html(showNotification('error', data.title, data.message));
                        }
                    })
                    .fail(function (xhr, textStatus, errorThrown) {
                        var json = jQuery.parseJSON(xhr.responseText);
                        $('#system-message-container').append(showNotification('error', json.title, json.message));
                    });
        }
    }

    $("#jform_notify_isbns, #jform_notify_ismns").click(function () {
        var id = $(this).attr('id');
        var type = id.match(/isbns$/) ? 'isbn' : 'ismn';
        var batchId = (type === 'ismn' ? batch_id_ismns : batch_id_isbns);
        SqueezeBox.open(url + '?option=com_isbnregistry&view=message&layout=send&tmpl=component&code=big_publisher_'
                + type + '&publisherId=' + publisher_id + '&batchId=' + batchId, {handler: 'iframe', size: {x: 800, y: 600}}
        );
    });

    $("#jform_notify_isbn, #jform_notify_ismn").click(function () {
        var id = $(this).attr('id');
        var type = id.match(/isbn$/) ? 'isbn' : 'ismn';
        var publication_id = (type === 'ismn' ? publication_id_ismn : publication_id_isbn);
        var batchId = (type === 'ismn' ? batch_id_ismn : batch_id_isbn);
        SqueezeBox.open(url + '?option=com_isbnregistry&view=message&layout=send&tmpl=component&code=identifier_created_'
                + type + '&publisherId=' + publisher_id + '&batchId=' + batchId + '&publicationId=' + publication_id, {handler: 'iframe', size: {x: 800, y: 600}}
        );
    });

    $("button[data-target='#modal-generate-message']").click(function () {
        var isbnRows = $('.isbn_range_active_row').length;
        var ismnRows = $('.ismn_range_active_row').length;
        var type = 'isbn';
        if (isbnRows == 0 && ismnRows > 0) {
            type = 'ismn';
        }
        SqueezeBox.open(url + '?option=com_isbnregistry&view=message&layout=send&tmpl=component&code=publisher_registered_'
                + type + '&publisherId=' + publisher_id, {handler: 'iframe', size: {x: 800, y: 600}}
        );
    });

    $("button[data-target='#modal-print']").click(function () {
        SqueezeBox.open(url + '?option=com_isbnregistry&view=publisher&layout=print&tmpl=component&id=' + publisher_id, {handler: 'iframe', size: {x: 1200, y: 600}}
        );
    });
});

function pad(num, char) {
    var result = '';
    for (var i = 0; i < num; i++) {
        result += char;
    }
    return result;
}

function showNotification(type, title, message) {
    var html = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
    html += '<div class="alert alert-' + type + '">';
    html += '<h4 class="alert-heading">' + title + '</h4>';
    html += '<p class="alert-message">' + message + '</p>';
    html += '</div>';
    return html;
}