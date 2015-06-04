Ext.require('Ext.window.MessageBox', function (){
    Ext.override(Ext.window.MessageBox, {
        info: function (msg, fn, scope) {
            var cfg = {
                title: '提示',
                msg: msg,
                buttons: this.OK,
                fn: fn,
                scope: scope,
                width: 450,
                icon: Ext.Msg.INFO
            };
            return this.show(cfg);
        },
        warning: function (msg, fn, scope) {
            var cfg = {
                title: '警告',
                msg: msg,
                buttons: this.YESNO,
                fn: fn,
                scope: scope,
                width: 450,
                icon: Ext.Msg.QUESTION
            };
            return this.show(cfg);
        },
        error: function (msg, fn, scope) {
            var cfg;
            if (typeof msg == 'string') {
                cfg = {
                    title: '错误',
                    msg: msg,
                    buttons: this.OK,
                    fn: fn,
                    scope: scope,
                    width: 450,
                    icon: Ext.Msg.ERROR
                };
            }
            else if (msg.errMsg) {
                // css 强制换行: word-wrap:break-word;word-break:break-all;
                var text = '<div style="word-wrap:break-word;word-break:break-all;">'+ msg.errMsg + '</div>';
                if (msg.detail) {
                    text = text + '<p style="margin:10px 0 0;display:"><a id="viewErrorMsgDetail" class="expandable" href="javascript:void(0);" ' +
                        '><span>+</span>' + _T('VIEW_DETAIL') + '</a></p>' +
                        '<div style="display:none;max-height:200px;overflow: auto;border: 1px solid #ccc;width: 350px;">' +  msg.detail + '</div>';
                }
                cfg = {
                    title: _T('COMMON_ERROR'),
                    msg: text,
                    buttons: this.OK,
                    fn: fn,
                    scope: scope,
                    width: 450,
                    icon: Ext.Msg.ERROR
                };
            }

            return this.show(cfg);
        }
    });
});

// Ext.require('Ext.selection.CheckboxModel', function (){
//     Ext.override(Ext.selection.CheckboxModel, {
//         mode: 'SIMPLE'
//     });
// });

// index为对应要生成的编号，从1开始
function getId(index) {
    if (index) {
        var cluster = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
        var str = '', len = cluster.length;
        if (index > len) {
            var m = Math.ceil(index / len),
                r = index % len;
            for (var i = 0; i < m; i++) {
                str += cluster[r - 1];
            }
            return str;
        }
        else {
            str = cluster[index - 1];
            return str;
        }
    }
    else {
        return '';
    }
}

function getIndex (c){
    var str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return str.indexOf(c) + 1;
}

(function () {
    var showing = false,
        tid;
    window.showMsg = function (title, format) {
        Ext.select('#tipBox .text').setHTML(title);
        Ext.select('#tipBox').slideIn('t', {
            //  easing: 'easeOut',
            duration: 500
        });
        if (tid) {
            clearTimeout(tid);
        }
        tid = setTimeout(hideMsg, 4000);
        showing = true;
    };

    window.hideMsg = function () {
        if (showing) {
            var el = Ext.select('#tipBox').first();
            if (el.isVisible()) {
                el.slideOut('t', {
                    //  easing: 'easeOut',
                    duration: 500
                });
            }
        }
        showing = false;
    };
})();

