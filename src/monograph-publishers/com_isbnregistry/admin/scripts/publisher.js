jQuery(document).ready(function ($) {
	loadPublisherIsbnRanges();
	loadPublicationsWithoutIdentifier();
	updatePreviousNames();
	observePreviousNamesChanges();
	
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
	$("#jform_publications_without_isbn").chosen({
		disable_search_threshold: 10,
		width: "17em"
	});
	
	$("#jform_get_publisher_identifier").click(function(){
		// Get ISBN range id
		var isbn_range_id = $("#jform_isbn_range").chosen().val();
		// Get publisher id
		var publisher_id = $("#jform_id").val();
		// Get current URL
		var url = window.location.pathname;		
		// Get session ID
		var name = $("input[type='hidden'][value='1'][name!='jform[id]']").attr('name');
		// Set post parameterts
		var postData = {};
		// Session ID
		postData[name] = 1;
		// Component that's called
		postData['option'] = 'com_isbnregistry';
		postData['task'] = 'isbnrange.getRange';
		// Set publisher id
		postData['publisherId'] = publisher_id;	
		// Set isbn range id
		postData['rangeId'] = isbn_range_id;		
		// If publisher is not new and isbn range has been selected
		// call API that generates new publisher identifiers
		if(isbn_range_id.length > 0 && publisher_id.length > 0) {
			// Add request parameters
			$.post( url, postData)
			.done(function( data ) {
				if(data.success == true) {
					location.reload();
				} else {
					$('#system-message-container').html(showNotification('error', data.title, data.message));
				}
			})                        
			.fail(function(xhr, textStatus, errorThrown) {
				var json = jQuery.parseJSON(xhr.responseText);
				$('#system-message-container').html(showNotification('error', json.title, json.message));
			});
		}
	});

	$('div#publisherIsbnRanges').on('click', 'td.isbn_range_col_5_activate', function(){
		// Get publisher id
		var publisher_id = $("#jform_id").val();
		// Get publisher isbn range id
		var publisher_isbn_range_id = $(this).closest('tr').attr('id').replace('row-', '');
		// Get current URL
		var url = window.location.pathname;
		// Get session ID
		var name = $("input[type='hidden'][value='1'][name!='jform[id]']").attr('name');
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
					$('#system-message-container').html(showNotification('success', data.title, data.message));					
					loadPublisherIsbnRanges();				
				} else {
					$('#system-message-container').html(showNotification('error', data.title, data.message));
				}
			})                        
			.fail(function(xhr, textStatus, errorThrown) {
				var json = jQuery.parseJSON(xhr.responseText);
				$('#system-message-container').html(showNotification('error', json.title, json.message));
			});
		}		
	});
	
	function loadPublisherIsbnRanges() {
		// Get publisher id
		var publisher_id = $("#jform_id").val();
		// Get current URL
		var url = window.location.pathname;		
		// Get session ID
		var name = $("input[type='hidden'][value='1'][name!='jform[id]']").attr('name');
		// Set post parameterts
		var postData = {};
		// Session ID
		postData[name] = 1;
		// Component that's called
		postData['option'] = 'com_isbnregistry';
		postData['task'] = 'publisherisbnrange.getIsbnRanges';
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
					content += '<td class="isbn_range_col_2">' + data[i].created.split(' ')[0] + ' (' + data[i].created_by + ')</td>';
					content += '<td class="isbn_range_col_3">' + data[i].free + '</td>';
					content += '<td class="isbn_range_col_4">' + data[i].next + '</td>';
					content += '<td class="isbn_range_col_5';
					if(data[i].is_active == 1) {
						content += '">' + active;
						// If range hasn't been used yet, add delete icon
						if(data[i].range_begin == data[i].next) {
							content += ' <span class="icon-delete"></span>';
						}						
					} else if(data[i].is_closed == 1) {
						content += '">' + closed;
					} else {
						content += '_activate">' + activate;
					}
					content += '</td>';					
					content += '</tr>';
				}
				// Remove current content
				$('tr.isbn_range_active_row, tr.isbn_range_row').remove();
				// Add new content
				$(content).insertAfter('#isbn_ranges_header');
			})
			.fail(function(xhr, textStatus, errorThrown) {
				var json = jQuery.parseJSON(xhr.responseText);
				$('#system-message-container').append(showNotification('error', json.title, json.message));
			});
		}
	}

	function observePreviousNamesChanges() {
		// select the target node
		var target = document.querySelector('#jform_previous_names');
		// create an observer instance
		var observer = new MutationObserver(function(mutations) {
		  mutations.forEach(function(mutation) {
				updatePreviousNames();
		  });
		});
		// configuration of the observer:
		var config = { attributes: true };
		// pass in the target node, as well as the observer options
		observer.observe(target, config);
	}

	function updatePreviousNames() {
		// Get previous names value, which is a JSON string
		var previous_names = $('#jform_previous_names').val();
		// Parse JSON
		var json = jQuery.parseJSON(previous_names);
		// Check for null value
		if(json != null) {
			// Put all the names inside a div
			var content = '<div id="previous_names">' + json.name.toString().replace(/,/g, ", ") + '</div>';
			// Remove earlier values
			$('div#previous_names').remove();
			// Add new values
			$(content).insertBefore('#jform_previous_names_button');
			// Add some margin
			$('div#previous_names').css("margin-bottom", "1em");
			$('div#previous_names').css("margin-top", ".4em");
			// Set width to 17em
			$('div#previous_names').css("width", "17em");
		}
	}
	
	$('div#publisherIsbnRanges').on('click', 'span.icon-delete', function(){
		// Get publisher isbn range id
		var publisher_isbn_range_id = $(this).closest('tr').attr('id').replace('row-', '');
		// Get current URL
		var url = window.location.pathname;
		// Get session ID
		var name = $("input[type='hidden'][value='1'][name!='jform[id]']").attr('name');
		// Set post parameterts
		var postData = {};
		// Session ID
		postData[name] = 1;
		// Component that's called
		postData['option'] = 'com_isbnregistry';
		postData['task'] = 'publisherisbnrange.deleteIsbnRange';
		// Set publisher isbn range id
		postData['publisherIsbnRangeId'] = publisher_isbn_range_id;
		// Add request parameters
		$.post( url, postData )
		.done(function( data ) {
			// If operation was successfull, update view
			if(data.success == true) {
				$('#system-message-container').html(showNotification('success', data.title, data.message));					
				loadPublisherIsbnRanges();				
			} else {
				$('#system-message-container').html(showNotification('error', data.title, data.message));
			}
		})                        
		.fail(function(xhr, textStatus, errorThrown) {
			var json = jQuery.parseJSON(xhr.responseText);
			$('#system-message-container').html(showNotification('error', json.title, json.message));
		});	
	});	
	
	$("#jform_get_isbn_numbers").click(function(){
		// Get publisher id
		var publisher_id = $("#jform_id").val();
		// Get isbn count
		var isbn_count = $("#jform_isbn_count").val();		
		// Get current URL
		var url = window.location.pathname;		
		// Get session ID
		var name = $("input[type='hidden'][value='1'][name!='jform[id]']").attr('name');
		// Set post parameterts
		var postData = {};
		// Session ID
		postData[name] = 1;
		// Component that's called
		postData['option'] = 'com_isbnregistry';
		postData['task'] = 'publisherisbnrange.getIsbnNumbers';
		// Set publisher id
		postData['publisherId'] = publisher_id;		
		// Set isbn count
		postData['isbnCount'] = isbn_count;			
		// If publisher is not new, try to generate isbn numbers
		if(publisher_id.length > 0 && isbn_count > 0) {
			// Add request parameters
			$.post( url, postData)
			.done(function( data ) {
				if(data.success == true) {														
					var isbn_numbers = '';
					$.each(data.isbn_numbers, function(key, value) {
						isbn_numbers += value + '\n';
					});
					$("textarea#jform_created_isbn_numbers").html(isbn_numbers);
					$('#system-message-container').html(showNotification('success', data.title, data.message));
					loadPublisherIsbnRanges();
				} else {
					$('#system-message-container').html(showNotification('error', data.title, data.message));
				}
			})                        
			.fail(function(xhr, textStatus, errorThrown) {
				var json = jQuery.parseJSON(xhr.responseText);
				$('#system-message-container').html(showNotification('error', json.title, json.message));
			});
		}
	});	
	
	$("#jform_get_isbn_number").click(function(){
		// Get publisher id
		var publisher_id = $("#jform_id").val();
		// Get selected publication
		var publication_id = $('#jform_publications_without_isbn').val();
		// Get current URL
		var url = window.location.pathname;		
		// Get session ID
		var name = $("input[type='hidden'][value='1'][name!='jform[id]']").attr('name');
		// Set post parameterts
		var postData = {};
		// Session ID
		postData[name] = 1;
		// Component that's called
		postData['option'] = 'com_isbnregistry';
		postData['task'] = 'publisherisbnrange.getIsbnNumber';
		// Set publisher id
		postData['publisherId'] = publisher_id;		
		// Set publication id
		postData['publicationId'] = publication_id;			
		// If publisher is not new and publication is selected, try to get isbn number
		if(publisher_id.length > 0 && publication_id.length > 0) {
			// Add request parameters
			$.post( url, postData)
			.done(function( data ) {
				if(data.success == true) {	
					// Get selected label
					var label = jQuery('#jform_publications_without_isbn option:selected').text();
					$('#system-message-container').html(showNotification('success', data.title, data.message));
					loadPublisherIsbnRanges();
					loadPublicationsWithoutIdentifier();
					var link = '<a href="' + url + '?option=com_isbnregistry&view=publication&layout=edit&id=' + publication_id + '" target="new">';
					link += label + ' (' + data.publication_identifier + ')</a>';
                    $('#jform_link_to_publication').html(link);
				} else {
					$('#system-message-container').html(showNotification('error', data.title, data.message));
					$('#jform_link_to_publication').html('');
				}
			})                        
			.fail(function(xhr, textStatus, errorThrown) {
				var json = jQuery.parseJSON(xhr.responseText);
				$('#system-message-container').html(showNotification('error', json.title, json.message));
				$('#jform_link_to_publication').html('');
			});
		}			
	});
	
	function loadPublicationsWithoutIdentifier() {
		// Get publisher id
		var publisher_id = $("#jform_id").val();
		// Get current URL
		var url = window.location.pathname;		
		// Get session ID
		var name = $("input[type='hidden'][value='1'][name!='jform[id]']").attr('name');
		// Set post parameterts
		var postData = {};
		// Session ID
		postData[name] = 1;
		// Component that's called
		postData['option'] = 'com_isbnregistry';
		postData['task'] = 'publication.getPublicationsWithoutIdentifier';
		// Set publisher id
		postData['publisherId'] = publisher_id;		
		// Set type
		postData['type'] = 'isbn';
		// Load publications if publisher is not new
		if(publisher_id.length > 0) {
			// Add request parameters
			$.post( url, postData)
			.done(function( data ) {
				// Check that query was succesfull
				if(data.success == true) {
					// Remove all options except the first one
					$('#jform_publications_without_isbn').find('option:not(:first)').remove();
					// Go through the publications
					for (var i = 0; i < data.publications.length; i++) {
						// Add results to dropdown list
						$('#jform_publications_without_isbn').append($('<option>', {
							value: data.publications[i].id,
							text: data.publications[i].title
						}));
					}
					// Update Chosen jQuery plugin
					$('#jform_publications_without_isbn').trigger('liszt:updated');
				} else {
					$('#system-message-container').html(showNotification('error', data.title, data.message));
				}				
			})
			.fail(function(xhr, textStatus, errorThrown) {
				var json = jQuery.parseJSON(xhr.responseText);
				$('#system-message-container').append(showNotification('error', json.title, json.message));
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

function showNotification(type, title, message) {
		var html = '<button type="button" class="close" data-dismiss="alert">&times;</button>';
		html += '<div class="alert alert-' + type + '">';
		html += '<h4 class="alert-heading">' + title + '</h4>';
		html += '<p class="alert-message">' + message + '</p>';
		html += '</div>';
		return html;
}