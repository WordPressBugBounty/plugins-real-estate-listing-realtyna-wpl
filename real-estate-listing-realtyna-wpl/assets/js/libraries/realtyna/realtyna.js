/*
 Realtyna JavaScript Framework
 @Author Steve M. @ Realtyna UI Department
 @Copyright 2015 Realtyna Inc.
 @license Realtyna Inc.
 @Version 1.3.9
 */

!function ($, window, document) {
    'use strict';

    var verRealtyna = '1.3.9',
        verRPL = '0.0.0',
        verWPL = '2.0.0';

    //region + Define realtyna jQuery namespace
    $._realtyna = {};

    $.fn.realtyna = function (plugin, opts) {
        if($._realtyna.hasOwnProperty(plugin)){
            return $._realtyna[plugin].init($(this), opts);
        }else{
            console.log('WPL::Dependency Missing=>Realtyna ' + plugin + ' library is not available and can\'t initialize.');
        }
    }

    $._realtyna.fn = {};
    //endregion

    // + Define Realtyna object
    window.Realtyna = (function () {

        var _pageHashes = [],
            _quaryStrings = [],
            _rxURL = /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/i;

        return {
            version: verRealtyna,
            shouldInit: true,

            init: function () {
                Realtyna.initURLVariables();
            },

            // URL parse
            initURLVariables: function () {

                // Init hash values
                _pageHashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('#');

                // Pars quary strings
                var _temp = _pageHashes[0].split('&');
                for (var i = 0; i < _temp.length; ++i) {
                    var _pair = _temp[i].split('=');
                    _quaryStrings[_pair[0]] = _pair[1];
                }

                return true;
            },

            // Get URL hashes
            getHash: function () {
                return (typeof _pageHashes[1] != "undefined") ? _pageHashes[1] : false;
            },

            setHash: function (hash, sepChar) {
                sepChar = sepChar || '#';
                hash = sepChar + hash;

                if (history.pushState) {
                    history.pushState(null, null, hash);
                }
                else {
                    location.hash = hash;
                }
            },

            // Get URL Query Strings
            getQueryString: function (name) {
                if (_quaryStrings.hasOwnProperty(name))
                    return _quaryStrings[name];
            },

            getBrowserSizes: function () {
                var _doc = document;

                return {

                    window: {
                        height: $(window).height(),
                        width: $(window).width()
                    },

                    document: {
                        height: $(_doc).height(),
                        width: function () {

                            return Math.max(
                                _doc.body.scrollHeight, _doc.documentElement.scrollHeight,
                                _doc.body.offsetHeight, _doc.documentElement.offsetHeight,
                                _doc.body.clientHeight, _doc.documentElement.clientHeight
                            );
                        }
                    },

                    browser: function () {
                        var __size = {};

                        if (window.innerHeight) //if browser supports window.innerWidth
                        {
                            __size.height = window.innerHeight;
                            __size.width = window.innerWidth;
                        }
                        else if (_doc.all) //else if browser supports document.all (IE 4+)
                        {
                            __size.height = _doc.body.clientHeight;
                            __size.width = _doc.body.clientWidth;
                        }

                        return __size;
                    }

                }
            },

            isQuery: function (obj) {
                return obj && obj.hasOwnProperty && obj instanceof $;
            },
            isString: function (str) {
                return str && $.type(str) === "string";
            },
            isPercentage: function (str) {
                return isString(str) && str.indexOf('%') > 0;
            },
            isScrollable: function (el) {
                return (el && !(el.style.overflow && el.style.overflow === 'hidden') && ((el.clientWidth && el.scrollWidth > el.clientWidth) || (el.clientHeight && el.scrollHeight > el.clientHeight)));
            },

            // - Inline options to JSON
            options2JSON: function (str) {

                var strArray = str.split('|'),
                    myObject = {},
                    rxIsBool = /^true|false$/i;

                for (var i = 0; i < strArray.length; ++i) {

                    var _sp = strArray[i].split(':');

                    if(_sp[1].length > 0 && !isNaN(_sp[1]))
                        myObject[_sp[0]] = parseInt(_sp[1]);
                    else if (rxIsBool.test(_sp[1]))
                        myObject[_sp[0]] = /^true$/i.test(_sp[1]);
                    else
                        myObject[_sp[0]] = _sp[1];
                }

                /*
                var strArray = str.split('|'),
                    myObject = {},
                    rxItem = /^[a-zA-Z]+:(('\w+[a-zA-Z\-_ ]+?\w+')|("\w+[a-zA-Z\-_ ]+?\w+")|\d+|\d+%?)$/i,
                    rxIsBool = /^true|false$/i,
                    rxWithQuote = /^(('\w+[a-zA-Z\-_ ]+?\w+')|("\w+[a-zA-Z\-_ ]+?\w+"))$/i;

                for (var i = 0; i < strArray.length; ++i) {

                    if (!rxItem.test(strArray[i]))
                        continue;

                    var _sp = strArray[i].split(':');

                    if ($.isNumeric(_sp[1]))
                        myObject[_sp[0]] = parseInt(_sp[1]);

                    else if (rxIsBool.test(_sp[1]))
                        myObject[_sp[0]] = /^true$/i.test(_sp[1]);
                    else{
                        if(rxWithQuote.test(_sp[1]))
                            myObject[_sp[0]] = _sp[1].slice(1,-1);
                        else
                            myObject[_sp[0]] = _sp[1];
                    }

                }
                */

                return myObject;
            },

            // - Set cookie
            setCookie: function (name, value, expires, path, domain, secure) {
                var today = new Date();
                today.setTime(today.getTime());
                if (expires) {
                    expires = expires * 1000 * 60 * 60 * 24;
                }
                var expires_date = new Date(today.getTime() + (expires));
                document.cookie = name + "=" + escape(value) +
                ((expires) ? ";expires=" + expires_date.toGMTString() : "") +
                ((path) ? ";path=" + path : "") +
                ((domain) ? ";domain=" + domain : "") +
                ((secure) ? ";secure" : "");
            },

            // - Get cookie
            getCookie : function (name) {
                var start = document.cookie.indexOf(name + "=");
                var len = start + name.length + 1;
                if ((!start) && (name != document.cookie.substring(0, name.length))) {
                    return null;
                }
                if (start == -1)
                    return null;
                var end = document.cookie.indexOf(";", len);
                if (end == -1)
                    end = document.cookie.length;
                return unescape(document.cookie.substring(len, end));
            },

            // - Set Local Storage
            setLocal: function(name,value){

                localStorage.setItem(name, value);

                return true;
            },

            // - Get Local Storage
            getLocal: function(name){
                return localStorage.getItem(name);
            },

            // - Set Local Storage
            setSession: function(name,value){

                sessionStorage.setItem(name, value);

                return true;
            },

            // - Get Local Storage
            getSession: function(name){
                return sessionStorage.getItem(name);
            },

            // - CSS Class generator
            getClass: function(){
                var args = Array.prototype.slice.call(arguments);
                return args.join(' ');
            },

            // - Scroll to
            scrollTo: function(element, additionOffset, speed){

                if(!element)
                    return false;

                if(!$(element).length)
                    return false;

                speed = speed || 650;

                if(!additionOffset)
                    additionOffset = 0;

                var moreOffset = 0,
                    finalPos = 0;

                if($('#wpadminbar').length)
                    moreOffset = $('#wpadminbar').outerHeight();

                finalPos = $(element).eq(0).offset().top - moreOffset + additionOffset

                $('html, body').animate({
                    scrollTop: finalPos
                }, speed);
            },

            isValidJson: function(str){
                try {
                    JSON.parse(str);
                } catch (e) {
                    return false;
                }
                return true;
            },

            hex2rgba: function(hex, opacity) {
                var r,g,b;

                if(hex[0] === '#')
                    hex = hex.slice(1);

                if (hex.length === 6) {
                    r = parseInt(hex.substring(0, 2), 16);
                    g = parseInt(hex.substring(2, 4), 16);
                    b = parseInt(hex.substring(4, 6), 16);
                }
                else if (hex.length === 3) {
                    r = parseInt(hex.substring(0, 1) + hex.substring(0, 1), 16);
                    g = parseInt(hex.substring(1, 2) + hex.substring(1, 2), 16);
                    b = parseInt(hex.substring(2, 3) + hex.substring(2, 3), 16);
                }
                return 'rgba('+ r+ ',' + g + ',' + b + ',' + opacity / 100 + ')';
            }

        };
    })();


    Realtyna.start = function () {

        return Realtyna.initializer(Realtyna);

    }


    // Call init methods
    Realtyna.initializer = function (obj) {
        var _initCalled = 0;

        if (typeof obj == "undefined")
            return false;

        // Self object init
        if (obj.hasOwnProperty('shouldInit') && obj.shouldInit && obj.hasOwnProperty('init')) {
            obj.init();
            _initCalled++;
        }


        // Sub object init
        for (var prop in obj) {

            if (obj[prop].hasOwnProperty('shouldInit') && obj[prop].shouldInit) {

                if (obj[prop].hasOwnProperty('init') && prop != 'prototype') {
                    _initCalled += Realtyna.initializer(obj[prop]);
                }

            }
            ;

        }
        ;

        return _initCalled;

    };

    // + WPL Object ////////////////////////////////////////////////////////////////////////////////////////////////

    Realtyna.wpl = (function () {

        return {
            version: verWPL,
            shouldInit: true,

            init: function () {

            },

            validate: function (obj, props) {
                for (var i = 0; i < props.length; ++i) {
                    if (!obj.hasOwnProperty(props[i]))
                        return false;
                }
                return true;
            }
        };
    })();

    // + Registers /////////////////////////////////////////////////////////////////////////////////////////////////

    // - Add registers prop
    Realtyna.regs = {};


    // + Options ///////////////////////////////////////////////////////////////////////////////////////////////////

    // - Add options prop
    Realtyna.options = {};
    Realtyna.wpl.options = {};

    // - Ajax Loader
    Realtyna.options.ajaxloader = {
        autoHide: 40, // Delay to hide all spinners in seconds

        coverTmpl: '<div class="realtyna-ajaxloader-cover"/>',
        coverStyle: {
            backgroundColor: 'rgba(0,0,0,0.4)',
            position: 'absolute',
            top: 0,
            left: 0,
            width: '100%',
            height: '100%',
            zIndex: 2e8,
            display: 'inline-block'

        }
    };

    // - [WPL] Tab options
    Realtyna.wpl.options.tabs = {
        // Class selectors
        tabSystemClass: '.wpl-js-tab-system',
        tabsClass: '.wpl-gen-tab-wp',
        tabContentsClass: '.wpl-gen-tab-contents-wp',
        tabContentClass: '.wpl-payment-content',

        tabActiveClass: 'wpl-gen-tab-active', // Class Name

        activeChildIndex: 0 // Active tab index
    };

    // - [WPL] Sidebars system
    Realtyna.wpl.options.sidebar = {

        // Selectors
        attrContainer: 'data-wpl-sidebar',
        attrTabs: 'data-wpl-sidebar-tabs',
        attrContents: 'data-wpl-sidebar-contents',

        tabPrefix: 'wpl_slide_label_id',
        contentPrefix: 'wpl_slide_container_id_',

        tabActiveClass: 'wpl-gen-side-tab-active',

        showEffect: 'animated fadeInDownSmall'

    };

    // - [WPL] Functional Class
    Realtyna.wpl.options.css = {
        utilHidden: 'wpl-util-hidden',
        utilShow: 'wpl-util-show',

        actionMove: 'wpl-icon-action-move'
    };


    // + Message Center ////////////////////////////////////////////////////////////////////////////////////////////

    // - Messages body
    Realtyna.wpl._messages = (function () {

        var _isInit = false,
            _template = null,
            _wrapper = null,
            _requireProp = ['msgBody'];

        return {
            shouldInit: false,

            init: function (tmplID) {

                if (typeof Handlebars != "undefined") {

                    var _tmplID = tmplID || '#wpl-tmpl-message',
                        _source = $(_tmplID).html();

                    _template = Handlebars.compile(_source);
                    _wrapper = $('#wpl-messages-wp');

                    _isInit = true;
                }
            },

            isInit: function () {
                return _isInit;
            },

            showMessage: function (msg) {
                if (Realtyna.wpl.validate(msg, _requireProp)) {
                    var _message = $(_template(msg));

                    _wrapper.prepend($(_message).show().addClass('animated fadeIn'));

                    return true;
                }
                return false;
            }
        };
    })();

    // - Sidebar body
    Realtyna.wpl._sidebars = (function () {
        var _sidebarsOpt = Realtyna.wpl.options.sidebar;

        return {
            shouldInit: true,

            init: function () {
                $('[' + _sidebarsOpt.attrContainer + ']').each(function (index) {

                    var _self = $(this),
                        _tabs = _self.find('[' + _sidebarsOpt.attrTabs + ']'),
                        _contents = _self.find('[' + _sidebarsOpt.attrContents + ']');

                    // Bind sidebar click event
                    _tabs.find('a').on('click', function (ev) {

                        // Break if already active
                        if ($(this).parent().hasClass(_sidebarsOpt.tabActiveClass))
                            return false;

                        // Reset layout
                        _tabs.find('a').parent().removeClass(_sidebarsOpt.tabActiveClass);
                        _contents.find('>').hide();

                        $(this).parent().addClass(_sidebarsOpt.tabActiveClass);

                        var _idPostfix = $(this).attr('href').slice(1);
                        _contents.find('#' + _sidebarsOpt.contentPrefix + _idPostfix).show().addClass(_sidebarsOpt.showEffect);

                    });

                    // Open load page
                    var _hasHashTab = false;

                    if (Realtyna.getHash())
                        _hasHashTab = (_contents.find('#' + _sidebarsOpt.contentPrefix + Realtyna.getHash()).length > 0);

                    if (_hasHashTab) {
                        _tabs.find('#' + _sidebarsOpt.tabPrefix + '_' + Realtyna.getHash()).trigger('click');
                    } else {
                        var _firstTab = (_tabs.find('a:eq(0)').attr('id') == _sidebarsOpt.tabPrefix + '10000') ?
                            _tabs.find('a:eq(1)') : _tabs.find('a:eq(0)');
                        _firstTab.trigger('click');
                    }

                });
            }

        };
    })();

    // + Global Methods ////////////////////////////////////////////////////////////////////////////////////////////

    Realtyna.libs = {};


    // + Global Methods ////////////////////////////////////////////////////////////////////////////////////////////

    // - Show Message
    Realtyna.wpl.showMessage = function (msg) {

        if (Realtyna.wpl._messages.isInit()) {
            if (msg.hasOwnProperty('type')) {

                msg.hasButton = false;

                if (msg.type == 'confirm') {

                    msg.hasButton = true;
                    msg.cssClass = ' wpl-get-message-confirm';

                } else if (msg.type == 'info') {
                    msg.cssClass = ' wpl-get-message-info';
                }

            }

            if (msg.hasOwnProperty('defaultBtn')) {
                if (msg.defaultBtn == 'yes')
                    msg.yesClass = ' wpl-gen-message-btn-default';
                else
                    msg.noClass = ' wpl-gen-message-btn-default';
            }

            if (msg.hasOwnProperty('onYes')) {

                if ($.isFunction(msg.onYes)) {

                } else if (typeof msg.onYes === "string") {

                }
            }

            if (msg.hasOwnProperty('onNo')) {

            }

            return Realtyna.wpl._messages.showMessage(msg);
        }

        return false;
    }


    // - Show ajax loader

    Realtyna.ajaxLoader = (function () {

        var _coverTmpl = Realtyna.options.ajaxloader.coverTmpl,
            _coverStyles = Realtyna.options.ajaxloader.coverStyle,
            _hideTimeout;

        return {
            version: '2.0.1',

            _autoHide: function () {
                clearTimeout(_hideTimeout);
                _hideTimeout = setTimeout(function () {
                    Realtyna.ajaxLoader.hide();
                }, Realtyna.options.ajaxloader.autoHide * 1000);
            },

            /**
             * Show Preloader on specific element
             * @param selector
             * @param param2 Size of Preloader or a plain object of all options - 'normal'
             * @param param3 Position to show - 'center'
             * @param param4 With cover - false
             * @param param5 Color of loader - '#000'
             * @param param6 Outer Gap size - 3
             * @param param7 Cover Style - {...}
             */
            show: function (selector, param2, param3, param4, param5, param6, param7) {

                var _spin = null,
                    _self = Realtyna.isQuery(selector) ? selector : $(selector),
                    _size = 'normal',
                    _position = {
                        left: '50%',
                        top: '50%'
                    },
                    _withCover = false,
                    _spinColor = param5 || '#000',
                    _spinSpace = param6 || 3,
                    _tagName = _self.prop('tagName').toLowerCase();


                if (_self.length > 0 && !_self.eq(0).data('wplHasSpin')) {

                    // + Define presents

                    // - Size
                    var _defaultSizes = $.fn.spin.presets = {
                        normal: {
                            spinWidth: 21,
                            lines: 20, // The number of lines to draw
                            length: 5, // The length of each line
                            width: 2, // The line thickness
                            radius: 19, // The radius of the inner circle
                            corners: 0, // Corner roundness (0..1)
                            rotate: 0, // The rotation offset
                            direction: 1, // 1: clockwise, -1: counterclockwise
                            color: '#000', // #rgb or #rrggbb or array of colors
                            speed: 1, // Rounds per second
                            trail: 85, // Afterglow percentage
                            shadow: false, // Whether to render a shadow
                            hwaccel: true, // Whether to use hardware acceleration
                            className: 'realtyna-spin', // The CSS class to assign to the spinner
                            zIndex: 2e9, // The z-index (defaults to 2000000000)
                            top: '50%', // Top position relative to parent
                            left: '50%' // Left position relative to parent
                        },
                        tiny: {
                            spinWidth: 8,
                            lines: 17, // The number of lines to draw
                            length: 0, // The length of each line
                            width: 2, // The line thickness
                            radius: 6, // The radius of the inner circle
                            corners: 1, // Corner roundness (0..1)
                            rotate: 0, // The rotation offset
                            direction: 1, // 1: clockwise, -1: counterclockwise
                            color: '#000', // #rgb or #rrggbb or array of colors
                            speed: 1, // Rounds per second
                            trail: 60, // Afterglow percentage
                            shadow: false, // Whether to render a shadow
                            hwaccel: true, // Whether to use hardware acceleration
                            className: 'realtyna-spin', // The CSS class to assign to the spinner
                            zIndex: 2e9, // The z-index (defaults to 2000000000)
                            top: '50%', // Top position relative to parent
                            left: '50%' // Left position relative to parent
                        },
                        full: {
                            spinWidth: 62,
                            lines: 30, // The number of lines to draw
                            length: 12, // The length of each line
                            width: 2, // The line thickness
                            radius: 60, // The radius of the inner circle
                            corners: 1, // Corner roundness (0..1)
                            rotate: 0, // The rotation offset
                            direction: 1, // 1: clockwise, -1: counterclockwise
                            color: '#000', // #rgb or #rrggbb or array of colors
                            speed: 1, // Rounds per second
                            trail: 36, // Afterglow percentage
                            shadow: false, // Whether to render a shadow
                            hwaccel: true, // Whether to use hardware acceleration
                            className: 'realtyna-spin', // The CSS class to assign to the spinner
                            zIndex: 2e9, // The z-index (defaults to 2000000000)
                            top: '50%', // Top position relative to parent
                            left: '50%' // Left position relative to parent
                        }
                    };

                    // Size or Options
                    if (typeof param2 != "undefined" && !$.isPlainObject(param2)) {

                        // Set size from param2
                        if ($.fn.spin.presets.hasOwnProperty(param2))
                            _size = param2;

                    } else if (typeof param2 != "undefined" && $.isPlainObject(param2)) {

                    }

                    // Check is with cover
                    if (typeof param4 != "undefined") {

                        if (param4) {

                            // Not input
                            if (_tagName != "input" && _tagName != "select") {

                                var _template = $(_coverTmpl);
                                var _coverFinalStyle = _coverStyles;

                                if(typeof param7 != "undefined")
                                    if($.isPlainObject(param7))
                                        _coverFinalStyle = $.extend({}, _coverStyles, param7);

                                _template.css(_coverFinalStyle);
                                _self.prepend(_template);
                            }
                        }


                    } else {
                        param4 = false;
                    }

                    // Showing position
                    if (typeof param3 != "undefined") {

                        switch (param3) {

                            case 'leftIn':
                                _position.left = _defaultSizes[_size].spinWidth + _spinSpace + 'px';
                                break;
                            case 'leftOut':
                                _position.left = -1 * (_defaultSizes[_size].spinWidth + _spinSpace) + 'px';
                                break;
                            case 'rightOut':
                                _position.left = Math.round(100 + ((_defaultSizes[_size].spinWidth + _spinSpace) * 100 / _self.width())) + '%';
                                break;
                            case 'rightIn':
                                _position.left = Math.round(100 - ((_defaultSizes[_size].spinWidth + _spinSpace) * 100 / _self.width())) + '%';
                                break;
                        }
                    }

                    var opts = $.extend({}, $.fn.spin.presets[_size], _position);
                    opts.color = _spinColor;

                    // Show spin right place in the input
                    if (_tagName == 'input') {

                        var _parent = _self.parent();

                        _parent.data({
                            wplHasSpin: true,
                            wplHasCover: param4,
                            wplCurrentPos: _parent.css('position')
                        });

                        _parent.css('position', 'relative');

                        var _inputPos = _self.position(),
                            _inputSize = {
                                width: _self.outerWidth(),
                                height: _self.height()
                            };

                        if (typeof param3 != "undefined") {

                            opts.top = _inputPos.top + (_inputSize.height / 2) + (_defaultSizes[_size].spinWidth / 2) + 'px';

                            switch (param3) {

                                case 'leftIn':
                                    opts.left = _inputPos.left + _defaultSizes[_size].spinWidth + _spinSpace + 'px';
                                    break;
                                case 'leftOut':
                                    opts.left = _inputPos.left - _defaultSizes[_size].spinWidth - _spinSpace + 'px';
                                    break;
                                case 'rightOut':
                                    opts.left = _inputPos.left + _inputSize.width + _defaultSizes[_size].spinWidth + _spinSpace + 'px';
                                    break;
                                case 'rightIn':
                                    opts.left = _inputPos.left + _inputSize.width - _defaultSizes[_size].spinWidth - _spinSpace + 'px';
                                    break;
                            }
                        }

                        _spin = _parent.spin(opts);

                    } else {
                        // Set necessary information
                        _self.data({
                            wplHasSpin: true,
                            wplHasCover: param4,
                            wplCurrentPos: _self.css('position')
                        });
                        _self.css('position', 'relative');

                        _spin = _self.spin(opts);
                    }


                    // Call autoHide for fix any performance issue
                    Realtyna.ajaxLoader._autoHide();


                    return _spin;
                }

                return false;

            },

            hide: function (elem) {

                var _spinCountHide = 0;

                if (typeof elem != "undefined" && elem) {
                    var _self = elem;

                    // Remove cover element
                    if (_self.data('wplHasCover'))
                        _self.find('.realtyna-ajaxloader-cover').remove();

                    // Remove spinner
                    _self.spin(false).removeData('wplHasSpin wplHasCover wplCurrentPos');

                    _spinCountHide++;
                } else {

                    $('.realtyna-spin').each(function (index) {
                        var _self = $(this),
                            _parent = _self.parent();

                        // Remove cover element
                        if (_parent.data('wplHasCover'))
                            _parent.find('.realtyna-ajaxloader-cover').remove();

                        _parent.spin(false).removeData('wplHasSpin wplHasCover wplCurrentPos');

                        _spinCountHide++;
                    });
                }

                clearTimeout(_hideTimeout);

                return _spinCountHide || false;
            }

        };

    })();

    // + Javascript Functions //////////////////////////////////////////////////////////////////////////////////////


    // DOM Ready ///////////////////////////////////////////////////////////////////////////////////////////////////

    $(function () {
        var initCount = Realtyna.start();

        /*Realtyna.wpl.showMessage({
         title: "My New Post2",
         msgBody: "This is my first post!",
         type: 'confirm',
         defaultBtn: 'yes'
         });

         Realtyna.wpl.showMessage({
         title: "My New Post1",
         msgBody: "This is my secodn post!",
         type: 'confirm',
         defaultBtn: 'no'
         });*/

    });


    /*
     Realtyna Lightbox Plugin
     @Author Steve M. @ Realtyna UI Department
     @Copyright 2015 Realtyna Inc.
     @license Realtyna Inc.
     @Version 1.9.6.6
     */
    $._realtyna.fn.lightbox = function(){

        var defaults = {
            // Layout options
            minWidth  : 200,
            minHeight : 130,
            maxWidth  : 9999,
            maxHeight : 9999,
            width: 800,
            height: 500,
            title: '',
            autoSize: true,
            zIndex: 500001,

            position: 'center', // 'right'

            showLoading: true,
            reloadPage: false,
            clearContent: true,
            multiple:false,
            closeOnOverlay:true,

            loading: {
                color: '#000'
            },

            addTo: 'body',
            overlayClass: '',
            wrapperClass: '',
            contentClass: '',
            closeClass: '',

            ajaxType    : 'POST',
            ajaxDataType: 'html',
            ajaxData    : undefined,
            ajaxURL     : undefined,

            // CSS Classes
            cssClasses: {
                lock    : 'realtyna-lightbox-lock',
                overlay : 'realtyna-lightbox-overlay',
                wrap    : 'realtyna-lightbox-wp',
                title   : 'realtyna-lightbox-title',
                content : 'realtyna-lightbox-cnt',
                close   : 'realtyna-lightbox-close-btn',
                error   : 'realtyna-lightbox-error',
                ajax    : 'realtyna-lightbox-ajax',
                placeholder: 'realtyna-lightbox-placeholder',
                textOnlyWrap: 'realtyna-lightbox-text-wrap',
                textOnlyContainer: 'realtyna-lightbox-text-cnt',
                rightPos: 'realtyna-lightbox-right-pos'
            },

            effects: {
                fadeIn      : 'wpl-fx-fadeIn',
                fadeOut     : 'wpl-fx-fadeOut',
                showOverlay : 'wpl-fx-fadeIn',
                showBox     : 'wpl-fx-fadeInBottom',
                hideBox     : 'wpl-fx-fadeOutBottom'
            },
            errors:{
                notFound    : "Selected content not found!",
                unexpected  : "An unexpected error happen. Please try again."
            },
            callbacks: {
                beforeOpen: function(){},
                afterOpen: function(){},
                afterShow: function(){},
                afterClose: function(){}
            }

        };

        var self    = this,
            elements= null,
            R       = Realtyna,

            obj     = null,
            opts    = {},
            classes = null,
            fx      = null,

            placeholder = null,

            isOpen      = false,
            isOpened    = false,
            isLoading   = false,

            overlay = null,
            wrap    = null,
            close   = null,
            content = null,
            loader  = null,
            current = null,
            inner   = null,
            error   = null,
            href    = null,
            watcher = null,
            type    = 'inline',

            IDs     =  {
                overlay : 'realtyna-js-lightbox-overlay',
                wrap    : 'realtyna-js-lightbox-wrapper',
                content : 'realtyna-js-lightbox-content',
                closeBtn: 'realtyna-js-lightbox-close'
            }

        // Initilaize local Object
        var L = $._realtyna.lightbox.fn = {};

        L._beforeOpen  = [];
        L._afterOpen   = [];
        L._afterShow   = [];
        L._afterClose  = [];

        L.open = function(obj){
            obj         = obj;
            isLoading   = true;
            opts        = obj.data('realtyna-lightbox-obj-opts');
            classes     = opts.cssClasses;
            fx          = opts.effects;
            placeholder = classes.placeholder;

            _buildLayout(obj, function (){

                type = _getType(obj);

                switch (type){
                    case 'inline':
                        _renderInline(obj);
                        break;
                    case 'ajax':
                        _renderURL(obj);
                        break;
                    default:
                        return false;
                }

            });
        }

        L.trigger = function (name) {
            var callbacks = opts.callbacks,
                evProp    = L['_' + name];

            // Add option callbacks to the array
            if(callbacks.hasOwnProperty(name) && callbacks[name] !== null){
                evProp.unshift(callbacks[name]);
            }

            for(var i= 0;i < evProp.length; ++i){
                evProp[i].call();
            }

            return true;
        }

        L.getInlineOptions = function(options){
            if(!options)
                return false;

            return R.options2JSON(options);
        }

        L._error = function (obj, err) {
            inner = $('<div/>').text(err).addClass(classes.error);

            inner.invisible().appendTo(content);

            isOpen = true;

            L.trigger('beforeOpen');

            _resetView(obj);

            wrap.unbind('onShow').bind('onShow',function(){

                inner.makevisible().hide().fadeIn();

                R.ajaxLoader.hide(loader);

                L.trigger('afterShow');

                isLoading = false;
                isOpened  = true;

            });

        }

        function _getInlineOptions(obj){
            var _opts      = obj.data('realtyna-lightbox-obj-opts'),
                inlineOpts = L.getInlineOptions(obj.attr('data-realtyna-lightbox-opts'));

            return $.extend(true, {}, _opts, inlineOpts);
        }

        function _buildLayout(obj, callback){

            // Initialize body
            $('html').addClass(classes.lock);

            // Create Overlay
            overlay = $('<div />').attr('id',IDs.overlay).addClass(R.getClass(classes.overlay, opts.overlayClass)).css({zIndex: opts.zIndex});

            // Create Wrapper
            wrap = $('<div />').attr('id',IDs.wrap).addClass(R.getClass(classes.wrap,opts.wrapperClass));
            wrap.appendTo(overlay).addClass((opts.position == 'center')? fx.showBox : classes.rightPos) ;

            // Create close button
            close = $('<div />').attr('id',IDs.closeBtn).addClass(R.getClass(classes.close,opts.closeClass));
            close.hide().appendTo(wrap);

            // Create content div to wrapper
            content = $('<div />').attr('id',IDs.content).addClass(R.getClass(classes.content,opts.contentClass));
            content.appendTo(wrap);

            // Create all element to body
            overlay.appendTo(opts.addTo);

            // Set necessary events
            _EventsOn(obj);

            L.trigger('beforeOpen');

            _resetView(obj);

            // Show Loading if necessary
            if(opts.showLoading){
                loader = R.ajaxLoader.show(wrap, 'normal', 'center', false, opts.loading.color);
            }


            callback.call();
        }

        function _resetView(obj){

            var viewport = R.getBrowserSizes().browser(),
                left,
                top,
                height,
                width;

            opts = _getInlineOptions(obj);

            if(isOpen){

                var oldPos = inner.css('position');

                inner.css({
                    position: 'absolute'
                });

                if(opts.autoSize){
                    width  = inner.not('script, style').eq(0).outerWidth();
                    height = inner.not('script, style').eq(0).outerHeight();
                }else{
                    width  = opts.width;
                    height = opts.height;
                }



                inner.css({
                    position: (oldPos === 'static')? '' : oldPos
                });

                if(opts.position == 'center') {

                    width = Math.min(width + 2, opts.maxWidth);
                    height = Math.min(height + 2, opts.maxHeight);

                    width = Math.max(width, opts.minWidth);
                    height = Math.max(height, opts.minHeight);

                    left = viewport.width / 2 - width / 2;
                    top = Math.max(viewport.height / 2 - height / 2, 40);

                    if (!isOpened) {

                        if($('body').hasClass('wpl_rtl')) {
                            wrap.animate({
                                right: left,
                                top: top,
                                width: width,
                                height: height
                            }, 450);
                        } else{
                            wrap.animate({
                                left: left,
                                top: top,
                                width: width,
                                height: height
                            }, 450);
                        }

                        setTimeout(function () {

                            if (height > opts.minHeight)
                                height = (opts.autoSize) ? 'auto' : height;

                            wrap.css({height: height});
                            overlay.css({overflowY: 'auto'});
                            wrap.trigger('onShow');
                            L.trigger('afterOpen');

                        }, 600);

                    } else {

                        if($('body').hasClass('wpl_rtl')) {
                            wrap.css({
                                height: (opts.autoSize) ? 'auto' : height,
                                right: left,
                                top: top,
                                width: width
                            });
                        } else {
                            wrap.css({
                                height: (opts.autoSize) ? 'auto' : height,
                                left: left,
                                top: top,
                                width: width
                            });
                        }

                    }
                }else if(opts.position == 'right'){

                        top = 0;

                        if ($('#wpadminbar').length) {
                            top = $('#wpadminbar').outerHeight();
                        }
                    if($('body').hasClass('wpl_rtl')) {
                        wrap.css({
                            right: '100%',
                            top: top,
                            height: (top === 0) ? '100%' : 'calc(100% - ' + top + 'px)',
                            width: width
                        }).animate({
                            marginRight: '-' + width
                        }, 450);

                        setTimeout(function () {

                            wrap.trigger('onShow');
                            L.trigger('afterOpen');

                        }, 600);
                    } else {

                        wrap.css({
                            left: '100%',
                            top: top,
                            height: (top === 0) ? '100%' : 'calc(100% - ' + top + 'px)',
                            width: width
                        }).animate({
                            marginLeft: '-' + width
                        }, 450);

                        setTimeout(function () {

                            wrap.trigger('onShow');
                            L.trigger('afterOpen');

                        }, 600);
                    }

                }

            } else {
                if(opts.showLoading) {
                    if ($('body').hasClass('wpl_rtl')) {
                        if (opts.position == 'center') {
                            wrap.css({
                                width: 100,
                                height: 100,
                                right: viewport.width / 2 - 50,
                                top: viewport.height / 2 - 50
                            });
                        } else if (opts.position == 'right') {

                            wrap.css({
                                width: 100,
                                height: '100%',
                                right: '100%',
                                top: 0
                            }).animate({
                                marginRight: -100
                            }, 100);

                        }
                    } else {
                        if (opts.position == 'center') {
                            wrap.css({
                                width: 100,
                                height: 100,
                                left: viewport.width / 2 - 50,
                                top: viewport.height / 2 - 50
                            });
                        } else if (opts.position == 'right') {

                            wrap.css({
                                width: 100,
                                height: '100%',
                                left: '100%',
                                top: 0
                            }).animate({
                                marginLeft: -100
                            }, 100);

                        }
                    }
                } else {
                    width  = opts.width;
                    height = opts.height;
                    if(opts.position == 'center') {

                        width = Math.min(width, opts.maxWidth);
                        height = Math.min(height, opts.maxHeight);

                        width = Math.max(width, opts.minWidth);
                        height = Math.max(height, opts.minHeight);

                        left = viewport.width / 2 - width / 2;
                        top = Math.max(viewport.height / 2 - height / 2, 40);
                        
                        if($('body').hasClass('wpl_rtl')) {
                            wrap.css({
                                height: (opts.autoSize) ? 'auto' : height,
                                right: left,
                                top: top,
                                width: width,
                                border: 'none',
                                boxShadow: 'none'
                            });
                        } else {
                            wrap.css({
                                height: (opts.autoSize) ? 'auto' : height,
                                left: left,
                                top: top,
                                width: width,
                                border: 'none',
                                boxShadow: 'none'
                            });
                        }
                    }else if(opts.position == 'right'){

                        top = 0;

                        if ($('#wpadminbar').length) {
                            top = $('#wpadminbar').outerHeight();
                        }
                        if($('body').hasClass('wpl_rtl')) {
                            wrap.css({
                                right: '100%',
                                top: top,
                                height: (top === 0) ? '100%' : 'calc(100% - ' + top + 'px)',
                                width: width,
                                border: 'none',
                                boxShadow: 'none'
                            }).animate({
                                marginRight: '-' + width
                            }, 100);
                        } else {
                            wrap.css({
                                left: '100%',
                                top: top,
                                height: (top === 0) ? '100%' : 'calc(100% - ' + top + 'px)',
                                width: width,
                                border: 'none',
                                boxShadow: 'none'
                            }).animate({
                                marginLeft: '-' + width
                            }, 100);
                        }

                    }
                }

            }

            return true;
        }

        function _getType(elm){
            var _href;

            if(typeof elm.attr('href') != 'undefined'){
                _href = elm.attr('href');
            }else if(typeof elm.attr('data-realtyna-href') != 'undefined'){
                _href = elm.attr('data-realtyna-href');
            }else{
                $._realtyna.lightbox.close();
            }

            if(_href.length > 0){
                if(_href[0] == '#'){
                    return 'inline';
                }else{
                    return 'ajax';
                }
            }
        }

        function _EventsOn(obj){


            // Close when click on overlay

            $(overlay).on('click.realtyna-lightbox',function(evn){
                var _self = $(evn.target);
                if(opts.closeOnOverlay && !_self.hasClass(classes.wrap) && _self.parents('#' + IDs.wrap).length == 0)
                    self.close();
            });

            $(close).on('click.realtyna-lightbox',function(evn){
                evn.preventDefault();
                self.close();
            });

            // Reset position on window resize
            $(window).on('resize.realtyna-lightbox',function(){
                window.resizeEvt;
                $(window).on('resize.realtyna-lightbox', function()
                {
                    if(opts.position !== 'right')
                    {
                        clearTimeout(window.resizeEvt);
                        window.resizeEvt = setTimeout(function()
                        {
                            _resetView(obj);

                        }, 250);
                    }
                });
            });

            $(document).on('keydown.realtyna-lightbox', function(e) {
                if (opts.closeOnOverlay && (e.which || e.keyCode) === 27) {

                    e.preventDefault();

                    self.close();

                }
            });

        }

        function _EventsOff(){
            $(window).off('resize.realtyna-lightbox');
            $(document).off('keydown.realtyna-lightbox');
        }

        function _renderInline(elm){
            var rxURL = /^#[a-z]+[\w-]*$/gi;

            href = elm.attr('href') || elm.attr('data-realtyna-href');

            // Is element id valid
            if(!rxURL.test(href))
                return;

            current = $(href);

            // If current element not found
            if(current.length === 0) {
                L._error(elm, opts.errors.notFound);
                return;
            }

            // Put current on right place
            if(!current.data(placeholder))
                current.data(placeholder, $('<div class="' + placeholder + '"></div>').insertAfter( current ).hide());

            current = current.show().invisible().detach();

            isOpen = true;

            $(content).append(current);

            wrap.bind('onReset',function(){

                if($(this).find(current).length){

                    if(opts.autoSize)
                        wrap.height(current.outerHeight());

                    current.hide().replaceAll(current.data(placeholder)).data(placeholder,false);

                    // Clear container div need to clear after show
                    if(opts.clearContent)
                        current.html('');

                }

            });

            wrap.bind('onShow',function(){

                current.makevisible().hide().fadeIn();

                close.fadeIn();

                R.ajaxLoader.hide(loader);

                isLoading = false;
                isOpened  = true;

                L.trigger('afterShow');

            });

            // Proceed when the content actually loaded.
            watcher = setInterval(function(){
                if(current.html().length > 0) {

                    clearInterval(watcher);

                    // Add Sample content
                    if(current.children().length == 0) {

                        current.wrapInner('<div class="' + opts.cssClasses.textOnlyContainer + '"/>');
                        current.prepend('<h2 class="'+ opts.cssClasses.title+'">' + opts.title + '</h2>');
                        current.wrapInner('<div class="' + opts.cssClasses.textOnlyWrap + '"/>');

                    }

                    // Wrap content to container
                    if(current.find('.' + opts.cssClasses.title).length != 0 && current.find('.' + opts.cssClasses.textOnlyContainer).length == 0){
                        current.wrapInner('<div class="' + opts.cssClasses.textOnlyContainer + '"/>');
                    }


                    // Add Title if not available
                    if(current.find('.' + opts.cssClasses.title).length == 0 && current.find('h2').length == 0){
                        current.prepend('<h2 class="'+ opts.cssClasses.title+'">' + opts.title + '</h2>');
                    }

                    // Wrap all elements
                    if(current.find('.' + opts.cssClasses.textOnlyWrap).length == 0){
                        current.wrapInner('<div class="' + opts.cssClasses.textOnlyWrap + '"/>');
                    }

                    inner = current.children();
                    _resetView(elm);

                }

            }, 300);

        }

        function _renderURL(elm){

            var data = elm.attr('data-realtyna-lightbox-data') || {};

            if(typeof opts.ajaxData != 'undefined')
                data = opts.ajaxData;


            href = elm.attr('href') || elm.attr('data-realtyna-href');

            if(typeof opts.ajaxURL != 'undefined')
                href = opts.ajaxURL;


            $.ajax({
                type: opts.ajaxType,
                dataType: opts.ajaxDataType,
                url: href,
                data: data,
                error: function (jqXHR, textStatus) {

                    L._error(elm, opts.errors.unexpected);
                },
                success: function (data, textStatus) {
                    if (textStatus === 'success') {
                        current = $('<div/>').addClass(classes.ajax).html(data);
                        inner   = current.children();

                        isOpen = true;

                        L.trigger('beforeOpen');

                        content.append(current.show().invisible());

                        _resetView(elm);
                    }
                }
            });

            wrap.bind('onShow',function(){

                current.makevisible().hide().fadeIn();

                close.fadeIn();

                R.ajaxLoader.hide(loader);

                isLoading = false;
                isOpened  = true;

                L.trigger('afterShow');

            });

        }


        // Public Methods

        this.initialize = function(options, setClick, el){
            elements    = el;

            if(typeof setClick != 'undefined' && setClick) {

                elements.each(function () {

                    // Not init initialized elements
                    if(!$(this).data('realtyna-lightbox-obj-opts')){

                        var _opts = $.extend(true, {}, defaults, options),
                            inlineOpts = L.getInlineOptions($(this).attr('data-realtyna-lightbox-opts'));

                        _opts = $.extend(true, {}, _opts, inlineOpts);

                        $(this).data('realtyna-lightbox-obj-opts', _opts);

                        $(this).on('click.realtyna-lightbox', function (evn) {

                            if (!isLoading) {
                                evn.preventDefault();

                                L.open($(this), evn);
                            }

                        });
                    }
                });


            }

            return this;
        };

        this.open = function (selector,opt) {
            var _options = opt || {};

            elements = R.isQuery(selector)? selector : $(selector);

            self.initialize(_options, true, elements);

            return L.open(elements);
        };

        this.close = function(){
            if(isOpen){
                wrap.trigger('onReset');

                isOpen    = false;
                isOpened  = false;
                isLoading = false;

                L._beforeOpen = [];
                L._afterOpen  = [];
                L._afterShow  = [];
                L._afterClose  = [];

                $(wrap).removeClass(fx.showBox + ' ' + opts.position);

                if(opts.position == 'center'){

                    $(wrap).addClass(fx.hideBox);

                } else if(opts.position == 'right'){

                    $(wrap).animate({
                        marginleft: 0
                    }, 450);

                }


                $(overlay).fadeOut(600,function(){

                    _EventsOff();

                    $(this).remove();

                    L.trigger('afterClose');

                    $('html').removeClass(classes.lock);

                    if(opts.reloadPage)
                        location.reload();

                });

                return true;
            }

            return false;

        };

        this.on = function(evn,func){
            var eventName = '_' + evn;
            if(L.hasOwnProperty(eventName)){
                L[eventName].push(func);
            }

            return true;
        };

    };
    $._realtyna.lightbox = (function () {
        var lbInstance;

        return{
            version: '1.9.6.6',

            init: function (element, options) {
                if(!element)
                    return false;

                if(!lbInstance){
                    lbInstance = new $._realtyna.fn.lightbox();

                    this.open = lbInstance.open;
                    this.on = lbInstance.on;
                    this.close = lbInstance.close;
                }

                return lbInstance.initialize(options, true, element);
            },
            initObject : function () {

                if(!lbInstance){
                    lbInstance = new $._realtyna.fn.lightbox();

                    this.open = lbInstance.open;
                    this.on = lbInstance.on;
                    this.close = lbInstance.close;
                }

                return lbInstance;
            },
            open: null,
            on: null,
            close: null
        }

    })();

    /*
     @preserve Realtyna Tagging Plugin
     @Author Steve M. @ Realtyna UI Department
     @Copyright 2015 Realtyna Inc.
     */
    $._realtyna.tagging = { version: '1.1.1' };
    $._realtyna.tagging.init = function(that,options){

        var defaults = {
            maxWidth: 'auto',
            sortable: true
        }

        var R       = Realtyna,
            self    = that,

            opts,

            tmpls   = {
                tagWrap: '<ul class="wpl-gen-tagging-wp"><li class="wpl-gen-tagging-input-wp"></li><li class="wpl-gen-tagging-values-wp"><input class="wpl-gen-tagging-values" type="hidden" /></li></ul>',
                tagItem: '<li class="wpl-gen-tagging-tag"><span class="wpl-gen-tagging-title"></span><span class="wpl-gen-tagging-close-btn"></span></li>'
            };

        opts = $.extend({},defaults, options);

        // Initialize local Object
        var T = $._realtyna.tagging.fn = {};

        T.generateValue = function(tagWrap, saveIt){
            var titlesItems = tagWrap.find('li').not('.wpl-gen-tagging-input-wp,.wpl-gen-tagging-values-wp').find('.wpl-gen-tagging-title'),
                value = [];

            titlesItems.each(function(){
                value.push($(this).text());
            });

            tagWrap.find('.wpl-gen-tagging-values').val(JSON.stringify(value)).trigger('onchange');

            if(typeof saveIt == "undefined")
                saveIt = true;

            if(saveIt)
                tagWrap.find('.wpl-gen-tagging-values').trigger('onchange');

        };

        T.createItem = function (itemValue, tagWrap, saveIt) {

            var newItem;

            if(typeof saveIt == 'undefined')
                saveIt = true;

            tagWrap.find('.wpl-gen-tagging-input-wp').before(tmpls.tagItem);

            newItem = tagWrap.find('.wpl-gen-tagging-input-wp').prev();

            newItem.find('.wpl-gen-tagging-title').text(itemValue);

            if(saveIt){
                T.generateValue(tagWrap, saveIt);
                tagWrap.sortable("refresh");
            }

            newItem.find('.wpl-gen-tagging-close-btn').on('click', function(e){
                e.preventDefault();

                var closeBtn = $(this),
                    newItem  = closeBtn.parent();

                if(typeof newItem.data('press-count') == 'undefined') {
                    newItem.data('press-count', 1);
                }

                if (newItem.data('press-count') == 1) {

                    newItem.addClass('wpl-gen-tagging-will-remove');
                    newItem.data('press-count', 2);

                } else if (newItem.data('press-count') == 2) {

                    newItem.children().fadeOut(300);

                    newItem.animate({width: 'toggle'},250,function(){
                        $(this).remove();

                        T.generateValue(tagWrap);
                    });

                }
            });

        };

        T.initialize = function (item){
            var tagWrap;

            item.after(tmpls.tagWrap);

            tagWrap = item.next();

            tagWrap.css({
                maxWidth: opts.maxWidth
            });

            item = item.detach();
            tagWrap.find('.wpl-gen-tagging-input-wp').append(item);

            tagWrap.find('.wpl-gen-tagging-values').attr({
                name: item.attr('name'),
                id: item.attr('id'),
                value: item.val(),
                onchange: item.attr('onchange')
            });

            // Add init value as item
            if(item.val().length > 0){
                var values = item.val();

                if(R.isValidJson(values)){
                    values = JSON.parse(values);
                    for(var i= 0; i < values.length; ++i){
                        T.createItem(values[i],tagWrap, false);
                    }
                }

            }

            tagWrap.sortable({
                axis: "x",
                items: '.wpl-gen-tagging-tag',
                handle: '.wpl-gen-tagging-title',
                placeholder: 'wpl-util-placeholder',
                update: function( event, ui ) {
                    T.generateValue(tagWrap);
                }
            });

            item.attr({
                name: null,
                id: null,
                onchange: null,
                value: ''
            });

            // Send focus to input element
            tagWrap.on('click',function(){
                item.focus();
            });


            item.on('keypress',function(e){

                if(e.which == 13 && item.val() !== ''){
                    e.preventDefault();

                    T.createItem(item.val(), tagWrap);

                    item.attr('name','');
                    item.val('');

                    tagWrap.find('.wpl-gen-tagging-will-remove').data('press-count', 1).removeClass('wpl-gen-tagging-will-remove');

                }else if(e.which == 124){
                    e.preventDefault();
                }

            });


            item.on('keyup',function(e){

                // Remove the last item on 2 press backspace
                if(e.which == 8 && item.val().length == 0){
                    tagWrap.find('.wpl-gen-tagging-input-wp').prev().find('.wpl-gen-tagging-close-btn').trigger('click');
                }

            });
        };

        self.each(function () {

            T.initialize($(this));

        });
    };


    /*
     @preserve Realtyna Utility Plugins
     @Author Steve M. @ Realtyna UI Department
     @Copyright 2015 Realtyna Inc.
     */
    $.fn.makevisible = function() {
        return this.css('visibility', 'visible');
    };

    $.fn.invisible = function() {
        return this.css('visibility', 'hidden');
    };

    $.fn.visibilityToggle = function() {
        return this.css('visibility', function(i, visibility) {
            return (visibility == 'visible') ? 'hidden' : 'visible';
        });
    };

}(jQuery, window, document);


