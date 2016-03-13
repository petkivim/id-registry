jQuery(document).ready(function ($) {
    $("#jform_send").click(function () {
        Joomla.submitbutton('message.send');
    });
    $("#jform_close").click(function () {
        var iframe = window.parent.document.getElementById('messages_iframe');
        if (iframe == null) {
            iframe = window.parent.parent.document.getElementById('messages_iframe');
            window.parent.location.reload();
        }
        iframe.src = iframe.src;
        window.parent.SqueezeBox.close();
    });
});
