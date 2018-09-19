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
 * @class View.Fields.Base.PdfactionField
 * @alias SUGAR.App.view.fields.BasePdfactionField
 * @extends View.Fields.Base.RowactionField
 */
({
    extendsFrom: 'RowactionField',
    events: {
        'click [data-action=link]': 'linkClicked',
        'click [data-action=download]': 'downloadClicked',
        'click [data-action=email]': 'emailClicked'
    },

    /**
     * PDF Template collection.
     *
     * @type {Data.BeanCollection}
     */
    templateCollection: null,

    /**
     * Visibility property for available template links.
     *
     * @property {Boolean}
     */
    fetchCalled: false,

    /**
     * @inheritdoc
     * Create PDF Template collection in order to get available template list.
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.templateCollection = app.data.createBeanCollection('PdfManager');
        this._fetchTemplate();
    },

    /**
     * @inheritdoc
     *
     * Prevents the "Email PDF" button from rendering if the user
     * doesn't have a valid email configuration or the user chooses to use an
     * external email client. RFC 2368 suggests only the "subject" and "body"
     * headers are safe headers and that other, unsafe headers do not need to
     * be supported by the "mailto" implementation. We cannot guarantee that
     * the "mailto" implementation for the user will allow for adding a PDF
     * attachment. To be consistent with existing application behavior, the
     * "Email PDF" option should be hidden for users when they cannot use the
     * internal email client.
     *
     * @private
     */
    _render: function() {
        var emailClientPreference = app.user.getPreference('email_client_preference');
        if (!this.templateCollection.length > 0 ||
            (this.def.action === 'email' && emailClientPreference.type !== 'sugar')) {
            this.hide();
        } else {
            this._super('_render');
        }
    },

    /**
     * Define proper filter for PDF template list.
     * Fetch the collection to get available template list.
     * @private
     */
    _fetchTemplate: function() {
        this.fetchCalled = true;
        var collection = this.templateCollection;
        collection.filterDef = {'$and': [{
            'base_module': this.module
        }, {
            'published': 'yes'
        }]};
        collection.fetch();
    },

    /**
     * Build download link url.
     *
     * @param {String} templateId PDF Template id.
     * @return {string} Link url.
     * @private
     */
    _buildDownloadLink: function(templateId) {
        var urlParams = $.param({
            'action': 'sugarpdf',
            'module': this.module,
            'sugarpdf': 'pdfmanager',
            'record': this.model.id,
            'pdf_template_id': templateId
        });
        return '?' + urlParams;
    },

    /**
     * Build email pdf link url.
     *
     * @param {String} templateId PDF Template id.
     * @return {string} Email pdf url.
     * @private
     */
    _buildEmailLink: function(templateId) {
        return '#' + app.bwc.buildRoute(this.module, null, 'sugarpdf', {
            'sugarpdf': 'pdfmanager',
            'record': this.model.id,
            'pdf_template_id': templateId,
            'to_email': '1'
        });
    },

    /**
     * Handle the button click event.
     * Stop event propagation in order to keep the dropdown box.
     *
     * @param {Event} evt Mouse event.
     */
    linkClicked: function(evt) {
        evt.preventDefault();
        evt.stopPropagation();
        if (this.templateCollection.dataFetched) {
            this.fetchCalled = !this.fetchCalled;
        } else {
            this._fetchTemplate();
        }
        this.render();
    },

    /**
     * Handles email pdf link.
     *
     * @param {Event} evt Mouse event.
     */
    emailClicked: function(evt) {
        var templateId = this.$(evt.currentTarget).data('id');
        app.router.navigate(this._buildEmailLink(templateId), {
            trigger: true
        });
    },

    /**
     * Handles download pdf link.
     *
     * Authenticate in bwc mode before triggering the download.
     *
     * @param {Event} evt The `click` event.
     */
    downloadClicked: function(evt) {
        var templateId = this.$(evt.currentTarget).data('id');

        app.bwc.login(null, _.bind(function() {
            this._triggerDownload(this._buildDownloadLink(templateId));
        }, this));
    },

    /**
     * Download the file once authenticated in bwc mode.
     *
     * @param {String} url The file download url.
     * @protected
     */
    _triggerDownload: function(url) {
        app.api.fileDownload(url, {
            error: function(data) {
                // refresh token if it has expired
                app.error.handleHttpError(data, {});
            }
        }, {iframe: this.$el});
    },

    /**
     * @inheritdoc
     * Bind listener for template collection.
     */
    bindDataChange: function() {
        this.templateCollection.on('reset', this.render, this);
        this._super('bindDataChange');
    },

    /**
     * @inheritdoc
     * Dispose safe for templateCollection listeners.
     */
    unbindData: function() {
        this.templateCollection.off(null, null, this);
        this.templateCollection = null;
        this._super('unbindData');
    },

    /**
     * @inheritdoc
     * Check additional access for PdfManager Module.
     */
    hasAccess: function() {
        var pdfAccess = app.acl.hasAccess('view', 'PdfManager');
        return pdfAccess && this._super('hasAccess');
    }
})
