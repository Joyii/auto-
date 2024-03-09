/**
 * handler js
 * 辅助js函数定义
 */
// @created by flydany
// 2017-04-04 13:33:00

var handler = {

    confirm: function(_function, message, title) {
        $.confirm(_function, message, title);
    },
    /**
     * @name ajax 添加数据 弹层处理事件初始化
     * @param param 弹层配置参数
     * @param param.button  弹窗事件按钮
     * @param param.beforeAlert  弹窗前函数校验
     * @param param.content  弹出框对象
     * @param param.src  如果弹出框内容不存在则为弹出frame
     * @param param.area  弹出框大小
     * @param param.afterAlert  弹出后执行函数
     */
    dialogs: function(param) {
        $.each($(param.button), function() {
            $(this).bind('click', function() {
                param.mthis = this;
                handler.dialog(param);
            });
        });
    },
    dialog: function(param) {
        // 如果存在事件触发前调用的函数，调用函数
        if (utiler.call(param.beforeAlert, param) === false) {
            return false;
        }
        var url = param.container;
        if(utiler.empty(url)) {
            url = $(param.mthis).attr('data-container');
        }
        if(utiler.empty(url)) {
            var url = param.src ? param.src : $(param.mthis).attr('data-href');
            if($(param.tr).attr('data-id')) {
                url += ((url.indexOf('?') > 0) ? '&' : '?') + 'id=' + $(param.tr).attr('data-id');
            }
        }
        // 设置弹层类型
        var _options = {
            class: param.class ? param.class : '',
            close: param.close ? param.close : true,
        };

        $.dialog(param.title, url, _options, param.afterAlert);
    },
    closeDialog: function(element, _function) {
        $.closeDialog($(element).parents('.dialog').attr('tabindex'), _function);
    },
    /**
     * @name 渲染td中的分类标识
     * @param param 配置参数
     * @param param.beforeRender  渲染前函数校验
     * @param param.functionName  是否执行函数渲染
     * @param param.category  需要被渲染的DOM
     * @param param.select  值来源DOM
     * @param param.afterRender  渲染后执行函数
     */
    renderCategory: function(param) {
        $(param.category).each(function() {
            param.mthis = this;
            if (utiler.call(param.beforeRender, param) === false) {
                return false;
            }
            if ($.trim($(this).text()) == '') {
                $(this).html((param.default == undefined) ? '' : param.default);
            }
            else {
                var texts = $.trim($(this).text()).split(',');
                var _html = [];
                for(var i = 0; i < texts.length; ++i) {
                    var value = '', icon_class = '',
                        status = 'blue';
                    if(texts[i] == '') {
                        continue;
                    }
                    var text = texts[i];
                    if (param.functionName) {
                        value = utiler.call(param.functionName, text);
                    }
                    else {
                        // 判断是名字 / 选择器
                        if (/^[\w_-]{1,}$/.test(param.select)) {
                            param.select = '.search select[name=' + param.select + ']';
                        }
                        var option = param.select + ' option[value=' + text + ']';
                        if ($(option).length) {
                            value = $(option).text();
                            icon_class = $(option).attr('data-icon');
                            status = $(option).attr('data-status') ? $(option).attr('data-status') : '';
                        }
                        else {
                            value = '--';
                        }
                    }
                    _html.push(value);
                }
                $(this).html(_html.join(param.splite ? param.splite : ''));
            }
            if (utiler.call(param.afterRender, param) === false) {
                return false;
            }
        });
    },
    /**
     * @name 单条操作
     * @param param 配置参数
     * @param param.button  操作触发按钮
     * @param param.url  数据提交地址
     */
    single: function(param) {
        if(param.button) {
            $(param.button).each(function() {
                $(this).bind('click', function() {
                    handler.immediately(param);
                });
            });
        }
        else {
            handler.immediately(param);
        }
        return true;
    },
    immediately: function(param, mthis) {
        // 初始化配置参数
        if(utiler.empty(param.data)) {
            param.data = {};
        }
        if(mthis) {
            param.mthis = mthis;
        }
        if (utiler.call(param.beforeRequest, param) === false) {
            return false;
        }
        if(param.mthis) {
            param.url = param.url ? param.url : $(param.mthis).attr('data-href');
            param.method = param.method ? param.method : $(param.mthis).attr('data-method');
            param.tr = $(param.mthis).parents(param.trKey ? param.trKey : 'tr');
            if(param.tr && param.tr.length) {
                var id = $(param.tr).attr('data-id');
                if (id >= 0) {
                    param.data.id = id;
                }
                param.table = $(param.mthis).parents(param.tableKey ? param.tableKey : 'table');
            }
            if ($(param.mthis).data()) {
                for (var name in $(param.mthis).data()) {
                    if($.inArray(name, ['id', 'table', 'href']) >= 0) {
                        continue;
                    }
                    param.data[name] = $(param.mthis).attr('data-' + name);
                }
            }
            if($(param.mthis).attr('data-form')) {
                $.extend(true, param.data, utiler.parameters($($(param.mthis).attr('data-form')).find('input, select, textarea')));
            }
        }
        if(param.mthis == undefined) {
            param.mthis = mthis ? mthis : $('body');
        }
        // 确认弹窗
        if(param.isConfirm == false || param.isConfirm == 'false') {
            handler.request(param);
        }
        else {
            handler.confirm(function() {
                handler.request(param);
            }, param.message, param.title);
        }
    },
    /**
     * @name 批量操作
     * @param param 配置参数
     * @param param.button  操作触发按钮
     * @param param.url  数据提交地址
     */
    multiple: function(param) {
        $(param.button).bind('click', function() {
            // 初始化配置参数
            param.mthis = this;
            param.table = $(this).attr('data-table');
            if (utiler.call(param.beforeRequest, param) == false) {
                return false;
            }
            // 校验是否存在删除数据
            var checkbox = $(param.table).find('input[type=checkbox].list:checked');
            var checkboxLength = $(checkbox).length;
            if (checkboxLength <= 0) {
                $.danger((param.title ? param.title : 'batch operation') + ' need select the line which you want to operator');
                return false;
            }
            // 初始化请求参数
            if(param.data == undefined) {
                param.data = {};
            }
            param.data.ids = [];
            for (var i = 0; i < checkboxLength; ++i) {
                param.data.ids.push($(checkbox).eq(i).val());
            }
            if ($(this).data()) {
                for (var name in $(this).data()) {
                    if($.inArray(name, ['id', 'table', 'href']) >= 0) {
                        continue;
                    }
                    param.data[name] = $(this).attr('data-' + name);
                }
            }
            param.tr = $(checkbox).parents(param.trKey ? trKey.trKey : 'tr');
            param.url = param.url ? param.url : $(this).attr('data-href');
            param.method = param.method ? param.method : $(param.mthis).attr('data-method');
            // 确认弹窗
            handler.confirm({ functionName: handler.request, param: param }, param.title ? param.title : 'batch operation');
            return true;
        });
    },
    /**
     * @name 执行操作
     * @param param 配置参数
     * @param param.data  操作提交数据
     * @param param.url  数据提交地址
     * @param param.mthis  数据提交按钮
     * @param param.isKeep int 是否保留 操作行的数据
     * @param param.isAlert int 是否提示
     * @param param.isSuccessAlert int 工程是否提示
     * @param param.table  对应表单
     * @param param.tr  对应行
     */
    request: function(param) {
        if (utiler.call(param.beforePost, param) === false) {
            return false;
        }
        var processData = true;
        var contentType = 'application/x-www-form-urlencoded';
        // 添加系统参数
        if(utiler.formater(param.data, '[object string]')) {
            var data = '';
            data = param.data + '&_token=' +  $('meta[name="csrf-token"]').attr('content');
        }
        else {
            $.extend(true, param.data, { _token: $('meta[name="csrf-token"]').attr('content') });
            // 包含file文件
            if(utiler.exists(param.data, 'include_file')) {
                contentType = false;
                processData = false;
                var data = new FormData();
                for(var i in param.data) {
                    if(i == 'include_file') {
                        continue;
                    }
                    // 数组特殊处理
                    if(!utiler.formater(param.data[i], '[object array]')) {
                        data.append(i, param.data[i]);
                        continue;
                    }
                    param.data[i].forEach(element => {
                        data.append(i + '[]', element)
                    })
                }
            }
            // 不包含file文件
            else {
                var data = param.data;
            }
        }
        if(!(param.isShadow == false || param.isShadow == 'false')) {
            // 加载弹层
            var dialog = $.loading();
        }
        var text;
        if(param.loading) {
            text = $(param.loading).html();
            $(param.loading).html('<i class="fa fa-spinner fa-spin fa-fw"></i>loading...');
        }
        // 保存数据
        $.ajax({
            url: param.url,
            type: param.method ? param.method : 'POST',
            data: data,
            async: (param.isAsync || param.isAsync === undefined) ? true : false,
            processData: processData,
            contentType: contentType,
            cache: false,
            dataType: 'json',
            // ajax请求完毕之后执行(失败成功都会执行)
            complete: function() {
                if(!(param.isShadow == false || param.isShadow == 'false')) {
                    // 加载弹层
                    $.loaded(dialog);
                }
                // 如果存在事件触发后调用的函数，调用函数
                utiler.call(param.afterPost, param);
            },
            // 加载信息异常提示
            error: function() {
                if(param.loading) {
                    $(param.loading).html(text);
                }
                if(!(param.isAlert == false || param.isAlert == 'false')) {
                    $.danger('请求异常，请稍后重试！');
                }
                utiler.call(param.afterFail, param);
            },
            // 数据加载成功处理方法
            success: function(ret) {
                if(param.loading) {
                    $(param.loading).html(text);
                }
                param.response = ret;
                if (param.response.code == utiler.SuccessCode) {
                    if(!(param.isSuccessAlert == false || param.isSuccessAlert == 'false')) {
                        $.success(param.response.message ? param.response.message : (param.title ? param.title : 'operation') + ' successful');
                    }
                    if(param.isKeep == false || param.isKeep == 'false') {
                        $(param.tr).remove();
                    }
                    if ($(param.table).find('tbody').length) {
                        if ( ! $(param.table).find('tbody tr').length) {
                            $(param.table).find('tbody').html('<tr><td class="first" colspan="' + $(param.table).find('thead th').length + '"><i class="icon-ban-circle"></i> 数据已被清空~</td></tr>');
                        }
                    }
                    else {
                        if (param.trKey && ! $(param.table).find(param.trKey).length) {
                            var _html = [];
                            _html.push('<div class="empty">');
                            _html.push('<i class="bi-funnel-fill"></i>');
                            _html.push('<h5>数据已被清空</h5><p>');
                            _html.push('<a class="btn btn-danger" href="/home"><span class="btn-label"><i class="bi-search"></i></span>前往首页</a>');
                            _html.push('<a class="btn btn-danger ms-5" href="/product"><span class="btn-label"><i class="bi-search"></i></span>前往展台商城</a>');
                            _html.push('</p></div>');
                            $(param.table).html(_html.join(''));
                        }
                    }

                    // 如果存在事件触发后调用的函数，调用函数
                    utiler.call(param.requestSuccess, param);
                }
                else {
                    if(!(param.isAlert == false || param.isAlert == 'false')) {
                        $.danger(param.response.message);
                    }
                    utiler.call(param.requestFail, param);
                }
            }
        });
        return true;
    }
}
