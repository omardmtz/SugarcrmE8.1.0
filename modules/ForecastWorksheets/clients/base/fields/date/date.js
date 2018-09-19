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
 * @class View.Fields.Base.ForecastsWorksheets.DateField
 * @alias SUGAR.App.view.fields.BaseForecastsWorksheetsDateField
 * @extends View.Fields.Base.DateField
 */
({
    extendsFrom: 'DateField',

    /**
     * @inheritdoc
     *
     * Add `ClickToEdit` plugin to the list of required plugins.
     */
    _initPlugins: function() {
        this._super('_initPlugins');

        this.plugins = _.union(this.plugins, [
            'ClickToEdit'
        ]);

        return this;
    }
})
