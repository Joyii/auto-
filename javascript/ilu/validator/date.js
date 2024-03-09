/**
 * number校验
 * @author Flydany
 */

function date(name, type, validator) {

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
            case 'formater': {
                return this.formater();
            } break;
            case 'after': {
                return this.after();
            } break;
            case 'after.equal': {
                return this.after_equal();
            } break;
            case 'before': {
                return this.before();
            } break;
            case 'before.equal': {
                return this.before_equal();
            } break;
            case 'equals': {
                return this.equal();
            } break;
        }

        return this.date();
    }

    /**
     * 按指定格式校验日期
     *
     * @returns {boolean}
     */
    this.formater = function() {
        var replaces = [
            [/Y/, 'yyyy'], [/m/, 'MM'], [/d/, 'dd'], [/H/, 'HH'], [/i/, 'mm'], [/s/, 'ss']
        ];
        var pattern = this.format;
        for(var i = 0; i < replaces.length; ++i) {
            pattern = pattern.replace(replaces[i][0], replaces[i][1]);
        }
        pattern = pattern.replace(/[yMdHms]/g, '\\d');
        var pattern = new RegExp('^' + pattern + '$');
        if(pattern.test(this.value)) {
            return true;
        }
        this.addError('格式错误');
        return false;
    }

    /**
     * 验证的字段值必须是通过 PHP 函数 strtotime 校验的有效日期。
     *
     * @returns {boolean}
     */
    this.date = function() {
        // var date= new Date(Date.parse(this.value.replace(/-/g,   "/")));
        var r = this.value.match(/^(\d{1,4})(-|\/)(\d{1,2})\2(\d{1,2})$/);
        if(r) {
            var d = new Date(r[1], r[3]-1, r[4]);
            if(d.getFullYear() == r[1] && (d.getMonth()+1) == r[3] && d.getDate() == r[4]) {
                return true;
            }
        }
        this.addError('格式错误');
        return false;
    }

    /**
     * 验证的字段必须是给定日期后的值。这个日期将会通过 PHP 函数 strtotime 来验证。
     *
     * @returns {boolean}
     */
    this.after = function() {
        if( ! this.date()) {
            return false;
        }
        if(this.format === 'tomorrow') {
            this.format = this.getDate(1);
        } else {
            // 使用其他字段校验
            var another = this.validator.getValue(this.format);
            if(another) {
                this.format = another;
            }
        }
        if(this.value > this.format) {
            return true;
        }
        this.addError('必须在' + this.format + '之后');
        return false;
    }

    /**
     * 验证的字段必须等于给定日期之后的值。更多信息请参见 after 规则。
     * @returns {boolean}
     */
    this.after_equal = function() {
        if( ! this.date()) {
            return false;
        }
        if(this.format === 'tomorrow') {
            this.format = this.getDate(1);
        } else {
            // 使用其他字段校验
            var another = this.validator.getValue(this.format);
            if(another) {
                this.format = another;
            }
        }
        if(this.value >= this.format) {
            return true;
        }
        this.addError('必须大于等于' + this.format);
        return false;
    }

    /**
     * 验证的字段必须是给定日期之前的值。这个日期将会通过 PHP 函数 strtotime 来验证。
     *
     * @returns {boolean}
     */
    this.before = function() {
        if( ! this.date()) {
            return false;
        }
        if(this.format === 'tomorrow') {
            this.format = this.getDate(1);
        } else {
            // 使用其他字段校验
            var another = this.validator.getValue(this.format);
            if(another) {
                this.format = another;
            }
        }
        if(this.value < this.format) {
            return true;
        }
        this.addError('必须在' + this.format + '之前');
        return false;
    }

    /**
     * 验证的字段必须是给定日期之前或之前的值。这个日期将会使用 PHP 函数 strtotime 来验证。
     *
     * @returns {boolean}
     */
    this.before_equal = function() {
        if( ! this.date()) {
            return false;
        }
        if(this.format === 'tomorrow') {
            this.format = this.getDate(1);
        } else {
            // 使用其他字段校验
            var another = this.validator.getValue(this.format);
            if(another) {
                this.format = another;
            }
        }
        if(this.value <= this.format) {
            return true;
        }
        this.addError('必须小于等于' + this.format);
        return false;
    }

    /**
     * 验证的字段必须等于给定的日期。该日期会被传递到 PHP 函数 strtotime。
     *
     * @returns {boolean}
     */
    this.equal = function() {
        if( ! this.date()) {
            return false;
        }
        if(this.format === 'tomorrow') {
            this.format = this.getDate(1);
        } else {
            // 使用其他字段校验
            var another = this.validator.getValue(this.format);
            if(another) {
                this.format = another;
            }
        }
        if(this.value == this.format) {
            return true;
        }
        this.addError('必须等于' + this.format);
        return false;
    }

    /**
     * 获取day天之后的日期
     * @param day
     * @returns {string}
     */
    this.getDate = function(day) {
        var dd = new Date();
        dd.setDate(dd.getDate()+day);
        var y = dd.getFullYear();
        var m = dd.getMonth() + 1;
        var d = dd.getDate();
        return y + "-" + m + "-" + d;
    }
}
