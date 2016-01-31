jQuery(document).ready(function ($) {
    $("#jform_isbn_categories, #jform_ismn_categories").chosen({
        max_selected_options: 5,
        width: "17em",
        placeholder_text_multiple: " "
    });
    $("#jform_message_type_id").chosen({
        disable_search_threshold: 10,
        width: "17em"
    });	
});
