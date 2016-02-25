jQuery(document).ready(function ($) {
    $("#publication_count").chosen({
        disable_search_threshold: 10,
        width: "9em"
    });
    $(".publication_type, .language").chosen({
        disable_search_threshold: 10,
        width: "100%"
    });
});
