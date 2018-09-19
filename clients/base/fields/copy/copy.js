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
 * @class View.Fields.Base.CopyField
 * @alias SUGAR.App.view.fields.BaseCopyField
 * @extends View.Fields.Base.BaseField
 */
({
    'events': {
        'click button': 'copyOnce'
    },

    _initialValues: null,
    _fields: null,
    _inSync: false,

    /**
     * Initializes the copy field component.
     *
     * Initializes the initialValues and fields properties.
     * Enables sync by default.
     *
     * @inheritdoc
     */
    initialize: function(options) {

        this._super('initialize', [options]);
        this._initialValues = {};
        this._fields = {};

        if (_.isUndefined(this.def.sync)) {
            this.def.sync = true;
        }

        this.before('render', function() {
            this.setDisabled(!this.hasAccess());
            return true;
        }, this);
    },

    /**
     * Function called for each click on checkbox (normally acts as toggle
     * function).
     *
     * If the checkbox is checked, copy all the source fields to target ones
     * based on the mapping definition of this field and disable target fields.
     * Otherwise, restore all the values of the modified fields by this copy
     * widget and enable target fields.
     *
     */
    toggle: function() {
        this.sync(this.$fieldTag.is(':checked'));
    },

    sync: function(enable) {

        enable = this.hasAccess() && (_.isUndefined(enable) || enable);

        if (this._inSync === enable) {
            return;
        }
        this._inSync = enable;

        if (!enable) {
            this.syncCopy(false);
            this.restore();
            return;
        }

        _.each(this.def.mapping, function(target, source) {
            this.copy(source, target);
            var field = this.getField(target);
            if (!_.isUndefined(field)) {
                field.setDisabled(true);
            }
        }, this);

        this.syncCopy(true);
    },

    /**
     * Function called for each click on button (normally acts as copy once).
     *
     * If the button is pressed, copy all the source fields to target ones
     * based on the mapping definition of this field.
     *
     * @param {Event} evt
     *   The event (expecting a click event) that triggers the copy once.
     */
    copyOnce: function(evt) {

        _.each(this.def.mapping, function(target, source) {
            this.copy(source, target);
        }, this);
    },

    /**
     * Copies the source field value to the target field.
     *
     * Store the initial value of the target field to be able to restore it
     * after. Copy the source field value to the target field.
     *
     * @param {View.Field} from
     *   The source field to get the value from.
     * @param {View.Field} to
     *   The target field to set the value to.
     */
    copy: function(from, to) {

        if (!this.model.has(from)) {
            return;
        }

        if (_.isUndefined(this._initialValues[to])) {
            this._initialValues[to] = this.model.get(to);
        }

        if (app.acl.hasAccessToModel('edit', this.model, to)) {
            this.model.set(to, this.model.get(from));
        }
    },

    /**
     * Restores all the initial value of the fields that were modified by this
     * copy command.
     */
    restore: function() {

        _.each(this._initialValues, function(value, field) {
            this.model.set(field, value);
        }, this);

        _.each(this.def.mapping, function(target, source) {
            var field = this.getField(target);
            if (!_.isUndefined(field)) {
                field.setDisabled(false);
            }
        }, this);

        this._initialValues = {};
    },

    /**
     * Enables or disables the sync copy only if the field has the `sync`
     * definition to set to TRUE.
     *
     * @param {Boolean} enable
     *   TRUE to keep the mapping fields in sync, FALSE otherwise.
     */
    syncCopy: function(enable) {

        if (!this.def.sync) {
            return;
        }

        if (!enable) {
            this.model.off(null, this.copyChanged, this);
            return;
        }

        var events = _.map(_.keys(this.def.mapping), function(field) {
            return 'change:' + field;
        });
        this.model.on(events.join(' '), this.copyChanged, this);
    },

    /**
     * Callback for the syncCopy binding.
     *
     * @param {Backbone.Model} model
     *   The model that was changed.
     * @param {*} value
     *   The value of the field that was changed.
     */
    copyChanged: function(model, value) {
        _.each(model.changedAttributes(), function(newValue, field) {
            model.set(this.def.mapping[field], model.get(field));
        }, this);
    },

    /**
     * Get the field with the supplied name.
     *
     * Cache the fields locally to be faster on next request of the same field.
     *
     * @param {String} name
     *   The name of the field to search for.
     *
     * @return {View.Field}
     *   The field with the name given.
     */
    getField: function(name) {

        if (_.isUndefined(this._fields[name])) {
            this._fields[name] = _.find(this.view.fields, function(field) {
                return field.name == name;
            });
        }

        return this._fields[name];
    },

    unformat: function(value) {
        // TODO this should change once we save this in the db
        return null;
    },

    /**
     * @inheritdoc
     *
     * @return {Boolean}
     */
    format: function(value) {
        if (_.isNull(value)) {
            // TODO this should change to the value once we get it from the model
            return this._inSync;
        }
        return value;
    },
    /**
     * Binds DOM changes to a model.
     */
    bindDomChange: function() {

        if (!(this.model instanceof Backbone.Model)) return;

        var self = this;
        var el = this.$fieldTag = this.$el.find(this.fieldTag);
        el.on("change", function() {
          self.toggle();
        });
    },
    /**
     * @inheritdoc
     *
     * This will make the fields in sync if it is on by default.
     */
    bindDataChange: function() {

        // TODO this field should be saved on the DB so we don't have this hack
        if (this.model && this.def.sync) {

            var active = !_.isUndefined(this.def['default']) ? this.def['default'] : true;
            if (!active && this.model.isNew()) {
                return;
            }

            var inSync = this.model.isNew() || _.all(this.def.mapping, function(target, source) {
                return this.model.has(source) && this.model.get(source) === this.model.get(target);
            }, this);
            this.sync(inSync);
        }
    },

    /**
     * Determine if ACLs allow for the copy to show
     * ACL check should return true if there is access to read target and edit
     * source for at least to one mapped field
     * @return {Boolean}
     */
    hasAccess: function() {
        return _.some(this.def.mapping || [], function(toField, fromField) {
            return app.acl.hasAccessToModel('read', this.model, fromField) &&
                app.acl.hasAccessToModel('edit', this.model, toField);
        }, this);
    }
})
