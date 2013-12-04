/**
 * @author: 五马石 <abke@qq.com> 
 * Time: 2013-7-4
 * Description: 
 */

var WUMASHI = {};

 
WUMASHI.var_dump = function(obj) { //显示属性和值
        var str = "";
        var o;
        for (var i in obj) {

            o = obj[i]
            if (typeof(o) == 'object') {
                str += this.var_dump(o);
            } else {
                str += (i + ": " + o) + "\r\n";
            }

        }
        return str;
    },
WUMASHI.test = function(obj) {
        alert(this.var_dump(obj));
    }
}
