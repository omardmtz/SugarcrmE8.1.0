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
 * This field uses the `EmailClientLaunch` plugin to launch the appropriate
 * email client.
 *
 * Recipients to pre-populate are pulled from the mass_collection.
 * For external email client: Changes to the mass_collection will rebuild the mailto: link
 * For internal email client: Recipients are gathered from the mass_collection on click.
 * In order for the recipients to be prepopulated, this field requires the models in the
 * mass_collection contain the email field - if not, recipients will simply be blank (no error).
 *
 * @class View.Fields.Base.MassEmailButtonField
 * @alias SUGAR.App.view.fields.BaseMassEmailButtonField
 * @extends View.Fields.Base.ButtonField
 */
({
    extendsFrom: 'ButtonField',

    /**
     * @inheritdoc
     *
     * Add the `EmailClientLaunch` plugin and force use of `ButtonField`
     * templates.
     */
    initialize: function(options) {
        this.plugins = _.union(this.plugins || [], ['EmailClientLaunch']);
        this._super('initialize', [options]);
    },

    /**
     * Set up `add`, `remove` and `reset` listeners on the `mass_collection` so
     * we can render this button appropriately whenever the mass collection changes.
     */
    bindDataChange: function() {
        var massCollection = this.context.get('mass_collection');
        massCollection.on('add remove reset', this.render, this);
        this._super('bindDataChange');
    },

    /**
     * Clean up listener on mass_collection updates
     */
    unbindData: function() {
        var massCollection = this.context.get('mass_collection');
        if (massCollection) {
            massCollection.off(null, null, this);
        }
        this._super('unbindData');
    },

    /**
     * Map mass collection models to the appropriate format
     * required to prepopulate the to on email compose
     *
     * @return {Object} options to prepopulate on the email compose
     * @private
     */
    _retrieveEmailOptionsFromLink: function() {
        var massCollection = this.context.get('mass_collection'),
            to = _.map(massCollection.models, function(model) {
                return {bean: model};
            }, this);
        return {
            to: to
        };
    }
})
