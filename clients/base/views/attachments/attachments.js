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
 * Attachment dashlet displays Notes and Attachments records that is related to the LHS record.
 * The following items are configurable.
 *
 * - {Integer} limit Limit imposed to the number of records pulled.
 * - {Integer} auto_refresh How often (minutes) should refresh the data collection.
 *
 * @class View.Views.Base.AttachmentsView
 * @alias SUGAR.App.view.views.BaseAttachmentsView
 * @extends View.View
 */
({
    plugins: ['LinkedModel', 'Dashlet', 'Pagination'],
    events: {
        'click [data-event=create_button]': 'createRelatedNote',
        'click [data-event=select_button]': 'openSelectDrawer'
    },

    /**
     * Default options used when none are supplied through metadata.
     *
     * Supported options:
     * - timer: How often (minutes) should refresh the data collection.
     * - limit: Limit imposed to the number of records pulled.
     *
     * @property {Object}
     * @protected
     */
    _defaultOptions: {
        limit: 5,
        timer: 0
    },

    /**
     * @inheritdoc
     *
     * @param {String} viewName view name.
     */
    initDashlet: function(viewName) {
        this._initOptions();
        if (!this.meta.config && this.context.get('collection')) {
            this.context.set('skipFetch', false);
            this.context.set('limit', this.limit);
        }
        if (!this.meta.config && !this.meta.preview) {
            this.context.on('attachment:view:fire', this.previewRecord, this);
            this.on('attachment:unlinkrow:fire', this.unlinkClicked, this);
            if (this.timer > 0) {
                //disabled previous interval
                this._disableAutoRefresh();
                this._enableAutoRefresh(this.timer);
            }
        }
    },

    /**
     * Initialize options, default options are used when none are supplied
     * through metadata.
     *
     * @return {Backbone.View} Instance of this view.
     * @protected
     */
    _initOptions: function() {
        this.tbodyTag = 'ul[data-action="pagination-body"]';
        var options = _.extend(this._defaultOptions, this.settings.attributes || {});
        this.timer = parseInt(options['auto_refresh'], 10) * 60 * 1000;
        this.limit = options.limit;
        return this;
    },

    /**
     * Get the list of field names to render the dashlet correctly
     * @return {string[]} The list of fields we need to fetch
     * @override
     */
    getFieldNames: function() {
        // FIXME TY-920: we shouldn't have to override this per-dashlet
        return this.dashletConfig && this.dashletConfig.dashlets[0].fields || [];
    },

    /**
     * Disable activated refresh interval
     * @protected
     */
    _disableAutoRefresh: function() {
        if (this.timerId) {
            clearInterval(this.timerId);
            this.timerId = null;
        }
        return this;
    },

    /**
     * Activate auto refresh data fetch.
     *
     * @param {Integer} msec Interval time in milli seconds(msec > 0).
     * @protected
     */
    _enableAutoRefresh: function(msec) {
        if (msec <= 0) {
            app.logger.error('Invalid interval timer: ' + msec);
            return this;
        }

        if (!_.isEmpty(this.timerId)) {
            app.logger.error('Trying to enable an already enabled auto-refresh dashlet.');
            return this;
        }

        this.timerId = setInterval(_.bind(function() {
            this.context.resetLoadFlag();
            this.layout.loadData();
        }, this), msec);
        return this;
    },

    /**
     * Apply svg icon plugin.
     */
    applySvgIcon: function() {
        var self = this,
            svgIconTemplate = app.template.get('attachments.svg-icon', this.module) ||
                app.template.get('attachments.svg-icon');
        this.$('[data-mime]').each(function() {
            var mimeType = $(this).data('mime'),
                filetype = self.dashletConfig.supportedImageExtensions[mimeType] || self._getFileType(mimeType);
            $(this).attr('data-filetype', filetype).html(svgIconTemplate());
        });
    },

    /**
     * Convert file mime type to file format
     *
     * @param {String} mimeType file mime type.
     * @return {String} file type.
     * @private
     */
    _getFileType: function(mimeType) {
        var filetype = mimeType.substr(mimeType.lastIndexOf('/') + 1).toUpperCase();
        return filetype ? filetype : this.dashletConfig.defaultType.toUpperCase();
    },

    /**
     * @inheritdoc
     *
     * Once collection is reset, the view should be refreshed.
     */
    bindDataChange: function() {
        if (this.collection) {
            this.collection.on('reset', this.render, this);
        }
        this.on('render', this.applySvgIcon, this);
        this.on('linked-model:create', this._reloadData, this);
    },

    /**
     * Re-fetches the data for the context's collection.
     *
     * FIXME: This will be removed when SC-4775 is implemented.
     *
     * @private
     */
    _reloadData: function() {
        this._prepContextForLoad();
        this.context.reloadData();
    },

    /**
     * Choose the attachment from the existing module list
     */
    openSelectDrawer: function() {
        var parentModel = this.context.get('parentModel'),
            linkModule = this.context.get('module'),
            link = this.context.get('link'),
            self = this;

        app.drawer.open({
            layout: 'selection-list',
            context: {
                module: linkModule
            }
        }, function(model) {
            if (!model) {
                return;
            }
            var relatedModel = app.data.createRelatedBean(parentModel, model.id, link),
                options = {
                    // Show alerts for this request
                    showAlerts: true,
                    relate: true,
                    success: function() {
                        self.context.resetLoadFlag();
                        self._prepContextForLoad();
                        self.context.loadData();
                    },
                    error: function() {
                        app.alert.show('server-error', {
                            level: 'error',
                            messages: 'ERR_GENERIC_SERVER_ERROR'
                        });
                    }
                };
            relatedModel.save(null, options);
        });
    },

    /**
     * Create new attachment record
     */
    createRelatedNote: function() {
        var link =  this.context.get('link'),
            parentModel = this.context.get('parentModel');
        this.createRelatedRecord(app.data.getRelatedModule(parentModel.module, link), link);
    },

    /**
     * Fix the fields on the context to prevent conflict with list-bottom
     * Also set `skipFetch` on the context since it is needed before most loads
     * @private
     */
    _prepContextForLoad: function() {
        this.context.set('fields', this.getFieldNames());
        this.context.set('skipFetch', false);
    },

    /**
     * Unlinks (removes) the selected model from the list view's collection.
     *
     * We trigger reset after removing the model in order to update html as well.
     *
     * @param {Data.Bean} model Selected model.
     */
    unlinkClicked: function(model) {
        var self = this;
        var name = Handlebars.Utils.escapeExpression(app.utils.getRecordName(model)).trim();
        var context = app.lang.getModuleName(model.module).toLowerCase() + ' ' + name;
        app.alert.show(model.get('id') + ':unlink_confirmation', {
            level: 'confirmation',
            messages: app.utils.formatString(app.lang.get('NTC_UNLINK_CONFIRMATION_FORMATTED'), [context]),
            onConfirm: function() {
                model.destroy({
                    //Show alerts for this request
                    showAlerts: true,
                    relate: true,
                    success: function() {
                        if (self.disposed) {
                            return;
                        }
                        self.collection.remove(model);
                        self.render();
                    }
                });
            }
        });
    },

    /**
     * @inheritdoc
     *
     * Dispose the interval timer as well.
     */
    _dispose: function() {
        this._disableAutoRefresh();
        app.view.View.prototype._dispose.call(this);
    }
})
