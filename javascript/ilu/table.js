/**
 * table-template js
 * TABLE 模版 公用js函数定义
 */
// @created by flydany
// 2017-04-02 20:00:00

'use strict';

$.fn.extend({
    table: function(config) {
        config.container = '#' + $(this).attr('id');
        config.search = $(this).attr('data-searcher');

        $(config.container).attr('data-index', config.index);
        $(config.search).attr('data-index', config.index);

        return (new table).init(config);
    }
});

var table = function() {
    // ajax 请求地址
    // er 表示容器
    this.url = '';
    this.method = 'POST';
    this.param = {};
    this.baseParam = {};
    // 请求参数容器
    this.searcher = {};
    this.searchClass = '.tabler';
    // 渲染容器
    this.container = '';
    this.readyCall = true;
    // 请求页数
    this.page = 1;
    this.pageSize = '';
    // 排序
    this.order = '';
    // 数据请求之后数据存储
    this.primary_key = 'id';
    this.response = {};
    this.lister = [];
    this.paginator = {current: 1, last: 0, total: 0};
    this.properties = [];
    this.operators = [];
    this.operateAlign = '';
    // POST请求数据之前调用的函数
    // format [[function name, param]]
    this.triggers = {
        beforePost: [],
        beforeRender: [],
        requestSuccess: [],
        afterPost: [],
    };
    /**
     * @name 添加请求前函数调用
     * @param functionName
     * @return this object
     */
    this.addTrigger = function(event, functionName) {
        this.triggers[event].push(functionName);
        return this;
    }
    /**
     * @name 设置系统配置 url
     * @param url 请求的地址
     * @return this object
     */
    this.setUrl = function(url) {
        this.url = url;
        return this;
    }
    this.setBaseParam = function(param) {
        this.baseParam = param;
    }
    this.setParams = function(param) {
        this.param = param;
    }
    this.addParams = function(params) {
        for(var k in params) {
            this.addParam(k, params[k]);
        }
        return this;
    }
    this.addParam = function(name, value) {
        if(name.indexOf('[]') > 0) {
            if(this.param[name] === undefined) {
                this.param[name] = [];
            }
            this.param[name].push(value);
        }
        else {
            this.param[name] = value;
        }
        return this;
    }
    // @name 设置系统配置 searcher 搜索条件容器
    this.setSearcher = function(search) {
        this.searcher = search;
        this.searchButton = $(search).find('.filter-button');
        return this;
    }
    // @name 设置系统配置 container 最终渲染容器
    this.setContainer = function(container) {
        this.container = container;
        return this;
    }
    // @name 设置字段列表
    this.setProperties = function(properties) {
        this.properties = properties;
        return this;
    }
    // @name 设置操作选项
    this.setOperators = function(operators) {
        this.operators = operators;
        return this;
    }
    this.setOperateAlign = function(operate_align) {
        this.operateAlign = operate_align;
        return this;
    }
    this.setPrimaryKey = function(primary_key) {
        if(utiler.empty(primary_key)) {
            return this;
        }
        this.primary_key = primary_key;
        return this;
    }
    this.setReadyCall = function(readyCall) {
        this.readyCall = (['true', '1', true, 1, undefined, 'undefined'].indexOf(readyCall) >= 0) ? true : false;
        return this;
    }
    this.isReadyCall = function() {
        return this.readyCall;
    }
    this.setOrderBy = function(order, by) {
        this.order = order + '|' + by;
        return this;
    }
    this.setPage = function(page) {
        this.page = page;
        return this;
    }
    this.setLister = function(lister) {
        this.lister = lister;
        return this;
    }
    this.setPaginator = function(pager) {
        this.paginator = pager;
        return this;
    }
    // @name 显示加载异常信息描述
    this.error = function(message) {
        this.status = 'error';
        this.message = message;

        var _html = [];
        _html.push('<tr class="text-danger"><td colspan="' + (this.properties.length + (!utiler.empty(this.operators) ? 1 : 0)) + '">');
        _html.push('<i class="bi bi-alert-triangle"></i>');
        _html.push(this.message);
        _html.push('</td></tr>');
        $(this.container).find('table tbody').html(_html.join(''));

        var _load = [];
        _load.push('<i class="bi bi-search"></i>筛选');
        $(this.searcher).find('.filter-button').removeClass('disabled').html(_load.join(''));
    }
    // @name 表格数据加载中动画、隐藏搜索button
    this.loading = function() {
        this.status = 'loading';

        var _html = [];
        _html.push('<tr><td colspan="' + (this.properties.length + (!utiler.empty(this.operators) ? 1 : 0)) + '">');
        _html.push('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>');
        _html.push('Loading...');
        _html.push('</td></tr>');
        $(this.container).find('table tbody').html(_html.join(''));

        var _load = [];
        _load.push('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>');
        _load.push('Loading...');
        $(this.searcher).find('.filter-button').addClass('disabled').html(_load.join(''));
    }
    // @name 显示搜索button
    this.loaded = function() {
        this.status = 'loaded';

        var _load = [];
        _load.push('<i class="bi bi-search"></i>筛选');
        $(this.searcher).find('.filter-button').removeClass('disabled').html(_load.join(''));
    }

    this.init = function(param) {
        // 初始化配置参数
        this.setUrl(param.url);
        this.setBaseParam(param.baseParam);
        // 设置查询参数、按钮
        this.setSearcher(param.search);
        // 设置查询参数、按钮
        this.setContainer(param.container);
        // 设置主键编号
        this.setPrimaryKey(param.primary_key);
        this.setProperties(param.properties).setOperators(param.operators).setOperateAlign(param.operate_align);
        // 添加请求前数据处理函数
        var events = ['beforePost', 'beforeRender', 'requestSuccess', 'afterPost'];
        for(var i in events) {
            if(utiler.exists(param, events[i])) {
                this.addTrigger(events[i], param[events[i]]);
            }
        }
        this.setReadyCall(param.readyCall);
        // 初始完毕后，是否直接发起请求
        if(this.isReadyCall()) {
            this.load();
        }
        return this;
    }
    this.reload = function() {
        this.setPage(1);
        this.load();
    }
    // @name JSON 请求加载数据
    this.load = function() {
        // 初始化请求数据
        this.initParams();
        // 请求前函数调用
        this.trigger('beforePost');
        // 加载动画效果
        this.loading();
        // 加载数据
        var tablerClass = this;
        $.ajax({
            url: this.buildUrl(),
            type: this.method,
            data: this.param,
            dataType: 'json',
            // 加载信息异常提示
            error: function() {
                // $.danger('系统异常，请稍后重试！');
                tablerClass.error('系统异常，请稍后重试！');
            },
            // 数据加载成功处理方法
            success: function(response) {
                // 解析返回值
                tablerClass.response = response;
                if(tablerClass.response.code == utiler.SuccessCode) {
                    tablerClass.setLister(tablerClass.response.lister);
                    // 分页填充到page容器
                    if(tablerClass.response.paginator) {
                        tablerClass.setPaginator(tablerClass.response.paginator);
                    }
                    // 渲染数据之前调用
                    tablerClass.trigger('beforeRender');
                    // 渲染数据
                    tablerClass.render();
                    // 请求成功函数调用
                    tablerClass.trigger('requestSuccess');
                    tablerClass.loaded();
                }
                else {
                    // $.danger(tablerClass.response.message);
                    tablerClass.error(tablerClass.response.message);
                }
                // 请求后函数调用
                tablerClass.trigger('afterPost');
            }
        });
    }
    // 获取请求地址
    this.buildUrl = function() {
        return this.url;
        var split = '?';
        if(this.url.indexOf('?') >= 0) {
            split = '&';
        }
        return this.url + split + 'page=' + this.page;
    }
    // 初始化参数
    this.initParams = function() {
        this.setParams(this.baseParam ? this.baseParam : {});
        this.addParam('page', this.page);
        this.addParam('order', this.order);
        this.addParam('_token', $('meta[name=csrf-token]').attr('content'));
        if(utiler.empty(this.searcher)) {
            return this;
        }
        this.addParams(utiler.parameters($(this.searcher).find(this.searchClass)));
        if(utiler.exists(this.param, 'pageSize')) {
            this.pageSize = this.param['pageSize'];
        }
        else {
            this.addParam('pageSize', this.pageSize);
        }
        return this;
    }
    // 执行钩子函数
    this.trigger = function(event, isBreak) {
        var functions = this.triggers[event];
        if(utiler.empty(functions)) {
            return true;
        }
        var functionLength = functions.length;
        for(var i = 0; i < functionLength; ++i) {
            if(utiler.call(functions[i], this) !== true && isBreak) {
                return false;
            }
        }
        return true;
    }
    this.render = function() {

        var _table = this.render_table();
        $(this.container).find('table tbody').html(_table);

        var _page = builder.page_render(this.paginator);
        $(this.container).find('.page-render').html(_page);

        this.page_event();
    }
    this.render_table = function() {
        var _html = [];
        if(utiler.empty(this.lister)) {
            _html.push('<tr class="text-danger"><td colspan="' + (this.properties.length + (!utiler.empty(this.operators) ? 1 : 0)) + '">');
            _html.push('<i class="bi bi-exclamation-triangle-fill"></i>');
            _html.push('哇，此处没有发现数据！');
            _html.push('</td></tr>');
            return _html.join('');
        }
        for (var i = 0; i < this.lister.length; ++i) {
            _html.push(this.render_one(this.lister[i]));
        }

        return _html.join('');
    }
    // 单条数据创建
    this.render_one = function(item) {
        var _html = [];

        var id = item[this.primary_key];
        _html.push('<tr id="tr-' + id + '" data-id="' + id + '">');
        // 数据列渲染
        for(var j = 0; j < this.properties.length; ++j) {
            var property = this.properties[j];
            _html.push('<td>');

            switch (property.type) {
                case 'foreach': {
                    _html.push(builder.render_foreach(item, property));
                } break;
                case 'array': {
                    _html.push(builder.render_array(item, property));
                } break;
                case 'images': {
                    _html.push(builder.render_images(item, property));
                } break;
                case 'image': {
                    _html.push(builder.render_image(item, property));
                } break;
                case 'replace': {
                    _html.push(builder.render_replace(item, property, this.searcher));
                } break;
                case 'html': {
                    _html.push(builder.render_html(item, property));
                } break;
                default: {
                    _html.push(builder.render(item, property));
                }
            }

            _html.push('</td>');
        }

        // 操作列渲染
        if(!utiler.empty(this.operators)) {
            _html.push('<td>');
            _html.push(this.build_operate(item));
            _html.push('</td>');
        }
        _html.push('</tr>');

        return _html.join('');
    }
    /**
     * 组织操作
     * @param item
     * @returns {string}
     */
    this.build_operate = function(item) {
        var _operate = [];

        if(this.operateAlign == 'vertical') {
            _operate.push('<div class="btn-group-vertical btn-group-sm">');
        }
        else {
            _operate.push('<div class="btn-group btn-group-sm">');
        }
        for(var k = 0; k < this.operators.length; ++k) {
            var operate = this.operators[k];
            if(utiler.exists(operate, 'when')) {
                if(!utiler.call(utiler.replace(operate['when'], item), item)) {
                    continue;
                }
            }
            switch (operate.handler) {
                case 'request': {
                    _operate.push(builder.handler_request(item, operate));
                } break;
                case 'dialog': {
                    _operate.push(builder.handler_dialog(item, operate, k));
                } break;
                case 'javascript': {
                    _operate.push(builder.handler_javascript(item, operate));
                } break;
                default: {
                    _operate.push(builder.handler_link(item, operate));
                }
            }
        }
        _operate.push('</div>');

        return _operate.join('');
    }
    this.page_event = function() {
        var tableClass = this;
        $(this.container).find('.page-render a.page-link').bind('click', function() {
            if(utiler.empty($(this).attr('data-page'))) {
                return true;
            }
            tableClass.setPage($(this).attr('data-page'));
            tableClass.load();
        });
    }
    /**
     * 重建
     * @param mthis
     * @param item
     */
    this.rebuild_one = function (param) {
        if(utiler.empty(param.response.item)) {
            return false;
        }
        this.append_one(param.response.item);
    }
    this.rebuild_operate = function (item) {
        var rebuild = '#tr-' + item[this.primary_key];
        var origianl = this.merge_item(item);
        var _html = this.build_operate(origianl);
        $(rebuild).find('td:last').html(_html);
    }
    // 追加
    this.append_one = function(item) {
        if(utiler.empty(item)) {
            return false;
        }
        var dom = $(this.container).find('tr#tr-' + item[this.primary_key]);
        var origianl = this.merge_item(item);
        var _html = this.render_one(origianl);
        // 已存在
        if($(dom).length) {
            $(dom).after(_html);
            $(dom).eq(0).remove();
            return true;
        }
        if($(this.container).find('table tbody tr td').length <= 1) {
            $(this.container).find('table tbody').html(_html);
        }
        else {
            $(this.container).find('table tbody').prepend(_html);
        }
    }
    this.merge_item = function(item) {
        var origianl = {};
        for(var i in this.lister) {
            if(this.lister[i][this.primary_key] == item[this.primary_key]) {
                origianl = this.lister[i];
                break;
            }
        }
        if(utiler.empty(origianl)) {
            this.lister.push(item);
            return item;
        }
        $.extend(true, origianl, item);
        this.lister[i] = origianl;
        return origianl;
    }
}