//Ext.Ajax.disableCachingParam = true;
Ext.require('Ext.Ajax', function () {
    /**
     * To be called when the request has come back from the server
     * @private
     * @param {Object} request
     * @return {Object} The response
     */
    Ext.Ajax.onComplete = function (request) {
        var me = this,
            options = request.options,
            result,
            success,
            response;

        try {
            result = me.parseStatus(request.xhr.status);
        }
        catch (e) {
            // in some browsers we can't access the status if the readyState is not 4, so the request has failed
            result = {
                success: false,
                isException: false
            };
        }
        success = result.success;

        if (success) {
            response = me.createResponse(request);
            if (me.fireEvent('requestcomplete', me, response, options)) {
                Ext.callback(options.success, options.scope, [response, options]);
            }
            else {
                success = false;
                Ext.callback(options.failure, options.scope, [response, options]);
            }
        }
        else {
            if (result.isException || request.aborted || request.timedout) {
                response = me.createException(request);
            }
            else {
                response = me.createResponse(request);
            }
            me.fireEvent('requestexception', me, response, options);
            Ext.callback(options.exception, options.scope, [response, options]);
        }
        Ext.callback(options.callback, options.scope, [options, success, response]);
        delete me.requests[request.id];
        return response;
    };

    /**
     * Fires before a network request is made to retrieve a data object.
     */
    Ext.Ajax.on('beforerequest', function (conn, opts, eopts) {
        opts.silent = opts.silent || (opts.operation ? opts.operation.silent : false) ||
            (opts.proxy ? opts.proxy.silent : false);
        if (opts.silent === true) {
            return;
        }

        if (opts.mask) {
            Ext.get(opts.mask).mask('', 'x-mask-wait');
        }
        else {
            var el = Ext.get('topMask');
            el && el.setStyle('display', 'block');
        }
    });

    Ext.Ajax.on('requestcomplete',
        /**
         * Fires if the request was successfully completed.
         * @return {?boolean}
         */
         function (conn, response, opts, eopts) {
            if (!opts.silent) {
                if (opts.mask) {
                    Ext.get(opts.mask).unmask();
                }
                else {
                    var el = Ext.get('topMask');
                    el && el.setStyle('display', 'none');
                }
            }
            var text = response.responseText;
            var showMgs = opts.showMsg || Ext.Ajax.showMsg;
            if (typeof showMgs === 'undefined') {
                showMgs = true;
            }

            return checkResponseError(text, showMgs, opts.silent);
        }
    );

    /**
     * @desc Checks whether the responseText is error or not.
     * @param {string} text  An string in jsonData type as usual.
     * @param {boolean} showMgs  errors the errMsg if passes this parameter while the errMsg exists.
     * @return {boolean} result  Return value reponses the check result.
     */
    function checkResponseError(text, showMgs, silent) {
        try {
            var json = Ext.JSON.decode(text, true);

            if (null === json) {
                throw 'Ext.JSON decode text run error';
            }

            if (json.errMsg) {
                if (!silent && showMgs) {
                    Ext.Msg.error(json);
                }
                return false;
            }
        }
        catch (e) {
            return true;
        }
        return true;
    }

    /**
     * Fires if an error HTTP status was returned from the server.
     */
    Ext.Ajax.on('requestexception', function (conn, response, opts, eopts) {
        if (opts.silent === true) {
            return;
        }
        if (opts.mask) {
            Ext.get(opts.mask).unmask();
        }
        else {
            var el = Ext.get('topMask');
            el && el.setStyle('display', 'none');
        }
        var status = response.status;
        if (status === 0) {
            Ext.Msg.error('请求失败, 服务器没有响应。');
            return;
        }
        else if (status === 403) {
            Ext.Msg.error('您没有进行该操作的权限, 可能由以下原因造成:<br/>1. 用户未被授予该操作的权限<br/>2. 产品或功能未授权或授权已失效');
            return;
        }
        else if (status === 404) {
            Ext.Msg.error('您请求的页面不存在');
            return;
        }
        else if (status === 401) {
            var obj = Ext.decode(response.responseText);
            Ext.Msg.error(obj.errMsg, logoutWithoutCleanningSession);
        }
        else if (status === -1) {
            // do nothing
        }
        else {
            Ext.Msg.error(response.status + ':' + response.statusText);
        }
    });
});

function logout (){
    Ext.Ajax.request({
        url: './libs/user.php?action=logout',
        method: 'POST',
        callback: function (opts, success, res){
            if (success) {
                var obj = Ext.decode(res.responseText);
                if (obj.status == 'successful') {
                    Ext.util.Cookies.clear('lastXtype');
                    location.href = './login.html';
                }
            }
        }
    });
}

function logoutWithoutCleanningSession (){
    location.href = './login.html';
}

/**
 * polish raw data into html string(\n \r to br etc.)
 * @param  {string} str raw data
 * @return {string}     decorated string
 */
function polish (str){
    str = unescape(str);
    str = str.replace(/[\r\n]/gi, '<br>').replace(/[\s]/ig, '&nbsp;');
    return str;
}

/**
 ** 加法函数，用来得到精确的加法结果
 ** 说明：javascript的加法结果会有误差，在两个浮点数相加的时候会比较明显。这个函数返回较为精确的加法结果。
 ** 调用：accAdd(arg1,arg2)
 ** 返回值：arg1加上arg2的精确结果
 **/
function accAdd(arg1, arg2) {
    var r1, r2, m, c;
    try {
        r1 = arg1.toString().split(".")[1].length;
    }
    catch (e) {
        r1 = 0;
    }
    try {
        r2 = arg2.toString().split(".")[1].length;
    }
    catch (e) {
        r2 = 0;
    }
    c = Math.abs(r1 - r2);
    m = Math.pow(10, Math.max(r1, r2));
    if (c > 0) {
        var cm = Math.pow(10, c);
        if (r1 > r2) {
            arg1 = Number(arg1.toString().replace(".", ""));
            arg2 = Number(arg2.toString().replace(".", "")) * cm;
        } else {
            arg1 = Number(arg1.toString().replace(".", "")) * cm;
            arg2 = Number(arg2.toString().replace(".", ""));
        }
    } else {
        arg1 = Number(arg1.toString().replace(".", ""));
        arg2 = Number(arg2.toString().replace(".", ""));
    }
    return (arg1 + arg2) / m;
}

