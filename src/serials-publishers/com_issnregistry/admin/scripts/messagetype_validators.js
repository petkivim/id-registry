jQuery(document).ready(function () {	
    document.formvalidator.setHandler("name", function (value) {
        regex = /^.{1,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("description", function (value) {
        regex = /^.{0,200}$/;
        return regex.test(value);
    });	
});
