/**
 * email验证
 * @author Flydany
 */

var validate = function() {

    // 错误提示
    this.tips = null;
    // 状态
    this.code = null;
    this.message = [];

    // 设置规则
    this.setConfiguration = function(config) {
        this.format = config;
    }

    // 数据校验配置
    this.name;
    this.value;

    // 设置值
    this.setValue = function(value) {
        this.value = value;
    }

    // 设置值
    this.setStatus = function(code, message) {
        this.code = code;
        this.message = message;
    }

    // 校验
    this.addError = function(message) {
        this.message.push(message);
    }

    this.errors = function () {
        return this.message.join('、');
    }
}