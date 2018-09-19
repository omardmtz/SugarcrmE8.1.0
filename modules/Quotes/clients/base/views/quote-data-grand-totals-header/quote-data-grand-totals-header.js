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
 * @class View.Views.Base.Quotes.QuoteDataGrandTotalsHeaderView
 * @alias SUGAR.App.view.views.BaseQuotesQuoteDataGrandTotalsHeaderView
 * @extends View.Views.Base.View
 */
({
    /**
     * @inheritdoc
     */
    events: {
        'click [name="create_qli_button"]': '_onCreateQLIBtnClicked',
        'click [name="create_comment_button"]': '_onCreateCommentBtnClicked',
        'click [name="create_group_button"]': '_onCreateGroupBtnClicked'
    },

    /**
     * @inheritdoc
     */
    className: 'quote-data-grand-totals-header-wrapper quote-totals-row',

    /**
     * Handles when the create Quoted Line Item button is clicked
     *
     * @param {MouseEvent} evt The mouse click event
     * @private
     */
    _onCreateQLIBtnClicked: function(evt) {
        this.context.trigger('quotes:defaultGroup:create', 'qli');
    },

    /**
     * Handles when the create Comment button is clicked
     *
     * @param {MouseEvent} evt The mouse click event
     * @private
     */
    _onCreateCommentBtnClicked: function(evt) {
        this.context.trigger('quotes:defaultGroup:create', 'note');
    },

    /**
     * Handles when the create Group button is clicked
     *
     * @param {MouseEvent} evt The mouse click event
     * @private
     */
    _onCreateGroupBtnClicked: function(evt) {
        this.context.trigger('quotes:group:create');
    }
})
