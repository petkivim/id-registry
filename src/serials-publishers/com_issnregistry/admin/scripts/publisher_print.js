jQuery(document).ready(function ($) {
    $("#jform_lang_code").chosen({
        disable_search_threshold: 10,
        width: "17em"
    });

    $("#print").click(function () {
        window.print();
    });
});
