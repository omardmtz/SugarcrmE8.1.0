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
 * @class View.Views.Base.HeaderpaneView
 * @alias SUGAR.App.view.views.BaseHeaderpaneView
 * @extends View.View
 */
({
    plugins: [
        'ErrorDecoration',
        'Editable'
    ],

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        this.meta = _.extend({}, app.metadata.getView(null, 'headerpane'), this.meta);

        /**
         * The label used for the title. This is the raw label.
         *
         * @deprecated 7.5 and will be removed in 7.7. We recommend to set the
         * title by defining a `fields` array, containing a field named `title`,
         * in the metadata. You should not define the title with `meta.title`.
         * Note that you can extend {@link #formatTitle} if the string used is a
         * template and you wish to pass a context.
         *
         * @type {string}
         * @private
         */
        this._title = this.meta.title;
        this.buttons = {};

        this.context.on('headerpane:title', function(title) {
            this._title = title;
            if (!this.disposed) this.render();
        }, this);

        //shortcut keys
        app.shortcuts.register({
            id: 'Headerpane:Cancel',
            keys: ['esc','mod+alt+l'],
            component: this,
            description: 'LBL_SHORTCUT_CLOSE_DRAWER',
            callOnFocus: true,
            handler: function() {
                var $cancelButton = this.$('a[name=cancel_button]'),
                    $closeButton = this.$('a[name=close]');

                if ($cancelButton.is(':visible') && !$cancelButton.hasClass('disabled')) {
                    $cancelButton.click();
                } else if ($closeButton.is(':visible') && !$closeButton.hasClass('disabled')) {
                    $closeButton.click();
                }
            }
        });
        app.shortcuts.register({
            id: 'Headerpane:Save',
            keys: ['mod+s','mod+alt+a'],
            component: this,
            description: 'LBL_SHORTCUT_RECORD_SAVE',
            callOnFocus: true,
            handler: function() {
                var $saveButton = this.$('a[name=save_button]');
                if ($saveButton.is(':visible') && !$saveButton.hasClass('disabled')) {
                    $saveButton.click();
                }
            }
        });

        this.adjustHeaderpane = _.debounce(this.adjustHeaderpane, 50);
        _.bindAll(this, 'adjustHeaderpane');
        $(window).on('resize.headerpane.' + this.cid, this.adjustHeaderpane);
        this.layout.on('headerpane:adjust_fields', this.adjustTitle, this);
    },

    /**
     * Adjusts the title's ellipsis max-width to match the ancestor title cell.
     */
    adjustTitle: function() {
        var $titleCell = this.$el.find('[data-name=title]');
        if ($titleCell) {
            var $ellipsisDiv = $titleCell.find('.ellipsis_inline');
            var width = $titleCell.css('max-width');
            $ellipsisDiv.css({'max-width': width});
        }
    },

    /**
     * @inheritdoc
     */
    _renderHtml: function() {
        /**
         * The title being rendered in the headerpane. This is the formatted
         * label.
         *
         * @deprecated 7.5 and will be removed in 7.7. We recommend to set the
         * title by defining a `fields` array, containing a field named `title`,
         * in the metadata. You should not define the title with `meta.title`.
         * Note that you can extend {@link #_formatTitle} if the string used is a
         * template and you wish to pass a context.
         *
         * @type {string}
         */
        this.title = !_.isUndefined(this._title) ? this._formatTitle(this._title) : this.title;
        // FIXME TY-1751 Move code that alters the metadata outside of _renderHtml
        this.meta.fields = _.map(this.meta.fields, function(field) {
            if (field.name === 'title') {
                field['formatted_value'] = this.title || this._formatTitle(field['default_value']);
            }
            return field;
        }, this);
        this._super('_renderHtml');
    },

    /**
     * Formats the title before being rendered.
     *
     * @param {string} title The unformatted title.
     * @return {string} The formatted title.
     * @protected
     */
    _formatTitle: function(title) {
        if (!title) {
            return '';
        }
        return app.lang.get(title, this.module);
    },

    /**
     * Adjust headerpane such that certain fields can be shown with ellipsis.
     */
    adjustHeaderpane: function() {
        this.setContainerWidth();
        this.adjustHeaderpaneFields();
    },

    /**
     * Adjust headerpane fields such that the first field is ellipsified and the last field
     * is set to 100% on view.  On edit, the first field is set to 100%.
     */
    adjustHeaderpaneFields: function() {
        var $ellipsisCell,
            ellipsisCellWidth,
            $recordCells;

        if (this.disposed) {
            return;
        }

        $recordCells = this.$('.headerpane h1').children('.record-cell, .btn-toolbar').get().reverse();

        if (($recordCells.length > 0) && (this.getContainerWidth() > 0)) {
            $ellipsisCell = $(this._getCellToEllipsify($recordCells));

            if ($ellipsisCell.length > 0) {
                if ($ellipsisCell.hasClass('edit')) {
                    // make the ellipsis cell widen to 100% on edit
                    $ellipsisCell.css({'width': '100%'});
                } else {
                    ellipsisCellWidth = this._calculateEllipsifiedCellWidth($recordCells, $ellipsisCell);
                    this._setMaxWidthForEllipsifiedCell($ellipsisCell, ellipsisCellWidth);
                }
            }
        }
        if (this.layout) {
            this.layout.trigger('headerpane:adjust_fields');
        }
    },

    /**
     * Adds the button corresponding to `buttonName` to the `buttons` object.
     *
     * @param {string} buttonName The name of the button.
     * @private
     */
    _registerFieldAsButton: function(buttonName) {
        var button = this.getField(buttonName);
        if (button) {
            this.buttons[buttonName] = button;
        }
    },

    /**
     * Returns a list of fields that are not button of the view.
     *
     * @private
     */
    _getNonButtonFields: function() {
        return _.filter(this.fields, _.bind(function(field) {
            if (field.name) {
                return !this.buttons[field.name];
            }

            return true;
        }, this));
    },

    /**
     * Uses {@link app.plugins.Editable} to
     *   set the internal property of {@link #editableFields}.
     */
    setEditableFields: function() {
        this.editableFields = this.getEditableFields(this._getNonButtonFields(), this.noEditFields || []);
    },

    /**
     * Registers fields as buttons as specified in the metadata.
     *
     * @protected
     */
    _setButtons: function() {
        if (this.meta && this.meta.buttons) {
            _.each(this.meta.buttons, function(button) {
                this._registerFieldAsButton(button.name);
            }, this);
        }
    },

    /**
     * Show/hide buttons depending on the state defined for each buttons in the
     * metadata.
     *
     * @param {string} state The {@link #STATE} of the current view.
     */
    setButtonStates: function(state) {
        this.currentState = state;

        _.each(this.buttons, function(field) {
            var showOn = field.def.showOn;
            if (_.isUndefined(showOn) || (showOn === state)) {
                field.show();
            } else {
                field.hide();
            }
        });

        this._toggleButtons(true);
    },

    /**
     * Enables or disables the action buttons that are currently shown on the
     * page. Toggles the `.disabled` class by default.
     *
     * @param {boolean} [enable=false] Whether to enable or disable the action
     *   buttons. Defaults to `false`.
     * @private
     */
    _toggleButtons: function(enable) {
        var state = !_.isUndefined(enable) ? !enable : false;

        _.each(this.buttons, function(button) {
            var showOn = button.def.showOn;
            if (_.isUndefined(showOn) || this.currentState === showOn) {
                button.setDisabled(state);
            }
        }, this);
    },

    /**
     * Get the width of the layout container
     */
    getContainerWidth: function() {
        return this._containerWidth;
    },

    /**
     * Set the width of the layout container
     */
    setContainerWidth: function() {
        this._containerWidth = this._getParentLayoutWidth(this.layout);
    },

    /**
     * Get the width of the parent layout that contains `getPaneWidth()`
     * method.
     *
     * @param {View.Layout} layout The parent layout.
     * @return {number} The parent layout width.
     * @private
     */
    _getParentLayoutWidth: function(layout) {
        if (!layout) {
            return 0;
        } else if (_.isFunction(layout.getPaneWidth)) {
            return layout.getPaneWidth(this);
        }

        return this._getParentLayoutWidth(layout.layout);
    },

    /**
     * Get the first cell for the field that can be ellipsified.
     * @param {jQuery} $cells
     * @return {jQuery}
     * @private
     */
    _getCellToEllipsify: function($cells) {
        var fieldTypesToEllipsify = ['fullname', 'name', 'text', 'base', 'enum', 'url',
            'dashboardtitle', 'label', 'drillthrough-labels'];

        return _.find($cells, function(cell) {
            return (_.indexOf(fieldTypesToEllipsify, $(cell).data('type')) !== -1);
        });
    },

    /**
     * Calculate the width for the cell that needs to be ellipsified.
     * @param {jQuery} $cells
     * @param {jQuery} $ellipsisCell
     * @return {number}
     * @private
     */
    _calculateEllipsifiedCellWidth: function($cells, $ellipsisCell) {
        var width = this.getContainerWidth();

        _.each($cells, function(cell) {
            var $cell = $(cell);

            if ($cell.is($ellipsisCell)) {
                width -= (parseInt($ellipsisCell.css('padding-left'), 10) +
                parseInt($ellipsisCell.css('padding-right'), 10));
            } else if ($cell.is(':visible')) {
                $cell.css({'width': 'auto'});
                width -= $cell.outerWidth();
            }
            $cell.css({'width': ''});
        });

        return width;
    },

    /**
     * Set the max-width for the specified cell.
     * @param {jQuery} $ellipsisCell
     * @param {number} width
     * @private
     */
    _setMaxWidthForEllipsifiedCell: function($ellipsisCell, width) {
        var ellipsifiedCell,
            fieldType = $ellipsisCell.data('type');

        if (fieldType === 'fullname' || fieldType === 'dashboardtitle') {
            ellipsifiedCell = this.getField($ellipsisCell.data('name'));
            width -= ellipsifiedCell.getCellPadding();
            ellipsifiedCell.setMaxWidth(width);
        } else {
            $ellipsisCell.css({'max-width': width});
        }
    },

    /**
     * @inheritdoc
     */
    _renderFields: function() {
        this._super('_renderFields');
        this.adjustHeaderpane();
    },

    /**
     * @inheritdoc
     */
    unbind: function() {
        this._super('unbind');
        $(window).off('resize.headerpane.' + this.cid);
        this.layout.off('headerpane:adjust_fields', this.adjustTitle);
    }
})
