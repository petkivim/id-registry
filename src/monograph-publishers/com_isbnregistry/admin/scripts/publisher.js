jQuery(document).ready(function ($) {
	loadPublisherIsbnRanges();
	
	$(document).ajaxStart(function () {
		$(document.body).css({ 'cursor': 'wait' })
	});
	$(document).ajaxComplete(function () {
		$(document.body).css({ 'cursor': 'default' })
	});
	
    $("#jform_question_7").chosen({
        max_selected_options: 4,
        width: "17em",
        placeholder_text_multiple: " "
    });
	$("#jform_lang_code").chosen({
		disable_search_threshold: 10,
		width: "17em"
	});	
	$("#jform_isbn_range").chosen({
		disable_search_threshold: 10,
		width: "22em"
	});	
	$("#jform_get_publisher_identifier").click(function(){
		// Get ISBN range id
		var isbn_range_id = $("#jform_isbn_range").chosen().val();
		// Get publisher id
		var publisher_id = $("#jform_id").val();
		// Get current URL
		var url = window.location.pathname;		
		// Get session ID
		var name = $("input[type='hidden'][value='1']").attr('name');
		// Set post parameterts
		var postData = {};
		// Session ID
		postData[name] = 1;
		// Component that's called
		postData['option'] = 'com_isbnregistry';
		postData['task'] = 'isbnrange.getIsbnRange';
		// Set publisher id
		postData['publisherId'] = publisher_id;	
		// Set isbn range id
		postData['isbnRangeId'] = isbn_range_id;		
		// If publisher is not new and isbn range has been selected
		// call API that generates new publisher identifiers
		if(isbn_range_id.length > 0 && publisher_id.length > 0) {
			// Add request parameters
			$.post( url, postData)
			.done(function( data ) {
				if(data.publisherIdentifier != 0) {
					location.reload();
				}
			});
		}
	});

	$('div#publisherIsbnRanges').on('click', 'td.isbn_range_col_4_activate', function(){
		// Get publisher id
		var publisher_id = $("#jform_id").val();
		// Get publisher isbn range id
		var publisher_isbn_range_id = $(this).closest('tr').attr('id').replace('row-', '');
		// Get current URL
		var url = window.location.pathname;
		// Get session ID
		var name = $("input[type='hidden'][value='1']").attr('name');
		// Set post parameterts
		var postData = {};
		// Session ID
		postData[name] = 1;
		// Component that's called
		postData['option'] = 'com_isbnregistry';
		postData['task'] = 'publisherisbnrange.activateIsbnRange';
		// Set publisher id
		postData['publisherId'] = publisher_id;
		// Set publisher isbn range id
		postData['publisherIsbnRangeId'] = publisher_isbn_range_id;
		// If publisher is not new, activate the publisher isbn range
		if(publisher_id.length > 0) {
			// Add request parameters
			$.post( url, postData )
			.done(function( data ) {
				// If operation was successfull, update view
				if(data.success == true) {
					$('tr.isbn_range_active_row, tr.isbn_range_row').remove();
					loadPublisherIsbnRanges();
				}
			});
		}		
	});
	
	function loadPublisherIsbnRanges() {
		// Get publisher id
		var publisher_id = $("#jform_id").val();
		// Get current URL
		var url = window.location.pathname;		
		// Get session ID
		var name = $("input[type='hidden'][value='1']").attr('name');
		// Set post parameterts
		var postData = {};
		// Session ID
		postData[name] = 1;
		// Component that's called
		postData['option'] = 'com_isbnregistry';
		postData['task'] = 'publisherisbnranges.getIsbnRanges';
		// Set publisher id
		postData['publisherId'] = publisher_id;		
		// Get labels
		var active = $("#label_active").text();
		var closed = $("#label_closed").text();
		var activate = $("#label_activate").text();
		// Load ISBN ranges if publisher is not new
		if(publisher_id.length > 0) {
			// Add request parameters
			$.post( url, postData)
			.done(function( data ) {
				var content = '';
				for (var i = 0; i < data.length; i++) {
					var padding = pad(data[i].category, 'X');
					content += '<tr id="row-' + data[i].id + '"';
					if(i == 0) {
						content += ' class="isbn_range_active_row"';
					}  else {
						content += ' class="isbn_range_row"';
					}
					content += '>';
					content += '<td class="isbn_range_col_1">' + data[i].publisher_identifier + '-' + padding + '-X' + '</td>';
					content += '<td class="isbn_range_col_2">' + data[i].free + '</td>';
					content += '<td class="isbn_range_col_3">' + data[i].next + '</td>';
					content += '<td class="isbn_range_col_4';
					if(data[i].is_active == 1) {
						content += '">' + active;
					} else if(data[i].is_closed == 1) {
						content += '">' + closed;
					} else {
						content += '_activate">' + activate;
					}
					content += '</td>';
					content += '</tr>';
				}
				$(content).insertAfter('#isbn_ranges_header');
			});
		}
	}	
});

function pad(num, char) {
    var result = '';
    for (var i = 0; i < num; i++) {
        result += char;
    }
    return result;
}