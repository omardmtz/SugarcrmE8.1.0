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
 * @class View.Fields.Base.ButtonField
 * @alias SUGAR.App.view.fields.BaseButtonField
 * @extends View.Fields.Base.BaseField
 */
({
    tagName: "span",
    fieldTag: "a",

    plugins: ['MetadataEventDriven'],

    initialize: function(options) {
        var self = this;
        this.tabIndex = options.def.tabindex || 0;

        this.events = _.extend({}, {
            'click *' : 'preventClick'
        }, this.events, options.def.events);

        this._super('initialize', [options]);

        // take advantage of this hook to do the acl check
        // we use this wrapper because our spec
        // requires us to set the button.isHidden = true
        // if we don't render it.
        this.before("render", function() {
            if (self.hasAccess()) {
                this._show();
                return true;
            }
            else {
                this.hide();
                return false;
            }
        });
    },
    _render:function() {
        this.fullRoute = _.isString(this.def.route) ? this.def.route : null;
        this.ariaLabel = null;
        if (!this.label || this.label.trim() === '') {
            if (this.def.tooltip) {
                this.ariaLabel = app.lang.get(this.def.tooltip, this.module);
            } else {
                this.ariaLabel = _.isString(this.def.icon) ? this.def.icon.replace(/^fa-(.*)/, '$1').replace(/-o(-)|-o$/, ' outline$1').replace('-', ' ') : null;
            }
        }

        app.view.Field.prototype._render.call(this);
    },
    getFieldElement: function() {
        return this.$(this.fieldTag);
    },
    setDisabled: function(disable) {
        disable = _.isUndefined(disable) ? true : disable;
        this.def.css_class = this.def.css_class || '';
        var css_class = this.def.css_class.split(' ');
        if (disable) {
            css_class.push('disabled');
        } else {
            css_class = _.without(css_class, 'disabled');
        }
        this.tabIndex = disable ? -1 : 0;
        this.def.css_class = _.unique(_.compact(css_class)).join(' ');
        app.view.Field.prototype.setDisabled.call(this, disable);
    },

    /**
     * Prevents the `click` event from propagating further if the button is
     * in a disabled state.
     *
     * @param {Event} evt The `click` event.
     * @return {boolean} Returns `false` if the button is disabled.
     */
    preventClick: function(evt) {
        // FIXME: isDisabled should not check against `this.action`, and should
        // should eliminate the need here to check for the `disabled` class.
        // Should be fixed with SC-3418.
        if (this.isDisabled() || this.$(this.fieldTag).hasClass('disabled')) {
            evt.preventDefault();
            evt.stopImmediatePropagation();
            return false;
        }
    },

    /**
     * Handles the jquery showing and event throwing
     * of the button. does no access checks.
     * @protected
     */
    _show: function() {
        if (this.isHidden !== false) {
            if (!this.triggerBefore("show")) {
                return false;
            }

            this.getFieldElement().removeClass("hide").show();
            this.isHidden = false;
            this.trigger('show');
        }
    },
    show: function() {
        if(this.hasAccess()) {
            this._show();
        } else {
            this.isHidden = true;
        }
    },
    hide: function() {
        if (this.isHidden !== true) {
            if (!this.triggerBefore("hide")) {
                return false;
            }

            this.getFieldElement().addClass("hide").hide();
            this.isHidden = true;
            this.trigger('hide');
        }
    },
    /**
     * Track using the flag that is set on the hide and show from above.
     *
     * It should check the visivility by isHidden instead of DOM visibility testing
     * since actiondropdown renders its dropdown lazy
     *
     * @return {boolean}
     */
    isVisible: function() {
        return !this.isHidden;
    },
    /**
     * @inheritdoc
     *
     * No data changes to bind.
     */
    bindDomChange: function () {
    },
    /**
     * @inheritdoc
     *
     * No need to bind DOM changes to a model.
     */
    bindDataChange: function () {
    },
    /**
     * Determine if ACLs or other properties (for example, "allow_bwc") allow for the button to show
     * @return {Boolean} true if allow access, false otherwise
     */
    hasAccess: function() {
        // buttons use the acl_action and acl_module properties in metadata to denote their action for acls
        var acl_module = this.def.acl_module,
            acl_action = this.def.acl_action;

        // Need to test BWC status
        if (_.isBoolean(this.def.allow_bwc) && !this.def.allow_bwc) {
            app.logger.warn('The "allow_bwc" property has been deprecated since 7.9, and will be removed in 7.10.');

            var isBwc = app.metadata.getModule(acl_module || this.module).isBwcEnabled;
            if (isBwc) {
                // Action not allowed for BWC module
                return false;
            }
        }

        // Finally check ACLs
        if (!acl_module) {
            return app.acl.hasAccessToModel(acl_action, this.model, this);
        } else {
            return app.acl.hasAccess(acl_action, acl_module);
        }
    }
})
