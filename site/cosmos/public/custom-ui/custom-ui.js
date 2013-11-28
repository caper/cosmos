/*

CustomUI 1.0.1
@author: sciner <sciner@ya.ru>
@since 2013-09-13 - 2013-09-25

*************** Popup ******************

    Данный скрипт позволяет сделать popup-окошки (лайтбоксы).
    Можно сделать так, что лайтбоксы будут открываться без единой дополнительной строчки яваскрипта,
    для этого необходимо создать кнопку открытия и саму форму, для этого надо:
        1) кнопка, с уникальной #id и классом .button-simple
        2) скрытый строчно-блочный элемент с классом .popup-form и #id как у кнопки, но с добавленным префиксом form-

*************** Treeview ********************

    Easy TreeView - jQuery plugin
    written by SCINER <sciner@yandex.ru>

    Copyright (c) 2011 SCINER

    Built for jQuery library
    http://jquery.com

    Markup example for $("#treeview").treeView(options);

    // default options
    var options = {
        click: '',
        allow_collapse_expand: true,
        collapse_expand_animate: true
    }; 

    <ul id="treeview">
        <li id="id1"><em>Item-1</em>
           <!-- sub list -->
           <ul>
             <li id="id2"><em>Item-A</em></li>
             <li id="id3"><em>Item-B</em></li>
           </ul>
        </li>
    </ul>

********************************************/

var CustomUI = {
    
    // идентификаторы все открытых окон
    popup_form_opened_forms_id: [],
    popup_form_freezed_id: null,
    confirm_answer: null,
    shadow_id: '#shadow',

    addMoreFileFieldEx: function(target, name, max_input_count) {
        if(max_input_count > 0) {
            if($(target).find('input[type=file]').size() >= max_input_count) {
                // Нельзя загружать больше файлов чем указано
                return false;
            }
        }
        var f = '<input name="' + name + '" type="file" class="gn-file-add" /><br />';
        $(target).append(f);
        $(target).find('input[type=file]:last').data('more_file_field_target', target);
        $(target).find('input[type=file]:last').change(function(e) {
            var target = $(this).data('more_file_field_target');
            $(this).unbind('change');
            return CustomUI.addMoreFileFieldEx(target, name, max_input_count);
        }).styler();
        return false;
    },

    /**
    * Нажатие кнопки на панели инструментов диалоговых окон
    */
    setButtonHandlers: function() {
        $('.popup-form-button, .button-simple').unbind('click').click(function() {
            var dis = $(this).attr('disabled');
            if(dis != 'disabled') {
                // id - операция
                var id = $(this).attr('id');
                func = $('#form-' + id).data('before_show');
                try {
                    var ns = true;
                    if(func instanceof Function) {
                        ns = func(this);
                    }
                    if(ns) {
                        CustomUI.showForm('form-' + id, true, true, {});
                    }
                    return false;
                }
                catch(e) {
                    alert('Ошибка: ' + e)
                };
            }
            return false;
        });
        return false;
    },

    /**
    * Центрирование всех форм по центру сайта
    */
    centerForms: function() {
        for(i = 0; i < CustomUI.popup_form_opened_forms_id.length; i++) {
            var hide_id = CustomUI.popup_form_opened_forms_id[i];
            if($('#' + hide_id).is(':visible')) {
               $('#' + hide_id).css({left: function(){
                   return $(window).width() * 0.5 - $(this).outerWidth() * 0.5;
               }, top: function(){
                   return $(window).height() * 0.5 - $(this).outerHeight() * 0.5;
               }});
            }
        }
        return false;
    },
    
    log: function(message) {
        // $('div#log').append(message + '<br />');
    },

    /**
    * Открытие формы
    */
    showForm: function(id, can_close, apply_class, options) {
        var form = $('#' + id);
        CustomUI.log('showForm(\'#' + id + '\');');
        if((typeof apply_class == 'undefined') || apply_class) {
            if(form.find('.popup-form-toolbox').size() < 1) {
                var no_cap = form.hasClass('popup-nocaption');
                if(!no_cap) {
                    var title = form.attr('title');
                    if(title == undefined) {title = '';}
                    form.removeAttr('title').data('title', title);
                    var buttons = '';
                    if(can_close == null || can_close === true) {
                        buttons += '<a title="Закрыть окно" href="#" onclick="return CustomUI.hideForm();" class="popup-close-button popup-toolbox-button">&times;</a>';
                    }
                    form.prepend('<table width="100%" class="popup-form-toolbox"><tr><td align="left"><h2 id="' + id+'-title">' + title + '</h2></td><td align="right">' + buttons + '</td></tr></table>');
                }
            }
        }
        form.css({margin: 0});
        var w = form.outerWidth();
        var h = form.outerHeight();
        var x = $(window).width() / 2 - w / 2;
        var y = $(window).height() / 2 - h / 2;
        CustomUI.hideForm();
        if(options) {
            form.data('options', options);
        }
        CustomUI.popup_form_opened_forms_id.push(id);
        $(CustomUI.shadow_id).show();
        form.data('shadow', $(CustomUI.shadow_id))
            .css({position: 'fixed', left: x, top: y, 'z-index': 3000})
            .show();
        var content_h = $(window).height() - 150;
        form.find('.popup-form-content').css({'max-height': content_h});
        // автофокус на первом элементе управления
        if(form.find('[data-first-focus]').size() > 0) {
            form.find('[data-first-focus]').eq(0).focus();
        } else {
            if(form.find('input[type=text]:visible').size() > 0) {
                form.find('input[type=text]:visible:first').focus();
            } else {
                form.find('textarea:visible:first').focus();
            }
        }
        // "колбэк" функция сразу после показа окна
        callback = form.data('after_show');
        if(callback instanceof Function) {
            callback(this);
        }    
        return CustomUI.centerForms();
    },

    /**
    * Закрытие всех открытых форм с генерацией события "onclose"
    * @since 2011-10-12
    */
    hideForm: function(id) {
        var id_list = CustomUI.popup_form_opened_forms_id.clone();
        CustomUI.popup_form_opened_forms_id = [];
        while(hide_id = id_list.pop()) {
            if($('#' + hide_id).is(':visible')) {
                var options = $('#' + hide_id).data('options');
                var defaults = {
                    can_close: true,
                    onclose: false,
                };
                var options = $.extend(defaults, options);
                if(options.can_close) {
                    $(CustomUI.shadow_id).hide();
                    $('#' + hide_id).hide();
                }
                var onclose = options['onclose'];
                if(onclose instanceof Function) {
                    var params = options['params'];
                    onclose(params);
                }
            }
        }
        return CustomUI.setButtonHandlers();
    },

    /**
    * Показ формы с сообщением
    * 
    * string message Текст сообщения
    * string title Заголовок окна
    * string icon стиль окна(info,error)
    * string options Массив с опциями окна (например событие "onclose")
    */
    showMessage: function(message, title, icon, options) {
        CustomUI.log('showMessage(\'' + $('<div/>').text(message).html() + '\');');
        $('#form-msg-popup-window').attr('title', title);
        if(icon == 'info') {$('#form-msg-popup-window').removeClass('popup-window-error').addClass('popup-window-info');}
        if(icon == 'error') {$('#form-msg-popup-window').removeClass('popup-window-info').addClass('popup-window-error');}
        $('#form-msg-popup-window .popup-form-content').html(message);
        return CustomUI.showForm('form-msg-popup-window', true, true, options);
    },

    showWait: function(text, caption) {
        CustomUI.log('showWait();');
        // why is need???
        //if(CustomUI.popup_form_opened_forms_id.length < 1) {
            text = text ? text : 'Пожалуйста подождите...';
            caption = caption ? caption : 'Получение данных';
            return CustomUI.showMessage('<div style="text-align: center;"><img src="/img/autocomplete_indicator.gif" align="absmiddle" /><br />' + text + '</div>', caption, 'info', {});
        //}
    },
}

