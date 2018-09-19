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
 * Forward action.
 *
 * This allows a user to "forward" an existing email.
 *
 * @class View.Fields.Base.Emails.ForwardActionField
 * @alias SUGAR.App.view.fields.EmailsBaseForwardActionField
 * @extends View.Fields.Base.EmailactionField
 */
({
    extendsFrom: 'EmailactionField',

    /**
     * Template for forward header.
     *
     * @protected
     */
    _tplHeaderHtml: null,

    /**
     * The name of the template for forward header.
     *
     * @protected
     */
    _tplHeaderHtmlName: 'forward-header-html',

    /**
     * The prefix to apply to the subject.
     *
     * @protected
     */
    _subjectPrefix: 'LBL_FW',

    /**
     * The element ID to use to identify the forward content.
     *
     * The ID is added to the div wrapper around the content for later
     * identifying the portion of the email body which is the forward content
     * (e.g., when inserting templates into an email, but maintaining the
     * forward content).
     *
     * @protected
     */
    _contentId: 'forwardcontent',

    /**
     * @inheritdoc
     *
     * The forward content is built ahead of the button click to support the
     * option of doing a mailto link which needs to be built and set in the DOM
     * at render time.
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        // Use field templates from emailaction.
        this.type = 'emailaction';

        this.addEmailOptions({
            // If there is a default signature in email compose, it should be
            // placed above the forward content in the email body.
            signature_location: 'above',
            // Focus the editor and place the cursor at the beginning of all
            // content.
            cursor_location: 'above',
            // Prevent prepopulating the email with case data.
            skip_prepopulate_with_case: true
        });
    },

    /**
     * Returns the subject to use in the email.
     *
     * Any instances of "Re: ", "FW: ", and "FWD: " (case-insensitive) found at
     * the beginning of the subject are removed prior to applying the prefix.
     *
     * @see EmailClientLaunch plugin.
     * @param {Data.Bean} model Use this model when constructing the subject.
     * @return {undefined|string}
     */
    emailOptionSubject: function(model) {
        var pattern = /^((?:re|fw|fwd): *)*/i;
        var subject = model.get('name') || '';

        return app.lang.get(this._subjectPrefix, model.module) + ': ' + subject.replace(pattern, '');
    },

    /**
     * Returns the plain-text body to use in the email.
     *
     * @see EmailClientLaunch plugin.
     * @param {Data.Bean} model Use this model when constructing the body.
     * @return {undefined|string}
     */
    emailOptionDescription: function(model) {
        var headerParams;
        var header;
        var body;
        var description;

        if (!this.useSugarEmailClient()) {
            headerParams = this._getHeaderParams(model);
            header = this._getHeader(headerParams);
            body = model.get('description') || '';
            description = '\n' + header + '\n' + body;
        }

        return description;
    },

    /**
     * Returns the HTML body to use in the email.
     *
     * Ensure the result is a defined string and strip any signature wrapper
     * tags to ensure it doesn't get stripped if we insert a signature above
     * the forward content. Also strip any reply content class if this is a
     * forward to a previous reply. And strip any forward content class if this
     * is a forward to a previous forward.
     *
     * @see EmailClientLaunch plugin.
     * @param {Data.Bean} model Use this model when constructing the body.
     * @return {undefined|string}
     */
    emailOptionDescriptionHtml: function(model) {
        var tplHeaderHtml = this._getHeaderHtmlTemplate();
        var headerParams = this._getHeaderParams(model);
        var headerHtml = tplHeaderHtml(headerParams);
        var body = model.get('description_html') || '';

        body = body.replace('<div class="signature">', '<div>');
        body = body.replace('<div id="replycontent">', '<div>');
        body = body.replace('<div id="forwardcontent">', '<div>');

        return '<div></div><div id="' + this._contentId + '">' + headerHtml + body + '</div>';
    },

    /**
     * Returns the attachments to use in the email.
     *
     * @see EmailClientLaunch plugin.
     * @param {Data.Bean} model Use this model when building the attachments.
     * @return {undefined|Array}
     */
    emailOptionAttachments: function(model) {
        return model.get('attachments_collection').map(function(attachment) {
            var filename = attachment.get('filename') || attachment.get('name');

            return {
                _link: 'attachments',
                upload_id: attachment.get('upload_id') || attachment.get('id'),
                name: filename,
                filename: filename,
                file_mime_type: attachment.get('file_mime_type'),
                file_size: attachment.get('file_size'),
                file_ext: attachment.get('file_ext')
            };
        });
    },

    /**
     * Returns the bean to use as the email's related record.
     *
     * @see EmailClientLaunch plugin.
     * @param {Data.Bean} model This model's parent is used as the email's
     * related record.
     * @return {undefined|Data.Bean}
     */
    emailOptionRelated: function(model) {
        var parent;

        if (model.get('parent') && model.get('parent').type && model.get('parent').id) {
            // We omit type because it is actually the module name and should
            // not be treated as an attribute.
            parent = app.data.createBean(model.get('parent').type, _.omit(model.get('parent'), 'type'));
        } else if (model.get('parent_type') && model.get('parent_id')) {
            parent = app.data.createBean(model.get('parent_type'), {
                id: model.get('parent_id'),
                name: model.get('parent_name')
            });
        }

        return parent;
    },

    /**
     * Returns the teamset array to seed the email's teams.
     *
     * @see EmailClientLaunch plugin.
     * @param {Data.Bean} model This model's teams is used as the email's
     * teams.
     * @return {undefined|Array}
     */
    emailOptionTeams: function(model) {
        return model.get('team_name');
    },

    /**
     * Build the header for text only emails.
     *
     * @param {Object} params
     * @param {string} params.from
     * @param {string} [params.date] Date original email was sent
     * @param {string} params.to
     * @param {string} [params.cc]
     * @param {string} params.name The subject of the original email.
     * @return {string}
     * @private
     */
    _getHeader: function(params) {
        var header = '-----\n' + app.lang.get('LBL_FROM', params.module) + ': ' + (params.from || '') + '\n';
        var date;

        if (params.date) {
            date = app.date(params.date).formatUser();
            header += app.lang.get('LBL_DATE', params.module) + ': ' + date + '\n';
        }

        header += app.lang.get('LBL_TO_ADDRS', params.module) + ': ' + (params.to || '') + '\n';

        if (params.cc) {
            header += app.lang.get('LBL_CC', params.module) + ': ' + params.cc + '\n';
        }

        header += app.lang.get('LBL_SUBJECT', params.module) + ': ' + (params.name || '') + '\n';

        return header;
    },

    /**
     * Returns the template for producing the header HTML for the top of the
     * forward content.
     *
     * @return {Function}
     * @private
     */
    _getHeaderHtmlTemplate: function() {
        // Use `this.def.type` because `this.type` was changed to `emailaction`
        // during initialization.
        this._tplHeaderHtml = this._tplHeaderHtml ||
            app.template.getField(this.def.type, this._tplHeaderHtmlName, this.module);
        return this._tplHeaderHtml;
    },

    /**
     * Get the data required by the header template.
     *
     * @param {Data.Bean} model The params come from this model's attributes.
     * EmailClientLaunch plugin should dictate the model based on the context.
     * @return {Object}
     * @protected
     */
    _getHeaderParams: function(model) {
        return {
            module: model.module,
            from: this._formatEmailList(model.get('from_collection')),
            date: model.get('date_sent'),
            to: this._formatEmailList(model.get('to_collection')),
            cc: this._formatEmailList(model.get('cc_collection')),
            name: model.get('name')
        };
    },

    /**
     * Given a list of people, format a text only list for use in a forward
     * header.
     *
     * @param {Data.BeanCollection} collection A list of models
     * @protected
     */
    _formatEmailList: function(collection) {
        return collection.map(function(model) {
            return model.toHeaderString();
        }).join(', ');
    }
})