/* Chosen v1.0.0 | (c) 2011-2013 by Harvest | MIT License, https://github.com/harvesthq/chosen/blob/master/LICENSE.md */
(function(){var a,AbstractChosen,Chosen,SelectParser,b,c={}.hasOwnProperty,d=function(a,b){function d(){this.constructor=a}for(var e in b)c.call(b,e)&&(a[e]=b[e]);return d.prototype=b.prototype,a.prototype=new d,a.__super__=b.prototype,a};SelectParser=function(){function SelectParser(){this.options_index=0,this.parsed=[]}return SelectParser.prototype.add_node=function(a){return"OPTGROUP"===a.nodeName.toUpperCase()?this.add_group(a):this.add_option(a)},SelectParser.prototype.add_group=function(a){var b,c,d,e,f,g;for(b=this.parsed.length,this.parsed.push({array_index:b,group:!0,label:this.escapeExpression(a.label),children:0,disabled:a.disabled}),f=a.childNodes,g=[],d=0,e=f.length;e>d;d++)c=f[d],g.push(this.add_option(c,b,a.disabled));return g},SelectParser.prototype.add_option=function(a,b,c){return"OPTION"===a.nodeName.toUpperCase()?(""!==a.text?(null!=b&&(this.parsed[b].children+=1),this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,value:a.value,text:a.text,html:a.innerHTML,selected:a.selected,disabled:c===!0?c:a.disabled,group_array_index:b,classes:a.className,style:a.style.cssText})):this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,empty:!0}),this.options_index+=1):void 0},SelectParser.prototype.escapeExpression=function(a){var b,c;return null==a||a===!1?"":/[\&\<\>\"\'\`]/.test(a)?(b={"<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#x27;","`":"&#x60;"},c=/&(?!\w+;)|[\<\>\"\'\`]/g,a.replace(c,function(a){return b[a]||"&amp;"})):a},SelectParser}(),SelectParser.select_to_array=function(a){var b,c,d,e,f;for(c=new SelectParser,f=a.childNodes,d=0,e=f.length;e>d;d++)b=f[d],c.add_node(b);return c.parsed},AbstractChosen=function(){function AbstractChosen(a,b){this.form_field=a,this.options=null!=b?b:{},AbstractChosen.browser_is_supported()&&(this.is_multiple=this.form_field.multiple,this.set_default_text(),this.set_default_values(),this.setup(),this.set_up_html(),this.register_observers())}return AbstractChosen.prototype.set_default_values=function(){var a=this;return this.click_test_action=function(b){return a.test_active_click(b)},this.activate_action=function(b){return a.activate_field(b)},this.active_field=!1,this.mouse_on_container=!1,this.results_showing=!1,this.result_highlighted=null,this.result_single_selected=null,this.allow_single_deselect=null!=this.options.allow_single_deselect&&null!=this.form_field.options[0]&&""===this.form_field.options[0].text?this.options.allow_single_deselect:!1,this.disable_search_threshold=this.options.disable_search_threshold||0,this.disable_search=this.options.disable_search||!1,this.enable_split_word_search=null!=this.options.enable_split_word_search?this.options.enable_split_word_search:!0,this.group_search=null!=this.options.group_search?this.options.group_search:!0,this.search_contains=this.options.search_contains||!1,this.single_backstroke_delete=null!=this.options.single_backstroke_delete?this.options.single_backstroke_delete:!0,this.max_selected_options=this.options.max_selected_options||1/0,this.inherit_select_classes=this.options.inherit_select_classes||!1,this.display_selected_options=null!=this.options.display_selected_options?this.options.display_selected_options:!0,this.display_disabled_options=null!=this.options.display_disabled_options?this.options.display_disabled_options:!0},AbstractChosen.prototype.set_default_text=function(){return this.default_text=this.form_field.getAttribute("data-placeholder")?this.form_field.getAttribute("data-placeholder"):this.is_multiple?this.options.placeholder_text_multiple||this.options.placeholder_text||AbstractChosen.default_multiple_text:this.options.placeholder_text_single||this.options.placeholder_text||AbstractChosen.default_single_text,this.results_none_found=this.form_field.getAttribute("data-no_results_text")||this.options.no_results_text||AbstractChosen.default_no_result_text},AbstractChosen.prototype.mouse_enter=function(){return this.mouse_on_container=!0},AbstractChosen.prototype.mouse_leave=function(){return this.mouse_on_container=!1},AbstractChosen.prototype.input_focus=function(){var a=this;if(this.is_multiple){if(!this.active_field)return setTimeout(function(){return a.container_mousedown()},50)}else if(!this.active_field)return this.activate_field()},AbstractChosen.prototype.input_blur=function(){var a=this;return this.mouse_on_container?void 0:(this.active_field=!1,setTimeout(function(){return a.blur_test()},100))},AbstractChosen.prototype.results_option_build=function(a){var b,c,d,e,f;for(b="",f=this.results_data,d=0,e=f.length;e>d;d++)c=f[d],b+=c.group?this.result_add_group(c):this.result_add_option(c),(null!=a?a.first:void 0)&&(c.selected&&this.is_multiple?this.choice_build(c):c.selected&&!this.is_multiple&&this.single_set_selected_text(c.text));return b},AbstractChosen.prototype.result_add_option=function(a){var b,c;return a.search_match?this.include_option_in_results(a)?(b=[],a.disabled||a.selected&&this.is_multiple||b.push("active-result"),!a.disabled||a.selected&&this.is_multiple||b.push("disabled-result"),a.selected&&b.push("result-selected"),null!=a.group_array_index&&b.push("group-option"),""!==a.classes&&b.push(a.classes),c=""!==a.style.cssText?' style="'+a.style+'"':"",'<li class="'+b.join(" ")+'"'+c+' data-option-array-index="'+a.array_index+'">'+a.search_text+"</li>"):"":""},AbstractChosen.prototype.result_add_group=function(a){return a.search_match||a.group_match?a.active_options>0?'<li class="group-result">'+a.search_text+"</li>":"":""},AbstractChosen.prototype.results_update_field=function(){return this.set_default_text(),this.is_multiple||this.results_reset_cleanup(),this.result_clear_highlight(),this.result_single_selected=null,this.results_build(),this.results_showing?this.winnow_results():void 0},AbstractChosen.prototype.results_toggle=function(){return this.results_showing?this.results_hide():this.results_show()},AbstractChosen.prototype.results_search=function(){return this.results_showing?this.winnow_results():this.results_show()},AbstractChosen.prototype.winnow_results=function(){var a,b,c,d,e,f,g,h,i,j,k,l,m;for(this.no_results_clear(),e=0,g=this.get_search_text(),a=g.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),d=this.search_contains?"":"^",c=new RegExp(d+a,"i"),j=new RegExp(a,"i"),m=this.results_data,k=0,l=m.length;l>k;k++)b=m[k],b.search_match=!1,f=null,this.include_option_in_results(b)&&(b.group&&(b.group_match=!1,b.active_options=0),null!=b.group_array_index&&this.results_data[b.group_array_index]&&(f=this.results_data[b.group_array_index],0===f.active_options&&f.search_match&&(e+=1),f.active_options+=1),(!b.group||this.group_search)&&(b.search_text=b.group?b.label:b.html,b.search_match=this.search_string_match(b.search_text,c),b.search_match&&!b.group&&(e+=1),b.search_match?(g.length&&(h=b.search_text.search(j),i=b.search_text.substr(0,h+g.length)+"</em>"+b.search_text.substr(h+g.length),b.search_text=i.substr(0,h)+"<em>"+i.substr(h)),null!=f&&(f.group_match=!0)):null!=b.group_array_index&&this.results_data[b.group_array_index].search_match&&(b.search_match=!0)));return this.result_clear_highlight(),1>e&&g.length?(this.update_results_content(""),this.no_results(g)):(this.update_results_content(this.results_option_build()),this.winnow_results_set_highlight())},AbstractChosen.prototype.search_string_match=function(a,b){var c,d,e,f;if(b.test(a))return!0;if(this.enable_split_word_search&&(a.indexOf(" ")>=0||0===a.indexOf("["))&&(d=a.replace(/\[|\]/g,"").split(" "),d.length))for(e=0,f=d.length;f>e;e++)if(c=d[e],b.test(c))return!0},AbstractChosen.prototype.choices_count=function(){var a,b,c,d;if(null!=this.selected_option_count)return this.selected_option_count;for(this.selected_option_count=0,d=this.form_field.options,b=0,c=d.length;c>b;b++)a=d[b],a.selected&&(this.selected_option_count+=1);return this.selected_option_count},AbstractChosen.prototype.choices_click=function(a){return a.preventDefault(),this.results_showing||this.is_disabled?void 0:this.results_show()},AbstractChosen.prototype.keyup_checker=function(a){var b,c;switch(b=null!=(c=a.which)?c:a.keyCode,this.search_field_scale(),b){case 8:if(this.is_multiple&&this.backstroke_length<1&&this.choices_count()>0)return this.keydown_backstroke();if(!this.pending_backstroke)return this.result_clear_highlight(),this.results_search();break;case 13:if(a.preventDefault(),this.results_showing)return this.result_select(a);break;case 27:return this.results_showing&&this.results_hide(),!0;case 9:case 38:case 40:case 16:case 91:case 17:break;default:return this.results_search()}},AbstractChosen.prototype.container_width=function(){return null!=this.options.width?this.options.width:""+this.form_field.offsetWidth+"px"},AbstractChosen.prototype.include_option_in_results=function(a){return this.is_multiple&&!this.display_selected_options&&a.selected?!1:!this.display_disabled_options&&a.disabled?!1:a.empty?!1:!0},AbstractChosen.browser_is_supported=function(){return true;},AbstractChosen.default_multiple_text="Select Some Options",AbstractChosen.default_single_text="Select an Option",AbstractChosen.default_no_result_text="No results match",AbstractChosen}(),a=jQuery,a.fn.extend({chosen:function(b){return AbstractChosen.browser_is_supported()?this.each(function(){var c,d;c=a(this),d=c.data("chosen"),"destroy"===b&&d?d.destroy():d||c.data("chosen",new Chosen(this,b))}):this}}),Chosen=function(c){function Chosen(){return b=Chosen.__super__.constructor.apply(this,arguments)}return d(Chosen,c),Chosen.prototype.setup=function(){return this.form_field_jq=a(this.form_field),this.current_selectedIndex=this.form_field.selectedIndex,this.is_rtl=this.form_field_jq.hasClass("chosen-rtl")},Chosen.prototype.set_up_html=function(){var b,c;return b=["chosen-container"],b.push("chosen-container-"+(this.is_multiple?"multi":"single")),this.inherit_select_classes&&this.form_field.className&&b.push(this.form_field.className),this.is_rtl&&b.push("chosen-rtl"),c={"class":b.join(" "),style:"width: "+this.container_width()+";",title:this.form_field.title},this.form_field.id.length&&(c.id=this.form_field.id.replace(/[^\w]/g,"_")+"_chosen"),this.container=a("<div />",c),this.is_multiple?this.container.html('<ul class="chosen-choices"><li class="search-field"><input type="text" value="'+this.default_text+'" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chosen-drop"><ul class="chosen-results"></ul></div>'):this.container.html('<a class="chosen-single chosen-default" tabindex="-1"><span>'+this.default_text+'</span><div><b></b></div></a><div class="chosen-drop"><div class="chosen-search"><input type="text" autocomplete="off" /></div><ul class="chosen-results"></ul></div>'),this.form_field_jq.hide().after(this.container),this.dropdown=this.container.find("div.chosen-drop").first(),this.search_field=this.container.find("input").first(),this.search_results=this.container.find("ul.chosen-results").first(),this.search_field_scale(),this.search_no_results=this.container.find("li.no-results").first(),this.is_multiple?(this.search_choices=this.container.find("ul.chosen-choices").first(),this.search_container=this.container.find("li.search-field").first()):(this.search_container=this.container.find("div.chosen-search").first(),this.selected_item=this.container.find(".chosen-single").first()),this.results_build(),this.set_tab_index(),this.set_label_behavior(),this.form_field_jq.trigger("chosen:ready",{chosen:this})},Chosen.prototype.register_observers=function(){var a=this;return this.container.bind("mousedown.chosen",function(b){a.container_mousedown(b)}),this.container.bind("mouseup.chosen",function(b){a.container_mouseup(b)}),this.container.bind("mouseenter.chosen",function(b){a.mouse_enter(b)}),this.container.bind("mouseleave.chosen",function(b){a.mouse_leave(b)}),this.search_results.bind("mouseup.chosen",function(b){a.search_results_mouseup(b)}),this.search_results.bind("mouseover.chosen",function(b){a.search_results_mouseover(b)}),this.search_results.bind("mouseout.chosen",function(b){a.search_results_mouseout(b)}),this.search_results.bind("mousewheel.chosen DOMMouseScroll.chosen",function(b){a.search_results_mousewheel(b)}),this.form_field_jq.bind("chosen:updated.chosen",function(b){a.results_update_field(b)}),this.form_field_jq.bind("chosen:activate.chosen",function(b){a.activate_field(b)}),this.form_field_jq.bind("chosen:open.chosen",function(b){a.container_mousedown(b)}),this.search_field.bind("blur.chosen",function(b){a.input_blur(b)}),this.search_field.bind("keyup.chosen",function(b){a.keyup_checker(b)}),this.search_field.bind("keydown.chosen",function(b){a.keydown_checker(b)}),this.search_field.bind("focus.chosen",function(b){a.input_focus(b)}),this.is_multiple?this.search_choices.bind("click.chosen",function(b){a.choices_click(b)}):this.container.bind("click.chosen",function(a){a.preventDefault()})},Chosen.prototype.destroy=function(){return a(document).unbind("click.chosen",this.click_test_action),this.search_field[0].tabIndex&&(this.form_field_jq[0].tabIndex=this.search_field[0].tabIndex),this.container.remove(),this.form_field_jq.removeData("chosen"),this.form_field_jq.show()},Chosen.prototype.search_field_disabled=function(){return this.is_disabled=this.form_field_jq[0].disabled,this.is_disabled?(this.container.addClass("chosen-disabled"),this.search_field[0].disabled=!0,this.is_multiple||this.selected_item.unbind("focus.chosen",this.activate_action),this.close_field()):(this.container.removeClass("chosen-disabled"),this.search_field[0].disabled=!1,this.is_multiple?void 0:this.selected_item.bind("focus.chosen",this.activate_action))},Chosen.prototype.container_mousedown=function(b){return this.is_disabled||(b&&"mousedown"===b.type&&!this.results_showing&&b.preventDefault(),null!=b&&a(b.target).hasClass("search-choice-close"))?void 0:(this.active_field?this.is_multiple||!b||a(b.target)[0]!==this.selected_item[0]&&!a(b.target).parents("a.chosen-single").length||(b.preventDefault(),this.results_toggle()):(this.is_multiple&&this.search_field.val(""),a(document).bind("click.chosen",this.click_test_action),this.results_show()),this.activate_field())},Chosen.prototype.container_mouseup=function(a){return"ABBR"!==a.target.nodeName||this.is_disabled?void 0:this.results_reset(a)},Chosen.prototype.search_results_mousewheel=function(a){var b,c,d;return b=-(null!=(c=a.originalEvent)?c.wheelDelta:void 0)||(null!=(d=a.originialEvent)?d.detail:void 0),null!=b?(a.preventDefault(),"DOMMouseScroll"===a.type&&(b=40*b),this.search_results.scrollTop(b+this.search_results.scrollTop())):void 0},Chosen.prototype.blur_test=function(){return!this.active_field&&this.container.hasClass("chosen-container-active")?this.close_field():void 0},Chosen.prototype.close_field=function(){return a(document).unbind("click.chosen",this.click_test_action),this.active_field=!1,this.results_hide(),this.container.removeClass("chosen-container-active"),this.clear_backstroke(),this.show_search_field_default(),this.search_field_scale()},Chosen.prototype.activate_field=function(){return this.container.addClass("chosen-container-active"),this.active_field=!0,this.search_field.val(this.search_field.val()),this.search_field.focus()},Chosen.prototype.test_active_click=function(b){return this.container.is(a(b.target).closest(".chosen-container"))?this.active_field=!0:this.close_field()},Chosen.prototype.results_build=function(){return this.parsing=!0,this.selected_option_count=null,this.results_data=SelectParser.select_to_array(this.form_field),this.is_multiple?this.search_choices.find("li.search-choice").remove():this.is_multiple||(this.single_set_selected_text(),this.disable_search||this.form_field.options.length<=this.disable_search_threshold?(this.search_field[0].readOnly=!0,this.container.addClass("chosen-container-single-nosearch")):(this.search_field[0].readOnly=!1,this.container.removeClass("chosen-container-single-nosearch"))),this.update_results_content(this.results_option_build({first:!0})),this.search_field_disabled(),this.show_search_field_default(),this.search_field_scale(),this.parsing=!1},Chosen.prototype.result_do_highlight=function(a){var b,c,d,e,f;if(a.length){if(this.result_clear_highlight(),this.result_highlight=a,this.result_highlight.addClass("highlighted"),d=parseInt(this.search_results.css("maxHeight"),10),f=this.search_results.scrollTop(),e=d+f,c=this.result_highlight.position().top+this.search_results.scrollTop(),b=c+this.result_highlight.outerHeight(),b>=e)return this.search_results.scrollTop(b-d>0?b-d:0);if(f>c)return this.search_results.scrollTop(c)}},Chosen.prototype.result_clear_highlight=function(){return this.result_highlight&&this.result_highlight.removeClass("highlighted"),this.result_highlight=null},Chosen.prototype.results_show=function(){return this.is_multiple&&this.max_selected_options<=this.choices_count()?(this.form_field_jq.trigger("chosen:maxselected",{chosen:this}),!1):(this.container.addClass("chosen-with-drop"),this.form_field_jq.trigger("chosen:showing_dropdown",{chosen:this}),this.results_showing=!0,this.search_field.focus(),this.search_field.val(this.search_field.val()),this.winnow_results())},Chosen.prototype.update_results_content=function(a){return this.search_results.html(a)},Chosen.prototype.results_hide=function(){return this.results_showing&&(this.result_clear_highlight(),this.container.removeClass("chosen-with-drop"),this.form_field_jq.trigger("chosen:hiding_dropdown",{chosen:this})),this.results_showing=!1},Chosen.prototype.set_tab_index=function(){var a;return this.form_field.tabIndex?(a=this.form_field.tabIndex,this.form_field.tabIndex=-1,this.search_field[0].tabIndex=a):void 0},Chosen.prototype.set_label_behavior=function(){var b=this;return this.form_field_label=this.form_field_jq.parents("label"),!this.form_field_label.length&&this.form_field.id.length&&(this.form_field_label=a("label[for='"+this.form_field.id+"']")),this.form_field_label.length>0?this.form_field_label.bind("click.chosen",function(a){return b.is_multiple?b.container_mousedown(a):b.activate_field()}):void 0},Chosen.prototype.show_search_field_default=function(){return this.is_multiple&&this.choices_count()<1&&!this.active_field?(this.search_field.val(this.default_text),this.search_field.addClass("default")):(this.search_field.val(""),this.search_field.removeClass("default"))},Chosen.prototype.search_results_mouseup=function(b){var c;return c=a(b.target).hasClass("active-result")?a(b.target):a(b.target).parents(".active-result").first(),c.length?(this.result_highlight=c,this.result_select(b),this.search_field.focus()):void 0},Chosen.prototype.search_results_mouseover=function(b){var c;return c=a(b.target).hasClass("active-result")?a(b.target):a(b.target).parents(".active-result").first(),c?this.result_do_highlight(c):void 0},Chosen.prototype.search_results_mouseout=function(b){return a(b.target).hasClass("active-result")?this.result_clear_highlight():void 0},Chosen.prototype.choice_build=function(b){var c,d,e=this;return c=a("<li />",{"class":"search-choice"}).html("<span>"+b.html+"</span>"),b.disabled?c.addClass("search-choice-disabled"):(d=a("<a />",{"class":"search-choice-close","data-option-array-index":b.array_index}),d.bind("click.chosen",function(a){return e.choice_destroy_link_click(a)}),c.append(d)),this.search_container.before(c)},Chosen.prototype.choice_destroy_link_click=function(b){return b.preventDefault(),b.stopPropagation(),this.is_disabled?void 0:this.choice_destroy(a(b.target))},Chosen.prototype.choice_destroy=function(a){return this.result_deselect(a[0].getAttribute("data-option-array-index"))?(this.show_search_field_default(),this.is_multiple&&this.choices_count()>0&&this.search_field.val().length<1&&this.results_hide(),a.parents("li").first().remove(),this.search_field_scale()):void 0},Chosen.prototype.results_reset=function(){return this.form_field.options[0].selected=!0,this.selected_option_count=null,this.single_set_selected_text(),this.show_search_field_default(),this.results_reset_cleanup(),this.form_field_jq.trigger("change"),this.active_field?this.results_hide():void 0},Chosen.prototype.results_reset_cleanup=function(){return this.current_selectedIndex=this.form_field.selectedIndex,this.selected_item.find("abbr").remove()},Chosen.prototype.result_select=function(a){var b,c,d;return this.result_highlight?(b=this.result_highlight,this.result_clear_highlight(),this.is_multiple&&this.max_selected_options<=this.choices_count()?(this.form_field_jq.trigger("chosen:maxselected",{chosen:this}),!1):(this.is_multiple?b.removeClass("active-result"):(this.result_single_selected&&(this.result_single_selected.removeClass("result-selected"),d=this.result_single_selected[0].getAttribute("data-option-array-index"),this.results_data[d].selected=!1),this.result_single_selected=b),b.addClass("result-selected"),c=this.results_data[b[0].getAttribute("data-option-array-index")],c.selected=!0,this.form_field.options[c.options_index].selected=!0,this.selected_option_count=null,this.is_multiple?this.choice_build(c):this.single_set_selected_text(c.text),(a.metaKey||a.ctrlKey)&&this.is_multiple||this.results_hide(),this.search_field.val(""),(this.is_multiple||this.form_field.selectedIndex!==this.current_selectedIndex)&&this.form_field_jq.trigger("change",{selected:this.form_field.options[c.options_index].value}),this.current_selectedIndex=this.form_field.selectedIndex,this.search_field_scale())):void 0},Chosen.prototype.single_set_selected_text=function(a){return null==a&&(a=this.default_text),a===this.default_text?this.selected_item.addClass("chosen-default"):(this.single_deselect_control_build(),this.selected_item.removeClass("chosen-default")),this.selected_item.find("span").text(a)},Chosen.prototype.result_deselect=function(a){var b;return b=this.results_data[a],this.form_field.options[b.options_index].disabled?!1:(b.selected=!1,this.form_field.options[b.options_index].selected=!1,this.selected_option_count=null,this.result_clear_highlight(),this.results_showing&&this.winnow_results(),this.form_field_jq.trigger("change",{deselected:this.form_field.options[b.options_index].value}),this.search_field_scale(),!0)},Chosen.prototype.single_deselect_control_build=function(){return this.allow_single_deselect?(this.selected_item.find("abbr").length||this.selected_item.find("span").first().after('<abbr class="search-choice-close"></abbr>'),this.selected_item.addClass("chosen-single-with-deselect")):void 0},Chosen.prototype.get_search_text=function(){return this.search_field.val()===this.default_text?"":a("<div/>").text(a.trim(this.search_field.val())).html()},Chosen.prototype.winnow_results_set_highlight=function(){var a,b;return b=this.is_multiple?[]:this.search_results.find(".result-selected.active-result"),a=b.length?b.first():this.search_results.find(".active-result").first(),null!=a?this.result_do_highlight(a):void 0},Chosen.prototype.no_results=function(b){var c;return c=a('<li class="no-results">'+this.results_none_found+' "<span></span>"</li>'),c.find("span").first().html(b),this.search_results.append(c)},Chosen.prototype.no_results_clear=function(){return this.search_results.find(".no-results").remove()},Chosen.prototype.keydown_arrow=function(){var a;return this.results_showing&&this.result_highlight?(a=this.result_highlight.nextAll("li.active-result").first())?this.result_do_highlight(a):void 0:this.results_show()},Chosen.prototype.keyup_arrow=function(){var a;return this.results_showing||this.is_multiple?this.result_highlight?(a=this.result_highlight.prevAll("li.active-result"),a.length?this.result_do_highlight(a.first()):(this.choices_count()>0&&this.results_hide(),this.result_clear_highlight())):void 0:this.results_show()},Chosen.prototype.keydown_backstroke=function(){var a;return this.pending_backstroke?(this.choice_destroy(this.pending_backstroke.find("a").first()),this.clear_backstroke()):(a=this.search_container.siblings("li.search-choice").last(),a.length&&!a.hasClass("search-choice-disabled")?(this.pending_backstroke=a,this.single_backstroke_delete?this.keydown_backstroke():this.pending_backstroke.addClass("search-choice-focus")):void 0)},Chosen.prototype.clear_backstroke=function(){return this.pending_backstroke&&this.pending_backstroke.removeClass("search-choice-focus"),this.pending_backstroke=null},Chosen.prototype.keydown_checker=function(a){var b,c;switch(b=null!=(c=a.which)?c:a.keyCode,this.search_field_scale(),8!==b&&this.pending_backstroke&&this.clear_backstroke(),b){case 8:this.backstroke_length=this.search_field.val().length;break;case 9:this.results_showing&&!this.is_multiple&&this.result_select(a),this.mouse_on_container=!1;break;case 13:a.preventDefault();break;case 38:a.preventDefault(),this.keyup_arrow();break;case 40:a.preventDefault(),this.keydown_arrow()}},Chosen.prototype.search_field_scale=function(){var b,c,d,e,f,g,h,i,j;if(this.is_multiple){for(d=0,h=0,f="position:absolute; left: -1000px; top: -1000px; display:none;",g=["font-size","font-style","font-weight","font-family","line-height","text-transform","letter-spacing"],i=0,j=g.length;j>i;i++)e=g[i],f+=e+":"+this.search_field.css(e)+";";return b=a("<div />",{style:f}),b.text(this.search_field.val()),a("body").append(b),h=b.width()+25,b.remove(),c=this.container.outerWidth(),h>c-10&&(h=c-10),this.search_field.css({width:h+"px"})}},Chosen}(AbstractChosen)}).call(this);


/* qTip2 v2.2.1 | Plugins: tips viewport svg modal | Styles: core css3 | qtip2.com | Licensed MIT | Sat Sep 13 2014 04:15:06 */

!function(a,b,c){!function(a){"use strict";"function"==typeof define&&define.amd?define(["jquery"],a):jQuery&&!jQuery.fn.qtip&&a(jQuery)}(function(d){"use strict";function e(a,b,c,e){this.id=c,this.target=a,this.tooltip=E,this.elements={target:a},this._id=R+"-"+c,this.timers={img:{}},this.options=b,this.plugins={},this.cache={event:{},target:d(),disabled:D,attr:e,onTooltip:D,lastClass:""},this.rendered=this.destroyed=this.disabled=this.waiting=this.hiddenDuringWait=this.positioning=this.triggering=D}function f(a){return a===E||"object"!==d.type(a)}function g(a){return!(d.isFunction(a)||a&&a.attr||a.length||"object"===d.type(a)&&(a.jquery||a.then))}function h(a){var b,c,e,h;return f(a)?D:(f(a.metadata)&&(a.metadata={type:a.metadata}),"content"in a&&(b=a.content,f(b)||b.jquery||b.done?b=a.content={text:c=g(b)?D:b}:c=b.text,"ajax"in b&&(e=b.ajax,h=e&&e.once!==D,delete b.ajax,b.text=function(a,b){var f=c||d(this).attr(b.options.content.attr)||"Loading...",g=d.ajax(d.extend({},e,{context:b})).then(e.success,E,e.error).then(function(a){return a&&h&&b.set("content.text",a),a},function(a,c,d){b.destroyed||0===a.status||b.set("content.text",c+": "+d)});return h?f:(b.set("content.text",f),g)}),"title"in b&&(d.isPlainObject(b.title)&&(b.button=b.title.button,b.title=b.title.text),g(b.title||D)&&(b.title=D))),"position"in a&&f(a.position)&&(a.position={my:a.position,at:a.position}),"show"in a&&f(a.show)&&(a.show=a.show.jquery?{target:a.show}:a.show===C?{ready:C}:{event:a.show}),"hide"in a&&f(a.hide)&&(a.hide=a.hide.jquery?{target:a.hide}:{event:a.hide}),"style"in a&&f(a.style)&&(a.style={classes:a.style}),d.each(Q,function(){this.sanitize&&this.sanitize(a)}),a)}function i(a,b){for(var c,d=0,e=a,f=b.split(".");e=e[f[d++]];)d<f.length&&(c=e);return[c||a,f.pop()]}function j(a,b){var c,d,e;for(c in this.checks)for(d in this.checks[c])(e=new RegExp(d,"i").exec(a))&&(b.push(e),("builtin"===c||this.plugins[c])&&this.checks[c][d].apply(this.plugins[c]||this,b))}function k(a){return U.concat("").join(a?"-"+a+" ":" ")}function l(a,b){return b>0?setTimeout(d.proxy(a,this),b):void a.call(this)}function m(a){this.tooltip.hasClass(_)||(clearTimeout(this.timers.show),clearTimeout(this.timers.hide),this.timers.show=l.call(this,function(){this.toggle(C,a)},this.options.show.delay))}function n(a){if(!this.tooltip.hasClass(_)&&!this.destroyed){var b=d(a.relatedTarget),c=b.closest(V)[0]===this.tooltip[0],e=b[0]===this.options.show.target[0];if(clearTimeout(this.timers.show),clearTimeout(this.timers.hide),this!==b[0]&&"mouse"===this.options.position.target&&c||this.options.hide.fixed&&/mouse(out|leave|move)/.test(a.type)&&(c||e))try{a.preventDefault(),a.stopImmediatePropagation()}catch(f){}else this.timers.hide=l.call(this,function(){this.toggle(D,a)},this.options.hide.delay,this)}}function o(a){!this.tooltip.hasClass(_)&&this.options.hide.inactive&&(clearTimeout(this.timers.inactive),this.timers.inactive=l.call(this,function(){this.hide(a)},this.options.hide.inactive))}function p(a){this.rendered&&this.tooltip[0].offsetWidth>0&&this.reposition(a)}function q(a,c,e){d(b.body).delegate(a,(c.split?c:c.join("."+R+" "))+"."+R,function(){var a=x.api[d.attr(this,T)];a&&!a.disabled&&e.apply(a,arguments)})}function r(a,c,f){var g,i,j,k,l,m=d(b.body),n=a[0]===b?m:a,o=a.metadata?a.metadata(f.metadata):E,p="html5"===f.metadata.type&&o?o[f.metadata.name]:E,q=a.data(f.metadata.name||"qtipopts");try{q="string"==typeof q?d.parseJSON(q):q}catch(r){}if(k=d.extend(C,{},x.defaults,f,"object"==typeof q?h(q):E,h(p||o)),i=k.position,k.id=c,"boolean"==typeof k.content.text){if(j=a.attr(k.content.attr),k.content.attr===D||!j)return D;k.content.text=j}if(i.container.length||(i.container=m),i.target===D&&(i.target=n),k.show.target===D&&(k.show.target=n),k.show.solo===C&&(k.show.solo=i.container.closest("body")),k.hide.target===D&&(k.hide.target=n),k.position.viewport===C&&(k.position.viewport=i.container),i.container=i.container.eq(0),i.at=new z(i.at,C),i.my=new z(i.my),a.data(R))if(k.overwrite)a.qtip("destroy",!0);else if(k.overwrite===D)return D;return a.attr(S,c),k.suppress&&(l=a.attr("title"))&&a.removeAttr("title").attr(bb,l).attr("title",""),g=new e(a,k,c,!!j),a.data(R,g),g}function s(a){return a.charAt(0).toUpperCase()+a.slice(1)}function t(a,b){var d,e,f=b.charAt(0).toUpperCase()+b.slice(1),g=(b+" "+qb.join(f+" ")+f).split(" "),h=0;if(pb[b])return a.css(pb[b]);for(;d=g[h++];)if((e=a.css(d))!==c)return pb[b]=d,e}function u(a,b){return Math.ceil(parseFloat(t(a,b)))}function v(a,b){this._ns="tip",this.options=b,this.offset=b.offset,this.size=[b.width,b.height],this.init(this.qtip=a)}function w(a,b){this.options=b,this._ns="-modal",this.init(this.qtip=a)}var x,y,z,A,B,C=!0,D=!1,E=null,F="x",G="y",H="width",I="height",J="top",K="left",L="bottom",M="right",N="center",O="flipinvert",P="shift",Q={},R="qtip",S="data-hasqtip",T="data-qtip-id",U=["ui-widget","ui-tooltip"],V="."+R,W="click dblclick mousedown mouseup mousemove mouseleave mouseenter".split(" "),X=R+"-fixed",Y=R+"-default",Z=R+"-focus",$=R+"-hover",_=R+"-disabled",ab="_replacedByqTip",bb="oldtitle",cb={ie:function(){for(var a=4,c=b.createElement("div");(c.innerHTML="<!--[if gt IE "+a+"]><i></i><![endif]-->")&&c.getElementsByTagName("i")[0];a+=1);return a>4?a:0/0}(),iOS:parseFloat((""+(/CPU.*OS ([0-9_]{1,5})|(CPU like).*AppleWebKit.*Mobile/i.exec(navigator.userAgent)||[0,""])[1]).replace("undefined","3_2").replace("_",".").replace("_",""))||D};y=e.prototype,y._when=function(a){return d.when.apply(d,a)},y.render=function(a){if(this.rendered||this.destroyed)return this;var b,c=this,e=this.options,f=this.cache,g=this.elements,h=e.content.text,i=e.content.title,j=e.content.button,k=e.position,l=("."+this._id+" ",[]);return d.attr(this.target[0],"aria-describedby",this._id),f.posClass=this._createPosClass((this.position={my:k.my,at:k.at}).my),this.tooltip=g.tooltip=b=d("<div/>",{id:this._id,"class":[R,Y,e.style.classes,f.posClass].join(" "),width:e.style.width||"",height:e.style.height||"",tracking:"mouse"===k.target&&k.adjust.mouse,role:"alert","aria-live":"polite","aria-atomic":D,"aria-describedby":this._id+"-content","aria-hidden":C}).toggleClass(_,this.disabled).attr(T,this.id).data(R,this).appendTo(k.container).append(g.content=d("<div />",{"class":R+"-content",id:this._id+"-content","aria-atomic":C})),this.rendered=-1,this.positioning=C,i&&(this._createTitle(),d.isFunction(i)||l.push(this._updateTitle(i,D))),j&&this._createButton(),d.isFunction(h)||l.push(this._updateContent(h,D)),this.rendered=C,this._setWidget(),d.each(Q,function(a){var b;"render"===this.initialize&&(b=this(c))&&(c.plugins[a]=b)}),this._unassignEvents(),this._assignEvents(),this._when(l).then(function(){c._trigger("render"),c.positioning=D,c.hiddenDuringWait||!e.show.ready&&!a||c.toggle(C,f.event,D),c.hiddenDuringWait=D}),x.api[this.id]=this,this},y.destroy=function(a){function b(){if(!this.destroyed){this.destroyed=C;var a,b=this.target,c=b.attr(bb);this.rendered&&this.tooltip.stop(1,0).find("*").remove().end().remove(),d.each(this.plugins,function(){this.destroy&&this.destroy()});for(a in this.timers)clearTimeout(this.timers[a]);b.removeData(R).removeAttr(T).removeAttr(S).removeAttr("aria-describedby"),this.options.suppress&&c&&b.attr("title",c).removeAttr(bb),this._unassignEvents(),this.options=this.elements=this.cache=this.timers=this.plugins=this.mouse=E,delete x.api[this.id]}}return this.destroyed?this.target:(a===C&&"hide"!==this.triggering||!this.rendered?b.call(this):(this.tooltip.one("tooltiphidden",d.proxy(b,this)),!this.triggering&&this.hide()),this.target)},A=y.checks={builtin:{"^id$":function(a,b,c,e){var f=c===C?x.nextid:c,g=R+"-"+f;f!==D&&f.length>0&&!d("#"+g).length?(this._id=g,this.rendered&&(this.tooltip[0].id=this._id,this.elements.content[0].id=this._id+"-content",this.elements.title[0].id=this._id+"-title")):a[b]=e},"^prerender":function(a,b,c){c&&!this.rendered&&this.render(this.options.show.ready)},"^content.text$":function(a,b,c){this._updateContent(c)},"^content.attr$":function(a,b,c,d){this.options.content.text===this.target.attr(d)&&this._updateContent(this.target.attr(c))},"^content.title$":function(a,b,c){return c?(c&&!this.elements.title&&this._createTitle(),void this._updateTitle(c)):this._removeTitle()},"^content.button$":function(a,b,c){this._updateButton(c)},"^content.title.(text|button)$":function(a,b,c){this.set("content."+b,c)},"^position.(my|at)$":function(a,b,c){"string"==typeof c&&(this.position[b]=a[b]=new z(c,"at"===b))},"^position.container$":function(a,b,c){this.rendered&&this.tooltip.appendTo(c)},"^show.ready$":function(a,b,c){c&&(!this.rendered&&this.render(C)||this.toggle(C))},"^style.classes$":function(a,b,c,d){this.rendered&&this.tooltip.removeClass(d).addClass(c)},"^style.(width|height)":function(a,b,c){this.rendered&&this.tooltip.css(b,c)},"^style.widget|content.title":function(){this.rendered&&this._setWidget()},"^style.def":function(a,b,c){this.rendered&&this.tooltip.toggleClass(Y,!!c)},"^events.(render|show|move|hide|focus|blur)$":function(a,b,c){this.rendered&&this.tooltip[(d.isFunction(c)?"":"un")+"bind"]("tooltip"+b,c)},"^(show|hide|position).(event|target|fixed|inactive|leave|distance|viewport|adjust)":function(){if(this.rendered){var a=this.options.position;this.tooltip.attr("tracking","mouse"===a.target&&a.adjust.mouse),this._unassignEvents(),this._assignEvents()}}}},y.get=function(a){if(this.destroyed)return this;var b=i(this.options,a.toLowerCase()),c=b[0][b[1]];return c.precedance?c.string():c};var db=/^position\.(my|at|adjust|target|container|viewport)|style|content|show\.ready/i,eb=/^prerender|show\.ready/i;y.set=function(a,b){if(this.destroyed)return this;{var c,e=this.rendered,f=D,g=this.options;this.checks}return"string"==typeof a?(c=a,a={},a[c]=b):a=d.extend({},a),d.each(a,function(b,c){if(e&&eb.test(b))return void delete a[b];var h,j=i(g,b.toLowerCase());h=j[0][j[1]],j[0][j[1]]=c&&c.nodeType?d(c):c,f=db.test(b)||f,a[b]=[j[0],j[1],c,h]}),h(g),this.positioning=C,d.each(a,d.proxy(j,this)),this.positioning=D,this.rendered&&this.tooltip[0].offsetWidth>0&&f&&this.reposition("mouse"===g.position.target?E:this.cache.event),this},y._update=function(a,b){var c=this,e=this.cache;return this.rendered&&a?(d.isFunction(a)&&(a=a.call(this.elements.target,e.event,this)||""),d.isFunction(a.then)?(e.waiting=C,a.then(function(a){return e.waiting=D,c._update(a,b)},E,function(a){return c._update(a,b)})):a===D||!a&&""!==a?D:(a.jquery&&a.length>0?b.empty().append(a.css({display:"block",visibility:"visible"})):b.html(a),this._waitForContent(b).then(function(a){c.rendered&&c.tooltip[0].offsetWidth>0&&c.reposition(e.event,!a.length)}))):D},y._waitForContent=function(a){var b=this.cache;return b.waiting=C,(d.fn.imagesLoaded?a.imagesLoaded():d.Deferred().resolve([])).done(function(){b.waiting=D}).promise()},y._updateContent=function(a,b){this._update(a,this.elements.content,b)},y._updateTitle=function(a,b){this._update(a,this.elements.title,b)===D&&this._removeTitle(D)},y._createTitle=function(){var a=this.elements,b=this._id+"-title";a.titlebar&&this._removeTitle(),a.titlebar=d("<div />",{"class":R+"-titlebar "+(this.options.style.widget?k("header"):"")}).append(a.title=d("<div />",{id:b,"class":R+"-title","aria-atomic":C})).insertBefore(a.content).delegate(".qtip-close","mousedown keydown mouseup keyup mouseout",function(a){d(this).toggleClass("ui-state-active ui-state-focus","down"===a.type.substr(-4))}).delegate(".qtip-close","mouseover mouseout",function(a){d(this).toggleClass("ui-state-hover","mouseover"===a.type)}),this.options.content.button&&this._createButton()},y._removeTitle=function(a){var b=this.elements;b.title&&(b.titlebar.remove(),b.titlebar=b.title=b.button=E,a!==D&&this.reposition())},y._createPosClass=function(a){return R+"-pos-"+(a||this.options.position.my).abbrev()},y.reposition=function(c,e){if(!this.rendered||this.positioning||this.destroyed)return this;this.positioning=C;var f,g,h,i,j=this.cache,k=this.tooltip,l=this.options.position,m=l.target,n=l.my,o=l.at,p=l.viewport,q=l.container,r=l.adjust,s=r.method.split(" "),t=k.outerWidth(D),u=k.outerHeight(D),v=0,w=0,x=k.css("position"),y={left:0,top:0},z=k[0].offsetWidth>0,A=c&&"scroll"===c.type,B=d(a),E=q[0].ownerDocument,F=this.mouse;if(d.isArray(m)&&2===m.length)o={x:K,y:J},y={left:m[0],top:m[1]};else if("mouse"===m)o={x:K,y:J},(!r.mouse||this.options.hide.distance)&&j.origin&&j.origin.pageX?c=j.origin:!c||c&&("resize"===c.type||"scroll"===c.type)?c=j.event:F&&F.pageX&&(c=F),"static"!==x&&(y=q.offset()),E.body.offsetWidth!==(a.innerWidth||E.documentElement.clientWidth)&&(g=d(b.body).offset()),y={left:c.pageX-y.left+(g&&g.left||0),top:c.pageY-y.top+(g&&g.top||0)},r.mouse&&A&&F&&(y.left-=(F.scrollX||0)-B.scrollLeft(),y.top-=(F.scrollY||0)-B.scrollTop());else{if("event"===m?c&&c.target&&"scroll"!==c.type&&"resize"!==c.type?j.target=d(c.target):c.target||(j.target=this.elements.target):"event"!==m&&(j.target=d(m.jquery?m:this.elements.target)),m=j.target,m=d(m).eq(0),0===m.length)return this;m[0]===b||m[0]===a?(v=cb.iOS?a.innerWidth:m.width(),w=cb.iOS?a.innerHeight:m.height(),m[0]===a&&(y={top:(p||m).scrollTop(),left:(p||m).scrollLeft()})):Q.imagemap&&m.is("area")?f=Q.imagemap(this,m,o,Q.viewport?s:D):Q.svg&&m&&m[0].ownerSVGElement?f=Q.svg(this,m,o,Q.viewport?s:D):(v=m.outerWidth(D),w=m.outerHeight(D),y=m.offset()),f&&(v=f.width,w=f.height,g=f.offset,y=f.position),y=this.reposition.offset(m,y,q),(cb.iOS>3.1&&cb.iOS<4.1||cb.iOS>=4.3&&cb.iOS<4.33||!cb.iOS&&"fixed"===x)&&(y.left-=B.scrollLeft(),y.top-=B.scrollTop()),(!f||f&&f.adjustable!==D)&&(y.left+=o.x===M?v:o.x===N?v/2:0,y.top+=o.y===L?w:o.y===N?w/2:0)}return y.left+=r.x+(n.x===M?-t:n.x===N?-t/2:0),y.top+=r.y+(n.y===L?-u:n.y===N?-u/2:0),Q.viewport?(h=y.adjusted=Q.viewport(this,y,l,v,w,t,u),g&&h.left&&(y.left+=g.left),g&&h.top&&(y.top+=g.top),h.my&&(this.position.my=h.my)):y.adjusted={left:0,top:0},j.posClass!==(i=this._createPosClass(this.position.my))&&k.removeClass(j.posClass).addClass(j.posClass=i),this._trigger("move",[y,p.elem||p],c)?(delete y.adjusted,e===D||!z||isNaN(y.left)||isNaN(y.top)||"mouse"===m||!d.isFunction(l.effect)?k.css(y):d.isFunction(l.effect)&&(l.effect.call(k,this,d.extend({},y)),k.queue(function(a){d(this).css({opacity:"",height:""}),cb.ie&&this.style.removeAttribute("filter"),a()})),this.positioning=D,this):this},y.reposition.offset=function(a,c,e){function f(a,b){c.left+=b*a.scrollLeft(),c.top+=b*a.scrollTop()}if(!e[0])return c;var g,h,i,j,k=d(a[0].ownerDocument),l=!!cb.ie&&"CSS1Compat"!==b.compatMode,m=e[0];do"static"!==(h=d.css(m,"position"))&&("fixed"===h?(i=m.getBoundingClientRect(),f(k,-1)):(i=d(m).position(),i.left+=parseFloat(d.css(m,"borderLeftWidth"))||0,i.top+=parseFloat(d.css(m,"borderTopWidth"))||0),c.left-=i.left+(parseFloat(d.css(m,"marginLeft"))||0),c.top-=i.top+(parseFloat(d.css(m,"marginTop"))||0),g||"hidden"===(j=d.css(m,"overflow"))||"visible"===j||(g=d(m)));while(m=m.offsetParent);return g&&(g[0]!==k[0]||l)&&f(g,1),c};var fb=(z=y.reposition.Corner=function(a,b){a=(""+a).replace(/([A-Z])/," $1").replace(/middle/gi,N).toLowerCase(),this.x=(a.match(/left|right/i)||a.match(/center/)||["inherit"])[0].toLowerCase(),this.y=(a.match(/top|bottom|center/i)||["inherit"])[0].toLowerCase(),this.forceY=!!b;var c=a.charAt(0);this.precedance="t"===c||"b"===c?G:F}).prototype;fb.invert=function(a,b){this[a]=this[a]===K?M:this[a]===M?K:b||this[a]},fb.string=function(a){var b=this.x,c=this.y,d=b!==c?"center"===b||"center"!==c&&(this.precedance===G||this.forceY)?[c,b]:[b,c]:[b];return a!==!1?d.join(" "):d},fb.abbrev=function(){var a=this.string(!1);return a[0].charAt(0)+(a[1]&&a[1].charAt(0)||"")},fb.clone=function(){return new z(this.string(),this.forceY)},y.toggle=function(a,c){var e=this.cache,f=this.options,g=this.tooltip;if(c){if(/over|enter/.test(c.type)&&e.event&&/out|leave/.test(e.event.type)&&f.show.target.add(c.target).length===f.show.target.length&&g.has(c.relatedTarget).length)return this;e.event=d.event.fix(c)}if(this.waiting&&!a&&(this.hiddenDuringWait=C),!this.rendered)return a?this.render(1):this;if(this.destroyed||this.disabled)return this;var h,i,j,k=a?"show":"hide",l=this.options[k],m=(this.options[a?"hide":"show"],this.options.position),n=this.options.content,o=this.tooltip.css("width"),p=this.tooltip.is(":visible"),q=a||1===l.target.length,r=!c||l.target.length<2||e.target[0]===c.target;return(typeof a).search("boolean|number")&&(a=!p),h=!g.is(":animated")&&p===a&&r,i=h?E:!!this._trigger(k,[90]),this.destroyed?this:(i!==D&&a&&this.focus(c),!i||h?this:(d.attr(g[0],"aria-hidden",!a),a?(this.mouse&&(e.origin=d.event.fix(this.mouse)),d.isFunction(n.text)&&this._updateContent(n.text,D),d.isFunction(n.title)&&this._updateTitle(n.title,D),!B&&"mouse"===m.target&&m.adjust.mouse&&(d(b).bind("mousemove."+R,this._storeMouse),B=C),o||g.css("width",g.outerWidth(D)),this.reposition(c,arguments[2]),o||g.css("width",""),l.solo&&("string"==typeof l.solo?d(l.solo):d(V,l.solo)).not(g).not(l.target).qtip("hide",d.Event("tooltipsolo"))):(clearTimeout(this.timers.show),delete e.origin,B&&!d(V+'[tracking="true"]:visible',l.solo).not(g).length&&(d(b).unbind("mousemove."+R),B=D),this.blur(c)),j=d.proxy(function(){a?(cb.ie&&g[0].style.removeAttribute("filter"),g.css("overflow",""),"string"==typeof l.autofocus&&d(this.options.show.autofocus,g).focus(),this.options.show.target.trigger("qtip-"+this.id+"-inactive")):g.css({display:"",visibility:"",opacity:"",left:"",top:""}),this._trigger(a?"visible":"hidden")},this),l.effect===D||q===D?(g[k](),j()):d.isFunction(l.effect)?(g.stop(1,1),l.effect.call(g,this),g.queue("fx",function(a){j(),a()})):g.fadeTo(90,a?1:0,j),a&&l.target.trigger("qtip-"+this.id+"-inactive"),this))},y.show=function(a){return this.toggle(C,a)},y.hide=function(a){return this.toggle(D,a)},y.focus=function(a){if(!this.rendered||this.destroyed)return this;var b=d(V),c=this.tooltip,e=parseInt(c[0].style.zIndex,10),f=x.zindex+b.length;return c.hasClass(Z)||this._trigger("focus",[f],a)&&(e!==f&&(b.each(function(){this.style.zIndex>e&&(this.style.zIndex=this.style.zIndex-1)}),b.filter("."+Z).qtip("blur",a)),c.addClass(Z)[0].style.zIndex=f),this},y.blur=function(a){return!this.rendered||this.destroyed?this:(this.tooltip.removeClass(Z),this._trigger("blur",[this.tooltip.css("zIndex")],a),this)},y.disable=function(a){return this.destroyed?this:("toggle"===a?a=!(this.rendered?this.tooltip.hasClass(_):this.disabled):"boolean"!=typeof a&&(a=C),this.rendered&&this.tooltip.toggleClass(_,a).attr("aria-disabled",a),this.disabled=!!a,this)},y.enable=function(){return this.disable(D)},y._createButton=function(){var a=this,b=this.elements,c=b.tooltip,e=this.options.content.button,f="string"==typeof e,g=f?e:"Close tooltip";b.button&&b.button.remove(),b.button=e.jquery?e:d("<a />",{"class":"qtip-close "+(this.options.style.widget?"":R+"-icon"),title:g,"aria-label":g}).prepend(d("<span />",{"class":"ui-icon ui-icon-close",html:"&times;"})),b.button.appendTo(b.titlebar||c).attr("role","button").click(function(b){return c.hasClass(_)||a.hide(b),D})},y._updateButton=function(a){if(!this.rendered)return D;var b=this.elements.button;a?this._createButton():b.remove()},y._setWidget=function(){var a=this.options.style.widget,b=this.elements,c=b.tooltip,d=c.hasClass(_);c.removeClass(_),_=a?"ui-state-disabled":"qtip-disabled",c.toggleClass(_,d),c.toggleClass("ui-helper-reset "+k(),a).toggleClass(Y,this.options.style.def&&!a),b.content&&b.content.toggleClass(k("content"),a),b.titlebar&&b.titlebar.toggleClass(k("header"),a),b.button&&b.button.toggleClass(R+"-icon",!a)},y._storeMouse=function(a){return(this.mouse=d.event.fix(a)).type="mousemove",this},y._bind=function(a,b,c,e,f){if(a&&c&&b.length){var g="."+this._id+(e?"-"+e:"");return d(a).bind((b.split?b:b.join(g+" "))+g,d.proxy(c,f||this)),this}},y._unbind=function(a,b){return a&&d(a).unbind("."+this._id+(b?"-"+b:"")),this},y._trigger=function(a,b,c){var e=d.Event("tooltip"+a);return e.originalEvent=c&&d.extend({},c)||this.cache.event||E,this.triggering=a,this.tooltip.trigger(e,[this].concat(b||[])),this.triggering=D,!e.isDefaultPrevented()},y._bindEvents=function(a,b,c,e,f,g){var h=c.filter(e).add(e.filter(c)),i=[];h.length&&(d.each(b,function(b,c){var e=d.inArray(c,a);e>-1&&i.push(a.splice(e,1)[0])}),i.length&&(this._bind(h,i,function(a){var b=this.rendered?this.tooltip[0].offsetWidth>0:!1;(b?g:f).call(this,a)}),c=c.not(h),e=e.not(h))),this._bind(c,a,f),this._bind(e,b,g)},y._assignInitialEvents=function(a){function b(a){return this.disabled||this.destroyed?D:(this.cache.event=a&&d.event.fix(a),this.cache.target=a&&d(a.target),clearTimeout(this.timers.show),void(this.timers.show=l.call(this,function(){this.render("object"==typeof a||c.show.ready)},c.prerender?0:c.show.delay)))}var c=this.options,e=c.show.target,f=c.hide.target,g=c.show.event?d.trim(""+c.show.event).split(" "):[],h=c.hide.event?d.trim(""+c.hide.event).split(" "):[];this._bind(this.elements.target,["remove","removeqtip"],function(){this.destroy(!0)},"destroy"),/mouse(over|enter)/i.test(c.show.event)&&!/mouse(out|leave)/i.test(c.hide.event)&&h.push("mouseleave"),this._bind(e,"mousemove",function(a){this._storeMouse(a),this.cache.onTarget=C}),this._bindEvents(g,h,e,f,b,function(){return this.timers?void clearTimeout(this.timers.show):D}),(c.show.ready||c.prerender)&&b.call(this,a)},y._assignEvents=function(){var c=this,e=this.options,f=e.position,g=this.tooltip,h=e.show.target,i=e.hide.target,j=f.container,k=f.viewport,l=d(b),q=(d(b.body),d(a)),r=e.show.event?d.trim(""+e.show.event).split(" "):[],s=e.hide.event?d.trim(""+e.hide.event).split(" "):[];d.each(e.events,function(a,b){c._bind(g,"toggle"===a?["tooltipshow","tooltiphide"]:["tooltip"+a],b,null,g)}),/mouse(out|leave)/i.test(e.hide.event)&&"window"===e.hide.leave&&this._bind(l,["mouseout","blur"],function(a){/select|option/.test(a.target.nodeName)||a.relatedTarget||this.hide(a)}),e.hide.fixed?i=i.add(g.addClass(X)):/mouse(over|enter)/i.test(e.show.event)&&this._bind(i,"mouseleave",function(){clearTimeout(this.timers.show)}),(""+e.hide.event).indexOf("unfocus")>-1&&this._bind(j.closest("html"),["mousedown","touchstart"],function(a){var b=d(a.target),c=this.rendered&&!this.tooltip.hasClass(_)&&this.tooltip[0].offsetWidth>0,e=b.parents(V).filter(this.tooltip[0]).length>0;b[0]===this.target[0]||b[0]===this.tooltip[0]||e||this.target.has(b[0]).length||!c||this.hide(a)}),"number"==typeof e.hide.inactive&&(this._bind(h,"qtip-"+this.id+"-inactive",o,"inactive"),this._bind(i.add(g),x.inactiveEvents,o)),this._bindEvents(r,s,h,i,m,n),this._bind(h.add(g),"mousemove",function(a){if("number"==typeof e.hide.distance){var b=this.cache.origin||{},c=this.options.hide.distance,d=Math.abs;(d(a.pageX-b.pageX)>=c||d(a.pageY-b.pageY)>=c)&&this.hide(a)}this._storeMouse(a)}),"mouse"===f.target&&f.adjust.mouse&&(e.hide.event&&this._bind(h,["mouseenter","mouseleave"],function(a){return this.cache?void(this.cache.onTarget="mouseenter"===a.type):D}),this._bind(l,"mousemove",function(a){this.rendered&&this.cache.onTarget&&!this.tooltip.hasClass(_)&&this.tooltip[0].offsetWidth>0&&this.reposition(a)})),(f.adjust.resize||k.length)&&this._bind(d.event.special.resize?k:q,"resize",p),f.adjust.scroll&&this._bind(q.add(f.container),"scroll",p)},y._unassignEvents=function(){var c=this.options,e=c.show.target,f=c.hide.target,g=d.grep([this.elements.target[0],this.rendered&&this.tooltip[0],c.position.container[0],c.position.viewport[0],c.position.container.closest("html")[0],a,b],function(a){return"object"==typeof a});e&&e.toArray&&(g=g.concat(e.toArray())),f&&f.toArray&&(g=g.concat(f.toArray())),this._unbind(g)._unbind(g,"destroy")._unbind(g,"inactive")},d(function(){q(V,["mouseenter","mouseleave"],function(a){var b="mouseenter"===a.type,c=d(a.currentTarget),e=d(a.relatedTarget||a.target),f=this.options;b?(this.focus(a),c.hasClass(X)&&!c.hasClass(_)&&clearTimeout(this.timers.hide)):"mouse"===f.position.target&&f.position.adjust.mouse&&f.hide.event&&f.show.target&&!e.closest(f.show.target[0]).length&&this.hide(a),c.toggleClass($,b)}),q("["+T+"]",W,o)}),x=d.fn.qtip=function(a,b,e){var f=(""+a).toLowerCase(),g=E,i=d.makeArray(arguments).slice(1),j=i[i.length-1],k=this[0]?d.data(this[0],R):E;return!arguments.length&&k||"api"===f?k:"string"==typeof a?(this.each(function(){var a=d.data(this,R);if(!a)return C;if(j&&j.timeStamp&&(a.cache.event=j),!b||"option"!==f&&"options"!==f)a[f]&&a[f].apply(a,i);else{if(e===c&&!d.isPlainObject(b))return g=a.get(b),D;a.set(b,e)}}),g!==E?g:this):"object"!=typeof a&&arguments.length?void 0:(k=h(d.extend(C,{},a)),this.each(function(a){var b,c;return c=d.isArray(k.id)?k.id[a]:k.id,c=!c||c===D||c.length<1||x.api[c]?x.nextid++:c,b=r(d(this),c,k),b===D?C:(x.api[c]=b,d.each(Q,function(){"initialize"===this.initialize&&this(b)}),void b._assignInitialEvents(j))}))},d.qtip=e,x.api={},d.each({attr:function(a,b){if(this.length){var c=this[0],e="title",f=d.data(c,"qtip");if(a===e&&f&&"object"==typeof f&&f.options.suppress)return arguments.length<2?d.attr(c,bb):(f&&f.options.content.attr===e&&f.cache.attr&&f.set("content.text",b),this.attr(bb,b))}return d.fn["attr"+ab].apply(this,arguments)},clone:function(a){var b=(d([]),d.fn["clone"+ab].apply(this,arguments));return a||b.filter("["+bb+"]").attr("title",function(){return d.attr(this,bb)}).removeAttr(bb),b}},function(a,b){if(!b||d.fn[a+ab])return C;var c=d.fn[a+ab]=d.fn[a];d.fn[a]=function(){return b.apply(this,arguments)||c.apply(this,arguments)}}),d.ui||(d["cleanData"+ab]=d.cleanData,d.cleanData=function(a){for(var b,c=0;(b=d(a[c])).length;c++)if(b.attr(S))try{b.triggerHandler("removeqtip")}catch(e){}d["cleanData"+ab].apply(this,arguments)}),x.version="2.2.1",x.nextid=0,x.inactiveEvents=W,x.zindex=15e3,x.defaults={prerender:D,id:D,overwrite:C,suppress:C,content:{text:C,attr:"title",title:D,button:D},position:{my:"top left",at:"bottom right",target:D,container:D,viewport:D,adjust:{x:0,y:0,mouse:C,scroll:C,resize:C,method:"flipinvert flipinvert"},effect:function(a,b){d(this).animate(b,{duration:200,queue:D})}},show:{target:D,event:"mouseenter",effect:C,delay:90,solo:D,ready:D,autofocus:D},hide:{target:D,event:"mouseleave",effect:C,delay:0,fixed:D,inactive:D,leave:"window",distance:D},style:{classes:"",widget:D,width:D,height:D,def:C},events:{render:E,move:E,show:E,hide:E,toggle:E,visible:E,hidden:E,focus:E,blur:E}};var gb,hb="margin",ib="border",jb="color",kb="background-color",lb="transparent",mb=" !important",nb=!!b.createElement("canvas").getContext,ob=/rgba?\(0, 0, 0(, 0)?\)|transparent|#123456/i,pb={},qb=["Webkit","O","Moz","ms"];if(nb)var rb=a.devicePixelRatio||1,sb=function(){var a=b.createElement("canvas").getContext("2d");return a.backingStorePixelRatio||a.webkitBackingStorePixelRatio||a.mozBackingStorePixelRatio||a.msBackingStorePixelRatio||a.oBackingStorePixelRatio||1}(),tb=rb/sb;else var ub=function(a,b,c){return"<qtipvml:"+a+' xmlns="urn:schemas-microsoft.com:vml" class="qtip-vml" '+(b||"")+' style="behavior: url(#default#VML); '+(c||"")+'" />'};d.extend(v.prototype,{init:function(a){var b,c;c=this.element=a.elements.tip=d("<div />",{"class":R+"-tip"}).prependTo(a.tooltip),nb?(b=d("<canvas />").appendTo(this.element)[0].getContext("2d"),b.lineJoin="miter",b.miterLimit=1e5,b.save()):(b=ub("shape",'coordorigin="0,0"',"position:absolute;"),this.element.html(b+b),a._bind(d("*",c).add(c),["click","mousedown"],function(a){a.stopPropagation()},this._ns)),a._bind(a.tooltip,"tooltipmove",this.reposition,this._ns,this),this.create()},_swapDimensions:function(){this.size[0]=this.options.height,this.size[1]=this.options.width},_resetDimensions:function(){this.size[0]=this.options.width,this.size[1]=this.options.height},_useTitle:function(a){var b=this.qtip.elements.titlebar;return b&&(a.y===J||a.y===N&&this.element.position().top+this.size[1]/2+this.options.offset<b.outerHeight(C))},_parseCorner:function(a){var b=this.qtip.options.position.my;return a===D||b===D?a=D:a===C?a=new z(b.string()):a.string||(a=new z(a),a.fixed=C),a},_parseWidth:function(a,b,c){var d=this.qtip.elements,e=ib+s(b)+"Width";return(c?u(c,e):u(d.content,e)||u(this._useTitle(a)&&d.titlebar||d.content,e)||u(d.tooltip,e))||0},_parseRadius:function(a){var b=this.qtip.elements,c=ib+s(a.y)+s(a.x)+"Radius";return cb.ie<9?0:u(this._useTitle(a)&&b.titlebar||b.content,c)||u(b.tooltip,c)||0},_invalidColour:function(a,b,c){var d=a.css(b);return!d||c&&d===a.css(c)||ob.test(d)?D:d},_parseColours:function(a){var b=this.qtip.elements,c=this.element.css("cssText",""),e=ib+s(a[a.precedance])+s(jb),f=this._useTitle(a)&&b.titlebar||b.content,g=this._invalidColour,h=[];return h[0]=g(c,kb)||g(f,kb)||g(b.content,kb)||g(b.tooltip,kb)||c.css(kb),h[1]=g(c,e,jb)||g(f,e,jb)||g(b.content,e,jb)||g(b.tooltip,e,jb)||b.tooltip.css(e),d("*",c).add(c).css("cssText",kb+":"+lb+mb+";"+ib+":0"+mb+";"),h},_calculateSize:function(a){var b,c,d,e=a.precedance===G,f=this.options.width,g=this.options.height,h="c"===a.abbrev(),i=(e?f:g)*(h?.5:1),j=Math.pow,k=Math.round,l=Math.sqrt(j(i,2)+j(g,2)),m=[this.border/i*l,this.border/g*l];return m[2]=Math.sqrt(j(m[0],2)-j(this.border,2)),m[3]=Math.sqrt(j(m[1],2)-j(this.border,2)),b=l+m[2]+m[3]+(h?0:m[0]),c=b/l,d=[k(c*f),k(c*g)],e?d:d.reverse()},_calculateTip:function(a,b,c){c=c||1,b=b||this.size;var d=b[0]*c,e=b[1]*c,f=Math.ceil(d/2),g=Math.ceil(e/2),h={br:[0,0,d,e,d,0],bl:[0,0,d,0,0,e],tr:[0,e,d,0,d,e],tl:[0,0,0,e,d,e],tc:[0,e,f,0,d,e],bc:[0,0,d,0,f,e],rc:[0,0,d,g,0,e],lc:[d,0,d,e,0,g]};return h.lt=h.br,h.rt=h.bl,h.lb=h.tr,h.rb=h.tl,h[a.abbrev()]},_drawCoords:function(a,b){a.beginPath(),a.moveTo(b[0],b[1]),a.lineTo(b[2],b[3]),a.lineTo(b[4],b[5]),a.closePath()},create:function(){var a=this.corner=(nb||cb.ie)&&this._parseCorner(this.options.corner);return(this.enabled=!!this.corner&&"c"!==this.corner.abbrev())&&(this.qtip.cache.corner=a.clone(),this.update()),this.element.toggle(this.enabled),this.corner},update:function(b,c){if(!this.enabled)return this;var e,f,g,h,i,j,k,l,m=this.qtip.elements,n=this.element,o=n.children(),p=this.options,q=this.size,r=p.mimic,s=Math.round;b||(b=this.qtip.cache.corner||this.corner),r===D?r=b:(r=new z(r),r.precedance=b.precedance,"inherit"===r.x?r.x=b.x:"inherit"===r.y?r.y=b.y:r.x===r.y&&(r[b.precedance]=b[b.precedance])),f=r.precedance,b.precedance===F?this._swapDimensions():this._resetDimensions(),e=this.color=this._parseColours(b),e[1]!==lb?(l=this.border=this._parseWidth(b,b[b.precedance]),p.border&&1>l&&!ob.test(e[1])&&(e[0]=e[1]),this.border=l=p.border!==C?p.border:l):this.border=l=0,k=this.size=this._calculateSize(b),n.css({width:k[0],height:k[1],lineHeight:k[1]+"px"}),j=b.precedance===G?[s(r.x===K?l:r.x===M?k[0]-q[0]-l:(k[0]-q[0])/2),s(r.y===J?k[1]-q[1]:0)]:[s(r.x===K?k[0]-q[0]:0),s(r.y===J?l:r.y===L?k[1]-q[1]-l:(k[1]-q[1])/2)],nb?(g=o[0].getContext("2d"),g.restore(),g.save(),g.clearRect(0,0,6e3,6e3),h=this._calculateTip(r,q,tb),i=this._calculateTip(r,this.size,tb),o.attr(H,k[0]*tb).attr(I,k[1]*tb),o.css(H,k[0]).css(I,k[1]),this._drawCoords(g,i),g.fillStyle=e[1],g.fill(),g.translate(j[0]*tb,j[1]*tb),this._drawCoords(g,h),g.fillStyle=e[0],g.fill()):(h=this._calculateTip(r),h="m"+h[0]+","+h[1]+" l"+h[2]+","+h[3]+" "+h[4]+","+h[5]+" xe",j[2]=l&&/^(r|b)/i.test(b.string())?8===cb.ie?2:1:0,o.css({coordsize:k[0]+l+" "+(k[1]+l),antialias:""+(r.string().indexOf(N)>-1),left:j[0]-j[2]*Number(f===F),top:j[1]-j[2]*Number(f===G),width:k[0]+l,height:k[1]+l}).each(function(a){var b=d(this);b[b.prop?"prop":"attr"]({coordsize:k[0]+l+" "+(k[1]+l),path:h,fillcolor:e[0],filled:!!a,stroked:!a}).toggle(!(!l&&!a)),!a&&b.html(ub("stroke",'weight="'+2*l+'px" color="'+e[1]+'" miterlimit="1000" joinstyle="miter"'))})),a.opera&&setTimeout(function(){m.tip.css({display:"inline-block",visibility:"visible"})},1),c!==D&&this.calculate(b,k)},calculate:function(a,b){if(!this.enabled)return D;var c,e,f=this,g=this.qtip.elements,h=this.element,i=this.options.offset,j=(g.tooltip.hasClass("ui-widget"),{});return a=a||this.corner,c=a.precedance,b=b||this._calculateSize(a),e=[a.x,a.y],c===F&&e.reverse(),d.each(e,function(d,e){var h,k,l;
    e===N?(h=c===G?K:J,j[h]="50%",j[hb+"-"+h]=-Math.round(b[c===G?0:1]/2)+i):(h=f._parseWidth(a,e,g.tooltip),k=f._parseWidth(a,e,g.content),l=f._parseRadius(a),j[e]=Math.max(-f.border,d?k:i+(l>h?l:-h)))}),j[a[c]]-=b[c===F?0:1],h.css({margin:"",top:"",bottom:"",left:"",right:""}).css(j),j},reposition:function(a,b,d){function e(a,b,c,d,e){a===P&&j.precedance===b&&k[d]&&j[c]!==N?j.precedance=j.precedance===F?G:F:a!==P&&k[d]&&(j[b]=j[b]===N?k[d]>0?d:e:j[b]===d?e:d)}function f(a,b,e){j[a]===N?p[hb+"-"+b]=o[a]=g[hb+"-"+b]-k[b]:(h=g[e]!==c?[k[b],-g[b]]:[-k[b],g[b]],(o[a]=Math.max(h[0],h[1]))>h[0]&&(d[b]-=k[b],o[b]=D),p[g[e]!==c?e:b]=o[a])}if(this.enabled){var g,h,i=b.cache,j=this.corner.clone(),k=d.adjusted,l=b.options.position.adjust.method.split(" "),m=l[0],n=l[1]||l[0],o={left:D,top:D,x:0,y:0},p={};this.corner.fixed!==C&&(e(m,F,G,K,M),e(n,G,F,J,L),(j.string()!==i.corner.string()||i.cornerTop!==k.top||i.cornerLeft!==k.left)&&this.update(j,D)),g=this.calculate(j),g.right!==c&&(g.left=-g.right),g.bottom!==c&&(g.top=-g.bottom),g.user=this.offset,(o.left=m===P&&!!k.left)&&f(F,K,M),(o.top=n===P&&!!k.top)&&f(G,J,L),this.element.css(p).toggle(!(o.x&&o.y||j.x===N&&o.y||j.y===N&&o.x)),d.left-=g.left.charAt?g.user:m!==P||o.top||!o.left&&!o.top?g.left+this.border:0,d.top-=g.top.charAt?g.user:n!==P||o.left||!o.left&&!o.top?g.top+this.border:0,i.cornerLeft=k.left,i.cornerTop=k.top,i.corner=j.clone()}},destroy:function(){this.qtip._unbind(this.qtip.tooltip,this._ns),this.qtip.elements.tip&&this.qtip.elements.tip.find("*").remove().end().remove()}}),gb=Q.tip=function(a){return new v(a,a.options.style.tip)},gb.initialize="render",gb.sanitize=function(a){if(a.style&&"tip"in a.style){var b=a.style.tip;"object"!=typeof b&&(b=a.style.tip={corner:b}),/string|boolean/i.test(typeof b.corner)||(b.corner=C)}},A.tip={"^position.my|style.tip.(corner|mimic|border)$":function(){this.create(),this.qtip.reposition()},"^style.tip.(height|width)$":function(a){this.size=[a.width,a.height],this.update(),this.qtip.reposition()},"^content.title|style.(classes|widget)$":function(){this.update()}},d.extend(C,x.defaults,{style:{tip:{corner:C,mimic:D,width:6,height:6,border:C,offset:0}}}),Q.viewport=function(c,d,e,f,g,h,i){function j(a,b,c,e,f,g,h,i,j){var k=d[f],s=u[a],t=v[a],w=c===P,x=s===f?j:s===g?-j:-j/2,y=t===f?i:t===g?-i:-i/2,z=q[f]+r[f]-(n?0:m[f]),A=z-k,B=k+j-(h===H?o:p)-z,C=x-(u.precedance===a||s===u[b]?y:0)-(t===N?i/2:0);return w?(C=(s===f?1:-1)*x,d[f]+=A>0?A:B>0?-B:0,d[f]=Math.max(-m[f]+r[f],k-C,Math.min(Math.max(-m[f]+r[f]+(h===H?o:p),k+C),d[f],"center"===s?k-x:1e9))):(e*=c===O?2:0,A>0&&(s!==f||B>0)?(d[f]-=C+e,l.invert(a,f)):B>0&&(s!==g||A>0)&&(d[f]-=(s===N?-C:C)+e,l.invert(a,g)),d[f]<q&&-d[f]>B&&(d[f]=k,l=u.clone())),d[f]-k}var k,l,m,n,o,p,q,r,s=e.target,t=c.elements.tooltip,u=e.my,v=e.at,w=e.adjust,x=w.method.split(" "),y=x[0],z=x[1]||x[0],A=e.viewport,B=e.container,C=(c.cache,{left:0,top:0});return A.jquery&&s[0]!==a&&s[0]!==b.body&&"none"!==w.method?(m=B.offset()||C,n="static"===B.css("position"),k="fixed"===t.css("position"),o=A[0]===a?A.width():A.outerWidth(D),p=A[0]===a?A.height():A.outerHeight(D),q={left:k?0:A.scrollLeft(),top:k?0:A.scrollTop()},r=A.offset()||C,("shift"!==y||"shift"!==z)&&(l=u.clone()),C={left:"none"!==y?j(F,G,y,w.x,K,M,H,f,h):0,top:"none"!==z?j(G,F,z,w.y,J,L,I,g,i):0,my:l}):C},Q.polys={polygon:function(a,b){var c,d,e,f={width:0,height:0,position:{top:1e10,right:0,bottom:0,left:1e10},adjustable:D},g=0,h=[],i=1,j=1,k=0,l=0;for(g=a.length;g--;)c=[parseInt(a[--g],10),parseInt(a[g+1],10)],c[0]>f.position.right&&(f.position.right=c[0]),c[0]<f.position.left&&(f.position.left=c[0]),c[1]>f.position.bottom&&(f.position.bottom=c[1]),c[1]<f.position.top&&(f.position.top=c[1]),h.push(c);if(d=f.width=Math.abs(f.position.right-f.position.left),e=f.height=Math.abs(f.position.bottom-f.position.top),"c"===b.abbrev())f.position={left:f.position.left+f.width/2,top:f.position.top+f.height/2};else{for(;d>0&&e>0&&i>0&&j>0;)for(d=Math.floor(d/2),e=Math.floor(e/2),b.x===K?i=d:b.x===M?i=f.width-d:i+=Math.floor(d/2),b.y===J?j=e:b.y===L?j=f.height-e:j+=Math.floor(e/2),g=h.length;g--&&!(h.length<2);)k=h[g][0]-f.position.left,l=h[g][1]-f.position.top,(b.x===K&&k>=i||b.x===M&&i>=k||b.x===N&&(i>k||k>f.width-i)||b.y===J&&l>=j||b.y===L&&j>=l||b.y===N&&(j>l||l>f.height-j))&&h.splice(g,1);f.position={left:h[0][0],top:h[0][1]}}return f},rect:function(a,b,c,d){return{width:Math.abs(c-a),height:Math.abs(d-b),position:{left:Math.min(a,c),top:Math.min(b,d)}}},_angles:{tc:1.5,tr:7/4,tl:5/4,bc:.5,br:.25,bl:.75,rc:2,lc:1,c:0},ellipse:function(a,b,c,d,e){var f=Q.polys._angles[e.abbrev()],g=0===f?0:c*Math.cos(f*Math.PI),h=d*Math.sin(f*Math.PI);return{width:2*c-Math.abs(g),height:2*d-Math.abs(h),position:{left:a+g,top:b+h},adjustable:D}},circle:function(a,b,c,d){return Q.polys.ellipse(a,b,c,c,d)}},Q.svg=function(a,c,e){for(var f,g,h,i,j,k,l,m,n,o=(d(b),c[0]),p=d(o.ownerSVGElement),q=o.ownerDocument,r=(parseInt(c.css("stroke-width"),10)||0)/2;!o.getBBox;)o=o.parentNode;if(!o.getBBox||!o.parentNode)return D;switch(o.nodeName){case"ellipse":case"circle":m=Q.polys.ellipse(o.cx.baseVal.value,o.cy.baseVal.value,(o.rx||o.r).baseVal.value+r,(o.ry||o.r).baseVal.value+r,e);break;case"line":case"polygon":case"polyline":for(l=o.points||[{x:o.x1.baseVal.value,y:o.y1.baseVal.value},{x:o.x2.baseVal.value,y:o.y2.baseVal.value}],m=[],k=-1,i=l.numberOfItems||l.length;++k<i;)j=l.getItem?l.getItem(k):l[k],m.push.apply(m,[j.x,j.y]);m=Q.polys.polygon(m,e);break;default:m=o.getBBox(),m={width:m.width,height:m.height,position:{left:m.x,top:m.y}}}return n=m.position,p=p[0],p.createSVGPoint&&(g=o.getScreenCTM(),l=p.createSVGPoint(),l.x=n.left,l.y=n.top,h=l.matrixTransform(g),n.left=h.x,n.top=h.y),q!==b&&"mouse"!==a.position.target&&(f=d((q.defaultView||q.parentWindow).frameElement).offset(),f&&(n.left+=f.left,n.top+=f.top)),q=d(q),n.left+=q.scrollLeft(),n.top+=q.scrollTop(),m};var vb,wb,xb="qtip-modal",yb="."+xb;wb=function(){function a(a){if(d.expr[":"].focusable)return d.expr[":"].focusable;var b,c,e,f=!isNaN(d.attr(a,"tabindex")),g=a.nodeName&&a.nodeName.toLowerCase();return"area"===g?(b=a.parentNode,c=b.name,a.href&&c&&"map"===b.nodeName.toLowerCase()?(e=d("img[usemap=#"+c+"]")[0],!!e&&e.is(":visible")):!1):/input|select|textarea|button|object/.test(g)?!a.disabled:"a"===g?a.href||f:f}function c(a){k.length<1&&a.length?a.not("body").blur():k.first().focus()}function e(a){if(i.is(":visible")){var b,e=d(a.target),h=f.tooltip,j=e.closest(V);b=j.length<1?D:parseInt(j[0].style.zIndex,10)>parseInt(h[0].style.zIndex,10),b||e.closest(V)[0]===h[0]||c(e),g=a.target===k[k.length-1]}}var f,g,h,i,j=this,k={};d.extend(j,{init:function(){return i=j.elem=d("<div />",{id:"qtip-overlay",html:"<div></div>",mousedown:function(){return D}}).hide(),d(b.body).bind("focusin"+yb,e),d(b).bind("keydown"+yb,function(a){f&&f.options.show.modal.escape&&27===a.keyCode&&f.hide(a)}),i.bind("click"+yb,function(a){f&&f.options.show.modal.blur&&f.hide(a)}),j},update:function(b){f=b,k=b.options.show.modal.stealfocus!==D?b.tooltip.find("*").filter(function(){return a(this)}):[]},toggle:function(a,e,g){var k=(d(b.body),a.tooltip),l=a.options.show.modal,m=l.effect,n=e?"show":"hide",o=i.is(":visible"),p=d(yb).filter(":visible:not(:animated)").not(k);return j.update(a),e&&l.stealfocus!==D&&c(d(":focus")),i.toggleClass("blurs",l.blur),e&&i.appendTo(b.body),i.is(":animated")&&o===e&&h!==D||!e&&p.length?j:(i.stop(C,D),d.isFunction(m)?m.call(i,e):m===D?i[n]():i.fadeTo(parseInt(g,10)||90,e?1:0,function(){e||i.hide()}),e||i.queue(function(a){i.css({left:"",top:""}),d(yb).length||i.detach(),a()}),h=e,f.destroyed&&(f=E),j)}}),j.init()},wb=new wb,d.extend(w.prototype,{init:function(a){var b=a.tooltip;return this.options.on?(a.elements.overlay=wb.elem,b.addClass(xb).css("z-index",x.modal_zindex+d(yb).length),a._bind(b,["tooltipshow","tooltiphide"],function(a,c,e){var f=a.originalEvent;if(a.target===b[0])if(f&&"tooltiphide"===a.type&&/mouse(leave|enter)/.test(f.type)&&d(f.relatedTarget).closest(wb.elem[0]).length)try{a.preventDefault()}catch(g){}else(!f||f&&"tooltipsolo"!==f.type)&&this.toggle(a,"tooltipshow"===a.type,e)},this._ns,this),a._bind(b,"tooltipfocus",function(a,c){if(!a.isDefaultPrevented()&&a.target===b[0]){var e=d(yb),f=x.modal_zindex+e.length,g=parseInt(b[0].style.zIndex,10);wb.elem[0].style.zIndex=f-1,e.each(function(){this.style.zIndex>g&&(this.style.zIndex-=1)}),e.filter("."+Z).qtip("blur",a.originalEvent),b.addClass(Z)[0].style.zIndex=f,wb.update(c);try{a.preventDefault()}catch(h){}}},this._ns,this),void a._bind(b,"tooltiphide",function(a){a.target===b[0]&&d(yb).filter(":visible").not(b).last().qtip("focus",a)},this._ns,this)):this},toggle:function(a,b,c){return a&&a.isDefaultPrevented()?this:void wb.toggle(this.qtip,!!b,c)},destroy:function(){this.qtip.tooltip.removeClass(xb),this.qtip._unbind(this.qtip.tooltip,this._ns),wb.toggle(this.qtip,D),delete this.qtip.elements.overlay}}),vb=Q.modal=function(a){return new w(a,a.options.show.modal)},vb.sanitize=function(a){a.show&&("object"!=typeof a.show.modal?a.show.modal={on:!!a.show.modal}:"undefined"==typeof a.show.modal.on&&(a.show.modal.on=C))},x.modal_zindex=x.zindex-200,vb.initialize="render",A.modal={"^show.modal.(on|blur)$":function(){this.destroy(),this.init(),this.qtip.elems.overlay.toggle(this.qtip.tooltip[0].offsetWidth>0)}},d.extend(C,x.defaults,{show:{modal:{on:D,effect:C,blur:C,stealfocus:C,escape:C}}})})}(window,document);