//给Number类型增加一个add方法，调用起来更加方便。
Number.prototype.add = function (arg) {
    return accAdd(arg, this);
};

/**
 ** 减法函数，用来得到精确的减法结果
 ** 说明：javascript的减法结果会有误差，在两个浮点数相减的时候会比较明显。这个函数返回较为精确的减法结果。
 ** 调用：accSub(arg1,arg2)
 ** 返回值：arg1加上arg2的精确结果
 **/
function accSub(arg1, arg2) {
    var r1, r2, m, n;
    try {
        r1 = arg1.toString().split(".")[1].length;
    }
    catch (e) {
        r1 = 0;
    }
    try {
        r2 = arg2.toString().split(".")[1].length;
    }
    catch (e) {
        r2 = 0;
    }
    m = Math.pow(10, Math.max(r1, r2)); //last modify by deeka //动态控制精度长度
    n = (r1 >= r2) ? r1 : r2;
    return ((arg1 * m - arg2 * m) / m).toFixed(n);
}

// 给Number类型增加一个mul方法，调用起来更加方便。
Number.prototype.sub = function (arg) {
    return accSub(arg, this);
};

/**
 ** 乘法函数，用来得到精确的乘法结果
 ** 说明：javascript的乘法结果会有误差，在两个浮点数相乘的时候会比较明显。这个函数返回较为精确的乘法结果。
 ** 调用：accMul(arg1,arg2)
 ** 返回值：arg1乘以 arg2的精确结果
 **/
function accMul(arg1, arg2) {
    var m = 0, s1 = arg1.toString(), s2 = arg2.toString();
    try {
        m += s1.split(".")[1].length;
    }
    catch (e) {
    }
    try {
        m += s2.split(".")[1].length;
    }
    catch (e) {
    }
    return Number(s1.replace(".", "")) * Number(s2.replace(".", "")) / Math.pow(10, m);
}

// 给Number类型增加一个mul方法，调用起来更加方便。
Number.prototype.mul = function (arg) {
    return accMul(arg, this);
};

/** 
 ** 除法函数，用来得到精确的除法结果
 ** 说明：javascript的除法结果会有误差，在两个浮点数相除的时候会比较明显。这个函数返回较为精确的除法结果。
 ** 调用：accDiv(arg1,arg2)
 ** 返回值：arg1除以arg2的精确结果
 **/
function accDiv(arg1, arg2) {
    var t1 = 0, t2 = 0, r1, r2;
    try {
        t1 = arg1.toString().split(".")[1].length;
    }
    catch (e) {
    }
    try {
        t2 = arg2.toString().split(".")[1].length;
    }
    catch (e) {
    }
    with (Math) {
        r1 = Number(arg1.toString().replace(".", ""));
        r2 = Number(arg2.toString().replace(".", ""));
        return (r1 / r2) * pow(10, t2 - t1);
    }
}

//给Number类型增加一个div方法，调用起来更加方便。
Number.prototype.div = function (arg) {
    return accDiv(this, arg);
};

// send message to make a record.
function sendMsg (sender, receiver, content){
    Ext.Ajax.request({
        url: './libs/message.php',
        method: 'POST',
        params: {
            action: 'add',
            sender: sender,
            content: content,
            receiver: receiver
        },
        callback: function (opts, success, res){
            if (success) {
                var obj = Ext.decode(res.responseText);
                if (obj.status == 'successful') {
                }
                else {
                    showMsg(obj.errMsg);
                }
            }
        }
    });
}

