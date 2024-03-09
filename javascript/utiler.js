/**
 * 公用函数
 * @author Flydany
 * @date 2017.03.30
 */
'use strict';

var utiler = {
    SuccessCode: '200',
    domain: window.location.protocol + '//' + document.domain,

    // 路由
    route: function(uri) {
        return this.domain + uri;
    },

    // 校验空值
    empty: function (value) {
        if(value === undefined || value === null || value === '' || value === {}) {
            return true;
        }
        if(utiler.formater(value, '[object array]')) {
            if(value.length == 0 || (value.length == 1 && value[0] === '')) {
                return true;
            }
        }
        if(utiler.formater(value, '[object object]')) {
            return $.isEmptyObject(value);
        }
        return false;
    },
    // 校验不为空
    exists: function (value, key) {
        if(utiler.empty(key)) {
            return !utiler.empty(value);
        }
        return utiler.exists(value) && value.hasOwnProperty(key) && utiler.exists(value[key]);
    },
    // 数组是否包含子集
    in: function(value, inArr) {
        return inArr.findIndex(item => {
            return item == value;
        }) >= 0;
    },
    // 删除数组中指定的元素
    remove: function(value, inArr) {
        var index = inArr.findIndex(item => {
            return item == value;
        });
        if(index >= 0) {
            inArr.splice(index, 1);
        }
        return inArr;
    },
    // 弹层
    dialog: function (mthis) {
        handler.dialog({
            title: utiler.exists($(mthis).attr('data-message')) ? $(mthis).attr('data-message') : $(mthis).text(),
            mthis: mthis,
            container: $(mthis).attr('data-container'),
            class: $(mthis).attr('data-class'),
            close: $(mthis).attr('data-close'),
            afterAlert: $(mthis).attr('data-after'),
            beforeAlert: $(mthis).attr('data-before')
        });
    },
    // 弹层
    request: function (mthis) {
        handler.immediately({
            title: utiler.exists($(mthis).attr('data-message')) ? $(mthis).attr('data-message') : $(mthis).text(),
            mthis: mthis,
            trKey: $(mthis).attr('data-tr'),
            tableKey: $(mthis).attr('data-table'),
            isConfirm: $(mthis).attr('data-confirm') ? $(mthis).attr('data-confirm') : true,
            isAlert: $(mthis).attr('data-alert') ? $(mthis).attr('data-alert') : true,
            isSuccessAlert: $(mthis).attr('data-success-alert') ? $(mthis).attr('data-success-alert') : true,
            isKeep: $(mthis).attr('data-keep') ? $(mthis).attr('data-keep') : true,
            requestSuccess: $(mthis).attr('data-after'),
            beforePost: $(mthis).attr('data-before'),
            beforeRequest: $(mthis).attr('data-start')
        });
    },
    // 把字符串中的 `{xxx}` 替换为 item[xxx]
    replace: function(base, item) {
        if(utiler.empty(base) || utiler.empty(item) || item == undefined) {
            return base;
        }
        var match = base.match(/\{[\w\.\|:,-_\?]+\}/g);
        if(utiler.empty(match)) {
            return base;
        }
        if(utiler.formater(match, '[object string]')) {
            match = [match];
        }
        for(var i = 0; i < match.length; ++i) {
            if(utiler.empty(match[i])) {
                console.log('empty: ' + match[i]);
                continue;
            }
            var preg = match[i];
            var args = match[i].split('|');
            if(utiler.exists(args[1])) {
                preg = args[0];
            }
            var convert = utiler.findor(args[0].replace(/\{|\}/g, ''), item);
            if(utiler.exists(args[1])) {
                args[1] = args[1].replace(/\{|\}/g, '');
                var ps = args[1].split(':');
                if(utiler.exists(ps[1])) {
                    convert = utiler.call(ps[0], convert, ps[1]);
                }
                else {
                    convert = utiler.call(args[1], convert);
                }
            }
            base = base.replace(match[i], convert);
        }
        return base;
    },
    findor: function(keys, item) {
        var arr = keys.split('??');
        var convert = '';
        if(arr.length >= 1) {
            for(var i in arr) {
                convert = utiler.finder(item, arr[i].replace(/\{|\}/g, ''));
                if(utiler.exists(convert)) {
                    break;
                }
            }
        }
        return convert;
    },
    // 校验数据格式
    formater: function (value, format) {
        return Object.prototype.toString.call(value).toLowerCase() === format ? true : false;
    },
    amount: function(value, decimal) {
        if(utiler.empty(value)) {
            value = 0;
        }
        if(decimal == undefined) {
            return '¥' + utiler.number(value, decimal).replace(/\.0+$/, '').replace(/(\.[1-9])0+$/, '$1');
        }
        return '¥' + utiler.number(value, decimal);
    },
    number: function(value, decimal) {
        if(utiler.empty(value)) {
            value = 0;
        }
        if(utiler.empty(decimal)) {
            decimal = 0;
        }
        value = parseFloat((value + "").replace(/[^\d\.-]/g, "")).toFixed(decimal) + "";
        var l = value.split(".")[0].split("").reverse();
        var r = value.split(".")[1];
        var t = "";
        for (var i = 0; i < l.length; ++i) {
            t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");
        }
        return t.split("").reverse().join("") + (decimal > 0 ? "." + r : '');
    },
    jsoner: function(value) {
        if(utiler.formater(value, '[object string]')) {
            return value;
        }
        return JSON.stringify(value);
    },
    filesize: function(value) {
        if(utiler.empty(value)) {
            value = 0;
        }
        if (value >= 1073741824) {
            value = Math.round(value / 1073741824 * 100) / 100 + 'GB';
        }
        else if (value >= 1048576) {
            value = Math.round(value / 1048576 * 100) / 100 + 'MB';
        }
        else if (value >= 1024) {
            value = Math.round(value / 1024 * 100) / 100 + 'KB';
        }
        else {
            value = value + 'Bytes';
        }
        return value;
    },
    substring: function(value, operator) {
        if(utiler.empty(operator)) {
            operator = '32|..';
        }
        var args = (operator + '').split('|');
        if(!value || value.length <= args[0]) {
            return value;
        }
        if(utiler.empty(args[1])) {
            args[1] = '..';
        }
        return value.substring(0, args[0]) + args[1];
    },
    thumb: function(image, type) {
        var arr = image.split('.');
        arr.pop();
        return arr.join('.') + '_' + type + '.png';
    },
    date: function(value) {
        if(utiler.empty(value)) {
            return '--';
        }
        if(utiler.notInteger(value)) {
            return value.substring(0, 10);
        }
        var date = new Date(value * 1000);
        value = date.getFullYear();
        value += '-' + utiler.prevpend(date.getMonth() + 1);
        value += '-' + utiler.prevpend(date.getDate());
        return value;
    },
    datetime: function(value) {
        if(utiler.empty(value) || value == '0000-00-00 00:00:00') {
            return '--';
        }
        if(utiler.notInteger(value)) {
            return value.substring(0, 19).replace('T', ' ');
        }
        var date = new Date(value * 1000);
        value = date.getFullYear();
        value += '-' + utiler.prevpend(date.getMonth() + 1);
        value += '-' + utiler.prevpend(date.getDate());
        value += ' ' + utiler.prevpend(date.getHours());
        value += ':' + utiler.prevpend(date.getMinutes());
        value += ':' + utiler.prevpend(date.getSeconds());
        return value;
    },
    time: function(value) {
        if(utiler.empty(value)) {
            return '--';
        }
        if(utiler.notInteger(value)) {
            return value.substring(11, 16);
        }
        var date = new Date(value * 1000);
        var value = utiler.prevpend(date.getHours());
        value += ':' + utiler.prevpend(date.getMinutes());
        return value;
    },
    prevpend: function(value, length, pend) {
        value += '';
        if(utiler.empty(length)) {
            length = 2;
        }
        if(utiler.empty(pend)) {
            pend = '0';
        }
        if(value.length >= length) {
            return value;
        }
        for(var i = 0; i < length - value.length; ++i) {
            value = pend + '' + value;
        }
        return value;
    },
    finder: function(item, keys) {
        if(keys.indexOf('.') == -1) {
            return utiler.empty(item[keys]) ? '' : item[keys];
        }
        var keys = keys.split('.');
        for(var i = 0; i < keys.length; ++i) {
            item = item[keys[i]];
            if(item == undefined) {
                break;
            }
        }
        return utiler.empty(item) ? '': item;
    },
    bcadd: function(num1, num2) {
        var r1, r2, m;
        try {
            r1 = num1.toString().split('.')[1].length;
        }
        catch(e) {
            r1 = 0;
        }
        try {
            r2 = num2.toString().split(".")[1].length;
        }
        catch(e) {
            r2 = 0;
        }
        m = Math.pow(10, Math.max(r1, r2));
        return Math.round(num1 * m + num2 * m) / m;
    },
    bcsub:function(num1, num2) {
        var r1,r2,m;
        try {
            r1 = num1.toString().split('.')[1].length;
        }
        catch(e) {
            r1 = 0;
        }
        try {
            r2 = num2.toString().split(".")[1].length;
        }
        catch(e) {
            r2 = 0;
        }
        m = Math.pow(10, Math.max(r1, r2));
        n = (r1 >= r2) ? r1 : r2;
        return (Math.round(num1 * m - num2 * m) / m).toFixed(n);
    },
    isInteger: function(integer) {
        return integer % 1 === 0;
        // return typeof integer === 'number' && !isNaN(integer);
    },
    notInteger: function(integer) {
        return !utiler.isInteger(integer);
    },
    // 提取数据
    parameters: function(mthis) {
        var _params = {};
        this.updateEditor();
        $.each(mthis, function() {
            var name = $(this).attr('name');
            if(utiler.empty(name)) {
                if($(this).hasClass('active')) {
                    name = $(this).attr('data-name');
                }
            }
            if(utiler.empty(name)) {
                return true;
            }
            if(utiler.in($(this).attr('type'), ['radio', 'checkbox'])) {
                if( ! $(this).is(':checked')) {
                    return true;
                }
            }
            // file
            if(utiler.in($(this).attr('type'), ['file'])) {
                if(utiler.empty($(this)[0].files[0])) {
                    return true;
                }
                _params[name] = $(this)[0].files[0];
                _params['include_file'] = true;
                return true;
            }
            if(utiler.in($(this)[0].tagName.toUpperCase(), ['INPUT', 'SELECT', 'TEXTAREA'])) {
                if(name.indexOf('[]') > 0) {
                    name = name.replace('[]', '');
                    if(utiler.empty(_params[name])) {
                        _params[name] = [];
                    }
                    _params[name].push($(this).val());
                }
                else {
                    _params[name] = $(this).val();
                }
            }
            else {
                _params[name] = $(this).attr('data-value');
            }
        });
        return _params;
    },
    updateEditor: function(key) {
        if(typeof EDITOR == 'undefined' || utiler.empty(EDITOR)) {
            return true;
        }
        if(utiler.exists(key)) {
            $('#' + key).val(EDITOR[key].getData());
            return true;
        }
        for(var key in EDITOR) {
            $('#' + key).val(EDITOR[key].getData());
        }
        return true;
    },
    clone: function(object) {
        var buf;
        if(object instanceof Array) {
            buf=[];
            var i = object.length;
            while(i--) {
                buf[i] = this.clone(object[i]);
            }
            return buf;
        }
        else if(object instanceof Object) {
            buf = {};
            for(var k in object) {
                buf[k] = this.clone(object[k]);
            }
            return buf;
        }
        else {
            return object;
        }
    },
    // 校验数据格式
    call: function(_func, param, param2, param3) {
        if(utiler.empty(_func)) {
            return true;
        }
        if (typeof _func == 'function') {
            return _func(param, param2, param3);
        }
        else if (typeof _func == 'string') {
            // 类似function(a) { console.log(a); }
            if(['function ', 'function('].indexOf(_func.substr(0, 9)) >= 0 || _func.includes('=>')) {
                return eval('(' + _func + ')(param, param2, param3)');
            }
            // 类似utiler.in
            else {
                if(/\)$/.test(_func)) {
                    return eval(_func);
                }
                else {
                    return eval(_func + '(param, param2, param3)');
                }
            }
        }
        return false;
    }
}

var app = {
    // 获取table实例
    table: function(mthis) {
        if(utiler.exists(mthis)) {
            if($(mthis).parents('.search-container, .table-container').length) {
                return window[$(mthis).parents('.search-container, .table-container').attr('data-index')];
            }
            else {
                return window[$(mthis).attr('data-index')];
            }
        }
        if($('.table-container').length == 1) {
            return window[$('.table-container').attr('data-index')];
        }
        // 判断是否有弹层列表页
        else if($('.dialog.active .table-container').length == 1) {
            return window[$('.dialog.active .table-container').attr('data-index')];
        }
        console.log('unknow table');
    },

    // 获取tree实例
    tree: function(mthis) {

        return window['tree'];
    }
};
