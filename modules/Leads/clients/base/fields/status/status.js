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
 * @class View.Fields.Base.Leads.StatusField
 * @alias SUGAR.App.view.fields.BaseLeadsStatusField
 * @extends View.Fields.Base.EnumField
 */
({
    extendsFrom: 'EnumField',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.type = 'enum';
    },

    /**
     * @inheritdoc
     *
     * Filter out the Converted option if the Lead is not already converted.
     */
    _filterOptions: function(options) {
        var status = this.model.get('status');
        var filteredOptions = this._super('_filterOptions', [options]);

        return (!_.isUndefined(status) && status !== 'Converted') ?
            _.omit(filteredOptions, 'Converted') :
            filteredOptions;
    }

})
