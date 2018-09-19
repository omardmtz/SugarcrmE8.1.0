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
 * Layout displays a list of duplicate records found along with a count
 *
 * Note: Next step will be to add ability to switch to a filter list (and back).
 *       This is why this is in a layout.
 *
 * @class View.Layouts.Base.DupecheckLayout
 * @alias SUGAR.App.view.layouts.BaseDupecheckLayout
 * @extends View.Layout
 */
({
    initialize: function(options) {
        if(options.context.has('dupelisttype')) {
            options.meta = this.switchListView(options.meta, options.context.get('dupelisttype'));
        }
        app.view.Layout.prototype.initialize.call(this, options);
    },

    switchListView: function(meta, dupelisttype) {
        var listView = _.find(meta.components, function(component) {
            return (component.name === 'dupecheck-list');
        });
        listView.view = dupelisttype;
        return meta;
    }
})
