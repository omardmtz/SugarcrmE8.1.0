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
 * @class View.Layouts.Base.DataPrivacy.RecordLayout
 * @alias SUGAR.App.view.layouts.BaseDataPrivacyRecordLayout
 * @extends View.Layouts.Base.RecordLayout
 */
({
    extendsFrom: 'RecordLayout',

    /**
     * @inheritdoc
     *
     * Adds handler for invoking Mark for Erasure view
     */
    initialize: function(options) {
        this._super('initialize', arguments);
        this.listenTo(this.context, 'mark-erasure:click', this.showMarkForEraseDrawer);
    },

    /**
     * Open a drawer to mark fields on the given model for erasure.
     *
     * @param {Data.Bean} modelForErase Model to mark fields on.
     */
    showMarkForEraseDrawer: function(modelForErase) {
        var context = this.context.getChildContext({
            name: 'Pii',
            model: app.data.createBean('Pii'),
            modelForErase: modelForErase,
            fetch: false
        });

        app.drawer.open({
            layout: 'mark-for-erasure',
            context: context
        });
    }
})
