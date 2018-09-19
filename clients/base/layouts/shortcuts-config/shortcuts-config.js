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
 * @class View.Layouts.Base.ShortcutsConfigLayout
 * @alias SUGAR.App.view.layouts.BaseShortcutsConfigLayout
 * @extends View.Layout
 */
({
    plugins: ['ShortcutSession'],

    shortcuts: [
        'Headerpane:Cancel',
        'Headerpane:Save'
    ],

    /**
     * @inheritdoc
     */
    _placeComponent: function(component) {
        this.$('[data-action=render]').append(component.el);
    },

    /**
     * Do not fetch data.
     */
    loadData: $.noop
})
