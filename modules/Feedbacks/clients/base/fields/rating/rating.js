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
 * Rating field will generate clickable stars that will translate those into
 * a value for the model.
 *
 * Supported properties:
 *
 * - {Number} rate How many stars to display
 * - {Number} default What is the default value of the starts
 *
 * Example:
 *     // ...
 *     array(
 *         'rate' => 3,
 *         'default' => 3,
 *     ),
 *     //...
 *
 * @class View.Fields.Base.Feedbacks.RatingField
 * @alias SUGAR.App.view.fields.BaseFeedbacksRatingField
 * @extends View.Fields.Base.BaseField
 */
({

    /**
     * @inheritdoc
     *
     * Initializes default rate and generates stars based on that rate for
     * template.
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.def.rate = this.def.rate || 3;
        this.model.setDefault(this.name, this.def.default);
    },

    /**
     * @inheritdoc
     *
     * Fills all stars up to `this.value`. `true` means fill, `false` means not
     * filled.
     */
    format: function(value) {
        this.stars = _.map(_.range(1, this.def.rate + 1), function(n) {
            return n <= value;
        });
        return value;
    },

    /**
     * @inheritdoc
     */
    unformat: function(value) {
        return value + 1;
    },

    /**
     * @override
     * This will bind to a different event (`click` instead of `change`).
     */
    bindDomChange: function() {

        if (!this.model) {
            return;
        }

        var $el = this.$('[data-value]');
        $el.on('click', _.bind(function(evt) {
            var value = $(evt.currentTarget).data('value');
            this.model.set(this.name, this.unformat(value));
        }, this));
    },

    /**
     * @override
     * This will always render on model change.
     */
    bindDataChange: function() {
        if (this.model) {
            this.model.on('change:' + this.name, this.render, this);
        }
    }

})
