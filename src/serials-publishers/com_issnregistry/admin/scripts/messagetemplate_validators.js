jQuery(document).ready(function () {	
    document.formvalidator.setHandler("name", function (value) {
        regex = /^.{1,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("subject", function (value) {
        regex = /^.{1,50}$/;
        return regex.test(value);
    });	
    document.formvalidator.setHandler("message", function (value) {
        regex = /^.{1,5000}$/;
        return regex.test(value);
    });		
});
