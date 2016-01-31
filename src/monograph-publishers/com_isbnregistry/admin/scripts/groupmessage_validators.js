jQuery(document).ready(function () {	
    document.formvalidator.setHandler("isbncategories", function (value) {
        regex = /^.{0,20}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("ismncategories", function (value) {
        regex = /^.{0,20}$/;
        return regex.test(value);
    });	
});
