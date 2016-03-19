jQuery(document).ready(function ($) {
    // Get current URL
    var url = window.location.pathname;

    $("button[data-target='#modal-statistics']").click(function () {
        SqueezeBox.open(url + '?option=com_isbnregistry&view=statistic&layout=popup&tmpl=component', {handler: 'iframe', size: {x: 1200, y: 600}}
        );
    });
});