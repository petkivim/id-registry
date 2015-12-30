jQuery(document).ready(function () {
    document.formvalidator.setHandler("prefix", function (value) {
        regex = /^(979-0|M)$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("rangebegin", function (value) {
        regex = /^\d{3,7}$/;
        return regex.test(value);
    });	
    document.formvalidator.setHandler("rangeend", function (value) {
        regex = /^\d{3,7}$/;
        return regex.test(value);
    });		
});
