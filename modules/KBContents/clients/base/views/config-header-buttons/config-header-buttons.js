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
 * @class View.Views.Base.SchedulersJobsConfigHeaderButtonsView
 * @alias SUGAR.App.view.layouts.BaseSchedulersJobsConfigHeaderButtonsView
 * @extends View.Views.Base.ConfigHeaderButtonsView
 */
({
    extendsFrom: 'ConfigHeaderButtonsView',

    /**
     * Saves the config model
     *
     * Also calling doValidate to check that there is no Language duplication
     *
     * @private
     */
    _saveConfig: function() {
        var self = this,
            model = this.context.get('model');

        // Standard ConfigHeaderButtonsView doesn't use doValidate
        model.doValidate(null, function(isValid) {
            if (isValid) {
                self._super('_saveConfig');
            } else {
                self.getField('save_button').setDisabled(false);
            }
        });
    }
})