//spin.js#v2.0.1 + jquery.spin
!function(a,b){"object"==typeof exports?module.exports=b():"function"==typeof define&&define.amd?define(b):a.Spinner=b()}(this,function(){"use strict";function a(a,b){var c,d=document.createElement(a||"div");for(c in b)d[c]=b[c];return d}function b(a){for(var b=1,c=arguments.length;c>b;b++)a.appendChild(arguments[b]);return a}function c(a,b,c,d){var e=["opacity",b,~~(100*a),c,d].join("-"),f=.01+c/d*100,g=Math.max(1-(1-a)/b*(100-f),a),h=j.substring(0,j.indexOf("Animation")).toLowerCase(),i=h&&"-"+h+"-"||"";return l[e]||(m.insertRule("@"+i+"keyframes "+e+"{0%{opacity:"+g+"}"+f+"%{opacity:"+a+"}"+(f+.01)+"%{opacity:1}"+(f+b)%100+"%{opacity:"+a+"}100%{opacity:"+g+"}}",m.cssRules.length),l[e]=1),e}function d(a,b){var c,d,e=a.style;for(b=b.charAt(0).toUpperCase()+b.slice(1),d=0;d<k.length;d++)if(c=k[d]+b,void 0!==e[c])return c;return void 0!==e[b]?b:void 0}function e(a,b){for(var c in b)a.style[d(a,c)||c]=b[c];return a}function f(a){for(var b=1;b<arguments.length;b++){var c=arguments[b];for(var d in c)void 0===a[d]&&(a[d]=c[d])}return a}function g(a,b){return"string"==typeof a?a:a[b%a.length]}function h(a){this.opts=f(a||{},h.defaults,n)}function i(){function c(b,c){return a("<"+b+' xmlns="urn:schemas-microsoft.com:vml" class="spin-vml">',c)}m.addRule(".spin-vml","behavior:url(#default#VML)"),h.prototype.lines=function(a,d){function f(){return e(c("group",{coordsize:k+" "+k,coordorigin:-j+" "+-j}),{width:k,height:k})}function h(a,h,i){b(m,b(e(f(),{rotation:360/d.lines*a+"deg",left:~~h}),b(e(c("roundrect",{arcsize:d.corners}),{width:j,height:d.width,left:d.radius,top:-d.width>>1,filter:i}),c("fill",{color:g(d.color,a),opacity:d.opacity}),c("stroke",{opacity:0}))))}var i,j=d.length+d.width,k=2*j,l=2*-(d.width+d.length)+"px",m=e(f(),{position:"absolute",top:l,left:l});if(d.shadow)for(i=1;i<=d.lines;i++)h(i,-2,"progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)");for(i=1;i<=d.lines;i++)h(i);return b(a,m)},h.prototype.opacity=function(a,b,c,d){var e=a.firstChild;d=d.shadow&&d.lines||0,e&&b+d<e.childNodes.length&&(e=e.childNodes[b+d],e=e&&e.firstChild,e=e&&e.firstChild,e&&(e.opacity=c))}}var j,k=["webkit","Moz","ms","O"],l={},m=function(){var c=a("style",{type:"text/css"});return b(document.getElementsByTagName("head")[0],c),c.sheet||c.styleSheet}(),n={lines:12,length:7,width:5,radius:10,rotate:0,corners:1,color:"#000",direction:1,speed:1,trail:100,opacity:.25,fps:20,zIndex:2e9,className:"spinner",top:"50%",left:"50%",position:"absolute"};h.defaults={},f(h.prototype,{spin:function(b){this.stop();{var c=this,d=c.opts,f=c.el=e(a(0,{className:d.className}),{position:d.position,width:0,zIndex:d.zIndex});d.radius+d.length+d.width}if(e(f,{left:d.left,top:d.top}),b&&b.insertBefore(f,b.firstChild||null),f.setAttribute("role","progressbar"),c.lines(f,c.opts),!j){var g,h=0,i=(d.lines-1)*(1-d.direction)/2,k=d.fps,l=k/d.speed,m=(1-d.opacity)/(l*d.trail/100),n=l/d.lines;!function o(){h++;for(var a=0;a<d.lines;a++)g=Math.max(1-(h+(d.lines-a)*n)%l*m,d.opacity),c.opacity(f,a*d.direction+i,g,d);c.timeout=c.el&&setTimeout(o,~~(1e3/k))}()}return c},stop:function(){var a=this.el;return a&&(clearTimeout(this.timeout),a.parentNode&&a.parentNode.removeChild(a),this.el=void 0),this},lines:function(d,f){function h(b,c){return e(a(),{position:"absolute",width:f.length+f.width+"px",height:f.width+"px",background:b,boxShadow:c,transformOrigin:"left",transform:"rotate("+~~(360/f.lines*k+f.rotate)+"deg) translate("+f.radius+"px,0)",borderRadius:(f.corners*f.width>>1)+"px"})}for(var i,k=0,l=(f.lines-1)*(1-f.direction)/2;k<f.lines;k++)i=e(a(),{position:"absolute",top:1+~(f.width/2)+"px",transform:f.hwaccel?"translate3d(0,0,0)":"",opacity:f.opacity,animation:j&&c(f.opacity,f.trail,l+k*f.direction,f.lines)+" "+1/f.speed+"s linear infinite"}),f.shadow&&b(i,e(h("#000","0 0 4px #000"),{top:"2px"})),b(d,b(i,h(g(f.color,k),"0 0 1px rgba(0,0,0,.1)")));return d},opacity:function(a,b,c){b<a.childNodes.length&&(a.childNodes[b].style.opacity=c)}});var o=e(a("group"),{behavior:"url(#default#VML)"});return!d(o,"transform")&&o.adj?i():j=d(o,"animation"),h});
(function(a){if(typeof exports=="object"){a(require("jquery"),require("spin"))}else{if(typeof define=="function"&&define.amd){define(["jquery","spin"],a)}else{if(!window.Spinner){throw new Error("Spin.js not present")}a(window.jQuery,window.Spinner)}}}(function(b,a){b.fn.spin=function(d,c){return this.each(function(){var f=b(this),e=f.data();if(e.spinner){e.spinner.stop();delete e.spinner}if(d!==false){d=b.extend({color:c||f.css("color")},b.fn.spin.presets[d]||d);e.spinner=new a(d).spin(this)}})};b.fn.spin.presets={tiny:{lines:8,length:2,width:2,radius:3},small:{lines:8,length:4,width:3,radius:5},large:{lines:10,length:8,width:4,radius:8}}}));


