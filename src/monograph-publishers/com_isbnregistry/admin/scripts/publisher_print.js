jQuery(document).ready(function ($) {
    updatePreviousNames();

    $("#jform_question_7").chosen({
        max_selected_options: 4,
        width: "17em",
        placeholder_text_multiple: " "
    });
    $("#jform_lang_code").chosen({
        disable_search_threshold: 10,
        width: "17em"
    });

    // Hide previous names button - it's not needed
    $('#jform_previous_names_button').hide();

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

    $("#print").click(function () {
        window.print();
    });
});
