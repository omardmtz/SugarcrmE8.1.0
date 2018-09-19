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
 * "Link" button used in selection list for linking existing records.
 *
 * @class View.Fields.Base.LinkButtonField
 * @alias SUGAR.App.view.fields.BaseLinkButtonField
 * @extends View.Fields.Base.RowactionField
 */
({
    extendsFrom: 'RowactionField',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.massCollection = this.context.get('mass_collection');
        if (!this.massCollection) {
            return;
        }

        this.listenTo(this.massCollection, 'add remove', function() {
            this.setDisabled(this.massCollection.length === 0);
        });

        if (this.massCollection.length === 0) {
            this.setDisabled(true);
        }
    },

    /**
     * @inheritdoc
     */
    _loadTemplate: function() {
        this.type = 'rowaction';
        this._super('_loadTemplate');
        this.type = 'link-button';
    },

    /**
     * @inheritdoc
     */
    unbind: function() {
        this.stopListening(this.massCollection);
        this._super('unbind');
    }
})
