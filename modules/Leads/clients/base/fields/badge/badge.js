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
 * @class View.Fields.Base.Leads.BadgeField
 * @alias SUGAR.App.view.fields.BaseLeadsBadgeField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * @inheritdoc
     *
     * This field doesn't support `showNoData`.
     */
    showNoData: false,

    events: {
        'click [data-action=convert]': 'convertLead'
    },

    /**
     * @inheritdoc
     *
     * The badge is always a readonly field.
     */
    initialize: function(options) {
        options.def.readonly = true;
        app.view.Field.prototype.initialize.call(this, options);
    },

    /**
     * Kick off convert lead process.
     */
    convertLead: function() {
        var model = app.data.createBean(this.model.module);
        model.set(app.utils.deepCopy(this.model.attributes));

        app.drawer.open({
            layout : 'convert',
            context: {
                forceNew: true,
                skipFetch: true,
                module: this.model.module,
                leadsModel: model
            }
        });
    }
})
