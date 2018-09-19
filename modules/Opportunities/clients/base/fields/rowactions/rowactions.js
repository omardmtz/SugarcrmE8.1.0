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
/*
 * @class View.Fields.Base.Opportunities.RowactionsField
 * @alias SUGAR.App.view.fields.BaseOpportunitiesRowactionsField
 * @extends View.Fields.Base.RowactionsField
 */
({
    extendsFrom: 'RowactionsField',

    /**
     * Enable or disable caret depending on if there are any enabled actions in the dropdown list
     *
     * @inheritdoc
     * @private
     */
    _updateCaret: function() {
        // Left empty on purpose, the menu should always show
    }
})
