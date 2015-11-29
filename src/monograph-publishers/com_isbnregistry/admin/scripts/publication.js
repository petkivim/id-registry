jQuery(document).ready(function ($) {
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
});
