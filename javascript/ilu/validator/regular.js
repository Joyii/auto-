/**
 * regular验证
 * @author Flydany
 */

function regular(name, type, validator) {

    this.name = name;
    this.type = type;
    this.validator = validator;

    validate.apply(this);

    // 配置校验规则
    this.patterns = {
        url: /^(http(s)?:\/\/)?[A-Za-z0-9-_]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/,
        ipv4: /^(([01]?\d?\d|2[0-4]\d|25[0-5])\.){3}([01]?\d?\d|2[0-4]\d|25[0-5])$/,
        email: /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/,
    };

    // 校验
    this.validate = function() {
        if(utiler.empty(this.value)) {
            return true;
        }

        if(this.patterns[this.type] !== undefined) {
            return this.match(this.type);
        }

        switch(this.type) {
            case 'ip': {
                return this.ip();
            } break;
            case 'ipv6': {
                return this.ipv6();
            } break;
        }

        return this.preg();
    }

    /**
     * 指定校验规则匹配
     *
     */
    this.preg = function() {
        var pattern = new RegExp(this.format.substr(1, this.format.length - 2));
        if(pattern.test(this.value)) {
            return true;
        }
        this.addError('格式错误');
        return false;
    }


    /**
     * 校验已定义规则
     *
     */
    this.match = function() {
        if(this.patterns[this.type].test(this.value)) {
            return true;
        }
        this.addError('格式错误');
        return false;
    }

    /**
     * 校验IP格式
     *
     */
    this.ip = function() {
        if(this.match('ipv4') || this.ipv6()) {
            return true;
        }
        this.addError('格式错误');
        return false;
    }

    /**
     * 校验IPv6格式
     *
     */
    this.ipv6 = function() {
        if(/:/.test(str) && str.match(/:/g).length < 8 && /::/.test(str)?(str.match(/::/g).length == 1
            && /^::$|^(::)?([\da-f]{1,4}(:|::))*[\da-f]{1,4}(:|::)?$/i.test(str)):/^([\da-f]{1,4}:){7}[\da-f]{1,4}$/i.test(str)
            ) {

            return true;
        }
        this.addError('格式错误');
        return false;
    }
}
