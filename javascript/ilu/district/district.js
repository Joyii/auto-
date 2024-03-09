
'use strict';

$.fn.extend({
    district: function(config) {
        if(utiler.empty(config)) {
            config = {};
        }
        $.extend(true, config, { dom: this });
        return (new districts).init(config);
    }
});

function districts() {
    // 参数设置
    this.index = 0;
    this.dom = null;
    this.container = null;
    this.data = [];
    this.relate = [];
    this.selected = [];
    // 下探等级
    this.level = 3;
    this.clevel = 0;
    // 开始位置
    this.start = 0;
    this.cstart = 0;

    // 默认数据
    this.default = {
        text: '请选择地区',
        level: ['省份', '城市', '区县'],
    }

    this.parse_selected = function(selected) {
        if(utiler.empty(selected)) {
            selected = $(this.dom).val();
        }
        if(utiler.empty(selected)) {
            return true;
        }
        this.selected = selected.split('/');
    }

    this.init = function(param) {
        // 绑定的dom元素
        this.dom = param.dom;

        this.parse_selected(param.selected);

        // 数据
        this.data = dists;
        this.relate = dists_relate;

        // 替换默认值
        if(utiler.exists(param, 'default')) {
            for(var i in param.default) {
                this.default[i] = param.default[i];
            }
        }
        this.level = this.default.level.length;

        // 结构
        this.render_struct();

        // 填充地区
        this.cstart = 0;
        this.clevel = 0;
        this.render();

        this.trigger();
    }

    // 填充地区
    this.render = function() {
        if(this.clevel >= this.level) {
            this.close();
            return false;
        }
        var pannel = $(this.container).find('.pannel.level-' + this.clevel);
        if(!$(pannel).length) {
            $(this.container).find('.bodyer').append('<ul class="pannel level-' + this.clevel + '" data-level="' + this.clevel + '"></ul>');

            pannel = $(this.container).find('.pannel.level-' + this.clevel);
        }
        else {
            $(pannel).nextAll().remove();
        }

        var select = null;
        if(utiler.exists(this.selected, this.clevel)) {
            select = this.selected[this.clevel];
            this.selected[this.clevel] = null;
        }

        // 移除
        $(pannel).find('li').remove();

        if(!utiler.exists(this.data, this.cstart)) {
            this.close();
            return true;
        }
        // 填充
        var first_selected = null;
        var _li = [];
        for(var i in this.data[this.cstart]) {
            var name = this.data[this.cstart][i];
            if(name == select) {
                first_selected = i;
            }
            _li.push('<li class="text-truncate" data-code="' + i + '" data-value="' + name + '" title="' + name + '">' + name + '</li>');
        }
        $(pannel).append(_li.join(''));

        // 切换层级展示
        this.levelshow();

        var districtClass = this;
        // 底部点击选中+切换
        $(pannel).find('li').bind('click', function() {
            if($(this).hasClass('active')) {
                if(districtClass.level == (parseInt($(this).parent('.pannel').attr('data-level')) + 1)) {
                    districtClass.close();
                }
                else {
                    if(!utiler.exists(districtClass.data, $(this).attr('data-code'))) {
                        districtClass.close();
                        return true;
                    }
                    districtClass.clevel = (parseInt($(this).parent('.pannel').attr('data-level')) + 1);
                    districtClass.cstart = $(this).attr('data-code');
                    // 切换层级展示
                    districtClass.levelshow();
                }
                return true;
            }

            // 剔除其他active并选中当前
            $(this).addClass('active').siblings('.active').removeClass('active');
            
            var pannel = $(this).parent('.pannel');
            // 设置当前层级
            districtClass.clevel = parseInt($(pannel).attr('data-level'));
            // 展示控制
            districtClass.reshow();

            // 重新计算起始规则
            if(districtClass.clevel + 1 >= this.level) {
                districtClass.close();
                return true;
            }
            districtClass.clevel += 1;
            districtClass.cstart = $(this).attr('data-code');

            districtClass.render();
        });
        
        // 第一次加载选中
        if(first_selected !== null) {
            $(pannel).find('li[data-code="' + first_selected + '"]').trigger('click');
        }
    }

    // 根据clevel显示插件状态
    this.levelshow = function() {
        $(this.container).find('span.level-' + this.clevel).addClass('active').siblings('.active').removeClass('active');
        $(this.container).find('.pannel.level-' + this.clevel).addClass('active show').siblings('.active').removeClass('active show');
    }

    // 绑定事件
    this.trigger = function() {
        var districtClass = this;

        // 头部点击切换
        $(this.container).find('.header span').bind('click', function() {
            if($(this).hasClass('active')) {
                return true;
            }
            
            // 如果存在pannel则切换，不存在略过
            var pannel = $(this).parents('.district').find('.pannel.level-' + $(this).attr('data-level'));
            if($(pannel).length) {
                $(pannel).addClass('active show').siblings('.active').removeClass('active show');

                $(this).parents('.district').find('span.level-' + $(this).attr('data-level')).addClass('active').siblings('.active').removeClass('active');    
            }
            return true;
        });

        // 展示隐藏
        $(this.container).find('.choiced').bind('click', function() {
            if($(this).parent('.district').hasClass('show')) {
                $(this).parent('.district').removeClass('show');
            }
            else {
                $(this).parent('.district').addClass('show');
            }
        });

        // 隐藏
        $(this.container).find('.close').bind('click', function() {
            // districtClass.clear();
            $(this).parents('.district').removeClass('show');
        });
    }

    // 打开
    this.open = function() {
        $(this.container).addClass('show');
    }
    // 关闭
    this.close = function() {
        $(this.container).removeClass('show');
    }

    // 展示
    this.reshow = function() {
        var liactive = $(this.container).find('.pannel li.active');
        if(!$(liactive).length) {
            this.clear();
            return true;
        }
        var _value = [];
        for(var i = 0; i <= this.clevel; ++i) {
            if(!$(this.container).find('.pannel.level-' + i + ' li.active').length) {
                break;
            }
            _value.push($(this.container).find('.pannel.level-' + i + ' li.active').attr('data-value'));
        }

        $(this.dom).val(_value.join('/'));
        $(this.container).find('.choiced').html('<span>' + _value.join('</span><span>') + '</span>');
    }

    // 组装html结构
    this.render_struct = function() {
        var _class = $(this.dom).attr('class').replace(/tabler/, '');
        // 外层div
        $(this.dom).wrap('<div class="district ' + _class + '"></div>');
        // 隐藏input
        $(this.dom).attr('hidden', 'hidden');
        this.container = $(this.dom).parent('.district');
        // 显示默认
        $(this.container).append('<div class="choiced text-truncate"><span></span></div>');
        this.clear();

        // 组装选择区域
        $(this.container).append('<div class="database shadow"><div class="header"><i class="close bi-x"></i></div><div class="bodyer"></div></div><span class="carets"></span>');

        for(var i in this.default.level) {
            $(this.container).find('.header').append('<span class="level-' + i + '" data-level="' + i + '">' + this.default.level[i] + '</span>');
        }
        $(this.container).find('.header span:first').addClass('active');
    }

    // 清除选中
    this.clear = function() {
        this.clevel = 0;
        this.cstart = 0;
        $(this.container).find('.choiced').html('<span class="text-gray">' + this.default.text + '</span>');
        $(this.dom).val('');
        // 重新渲染
        // this.render();
        $(this.container).find('.pannel:not(.level-0)').remove();
        $(this.container).find('.pannel.level-0').addClass('active');
        $(this.container).find('.pannel li').removeClass('active');
        $(this.container).find('span.level-0').addClass('active').siblings('.active').removeClass('active');
    }
};