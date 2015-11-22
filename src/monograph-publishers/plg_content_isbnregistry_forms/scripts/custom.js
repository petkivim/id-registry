jQuery(document).ready(function($) {
	$("#question_7").chosen({max_selected_options: 4});
	$("#published_before,#publications_public,#publications_intra,#publishing_activity,#publication_type,#publication_format,#language,#year,#month,#edition").chosen({disable_search_threshold: 10});
});
