/**
 * compare验证
 * @author Flydany
 */

function compare(name, type, validator) {

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
            case 'same': {
                return this.same();
            } break;
            case 'size': {
                return this.size_equal();
            } break;
            case 'confirmed': {
                return this.confirmed();
            } break;
            case 'different': {
                return this.different();
            } break;
        }
    }

    /**
     * 给定字段必须与验证的字段匹配。
     *
     * @returns {boolean}
     */
    this.same = function() {
        if(this.value == this.validator.getValue(this.format)) {
            return true;
        }
        this.addError('不匹配');
        return false;
    }

    /**
     * 验证的字段必须具有与给定值匹配的大小。对于字符串来说，value 对应于字符数。对于数字来说，value 对应于给定的整数值。
     * 对于数组来说， size 对应的是数组的 count 值。对文件来说，size 对应的是文件大小（单位 kb ）。
     *
     * @returns {boolean}
     */
    this.size_equal = function() {
        if(this.value.length == this.format) {
            return true;
        }
        this.addError('大小不匹配');
        return false;
    }

    /**
     * 验证的字段必须能够和 foo_confirmation 字段相匹配。
     * 比如，如果验证的字段是 password，相应的 password_confirmation 字段必须在输入中被提供且与 password 相匹配。
     *
     * @returns {boolean}
     */
    this.confirmed = function() {
        this.format = name + '_confirmation';
        return this.same();
    }

    /**
     * 验证的字段必须与给定的字段不同。
     *
     * @returns {boolean}
     */
    this.different = function() {
        if(this.value != this.validator.getValue(this.format)) {
            return true;
        }
        this.addError('与指定字段不能相同');
        return false;
    }
}
