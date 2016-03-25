jQuery(document).ready(function ($) {
    $("#jform_lang_code").chosen({
        disable_search_threshold: 10,
        width: "17em"
    });

    $("#print").click(function () {
        window.print();
    });

    // Hide some buttons - they're not needed
    $('#jform_contact_person_button').hide();

    updateElement('jform_contact_person');

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
            for (var i = 0; i < json.name.length; i++) {
                content += json.name[i];
                content += json.name[i].length > 0 && json.email[i].length > 0 ? ' ' : '';
                content += json.email[i].length > 0 ? '(' + json.email[i] + ')' : '';
                content += (i < json.name.length - 1 ? '<br />' : '');

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
