/**
 * builder js
 * builder 模版 公用js函数定义
 */
// @created by flydany
// 2021-06-10 09:43:00

var builder = {

    render_foreach: function(item, property) {
        var _foreach = [];
        _foreach.push('<div class="' + utiler.replace(property.class, item) + '">');
        let ind = 0;
        for(var i in item[property.name]) {
            var current = item[property.name][i];
            current['_index'] = ind++;
            current['_key'] = i;
            _foreach.push(utiler.replace(property.foreach, current));
        }
        _foreach.push('</div>');
        return _foreach.join('');
    },
    render_array: function(item, property) {
        var _array = [];
        _array.push('<div class="btn-group">');
        for(var i = 0; i < item[property.name].length; ++i) {
            var current = item[property.name][i];
            _array.push('<button class="btn btn-outline-secondary" type="button">' + current + '</button>');
        }
        _array.push('</div>');
        return _array.join('');
    },
    render_images: function (item, property) {
        var _images = [];

        _images.push('<div><div class="image-pills wrap sm"><div class="body">');
        var images = utiler.finder(item, property.name);
        for(var i = 0; i < images.length; ++i) {
            _images.push('<img class="rounded-circle" src="' + utiler.replace(property.src, { single: images[i] }) + '">');
        }
        _images.push('</div></div></div>');

        return _images.join('');
    },
    render_image: function(item, property) {
        var _image = [];

        if(utiler.exists(property.url)) {
            var target = '';
            if(utiler.exists(property.target)) {
                target = ' target="' + utiler.replace(property.target, item) + '"';
            }
            _image.push('<a href="' + utiler.replace(property.url, item) + '"' + target + '>');
        }
        var _property = this.properties(property.properties, item);
        var _src = utiler.replace(property.src, item);
        if(_src[_src.length - 1] == '/') {
            _image.push('--');
        }
        else {
            // 获取缩略图
            if(utiler.exists(property, 'thumb')) {
                _src = utiler.thumb(_src, property.thumb);
            }
            _image.push('<img class="' + utiler.replace(property.class, item) + '" src="' + _src + '" width="' + property.width + '" height="' + property.height + '" ' + _property + '>');
        }
        if(utiler.exists(property.url)) {
            _image.push('</a>');
        }

        return _image.join('');
    },
    render_html: function(item, property) {
        var _html = [];

        _html.push('<div class="' + utiler.replace(property.class, item) + '">');
        if(utiler.exists(property.url)) {
            var target = '';
            if(utiler.exists(property.target)) {
                target = ' target="' + utiler.replace(property.target, item) + '"';
            }
            _html.push('<a href="' + utiler.replace(property.url, item) + '"' + target + '>');
        }
        _html.push(utiler.replace(property.html, item));
        if(utiler.exists(property.url)) {
            _html.push('</a>');
        }
        _html.push('</div>');

        return _html.join('');
    },
    render_replace: function(item, property, container) {
        var _render = [];

        if(utiler.exists(property.url)) {
            var target = '';
            if(utiler.exists(property.target)) {
                target = ' target="' + utiler.replace(property.target, item) + '"';
            }
            _render.push('<a href="' + utiler.replace(property.url, item) + '"' + target + '>');
        }
        if(utiler.empty(container)) {
            var value = $('select[name="' + property.name + '"] option[value="' + utiler.finder(item, property.name) + '"]').text();
        }
        else {
            var value = $(container).find('select[name="' + property.name + '"] option[value="' + utiler.finder(item, property.name) + '"]').text();
        }
        if(utiler.exists(property, 'format')) {
            value = utiler.call(property.format, value, property.args);
        }
        _render.push('<span class="' + utiler.replace(property.class, item) + '">' + value + '</span>');
        if(utiler.exists(property.url)) {
            _render.push('</a>');
        }

        return _render.join('');
    },
    render: function(item, property) {
        var _default = [];

        if(utiler.exists(property.url)) {
            var target = '';
            if(utiler.exists(property.target)) {
                target = ' target="' + utiler.replace(property.target, item) + '"';
            }
            _default.push('<a href="' + utiler.replace(property.url, item) + '"' + target + '>');
        }
        var value = utiler.finder(item, property.name, '');
        if(utiler.exists(property.format)) {
            value = utiler.call(property.format, value, property.args);
        }
        var _property = this.properties(property.properties, item);
        _default.push('<span class="d-block ' + utiler.replace(property.class, item) + '" ' + _property + '>' + value + '</span>');
        if(utiler.exists(property.url)) {
            _default.push('</a>');
        }

        return _default.join('');
    },

    /**
     * 渲染操作中的Request操作事件
     *
     * @param item
     * @param operate
     */
    handler_request: function (item, operate) {
        operate = $.extend({ class: 'btn-primary', type: 'post', keep: 'true', icon: 'pencil-square' }, operate);
        var _request = [];

        _request.push('<button class="btn ' + (operate.class ? operate.class : 'btn-primary') + '" type="button"');
        _request.push(this.properties(operate.properties, item));
        _request.push(' onclick="utiler.request(this);">');
        _request.push('<i class="bi-' + operate.icon + '"></i>');
        _request.push(operate.title);
        _request.push('</button>');

        return _request.join('');
    },
    /**
     * 渲染操作中的Dialog操作事件
     *
     * @param item
     * @param operate
     */
    handler_dialog: function (item, operate, index) {
        operate = $.extend({ class: 'btn-primary', href: '', dialog_class: '', icon: 'pencil-square' }, operate);
        var _dialog = [];

        _dialog.push('<button class="btn ' + (operate.class ? operate.class : 'btn-primary') + '" type="button"');
        _dialog.push(' data-index="' + index + '"');
        _dialog.push(this.properties(operate.properties, item));
        _dialog.push(' onclick="utiler.dialog(this);">');
        _dialog.push('<i class="bi-' + operate.icon + '"></i>');
        _dialog.push(operate.title);
        _dialog.push('</button>');

        return _dialog.join('');
    },
    /**
     * 渲染操作中的直接跳转操作事件
     *
     * @param item
     * @param operate
     */
    handler_link: function(item, operate) {
        _operate = $.extend({ class: 'btn-primary', href: '', target: '_blank', icon: 'pencil-square' }, operate);
        var _link = [];

        _link.push('<a class="btn ' + (_operate.class ? _operate.class : 'btn-primary') + '" type="button"');
        _link.push(' href="' + utiler.replace(_operate.href, item) + '"');
        _link.push(' target="' + _operate.target + '">');
        _link.push('<i class="bi-' + _operate.icon + '"></i>');
        _link.push(_operate.title);
        _link.push('</a>');

        return _link.join('');
    },
    /**
     * js事件绑定
     * @param item
     * @param operate
     * @returns {string}
     */
    handler_javascript: function (item, operate) {
        var _javascript = [];

        _javascript.push('<button class="btn ' + (operate.class ? operate.class : 'btn-primary') + '" type="button"');
        _javascript.push(this.properties(operate.properties, item));
        _javascript.push('>');
        _javascript.push('<i class="bi-' + operate.icon + '"></i>');
        _javascript.push(operate.title);
        _javascript.push('</button>');

        return _javascript.join('');
    },

    /**
     * 下拉框
     *
     * @param name
     * @param options
     * @param selected
     * @param properties
     * @returns {string}
     */
    form_select: function(name, options, selected, properties) {
        var _html = [];
        if(!utiler.formater(selected, '[object array]')) {
            selected = [selected];
        }
        if(utiler.exists(properties, 'class')) {
            properties['class'] = 'form-select ' + properties['class'];
        }
        else {
            properties['class'] = 'form-select';
        }
        _property = this.properties(properties, null);
        _html.push('<select name="' + name + '" ' + _property + '>');
        if(utiler.exists(properties, '_empty')) {
            _html.push('<option>' + properties['_empty'] + '</option>');
        }
        for(var value in options) {
            if(utiler.in(value, selected)) {
                _html.push('<option value="' + value + '" selected>' + options[value] + '</option>');
            }
            else {
                _html.push('<option value="' + value + '">' + options[value] + '</option>');
            }
        }
        _html.push('</select>');

        return _html.join('');
    },

    /**
     * 输入框
     *
     * @param name
     * @param value
     * @param properties
     * @returns {string}
     */
    form_input: function(name, value, _properties) {
        if(value === null) {
            value = '';
        }
        var _html = [];

        properties = utiler.clone(_properties);
        if(utiler.exists(properties, 'class')) {
            properties['class'] = 'form-control ' + properties['class'];
        }
        else {
            properties['class'] = 'form-control';
        }
        properties['type'] = utiler.exists(properties, 'type') ? properties['type'] : 'text';
        _property = this.properties(properties, null);
        _html.push('<input name="' + name + '" value="' + value + '" ' + _property + '>');

        return _html.join('');
    },

    /**
     * 隐藏输入框hidden
     *
     * @param name
     * @param value
     * @param properties
     * @returns {string}
     */
    form_hidden: function(name, value, properties) {

        properties['type'] = 'hidden';

        return this.form_input(name, value, properties);
    },

    form_switch: function(name, value, current, _properties) {

        var title = '';
        properties = utiler.clone(_properties);
        // 样式定义
        if(utiler.exists(properties, 'class')) {
            properties['class'] = 'form-check-input ' + properties['class'];
        }
        else {
            properties['class'] = 'form-check-input';
        }
        properties['type'] = utiler.exists(properties, 'type') ? properties['type'] : 'checkbox';
        if(current == value) {
            properties['checked'] = 'checked';
        }

        var _html = [];
        _html.push('<div class="form-check form-switch">');
        properties['id'] = 'check-' + Math.random();
        _html.push(this.form_input(name, value, properties));
        _html.push('<label class="form-check-label" for="' + properties['id'] + '">' + title + '</label>');
        _html.push('</div>');

        return _html.join('')
    },

    /**
     * 渲染分页
     */
    page_render: function(paginator) {
        var _page = [];

        _page.push('<ul class="pagination">');
        _page.push('<li class="page-item"><a class="page-link">总计：' + paginator.total + '条记录</a></li>');
        if(paginator.current == 1) {
            _page.push('<li class="page-item disabled"><a class="page-link">首页</a></li>');
        }
        else {
            _page.push('<li class="page-item"><a class="page-link" data-page="' + (parseInt(paginator.current) - 1) + '" href="javascript:;">上一页</a></li>');
        }
        if(paginator.current - 6 > 0) {
            _page.push('<li class="page-item"><a class="page-link" data-page="1" href="javascript:;">1</a></li>');
        }
        if(paginator.current - 7 > 0) {
            _page.push('<li class="page-item disabled"><a class="page-link">...</a></li>');
        }
        for (var page = 1; page <= 5; ++page) {
            var c_page = parseInt(paginator.current) - 6 + page;
            if(c_page < 1) {
                continue;
            }
            _page.push('<li class="page-item">');
            _page.push('<a class="page-link" data-page="' + c_page + '" href="javascript:;">' + c_page + '</a>');
            _page.push('</li>');
        }
        if(paginator.current > 1 && paginator.current < 6) {
            for (var page in paginator.current - 1) {
                _page.push('<li class="page-item">');
                _page.push('<a class="page-link" data-page="' + page + '" href="javascript:;">' + page + '</a>');
                _page.push('</li>');
            }
        }
        _page.push('<li class="page-item active"><a class="page-link">' + paginator.current + '</a></li>');
        if(paginator.last) {
            for (var page = 1; page <= (Math.min(5, Math.abs(paginator.last - paginator.current))); ++page) {
                var c_page = parseInt(paginator.current) + page;
                _page.push('<li class="page-item">');
                _page.push('<a class="page-link" data-page="' + c_page + '" href="javascript:;">' + c_page + '</a>');
                _page.push('</li>');
            }
        }
        if(parseInt(paginator.current) + 6 < paginator.last) {
            _page.push('<li class="page-item disabled"><a class="page-link">...</a></li>');
        }
        if(parseInt(paginator.current) + 5 < paginator.last) {
            _page.push('<li class="page-item"><a class="page-link" data-page="' + paginator.last + '" href="javascript:;">' + paginator.last + '</a></li>');
        }
        if(paginator.current == paginator.last || !paginator.last) {
            _page.push('<li class="page-item disabled"><a class="page-link">尾页</a></li>');
        }
        else {
            _page.push('<li class="page-item"><a class="page-link" data-page="' + (parseInt(paginator.current) + 1) + '" href="javascript:;">下一页</a></li>');
        }
        _page.push('</ul>');

        return _page.join('');
    },

    /**
     * 组装属性
     *
     * @param properties
     * @param prev
     * @returns {string}
     */
    properties: function(properties, item, prev = '') {
        if(utiler.empty(properties)) {
            return '';
        }
        var _html = [];
        for(var k in properties) {
            _html.push(prev + '' + k + '="' + utiler.replace(properties[k], item) + '"');
        }
        return _html.join(' ');
    }
}
