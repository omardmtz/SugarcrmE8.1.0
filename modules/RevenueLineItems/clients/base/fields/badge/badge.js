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
 * @class View.Fields.Base.RevenueLineItems.BadgeField
 * @alias SUGAR.App.view.fields.BaseRevenueLineItemsBadgeField
 * @extends View.Fields.Base.RowactionField
 */
({
    /**
     * @inheritdoc
     */
    extendsFrom: 'RowactionField',

    /**
     * @inheritdoc
     */
    showNoData: false,

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this.model.on('change:' + this.name, this.render, this);
    }
});
