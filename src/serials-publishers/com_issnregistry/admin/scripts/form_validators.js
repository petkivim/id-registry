jQuery(document).ready(function () {
    document.formvalidator.setHandler("publisher", function (value) {
        regex = /^.{1,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("contactperson", function (value) {
        regex = /^.{0,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("phone", function (value) {
        regex = /^(\+){0,1}[0-9 ()]{0,30}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("address", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("zip", function (value) {
        regex = /^\d{5}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("city", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("langcode", function (value) {
        regex = /^.{0,8}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("status", function (value) {
        regex = /^.{0,12}$/;
        return regex.test(value);
    });
});
