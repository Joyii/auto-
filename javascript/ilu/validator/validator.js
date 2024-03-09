/**
 * Form表单数据格式校验
 * @create by Flydany
 * @date 2017.03.30
 * include dom blur check & dom value change & form submit data check
 * check type contain's single dom
 */

 // 已经加载的校验类数组
 var validatorLoader = [];

function validator(type) {

    this.modal = {
        accepted: ['range', 'accepted'],
        present: ['required', 'exists'],
        required: 'required',
        filled: 'required',
        sometimes: ['required', 'sometimes'],
        required_if: ['required', 'if'],
        required_unless: ['required', 'unless'],
        required_with: ['required', 'with'],
        required_with_all: ['required', 'with.all'],
        required_without: ['required', 'without'],
        required_without_all: ['required', 'without.all'],
        string: 'stringer',
        alpha: ['stringer', 'alpha'],
        alpha_dash: ['stringer', 'alpha.dash'],
        alpha_num: ['stringer', 'alpha.num'],
        max: ['stringer', 'max'],
        min: ['stringer', 'min'],
        json: ['stringer', 'json'],
        between: ['stringer', 'between'],
        array: 'arrayer',
        distinct: ['arrayer', 'distinct'],
        numeric: 'number',
        integer: ['number', 'integer'],
        boolean: ['number', 'boolean'],
        digits: ['number', 'digits'],
        regex: 'regular',
        url: ['regular', 'url'],
        active_url: ['regular', 'url'],
        ip: ['regular', 'ip'],
        ipv4: ['regular', 'ipv4'],
        ipv6: ['regular', 'ipv6'],
        email: ['regular', 'email'],
        in: 'range',
        in_array: ['range', 'in.array'],
        not_in: ['range', 'not.in'],
        date: 'date',
        date_format: ['date', 'formater'],
        after: ['date', 'after'],
        after_or_equal: ['date', 'after.equal'],
        before: ['date', 'before'],
        before_or_equal: ['date', 'before.equal'],
        same: ['compare', 'same'],
        size: ['compare', 'size'],
        confirmed: ['compare', 'confirmed'],
        different: ['compare', 'different'],
    };

    ///////// checker 属性定义 ////////////
    this.ruleDom = '';
    // 需要校验的form表单 string
    this.form = '';
    // 数据校验规则 object
    this.rules = {};
    this.messages = {};
    this.names = {};
    // 数据校验状态 string
    this.status = 'pass';
    // 校验异常描述 array
    this.errors = [];
    this.default = {};
    // 数据校验配置
    this.pass = 'pass';
    this.singleChecker = '.checker';
    this.warnClass = '.warner';
    this.bindEvent = 'change';
    this.errorClass = 'has-error';
    this.successClass = 'has-success';
    this.isRecycle = false;

    ///////// checker 数据校验方法定义 //////////
    this.init = function(param) {
        if(utiler.formater(param, '[object string]')) {
            param = { ruleDom: param };
        }
        this.reset(param.ruleDom);
        this.form = $(param.ruleDom).attr('data-form');
        // 初始化DOM实时验证
        this.initEvent();
        if(param.isSubmit == false) {
            return;
        }
        if($(this.form).length && $(this.form)[0] && $(this.form)[0].tagName == 'FORM') {
            // 初始化提交验证
            var checker = this;
            $(this.form).on('submit', function () {
                return checker.validate();
            });
        }
    }
    this.validate = function() {
        this.status = this.pass;
        this.errors = [];
        return this.initSubmit();
    }
    this.convertName = function(name) {
        var ns = name.split('.');
        name = ns[0];
        if(ns.length > 1) {
            for (var i = 1; i < ns.length; ++i) {
                if(ns[i] == '*' && i == (ns.length - 1)) {
                    continue;
                }
                name += '[' + ns[i] + ']';
            }
        }
        return name;
    }
    // 设置校验规则
    this.setRules = function(rules) {
        var path = $('#_validate-scripttag').attr('src');
        for(var name in rules) {
            this.names[this.convertName(name)] = name;
            // 定义字段校验规则存储器
            this.rules[name] = [];
            if(utiler.formater(rules[name], '[object array]')) {
                var current = rules[name];
            }
            else {
                var current = rules[name].split('|');
            }
            for(var i = 0; i < current.length; ++i) {
                var rule = current[i].split(':');
                // 忽略未定义的校验类型
                if(this.modal[rule[0]] === undefined) {
                    console.log('未找到校验规则：' + rule[0]);
                    continue;
                }
                var r = rule[0];
                if(rule[1] == undefined) {
                    this.rules[name].push([r]);
                } else {
                    rule.splice(0, 1);
                    this.rules[name].push([r, rule.join(':')]);
                }
                var classer = utiler.formater(this.modal[r], '[object array]') ? this.modal[r][0] : this.modal[r];
                // 引入JS
                if(validatorLoader.indexOf(classer) < 0 && eval("typeof(" + classer + ")") == 'undefined') {
                    validatorLoader.push(classer);
                    $.getScript(path.replace(/validate/, classer));
                }
            }
        }
        return this;
    }
    this.getOriRules = function() {
        var config = JSON.parse($(this.ruleDom).val());
        this.messages = config.messages;
        return config.rules;
    }
    // 添加数据校验错误描述
    this.addError = function(message) {
        this.errors.push(message);
        return this;
    }
    // 返回当前校验是否通过
    // @return boolean true 不通过， false 通过
    this.isError = function() {
        return this.status === this.pass ? false : true;
    }
    // 是否全部校验(遇错是否直接退出)
    // @return boolean
    this.unRecycle = function() {
        return this.isRecycle ? false : true;
    }
    // 获取name对应的dom元素
    // @param string name 校验数据的name值
    // @param boolean gFull 是否获取全部的元素 、 获取选中的元素
    // return dom object
    this.getDom = function(name, gFull) {
        name = this.convertName(name);
        var dom = this.form + ' [name="' + name + '"]';
        if( ! $(dom).length) {
            dom = this.form + " [name=\"" + name + "[]\"]";
        }
        if( ! gFull && ($(dom).get(0) && $(dom).get(0).tagName.toLowerCase() === 'input') && ($.inArray($(dom).eq(0).attr('type'), ['checkbox', 'radio']) >= 0)) {
            dom = dom + ':checked';
        }
        // 异常输出
        else if( ! $(dom).length) {
            return false;
        }
        return dom;
    }
    // 获取dom的name属性
    // @return string
    this.tagName = function(dom) {
        var name = $(dom).attr('name');
        if(name.substring(name.length - 2) == '[]') {
            return name.substring(0, name.length - 2);
        }
        return name;
    }
    // 获取label名称
    this.getTitle = function(name) {
        return $(this.getDom(name)).eq(0).parents('.checker').find('label').text();
    }
    this.getMessage = function(name, rule) {
        return this.messages[name + '.' + rule];
    }
    // 获取dom[name=name]元素的值
    // @param string name 元素name
    // @return array
    this.getValue = function(name, index) {
        var dom = this.getDom(name, false);
        var domLength = $(dom).length;
        if(domLength <= 1) {
            return $(dom).val();
        }
        if(index) {
            return $(dom).eq(index).val();
        }
        var value = [];
        for(var i = 0; i < domLength; ++i) {
            value.push($(dom).eq(i).val());
        }
        return value;
    }
    // 重置校验配置参数
    this.reset = function(ruleDom) {
        this.ruleDom = ruleDom;
        this.form = null;
        // 设置 rules 避免直接存储之后
        // 避免下次使用原始rule会出现值被改变
        this.setRules(this.getOriRules());
        this.status = this.pass;
        return this;
    }

    // 初始化rules[param]校验源的事件
    this.initEvent = function () {
        for (var name in this.rules) {
            // 获取DOM对象
            var dom = this.getDom(name, true);
            if(dom == false) {
                console.log('没有找到DOM元素：' + name);
                continue;
            }
            // 校验单条数据
            // 绑定每个DOM的blur事件，数据校验触发条件
            var checker = this;
            $.each($(dom), function(index) {
                $(this).attr('validate-index', index);
                $(this).on(checker.bindEvent, function () {
                    var name = checker.names[checker.tagName(this)];
                    if(utiler.empty(name)) {
                        console.log('DOM元素名称异常');
                        return true;
                    }
                    // 检测当前DOM的规则
                    checker.verifySingle(name, $(this).attr('validate-index'));
                });
            });
            // 多重验证事件
            $(dom).on('blur', function() {
                $(this).trigger('change');
            });
        }
        return this;
    }

    /**
     * 初始化数据提交时整体验证方法
     * @param this.rules object 数据校验规则
     * @param this.message array 异常描述
     * @param this.status string 处理状态
     * @return boolean
     */
    this.initSubmit = function() {
        // 循环每条数据规则
        this.verifyParams();
        // 判断是否校验通过
        if(this.isError()) {
            // layer.msg(this.errors.join('。'), { shift: 6 });
            $.danger(this.errors.join('。'));
            return false;
        }
        return true;
    }
    /**
     * 校验所有的数据字段
     * @param this.rules.param object 需要校验的数据字段
     * @return boolean
     */
    this.verifyParams = function() {
        for (var name in this.rules) {
            // 校验单条数据
            var response = this.verifySingle(name);
            if(response.code !== this.pass) {
                this.status = response.code;
                this.addError(response.message);
                if(this.unRecycle()) {
                    break;
                }
            }
        }
        return this;
    }
    /**
     * 校验单条数据字段
     * @param name string 需要校验的数据字段
     * @return boolean
     */
    this.verifySingle = function(name, index) {
        var oneStatus = this.pass,
            oneMessage = [],
            valueLoop = false;
        var oneErrors = [];
        // 获取dom的值
        var dom = this.getDom(name, false);
        // 获取dom的所有value值
        if( ! $(dom).length) {
            dom = this.getDom(name, true);
        }
        var title = this.getTitle(name);
        if(index) {
            var value = this.getValue(name, index);
        }
        else {
            var value = this.getValue(name);
        }
        // 获取当前数据验证规则
        var rules = this.rules[name];
        // 循环验证 rules中的每个具体数据规则
        for(var i = 0; i < rules.length; ++i) {
            var rule = rules[i];
            var format = rule[0];
            if(format == 'sometimes') {
                if(utiler.empty(dom)) {
                    break;
                }
                continue;
            }
            if (this.unRecycle() && oneStatus != this.pass) {
                break;
            }
            // 判断校验规则
            var classer, type = '';
            if(utiler.formater(this.modal[format], '[object array]')) {
                classer = this.modal[format][0];
                type = this.modal[format][1];
            }
            else {
                classer = this.modal[format];
            }
            var checker = eval("new " + classer + "('" + name + "', '" + type + "', this)");
            checker.setConfiguration(rule[1]);
            var error = this.getMessage(name, rule[0]);
            if (utiler.formater(value, '[object array]')) {
                valueLoop = true;
                for(var v = 0; v < value.length; ++v) {
                    checker.setValue(value[v]);
                    if (!checker.validate()) {
                        if(oneErrors[v] === undefined) {
                            oneErrors[v] = [];
                        }
                        oneErrors[v].push('第' + (v + 1) + '条：' + (error ? error : (title + checker.errors())));
                        oneStatus = 'error';
                        oneMessage.push('第' + (v + 1) + '条：' + (error ? error : (title + checker.errors())));
                        if(this.unRecycle()) {
                            break;
                        }
                    }
                }
            }
            else {
                checker.setValue(value);
                if (!checker.validate()) {
                    oneStatus = 'error';
                    oneMessage.push(error ? error : (title + checker.errors()));
                }
            }
        }
        // 获取承载当前异常的父元素
        if(!valueLoop) {
            if (index) {
                oneErrors[index] = [];
                oneErrors[index].push(oneMessage);
            } else {
                oneErrors[0] = [];
                oneErrors[0].push(oneMessage);
            }
        }
        else {
            var parent = $(dom).parents(this.singleChecker);
            // 取消展示错误、展示通过提示
            $(parent).addClass(this.successClass).removeClass(this.errorClass);
            $(parent).find(this.warnClass).text('PASS');
        }
        for(var o in oneErrors) {
            var parent = $(dom).eq(o).parents(this.singleChecker);
            // 校验未通过
            if(oneStatus !== this.pass) {
                oneMessageSplit = oneErrors[o].join(', ');
                // 展示错误
                $(parent).addClass(this.errorClass).removeClass(this.successClass);
                // 获取需要渲染错误描述的dom元素
                $(parent).find(this.warnClass).text(oneMessageSplit);
            }
            // 校验通过
            else {
                // 取消展示错误、展示通过提示
                $(parent).addClass(this.successClass).removeClass(this.errorClass);
                $(parent).find(this.warnClass).text('PASS');
            }
        }
        return { code: oneStatus, message: oneMessage.join('，') };
    }
    // 是否必填项
    // @return boolean
    this.isRequired = function(name) {
        for (var i = 0; i < this.rules[name].length; ++i) {
            if(this.rules[name][i].indexOf('required') >= 0) {
                return true;
            }
        }
        return false;
    }
}
