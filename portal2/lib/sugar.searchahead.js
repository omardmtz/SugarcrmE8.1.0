/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */
(function($) {
    var SearchAhead = function (element, options) {
        this.options = $.extend({}, $.fn.searchahead.defaults, options);
        /*
         * Example initialization of plugin:
         *
         * this.$('.search-query').searchahead({
         *     request:  fireSearchRequest,
         *     compiler: menuTemplate,
         *     buttonElement: '.navbar-search a.btn'
         * });
         */

        // A String representing the selector of search input element
        // required
        this.$element = $(element);

        // request: function that makes http request. It will receive one parameter,
        // the current search term, and must call the plugin.provide method with data
        // received. Note, it will receive have the plugin scope. For example:
        //
        // var plugin = this; // this points to the searchahead plugin's this
        // app.api.search(term, 'name', {
        //   success:function(data) {
        //     plugin.provide(data);
        //   }
        // });
        // Please note that if you use the optional `context` option when setting up the
        // searchahead plugin, the context will be that (not the plugin), and so you will
        // need to call provide as follows:
        // this.$('.search-query').searchahead('provide', data);
        //
        // required
        this.request = this.options.request;

        // Compiler will be passed data as an object literal like:
        // { data: <data>, term: <last_search_term> }
        // It must produce a string representing the markup desired.
        //
        // compile will get the data + last search term
        //
        // required
        this.compiler = this.options.compiler;
        // Optional context to use when calling back the `request` and `onEnterFn` hooks.
        // Useful if the caller wants to preserve their context when they get called back
        // (otherwise, `this` will point to the plugin's `this` not the caller's `this`)
        this.context = options.context || null;

        // Optional - rest of these have defaults if not supplied
        this.$button = this.options.buttonElement ? $(this.options.buttonElement) : null;// optional search button that triggers search ahead dropdown
        this.throttle = this.options.throttle || this.throttle;
        this.throttleMillis = this.options.throttleMillis || this.throttleMillis;
        // TODO: Assumes <div id='searchForm'><form class="navbar-search"><input></form><div class="typeahead-wrapper"> structure ... hence parent of parent
        this.$menu = $(this.options.menu).appendTo(this.$element.parent().next('.typeahead-wrapper'));
        this.shown = false;
        // Since 0 is falsy in javascript, use -1 if no minimum desired
        this.minChars = this.options.minChars || 1;
        this.patchIfZepto();
        this.listen();
        // If user hitting enter key should be considered trigger to follow selected link (or
        // current search term if they haven't navigated in to the drop down results) use function
        // Please note that if the `context` option was used when setting up the plugin, that will be
        // the context of `this` when onEnterFn is called (otherwise will be this plugin's `this`)
        this.onEnterFn = this.options.onEnterFn || null;
    };

    SearchAhead.prototype = {
        constructor: SearchAhead,
        compile: function(data) {
            //URI encode the search query term to keep href safe (bug55572)
            return (this.compiler({data:data, term: encodeURIComponent(this.query)}));
        },
        disabled: false,
        enable: function() {
            this.disabled = false;
        },
        disable: function(millis) {
            var self = this;
            self.disabled = true;
            self.hide();
            // Hack - in case a search is just right now about to be rendered we have to hide
            setTimeout(function() {
                self.hide();
            }, self.throttleMillis);
            setTimeout(function() {
                self.enable();
            }, millis);
        },
        throttleMillis: 250,
        throttle: function(callback, millis) {
            var timerId = null;
            timerId = setTimeout(function() {
                callback();
            }, millis);
        },
        go: function () {
            var activeItem = null, href = null, term = null;
            activeItem = this.$menu.find('.active');
            href = activeItem.find('a').attr('href');
            if(href) {
                if(this.onEnterFn) {
                    // Check if plugin was provided a context to use
                    if (this.context) {
                        this.onEnterFn.call(this.context, href, true);
                    } else {
                        this.onEnterFn(href, true);
                    }
                } else {
                    window.location = href;
                }
            } else {
                // They may not have any item in dropdown selected.

                // Search term is a URI segment, we need to encode it so
                // that it gets routed appropriately. (bug55572)
                term= encodeURIComponent(this.$element.val());

                // In this case, use term entered if any.
                if(term && term.length) {
                    // If no onEnterFn do nothing.
                    if(this.onEnterFn) {
                        // Check to se if plugin was provided a context to use
                        if (this.context) {
                            this.onEnterFn.call(this.context, term, false);
                        } else {
                            this.onEnterFn(term, false);
                        }
                    }
                }
            }
            return this.hide();
        },
        show: function () {
            this.$menu.show();
            this.shown = true;

            if(!this.disabled) {
                this.$menu.show();
                this.shown = true;
                return this;
            }
            return null;
        },
        hide: function () {
            this.$menu.hide();
            this.shown = false;
            return this;
        },
        // User clicks the search button (optional buttton)
        // then do autocomplete dropdown and put focus back on input
        buttonLookup: function (evt) {
            evt.stopPropagation();
            evt.preventDefault();
            this.lookup(evt);
            this.$element.focus();
        },
        lookup: function (evt) {
            var that = this, items;
            this.query = this.$element.val();
            if (!this.query) {
                return this.shown ? this.hide() : this;
            }
            this.onSearch(evt);
        },
        onSearch: function(evt) {
            var self = this;
            //filter out up/down, tab, enter, and escape keys
            if( $.inArray(evt.keyCode,[40,38,9,13,27]) === -1 ){
                evt.preventDefault();
                self.throttle(function() {
                    // check length of current term just before make the call .. rapid back button ;)
                    self.query = self.$element.val();
                    if(self.query && self.query.length >= self.minChars) {
                        if (self.context) {
                            self.request.call(self.context, self.query);
                        } else {
                            self.request(self.query);
                        }
                    } else {
                        self.hide();
                    }
                }, self.throttleMillis);
            }
        },
        provide: function (data) {
            var trimmed, markup = this.compile(data), self = this;
            trimmed = markup.replace(/^\s+/,'').replace(/\s+$/,'');
            if(trimmed.length) {
                self.$menu.html(markup);
                if(!self.disabled) {
                    return self.show();
                }
            }
            return null;
        },
        move: function (event, direction) {
            var active = this.$menu.find('.active').removeClass('active'), move;
            if(direction==='next') {
                move = active.next();
            } else {
                move = active.prev();
            }
            if (!move.length) {
                move = $(this.$menu.find('li')[0]);
            }
            move.addClass('active');
        },
        next: function (e) {
            this.move(e, 'next');
        },
        prev: function (e) {
            this.move(e, 'prev');
        },
        // These are mostly lifted from:
        // http://blog.pamelafox.org/2011/11/porting-from-jquery-to-zepto.html
        patchIfZepto: function() {

            if(!_.isUndefined(window.Zepto)) {

                // patch support
                $.support = {};

                // patch proxy
                $.proxy = function( fn, context ) {
                    var args, tmp, proxy;

                    if ( typeof context === "string" ) {
                        tmp = fn[ context ];
                        context = fn;
                        fn = tmp;
                    }
                    // Quick check to determine if target is callable, in the spec
                    // // this throws a TypeError, but we will just return undefined.
                    if ( !$.isFunction( fn ) ) {
                        return undefined;
                    }
                    // Simulated bind
                    args = Array.prototype.slice.call( arguments, 2 );
                    proxy = function() {
                        return fn.apply( context, args.concat( Array.prototype.slice.call( arguments ) ) );
                    };
                    // Set the guid of unique handler to the same of original handler, so it can be removed
                    proxy.guid = fn.guid = fn.guid || proxy.guid || $.guid++;
                    return proxy;
                };
            }
        },
        listen: function () {
            // $element is the search input
            this.$element
                .on('blur',     $.proxy(this.blur, this))
                .on('keypress', $.proxy(this.keypress, this))
                .on('keyup',    $.proxy(this.keyup, this));

            if ($.browser.webkit || $.browser.msie) {
                this.$element.on('keydown', $.proxy(this.keypress, this));
            }
            // This is an optional search button that may reside next to the
            // search input. If buttonElement set, then will exist. Essentially,
            // this will just trigger a lookup and put focus on the input
            if (this.$button) {
                this.$button
                    .on('click', $.proxy(this.buttonLookup, this));
                if (app.accessibility) {
                    app.accessibility.run(this.$button, 'click');
                }
            }

            // $menu is the dynamic dropdown
            this.$menu
                .on('mouseenter', 'li', $.proxy(this.mouseenter, this));
        },
        // Keyups while within the input
        keyup: function (e) {
            switch(e.keyCode) {
                case 40: // down arrow
                case 38: // up arrow
                    break;
                case 9:  // tab
                    if(!this.options.tabToSelect)
                    break;
                case 13: // enter

                    if(this.onEnterFn) {
                        this.go();
                        break;
                    }

                    if (!this.shown) return;
                    this.lookup(e);
                    break;

                case 27: // escape
                    if (!this.shown) return;
                    this.hide();
                    break;

                default:
                    this.lookup(e);
            }
            e.stopPropagation();
            e.preventDefault();
        },
        keypress: function (e) {
            if (!this.shown) return;

            switch(e.keyCode) {
                case 9:  // tab
                case 13: // enter
                case 27: // escape
                    e.preventDefault();
                    break;

                case 38: // up arrow
                    if (e.type !== 'keydown') break;
                    e.preventDefault();
                    this.prev();
                    break;

                case 40: // down arrow
                    if (e.type !== 'keydown') break;
                    e.preventDefault();
                    this.next();
                    break;
            }
            e.stopPropagation();
        },
        blur: function (e) {
            var that = this;
            setTimeout(function () { that.hide(); }, 500);
        },
        mouseenter: function (e) {
            this.$menu.find('.active').removeClass('active');
            $(e.currentTarget).addClass('active');
        }
    };

    $.fn.searchahead = function () {
        var option = arguments[0],
            args = Array.prototype.slice.call(arguments, 1);
        return this.each(function () {
            var $this = $(this),
                data = $this.data('searchahead'),
                options = typeof option === 'object' && option;

            if (!data) {
                $this.data('searchahead', (data = new SearchAhead(this, options)));
            }
            if (typeof option === 'string') {
                // this is gonna do something like plugin.provide(data) etc.
                // note that `data` here is the context of the plugin itself
                data[option].apply(data, args);
            }
        });
    };

    $.fn.searchahead.defaults = {
        items: 10,
        // TODO: typeahead is legacy; when we update styleguide this
        // should probably be 'searchahead'
        menu: '<ul class="typeahead dropdown-menu"></ul>'
    };
    $.fn.searchahead.Constructor = SearchAhead;

})(window.$);

