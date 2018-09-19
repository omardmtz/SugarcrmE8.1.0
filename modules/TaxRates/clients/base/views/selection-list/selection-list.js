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
 * @class View.Views.Base.TaxRates.SelectionListView
 * @alias SUGAR.App.view.views.BaseTaxRatesSelectionListView
 * @extends View.Views.Base.SelectionListView
 */
({
    extendsFrom: 'SelectionListView',

    /**
     * Extending to add the value into the attributes passed back
     *
     * @inheritdoc
     */
    _getModelAttributes: function(model) {
        var attributes = {
            id: model.id,
            name: model.get('name'),
            value: model.get('value')
        };

        //only pass attributes if the user has view access
        _.each(model.attributes, function(value, field) {
            if (app.acl.hasAccessToModel('view', model, field)) {
                attributes[field] = attributes[field] || model.get(field);
            }
        }, this);

        return attributes;
    }
})
