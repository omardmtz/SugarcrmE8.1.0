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
 * @class View.Views.Base.ListHeaderpaneView
 * @alias SUGAR.App.view.views.BaseListHeaderpaneView
 * @extends View.Views.Base.HeaderpaneView
 */
({
    extendsFrom: 'HeaderpaneView',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        // FIXME: SC-3594 will address having child views extending metadata
        // from its parent.
        options.meta = _.extend(
            {},
            app.metadata.getView(null, 'list-headerpane'),
            app.metadata.getView(options.module, 'list-headerpane'),
            options.meta
        );

        this._super('initialize', [options]);

        //shortcut keys
        app.shortcuts.register({
            id: 'List:Headerpane:Create',
            keys: 'a',
            component: this,
            description: 'LBL_SHORTCUT_CREATE_RECORD',
            handler: function() {
                var $createButton = this.$('a[name=create_button]');
                if ($createButton.is(':visible') && !$createButton.hasClass('disabled')) {
                    $createButton.get(0).click();
                }
            }
        });
    }
})
