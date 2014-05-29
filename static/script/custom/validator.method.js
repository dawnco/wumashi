
$.validator.addMethod("mobile", function(val, element, param){
    return this.optional(element) || /1\d{10,10}/.test(val);
}, "手机号码不正确");
