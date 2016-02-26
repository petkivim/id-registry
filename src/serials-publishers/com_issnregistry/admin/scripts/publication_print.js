jQuery(document).ready(function ($) {
    $("#jform_language, #jform_publisher_id, #jform_publication_type, #jform_medium, #jform_frequency, #jform_issued_from_year").chosen({
        disable_search_threshold: 10,
        width: "17em"
    });

    $("#print").click(function () {
        window.print();
    });
});
