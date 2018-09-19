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
 * ClosebuttonField is a field for Meetings/Calls/Tasks that handles setting a value on a field in the model based on meta data with
 * an option to create a new record
 *
 * FIXME: This component will be moved out of clients/base folder as part of MAR-2274 and SC-3593
 *
 * @class View.Fields.Base.ClosebuttonField
 * @alias SUGAR.App.view.fields.BaseClosebuttonField
 * @extends View.Fields.Base.RowactionField
 */


({
    extendsFrom: 'RowactionField',

    /**
     * Setup click event handlers.
     * @inheritdoc
     *
     * @param {Object} options
     */
    initialize: function(options) {
        this.events = _.extend({}, this.events, options.def.events, {
            'click [name="record-close"]': 'closeClicked',
            'click [name="record-close-new"]': 'closeNewClicked'
        });

        this._super('initialize', [options]);
        this.type = 'rowaction';
    },

    /**
     * Handle record close event.
     *
     * @param {Event} event The click event for the close button
     */
    closeClicked: function(event) {
        this._close(false);
    },

    /**
     * Handle record close and create new event.
     *
     * @param {Event} event The click event for the close and create new button
     */
    closeNewClicked: function(event) {
        this._close(true);
    },

    /**
     * @inheritdoc
     *
     * Button should be hidden if record displayed is already closed
     */
    _render: function() {
        if (this.model.get(this.getStatusFieldName()) === this.getClosedStatus()) {
            this.hide();
        } else {
            this._super('_render');
        }
    },

    /**
     * Retrieve the closed status value from the fields meta definition
     *
     * @return {string}
     */
    getClosedStatus: function() {
        return ((this.def && this.def.closed_status) ?
            this.def.closed_status :
            'Completed');
    },

    /**
     * Retrieve the status field name from the field meta definition.
     * Defaults to 'status'
     *
     * @return {string}
     */
    getStatusFieldName: function() {
        return ((this.def && this.def.status_field_name) ?
            this.def.status_field_name :
            'status');
    },

    /**
     * Close the record by setting the appropriate status on the record.
     *
     * @param {boolean} createNew Flag for whether to open a new drawer to create a
     *   record after close.
     * @private
     */
    _close: function(createNew) {
        var self = this;

        this.model.set(this.getStatusFieldName(), this.getClosedStatus());
        this.model.save({}, {
            success: function() {
                self.showSuccessMessage();
                if (createNew) {
                    self.openDrawerToCreateNewRecord();
                }
            },
            error: function(model, error) {
                self.showErrorMessage();
                app.logger.error('Record failed to close. ' + error);

                // we didn't save, revert!
                self.model.revertAttributes();
            }
        });
    },

    /**
     * Open a drawer to create a new record.
     */
    openDrawerToCreateNewRecord: function() {
        var self = this,
            statusField = this.getStatusFieldName(),
            module = app.metadata.getModule(this.model.module),
            prefill = app.data.createBean(this.model.module);

        prefill.copy(this.model);

        if (module.fields[statusField] && module.fields[statusField]['default']) {
            prefill.set(statusField, module.fields[statusField]['default']);
        } else {
            prefill.unset(statusField);
        }

        app.drawer.open({
            layout: 'create',
            context: {
                create: true,
                model: prefill
            }
        }, function() {
            if (self.parent) {
                self.parent.render();
            } else {
                self.render();
            }
        });
    },

    /**
     * Display a success message.
     *
     * This message includes the value the status field was set to - so we need
     * to retrieve the translated string (if there is one).
     */
    showSuccessMessage: function() {
        var statusField = this.getStatusFieldName(),
            statusFieldMetadata = app.metadata.getModule(this.module).fields[statusField],
            optionStrings,
            statusValue;

        // if this is an enum field, retrieve translated value
        if (statusFieldMetadata && statusFieldMetadata.options) {
            optionStrings = app.lang.getAppListStrings(statusFieldMetadata.options);
            statusValue = optionStrings[this.getClosedStatus()].toLocaleLowerCase();
        } else {
            // not an enum field - just display lowercase version of the value
            statusValue = this.getClosedStatus().toLocaleLowerCase();
        }

        app.alert.show('status_change_success', {
            level: 'success',
            autoClose: true,
            messages: app.lang.get('TPL_STATUS_CHANGE_SUCCESS',
                this.module,
                {
                    moduleSingular: app.lang.getModuleName(this.module),
                    status: statusValue
                }
            )
        });
    },

    /**
     * Display an error message.
     */
    showErrorMessage: function() {
        app.alert.show('close_record_error', {
            level: 'error',
            title: app.lang.get('ERR_AJAX_LOAD')
        });
    },

    /**
     * Re-render the field when the status on the record changes.
     */
    bindDataChange: function() {
        if (this.model) {
            this.model.on('change:status', this.render, this);
        }
    }
})
