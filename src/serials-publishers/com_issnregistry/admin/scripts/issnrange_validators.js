jQuery(document).ready(function () {
    document.formvalidator.setHandler("block", function (value) {
        regex = /^\d{4}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("rangebegin", function (value) {
        regex = /^\d{3}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("rangebeginedit", function (value) {
        regex = /^\d{3}[\dxX]$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("rangeend", function (value) {
        regex = /^\d{3}$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("rangeendedit", function (value) {
        regex = /^\d{3}[\dxX]$/;
        return regex.test(value);
    });
});
