function validateForm() {
    $("[datatype]").focusin(function() {
        if (this.timeout) {
            clearTimeout(this.timeout);
        }
        var infoObj = $(this).next(".tipinfo");
        if (infoObj.nextAll(".Validform_right").length != 0) {
            return;
        }
        infoObj.show().nextAll().hide();
    }).focusout(function() {
        var infoObj = $(this).next(".tipinfo");
        this.timeout = setTimeout(function() {
            infoObj.hide().nextAll(".Validform_wrong,.Validform_right,.Validform_loading").show();
        }, 0);
    });

    $(".validate-form").Validform({tiptype: 4, beforeSubmit: function(curform) {
            //显示 loadding
            var subBtn = $(curform).find("button[data-loading-text]");
            subBtn.attr("disabled", true);
            subBtn.html(subBtn.attr('data-loading-text'));
            return true;
        }});
}


Number.prototype.toNumber = function(decimal) {
    var s = this.toFixed(decimal);
    return parseFloat(s);
}

/**
 * 计算加上手续费的金额 
 * @param {type} number
 * @param {type} fee
 * @param {type} decimal
 * @param {type} type  定义这个值 返回结果加上了手续费 否则 返回结果是减去手续费的结果
 * @returns {Number}
 */
function calcFee(number, fee, decimal, type) {
    var val;

    number = parseFloat(number);


    if (typeof (type) == 'undefined')
        type = -1;
    else
        type = 1;


    if (!number)
        return 0;

    if (!fee)
        return number;


    if (fee.substr(-1) === '%') {
        val = number + type * number * parseFloat(fee.substr(0, fee.length - 1)) / 100;
    } else {
        val = number + type * parseFloat(fee);
    }
    return  Number(val).toNumber(decimal);
}

function onlyCalcFee(number, fee, decimal) {

    fee = fee.toString();

    var val;

    number = parseFloat(number);


    if (!number || !fee)
        return 0;


    if (fee.substr(-1) === '%') {
        val = number * parseFloat(fee.substr(0, fee.length - 1)) / 100;
    } else {
        val = parseFloat(fee);
    }

    return  Number(val).toNumber(decimal);
}





/**
 * 转换为浮点数  
 * @param {type} val
 * @param {type} decimal 小数位数
 * @returns {Number} 
 */
function toFloat(val, decimal) {
    decimal = parseInt(decimal, 10);
    val = val.toString();
    val = val.replace(/[^0-9\.]/g, "");

    if (!val)
        val = '0';

    return Number(parseFloat(val)).toNumber(decimal);

}

/**
 * 转换为数字字符串
 * @param {type} val
 * @param {type} decimal  小数位数
 * @returns {string}
 */
function toNumber(val, decimal) {
    val = val.toString();

    if (val.length > 1 && val.indexOf('0') == 0 && val.substr(0, 2) != "0.")
        val = val.substr(1);

    val = val.replace(/[^0-9\.]/g, "");
    decimal = parseInt(decimal, 10);
    var index = val.indexOf(".");

    if (index > -1 && val.substr(index + 1).length > decimal) {
        return val.substr(0, index + decimal + 1);
    } else {
        return val;
    }


}


function checkMobile(mobile) {
    return /^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/.test(mobile);
}

//disabled button 一段时间
function setBtnReadOnly(obj, time, oldVal) {
    time = parseInt(time, 10);
    obj.attr("disabled", true);
    var intID = setInterval(function() {
        var t = time--;
        if (t === 0) {
            obj.removeAttr("disabled");
            obj.html(oldVal);
            clearInterval(intID)
        } else {
            obj.html("再次发送剩余" + t + "秒");
        }
    }, 1000);
}