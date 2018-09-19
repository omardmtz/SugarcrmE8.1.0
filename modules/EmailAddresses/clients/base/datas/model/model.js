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
 * @class Model.Datas.Base.EmailAddressesModel
 * @alias SUGAR.App.model.datas.BaseEmailAddressesModel
 * @extends Data.Bean
 */
({
    /**
     * @inheritdoc
     *
     * Defaults `opt_out` to the `new_email_addresses_opted_out` config.
     */
    initialize: function(attributes) {
        this._defaults = _.extend({}, this._defaults, {opt_out: app.config.newEmailAddressesOptedOut});
        app.Bean.prototype.initialize.call(this, attributes);
    }
})
