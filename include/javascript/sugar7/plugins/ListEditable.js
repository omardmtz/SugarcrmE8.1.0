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
(function(app) {
    app.events.on('app:init', function() {
        /**
         * ListEditable plugin is for fields that use a list-edit template instead of the standard edit
         * during inline editing on list views
         */
        app.plugins.register('ListEditable', ['field'], {
            _loadTemplate: function() {
                //Invoke the original method first
                Object.getPrototypeOf(this)._loadTemplate.call(this);
                if (this.view.action === 'list' && _.contains(['edit', 'disabled'], this.tplName)) {
                    var tplName = 'list-' + this.tplName;
                    this.template = app.template.getField(this.type, tplName, this.module, this.tplName) ||
                        app.template.empty;
                    this.tplName = tplName;
                }
            }
        });
    })
})(SUGAR.App);
