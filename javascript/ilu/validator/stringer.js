/**
 * @name string类型校验
 * @create by Flydany
 * @date 2017.03.30
 * include dom blur check & dom value change & form submit data check
 * check type contain's single dom & relate dom & or mult dom choise one
 */

function stringer(name, type, validator) {

    this.name = name;
    this.type = type;
    this.validator = validator;

    validate.apply(this);

    // 校验
    this.validate = function() {
        if(utiler.empty(this.value)) {
            return true;
        }
        switch(this.type) {
            case 'min': {
                return this.min();
            } break;
            case 'max': {
                return this.max();
            } break;
            case 'between': {
                return this.between();
            } break;
            case 'json': {
                return this.json();
            } break;
            case 'alpha': {
                return this.alpha();
            } break;
            case 'alpha.dash': {
                return this.alpha_dash();
            } break;
            case 'alpha.num': {
                return this.alpha_num();
            } break;
        }

        return this.string();
    }

    /**
     * 验证的字段必须是字符串
     *
     * @returns {boolean}
     */
    this.string = function() {
        if(utiler.formater(this.value, '[object string]')) {
            return true;
        }
        this.addError('必须是字符串');
        return false;
    }

    /**
     * 验证中的字段必须具有最小值。字符串、数字、数组或是文件大小的计算方式都用 size 方法进行评估。
     *
     * @returns {boolean}
     */
    this.min = function() {
        if(this.format > this.value.length) {
            this.addError('长度不能小于' + this.format);
            return false;
        }
        return true;
    }

    /**
     * 验证中的字段必须小于或等于 value。字符串、数字、数组或是文件大小的计算方式都用 size 方法进行评估。
     *
     * @returns {boolean}
     */
    this.max = function() {
        if(this.format < this.value.length) {
            this.addError('长度不能大于' + this.format);
            return false;
        }
        return true;
    }

    /**
     * 验证的字段的大小必须在给定的 min 和 max 之间。字符串、数字、数组或是文件大小的计算方式都用 size 方法进行评估。
     *
     * @returns {boolean}
     */
    this.between = function() {
        var split = this.format.split(',');
        if(split[0] > this.value.length) {
            this.addError('长度必须介于：' + split[0] + '和' + split[1]);
            return false;
        }
        if(split[1] < this.value.length) {
            this.addError('长度必须介于：' + split[0] + '和' + split[1]);
            return false;
        }
        return true;
    }

    /**
     * 验证的字段必须是有效的 JSON 字符串。
     *
     * @returns {boolean}
     */
    this.json = function() {
        if (typeof this.value == 'string') {
            try {
                var obj=JSON.parse(this.value);
                if(typeof obj == 'object' && obj ){
                    return true;
                }
            } catch(e) {}
        }
        this.addError('不是JSON数据');
        return false;
    }

    /**
     * 验证的字段必须完全是字母的字符。
     *
     * @returns {boolean}
     */
    this.alpha = function() {
        if(/^[a-zA-Z]+$/.test(this.value)) {
            return true;
        }
        this.addError('只能全为字母')
        return false;
    }

    /**
     * 验证的字段可能具有字母、数字、破折号-以及下划线_。
     *
     * @returns {boolean}
     */
    this.alpha_dash = function() {
        if(/^[a-zA-Z\d\-_]+$/.test(this.value)) {
            return true;
        }
        this.addError('只能全为字母、数字、破折号-、下划线_')
        return false;
    }

    /**
     * 验证的字段可能具有字母、数字
     *
     * @returns {boolean}
     */
    this.alpha_num = function() {
        if(/^\w+$/.test(this.value)) {
            return true;
        }
        this.addError('只能全为字母、数字')
        return false;
    }
}