// time: 20151019124530 yyyyMMddHHmmss
function sendSMS (sender, reciever, recieverPhone, content, time){
    if (sender && reciever) {
        Ext.Ajax.request({
            url: './libs/user.php?action=getrealname',
            method: 'GET',
            params: {
                name: sender
            },
            callback: function (opts, success, res){
                if (success) {
                    var obj = Ext.decode(res.responseText);
                    if (obj.status == 'successful') {
                        sender = obj['realname'];
                        Ext.Ajax.request({
                            url: './libs/user.php?action=getrealname',
                            method: 'GET',
                            params: {
                                name: reciever
                            },
                            callback: function (opts, success, res){
                                obj = Ext.decode(res.responseText);
                                if (obj.status == 'successful') {
                                    reciever = obj['realname'];
                                    if (!recieverPhone) {
                                        setTimeout(function (){
                                            showMsg('发送用户没有手机号，无法发送！');
                                        }, 1000);
                                    }
                                    else {
                                        var p = {
                                            sender: sender,
                                            reciever: reciever,
                                            recieverPhone: recieverPhone,
                                            content: content
                                        };
                                        if (time) {
                                            Ext.apply(p, {
                                                time: time
                                            });
                                        }
                                        Ext.Ajax.request({
                                            url: './libs/msg.php?action=sendmsg',
                                            method: 'POST',
                                            params: p,
                                            callback: function (opts, success, res){
                                                if (success) {
                                                    var obj = Ext.decode(res.responseText);
                                                    if (obj.status == 'successful') {
                                                        setTimeout(function (){
                                                            showMsg('短信发送成功！');
                                                        }, 500);
                                                    }
                                                    else {
                                                        setTimeout(function (){
                                                            showMsg(obj.errMsg);
                                                        }, 500);
                                                    }
                                                }
                                            }
                                        });
                                    }
                                }
                                else {
                                    showMsg(obj.errMsg);
                                }
                            }
                        })
                    }
                    else {
                        showMsg(obj.errMsg);
                    }
                }
            }
        });
    }
    else {
        setTimeout(function (){
            showMsg('短信发送没有发送方或接收方!');
        })
    }
}

function sendMail (reciever, recieverMail, subject, content) {
    if (reciever) {
        Ext.Ajax.request({
            url: './libs/user.php?action=getrealname',
            method: 'GET',
            params: {
                name: reciever
            },
            callback: function (opts, success, res){
                obj = Ext.decode(res.responseText);
                if (obj.status == 'successful') {
                    reciever = obj['realname'];
                    if (!recieverMail) {
                        setTimeout(function (){
                            showMsg('用户没有邮箱地址，请通知用户尽快完善信息！');
                        }, 1000);
                    }
                    else {
                        var p = {
                            recipient: recieverMail,
                            subject: subject,
                            body: content
                        };
                        Ext.Ajax.request({
                            url: './libs/mail.php?action=send',
                            method: 'POST',
                            params: p,
                            callback: function (opts, success, res){
                                if (success) {
                                    var obj = Ext.decode(res.responseText);
                                    if (obj.status == 'successful') {
                                        setTimeout(function (){
                                            showMsg('邮件发送成功！');
                                        }, 500);
                                    }
                                    else {
                                        setTimeout(function (){
                                            showMsg(obj.errMsg);
                                        }, 500);
                                    }
                                }
                            }
                        });
                    }
                }
                else {
                    showMsg(obj.errMsg);
                }
            }
        })
    }
    else {
        setTimeout(function (){
            showMsg('邮件发送没有接收方!');
        })
    }
}

window.onresize = function() {
    var w = Ext.query('.x-window');
    Ext.each(w, function(item) {        
        var win = Ext.getCmp(item.id);
        
        win.center();
    })
}

Ext.require('Ext.form.field.VTypes', function (){
    Ext.apply(Ext.form.field.VTypes, {
        'phone': function() {
            var re = /^[\(\)\.\- ]{0,}[0-9]{3}[\(\)\.\- ]{0,}[0-9]{4}[\(\)\.\- ]{0,}[0-9]{4}[\(\)\.\- ]{0,}$/;
            return function(v) {
                return re.test(v);
            };
        }(),
        'phoneText': '手机号码错误, 例如: 123-456-7890 (破折号可选) 或者 (123) 456-7890',
        'fax': function() {
            var re = /^[\(\)\.\- ]{0,}[0-9]{3}[\(\)\.\- ]{0,}[0-9]{3}[\(\)\.\- ]{0,}[0-9]{4}[\(\)\.\- ]{0,}$/;
            return function(v) {
                return re.test(v);
            };
        }(),
        'faxText': 'The fax format is wrong',
        'zipCode': function() {
            var re = /^\d{5}(-\d{4})?$/;
            return function(v) {
                return re.test(v);
            };
        }(),
        'zipCodeText': 'The zip code format is wrong, e.g., 94105-0011 or 94105',
        'ssn': function() {
            var re = /^\d{3}-\d{2}-\d{4}$/;
            return function(v) {
                return re.test(v);
            };
        }(),
        'ssnText': 'The SSN format is wrong, e.g., 123-45-6789',
        'mail': function () {
            var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
            return function(v) {
                return re.test(v);
            }
        }(),
        'mailText': '邮箱格式错误，请重新输入'
    });
});

Ext.define('FamilyDecoration.Common', {});