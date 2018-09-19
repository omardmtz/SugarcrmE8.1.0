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
 * @class View.Views.Base.Products.RecordView
 * @alias SUGAR.App.view.views.BaseProductsRecordView
 * @extends View.Views.Base.RecordView
 */
({
    extendsFrom: 'BaseRecordView',

    /**
     * @inheritdoc
     */
    delegateButtonEvents: function() {
        this.context.on('button:convert_to_quote:click', this.convertToQuote, this);
        this.context.on('editable:record:toggleEdit', this._toggleRecordEdit, this);

        this._super('delegateButtonEvents');
    },

    /**
     * @inheritdoc
     */
    _toggleRecordEdit: function() {
        this.setButtonStates(this.STATE.EDIT);
    },

    /**
     * @inheritdoc
     */
    cancelClicked: function() {
        this.context.trigger('record:cancel:clicked');
        this._super('cancelClicked');
    }
})
