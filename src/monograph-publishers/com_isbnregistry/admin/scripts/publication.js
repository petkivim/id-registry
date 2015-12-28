jQuery(document).ready(function ($) {
	var publisher_link_label = $('#jform_link_to_publisher').text();
	var url = window.location.pathname;

	updatePublisherLink();
	
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
	
	$( "#jform_publisher_id" ).change(function() {
		updatePublisherLink();
	});
	
	function updatePublisherLink() {
		var publisher_id = $( "#jform_publisher_id" ).val();
		if(publisher_id.length > 0) {
			var link = '<a href="' + url + '?option=com_isbnregistry&view=publisher&layout=edit&id=' + publisher_id + '" target="new">';
			link += publisher_link_label + '</a>';
		$('#jform_link_to_publisher').html(link);
		} else {
			$('#jform_link_to_publisher').html(publisher_link_label);
		}
	}
});
