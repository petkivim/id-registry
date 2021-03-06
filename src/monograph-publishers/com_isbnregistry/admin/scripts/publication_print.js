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
    $("#jform_publisher_id, #jform_lang_code, #jform_publication_type, #jform_publication_format, #jform_language, #jform_year, #jform_month,#jform_edition").chosen({
        disable_search_threshold: 10,
        width: "17em"
    });

    $("#print").click(function () {
        window.print();
    });
});
