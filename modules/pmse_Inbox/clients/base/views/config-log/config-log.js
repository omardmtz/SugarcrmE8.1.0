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
    /**
     * @inheritdoc
     *
     * Sets up the file field to edit mode
     *
     * @param {View.Field} field
     * @private
     */
    _renderField: function(field) {
        app.view.View.prototype._renderField.call(this, field);
        app.alert.show('txtConfigLog', {level: 'process', title: 'Loading', autoclose: false});
        url = app.api.buildURL(this.module + '/logGetConfig');
        app.api.call('READ', url, {},{
            success: function(data)
            {
                field.model.set('comboLogConfig',data['records'][0]['cfg_value']);
                app.alert.dismiss('txtConfigLog');
            }
        });
    }
})