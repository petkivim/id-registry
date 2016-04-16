jQuery(document).ready(function () {	
    document.formvalidator.setHandler("recipient", function (value) {
        regex = /^.{1,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("subject", function (value) {
        regex = /^.{1,150}$/;
        return regex.test(value);
    });	
    document.formvalidator.setHandler("message", function (value) {
        regex = /^.{1,5000}$/;
        return regex.test(value);
    });		
});
