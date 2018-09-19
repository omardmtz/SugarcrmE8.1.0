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
 * Button to launch an external meeting
 *
 * @class View.Fields.Base.Meetings.LaunchbuttonField
 * @alias SUGAR.App.view.fields.BaseMeetingsLaunchbuttonField
 * @extends View.Fields.Base.RowactionField
 */
({
    extendsFrom: 'RowactionField',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);
        this.type = 'rowaction';
        this.isHost = (this.def.host === true);
    },

    /**
     * @inheritdoc
     *
     * Hide this button if:
     * - Status is not Planned
     * - Type is Sugar (not an external meeting type)
     * - Host button and user does not have permission to start the meeting
     */
    _render: function() {
        if (this.model.get('status') !== 'Planned' ||
            this.model.get('type') === 'Sugar' ||
            (this.isHost && !this._hasPermissionToStartMeeting())
        ) {
            this.hide();
        } else {
            this._setLabel();
            this._super('_render');
            this.show();
        }
    },

    /**
     * Check if the user has permission to host the external meeting
     * True if assigned user or an admin for Meetings
     *
     * @return {boolean}
     * @private
     */
    _hasPermissionToStartMeeting: function() {
        return (this.model.get('assigned_user_id') === app.user.id || app.acl.hasAccess('admin', 'Meetings'));
    },

    /**
     * Set the appropriate label for this field
     * Use the Start Meeting label for host
     * Use the Join Meeting label otherwise
     *
     * @private
     */
    _setLabel: function() {
        this.label = (this.isHost) ?
            this._getLabel('LBL_START_MEETING') :
            this._getLabel('LBL_JOIN_MEETING');
    },

    /**
     * Build the appropriate label based on the meeting type
     *
     * @param {string} labelName Meetings module label
     * @return {string}
     * @private
     */
    _getLabel: function(labelName) {
        var meetingTypeStrings = app.lang.getAppListStrings('eapm_list'),
            meetingType = meetingTypeStrings[this.model.get('type')] ||
                app.lang.get('LBL_MODULE_NAME_SINGULAR', this.module);

        return app.lang.get(labelName, this.module, {'meetingType': meetingType});
    },

    /**
     * Event to trigger the join/start of the meeting
     * Call the API first to get the host/join URL and determine if user has permission
     */
    rowActionSelect: function() {
        var url = app.api.buildURL('Meetings', 'external', {id: this.model.id});
        app.api.call('read', url, null, {
            success: _.bind(this._launchMeeting, this),
            error: function() {
                app.alert.show('launch_meeting_error', {
                    level: 'error',
                    messages: app.lang.get('LBL_ERROR_LAUNCH_MEETING_GENERAL', this.module)
                });
            }
        });
    },

    /**
     * Given the external meeting info retrieved from the API, launch the meeting
     * Display an error if user is not permitted to launch the meeting.
     *
     * @param {Object} externalInfo
     * @private
     */
    _launchMeeting: function(externalInfo) {
        var launchUrl = '';

        if (this.disposed) {
            return;
        }

        if (this.isHost && externalInfo.is_host_option_allowed) {
            launchUrl = externalInfo.host_url;
        } else if (!this.isHost && externalInfo.is_join_option_allowed) {
            launchUrl = externalInfo.join_url;
        } else {
            // user is not allowed to launch the external meeting
            app.alert.show('launch_meeting_error', {
                level: 'error',
                messages: app.lang.get(this.isHost ? 'LBL_EXTNOSTART_MAIN' : 'LBL_EXTNOT_MAIN', this.module)
            });
            return;
        }

        if (!_.isEmpty(launchUrl)) {
            window.open(launchUrl);
        } else {
            app.alert.show('launch_meeting_error', {
                level: 'error',
                messages: this._getLabel('LBL_EXTERNAL_MEETING_NO_URL')
            });
        }
    },

    /**
     * Re-render the join button when the model changes
     */
    bindDataChange: function() {
        if (this.model) {
            this.model.on('change', this.render, this);
        }
    }
})
