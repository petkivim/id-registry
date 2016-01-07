jQuery(document).ready(function ($) {
    $("#jform_send").click(function () {
        Joomla.submitbutton('message.send');
    });
    $("#jform_close").click(function () {
        window.parent.SqueezeBox.close();
    });
});
