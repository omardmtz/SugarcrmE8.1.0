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
 * Unlink row action used in subpanels and dashlets.
 *
 * @class View.Fields.Base.VcardField
 * @alias SUGAR.App.view.fields.BaseVcardField
 * @extends View.Fields.Base.RowactionField
 */
({
    extendsFrom: 'RowactionField',

    initialize: function(options) {
        this._super("initialize", [options]);
        this.type = 'rowaction';
    },

    /**
     * Downloads the vCard from the Rest API.
     *
     * First we do an ajax call to the `ping` API. This will check if the token
     * hasn't expired before we append it to the URL of the VCardDownload.
     *
     */
    rowActionSelect: function() {
        var url = app.api.buildURL(this.model.module, 'vcard', {id: this.model.id}, {platform: app.config.platform});

        if (_.isEmpty(url)) {
            app.logger.error('Unable to get the vCard download uri.');
            return;
        }

        app.api.fileDownload(url, {
            error: function(data) {
                // refresh token if it has expired
                app.error.handleHttpError(data, {});
            }
        }, {iframe: this.$el});
    },

    bindDataChange: function() {
        if (this.model) {
            this.model.on('change', this.render, this);
        }
    }
})
