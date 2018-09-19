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
 * Add support for changing application language when the Portal user's
 * preferred language changes.
 *
 * @class View.Views.Portal.Contacts.RecordView
 * @alias SUGAR.App.view.views.PortalContactsRecordView
 * @extends View.Views.Base.RecordView
 */
({
    extendsFrom: 'RecordView',
    /**
     * @override
     */
    bindDataChange: function(){
        this._super("bindDataChange");
        var model = this.context.get('model');
        model.on('sync', this._setPreferredLanguage, this);
    },
    /**
     * Update application language based on Portal user's preferred language
     * @private
     */
    _setPreferredLanguage: function(){
        var newLang = this.model.get("preferred_language");
        if(_.isString(newLang) && newLang !== app.lang.getLanguage()){
            app.alert.show('language', {level: 'warning', title: 'LBL_LOADING_LANGUAGE', autoclose: false});
            app.lang.setLanguage(newLang, function(){
                app.alert.dismiss('language');
            });

        }
    }
})