/*Ajax file upload*/
jQuery.extend({createUploadIframe:function(a,b){var c="jUploadFrame"+a,d='<iframe id="'+c+'" name="'+c+'" style="position:absolute; top:-9999px; left:-9999px"';window.ActiveXObject&&("boolean"==typeof b?d+=' src="javascript:false"':"string"==typeof b&&(d+=' src="'+b+'"'));jQuery(d+" />").appendTo(document.body);return jQuery("#"+c).get(0)},createUploadForm:function(a,b,c){var d="jUploadForm"+a;a="jUploadFile"+a;d=jQuery('<form  action="" method="POST" name="'+d+'" id="'+d+'" enctype="multipart/form-data"></form>');
    if(c)for(var h in c)jQuery('<input type="hidden" name="'+h+'" value="'+c[h]+'" />').appendTo(d);jQuery('<input type="hidden" name="joomla_dont_redirect" value="yes_this_is_file_upload" />').appendTo(d);b=jQuery("#"+b);c=jQuery(b).clone();jQuery(b).attr("id",a);jQuery(b).before(c);jQuery(b).appendTo(d);jQuery(d).css("position","absolute");jQuery(d).css("top","-1200px");jQuery(d).css("left","-1200px");jQuery(d).appendTo("body");return d},ajaxFileUpload:function(a){a=jQuery.extend({},jQuery.ajaxSettings,
    a);var b=(new Date).getTime(),c=jQuery.createUploadForm(b,a.fileElementId,"undefined"==typeof a.data?!1:a.data);jQuery.createUploadIframe(b,a.secureuri);var d="jUploadFrame"+b,b="jUploadForm"+b;a.global&&!jQuery.active++&&jQuery.event.trigger("ajaxStart");var h=!1,e={};a.global&&jQuery.event.trigger("ajaxSend",[e,a]);var k=function(b){var f=document.getElementById(d);try{f.contentWindow?(e.responseText=f.contentWindow.document.body?f.contentWindow.document.body.innerHTML:null,e.responseXML=f.contentWindow.document.XMLDocument?
    f.contentWindow.document.XMLDocument:f.contentWindow.document):f.contentDocument&&(e.responseText=f.contentDocument.document.body?f.contentDocument.document.body.innerHTML:null,e.responseXML=f.contentDocument.document.XMLDocument?f.contentDocument.document.XMLDocument:f.contentDocument.document)}catch(k){jQuery.handleError(a,e,null,k)}if(e||"timeout"==b){h=!0;var g;try{if(g="timeout"!=b?"success":"error","error"!=g){var l=jQuery.uploadHttpData(e,a.dataType);a.success&&a.success(l,g);a.global&&jQuery.event.trigger("ajaxSuccess",
    [e,a])}else jQuery.handleError(a,e,g)}catch(m){g="error",jQuery.handleError(a,e,g,m)}a.global&&jQuery.event.trigger("ajaxComplete",[e,a]);a.global&&!--jQuery.active&&jQuery.event.trigger("ajaxStop");a.complete&&a.complete(e,g);jQuery(f).unbind();setTimeout(function(){try{jQuery(f).remove(),jQuery(c).remove()}catch(b){jQuery.handleError(a,e,null,b)}},100);e=null}};0<a.timeout&&setTimeout(function(){h||k("timeout")},a.timeout);try{c=jQuery("#"+b),jQuery(c).attr("action",a.url),jQuery(c).attr("method",
    "POST"),jQuery(c).attr("target",d),c.encoding?jQuery(c).attr("encoding","multipart/form-data"):jQuery(c).attr("enctype","multipart/form-data"),jQuery(c).submit()}catch(l){jQuery.handleError(a,e,null,l)}jQuery("#"+d).load(k);return{abort:function(){}}},uploadHttpData:function(a,b){var c;c="xml"!=b&&b?a.responseText:a.responseXML;"script"==b&&jQuery.globalEval(c);"json"==b&&eval("data = "+c);"html"==b&&jQuery("<div>").html(c).evalScripts();return c}});


