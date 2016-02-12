jQuery(document).ready(function () {
    document.formvalidator.setHandler("officialname", function (value) {
        regex = /^.{0,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("publisheridentifierstr", function (value) {
        regex = /^.{0,20}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("locality", function (value) {
        regex = /^.{0,50}$/;
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
    document.formvalidator.setHandler("contactperson", function (value) {
        regex = /^.{0,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("phone", function (value) {
        regex = /^(\+){0,1}[0-9 ()]{1,30}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("year", function (value) {
        regex = /^\d{4}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("publishingactivity", function (value) {
        regex = /^.{0,10}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("publishingactivityamount", function (value) {
        regex = /^.{0,5}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("publicationtype", function (value) {
        regex = /^.{0,15}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("publicationformat", function (value) {
        regex = /^.{0,20}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("firstname", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("lastname", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("role", function (value) {
        regex = /^.{0,40}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("firstnameopt", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("lastnameopt", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("roleopt", function (value) {
        regex = /^.{0,40}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("title", function (value) {
        regex = /^.{0,200}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("subtitle", function (value) {
        regex = /^.{0,200}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("mapscale", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("language", function (value) {
        regex = /^.{3}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("year", function (value) {
        regex = /^\d{4}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("month", function (value) {
        regex = /^\d{2}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("series", function (value) {
        regex = /^.{0,200}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("issn", function (value) {
        regex = /^\d{4}-\d{3}[\dxX]{1}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("volume", function (value) {
        regex = /^.{0,20}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("printinghouse", function (value) {
        regex = /^.{0,100}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("printinghousecity", function (value) {
        regex = /^.{0,50}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("copies", function (value) {
        regex = /^.{0,10}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("edition", function (value) {
        regex = /^.{0,2}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("type", function (value) {
        regex = /^.{0,35}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("comments", function (value) {
        regex = /^.{0,500}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("fileformat", function (value) {
        regex = /^.{0,25}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("fileformatother", function (value) {
        regex = /^.{0,100}$/;
        return regex.test(value);
    });
});
