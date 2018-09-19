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
    extendsFrom: 'ProfileactionsView',

    plugins: ['Dropdown'],

    /**
     * The selector for the tab that contains profile actions.
     *
     * @type {String}
     */
    _profileActionsTag: '[data-menu="user-actions"]',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        var self = this;
        this._super('initialize', [options]);
        app.events.on('app:view:change', function(layout, params){
            if (params.module === 'Contacts') {
                self.$(self._profileActionsTag).addClass('active');
            } else {
                self.$(self._profileActionsTag).removeClass('active');
            }
        });
    },

    /**
     * @inheritdoc
     */
    _renderHtml: function(){
        this._super('_renderHtml');
        if (app.controller.context.get('module') === 'Contacts') {
            this.$(this._profileActionsTag).addClass('active');
        }
    },

    /**
     * Sets the current user's information like full name, user name, avatar, etc.,
     * using portal's user module which is currently the Contacts module.
     * @protected
     */
    setCurrentUserData: function() {
        this.fullName = app.user.get("full_name");
        this.userName = app.user.get("portal_name");
        var picture = app.user.get("picture");
        this.pictureUrl = picture ? app.api.buildFileURL({
            module: "Contacts",
            id: app.user.get("id"),
            field: "picture"
        }) : '';
        this.render();
    },

    /**
     * Filters single menu data
     * @param Array menu data
     * @return {Array}
     */
    filterMenuProperties:function(singleItem){
        if(singleItem['label'] === 'LBL_PROFILE'){
            singleItem['img_url'] = this.pictureUrl;
        }
        return singleItem;
    }
})
