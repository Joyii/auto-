/**
 * number校验
 * @author Flydany
 */

function number(name, type, validator) {

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
            case 'integer': {
                return this.integer();
            } break;
            case 'boolean': {
                return this.boolean();
            } break;
            case 'digits': {
                return this.digits();
            } break;
            case 'digits.between': {
                return this.digits_between();
            } break;
        }

        return this.numeric();
    }

    /**
     * 验证字段必须是整型
     *
     * @returns {boolean}
     */
    this.integer = function() {
        if(/^(0|[\-+]?[1-9]\d*)$/.test(this.value)) {
            return true;
        }
        this.addError('必须是有效数字');
        return false;
    }

    /**
     * 验证字段必须是双精度浮点数
     *
     * @returns {boolean}
     */
    this.double = function() {
        if(/^[\-+]?\d+(\.\d+)?$/.test(this.value)) {
            return true;
        }
        this.addError('不符合双精度浮点型数字格式');
        return false;
    }

    /**
     * 验证的字段必须能够转换为布尔值。所接受的输入可以是 true，false，1，0，"1"，"0"。
     *
     * @returns {boolean}
     */
    this.boolean = function() {
        if([true, false, '1', '0', 1, 0].indexOf(this.value) >= 0) {
            return true;
        }
        this.addError('必须为布尔值类型');
        return false;
    }

    /**
     * 验证字段必须是数值
     *
     * @returns {boolean}
     */
    this.numeric = function() {
        if(/^\d+(\.\d+)?$/.test(this.value)) {
            return true;
        }
        this.addError('必须由数字组成');
        return false;
    }

    /**
     * 验证的字段必须是数字类型并且具有指定的长度。
     *
     * @returns {boolean}
     */
    this.digits = function() {
        if( ! this.numeric()) {
            this.addError('必须由数字组成');
            return false;
        }
        if((this.value + '').length == this.format) {
            return true;
        }
        this.addError('长度不正确');
        return false;
    }

    /**
     * 验证的字段必须具有指定区间的长度。
     *
     * @returns {boolean}
     */
    this.digits_between = function() {
        if( ! this.numeric()) {
            this.addError('必须由数字组成');
            return false;
        }
        var length = (this.value + '').length;
        var format = this.format.split(',');
        if(length >= format[0]) {
            if(format[1] && length <= format[1]) {
                return true;
            }
        }
        this.addError('长度不正确');
        return false;
    }


}
