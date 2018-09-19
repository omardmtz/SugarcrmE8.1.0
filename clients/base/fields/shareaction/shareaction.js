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
 * Share row action.
 *
 * This allows an user to share a record that is currently mapped with this
 * field context.
 *
 * @class View.Fields.Base.ShareactionField
 * @alias SUGAR.App.view.fields.BaseShareactionField
 * @extends View.Fields.Base.EmailactionField
 */
({
    extendsFrom: 'EmailactionField',

    /**
     * Share template for subject.
     *
     * See {@link #_initShareTemplates}.
     */
    shareTplSubject: null,

    /**
     * Share template for body.
     *
     * See {@link #_initShareTemplates}.
     */
    shareTplBody: null,

    /**
     * Share template for body in HTML format.
     *
     * See {@link #_initShareTemplates}.
     */
    shareTplBodyHtml: null,

    /**
     * @inheritdoc
     *
     * Adds the share options for use when launching the email client.
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.type = 'emailaction';
        this._initShareTemplates();

        // If there is a default signature in email compose, it should be
        // placed below the share content in the email body.
        this.addEmailOptions({signature_location: 'below'});
    },

    /**
     * Initializes the sharing feature templates.
     *
     * This will get the templates from either the current module (since we
     * might want to customize it per module) or from core templates.
     *
     * Please define your templates on:
     *
     * - `custom/clients/{platform}/view/share/subject.hbs`
     * - `custom/clients/{platform}/view/share/body.hbs`
     * - `custom/clients/{platform}/view/share/body-html.hbs`
     * - `{custom/,}modules/{module}/clients/{platform}/view/share/subject.hbs`
     * - `{custom/,}modules/{module}/clients/{platform}/view/share/body.hbs`
     * - `{custom/,}modules/{module}/clients/{platform}/view/share/body-html.hbs`
     *
     * @template
     * @protected
     */
    _initShareTemplates: function() {
        this.shareTplSubject = app.template.getView('share.subject', this.module) ||
            app.template.getView('share.subject');
        this.shareTplBody = app.template.getView('share.body', this.module) ||
            app.template.getView('share.body');
        this.shareTplBodyHtml = app.template.getView('share.body-html', this.module) ||
            app.template.getView('share.body-html');
    },

    /**
     * Returns the subject to use in the email.
     *
     * @see EmailClientLaunch plugin.
     * @param {Data.Bean} model Use this model when constructing the subject.
     * @return {undefined|string}
     */
    emailOptionSubject: function(model) {
        var shareParams = this._getShareParams(model);
        var subject = this.shareTplSubject(shareParams);

        return subject;
    },

    /**
     * Returns the plain-text body to use in the email.
     *
     * @see EmailClientLaunch plugin.
     * @param {Data.Bean} model Use this model when constructing the body.
     * @return {undefined|string}
     */
    emailOptionDescription: function(model) {
        var shareParams = this._getShareParams(model);
        var description = this.shareTplBody(shareParams);

        return description;
    },

    /**
     * Returns the HTML body to use in the email.
     *
     * @see EmailClientLaunch plugin.
     * @param {Data.Bean} model Use this model when constructing the body.
     * @return {undefined|string}
     */
    emailOptionDescriptionHtml: function(model) {
        var shareParams = this._getShareParams(model);
        var description = this.shareTplBody(shareParams);
        var descriptionHtml = this.shareTplBodyHtml(shareParams);

        return descriptionHtml || description;
    },

    /**
     * Set subject and body settings for the EmailClientLaunch plugin to use
     *
     * @protected
     * @deprecated Use
     * View.Fields.Base.ShareactionField#emailOptionSubject,
     * View.Fields.Base.ShareactionField#emailOptionDescription, and
     * View.Fields.Base.ShareactionField#emailOptionDescriptionHtml
     * instead.
     */
    _setShareOptions: function() {
        app.logger.warn('View.Fields.Base.ShareactionField#_setShareOptions is deprecated. Use ' +
            'View.Fields.Base.ShareactionField#emailOptionSubject, ' +
            'View.Fields.Base.ShareactionField#emailOptionDescription, and ' +
            'View.Fields.Base.ShareactionField#emailOptionDescriptionHtml instead.');
    },

    /**
     * Get the params required by the templates defined on
     * {@link #_initShareTemplates}.
     *
     * Override this if your templates need more information to be sent on the
     * share email.
     *
     * @template
     * @protected
     * @param {Data.Bean} model The params come from this model's attributes.
     * EmailClientLaunch plugin should dictate the model based on the context.
     */
    _getShareParams: function(model) {
        // Falls back to the `this.model` for backward compatibility.
        model = model || this.model;

        return _.extend({}, model.attributes, {
            module: app.lang.getModuleName(model.module),
            appId: app.config.appId,
            url: window.location.href,
            name: new Handlebars.SafeString(app.utils.getRecordName(model))
        });
    },

    /**
     * Explicit share action to launch the sugar email client with share info
     * (used by bwc)
     */
    shareWithSugarEmailClient: function() {
        this.launchSugarEmailClient(this.emailOptions);
    },

    /**
     * If there is a default signature in email compose, it should be placed
     * below the share content in the email body.
     *
     * @return {Object}
     * @protected
     * @deprecated The signature location option is set during initialization.
     */
    _retrieveEmailOptionsFromLink: function() {
        app.logger.warn('View.Fields.Base.ShareactionField#_retrieveEmailOptionsFromLink is deprecated. ' +
            'The signature location option is set during initialization.');
        return {};
    },

    /**
     * Retrieve a mailto URL to launch an external mail client with share info
     * (used by bwc)
     */
    getShareMailtoUrl: function() {
        return this._buildMailToURL(this.emailOptions);
    }
})
