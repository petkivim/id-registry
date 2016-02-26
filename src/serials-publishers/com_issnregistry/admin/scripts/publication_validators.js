jQuery(document).ready(function () {
    document.formvalidator.setHandler("title", function (value) {
        regex = /^.{1,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("issn", function (value) {
        regex = /^\d{4}-\d{3}[\dxX]{1}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("placeofpublication", function (value) {
        regex = /^.{0,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("printer", function (value) {
        regex = /^.{0,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("issuedfromyear", function (value) {
        regex = /^\d{4}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("issuedfromnumber", function (value) {
        regex = /^.{0,20}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("frequency", function (value) {
        regex = /^.{0,1}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("language", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("publicationtype", function (value) {
        regex = /^.{0,25}$/;
        return regex.test(value);
    });

    document.formvalidator.setHandler("publicationtypeother", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("medium", function (value) {
        regex = /^.{0,7}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("mediumother", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("previoustitle", function (value) {
        regex = /^.{0,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("previousissn", function (value) {
        regex = /^\d{4}-\d{3}[\dxX]{1}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("previoustitlelastissue", function (value) {
        regex = /^.{0,20}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("mainseriestitle", function (value) {
        regex = /^.{0,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("mainseriesissn", function (value) {
        regex = /^\d{4}-\d{3}[\dxX]{1}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("subseriestitle", function (value) {
        regex = /^.{0,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("subseriesissn", function (value) {
        regex = /^\d{4}-\d{3}[\dxX]{1}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("anothermediumtitle", function (value) {
        regex = /^.{0,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("anothermediumissn", function (value) {
        regex = /^\d{4}-\d{3}[\dxX]{1}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("additionalinfo", function (value) {
        regex = /^.{0,500}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("www", function (value) {
        regex = /^http(s)?:\/\/(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;
        return regex.test(value);
    });
});