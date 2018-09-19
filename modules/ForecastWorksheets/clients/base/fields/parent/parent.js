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
 * @class View.Fields.Base.ForecastsWorksheets.ParentField
 * @alias SUGAR.App.view.fields.BaseForecastsWorksheetsParentField
 * @extends View.Fields.Base.ParentField
 */
({
    extendsFrom: 'ParentField',

    _render: function () {
        if(this.model.get('parent_deleted') == 1) {
            // set the value for use in the template
            this.deleted_value = this.model.get('name');
            // override the template to use the delete one
            this.options.viewName = 'deleted';
        }
        this._super("_render");
    }
})
