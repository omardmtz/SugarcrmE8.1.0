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
 * @class View.Views.Base.SweetspotResultsView
 * @alias SUGAR.App.view.views.BaseSweetspotResultsView
 * @extends View.View
 */
({
    className: 'sweetspot-results',
    tagName: 'ul',

    events: {
        'click li[data-sweetaction] > a': 'triggerAction',
    },

    /**
     * @inheritdoc
     *
     * - Listens to `sweetspot:results` on the layout to update the results.
     * - Listens to `keydown` on `window` to highlight an item.
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        // FIXME Sidecar should be modified to allow multiple top level contexts. When this happens, quick search
        // should use that context instead of layout.collection.
        this.collection = this.layout.collection || app.data.createMixedBeanCollection();

        /**
         * The list of results returned by Sweet Spot, split by category.
         *
         * It serves as a helper for the navigation within the results.
         * Includes:
         *
         *  - {@link #actions}
         *  - {@link #keywords}
         *  - {@link #records}
         *
         * @property {Array}
         */
        this.results = [];

        /**
         * The list of results returned by Sweet Spot, split by category.
         *
         * @property {Array}
         */
        this.records = [];

        /**
         * The list of results returned by Sweet Spot, split by category.
         *
         * @property {Array}
         */
        this.actions = [];

        /**
         * The list of results returned by Sweet Spot, split by category.
         *
         * @property {Array}
         */
        this.keywords = [];

        /**
         * Partial template for rendering a result row.
         *
         * @property {Function}
         */
        this._resultPartial = app.template.get(this.name + '.result');

        /**
         * Template for rendering the show more link.
         *
         * @property {Function}
         */
        this._showMoreTpl = app.template.get(this.name + '.showmore');

        /**
         * Stores the index of the currently highlighted list element.
         * This is used for keyboard navigation.
         *
         * @property {number}
         */
        this.activeIndex = null;

        // Listens to new set of results and updates the different sections.
        this.layout.on('sweetspot:results', function(results) {
            // We want to highlight the same item that was highlighted before,
            // so first we get the result that was highlighted.
            var oldHighlighted = this.results[this.activeIndex];
            var options = _.pick(results, 'showMore', 'term');

            // Rendering different sections
            this.renderSection('actions', this._formatResults(results.actions));
            this.renderSection('records', this._formatResults(results.records), options);
            this.renderSection('keywords', this._formatResults(results.keywords));

            if (options.showMore) {
                // This is so we get the moveForward/moveBackward working
                // properly.
                this.records.push(options);
            }

            // Update with the new list of records.
            this.results = this.keywords.concat(this.actions).concat(this.records);

            var newActiveIndex;
            if (oldHighlighted) {
                // Try to find the old highlighted result in the new data set.
                _.find(this.results, function(result, index) {
                    var test;
                    if (oldHighlighted.id) {
                        test = oldHighlighted.id === result.id;
                    }
                    if (oldHighlighted.route) {
                        test = oldHighlighted.route === result.route;
                    }
                    if (test) {
                        // Once we found it, we actually want its new index.
                        newActiveIndex = index;
                        return true;
                    }
                });
            }
            // Sets the item that will be highlighted.
            this.activeIndex = newActiveIndex || 0;
            if (this.results.length) {
                this._highlightActive();
                this.layout.trigger('sweetspot:calc:resultsHeight');
            }
        }, this);

        // Listens to when sweet spot is opened to bind keydown event
        this.layout.on('show', function() {
            this.results = this.actions = this.records = this.keywords = [];
            $(window).on('keydown.' + this.cid, _.bind(this.keydownHandler, this));
            this.render();
        }, this);

        // Listens to when sweet spot is opened to unbind keydown event
        this.layout.on('hide', function() {
            $(window).off('keydown.' + this.cid);
        }, this);

        this.layout.on('sweetspot:results:adjustMaxHeight', this.setMaxHeight, this);
    },

    /**
     * Sets the max-height of the element.
     *
     * @param {number} maxHeight The max-height value.
     */
    setMaxHeight: function(maxHeight) {
        if (this.results.length) {
            this.$el.css('maxHeight', maxHeight);
        }
    },

    /**
     * Renders a specific section.
     *
     * - Only if the list of records for that section has changed.
     * - Shows the section if there is at least a record
     * - Hides the section if there are no records.
     *
     * @param {string} section The section name (can be `actions`, `keywords`
     *   or `records`).
     * @param {Array} results The list of results for that section.
     * @param {Object} [options] Custom rendering options per section.
     */
    renderSection: function(section, results, options) {
        options = options || {};
        var allowed = ['actions', 'keywords', 'records'];
        if (!_.contains(allowed, section)) {
            return;
        }
        if (_.isEqual(this[section], results)) {
            return;
        }
        var $section = this.$('[data-section="' + section + '"]');
        var $list = $section.find('ul');
        $list.empty();
        this[section] = results;
        if (results.length === 0) {
            $section.addClass('hide');
            $list.empty();
        } else {
            $section.removeClass('hide');
            _.each(results, function(result) {
                $list.append(this._resultPartial(result));
            }, this);
            if (options.showMore) {
                options.searchRoute = '#search/' + options.term + '?';
                $list.append(this._showMoreTpl(options));
            }
        }
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this._super('_render');
        this.activeIndex = 0;
        if (this.results.length) {
            this._highlightActive();
        }
    },

    /**
     * Formatter function for the list of results.
     *
     * @param {Array} results The list of actions/commands.
     * @return {Array} The formatted list of actions/commands.
     */
    _formatResults: function(results) {
        if (_.isEmpty(results)) {
            return [];
        }

        return results;
    },

    /**
     * Handle the keydown events.
     * @param {event} e The `keydown` event.
     */
    keydownHandler: function(e) {
        switch (e.keyCode) {
            case 13: // enter
                this.triggerAction();
                break;
            case 40: // down arrow
                this.moveForward();
                e.preventDefault();
                break;
            case 38: // up arrow
                this.moveBackward();
                e.preventDefault();
                break;
        }
    },

    /**
     * Hides the {@link View.Layouts.Base.SweetspotLayout sweet spot layout}.
     */
    triggerHide: function() {
        this.layout.hide();
    },

    /**
     * Triggers the action linked to the active element.
     *
     * Navigates to the view or calls the callback method.
     *
     * @param {Event} [evt] The `click` event.
     */
    triggerAction: function(evt) {
        if (_.isEmpty(this.results)) {
            return;
        }

        this.triggerHide();

        var $action;
        if (evt) {
            evt.preventDefault();
            // When the user clicks on an action, we need to select that action
            // instead of the active one.
            $action = this.$(evt.currentTarget);
        } else {
            $action = this.$('.active > a');
        }

        var route = $action.data('route');
        if (route) {
            var openwindow = $action.data('openwindow');
            if (openwindow) {
                // If the there is an `openwindow` property configured on the action
                // metadata, open this action in a new browser window.
                window.open(route, '_blank');
                return;
            }

            app.router.navigate(route, {trigger: true});
        }

        var callback = $action.data('callback');
        if (callback) {
            this.layout.triggerSystemAction(callback);
        }
    },

    /**
     * Highlight the active element and unhighlight the rest of the elements.
     */
    _highlightActive: function() {
        this.$('.active').removeClass('active');
        var nth = this.activeIndex;
        var $active = this.$('[data-sweetaction=true]:nth(' + nth + ')');
        $active.addClass('active');
        $active.find('a').focus();
        this.$el.prev().find('input').focus();
    },

    /**
     * Moves to the next the active element.
     */
    moveForward: function() {
        // check to make sure we will be in bounds.
        this.activeIndex++;
        if (this.activeIndex < this.results.length) {
            // We're in bounds, just go to the next element in this view.
            this._highlightActive();
        } else {
            this.activeIndex = 0;
            this._highlightActive();
        }
    },

    /**
     * Moves to the previous the active element.
     */
    moveBackward: function() {
        // check to make sure we will be in bounds.
        if (this.activeIndex > 0) {
            // We're in bounds, just go to the previous element in this view
            this.activeIndex--;
            this._highlightActive();
        } else {
            this.activeIndex = this.results.length - 1;
            this._highlightActive();
        }
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        $(window).off('keydown.' + this.cid);
        this._super('_dispose');
    }
})
