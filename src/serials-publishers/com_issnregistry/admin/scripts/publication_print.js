jQuery(document).ready(function ($) {
    var previous = 'jform_previous';
    var main_series = 'jform_main_series';
    var subseries = 'jform_subseries';
    var another_medium = 'jform_another_medium';

    updateElement(previous);
    updateElement(main_series);
    updateElement(subseries);
    updateElement(another_medium);

    // Hide some buttons - they're not needed
    $('#jform_previous_button, #jform_main_series_button, #jform_subseries_button, #jform_another_medium_button').hide();

    $("#jform_language, #jform_publisher_id, #jform_publication_type, #jform_medium, #jform_frequency, #jform_issued_from_year").chosen({
        disable_search_threshold: 10,
        width: "17em"
    });

    $("#print").click(function () {
        window.print();
    });

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
