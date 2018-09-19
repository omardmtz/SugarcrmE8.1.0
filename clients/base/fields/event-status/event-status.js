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
 * EventStatusField is a field for Meetings/Calls that show the status field of the model as a badge field.
 *
 * FIXME: This component will be moved out of clients/base folder as part of MAR-2274 and SC-3593
 *
 * @class View.Fields.Base.EventStatusField
 * @alias SUGAR.App.view.fields.BaseEventStatusField
 * @extends View.Fields.Base.BadgeSelectField
 */
({
    extendsFrom: 'BadgeSelectField',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        /**
         * An object where its keys map to specific status and color to matching
         * CSS classes.
         */
        this.statusClasses = {
            'Held': 'label-success',
            'Not Held': 'label-important',
            'Planned': 'label-pending'
        };

        this.type = 'badge-select';
    },

    /**
     * @inheritdoc
     */
    _loadTemplate: function() {
        var action = this.action || this.view.action;
        if (action === 'edit') {
            this.type = 'enum';
        }

        this._super('_loadTemplate');
        this.type = 'badge-select';
    }
})
