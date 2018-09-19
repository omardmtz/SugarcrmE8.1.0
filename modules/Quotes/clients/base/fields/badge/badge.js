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
 * @class View.Fields.Base.BadgeField
 * @alias SUGAR.App.view.fields.BaseBadgeField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * Hash map of the possible labels for the badge
     */
    badgeLabelMap: undefined,

    /**
     * Hash map of the possible CSS Classes for the badge
     */
    cssClassMap: undefined,

    /**
     * The current CSS Class to add to the badge
     */
    currentCSSClass: undefined,

    /**
     * The current Label to use for the badge
     */
    currentLabel: undefined,

    /**
     * The field name to check for the badge
     */
    badgeFieldName: undefined,

    /**
     * The current state of the field
     */
    state: undefined,

    /**
     * @inheritdoc
     *
     * This field doesn't support `showNoData`.
     */
    showNoData: false,

    /**
     * @inheritdoc
     *
     * The badge is always a readonly field.
     */
    initialize: function(options) {
        options.def.readonly = true;
        this._initOptionMaps(options);

        this._super('initialize', [options]);

        this._setState();
    },

    /**
     * Sets up any class hashes defined in metadata
     *
     * @param {Object} options The field def options from metadata
     * @private
     */
    _initOptionMaps: function(options) {
        this.cssClassMap = options.def.css_class_map;
        this.badgeLabelMap = options.def.badge_label_map;
    },

    /**
     * Sets the state of the field, field name, label, css classes, etc
     *
     * @private
     */
    _setState: function() {
        this.badgeFieldName = this.def.related_fields && _.first(this.def.related_fields) || this.name;

        var val = this.model.get(this.badgeFieldName);
        switch (this.def.badge_compare.comparison) {
            case 'notEq':
                this.state = val != this.def.badge_compare.value;
                break;
            case 'eq':
                this.state = val == this.def.badge_compare.value;
                break;
            case 'notEmpty':
                this.state = !_.isUndefined(val) && !_.isEmpty(val.toString());
                break;
            case 'empty':
                this.state = !_.isUndefined(val) && _.isEmpty(val.toString());
                break;
        }

        this.currentLabel = app.lang.get(this.badgeLabelMap[this.state], this.module);
        this.currentCSSClass = this.cssClassMap[this.state];
    },

    /**
     * @inheritdoc
     */
    bindDataChange: function() {
        this.model.on('change:' + this.badgeFieldName, function() {
            if (!this.disposed) {
                this._setState();
                this.render();
            }
        }, this);
    }
})
