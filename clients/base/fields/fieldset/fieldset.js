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
 * A fieldset is a field that contains one or more child fields.
 * The hbs template sets the placeholders of child fields but the creation of
 * child fields reside in the controller.
 *
 * Accessibility is checked against each child field as well as the fieldset.
 * We do not hide the fieldset in the event that the fieldset is accessible and
 * all child fields are not.
 *
 * Supported properties:
 *
 * - {Array} fields List of fields that are part of the fieldset.
 * - {boolean} show_child_labels Set to `true` to show labels on child fields in
 * the record view.
 * - {boolean} inline Set to `true` to render the fieldset inline.
 * - {boolean} equal_spacing When in inline mode, setting `true` will make the
 * fields inside fieldsets to have equal spacing, rather than being left aligned.
 *
 * Example usage:
 *
 *      array(
 *          'name' => 'date_entered_by',
 *          'type' => 'fieldset',
 *          'label' => 'LBL_DATE_ENTERED',
 *          'fields' => array(
 *              array(
 *                  'name' => 'date_entered',
 *              ),
 *              array(
 *                  'type' => 'label',
 *                  'default_value' => 'LBL_BY',
 *              ),
 *              array(
 *                  'name' => 'created_by_name',
 *              ),
 *          )
 *      )
 *
 * @class View.Fields.Base.FieldsetField
 * @alias SUGAR.App.view.fields.BaseFieldsetField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * Initializes the fieldset field component.
     *
     * Initializes the fields property.
     *
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        /**
         * Children fields that are created as part of this field.
         *
         * @property {Array}
         */
        this.fields = [];

        var inlineTag = this.def.inline ? '-inline' : '';
        this.def.css_class = (this.def.css_class ? this.def.css_class + ' fieldset' :
            'fieldset') + inlineTag;

        if (this.def.equal_spacing && this.def.inline) {
            this.def.css_class += ' fieldset-equal';
        }
    },

    /**
     * @inheritdoc
     *
     * Looks for the fallback template as specified by the view. Returns the
     * `detail` template if it's not found.
     * FIXME: SC-3363 This should be the default behavior in `field.js`.
     */
    _getFallbackTemplate: function(viewName) {
        if (_.contains(this.fallbackActions, viewName)) {
            return viewName;
        }
        if (app.template.get('f.' + this.type + '.' + this.view.fallbackFieldTemplate)) {
            return this.view.fallbackFieldTemplate;
        }
        return 'detail';
    },

    /**
     * @inheritdoc
     *
     * Loads the `record-detail` template if the view is `record`.
     * FIXME: This is a quick hack and will be fixed by SC-3364.
     */
    _loadTemplate: function() {
        this._super('_loadTemplate');

        if ((this.view.name === 'record' || this.view.name === 'create'
            || this.view.name === 'create-nodupecheck' || this.view.name === 'pmse-case')
            && this.type === 'fieldset' && !_.contains(this.fallbackActions, this.action)) {

            this.template = app.template.getField('fieldset', 'record-detail', this.model.module);
        }
    },

    /**
     * @inheritdoc
     *
     * If current fieldset is not readonly, it always falls back to false
     * (nodata unsupportable).
     * If current fieldset is readonly and all dependant fields contains empty
     * data set, then it falls back to true.
     *
     * @return {Boolean} true if this fieldset is readonly and all the data
     * fields are empty.
     */
    showNoData: function() {

        if (!this.def.readonly) {
            return false;
        }

        return !_.some(this.fields, function(field) {
            return field.name && field.model.has(field.name);
        });
    },

    /**
     * @inheritdoc
     *
     * We set the result from `field.getPlaceholder()` into a property named
     * `placeholder` for each of the child fields. These placeholders help us
     * render the child fields when placed in the hbs file.
     */
    _render: function() {
        var fields = this._getChildFields();
        _.each(fields, function(field) {
            field.placeholder = field.getPlaceholder();
        }, this);

        this.focusIndex = 0;

        this._super('_render');

        this._renderFields(fields);

        return this;
    },

    /**
     * Renders the children fields in their respective placeholders.
     *
     * @param {Array} fields The children fields.
     * @protected
     */
    _renderFields: function(fields) {
        // In terms of performance it is better to search the DOM once for
        // all the fieldset fields, than to search the DOM for each field.
        // That's why we cache the DOM elements in the `fieldElems` hash and pass
        // them to {@link Backbone.View#setElement}.
        var fieldElems = {};

        this.$('span[sfuuid]').each(function() {
            var $this = $(this);
            var sfId = $this.attr('sfuuid');
            fieldElems[sfId] = $this;
        });

        _.each(fields, function(field) {
            field.setElement(fieldElems[field.sfId]);
            field.render();
        }, this);
    },

    /**
     * Gets the child field definitions that are defined in the metadata.
     *
     * @return {Object} Metadata of the child fields.
     * @protected
     */
    _getChildFieldsMeta: function() {
        return app.utils.deepCopy(this.def.fields);
    },

    /**
     * Creates the children fields that are specified in the definitions.
     *
     * @return {Array} Children fields that are created.
     * @protected
     */
    _getChildFields: function() {
        if (!_.isEmpty(this.fields)) {
            return this.fields;
        }

        var metaFields = this._getChildFieldsMeta();
        if (metaFields) {
            _.each(metaFields, function(fieldDef) {
                var field = app.view.createField({
                    def: fieldDef,
                    view: this.view,
                    nested: true,
                    viewName: this.options.viewName,
                    model: this.model
                });
                this.fields.push(field);
                field.parent = this;
            }, this);
        }
        return this.fields;
    },

    /**
     * @inheritdoc
     */
    clearErrorDecoration: function() {
        _.each(this.fields, function(field) {
            field.clearErrorDecoration();
        });

        this._super('clearErrorDecoration');
    },

    /**
     * The tab handler.
     *
     * Focus on the next child field. Skips disabled fields.
     *
     * @return {boolean} `true` if this method should be called upon the next tab.
     */
    focus: function() {
        // this should be zero but lets make sure
        if (this.focusIndex < 0 || !this.focusIndex) {
            this.focusIndex = 0;
        }

        if (this.focusIndex >= this.fields.length) {
            // done focusing our inputs return false
            this.focusIndex = -1;
            return false;
        } else {
            // this field is disabled skip ahead
            if (this.fields[this.focusIndex] && this.fields[this.focusIndex].isDisabled()) {
                this.focusIndex++;
                return this.focus();
            }
            // if the next field returns true its not done focusing so don't
            // increment to the next field
            if (_.isFunction(this.fields[this.focusIndex].focus) && this.fields[this.focusIndex].focus()) {
            } else {
                var field = this.fields[this.focusIndex];
                var $el = field.$(field.fieldTag + ':first');
                $el.focus().val($el.val());
                this.focusIndex++;
            }
            return true;
        }
    },

    /**
     * Fieldsets need to reset the action of its individual fields as well
     *
     * @protected
     * @override
     */
    _resetAction: function() {
        this._super('_resetAction');
        _.each(this.fields, function(field) {
            field._resetAction();
        });
    },

    /**
     * @inheritdoc
     */
    setDisabled: function(disable) {
        disable = _.isUndefined(disable) ? true : disable;
        this._super('setDisabled', [disable]);
        _.each(this.fields, function(field) {
            field.setDisabled(disable);
        }, this);
    },

    /**
     * @inheritdoc
     */
    setViewName: function(view) {
        this._super('setViewName', [view]);
        _.each(this.fields, function(field) {
            field.setViewName(view);
        }, this);
    },

    /**
     * @inheritdoc
     *
     * Set action name of child fields of this field set.
     * Reset current focus index to the first item when it switches to different mode.
     */
    setMode: function(name) {
        this.focusIndex = 0;

        //Set the mode on child fields without rendering
        _.each(this.fields, function(field) {
            var oldAction = field._previousAction || field.action;
            field._removeViewClass(oldAction);
            if (field.isDisabled()) {
                field._previousAction = name;
            } else {
                field.action = name;
            }
        });

        //The _super 'setMode' would render all child fields.
        this._super('setMode', [name]);
    },

    /**
     * @inheritdoc
     *
     * We need this empty so it won't affect the nested fields that have the
     * same `fieldTag` of this fieldset due the usage of `find()` method.
     */
    bindDomChange: function() {
    },

    /**
     * @inheritdoc
     *
     * Keep empty because you cannot set a value of a type `fieldset`.
     */
    bindDataChange: function() {
        var removeNoData = _.debounce(function() {
            if (this.disposed) {
                return;
            }

            if (this.action === 'nodata') {
                this.setMode('detail');
            }
        }, 100);

        _.each(this._getChildFields(), function(field) {
            this.model.on('change:' + field.name, removeNoData, this);
        }, this);
    },

    /**
     * @inheritdoc
     *
     * We need this empty so it won't affect the nested fields that have the
     * same `fieldTag` of this fieldset due the usage of `find()` method.
     */
    unbindDom: function() {
    },

    /**
     * @inheritdoc
     */
    _dispose: function() {
        //fields inside fieldset need to be disposed before the fielset itself is disposed.
        _.each(this.fields, function(field) {
            field.parent = null;
            field.dispose();
        });
        this.fields = null;
        app.view.Field.prototype._dispose.call(this);
    }
})