$(function($) {

    // добавление затемнения (фон для окон)
    $('body').prepend('<div id="shadow" style="display:none;"></div>');
    // добавление окна сообщений
    $('body').prepend('<form class="popup-form" id="form-msg-popup-window" title="" style="display: none;"><div class="popup-form-content"></div></form>');
    $('body').prepend('<form class="popup-form" id="form-msg-popup-confirm" title="" style="display: none;"><div class="popup-form-content"></div><div class="popup-form-footer"><input type="submit" value=" Да " class="btn btn-primary" title="Продолжить" onclick="CustomUI.confirm_answer = true; CustomUI.hideForm(); return false;" name="yes" /><input type="submit" class="btn" value=" Отмена " title="Прекратить выполнение действия" onclick="CustomUI.confirm_answer = false; CustomUI.hideForm(); return false;" name="cancel" /></div></form>');
    CustomUI.setButtonHandlers();

    /**
    * Закрытие всех окон по нажатию на клавишу [Esc]
    */
    $(document).keydown(function(event) {
        try {
            if (event.keyCode == 27) { // 27 is keycode for [Esc] button on keyboard
                 CustomUI.hideForm('');
                 event.preventDefault();
            }
        } catch(e) {
            // do nothing
        }
    });

    /**
    * "Быстроссылка" из любого тега (например TR)
    */
    $('[data-href]').click(function(e){
        if(e.target.tagName != 'A') {
            var url = $(this).attr('data-href');
            location.href = url;
            return false;
        }
    });

    /**
    * Обработка элементов с атрибутом data-sort
    */
    $('[data-sort]').unbind('click').click(function() {
        var dir = $(this).data('sort-dir');
        if(dir == 'asc') {
            dir = 'desc';
        } else {
            dir = 'asc';
        }
        var field = $(this).attr('data-sort');
        $('#sort_field').val(field);
        $('#sort_dir').val(dir);
        $('#sortfilter').submit();
    });

    /**
    * Обработка элементов с атрибутом data-ajax
    */
    $('[data-ajax]').click(function() {
        var params = $(this).attr('data-ajax');
        // результат
        try {
            var options = eval('(' + params + ')');
            // default configuration properties
            var defaults = {
                url: '',
                type: 'post',
                refresh: false,
                callback: false,
                go: '',
            };
            var options = $.extend(defaults, options);
            CustomUI.showForm('form-wait', false, true);
            $.post(options.url, function(data) {
                // результат
                try {
                    var obj = eval('(' + data + ')');
                    if(options.callback) {
                        CustomUI.hideForm();
                        var callback = eval(options.callback);
                        callback.call(obj);
                        return false;
                    }
                    if(obj.status == 'success' && (options.refresh || options.go)) {
                        window.location = options.go;
                    } else {
                        CustomUI.hideForm();
                        alert(obj.message);
                    }
                }
                catch(e) {
                    CustomUI.hideForm();
                    alert(e);
                }
            });
        }
        catch(e) {
            alert(e);
        }
        return false;
    });

    $.fn.customFileInput = function(options) {
        // default configuration properties
        var defaults = {
            name: false,
            max_count: 5
        };
        var options = $.extend(defaults, options);
        this.each(function() {
            CustomUI.addMoreFileFieldEx(this, options.name, options.max_count);
        });
    };

    $.fn.customSubmit = function(options) {
        // default configuration properties
        var defaults = {
            success: false,
            onclose: false,
            error: false,
            caption: '',
            show_wait: true,
            confirm: false,
            ajax: true,
            json: false,
            validate_function: false,
            wait_message: 'Пожалуйста, подождите'
        };
        var options = $.extend(defaults, options);
        this.each(function() {
            // check if listeneer already installed
            if(typeof $(this).data('CustomUI_lai') == 'undefined') {
                $(this).data('CustomUI_lai', true);
            } else {
                var id = $(this).attr('id');
                return alert('CustomUI.customSubmit() already installed for\nform#' + id);
            }
            $(this).submit(
                function() {
                    try {
                        var freezed_form_id = $(this).hasClass('popup-form') ? $(this).attr('id') : false;
                        // show_wait
                        var sw = $(this).data('show_wait');
                        options.show_wait = (typeof sw != 'undefined') ? sw : options.show_wait ;
                        // caption
                        options.caption = options.caption ? options.caption : $(this).data('title');
                        if(!options.caption) {
                            options.caption = $(this).attr('title');
                        }
                        // onclose
                        options.onclose = options.onclose ? options.onclose : $(this).data('onclose');
                        if(!options.onclose) {
                            options.onclose = defaults.onclose;
                        }
                        // success
                        options.success = options.success ? options.success : $(this).data('success');
                        if(!options.success) {
                            options.success = defaults.success;
                        }
                        // error
                        options.error = options.error ? options.error : $(this).data('error');
                        if(!options.error) {
                            options.error = defaults.error;
                        }
                        // confirm
                        options.confirm = options.confirm ? options.confirm : $(this).data('confirm');
                        if(!options.confirm) {
                            options.confirm = defaults.confirm;
                        }
                        // validate function
                        options.validate_function = options.validate_function ? options.validate_function : $(this).data('validate_function');
                        if(!options.validate_function) {
                            options.validate_function = defaults.validate_function;
                        }
                        $(this).data('options', options);
                        if(options.confirm) {
                            if(CustomUI.confirm_answer == null) {
                                var object = this;
                                $('#form-msg-popup-confirm .popup-form-content').html(options.confirm);
                                $('#form-msg-popup-confirm').attr('title', options.caption);
                                $('#form-msg-popup-confirm').data(
                                    'options',
                                    {
                                        onclose: function(e){
                                            if(CustomUI.confirm_answer) {
                                                $(object).submit();
                                            }
                                            else {
                                                CustomUI.confirm_answer = null;
                                                return false;
                                            }
                                        }
                                    }
                                );
                                return CustomUI.showForm('form-msg-popup-confirm', false, true);
                            }
                            if(!CustomUI.confirm_answer) {
                                CustomUI.confirm_answer = null;
                                return false;
                            }
                            CustomUI.confirm_answer = null;
                        }
                        if (options.validate_function instanceof Function) {
                            if(!options.validate_function(this)) {
                                return false;
                            }
                        }
                        if(options.ajax) {
                            // вывод информирующей формы с предложением дождаться серверной обработки формы
                            if(options.show_wait) {
                                CustomUI.showWait(options.wait_message, options.caption);
                            }
                            var op = {
                                // успешный HTTP ответ (статус 200)
                                success: function(data) {
                                    if(options.json) {
                                        // если в ответ должен прийти json объект
                                        var obj;
                                        try {
                                            // раскодируем сериализованный объект
                                            if(data instanceof Object) {
                                                obj = data;
                                            } else {
                                                obj = eval('(' + data + ')');
                                            }
                                            // на сервере произошла ошибка
                                            if(obj.status != 'success') {
                                                throw obj.message;
                                            }
                                            // если есть callback-функция для обработки статуса "успех"
                                            if(options.success instanceof Function) {
                                                options.success(obj ? obj : data);
                                            } else {
                                                // если в ответе отсутствует сообщение, присваиваем стандартное
                                                if(!obj.message) {
                                                    obj.message = 'Действие успешно выполнено';
                                                }
                                                // вывод информирующей формы
                                                CustomUI.showMessage(obj.message, options.caption, 'info', {onclose: options.success, params: data});
                                            }
                                        } catch(e) {
                                            // если есть callback-функция для обработки статуса "ошибка"
                                            if(options.error instanceof Function) {
                                                return options.error(obj ? obj : data);                                                
                                            } else {
                                                // вывод информирующей формы
                                                return CustomUI.showMessage(e, options.caption, 'error', {onclose: function(data) {
                                                        if(freezed_form_id) {
                                                            return CustomUI.showForm(freezed_form_id, true, true, {});
                                                        }
                                                    }, params: data});
                                            }
                                        }
                                    } else {
                                        CustomUI.hideForm();
                                        // если есть callback-функция для обработки статуса "успех"
                                        if(options.success instanceof Function) {
                                            options.success(data);
                                        }
                                    }
                                },
                                // ошибочный HTTP ответ
                                error: function(data) {
                                    alert('error');
                                }
                            };
                            // отправка формы на сервер
                            $(this).ajaxSubmit(op);
                            return false;
                        } else {
                            CustomUI.hideForm();
                            return true;
                        }
                    } catch(e) {
                        alert('eee: ' + e);
                        return false;
                    }
                }
            );
        });
    };
    
    /**
    * Treeview
    */
    $.fn.treeView = function(options){
        // default configuration properties
        var defaults = {
            click: '',
            allow_collapse_expand: true,
            collapse_expand_animate: true,
            icon_folder_class: 'tree-icon-folder',
            icon_file_class: 'tree-icon-file'
        };
        var options = $.extend(defaults, options);
        this.each(function() {
            var obj = $(this);
            obj.addClass('ui-nice-treeview');
            $('li a', obj).click(function() {
                var id = $(this).parent().attr('id');
                var li = $(this).closest('li');
                if(options.allow_collapse_expand) {
                    // скрытие/раскрытие
                    if(options.collapse_expand_animate) {
                        $(this).parent().children('ul:first').slideToggle('fast', function() {
                            if($(this).is(':visible')) {
                                $(li).addClass('expanded');
                            } else {
                                $(li).removeClass('expanded');
                            }
                        });
                    }
                    else {
                        $(this).parent().children('ul:first').toggle(0, function() {
                            if($(this).is(':visible')) {
                                $(li).addClass('expanded');
                            } else {
                                $(li).removeClass('expanded');
                            }
                        });
                    }
                }                
                if(options.click) {
                    options.click(this);
                }
            });
        });
    };

    $('.popup-ajax').customSubmit({
        json: true
    });

});

if(!Array.prototype.clone) {
    Array.prototype.clone = function() {
        return this.slice(0);
    };
}

/**
* Центрование открытых/видимых форм при ресайзе окна браузера
*/
$(window).resize(function() {
    CustomUI.centerForms();
});

function show_wait() {
    return CustomUI.showWait('Пожалуйста подождите...', 'Получение данных');
}

/**
* Показ формы с сообщением
*/
function show_message(message, title, icon, options) {
    return CustomUI.showMessage(message, title, icon, options);
}

/**
* Открытие формы
*/
function show_form(id, can_close, apply_class) {
    return CustomUI.showForm(id, can_close, apply_class, {can_close: can_close});
}

/**
* Закрытие всех открытых форм с генерацией события "onclose"
*/
function hide_form(id) {
    return CustomUI.hideForm(id);
}

/**
* Центрирование всех открытых форм
*/
function center_forms() {
    return CustomUI.centerForms();
}
