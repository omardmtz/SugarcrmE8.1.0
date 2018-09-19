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
 * @class View.Views.Base.PrefilterelistView
 * @alias SUGAR.App.view.views.BasePrefilteredlistView
 * @extends View.Views.Base.RecordlistView
 */
({
    extendsFrom: 'RecordlistView',

    /**
     * Load recordlist templates.
     * @inheritdoc
     */
    _loadTemplate: function(options) {
        this.tplName = 'recordlist';
        this.template = app.template.getView(this.tplName);
    }
})