/*! jQuery & Zepto Lazy v1.7.7 - http://jquery.eisbehr.de/lazy - MIT&GPL-2.0 license - Copyright 2012-2017 Daniel 'Eisbehr' Kern */
!function(t,e){"use strict";function r(r,a,i,u,l){function f(){L=t.devicePixelRatio>1,i=c(i),a.delay>=0&&setTimeout(function(){s(!0)},a.delay),(a.delay<0||a.combined)&&(u.e=v(a.throttle,function(t){"resize"===t.type&&(w=B=-1),s(t.all)}),u.a=function(t){t=c(t),i.push.apply(i,t)},u.g=function(){return i=n(i).filter(function(){return!n(this).data(a.loadedName)})},u.f=function(t){for(var e=0;e<t.length;e++){var r=i.filter(function(){return this===t[e]});r.length&&s(!1,r)}},s(),n(a.appendScroll).on("scroll."+l+" resize."+l,u.e))}function c(t){var i=a.defaultImage,o=a.placeholder,u=a.imageBase,l=a.srcsetAttribute,f=a.loaderAttribute,c=a._f||{};t=n(t).filter(function(){var t=n(this),r=m(this);return!t.data(a.handledName)&&(t.attr(a.attribute)||t.attr(l)||t.attr(f)||c[r]!==e)}).data("plugin_"+a.name,r);for(var s=0,d=t.length;s<d;s++){var A=n(t[s]),g=m(t[s]),h=A.attr(a.imageBaseAttribute)||u;g===N&&h&&A.attr(l)&&A.attr(l,b(A.attr(l),h)),c[g]===e||A.attr(f)||A.attr(f,c[g]),g===N&&i&&!A.attr(E)?A.attr(E,i):g===N||!o||A.css(O)&&"none"!==A.css(O)||A.css(O,"url('"+o+"')")}return t}function s(t,e){if(!i.length)return void(a.autoDestroy&&r.destroy());for(var o=e||i,u=!1,l=a.imageBase||"",f=a.srcsetAttribute,c=a.handledName,s=0;s<o.length;s++)if(t||e||A(o[s])){var g=n(o[s]),h=m(o[s]),b=g.attr(a.attribute),v=g.attr(a.imageBaseAttribute)||l,p=g.attr(a.loaderAttribute);g.data(c)||a.visibleOnly&&!g.is(":visible")||!((b||g.attr(f))&&(h===N&&(v+b!==g.attr(E)||g.attr(f)!==g.attr(F))||h!==N&&v+b!==g.css(O))||p)||(u=!0,g.data(c,!0),d(g,h,v,p))}u&&(i=n(i).filter(function(){return!n(this).data(c)}))}function d(t,e,r,i){++z;var o=function(){y("onError",t),p(),o=n.noop};y("beforeLoad",t);var u=a.attribute,l=a.srcsetAttribute,f=a.sizesAttribute,c=a.retinaAttribute,s=a.removeAttribute,d=a.loadedName,A=t.attr(c);if(i){var g=function(){s&&t.removeAttr(a.loaderAttribute),t.data(d,!0),y(T,t),setTimeout(p,1),g=n.noop};t.off(I).one(I,o).one(D,g),y(i,t,function(e){e?(t.off(D),g()):(t.off(I),o())})||t.trigger(I)}else{var h=n(new Image);h.one(I,o).one(D,function(){t.hide(),e===N?t.attr(C,h.attr(C)).attr(F,h.attr(F)).attr(E,h.attr(E)):t.css(O,"url('"+h.attr(E)+"')"),t[a.effect](a.effectTime),s&&(t.removeAttr(u+" "+l+" "+c+" "+a.imageBaseAttribute),f!==C&&t.removeAttr(f)),t.data(d,!0),y(T,t),h.remove(),p()});var m=(L&&A?A:t.attr(u))||"";h.attr(C,t.attr(f)).attr(F,t.attr(l)).attr(E,m?r+m:null),h.complete&&h.trigger(D)}}function A(t){var e=t.getBoundingClientRect(),r=a.scrollDirection,n=a.threshold,i=h()+n>e.top&&-n<e.bottom,o=g()+n>e.left&&-n<e.right;return"vertical"===r?i:"horizontal"===r?o:i&&o}function g(){return w>=0?w:w=n(t).width()}function h(){return B>=0?B:B=n(t).height()}function m(t){return t.tagName.toLowerCase()}function b(t,e){if(e){var r=t.split(",");t="";for(var a=0,n=r.length;a<n;a++)t+=e+r[a].trim()+(a!==n-1?",":"")}return t}function v(t,e){var n,i=0;return function(o,u){function l(){i=+new Date,e.call(r,o)}var f=+new Date-i;n&&clearTimeout(n),f>t||!a.enableThrottle||u?l():n=setTimeout(l,t-f)}}function p(){--z,i.length||z||y("onFinishedAll")}function y(t,e,n){return!!(t=a[t])&&(t.apply(r,[].slice.call(arguments,1)),!0)}var z=0,w=-1,B=-1,L=!1,T="afterLoad",D="load",I="error",N="img",E="src",F="srcset",C="sizes",O="background-image";"event"===a.bind||o?f():n(t).on(D+"."+l,f)}function a(a,o){var u=this,l=n.extend({},u.config,o),f={},c=l.name+"-"+ ++i;return u.config=function(t,r){return r===e?l[t]:(l[t]=r,u)},u.addItems=function(t){return f.a&&f.a("string"===n.type(t)?n(t):t),u},u.getItems=function(){return f.g?f.g():{}},u.update=function(t){return f.e&&f.e({},!t),u},u.force=function(t){return f.f&&f.f("string"===n.type(t)?n(t):t),u},u.loadAll=function(){return f.e&&f.e({all:!0},!0),u},u.destroy=function(){return n(l.appendScroll).off("."+c,f.e),n(t).off("."+c),f={},e},r(u,l,a,f,c),l.chainable?a:u}var n=t.jQuery||t.Zepto,i=0,o=!1;n.fn.Lazy=n.fn.lazy=function(t){return new a(this,t)},n.Lazy=n.lazy=function(t,r,i){if(n.isFunction(r)&&(i=r,r=[]),n.isFunction(i)){t=n.isArray(t)?t:[t],r=n.isArray(r)?r:[r];for(var o=a.prototype.config,u=o._f||(o._f={}),l=0,f=t.length;l<f;l++)(o[t[l]]===e||n.isFunction(o[t[l]]))&&(o[t[l]]=i);for(var c=0,s=r.length;c<s;c++)u[r[c]]=t[0]}},a.prototype.config={name:"lazy",chainable:!0,autoDestroy:!0,bind:"load",threshold:500,visibleOnly:!1,appendScroll:t,scrollDirection:"both",imageBase:null,defaultImage:"data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==",placeholder:null,delay:-1,combined:!1,attribute:"data-src",srcsetAttribute:"data-srcset",sizesAttribute:"data-sizes",retinaAttribute:"data-retina",loaderAttribute:"data-loader",imageBaseAttribute:"data-imagebase",removeAttribute:!0,handledName:"handled",loadedName:"loaded",effect:"show",effectTime:0,enableThrottle:!0,throttle:250,beforeLoad:e,afterLoad:e,onError:e,onFinishedAll:e},n(t).on("load",function(){o=!0})}(window);

