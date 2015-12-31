jQuery(document).ready(function () {
    document.formvalidator.setHandler("officialname", function (value) {
        regex = /^.{1,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("othernames", function (value) {
        regex = /^.{0,200}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("previousnames", function (value) {
        regex = /^.{0,300}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("contactperson", function (value) {
        regex = /^.{0,100}$/;
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
    document.formvalidator.setHandler("phone", function (value) {
        regex = /^(\+){0,1}[0-9 ]{0,30}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("www", function (value) {
        regex = /^http(s)?:\/\/(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("question1", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("question2", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("question3", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("question4", function (value) {
        regex = /^.{0,200}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("question5", function (value) {
        regex = /^.{0,200}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("question6", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("question7", function (value) {
        regex = /^(\d{3}){0,1}(,\d{3}){0,1}(,\d{3}){0,1}(,\d{3}){0,1}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("question8", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("confirmation", function (value) {
        regex = /^.{0,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("additionalinfo", function (value) {
        regex = /^.{0,500}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("yearquitted", function (value) {
        regex = /^\d{4}$/;
        return regex.test(value);
    });
});
