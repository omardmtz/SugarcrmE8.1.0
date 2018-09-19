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
 * @class View.Fields.Base.ForecastsWorksheets.CurrencyField
 * @alias SUGAR.App.view.fields.BaseForecastsWorksheetsCurrencyField
 * @extends View.Fields.Base.CurrencyField
 */
({
    extendsFrom: 'CurrencyField',

    initialize: function(options) {
        // we need to make a clone of the plugins and then push to the new object. this prevents double plugin
        // registration across ExtendedComponents
        this.plugins = _.clone(this.plugins) || [];
        this.plugins.push('ClickToEdit');
        this._super("initialize", [options]);
    }
})
