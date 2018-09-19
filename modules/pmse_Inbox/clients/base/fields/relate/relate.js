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
// jscs:disable jsDoc
/**
 * relate Widget.
 *
 * Extends from BaseRelateField widget
 *
 * @class View.Fields.Base.pmse_Inbox.RelateField
 * @alias SUGAR.App.view.fields.Basepmse_InboxRelateField
 * @extends View.Fields.Base.RelateField
 */
// jscs:anable jsDoc
({
    /**
     * Renders relate field
     */
    _render: function() {
        // a way to override viewName
        if (this.def.view) {
            this.options.viewName = this.def.view;
        }
        this._super('_render');
    }

});
