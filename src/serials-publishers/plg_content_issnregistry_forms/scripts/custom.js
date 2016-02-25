jQuery(document).ready(function ($) {
    $("#publication_count, .issued_from_year").chosen({
        disable_search_threshold: 10,
        width: "9em"
    });
    $(".publication_type, .language, .frequency").chosen({
        disable_search_threshold: 10,
        width: "100%"
    });
});
