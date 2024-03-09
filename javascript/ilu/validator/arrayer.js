/**
 * arrayer验证
 * @author Flydany
 */

function arrayer(name, type, validator) {

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
            case 'distinct': {
                return this.distinct();
            } break;
        }

        return this.is_array();
    }

    /**
     * 当与数组协作时，验证的字段中必须不能含有重复的值：
     *
     * @returns {boolean}
     */
    this.distinct = function() {
        if((new Set(this.value)).size == this.value.length) {
            return true;
        }
        this.addError('存在重复的值');
        return false;
    }

    /**
     * 验证的字段必须是一个 PHP array。
     *
     * @returns {boolean}
     */
    this.is_array = function() {
        if(utiler.formater(this.value, '[object array]')) {
            return true;
        }
        this.addError('存在重复的值');
        return false;
    }
}
