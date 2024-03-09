
var dialog_loaded = true;
var dialogIndex = 10;
jQuery.danger = function(message) {

    return swal(message + '', '', 'error', {
        dangerMode: true,
        buttons: ['取消', '确定'],
    });
};
jQuery.warning = function(message) {

    return swal(message + '', '', 'warning', {
        dangerMode: true,
        buttons: ['取消', '确定'],
    });
};

jQuery.scrolling = function() {
    $("body").parent().css("overflow-y","auto");
};
jQuery.unscroll = function() {
    $("body").parent().css("overflow-y","hidden");
};

jQuery.success = function(message) {

    return swal(message + '', '', 'success', {
        dangerMode: true,
        buttons: ['取消', '确定'],
    });
};

jQuery.loading = function(_function) {
    return swal({
        content:  {
            element: 'div',
            attributes: {
                innerHTML: `<span></span><span></span><span></span><span></span><span></span>`,
            }
        },
        className: 'loading',
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: false,
    });
};
jQuery.loaded = function(_function) {
    swal.close();
    if(_function) {
        utiler.call(_function);
    }
}

jQuery.dialog = function(title, container, options, _function) {
    options = $.extend(true, {
        closeOnClickOutside: false,
        closeOnEsc: true,
        buttons: false,
        closable: true,
        class: ''
    }, options);
    var id = 'dialog-' + ++dialogIndex;
    jQuery.unscroll();
    if(utiler.formater(container, '[object string]') && (container.match(/^http/))) {
        $.loading();
        $.ajax({ url: container, type: 'get', async: true, success: function(response) {
                // 请求异常
                if(utiler.exists(response.code) && response.code != utiler.SuccessCode) {
                    $.danger(response.message);
                    return false;
                }
                $.loaded();
                $('body').append('<div class="dialog active ' + options.class + '" id="' + id + '" tabindex="' + dialogIndex + '" data-from="ajax"><div class="content"><div class="title">' + title + '<span class="close"><i class="bi-x"></i></span></div></div></div>');
                var js = response.match(/<script>([\s\S]*)<\/script>/);
                id = '#' + id + ' > .content';
                if(utiler.exists(js)) {
                    var script = document.createElement('script');
                    script.type = 'text/javascript';
                    script.innerHTML = js[1].replace(/<script>/g, '').replace(/<\/script>/g, '');
                    response = response.replace(/<script>([\s\S]*)<\/script>/g, '');
                    $(id).append('<div class="dialog-container">' + response + '</div>');
                    $(id).append(script);
                }
                else {
                    $(id).append('<div class="dialog-container">' + response + '</div>');
                }
                $(id).find('.close').bind('click', function() {
                    jQuery.closeDialog($(this).parents('.dialog').attr('tabindex'));
                    // $(this).parents('.dialog').remove();
                });
                utiler.call(_function);
                return true;
            }
        });
    } else {
        if($(container).hasClass('already')) {
            $(container).parents('.dialog').addClass('active');
        }
        else {
            $(container).addClass('already').removeClass('d-none').removeAttr('hidden');
            $(container).wrap('<div class="dialog active ' + options.class + '" id="' + id + '" tabindex="' + dialogIndex + '"></div>');
            $(container).wrap('<div class="content"></div>');
            $(container).wrap('<div class="dialog-container"></div>');
            id = '#' + id + ' > .content';
            if(title) {
                $(id).prepend('<div class="title">' + title + '<span class="close"><i class="bi-x"></i></span></div>');
            }
            else if(!utiler.in(options.close, ['false', false])) {
                $(container).append('<span class="close"><i class="bi-x"></i></span>');
            }
            $(container).removeAttr('hidden');
            $(id).find('.close').bind('click', function() {
                jQuery.closeDialog($(this).parents('.dialog').attr('tabindex'));
                // $(this).parents('.dialog').removeClass('active');
            });
        }
        utiler.call(_function);
        return true;
    }
};
jQuery.closeDialog = function(index, _function) {
    
    if($('#dialog-' + index).attr('data-from') == 'ajax') {
        $('#dialog-' + index).remove();
    }
    else {
        $('#dialog-' + index).removeClass('active');
    }
    if($('.dialog.active').length <= 0) {
        jQuery.scrolling();
    }
    if(_function) {
        utiler.call(_function);
    }
};

jQuery.confirm = function(_function, message, title, _close) {
    if (utiler.empty(title)) {
        title = '确定要执行本次操作么？';
    }
    if (utiler.empty(message)) {
        message = '确认请点击OK按钮';
    }
    return swal(title + '', message + '', 'warning', {
        closeOnClickOutside: false,
        closeOnEsc: false,
        buttons: ['取消', '确定'],
    }).then((isConfirm) => {
        //判断 是否 点击的 确定按钮
        if (isConfirm) {
            utiler.call(_function);
        }
    });
};

jQuery.close = function(_function) {
    jQuery.scrolling();
    swal.close();
    if(_function) {
        utiler.call(_function);
    }
};
