/**
 * range校验
 * @author Flydany
 */

function range(name, type, validator) {

    this.name = name;
    this.type = type;
    this.validator = validator;

    validate.apply(this);

    // 校验
    this.validate = function() {
        if(utiler.empty(this.value)) {
            return true;
        }

        switch (this.type) {
            case 'in.array': {
                return this.in_array();
            } break;
            case 'not.in': {
                return this.not_in();
            } break;
            case 'accepted': {
                return this.accepted();
            } break;
        }

        return this.in();
    }

    /**
     *验证的字段必须包含在给定的值列表中。
     *
     * @returns {boolean}
     */
    this.in = function() {
        var ranges = this.format.split(',');
        if(ranges.indexOf(this.value) >= 0) {
            return true;
        }
        this.addError('不在指定值范围内');
        return false;
    }

    /**
     *验证的字段必须包含在给定的值列表中。
     *
     * @returns {boolean}
     */
    this.not_in = function() {
        var ranges = this.format.split(',');
        if(ranges.indexOf(this.value) < 0) {
            return true;
        }
        this.addError('不能包含在指定值范围内');
        return false;
    }

    /**
     * 验证的字段必须存在于另一个字段 anotherfield 的值中。
     *
     * @returns {boolean}
     */
    this.in_array = function() {
        var ranges = this.validator.getValue(this.format);
        if(utiler.formater(ranges, '[object array]')) {
            if (ranges.indexOf(this.value) >= 0) {
                return true;
            }
        } else if(utiler.formater(ranges, '[object string]')) {
            if(ranges == this.value) {
                return true;
            }
        }
        this.addError('不在指定值范围内');
        return false;
    }

    /**
     * 验证的字段必须为 yes，on，1，或者 true。这通常用来验证服务条款的承诺。
     *
     * @returns {boolean}
     */
    this.accepted = function() {
        if(['yes', 'no', '1', 'true'].indexOf(this.value) >= 0) {
            return true;
        }
        this.addError('不在选取范围');
        return false;
    }
}
