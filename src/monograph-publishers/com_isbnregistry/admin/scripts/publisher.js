jQuery(document).ready(function ($) {
    $("#jform_question_7").chosen({
        max_selected_options: 4,
        width: "17em",
        placeholder_text_multiple: " "
    });
	$("#jform_lang_code").chosen({
		disable_search_threshold: 10,
		width: "17em"
	});	
});
