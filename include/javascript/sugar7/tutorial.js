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
/**
 * @class View.TutorialView
 * @alias SUGAR.App.tutorial
 * @singleton
 */
(function(app) {

    /*
        Overlays a tutorial.

        // module parameters
        version: Module tutorial version. Increment value to show new data to those who have seen it.
        scroll: (optional) Element that is scrollable.
        intro: (optional) Text shown at the beginning of the tutorial.
        content: Tutorial content. See content parameters below.

        // content parameters
        name: (optional) CSS selector of the element to highlight.
            If no element found, content will be skipped.
            If no name, text will be shown without highlighting an element.
        text: Tutorial text for that element.
        horizAdj: (optional) Adjust the highlight position horizontally, negative values are acceptable.
        vertAdj: (optional) Adjust the highlight position vertically, negative values are acceptable.
        glow: (optional) Show spotlight behind the highlight, defaults to true.

        // Sample Data

        app.tutorial.data = {
            "home": {
                "version": 1,
                "scroll": ".list-layout",
                "intro": "Welcome to SugarCRM.<br/><br/> Allow us to highlight some new features...",
                "content":[{
                        "name": "#logo",
                        "text": "Yum! Sugar Cube!"
                    },
                    {
                        "name": "#create",
                        "text": "Create from the Plus!"
                    },
                    {
                        "name": "#blahblah",
                        "text": "This missing element will be skipped."
                    },
                    {
                        "name": ".grip",
                        "text": "Slide grip to get more detailed options",
                        "horizAdj": 5,
                        "vertAdj": 10,
                        "glow": false
                    },
                    {
                        "text": "Explain new features!"
                    },
                    {
                        "name": "#bucket-Upcoming .bucket-header",
                        "text": "Upcoming Items go here",
                        "full": true
                    },
                    {
                        "name": "#bucket-Recent .bucket-header",
                        "text": "Recent Items go here",
                        "horizAdj": 8,
                        "vertAdj": -5
                    },
                    {
                        "text": "Enjoy all the new features!"
                    }]
            },
            "detail": {
                "version": 1,
                    content: [{
                    "text": "Welcome to your detail view!"
                }]
            },
            "edit": {
                "version": 1,
                    content: [{
                    "text": "Welcome to your edit view!"
                }]
            },
            "home-menu": {
                "version": 1,
                    content: [{
                    "text": "Welcome to your home menu!"
                }]
            },
            "plus-menu": {
                "version": 1,
                    content: [{
                    "text": "Welcome to your plus menu!"
                }]
            }
        }
    */

    // in milliseconds
    var animationSpeed = 500;
    // amount of padding to have between the top of the scrollable area and the item when we scroll to it
    var scrollOffset = 25;
    // reduce the calculated viewed area, used if you want to have a padding at the bottom of the viewed area for controls
    var scrollViewReduction = 75;

    app.augment('tutorial', {
        instance: null,
        /**
         * Test if a layout for the given module has a Tutorial associated with
         * it. By default, this will check if the layout associated with the
         * default context has a Tutorial.
         *
         * @param {string} [layout] layout name to check.
         * @param {string} [module] module name to check.
         * @return {boolean} `true` if a tutorial exists for this layout,
         *   `false` otherwise.
         */
        hasTutorial: function(layout, module) {
            module = module || app.controller.context.get('module');
            layout = layout || app.controller.context.get('layout');

            var meta = app.metadata.getModule(module);
            //Cache default tutorial metadata
            var defaultMeta = app.metadata.getView('','tutorial');
            if (defaultMeta) {
                this.data = defaultMeta;
            }
            if (meta && meta.views && meta.views.tutorial && meta.views.tutorial.meta && meta.views.tutorial.meta[layout]) {
                return true;
            } else if (this.data && this.data[layout]) {
                return true;
            } else {
                return false;
            }
        },
        show: function(name, params) {
            if (app.tutorial.data) {
                this.doTutorial(name, params);
            } else if (app.metadata.getView('','tutorial')) {
                //Cache default tutorial metadata
                app.tutorial.data = app.metadata.getView('','tutorial');
                this.doTutorial(name, params);
            }
        },
        doTutorial: function(name, params) {
            var tutorialData = app.tutorial.data[name];
            var prefKey = name;
            // check for module specific tutorial data
            if (params && params.module) {
                var meta = app.metadata.getModule(params.module);
                if (meta.views && meta.views.tutorial && meta.views.tutorial.meta && meta.views.tutorial.meta[name]) {
                    tutorialData = meta.views.tutorial.meta[name];
                    prefKey = name + params.module;
                }
            }

            var prefs = this.getPrefs();
            if (tutorialData &&
                (!prefs.skipVersion[prefKey] || prefs.skipVersion[prefKey] < tutorialData.version) &&
                (!prefs.viewedVersion[prefKey] || prefs.viewedVersion[prefKey] < tutorialData.version)) {
                params = _.extend(tutorialData, {type: 'Tutorial'});
                app.tutorial.instance = app.view.createView(params);
                app.tutorial.instance.show();

                prefs.viewedVersion[prefKey] = tutorialData.version;
                app.cache.set('tutorialPrefs', prefs);
            }
        },
        getPrefs: function() {
            var prefs = app.cache.get('tutorialPrefs') || {};
            prefs.viewedVersion = prefs.viewedVersion || {};
            prefs.skipVersion = prefs.skipVersion || {};
            return prefs;
        },
        resetPrefs: function(prefs) {
            prefs = prefs || {};
            prefs.viewedVersion = {};
            prefs.skipVersion = {};
            app.cache.set('tutorialPrefs', prefs);
        },
        // Skip all tutorials for this version
        skipTutorial: function() {
            var prefs = this.getPrefs();
            _.each(app.tutorial.data, function(data, name) {
                prefs.skipVersion[name] = app.tutorial.data[name].version;
            });
            app.user.setPreference('tutorialPrefs', prefs);
        }
    });

    /**
     * @class View.TutorialView
     * @alias SUGAR.App.view.TutorialView
     * @extends View.View
     *
     * TODO this needs to be removed from this path
     */
    app.view.TutorialView = app.view.View.extend({

        events: {
            'click .btn.disabled': function(e) {return false;},
            'click .back-btn:not(.disabled)':   'back',
            'click .next-btn:not(.disabled)':   'next',
            'click .done-btn':                  'hide',
            'swipeLeft':                        'onSwipeLeft'
        },

        /**
         * Flag to indicate the `highlight` animation is in progess.
         *
         * @property {boolean}
         * @private
         */
        _duringAnimate: false,

        initialize: function(options) {
            options = _.extend({
            }, options || {});

            this.index = 0;
            this.setIntro(options.intro);
            this.setContent(options.content);
            this.setScroll(options.scroll);

            app.events.on('app:login app:logout', this.hide, this);
        },

        setContent: function(content) {
            this.content = content;
        },

        setIntro: function(intro) {
            this.intro = intro;
        },

        setScroll: function(scroll) {
            this.scroll = scroll;
        },

        reset: function() {
            this.index = 0;
        },

        onSwipeLeft: function(e) {
            app.tutorial.skipTutorial();
            this.hide(e);
        },

        show: function(options) {
            options = _.extend({
            }, options || {});
            if (options.content) {
                this.setContent(options.content);
            }
            if (options.intro) {
                this.setIntro(options.intro);
            }
            if (options.scroll) {
                this.setScroll(options.scroll);
            }
            if (options.reset) {
                this.reset();
            }

            this.render();

            if (this.intro && !this.introShown) {
                this.index = -1;
                this.introShown = true;
                this.showMessageOnly(this.intro);
            } else {
                this.highlightItem(this.index);
            }
        },

        /**
         * Hides and disposes the tutorial.
         *
         * @param {Event} [event] Any event.
         */
        hide: function(event) {
            if (event) {
                event.preventDefault();
            }
            this.dispose();
        },

        /**
         * Goes to the previous item.
         *
         * Does nothing if an animation is in progress.
         *
         * @param {Event} [event] Any event.
         */
        back: function(event) {
            if (event) {
                event.preventDefault();
            }
            if (this._duringAnimate) {
                return;
            }
            this.highlightItem(this.index - 1);
        },

        /**
         * Goes to the next item.
         *
         * Does nothing if an animation is in progress.
         *
         * @param {Event} [event] Any event.
         */
        next: function(event) {
            if (event) {
                event.preventDefault();
            }
            if (this._duringAnimate) {
                return;
            }
            this.highlightItem(this.index + 1);
        },

        /**
         * Moves the highlighted item to another item.
         *
         * Does nothing when going before the first position, and hides the
         * tutorial after the last item.
         *
         * @param {number} index The index of the target item.
         */
        highlightItem: function(index) {
            var self = this;

            if (index < 0) {
                return;
            }
            if (index >= this.content.length) {
                this.hide();
                return;
            }

            var content = this.content[index];

            // If there is no name for an element, just show the text.
            if (!content.name) {
                this.showMessageOnly(content.text);
            } else {
                var item = $(content.name);


                // Skip any items that are not found
                var direction = this.index > index ? -1 : 1;
                if ((item.length === 0 || item.css('display') === 'none')  || item.parents('.hide').length > 0) {
                    return this.highlightItem(index + direction);
                }

                var highlightCallback =  function() {
                    // If no existing highlight, create one
                    if (!this.highlight) {
                        this.highlight = $('<div/>');
                        this.highlight.attr('id', 'highlight');

                        this.highlightText = $('<div/>');
                        this.highlightText.attr('id', 'highlight-text');
                        this.highlight.html(this.highlightText);

                        $('#tutorial-content').html(this.highlight);
                    }

                    // Hide the text and glow since it doesn't make sense to animate (text), or can't be animated (glow)
                    this.highlightText.hide();
                    this.hideGlow();

                    this._duringAnimate = true;
                    // Move the highlight to the element
                    this.highlight.animate({
                        'top': item.offset().top + (content.vertAdj || 0),
                        'left': item.offset().left + (content.horizAdj || 0),
                        'width': content.full ? item.offset().width : '',
                        'height': content.full ? item.offset().height : ''
                    }, animationSpeed, 'linear', function() {
                        self._duringAnimate = false;
                        // default to have glow effect
                        if (content.glow === undefined || content.glow !== false) {
                            self.showGlow();
                        }
                        self.showHighlightText(content.text);
                    });
                };
                highlightCallback = _.bind(highlightCallback, this);
                this.scrollToEl(item, highlightCallback);
            }

            // Update the state of the controls
            if (index <= 0) {
                this.$el.find('.back-btn').addClass('disabled');
            } else {
                this.$el.find('.back-btn').removeClass('disabled');
            }

            if (index >= (this.content.length - 1)) {
                this.$el.find('.next-btn').addClass('disabled');
            } else {
                this.$el.find('.next-btn').removeClass('disabled');
            }

            this.index = index;
        },

        showGlow: function() {
            this.$el.find('#mask').css('background-image', '-webkit-radial-gradient(' + (this.highlight.offset().left + (this.highlight.width() / 2)) + 'px ' +
                (this.highlight.offset().top + (this.getTotalHeight(this.highlight) / 2)) + 'px , ' +
                this.highlight.width() + 'px ' + this.highlight.height() + 'px, transparent, black ' + Math.max(this.highlight.width(), this.highlight.height()) + 'px)');
            this.$el.find('#mask').css('background', 'radial-gradient(circle closest-side at ' + (this.highlight.offset().left + (this.highlight.width() / 2)) + 'px ' +
                (this.highlight.offset().top + (this.getTotalHeight(this.highlight) / 2)) + 'px , ' + ' transparent 0%, black ' + Math.max(this.highlight.width(), this.highlight.height()) + 'px)');
            this.$el.find('#mask').css('background-color', 'transparent');
        },

        hideGlow: function() {
            this.$el.find('#mask').css('background-color', '');
            this.$el.find('#mask').css('background-image', '');
        },

        showHighlightText: function(message) {
            var top;
            var highlightBorderOffset;
            this.highlightText.html(app.lang.get(message, app.controller.context.get('module')));
            this.highlightText.show();

            //try left
            if (this.highlight.offset().left - this.getTotalWidth(this.highlightText) > 0 &&
                this.findIntersectors([this.highlight.offset().left - this.getTotalWidth(this.highlightText), this.highlight.offset().left],  // Check if we overlap controls, using left right positions
                    [this.highlight.offset().top + (this.getTotalHeight(this.highlight) / 2) - (this.getTotalHeight(this.highlightText) / 2), // and top bottom positions
                        this.highlight.offset().top + (this.getTotalHeight(this.highlight) / 2) - (this.getTotalHeight(this.highlightText) / 2) + this.getTotalHeight(this.highlightText)], '.btn-group').length === 0) {
                highlightBorderOffset = parseInt(this.highlight.css('border-left-width'));
                this.highlightText.css('left', 0 - (this.getTotalWidth(this.highlightText) + highlightBorderOffset));
                top = (this.getTotalHeight(this.highlight) / 2) - (this.getTotalHeight(this.highlightText) / 2);
                this.highlightText.css('top', top >= 0 ? top : 0);
                this.highlightText.css('text-align', 'right');
            } else if (this.highlight.offset().left + this.getTotalWidth(this.highlight) + this.getTotalWidth(this.highlightText) < $(window).width() &&
                this.findIntersectors([this.highlight.offset().left + this.getTotalWidth(this.highlight), this.highlight.offset().left + this.getTotalWidth(this.highlight) + this.getTotalWidth(this.highlightText)],  // Check if we overlap controls, left right positions
                    [this.highlight.offset().top + (this.getTotalHeight(this.highlight) / 2) - (this.getTotalHeight(this.highlightText) / 2), // and top bottom positions
                        this.highlight.offset().top + (this.getTotalHeight(this.highlight) / 2) - (this.getTotalHeight(this.highlightText) / 2) + this.getTotalHeight(this.highlightText)], '.btn-group').length === 0) { // try left) {
                highlightBorderOffset = parseInt(this.highlight.css('border-right-width'));
                this.highlightText.css('left', this.getTotalWidth(this.highlight) + highlightBorderOffset);
                top = (this.getTotalHeight(this.highlight) / 2) - (this.getTotalHeight(this.highlightText) / 2);
                this.highlightText.css('top', top >= 0 ? top : 0);
                this.highlightText.css('text-align', 'left');
            } else { // try below or above
                var newLeftVal = (this.getTotalWidth(this.highlight) / 2) - (this.getTotalWidth(this.highlightText)/ 2);
                var leftEdge = this.highlight.offset().left + newLeftVal;
                // make sure we're not off the screen on the left
                newLeftVal = leftEdge < 0 ? 0 - this.highlight.offset().left : newLeftVal;

                var rightEdge = this.highlight.offset().left + newLeftVal + this.getTotalWidth(this.highlightText);
                // make sure we're not off the screen on the right
                newLeftVal = rightEdge > $(window).width() ? newLeftVal - (rightEdge - $(window).width()) : newLeftVal;

                var newTopVal;
                // try below
                if (this.highlight.offset().top + this.getTotalHeight(this.highlight) + this.getTotalHeight(this.highlightText) < $(window).height() &&
                    this.findIntersectors([this.highlight.offset().left + newLeftVal, this.highlight.offset().left + newLeftVal + this.getTotalWidth(this.highlightText)],  // Check if we overlap controls, left right positions
                        [this.highlight.offset().top + this.getTotalHeight(this.highlight), // and top bottom positions
                            this.highlight.offset().top + this.getTotalHeight(this.highlight) + this.getTotalHeight(this.highlightText)], '.btn-group').length === 0) {
                    newTopVal = this.getTotalHeight(this.highlight);
                } else { // default to above
                    newTopVal = -5 - this.getTotalHeight(this.highlightText);
                }

                this.highlightText.css('left', newLeftVal);
                this.highlightText.css('top', newTopVal);
                this.highlightText.css('text-align', 'center');
            }
        },

        findIntersectors: function(tX, tY, intersectorsSelector) {
            var intersectors = [];
            var self = this;

            this.$el.find(intersectorsSelector).each(function() {
                var $this = $(this);
                var thisPos = $this.offset();
                var iX = [thisPos.left, thisPos.left + self.getTotalWidth($this)];
                var iY = [thisPos.top, thisPos.top + self.getTotalHeight($this)];

                if (tX[0] < iX[1] && tX[1] > iX[0] &&
                    tY[0] < iY[1] && tY[1] > iY[0]) {
                    intersectors.push($this);
                }

            });
            return intersectors;
        },

        showMessageOnly: function(message) {
            $('#tutorial-content').html("<div class='text-container'><div class='text'>" +
                app.lang.get(message, app.controller.context.get('module')) + '</div></div>');
            this.highlight = null;
            this.hideGlow();
        },

        getTotalWidth: function(el) {
            var totalWidth = el.width();
            totalWidth += parseInt(el.css('padding-left'), 10) + parseInt(el.css('padding-right'), 10);
            totalWidth += parseInt(el.css('margin-left'), 10) + parseInt(el.css('margin-right'), 10);

            return totalWidth;
        },

        getTotalHeight: function(el) {
            var totalHeight = el.height();
            totalHeight += parseInt(el.css('padding-top'), 10) + parseInt(el.css('padding-bottom'), 10);
            totalHeight += parseInt(el.css('margin-top'), 10) + parseInt(el.css('margin-bottom'), 10);

            return totalHeight;
        },

        scrollToEl: function($targetEl, callback) {
            var viewportHeight = $(window).height();
            var elTop = $targetEl.offset().top;
            var elHeight = $targetEl.height();
            var headerHeight = ($('.navbar').height() + 3) || 0;
            var footerHeight = $('footer').height() || 0;
            // the header and footer cover elements on the page so we account for this
            var buffer = 125;
            var direction;

            if ($.contains('.navbar', $targetEl)) {
                headerHeight = 0;
            }

            if ($.contains('footer', $targetEl)) {
                footerHeight = 0;
            }

            if (elTop + elHeight > window.pageYOffset + viewportHeight - footerHeight) {
                direction = 'down';
                buffer *= -1;
            } else if (elTop + elHeight < window.pageYOffset + elHeight + headerHeight) {
                direction = 'up';
            } else {
                direction = 'none';
                if (callback && _.isFunction(callback)) {
                    callback();
                }
            }

            if (direction !== 'none') {
                // scroll to element
                $('#content, .main-pane, .side-pane').animate({
                    scrollTop: elTop + buffer
                },
                {
                    duration: 'fast',
                    complete: function() {
                        if (callback && _.isFunction(callback)) {
                            callback();
                        }
                    }
                });
            }
        },

        render: function() {
            var tutorial = app.template.get('tutorial');
            $('body').append(tutorial());
            app.$contentEl.attr('aria-hidden', true);
            this.$el = $('#tutorial');

            this.delegateEvents();

            this.$el.show();
            return this;
        },

        /**
         * @inheritdoc
         */
        remove: function() {
            app.$contentEl.removeAttr('aria-hidden');
            if (app.tutorial.instance === this) {
                app.tutorial.instance = null;
            }
            if (this.highlight) {
                this.highlight.stop(true, true);
            }
            delete this.highlight;
            delete this.highlightText;
            Backbone.View.prototype.remove.call(this);
        }

    });

})(SUGAR.App);
