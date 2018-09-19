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
 * @class View.Fields.Base.Emails.QuickcreateField
 * @alias SUGAR.App.view.fields.BaseEmailsQuickcreateField
 * @extends View.Fields.Base.QuickcreateField
 */
({
    extendsFrom: 'QuickcreateField',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], ['EmailClientLaunch']);
        this._super('initialize', [options]);

        if (this.context && this.context.has('model')) {
            // call updateEmailLinks if the user changes something on the context model
            // so if user changes the email address we make sure we've got the latest
            // email address in the quick Compose Email link
            this.context.get('model').on('change', this.updateEmailLinks, this);
        }

        app.routing.before('route', this._beforeRouteChanged, this);
        app.router.on('route', this._routeChanged, this);
    },

    /**
     * Before we navigate to a different page, we need to remove the
     * change event listener we added on the context model
     *
     * @protected
     */
    _beforeRouteChanged: function() {
        if (this.context && this.context.has('model')) {
            // route is about to change, need to remove previous
            // listeners before model gets changed
            this.context.get('model').off('change', null, this);
        }
    },

    /**
     * After the route has changed, we need to re-add the model listener
     * on the new context model. This also calls updateEmailLinks to blank
     * out any existing email on the current quickcreate link; e.g. re-set the
     * quick Compose Email link back to "mailto:"
     *
     * @protected
     */
    _routeChanged: function() {
        if (this.context && this.context.has('model')) {
            // route has changed, most likely a new model, need to add new listeners
            this.context.get('model').on('change', this.updateEmailLinks, this);
        }
        this.updateEmailLinks();
    },

    /**
     * Used by EmailClientLaunch as a hook point to retrieve email options that are specific to a view/field
     * In this case we are using it to retrieve the parent model to make this email compose launching
     * context aware - prepopulating the to address with the given model and the parent relate field
     *
     * @return {Object}
     * @private
     */
    _retrieveEmailOptionsFromLink: function() {
        var context = this.context.parent || this.context,
            parentModel = context.get('model'),
            emailOptions = {};

        if (parentModel && parentModel.id) {
            // set parent model as option to be passed to compose for To address & relate
            // if parentModel does not have email, it will be ignored as a To recipient
            // if parentModel's module is not an available module to relate, it will also be ignored
            emailOptions = {
                to: [{bean: parentModel}],
                related: parentModel
            };
        }

        return emailOptions;
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        // remove context model change listeners if they exist
        this._beforeRouteChanged();
        app.routing.offBefore('route', this.beforeRouteChanged, this);
        app.router.off('route', this.routeChanged, this);

        this._super('_dispose');
    }
})
