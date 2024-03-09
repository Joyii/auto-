/**
 * required验证
 * @author Flydany
 */

function required(name, type, validator) {

    this.name = name;
    this.type = type;
    this.validator = validator;

    validate.apply(this);

    // 校验
    this.validate = function() {
        switch(this.type) {
            case 'if': {
                return this._if();
            } break;
            case 'unless': {
                return this.unles();
            } break;
            case 'with': {
                return this.with();
            } break;
            case 'with.all': {
                return this.with_all();
            } break;
            case 'without': {
                return this.without();
            } break;
            case 'without.all': {
                return this.without_all();
            } break;
            default: {
                return this.required();
            }
        }
    }

    /**
     * 不能为空
     *
     */
    this.required = function() {
        if(this.value == undefined || (this.value + '').length == 0) {
            this.addError('不能为空');
            return false;
        }
        return true;
    }

    /**
     * format: anotherfiled,value
     * 验证字段在另一个字段等于指定值value时是必须的
     */
    this._if = function() {
        var format = this.format.split(',');
        var value = this.validator.getValue(format[0]);
        format.splice(0, 1);
        var compare = ',' + format.join(',') + ',';
        if(compare.indexOf(value) >= 0) {
            return this.required();
        }
        return true;
    }

    /**
     * format: anotherfiled,value
     * 除了 anotherfield字段等于value，验证字段不能空
     */
    this.unless = function() {
        var format = this.format.split(',');
        var value = this.validator.getValue(format[0]);
        if(value == format[1]) {
            return true;
        }
        return this.required();
    }

    /**
     * format: foo,bar,…
     * 验证字段只有在任一其它指定字段存在的话才是必须的
     */
    this.with = function() {
        var attributes = this.format.split(',');
        for(var i = 0; i < attributes.length; ++i) {
            if(utiler.exists(this.validator.getValue(attributes[i]))) {
                return this.required();
            }
        }
        return true;
    }

    /**
     * format: foo,bar,…
     * 验证字段只有在所有指定字段存在的情况下才是必须的
     */
    this.with_all = function() {
        var attributes = this.format.split(',');
        for(var i = 0; i < attributes.length; ++i) {
            if(utiler.empty(this.validator.getValue(attributes[i]))) {
                return true;
            }
        }
        return this.required();
    }

    /**
     * format: foo,bar,…
     * 验证字段只有当任一指定字段不存在的情况下才是必须的
     */
    this.without = function() {
        var attributes = this.format.split(',');
        for(var i = 0; i < attributes.length; ++i) {
            if(utiler.empty(this.validator.getValue(attributes[i]))) {
                return this.required();
            }
        }
        return true;
    }

    /**
     * format: foo,bar,…
     * 验证字段只有当所有指定字段不存在的情况下才是必须的
     */
    this.without_all = function() {
        var attributes = this.format.split(',');
        for(var i = 0; i < attributes.length; ++i) {
            if(utiler.exists(this.validator.getValue(attributes[i]))) {
                return true;
            }
        }
        return this.required();
    }
}
