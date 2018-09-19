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
    className: 'businessrules',

    /**
     * @inheritdoc
     */
    loadData: function (options) {
        this.br_uid = this.options.context.attributes.modelId;
    },

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        app.view.View.prototype.initialize.call(this, options);
        this.context.off("businessRules:save:finish", null, this);
        this.context.on("businessRules:save:finish", this.saveBusinessRules, this);

        this.context.off("businessRules:save:save", null, this);
        this.context.on("businessRules:save:save", this.saveOnlyBusinessRules, this);

        this.context.off("businessRules:cancel:button", null, this);
        this.context.on("businessRules:cancel:button", this.cancelBusinessRules, this);

        this.myDefaultLayout = this.closestComponent('sidebar');
        app.routing.before('route', _.bind(this.beforeRouteChange, this), this, true);
        this._currentUrl = Backbone.history.getFragment();

        this._decisionTable = null;
        this._brName = null;
        this._brModule = null;
    },

    /**
     * Updates the Business Rules decision table header text.
     * @param name
     * @param module
     * @private
     */
    _updateBRHeader: function (name, module) {
        this.$('.brTitle').text(name);
        var brModule = app.lang.get('LBL_RST_MODULE', this.module) + ': ' + module;
        this.$('.brModule').text(brModule);
    },

    /**
     * Creates the Business Rules decision table
     * @param data
     * @private
     */
    _addDecisionTable: function (data) {
        var module = 'pmse_Business_Rules';
        var pmseCurrencies = [];
        var currencies = App.metadata.getCurrencies();
        var that = this;

        for (currID in currencies) {
            if (currencies.hasOwnProperty(currID)) {
                if (currencies[currID].status === 'Active') {
                    pmseCurrencies.push({
                        id: currID,
                        iso: currencies[currID].iso4217,
                        name: currencies[currID].name,
                        rate: parseFloat(currencies[currID].conversion_rate),
                        preferred: currID === App.user.getCurrency().currency_id,
                        symbol: currencies[currID].symbol
                    });
                }
            }
        }

        $.extend(true, data, {
            dateFormat: App.date.getUserDateFormat(),
            timeFormat: App.user.getPreference("timepref"),
            currencies: pmseCurrencies
        });

        this._decisionTable = new DecisionTable(data);

        if (!this._decisionTable.correctlyBuilt) {
            this.$('#save').hide();
        }

        this._decisionTable.onDirty = function (state) {
            if (state) {
                updateName = that._brName + " *";
            } else {
                updateName = that._brName;
            }
            that.$(".brTitle").text(updateName);
        };

        this._decisionTable.onAddColumn =
            this._decisionTable.onAddRow = this._decisionTable.onRemoveColumn = this._decisionTable.onRemoveRow;

        this.$('#businessruledesigner').prepend(this._decisionTable.getHTML());
    },

    /**
     * Initialize the Business Rules decision table.
     * @param params
     * @private
     */
    _initDecisionTable: function (params) {
        var data;

        this._brName = params.data.name;
        this._brModule = App.lang.getModuleName(params.data.rst_module, {plural: true});

        //errorLog = $('#error-log');

        if (params.data && params.data.rst_source_definition) {
            data = JSON.parse(params.data.rst_source_definition);
        } else {
            data = {
                "saveedit":"1",
                "btnSubmitEdit":"Save and Edit",
                "id":params.data.id,
                "name":params.data.name,
                "base_module":params.data.rst_module,
                "type":"single",
                "columns":{
                    "conditions":[],
                    "conclusions":[]
                },
                "ruleset":[
                    {
                        "conditions":[],
                        "conclusions":[]
                    }
                ]
            }
        }
        this._updateBRHeader(this._brName, this._brModule);
        this._addDecisionTable(data);
        this._decisionTable.setIsDirty(false);
    },

    /**
     * @inheritdoc
     */
    render: function () {
        var that = this;
        app.view.View.prototype.render.call(this);

        var params = {
            br_uid: this.br_uid
        };
        App.api.call("read", App.api.buildURL("pmse_Business_Rules", null, {id: this.br_uid }), {}, {
            success: function (response) {
                params.data = response;
                that._initDecisionTable(params);
            }
        });
    },

    /**
     * Saves the Buiness Rules decision table data.
     * @param route
     * @param id
     */
    _saveBR: function (id, route) {
        var json,
            base64encoded,
            url,
            validation = this._decisionTable.isValid(),
            that = this;

        if (this._decisionTable && validation.valid) {
            json = this._decisionTable.getJSON();
            base64encoded = JSON.stringify(json);
            url = App.api.buildURL('pmse_Business_Rules', null, {id: id});
            attributes = {rst_source_definition: base64encoded};

            App.alert.show('upload', {level: 'process', title: 'LBL_SAVING', autoclose: false});

            App.api.call('update', url, attributes, {
                success: function (data) {
                    App.alert.dismiss('upload');
                    App.alert.show('save-success', {
                        level: 'success',
                        messages: App.lang.get('LBL_SAVED'),
                        autoClose: true
                    });
                    if (route) {
                        that._decisionTable.setIsDirty(false, true);
                        App.router.navigate(route, {trigger: true});
                    } else {
                        that._decisionTable.setIsDirty(false);
                    }
                },
                error: function (err) {
                    App.alert.dismiss('upload');
                }
            });
        } else {
            App.alert.show('br-save-error', {
                level: 'error',
                messages: validation.location + ": " + validation.message,
                autoClose: true
            });
        }
    },

    /**
     * Handler for the 'businessRules:save:finish' event.
     */
    saveBusinessRules: function() {
        this._saveBR(this.model.id, App.router.buildRoute("pmse_Business_Rules"));
    },

    /**
     * Handler for the 'businessRules:save:save'
     */
    saveOnlyBusinessRules: function() {
        this._saveBR(this.model.id);
    },

    /**
     * Handler for the 'businessRules:cancel:button' event.
     */
    cancelBusinessRules: function () {
        app.router.navigate('pmse_Business_Rules', {trigger: true});
    },

    /**
     * @inheritdoc
     * @returns {boolean}
     */
    beforeRouteChange: function () {
        var targetUrl = Backbone.history.getFragment(), that = this;
        if (this._decisionTable.getIsDirty()) {
            //Replace the url hash back to the current staying page
            app.router.navigate(this._currentUrl, {trigger: false, replace: true});
            app.alert.show('leave_confirmation', {
                level: 'confirmation',
                messages: app.lang.get('LBL_WARN_UNSAVED_CHANGES', this.module),
                onConfirm: function () {
                    that._decisionTable.setIsDirty(false, true);
                    app.router.navigate(targetUrl , {trigger: true, replace: true });
                },
                onCancel: $.noop
            });
            return false;
        }
        return true;
    },

    /**
     * @inheritdoc
     */
    _dispose: function () {
        this._super('_dispose', arguments);
    }
})

