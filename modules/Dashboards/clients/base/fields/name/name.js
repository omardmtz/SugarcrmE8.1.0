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
 * @class View.Fields.Base.DashboardsNameField
 * @alias App.view.fields.BaseDashboardsNameField
 * @extends View.Fields.Base.NameField
 */
({
    /**
     * Formats the value to be used in handlebars template and displayed on
     * screen. We are overriding this method to translate labels in the name
     * field within the Dashboard module.
     * @override
     */
    format: function(value) {
        return app.lang.get(value, this.model.get('dashboard_module'));
    }
})
