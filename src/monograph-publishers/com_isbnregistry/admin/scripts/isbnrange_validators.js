jQuery(document).ready(function () {
    document.formvalidator.setHandler("prefix", function (value) {
        regex = /^(978|979|)$/;
        return regex.test(value);
    });
    document.formvalidator.setHandler("langgroup", function (value) {
        regex = /^(951|952)$/;
        return regex.test(value);
    });	
    document.formvalidator.setHandler("rangebegin", function (value) {
        regex = /^\d{1,5}$/;
        return regex.test(value);
    });	
    document.formvalidator.setHandler("rangeend", function (value) {
        regex = /^\d{1,5}$/;
        return regex.test(value);
    });		
});
