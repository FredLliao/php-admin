String.prototype.replaceAll = function (oldStr,newStr){
    var index = 0;
    var str = new String(this);
    if (newStr==undefined || newStr==null) newStr="";
    while(index >=0 ){
        str = str.replace(oldStr, newStr);
        index = str.indexOf(oldStr);
    }
    return str;
};
Date.prototype.format = function(format) {
    var date = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S+": this.getMilliseconds()
    };
    if (/(y+)/i.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    for (var k in date) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1
                ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
        }
    }
    return format;
}


var userAgent = navigator.userAgent.toLowerCase();
var commonUtil = {
        browser: {
            version: (userAgent.match(/.+(?:rv|it|ra|ie)[\/: ]([\d.]+)/) || [])[1],
            firefox: /firefox/.test(userAgent),
            safari: /mac.*version\/([\d.]+).*safari/.test(userAgent),
            opera: /opera/.test(userAgent),
            msie: /msie/.test(userAgent) && !/opera/.test(userAgent),
            mozilla: /mozilla/.test(userAgent) && !/(compatible|webkit)/.test(userAgent),
            chrome: /chrome/.test(userAgent),
            iphone: /iphone/.test(userAgent),
            ipod: /ipod/.test(userAgent),
            ipad: /ipad/.test(userAgent),
            apple: /iphone/.test(userAgent) || /ipod/.test(userAgent) || /ipad/.test(userAgent),
            android: /android/.test(userAgent),
            micromessenger: /micromessenger/.test(userAgent),
            medtree: /medtree/.test(userAgent),
            accessToken: function() {
                var medAgent= (userAgent.match(/medtree \((.*)\)/) || [])[0];
                if(!medAgent || medAgent == "") return "";
                var keys = medAgent.split(";");
                return trim(keys[2]);
            }
        },
        compareObject: function (objA, objB) {
            if (typeof arguments[0] != typeof arguments[1])
                return false;
            //数组
            if (arguments[0] instanceof Array) {
                if (arguments[0].length != arguments[1].length)
                    return false;

                var allElementsEqual = true;
                for (var i = 0; i < arguments[0].length; ++i) {
                    if (typeof arguments[0][i] != typeof arguments[1][i])
                        return false;

                    if (typeof arguments[0][i] == 'number' && typeof arguments[1][i] == 'number')
                        allElementsEqual = (arguments[0][i] == arguments[1][i]);
                    else
                        allElementsEqual = arguments.callee(arguments[0][i], arguments[1][i]);            //递归判断对象是否相等
                }
                return allElementsEqual;
            }

            //对象
            if (arguments[0] instanceof Object && arguments[1] instanceof Object) {
                var result = true;
                var attributeLengthA = 0, attributeLengthB = 0;
                for (var o in arguments[0]) {
                    //判断两个对象的同名属性是否相同（数字或字符串）
                    if (typeof arguments[0][o] == 'number' || typeof arguments[0][o] == 'string')
                        result = result && eval("arguments[0]['" + o + "'] == arguments[1]['" + o + "']");
                    else {
                        //如果对象的属性也是对象，则递归判断两个对象的同名属性
                        //if (!arguments.callee(arguments[0][o], arguments[1][o]))
                        if (!arguments.callee(eval("arguments[0]['" + o + "']"), eval("arguments[1]['" + o + "']"))) {
                            result = false;
                            return result;
                        }
                    }
                    ++attributeLengthA;
                }

                for (var o in arguments[1]) {
                    ++attributeLengthB;
                }

                //如果两个对象的属性数目不等，则两个对象也不等
                if (attributeLengthA != attributeLengthB)
                    result = false;
                return result;
            }
            return arguments[0] == arguments[1];
        },
        json: {
            getItemString: function (key, value) {
                return "\"" + key + "\":\"" + value + "\"";
            }
        },
        // 判断是否是有空字符串
        isEmptyString: function (str) {
            if (!str) return true;
            if (str == null) return true;
            if (str == undefined) return true;
            if (str.length == 0) return true;
            if (str.replace(/(^\s*)|(\s*$)/g, "") == "") return true;
            //if (str.trim() == "") return true;
            if (str == "null") return true;
//        if (typeof str != "string") return true;
            return false;
        },
        // 判断字符长度
        stringLengthValidate: function (str,len) {
            if(str.length>len) return str.substr(0,len)+"...";
            return str;
        },
        clearContent: function (elemID) {
            $("#"+elemID).find("input[type=text], select").each(function(){
                var $this = $(this), $parent;
                if ($this.hasClass("req-param-input") || $this.hasClass("req-param")){
                    $parent = $this.hasClass("req-param") ? $this : $this.parents("req-param");
                    $this.val("").removeClass("param-selected");
                    $parent.removeData("req-value").find(".param-selected").removeClass("param-selected");
                }
            });
        }
    },
    responseUtil = {
        parseJsonObject : function(res){
            if (typeof res != 'object') {
                try{ res = JSON.parse(res); } catch (e){ res = null; }
            }
            return res;
        },
        checkJsonDataStatus : function(obj){
            var status = this.states.ok;
            if (obj==null || typeof obj != 'object') status = -1;
            else if (obj["stat"]!=this.states.ok) status = -2;
            return status;
        },
        states: { ok:1 }
    },
    dateUtil={
        parseUnixTimestamp: function(timestamp, format){
            if (!format) format = "yyyy-MM-dd";
            return new Date(timestamp*1000).format(format);
        },
        //增加月
        addMonths: function(date, value) {
            date.setMonth(date.getMonth() + value);
            return date;
        },
        //增加周
        addWeeks: function(date, value) {
            date.setDate(date.getDate() + (value*7));
            return date;
        },
        //增加天
        addDays: function(date, value) {
            date.setDate(date.getDate() + value);
            return date;
        },
        //增加时
        addHours: function(date, value) {
            date.setHours(date.getHours() + value);
            return date;
        },
        //获取短日期格式
        getShortDate: function(date) {
            return date.format("yyyy-MM-dd");
        },
        //获取长日期格式
        getLongDate: function(date) {
            return date.format("yyyy-MM-dd hh:mm:ss");
        },
        /**
         * 判断字符串str的日期格式为yyyy-MM-dd和正确的日期
         * @param str
         * @returns {boolean}
         */
        isDate: function(str) {
            if (str=="") return false;
            var reg = /^(\d{4})-(\d{2})-(\d{2})$/;
            var arr = reg.exec(str);
            if (reg.test(str)&&RegExp.$2<=12&&RegExp.$3<=31){
                return true;
            }
            return false;
        },
        /**
         * 判断字符串str的日期格式为yyyy-MM-dd HH:mm:ss和正确的日期
         * @param str
         * @returns {boolean}
         */
        isDateTime: function(str) {
            if (str=="") return false;
            var reg = /^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/;
            var r = str.match(reg);
            if(r==null)return false;
            r[2]=r[2]-1;
            var d= new Date(r[1], r[2],r[3], r[4],r[5], r[6]);
            if(d.getFullYear()!=r[1])return false;
            if(d.getMonth()!=r[2])return false;
            if(d.getDate()!=r[3])return false;
            if(d.getHours()!=r[4])return false;
            if(d.getMinutes()!=r[5])return false;
            if(d.getSeconds()!=r[6])return false;
            return true;
        }
    },
    Validator = {
        Require : /.+/,
        SequenceNumber:/^\d{4}$/,
        Username:/^\w+$/,
        Email : /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
        Phone : /^((\(\d{3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}$/,
        Mobile : /^((\(\d{3}\))|(\d{3}\-))?1[3|4|5|7|8]\d{9}$/,
        Tel:/^((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)$/,
        Url : /^http(s)?:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/,
//        IdCard : /^\d{15}(\d{2}[A-Za-z0-9])?$/,
        PWD:/^(?=.*\d.*)(?=.*[a-zA-Z].*).{6,16}$/,//密码规则要求：(6位以上至少包含1位字母和1位数字)
        Currency : /^\d+(\.\d+)?$/,
        Number : /^\d+$/,
        Zip : /^[1-9]\d{5}$/,
        QQ : /^[1-9]\d{4,8}$/,
        Integer : /^[-\+]?\d+$/,
        Double : /^[-\+]?\d+(\.\d+)?$/,
        English : /^[A-Za-z]+$/,
        Chinese :  /^[\u0391-\uFFE5]+$/,
        UnSafe : /^(([A-Z]*|[a-z]*|\d*|[-_\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\/\'\"]*)|.{0,5})$|\s/,
        IsSafe : function(str){return !this.UnSafe.test(str);},
        IdCard : "IdCardValidate(value)",
        SafeString : "this.IsSafe(value)",
        Limit : "this.limit(value.length,getAttribute('min'),  getAttribute('max'))",
        LimitB : "this.limit(this.LenB(value), getAttribute('min'), getAttribute('max'))",
        Date : "this.IsDate(value, getAttribute('min'), getAttribute('format'))",
        Repeat : "value == document.getElementById(getAttribute('to')).value",
        Different : "value != document.getElementById(getAttribute('to')).value",
        Range : "(!isNaN(value)) && (Number(getAttribute('min'))<=Number(value)) && (Number(value)<=Number(getAttribute('max')))",
        EmptyOrRange : "(!value) || ((!isNaN(value)) && (Number(getAttribute('min'))<=Number(value)) && (Number(value)<=Number(getAttribute('max'))))",
        Compare : "this.compare(value,getAttribute('operator'),getAttribute('to'))",
        Custom : "this.Exec(value, getAttribute('regexp'))",
        Group : "this.MustChecked(getAttribute('name'), getAttribute('min'), getAttribute('max'))",
        ErrorItem : [document.forms[0]],
        ErrorMessage : ["以下原因导致提交失败：\t\t\t\t"],
        Validate : function(theForm, mode, depth){
            var obj = theForm || event.srcElement;
            var count = obj.elements.length;
            this.ErrorMessage.length = 1;
            this.ErrorItem.length = 1;
            this.ErrorItem[0] = obj;
            for(var i=0;i<count;i++){
                with(obj.elements[i]){
                    var _dataType = getAttribute("dataType");
                    if(typeof(_dataType) == "object" || typeof(this[_dataType]) == "undefined")  continue;
                    this.ClearState(obj.elements[i]);
                    if(getAttribute("require") == "false" && value == "") continue;
                    switch(_dataType){
                        case "IdCard" :
                        case "Date" :
                        case "Repeat" :
                        case "Different" :
                        case "Range" :
                        case "EmptyOrRange" :
                        case "Compare" :
                        case "Custom" :
                        case "Group" :
                        case "Limit" :
                        case "LimitB" :
                        case "SafeString" :
                            if(!eval(this[_dataType])) {
                                this.AddError(i, getAttribute("msg"));
                            }
                            break;
                        default :
                            if(!this[_dataType].test(value)){
                                this.AddError(i, getAttribute("msg"));
                            }
                            break;
                    }
                }
            }
            if(this.ErrorMessage.length > 1){
                mode = mode || 1;
                var errCount = this.ErrorItem.length;
                switch(mode){
                    case 2 :
                        for(var i=1;i<errCount;i++)
                            this.ErrorItem[i].style.color = "red";
                    case 1 :
                        alert(this.ErrorMessage.join("\n"));
                        this.ErrorItem[1].focus();
                        break;
                    case 3 :
                        for(var i=1;i<errCount;i++){
                            try{
                                var span = document.createElement("SPAN");
                                span.id = "__ErrorMessagePanel";
                                span.style.color = "red";
                                this.ErrorItem[i].parentNode.appendChild(span);
                                span.innerHTML = this.ErrorMessage[i].replace(/\d+:/,"*");
                            }
                            catch(e){alert(e.description);}
                        }
                        this.ErrorItem[1].focus();
                        break;
                    default :
                        alert(this.ErrorMessage.join("\n"));
                        break;
                }
                return false;
            }
            return true;
        },
        limit : function(len,min, max){
            min = min || 0;
            max = max || Number.MAX_VALUE;
            return min <= len && len <= max;
        },
        LenB : function(str){
            return str.replace(/[^\x00-\xff]/g,"**").length;
        },
        ClearState : function(elem){
            with(elem){
                if(style.color == "red")
                    style.color = "";
                var lastNode = parentNode.childNodes[parentNode.childNodes.length-1];
                if(lastNode.id == "__ErrorMessagePanel")
                    parentNode.removeChild(lastNode);
            }
        },
        AddError : function(index, str){
            this.ErrorItem[this.ErrorItem.length] = this.ErrorItem[0].elements[index];
            this.ErrorMessage[this.ErrorMessage.length] = this.ErrorMessage.length + ":" + str;
        },
        Exec : function(op, reg){
            return new RegExp(reg,"g").test(op);
        },
        compare : function(op1,operator,op2){
            switch (operator) {
                case "NotEqual":
                    return (op1 != op2);
                case "GreaterThan":
                    return (op1 > op2);
                case "GreaterThanEqual":
                    return (op1 >= op2);
                case "LessThan":
                    return (op1 < op2);
                case "LessThanEqual":
                    return (op1 <= op2);
                default:
                    return (op1 == op2);
            }
        },
        MustChecked : function(name, min, max){
            var groups = document.getElementsByName(name);
            var hasChecked = 0;
            min = min || 1;
            max = max || groups.length;
            for(var i=groups.length-1;i>=0;i--)
                if(groups[i].checked) hasChecked++;
            return min <= hasChecked && hasChecked <= max;
        },
        IsDate : function(op, formatString){
            formatString = formatString || "ymd";
            var m, year, month, day;
            switch(formatString){
                case "yyyymmdd":
                    m = op.match(new RegExp("^((\\d{4}))(\\d{1,2})(\\d{1,2})$"));
                    if(m == null ) return false;
                    day = m[4];
                    month = m[3]--;
                    year =  (m[2].length == 4) ? m[2] : GetFullYear(parseInt(m[3], 10));
                    break;
                case "ymd" :
                    m = op.match(new RegExp("^((\\d{4})|(\\d{2}))([-./])(\\d{1,2})\\4(\\d{1,2})$"));
                    if(m == null ) return false;
                    day = m[6];
                    month = m[5]--;
                    year =  (m[2].length == 4) ? m[2] : GetFullYear(parseInt(m[3], 10));
                    break;
                case "dmy" :
                    m = op.match(new RegExp("^(\\d{1,2})([-./])(\\d{1,2})\\2((\\d{4})|(\\d{2}))$"));
                    if(m == null ) return false;
                    day = m[1];
                    month = m[3]--;
                    year = (m[5].length == 4) ? m[5] : GetFullYear(parseInt(m[6], 10));
                    break;
                default :
                    break;
            }
            if(!parseInt(month)) return false;
            month = month==12 ?0:month;
            var date = new Date(year, month, day);
            return (typeof(date) == "object" && year == date.getFullYear() && month == date.getMonth() && day == date.getDate());
            function GetFullYear(y){return ((y<30 ? "20" : "19") + y)|0;}
        }
    };

var Wi = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1 ];// 加权因子
var ValidateCode = [ 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ];// 身份证验证位值.10代表X
function IdCardValidate(idCard) {
    idCard = trim(idCard.replace(/ /g, ""));
    if (idCard.length == 15) {
        return isValidityBrithBy15IdCard(idCard);
    } else if (idCard.length == 18) {
        var a_idCard = idCard.split("");// 得到身份证数组
        if(isValidityBrithBy18IdCard(idCard)&&isTrueValidateCodeBy18IdCard(a_idCard)){
            return true;
        }else {
            return false;
        }
    } else {
        return false;
    }
}
/**
 * 判断身份证号码为18位时最后的验证位是否正确
 * @param a_idCard 身份证号码数组
 * @return
 */
function isTrueValidateCodeBy18IdCard(a_idCard) {
    var sum = 0; // 声明加权求和变量
    if (a_idCard[17].toLowerCase() == 'x') {
        a_idCard[17] = 10;// 将最后位为x的验证码替换为10方便后续操作
    }
    for ( var i = 0; i < 17; i++) {
        sum += Wi[i] * a_idCard[i];// 加权求和
    }
    var valCodePosition = sum % 11;// 得到验证码所位置
    if (a_idCard[17] == ValidateCode[valCodePosition]) {
        return true;
    } else {
        return false;
    }
}
/**
 * 通过身份证判断是男是女
 * @param idCard 15/18位身份证号码
 * @return 'female'-女、'male'-男
 */
function maleOrFemaleByIdCard(idCard){
    idCard = trim(idCard.replace(/ /g, ""));// 对身份证号码做处理。包括字符间有空格。
    if(idCard.length==15){
        if(idCard.substring(14,15)%2==0){
            return 'female';
        }else{
            return 'male';
        }
    }else if(idCard.length ==18){
        if(idCard.substring(14,17)%2==0){
            return 'female';
        }else{
            return 'male';
        }
    }else{
        return null;
    }
}
/**
 * 验证18位数身份证号码中的生日是否是有效生日
 * @param idCard 18位书身份证字符串
 * @return
 */
function isValidityBrithBy18IdCard(idCard18){
    var year =  idCard18.substring(6,10);
    var month = idCard18.substring(10,12);
    var day = idCard18.substring(12,14);
    var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));
    // 这里用getFullYear()获取年份，避免千年虫问题
    if(temp_date.getFullYear()!=parseFloat(year)
        ||temp_date.getMonth()!=parseFloat(month)-1
        ||temp_date.getDate()!=parseFloat(day)){
        return false;
    }else{
        return true;
    }
}
/**
 * 验证15位数身份证号码中的生日是否是有效生日
 * @param idCard15 15位书身份证字符串
 * @return
 */
function isValidityBrithBy15IdCard(idCard15){
    var year =  idCard15.substring(6,8);
    var month = idCard15.substring(8,10);
    var day = idCard15.substring(10,12);
    var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));
    // 对于老身份证中的年龄则不需考虑千年虫问题而使用getYear()方法
    if(temp_date.getYear()!=parseFloat(year)
        ||temp_date.getMonth()!=parseFloat(month)-1
        ||temp_date.getDate()!=parseFloat(day)){
        return false;
    }else{
        return true;
    }
}

//去掉字符串头尾空格
function trim(str) {
    return str.replace(/(^\s*)|(\s*$)/g, "");
}

/**
 * 验证军官证号是否正确
 * @param {Object} value
 * @return true 正确，false 错误
 */
function isCheckAICard(value){
    var re= /^[0-9]{8}$/;
    return re.test(value) && !quanjiao(value)
}
/**
 * 检查是否为全角
 * @param {Object} str
 * @return {TypeName}
 */
function quanjiao(str){
    if (str.length>0){
        for (var i = str.length-1; i >= 0; i--){
            unicode=str.charCodeAt(i);
            if (unicode>65280 && unicode<65375){
                //alert("不能输入全角字符，请输入半角字符");
                return true;
            }
        }
    }
}

//浏览记录 ,依赖于jquery.cookie.js
function writeCookie(name, data) {
    var value = $.cookie(name) ? $.cookie(name) : '[]';
    var jsonstr = "{type: '"+ data.type +"', id: '"+ data.id +"', name: '"+ data.name +"'},";
    if(value.indexOf(jsonstr) != -1){
        value = value.replace(jsonstr, "");
    }
    value = value.replace("[", "["+ jsonstr);
    json = eval(value);
    if(json.length > 10){
        value = "[";
        $.each(json, function (index, domEle) {
            if(index < 10){
                value += "{type: '"+ domEle.type +"', id: '"+ domEle.id +"', name: '"+ domEle.name +"'},";
            }
        });
        value += "]";
    }
    $.cookie(name, value, {path: '/'});
}

//漂亮的弹出div提示 ,依赖于jquery.gritter.css和jquery.gritter.js
/**
 * Use JQuery.gritter to popup success message
 *
 * @param message 待显示的消息
 * arguments[1] available：gritter|gritter-blue|gritter-light|gritter-brown|gritter-long|gritter-purple
 * arguments[2] 超时时间，超过后自动消失
 */
function alert_success(message) {
    $.gritter.add({
        title: 'Success!',
        text: message,
        image: myDomain.baseUrl+'assets/img/icon/checkmark_green.png',//是否显示图标，可选
        class_name: arguments[1] ? arguments[1] : 'gritter-blue',
        time: arguments[2] ? arguments[2] : 3000
    });
}

/**
 * Use JQuery.gritter to popup error message
 *
 * @param message 待显示的消息
 * arguments[1] available：gritter|gritter-blue|gritter-light|gritter-brown|gritter-long|gritter-purple
 * arguments[2] 超时时间，超过后自动消失
 */
function alert_error(message) {
    $.gritter.add({
        title: 'Error!',
        text: message,
        image: myDomain.baseUrl+'assets/img/icon/cross_green.png',//是否显示图标，可选
        class_name: arguments[1] ? arguments[1] : 'gritter-brown',
        time: arguments[2] ? arguments[2] : 4000
    });
}

function isArray(obj) {
    return Object.prototype.toString.call(obj) === '[object Array]';
}


/**
 * 将时间戳转换为普通日期格式:yyyy-MM-dd HH:mm:ss
 * @param timestamp 时间戳
 * @returns {string}
 */
function getLocalTime(timestamp) {
    var time_num = parseInt(timestamp);     //传回来的是个字符串
    var d = new Date(time_num*1000);       //这个很重要，要*1000
    return formatDate(d);
}

/**
 * 将date类型转换为普通日期格式:yyyy-MM-dd HH:mm:ss
 * @param now   new Date()
 * @returns {string}
 */
function   formatDate(now)   {
    var   year=now.getFullYear();
    var   month=now.getMonth()+1;
    var   date=now.getDate();
    var   hour=now.getHours();
    var   minute=now.getMinutes();
    var   second=now.getSeconds();
    return   year+"-"+month+"-"+date+"   "+hour+":"+minute+":"+second;
}


/**
 * 用js计算时间差，得到比较人性化的结果a发表，如：20分钟前、1个小时前、 两天前等等
 * @param dateTimeStamp 时间戳
 * @returns {string|*}
 */
function getDateDiff(dateTimeStamp){
    var minute = 1000 * 60;
    var hour = minute * 60;
    var day = hour * 24;
    var halfamonth = day * 15;
    var month = day * 30;
    var year = month * 12;

    var now = new Date().getTime();
    var diffValue = now - dateTimeStamp;

    if(diffValue < 0){
        //非法操作
        //alert("结束日期不能小于开始日期！");
    }

    var yearC =diffValue/(12*month);
    var monthC =diffValue/month;
    var weekC =diffValue/(7*day);
    var dayC =diffValue/day;
    var hourC =diffValue/hour;
    var minC =diffValue/minute;

    if(yearC>=1){
        result=parseInt(monthC) + "年前";
    }
    else if(monthC>=1){
        result=parseInt(monthC) + "个月前";
    }
    else if(weekC>=1){
        result=parseInt(weekC) + "周前";
    }
    else if(dayC>=1){
        result=parseInt(dayC) +"天前";
    }
    else if(hourC>=1){
        result=parseInt(hourC) +"小时前";
    }
    else if(minC>=1){
        result=parseInt(minC) +"分钟前";
    }else
        result="刚刚发表";
    return result;
}

/**
 * 如果你得到的原始数据不是时间戳，可以采用下面的函数把字符串转换为标准时间戳, 它相当于JS版的strtotime，只不过精度不同罢了：
 * @param dateStr 时间字符串,如：2014-09-23 19:11:06
 * @returns {string|*}
 */
function getDateDiffByStr(dateStr){
    return getDateDiff(Date.parse(dateStr.replace(/-/gi,"/")));
}


//判断网页是否在医树app内部打开，内部打开userAgent中包含有medtree关键词，返回tree；
// 如果是外部浏览器打开则没有medtree关键词，返回false；
function isInApp(){
    return commonUtil.browser.medtree;
}

function checkIsInWeiXin(){
    if(commonUtil.browser.micromessenger){
        return true;
    }
    return false;
}

function checkIsInApple(){
    if(commonUtil.browser.apple){
        return true;
    }
    return false;
}


//图片滚动 调用方法 imgscroll({speed: 30,amount: 1,dir: "up"});
$.fn.imgscroll = function(o){
    var defaults = {
        speed: 40,
        amount: 0,
        width: 1,
        dir: "left"
    };
    o = $.extend(defaults, o);

    return this.each(function(){
        var _li = $("li", this);
        _li.parent().parent().css({overflow: "hidden", position: "relative"}); //div
        _li.parent().css({margin: "0", padding: "0", overflow: "hidden", position: "relative", "list-style": "none"}); //ul
        _li.css({position: "relative", overflow: "hidden"}); //li
        if(o.dir == "left") _li.css({"float":"left"});

        //初始大小
        var _li_size = 0;
        for(var i=0; i<_li.size(); i++)
            _li_size += o.dir == "left" ? _li.eq(i).outerWidth(true) : _li.eq(i).outerHeight(true);

        //循环所需要的元素
        if(o.dir == "left") _li.parent().css({width: (_li_size*3)+"px"});
        _li.parent().empty().append(_li.clone()).append(_li.clone()).append(_li.clone());
        _li = $("li", this);

        //滚动
        var _li_scroll = 0;
        function scrollTo() {
            _li_scroll += o.width;
            if(_li_scroll > _li_size)
            {
                _li_scroll = 0;
                _li.parent().css(o.dir == "left" ? { left : -_li_scroll } : { top : -_li_scroll });
                _li_scroll += o.width;
            }
            _li.parent().animate(o.dir == "left" ? { left : -_li_scroll } : { top : -_li_scroll }, o.amount);
        }

        //开始
        var move = setInterval(function(){ scrollTo(); }, o.speed);
        _li.parent().hover(
            function(){ clearInterval(move);},
            function(){ clearInterval(move);move = setInterval(function(){ scrollTo(); }, o.speed);}
        );
    });
}





