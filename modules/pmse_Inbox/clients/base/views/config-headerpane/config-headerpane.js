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
    extendsFrom: "HeaderpaneView",

    events: {
        "click [name=save_button]":   "_save",
        "click [name=cancel_button]": "_cancel"
    },
    /**
     * Save the drawer.
     *
     * @private
     */
    _save: function() {
        var fieldPmse=new Object();
        fieldPmse.logger_level={name: "logger_level", required: true};
        fieldPmse.error_number_of_cycles={name: "error_number_of_cycles", required: true, type: 'int'};
        fieldPmse.error_timeout={name: "error_timeout", required: true, type: 'int'};
//        console.log('mmm',fieldPmse);
        this.model.doValidate(fieldPmse, _.bind(this.validationCompleteSettings, this));
    },
    validationCompleteSettings: function(isValid) {
        var self=this;
        if (isValid) {
            app.alert.show('upload', {level: 'process', title: 'LBL_LOADING', autoclose: false});
            var value = {}, data = {};
            data.logger_level = self.model.get('logger_level');
            data.error_number_of_cycles = self.model.get('error_number_of_cycles');
            data.error_timeout = self.model.get('error_timeout');
            value.data = data;
            //console.log('Values->',value);
            var pmseInboxUrl = app.api.buildURL('pmse_Inbox/settings','',{},{});
            app.api.call('update', pmseInboxUrl, value,{
                success: function (data){
                    if(data.success){
                        app.alert.dismiss('upload');
//                        app.router.goBack();
                        app.router.navigate("bwc/index.php?module=Administration&action=index",{trigger:true});
                    }
                }
            });

//            console.log('Validado');
        }
    },
    /**
     * Close the drawer.
     *
     * @private
     */
    _cancel: function() {
        app.router.navigate(app.router.buildRoute('Administration'), {trigger: true});
    }
})
