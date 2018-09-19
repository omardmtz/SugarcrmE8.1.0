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
    extendsFrom: 'HeaderpaneView',

    events:{
        'click [name=project_finish_button]': 'initiateFinish',
        'click [name=project_save_button]': 'initiateSave',
        'click [name=project_cancel_button]': 'initiateCancel'
    },

    initiateFinish: function() {
        this.context.trigger('businessRules:save:finish');
    },
    initiateSave: function() {
        this.context.trigger('businessRules:save:save');
    },
    initiateCancel : function() {
        this.context.trigger('businessRules:cancel:button');
    }
})
