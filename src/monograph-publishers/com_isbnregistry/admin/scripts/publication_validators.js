jQuery(document).ready(function () {
    document.formvalidator.setHandler("officialname", function (value) {
        regex = /^.{1,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("publisher_id", function (value) {
        regex = /^.{0,20}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("address", function (value) {
        regex = /^.{1,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("zip", function (value) {
        regex = /^\d{5}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("city", function (value) {
        regex = /^.{1,50}$/;
        return regex.test(value);
    });
	document.formvalidator.setHandler("contactperson", function (value) {
        regex = /^.{1,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("phone", function (value) {
        regex = /^(\+){0,1}[0-9 ]{1,30}$/;
        return regex.test(value);
    });	
    document.formvalidator.setHandler("year", function (value) {
        regex = /^\d{4}$/;
        return regex.test(value);
    });
   /*
    document.formvalidator.setHandler("role", function (value) {
        regex = /^(\w+){1}(,\w+}){0,1}(,\w+){0,1}(,\w+}){0,1}$/;
        return regex.test(value);
    });*/
});
