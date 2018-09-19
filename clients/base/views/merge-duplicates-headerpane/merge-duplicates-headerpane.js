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
 * View for merge duplicates header pane.
 *
 * @class View.Views.Base.MergeDuplicatesHeaderpaneView
 * @alias SUGAR.App.view.views.BaseMergeDuplicatesHeaderpaneView
 * @extends View.Views.Base.HeaderpaneView
 */
({
    extendsFrom: 'HeaderpaneView',

    events: {
        'click a[name=cancel_button]': 'cancel',
        'click a[name=save_button]': 'save'
    },

    /**
     * @inheritdoc
     *
     * Gets the selected duplicates from the context and defines the title based
     * on the number of selected records.
     */
    _formatTitle: function(title) {
        var records = this.context.get('selectedDuplicates');
        return app.lang.get(title, this.module, {mergeCount: records.length});
    },

    /**
     * Cancel and close the drawer.
     */
    cancel: function() {
        app.drawer.close();
    },

    /**
     * Save primary and delete other records.
     */
    save: function() {
        this.layout.trigger('mergeduplicates:save:fire');
    }
})
