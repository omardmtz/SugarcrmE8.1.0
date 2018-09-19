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

({
    plugins: ['Dashlet'],

    events: {
        'click [data-action=show-more]': 'loadMoreData'
    },

    /**
     * @inheritdoc
     *
     * @property {number} _defaultSettings.limit Maximum number of records to
     *   load per request, defaults to '5'.
     */
    _defaultSettings: {
        limit: 5
    },

    /**
     * KBContents bean collection.
     *
     * @property {Data.BeanCollection}
     */
    collection: null,

    /**
     * @inheritdoc
     *
     * Init collection.
     */
    initDashlet: function () {
        this._initSettings();
        this._initCollection();
    },

    /**
     * Sets up settings, starting with defaults.
     *
     * @return {View.Views.BaseRelatedDocumentsView} Instance of this view.
     * @protected
     */
    _initSettings: function () {
        this.settings.set(
            _.extend(
                {},
                this._defaultSettings,
                this.settings.attributes
            )
        );
        return this;
    },

    /**
     * Initialize feature collection.
     */
    _initCollection: function () {
        this.collection = app.data.createBeanCollection(this.module);
        this.context.set('collection', this.collection);
        return this;
    },

    /**
     * @inheritdoc
     *
     * Once collection has been changed, the view should be refreshed.
     */
    bindDataChange: function () {
        if (this.collection) {
            this.collection.on('add remove reset', function () {
                if (this.disposed) {
                    return;
                }
                this.render();
            }, this);
        }
    },

    /**
     * Load more data (paginate).
     */
    loadMoreData: function () {
        if (this.collection.next_offset > 0) {
            this.collection.paginate({add: true});
        }
    },

    /**
     * @inheritdoc
     */
    loadData: function (options) {
        if (this.collection.dataFetched) {
            return;
        }
        var currentContext = this.context.parent || this.context,
            model = currentContext.get('model');

        if (!model.get('kbdocument_id')) {
            model.once('sync', function() {this.loadData();}, this);
            return;
        }
        options = options || {};
        this.collection.setOption({
            limit: this.settings.get('limit'),
            fields: [
                'id',
                'name',
                'date_entered',
                'created_by',
                'created_by_name',
                'language'
            ],
            filter: {
                'kbdocument_id': {
                    '$equals': model.get('kbdocument_id')
                },
                'id' : {
                    '$not_equals': model.get('id')
                },
                'status': {
                    '$equals': 'published'
                },
                'active_rev': {
                    '$equals': 1
                }
            }
        });
        if (!options.error) {
            options.error = _.bind(function(collection, error) {
                if (error.code === 'not_authorized') {
                    this.$el.find('.block-footer').html(app.lang.get('LBL_NO_DATA_AVAILABLE', this.module));
                }
            }, this);
        }
        this.collection.fetch(options);
    }
})
