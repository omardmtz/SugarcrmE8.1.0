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
({
    extendsFrom: 'EnumField',

    loadEnumOptions: function(fetch, callback) {
        this._super('loadEnumOptions', [fetch, callback]);

        var startYear = this.options.def.startYear;

        _.each(this.items, function(value, key, list) {
            list[key] = list[key].replace("{{year}}", startYear++);
        }, this);
    }
})
