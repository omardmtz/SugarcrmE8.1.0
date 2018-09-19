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
 * @class View.Fields.Base.Leads.ConvertbuttonField
 * @alias SUGAR.App.view.fields.BaseLeadsConvertbuttonField
 * @extends View.Fields.Base.RowactionField
 */
({
    extendsFrom: 'RowactionField',

    initialize: function (options) {
        this._super("initialize", [options]);
        this.type = 'rowaction';
    },

    _render: function () {
        var convertMeta = app.metadata.getLayout('Leads', 'convert-main');
        var missingRequiredAccess = _.some(convertMeta.modules, function (moduleMeta) {
            return (moduleMeta.required === true && !app.acl.hasAccess('create', moduleMeta.module));
        }, this);

        if (this.model.get('converted') || missingRequiredAccess) {
            this.hide();
        } else {
            this._super("_render");
        }
    },

    /**
     * Event to trigger the convert lead process for the lead
     */
    rowActionSelect: function() {
        var model = app.data.createBean(this.model.module);

        model.set(app.utils.deepCopy(this.model.attributes));
        app.drawer.open({
            layout : "convert",
            context: {
                forceNew: true,
                skipFetch: true,
                module: 'Leads',
                leadsModel: model
            }
        });
    },

    bindDataChange: function () {
        if (this.model) {
            this.model.on("change", this.render, this);
        }
    }
})
