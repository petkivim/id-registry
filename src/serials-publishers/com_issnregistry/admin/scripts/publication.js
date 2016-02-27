jQuery(document).ready(function ($) {
    var publication_id = $("#jform_id").val();
    var form_id = $("#jform_form_id").val();
    var publisher_link_label = $('#jform_link_to_publisher').text();
    var show_label = publisher_link_label.split('|')[0];
    var edit_label = publisher_link_label.split('|')[1];
    var url = window.location.pathname;
    // Observables
    var previous = 'jform_previous';
    var main_series = 'jform_main_series';
    var subseries = 'jform_subseries';
    var another_medium = 'jform_another_medium';
    updatePublisherLink();

    updateElement(previous);
    updateElement(main_series);
    updateElement(subseries);
    updateElement(another_medium);
    observeElementChanges(previous);
    observeElementChanges(main_series);
    observeElementChanges(subseries);
    observeElementChanges(another_medium);

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

    $("button[data-target='#modal-goto-form']").click(function () {
        window.location = url + '?option=com_issnregistry&view=form&layout=edit&id=' + form_id;
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

    function observeElementChanges(observableId) {
        // select the target node
        var target = document.querySelector('#' + observableId);
        // create an observer instance
        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                var elementName = mutation.target.id;
                updateElement(elementName);
            });
        });
        // configuration of the observer:
        var config = {attributes: true};
        // pass in the target node, as well as the observer options
        observer.observe(target, config);
    }

    function updateElement(elementName) {
        // Get value, which is a JSON string
        var elementValue = $('#' + elementName).val();
        // Get rid of jform prefix
        elementName = elementName.replace('jform_', '');
        // Parse JSON
        var json = jQuery.parseJSON(elementValue);
        // Check for null value
        if (json !== null) {
            var content = '<div id="' + elementName + '">';
            for (var i = 0; i < json.title.length; i++) {
                content += json.title[i];
                content += json.title[i].length > 0 && json.issn[i].length > 0 ? ' ' : '';
                content += json.issn[i].length > 0 ? '(' + json.issn[i] + ')' : '';
                if (elementName === 'previous') {
                    content += json.last_issue[i].length > 0 ? ', ' + json.last_issue[i] : '';
                }
                content += (i < json.title.length - 1 ? '<br />' : '');

            }
            // Put all the names inside a div
            content += '</div>';
            // Update values to UI
            updateContent(content, elementName);
        }
    }

    function updateContent(content, elementName) {
        // Remove earlier values
        $('div#' + elementName).remove();
        // Add new values
        $(content).insertBefore('#jform_' + elementName + '_button');
        // Add some margin
        $('div#' + elementName).css("margin-bottom", "1em");
        $('div#' + elementName).css("margin-top", ".4em");
        // Set width to 90%
        $('div#' + elementName).css("width", "90%");
    }
});