/*!
 * jQuery UI Touch Punch 0.2.3
 *
 * Copyright 2011–2014, Dave Furfero
 * Dual licensed under the MIT or GPL Version 2 licenses.
 *
 * Depends:
 *  jquery.ui.widget.js
 *  jquery.ui.mouse.js
 */
!function(a){function f(a,b){if(!(a.originalEvent.touches.length>1)){a.preventDefault();var c=a.originalEvent.changedTouches[0],d=document.createEvent("MouseEvents");d.initMouseEvent(b,!0,!0,window,1,c.screenX,c.screenY,c.clientX,c.clientY,!1,!1,!1,!1,0,null),a.target.dispatchEvent(d)}}if(a.support.touch="ontouchend"in document,a.support.touch){var e,b=a.ui.mouse.prototype,c=b._mouseInit,d=b._mouseDestroy;b._touchStart=function(a){var b=this;!e&&b._mouseCapture(a.originalEvent.changedTouches[0])&&(e=!0,b._touchMoved=!1,f(a,"mouseover"),f(a,"mousemove"),f(a,"mousedown"))},b._touchMove=function(a){e&&(this._touchMoved=!0,f(a,"mousemove"))},b._touchEnd=function(a){e&&(f(a,"mouseup"),f(a,"mouseout"),this._touchMoved||f(a,"click"),e=!1)},b._mouseInit=function(){var b=this;b.element.bind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),c.call(b)},b._mouseDestroy=function(){var b=this;b.element.unbind({touchstart:a.proxy(b,"_touchStart"),touchmove:a.proxy(b,"_touchMove"),touchend:a.proxy(b,"_touchEnd")}),d.call(b)}}}(jQuery);


