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
 * @class View.Fields.Base.CollectionCountField
 * @alias SUGAR.App.view.fields.BaseCollectionCountField
 * @extends View.Fields.Base.BaseField
 */
({
    events: {
        'click [data-action="count"]': 'fetchCount'
    },

    /**
     * Fetches the total amount of filtered records from the collection, and
     * renders the field to show the new (or cached) total.
     */
    fetchCount: function() {
        if (_.isNull(this.collection.total)) {
            app.alert.show('fetch_count', {
                level: 'process',
                title: app.lang.get('LBL_LOADING'),
                autoClose: false
            });
        }

        this.collection.fetchTotal({
            success: _.bind(function() {
                if (!this.disposed) {
                    this.updateCount();
                }
            }, this),
            complete: function() {
                app.alert.dismiss('fetch_count');
            }
        });
    },

    /**
     * Updates {@link #countLabel the count label} and renders this field.
     *
     * @param {number} [options] Optional hash of values to use for the `length`
     *   and `hasMore` properties. Use this if you want to customize what this
     *   field should display.
     * @param {number} [options.length] The length of values.
     * @param {boolean} [options.hasMore] `true` if there are more values to be
     *   fetched or paginated, `false` if we've fetched everything.
     */
    updateCount: function(options) {
        this._setCountLabel(options);
        this.render();
    },

    /**
     * Returns the label for the count in the headerpane.
     *
     * If you would like to customize these, the following labels are being
     * used: `TPL_LIST_HEADER_COUNT`, `TPL_LIST_HEADER_COUNT_PARTIAL`,
     * `TPL_LIST_HEADER_COUNT_TOTAL`, and `TPL_LIST_HEADER_COUNT_TOOLTIP`.
     *
     * There are several ways the total count label is represented, depending on
     * the state of `this.collection`. If the collection contains all the
     * records, the label will display `this.collection.length`, for example:
     *
     *     (17)
     *
     * If `this.collection.total` exists and is cached, the label will display
     * in the form:
     *
     *     (20 of 50)
     *
     * Otherwise, the returned label will include the link to fetch the total:
     *
     *     (20 of <a data-action="count">21+</a>)
     *
     * @protected
     * @param {number} [options] Optional hash of values to use for the `length`
     *   and `hasMore` properties. Use this if you want to customize what this
     *   field should display.
     * @param {number} [options.length] The length of values. Defaults to
     *   `this.collection.length`.
     * @param {boolean} [options.hasMore] `true` if there are more values to be
     *   fetched or paginated, `false` if we've fetched everything. Defaults to
     *   `false`.
     * @return {string|Handlebars.SafeString} The label to use for the list view
     *   count.
     */
    _setCountLabel: function(options) {
        // Default properties.
        options = options || {};
        var length = this.collection.length;
        var fullyFetched = this.collection.next_offset <= 0;
        // Override default properties with passed-in values.
        length = !_.isUndefined(options.length) ? options.length : length;
        fullyFetched = !_.isUndefined(options.hasMore) ? !options.hasMore : fullyFetched;

        if (!length && !this.collection.dataFetched) {
            return this.countLabel = '';
        }

        var tplKey = 'TPL_LIST_HEADER_COUNT_TOTAL';
        var context = {num: length};

        if (fullyFetched) {
            tplKey = 'TPL_LIST_HEADER_COUNT';
        } else if (!_.isNull(this.collection.total)) {
            context.total = this.collection.total;
        } else {
            var tooltipLabel = app.lang.get('TPL_LIST_HEADER_COUNT_TOOLTIP', this.module);
            // FIXME: When SC-3681 is ready, we will no longer have the need for
            // this link, since the total will be displayed by default.
            context.total = new Handlebars.SafeString(
                '<a href="javascript:void(0);" data-action="count" rel="tooltip" data-placement="right" title="' + tooltipLabel + '" role="button" tabindex="0">' +
                Handlebars.Utils.escapeExpression(
                    app.lang.get('TPL_LIST_HEADER_COUNT_PARTIAL', this.module, {num: context.num + 1})
                ) + '</a>'
            );
        }

        // FIXME: When SC-3681 is ready, remove the SafeString call.
        return this.countLabel = new Handlebars.SafeString(app.lang.get(tplKey, this.module, context));
    },

    /**
     * @override
     *
     * Re-renders the field when the attached collection is `reset`. Also
     * handles executing a request for the total count when a `pagination` event
     * occurs on the context. We do this on `pagination` because it is a
     * user-initiated action - if we request the count on `reset` as well it
     * would decrease performance.
     */
    bindDataChange: function() {
        if (!this.collection) {
            return;
        }

        this.listenTo(this.collection, 'remove reset', function() {
            if (!this.disposed) {
                this.updateCount();
            }
        });
        this.listenTo(this.context, 'paginate', function() {
            if (!this.disposed) {
                this.fetchCount();
            }
        });
        this.listenTo(this.context, 'refresh:count', function(hasAmount, properties) {
            if (!this.disposed) {
                this.updateCount(properties);
            }
        });
    }
})
