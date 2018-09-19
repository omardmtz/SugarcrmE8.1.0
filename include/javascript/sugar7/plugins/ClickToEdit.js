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
 * ClickToEdit is both a View-level and Field-level plugin. This combines the < v7.9 "CteTabbing" and "ClickToEdit"
 * plugins into one plugin as there were dependencies across the plugins.
 *
 * View-level plugin, previously "CteTabbing"
 *      This plugin enables a view to support tabbing forward and backward
 *      across multiple click-to-edit-enabled fields
 *
 * Field-level plugin, previously "ClickToEdit"
 *      This plugin allows a field to become editable on click.
 *      Current supported field types:
 *          - Int
 *          - Currency
 *          - Enum
 *          - Date
 *      Others may work work but have not been tested or inline validation written for them.
 */
(function(app) {
    app.events.on('app:init', function() {
        app.plugins.register('ClickToEdit', ['view', 'field'], {
            /**
             * If this component is a View
             */
            isView: false,

            /**
             * If this component is a Field
             */
            isField: false,

            /**
             * Are we on IE 9 or 10?
             */
            isIe9Or10: $.browser.msie && ($.browser.version === '9.0' ||
                ($.browser.version === '10.0' && !!navigator.userAgent.match(/Trident\/6\./))),

            /**
             * current index in the CTE list
             */
            _viewCurrentIndex: -1,

            /**
             * current CTE list (dom elements)
             */
            _viewCurrentCTEList: [],

            /**
             * Can the field be edited
             */
            _fieldCanEdit: false,

            /**
             * are we currently in edit mode
             */
            _fieldIsInEdit: false,

            /**
             * Error Message
             */
            _fieldErrorMessage: '',

            /**
             * Is an error currently being displayed for a field
             */
            _fieldIsErrorState: false,

            /**
             * if we have already processed the handleHideDatePicker event, don't process it again
             */
            isHiding: false,

            /**
             * @inheritdoc
             */
            onAttach: function(component, plugin) {
                if (component instanceof app.view.Field) {
                    this.isField = true;
                    this._fieldOnAttach(component);
                } else if (component instanceof app.view.View) {
                    this.isView = true;
                    this._viewOnAttach(component);
                }
            },

            /**
             * onAttach for a View component
             *
             * @param {App.view.Component} component
             * @protected
             */
            _viewOnAttach: function(component) {
                this.once('init', function() {
                    this.context.on('field:editable:click', this._viewHandleClickIndex, this);
                    $(window).on('keydown.' + this.cid, _.bind(function(e) {
                        if (this.layout.isVisible()) {
                            this._viewHandleKeyDown(e);
                        }
                    }, this));
                    this.on('render', this._viewResetCTEList, this);
                }, this);
            },

            /**
             * onAttach for a Field component
             *
             * @param {App.view.Component} component
             * @protected
             */
            _fieldOnAttach: function(component) {
                this.events = _.extend(this.events, {
                    'mouseenter div.clickToEdit': '_fieldShowClickToEdit',
                    'mouseleave div.clickToEdit': '_fieldHideClickToEdit',
                    'click div.clickToEdit': '_fieldHandleFieldClick'
                });

                this.once('init', _.bind(function() {
                    if (this._fieldCheckIfCanEdit()) {
                        this._fieldBindPluginEvents();
                    }
                }, this));
            },

            /**
             * @inheritdoc
             */
            onDetach: function(component, plugin) {
                if (this.isField) {
                    this._fieldOnDetach(component);
                } else if (this.isView) {
                    this._viewOnDetach(component);
                }
            },

            /**
             * onDetach for a View component
             *
             * @param {App.view.Component} component
             * @protected
             */
            _viewOnDetach: function(component) {
                this.context.off('field:editable:click');
                $(window).off('keydown.' + this.cid);
            },

            /**
             * onDetach for a Field component
             *
             * @param {App.view.Component} component
             * @protected
             */
            _fieldOnDetach: function(component) {
                $(document).off('mousedown.record' + this.cid);
            },

            /**
             * Handles custom window keydown events
             *
             * @param evt Event object
             * @protected
             */
            _viewHandleKeyDown: function(evt) {
                if (evt.which === 9) { // tab
                    evt.preventDefault();
                    var $elem = $(evt.target),
                        isValid,
                        field = this.fields[$(this._viewCurrentCTEList[this._viewCurrentIndex]).attr('sfuuid')],
                        val;

                    // find the value for the element we are on.
                    if (field && field.type === 'enum') {
                        // since the enum fields use select2, we have to use it to get the value
                        val = field.$(field.fieldTag).select2('val');
                    } else {
                        // just pull from the target otherwise
                        val = $elem.val();
                    }

                    if (!evt.shiftKey) {
                        if (this._viewCurrentIndex !== -1 &&
                            this._viewCurrentIndex + 1 < this._viewCurrentCTEList.length) {
                            // get the field
                            isValid = this._fieldDoValidate($elem, val);
                            if (isValid) {
                                $elem.parents('.isEditable').removeClass('error');
                                this._viewCurrentIndex++;
                            } else {
                                $elem.parents('.isEditable').addClass('error');
                            }
                        } else {
                            this._viewCurrentIndex = 0;
                        }
                    } else {
                        if (this._viewCurrentIndex - 1 >= 0) {
                            isValid = this._fieldDoValidate($elem, val);
                            if (isValid) {
                                $elem.parents('.isEditable').removeClass('error');
                                this._viewCurrentIndex--;
                            } else {
                                $elem.parents('.isEditable').addClass('error');
                            }
                        } else {
                            this._viewCurrentIndex = this._viewCurrentCTEList.length - 1;
                        }
                    }
                    this.$(this._viewCurrentCTEList[this._viewCurrentIndex]).find('div.clickToEdit').click();
                }
            },

            /**
             * resets the CTE list of dom elements
             *
             * @protected
             */
            _viewResetCTEList: function() {
                var oldLength = this._viewCurrentCTEList.length;
                this._viewCurrentCTEList = this.$('.isEditable');

                /*
                 * If the length of the CTE list changes (less rows), then most likely the cell we were on initiated
                 * that change.  We need to reset the current tab index to trigger the same cell, becuase
                 * the original one is now gone.
                 *
                 * Note: if business rules change, this will have to be revisited.
                 */
                if (oldLength > this._viewCurrentCTEList.length) {
                    this._viewCurrentIndex--;
                }
            },

            /**
             * handle setting the current index from a click
             *
             * @param field Field that is clicked
             * @protected
             */
            _viewHandleClickIndex: function(field) {
                _.find(this._viewCurrentCTEList, function(el, idx, list) {
                    var $el = $(el);
                    if (_.isEqual($el.find('div.clickToEdit').data('cid'), field.cid)) {
                        this._viewCurrentIndex = idx;
                        return true;
                    }
                }, this);
            },

            /**
             * Bind Field plugin events
             *
             * @protected
             */
            _fieldBindPluginEvents: function() {
                this.context.on('field:editable:open', function(cid) {
                    // another CTE field has been opened
                    if (this._fieldIsErrorState) {
                        // I am open with an error, send the message
                        this.context.trigger('field:editable:error', this.cid);
                    } else {
                        if (this._fieldIsInEdit && this.cid !== cid) {
                            if (this.type === 'enum') {
                                this.$('select').select2('close');
                            }

                            // for the date field, this is handled when the date field gets removed below
                            if (this.type != 'date') {
                                this.setMode('list');
                            }
                        }
                    }
                }, this);

                this.context.on('field:editable:error', function(cid) {
                    if (!_.isEqual(cid, this.cid) && this.options.viewName === 'edit') {
                        // some other field is open with an error, close this
                        this.setMode('list');
                    }
                }, this);

                this.on('render', function() {
                    var cteClass = 'clickToEdit';
                    if (this.action === 'edit') {
                        cteClass += ' active';
                        this.$el.addClass('active');
                    } else {
                        this.$el.removeClass('active');
                    }
                    // only add isEditable if the field is not disabled
                    if (!this.$el.hasClass('disabled')) {
                        this.$el.addClass('isEditable');
                        this.$el.wrapInner('<div class="' + cteClass + '" data-cid="' + this.cid + '" />');
                    }
                }, this);

                if (this.context.parent) {
                    // Clears errors when navigating from the manager's forecast worksheet to the manager's RLI so that
                    // the error tooltip is not displaying when forecast worksheet is hidden.
                    this.context.parent.on('change:selectedUser', function() {
                        if (this._fieldIsErrorState) {
                            this.clearErrorDecoration();
                        }
                    }, this);
                }
            },

            /**
             * Logic to make sure that we can actually edit the field
             *
             * @return {boolean}
             * @protected
             */
            _fieldCheckIfCanEdit: function() {
                var isEnforced = (!_.isUndefined(this.def.enforced) && this.def.enforced === true),
                    isClickToEdit = (!_.isUndefined(this.def.click_to_edit) && this.def.click_to_edit === true);
                if (!isEnforced && isClickToEdit) {
                    // only worksheet owner can edit
                    // make sure we get the correct context, if we are in the forecast module
                    // its this.context.parent otherwise, its this.context
                    var ctx = this.context.parent || this.context,
                        selectedUser = ctx.get('selectedUser') || app.user.toJSON();

                    this._fieldCanEdit = _.isEqual(app.user.get('id'), selectedUser.id);

                    // lets make sure we can actually write to the field
                    this._fieldCanEdit = (this._fieldCanEdit &&
                    app.acl.hasAccess('write', this.module, app.user.get('id'), this.name));

                    // only they have write access to the field and if sales stage is won/lost can edit
                    if (this._fieldCanEdit && this.model.has('sales_stage')) {
                        var salesStage = this.model.get('sales_stage'),
                            disableIfSalesStageIs = _.union(
                                app.metadata.getModule('Forecasts', 'config').sales_stage_won,
                                app.metadata.getModule('Forecasts', 'config').sales_stage_lost
                            );
                        if (salesStage && _.indexOf(disableIfSalesStageIs, salesStage) != -1) {
                            this._fieldCanEdit = false;
                        }
                    }
                }

                return this._fieldCanEdit;
            },

            /**
             * Overwrite the default bindDomChange for Fields since we need to do inline validation
             *
             * @protected
             */
            _fieldBindDomChange: function() {
                var $el = this.$(this.fieldTag);
                $el.on('change', _.bind(function() {
                    var value = this._fieldDoValidate(this, $el.val());
                    if (value !== false) {
                        // field is valid, save it
                        if (this._fieldIsErrorState) {
                            this.clearErrorDecoration();
                        }
                        // save to model
                        this.model.set(this.name, this.unformat(value));
                    } else {
                        // invalid display error
                        var hb = Handlebars.compile('{{str key module context}}'),
                            args = {field_name: app.lang.get(this.def.label, this.module)};

                        this._fieldErrorMessage = hb({
                            'key': 'LBL_EDITABLE_INVALID',
                            'module': this.module,
                            'context': args
                        });

                        this._fieldShowErrors();
                        $el.select();
                    }
                }, this));

                // Focus doesn't always change when tabbing through inputs on IE9 & IE10 (Bug54717)
                // This prevents change events from being fired appropriately on IE9 & IE10
                if (this.isIe9Or10 && $el.is('input')) {
                    _.defer(function(el) {
                        $el.on('input', function() {
                            // Set focus on input element receiving user input
                            $el.focus();
                        });
                    }, $el);
                }
            },

            /**
             * Show an error message if not already display
             *
             * @protected
             */
            _fieldShowErrors: function() {
                if (this._fieldIsErrorState === false) {
                    var ftag = this.fieldTag || '';
                    var $ftag = this.$(ftag);
                    var $tooltip;

                    this._fieldIsErrorState = true;

                    var ctx = this.context.parent || this.context;
                    // trigger field error on context
                    ctx.trigger('field:error', this, true);

                    this.$el.addClass('error');

                    var isWrapped = $ftag.parent().hasClass('input-append');
                    if (!isWrapped) {
                        $ftag.wrap('<div class="input-append ' + ftag + '">');
                    }

                    $ftag.parent().addClass('error');
                    $tooltip = $(this.exclamationMarkTemplate([this._fieldErrorMessage]));
                    $ftag.after($tooltip);

                    this.$('input').addClass('local-error');
                }
            },

            /**
             * Show the click to edit icon.
             *
             * @param event
             * @protected
             */
            _fieldShowClickToEdit: function(event) {
                if (this._fieldCanEdit && !this._fieldIsInEdit) {
                    var target = $(event.currentTarget),
                        icon = '<span class="edit-icon"><i class="fa fa-pencil fa-sm"></i></span>';

                    // use case for currency field that show transactional value + the converted to base currency
                    if (target.has('label.original').length) {
                        target = target.find('label.original').next();
                    }

                    // use case for the ellipsis_inline div
                    if (target.has('div.ellipsis_inline').length) {
                        target = target.find('div.ellipsis_inline');
                    }

                    target.prepend(icon);
                }
            },

            /**
             * Hide the click to edit icon.
             *
             * @param event
             * @protected
             */
            _fieldHideClickToEdit: function(event) {
                if (this._fieldCanEdit && !this._fieldIsInEdit) {
                    $(event.currentTarget).find('span.edit-icon').remove();
                }
            },

            /**
             * Handle when a click event is triggered
             *
             * @param evt Click event
             * @protected
             */
            _fieldHandleFieldClick: function(evt) {
                if (this._fieldCanEdit && !this._fieldIsInEdit) {
                    this.context.trigger('field:editable:click', this);
                    this.setMode('edit');
                    if (_.isFunction(this.focus)) {
                        this.focus();
                    } else {
                        var $el = this.$(this.fieldTag + ':first');
                        $el.focus().val($el.val()).select();
                    }

                    if (this.type !== 'image') {
                        if (_.isFunction(this.bindKeyDown)) {
                            this.bindKeyDown(_.bind(this._fieldOnKeyDown, this));
                        } else {
                            this.$(this.fieldTag).on(
                                'keydown.record' + this.cid,
                                {field: this},
                                _.bind(this._fieldOnKeyDown, this)
                            );
                        }

                        $(document).on('mousedown.record' + this.cid, {field: this}, _.bind(this._fieldMouseClicked, this));
                    }

                    if (this.type === 'enum') {
                        this.model.once('change:' + this.name, function() {
                            _.defer(_.bind(function() {
                                this.setMode('list');
                            }, this));
                        }, this);
                    }

                    if (this.type === 'date') {
                        this.$el.closest('td').addClass('td-inline-edit');
                    }
                }
            },

            /**
             * Key Down Handler
             *
             * @param evt
             * @protected
             */
            _fieldOnKeyDown: function(evt) {
                this._fieldHandleKeyDown.call(this, evt, evt.data.field);
            },

            /**
             * Mouse Click Handler
             *
             * @protected
             */
            _fieldMouseClicked: _.debounce(function(evt) {
                this._fieldClose.call(this, evt, evt.data.field);
            }, 0),

            /**
             * Close out the field from a mouse click
             *
             * @param evt
             * @param field
             * @protected
             */
            _fieldClose: function(evt, field) {
                var currFieldParent = field.$el,
                    targetPlaceHolder = this.$(evt.target).parents('span[sfuuid="' + field.sfId + '"]'),
                    preventPlaceholder = this.$(evt.target).closest('.prevent-mousedown');

                // When mouse clicks the document, it should maintain the edit mode within the following cases
                // - Some fields (like email) may have buttons and the mousedown event will fire before the one
                //   attached to the button is fired. As a workaround we wrap the buttons with .prevent-mousedown
                // - If mouse is clicked within the same field placeholder area
                // - If cursor is focused among the field's input elements
                if (preventPlaceholder.length > 0 || targetPlaceHolder.length > 0
                    || currFieldParent.find(':focus').length > 0 || !_.isEmpty(app.drawer._components)) {
                    return;
                }

                if (this._fieldIsErrorState) {
                    this.$(this.fieldTag).focus().select();
                    return;
                }

                this.setMode('list');
            },

            /**
             * Logic behind a key down event
             *
             * @param evt
             * @param field
             * @protected
             */
            _fieldHandleKeyDown: function(evt, field) {
                // if the field is already disposed, just ignore this bit of code.
                if (field.disposed) {
                    return;
                }

                if (evt.which === 27) { // esc
                    this.setMode('list');
                } else if (evt.which === 13) { // enter
                    if (this._fieldValueChanged(field)) {
                        this.model.once('change:' + field.name, function() {
                            this.setMode('list');
                        }, this);
                    } else {
                        this.setMode('list');
                    }
                }
            },

            /**
             * Check if the field value changed
             *
             * @param field
             * @returns {boolean}
             * @protected
             */
            _fieldValueChanged: function(field) {
                // get the field value
                var elVal = field.$(field.fieldTag).val();

                if (field.type === 'currency' || field.type === 'int') {
                    elVal = this._fieldParsePercentage(elVal, (field.type === 'currency') ? undefined : 0);
                }

                if (field.type === 'currency') {
                    // for currency we want to make sure the value didn't actually change so get the difference
                    // and multiple it by 100 (2 decimals out), if it's not equal to 0, then it changed.
                    var diff = Math.abs(this.unformat(elVal) - this.unformat(field.value));
                    return ((Math.round(diff * 100)) != 0)
                } else {
                    if (field.type === 'date') {
                        if (_.isUndefined(elVal)) {
                            elVal = field.$('div').html();
                        }
                        return !_.isEqual(this.unformat(elVal), this.unformat(field.value));
                    } else {
                        return !_.isEqual(this.unformat(elVal), this.unformat(field.value));
                    }
                }
            },

            /**
             * Main validate method.
             *
             * @param field
             * @param newValue
             * @returns {*}
             * @protected
             */
            _fieldDoValidate: function(field, newValue) {
                var fieldType = field.type || field.data('type');

                if (_.isUndefined(newValue) || _.isEmpty(newValue)) {
                    // try to get the value again
                    if (_.isUndefined(this.fieldTag)) {
                        return false;
                    }
                    newValue = this.$(this.fieldTag).val();
                }

                if (fieldType === 'int') {
                    // check for percentages
                    newValue = this._fieldParsePercentage(newValue, 0);
                    if (this._fieldVerifyIntValue(newValue)) {
                        return newValue;
                    }
                } else {
                    if (fieldType === 'currency') {
                        newValue = this._fieldParsePercentage(newValue);
                        if (this._fieldVerifyCurrencyValue(newValue)) {
                            return newValue;
                        }
                    } else {
                        if (fieldType === 'date') {
                            var dateFormat = app.date.convertFormat(app.user.getPreference('datepref'));

                            if (dateFormat) {
                                if (app.date(newValue, [dateFormat], true).isValid()) {
                                    return newValue;
                                }
                            } else {
                                // revert to simple date checking if no preference exists
                                if (app.date(newValue).isValid()) {
                                    return newValue;
                                }
                            }
                        } else {
                            return newValue;
                        }
                    }
                }

                return false;
            },

            /**
             * Verify a currency value
             *
             * @param value
             * @returns {boolean}
             * @protected
             */
            _fieldVerifyCurrencyValue: function(value) {
                // trim off any whitespace
                value = value.toString().trim();

                // matches a valid positive decimal number
                var config = app.metadata.getConfig(),
                    decSep = app.user.getPreference('decimal_separator') || config.defaultDecimalSeparator || '.',
                    groupSep = app.user.getPreference('number_grouping_separator')
                        || config.defaultNumberGroupingSeparator || ',',
                    currency = app.user.getPreference('currency_id') || app.currency.getBaseCurrencyId(),
                    currency_symbol = app.currency.getCurrencySymbol(currency),
                    regex = new RegExp('^(' + this._fieldEscapeRegexChar(currency_symbol) + ')?(([\\d]{1,3}(?:'
                        + this._fieldEscapeRegexChar(groupSep) + '?[\\d]{3})*)?((?:'
                        + this._fieldEscapeRegexChar(decSep) + '[\\d]+)))'),
                    parts = value.match(regex),
                    isValid = true;

                // always make sure that we have a string here, since match only works on strings
                // make sure that value has a length, that the patch parts are not null, that parts[0] is not empty
                // and that the parts[0] is equal to what was passed in. in some cases it wont match and we should not
                // allow that value to be used
                if (value.length === 0 || _.isNull(parts) || _.isEmpty(parts[0]) || parts[0] != value) {
                    isValid = false;
                }

                return isValid;
            },

            /**
             * Utility Method to only escape the values that need to be escaped for a RegularExpression
             *
             * @param {String} character
             * @returns {String}
             * @protected
             */
            _fieldEscapeRegexChar: function(character) {
                var needs_escape = [
                    '.', '\\', '+', '*', '?', '[', '^', ']', '$',
                    '(', ')', '{', '}', '=', '!', '<', '>', '|', ':', '-'
                ];

                if (_.indexOf(needs_escape, character) != -1) {
                    character = '\\' + character;
                }

                return character;
            },

            /**
             * Verify an Int Value
             * @param value
             * @returns {*}
             * @protected
             */
            _fieldVerifyIntValue: function(value) {
                var regex = new RegExp('^\\d+$'),
                    match = value.toString().match(regex);

                // always make sure that we have a string here, since match only works on strings
                if (_.isNull(match)) {
                    return false;
                }
                var defValidation = this.def.validation,
                    isValid = true;

                if (defValidation && defValidation.type === 'range' && !_.isUndefined(defValidation.min)
                    && !_.isUndefined(defValidation.max) && (value < defValidation.min || value > defValidation.max)) {
                    // def.validation exists, this is a range validation, there's a min and max specified
                    // and the value is currently below min or above max so this is not a valid value
                    isValid = false;
                }

                return isValid;
            },

            /**
             * Check the value to see if it's a percentage, if it is, then adjust the value
             *
             * @param {String} value The value we are parsing.
             * @param {number} (decimals) How far to round to.
             * @return {*}
             */
            _fieldParsePercentage: function(value, decimals) {
                var orig = this.model.get(this.name),
                    config = app.metadata.getConfig(),
                    decSep = app.user.getPreference('decimal_separator') || config.defaultDecimalSeparator || '.',
                    groupSep = app.user.getPreference('number_grouping_separator')
                        || config.defaultNumberGroupingSeparator || ',',
                    regex = new RegExp('^([+-])(([\\d]{1,3}(?:' + this._fieldEscapeRegexChar(groupSep)
                        + '[\\d]{3})*|(?:[\\d]))*((?:' + this._fieldEscapeRegexChar(decSep) + '[\\d]+))?)(\\%)?'),
                    parts = value.toString().match(regex),
                    isPercent = (parts && parts[5] === '%'),
                    isAddition = (parts && parts[1] === '+'),
                    isSubtraction = (parts && parts[1] === '-'),
                    amount;

                // if we have parts and the addition is not zero (0), if it happens to be zero it's from an input
                // like this +0,5 when you have , as your grouping and . as your decimal
                // there is a test that covers this use case in the ForecastWorksheet/currency field test
                // we also want to make sure that parts[0] is equal to the value that was passed in,
                // if it's not the same, we need to ignore this as it didn't match the full string
                if (parts && parts[0] === value && parts[2] != '0') {
                    // use original number to apply calculations
                    amount = this.unformat(parts[2]);
                    if (isPercent) {
                        // percentage calculation
                        value = app.math.mul(app.math.div(amount, 100), orig);
                    } else {
                        // add/sub calculation
                        value = amount;
                    }
                    if (isAddition) {
                        value = app.math.add(orig, value);
                    } else {
                        if (isSubtraction) {
                            value = app.math.sub(orig, value);
                        }
                    }
                    value = app.math.round(value, decimals);
                }
                return this.format(value.toString());
            },

            /**
             * overridden from date.js -- Forecasts must validate date before setting the model
             * whereas the base date.js field sets the model, then does validation when you save
             *
             * @inheritdoc
             * @override
             */
            handleHideDatePicker: function() {
                var $field = this.$(this.fieldTag),
                    value = $field.val();

                /**
                 * flag for if we've already processed the hide event. Setting the mode can trigger another
                 * hide event, which causes infinite loops in the UI.  This is bad.
                 */
                if (this.isHiding) {
                    this.isHiding = false;
                    return true;
                }

                this.isHiding = true;
                // if new value is empty, revert it back to the previous value
                // to be compliant with Forecasts requisites
                if (_.isEmpty(value)) {
                    $field.val(this.format());
                    return;
                }

                value = this.unformat(value);

                if (!_.isEmpty(value)) {
                    this.model.set(this.name, value);

                    // trigger the onBlur function to set the field back to list view and render
                    this.setMode('list');
                    return;
                }
            },

            /**
             * @inheritdoc
             */
            bindDomChange: function() {
                // only need to override this for the field
                if(this.isView) {
                    return this._super('bindDomChange');
                }

                if (!(this.model instanceof Backbone.Model)) {
                    return;
                }

                this._fieldBindDomChange();
            },

            /**
             * @inheritdoc
             */
            clearErrorDecoration: function() {
                this._fieldIsErrorState = false;

                var ctx = this.context.parent || this.context;
                // trigger field error on context
                ctx.trigger('field:error', this, false);
                this._fieldErrorMessage = '';
            },

            /**
             * @inheritdoc
             */
            setMode: function(name) {
                if (name === 'list') {
                    // remove handlers
                    this.$(this.fieldTag).off('keydown.record' + this.cid);
                    $(document).off('mousedown.record' + this.cid);
                    if (this.type === 'date') {
                        // remove the scrolling update handler for the date field
                        $('.main-pane, .flex-list-view-content').off('scroll.' + this.cid);
                    }
                }

                if (this._fieldIsErrorState) {
                    this.clearErrorDecoration();
                }

                // need to call Field's setMode since this plugin doesn't inherit from
                // Field so _super wouldn't work
                app.view.Field.prototype.setMode.call(this, name);

                this._fieldIsInEdit = (this.action === 'edit');

                if (this._fieldIsInEdit) {
                    // trigger the event
                    this.context.trigger('field:editable:open', this.cid);
                } else {
                    this.$el.removeClass('error');
                }

                if (this.action !== 'edit' && this.type === 'date') {
                    this.$el.closest('td').removeClass('td-inline-edit');
                }
            }
        });
    });
})(SUGAR.App);
