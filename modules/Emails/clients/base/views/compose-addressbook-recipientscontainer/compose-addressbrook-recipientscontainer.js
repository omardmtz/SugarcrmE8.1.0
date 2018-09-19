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
 * @class View.Views.Base.Emails.ComposeAddressbookRecipientscontainerView
 * @alias SUGAR.App.view.views.BaseEmailsComposeAddressbookRecipientscontainerView
 * @extends View.Views.Base.RecordView
 */
({
    extendsFrom: 'RecordView',
    enableHeaderButtons: false,
    enableHeaderPane: false,
    events: {},

    /**
     * Override to remove unwanted functionality.
     *
     * @param prefill
     */
    setupDuplicateFields: function(prefill) {},

    /**
     * Override to remove unwanted functionality.
     */
    delegateButtonEvents: function() {},

    /**
     * Override to remove unwanted functionality.
     */
    _initButtons: function() {
        this.buttons = {};
    },

    /**
     * Override to remove unwanted functionality.
     */
    showPreviousNextBtnGroup: function() {},

    /**
     * Override to remove unwanted functionality.
     */
    bindDataChange: function() {},

    /**
     * Override to remove unwanted functionality.
     *
     * @param isEdit
     */
    toggleHeaderLabels: function(isEdit) {},

    /**
     * Override to remove unwanted functionality.
     *
     * @param field
     */
    toggleLabelByField: function(field) {},

    /**
     * Override to remove unwanted functionality.
     *
     * @param e
     * @param field
     */
    handleKeyDown: function(e, field) {},

    /**
     * Override to remove unwanted functionality.
     *
     * @param state
     */
    setButtonStates: function(state) {},

    /**
     * Override to remove unwanted functionality.
     *
     * @param title
     */
    setTitle: function(title) {}
})
