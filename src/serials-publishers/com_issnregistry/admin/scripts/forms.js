jQuery(document).ready(function ($) {
    // Get current URL
    var url = window.location.pathname;

    $("button[data-target='#modal-statistics']").click(function () {
        SqueezeBox.open(url + '?option=com_issnregistry&view=statistic&layout=popup&tmpl=component', {handler: 'iframe', size: {x: 800, y: 600}}
        );
    });
});