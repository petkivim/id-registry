jQuery(document).ready(function ($) {
    // Get publisher id
    var publisher_id = $("#jform_id").val();
    // Get current URL
    var url = window.location.pathname;

    $("#jform_lang_code").chosen({
        disable_search_threshold: 10,
        width: "17em"
    });

    $("button[data-target='#modal-print']").click(function () {
        SqueezeBox.open(url + '?option=com_issnregistry&view=publisher&layout=print&tmpl=component&id=' + publisher_id, {handler: 'iframe', size: {x: 1200, y: 600}}
        );
    });

    $("button[data-target='#modal-generate-message']").click(function () {
        SqueezeBox.open(url + '?option=com_issnregistry&view=message&layout=send&tmpl=component&code=publisher_summary'
                + '&publisherId=' + publisher_id, {handler: 'iframe', size: {x: 1200, y: 850}}
        );
    });

    updateElement('jform_contact_person');
    observeElementChanges('jform_contact_person');

    function observeElementChanges(observableId) {
        // select the target node
        var target = document.querySelector('#' + observableId);
        // create an observer instance
        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                var elementName = mutation.target.id;
                updateElement(elementName);
            });
        });
        // configuration of the observer:
        var config = {attributes: true};
        // pass in the target node, as well as the observer options
        observer.observe(target, config);
    }

    function updateElement(elementName) {
        // Get value, which is a JSON string
        var elementValue = $('#' + elementName).val();
        // Get rid of jform prefix
        elementName = elementName.replace('jform_', '');
        // Parse JSON
        var json = jQuery.parseJSON(elementValue);
        // Check for null value
        if (json !== null) {
            var content = '<div id="' + elementName + '">';
            for (var i = 0; i < json.name.length; i++) {
                content += json.name[i];
                content += json.name[i].length > 0 && json.email[i].length > 0 ? ' ' : '';
                content += json.email[i].length > 0 ? '(' + json.email[i] + ')' : '';
                content += (i < json.name.length - 1 ? '<br />' : '');

            }
            // Put all the names inside a div
            content += '</div>';
            // Update values to UI
            updateContent(content, elementName);
        }
    }

    function updateContent(content, elementName) {
        // Remove earlier values
        $('div#' + elementName).remove();
        // Add new values
        $(content).insertBefore('#jform_' + elementName + '_button');
        // Add some margin
        $('div#' + elementName).css("margin-bottom", "1em");
        $('div#' + elementName).css("margin-top", ".4em");
        // Set width to 90%
        $('div#' + elementName).css("width", "90%");
    }
});
