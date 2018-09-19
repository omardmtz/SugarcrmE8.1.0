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

({
    /**
     * status Widget.
     *
     * Extends from EnumField widget adding style property according to specific
     * status.
     */
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
            'draft': 'label-pending',
            'in-review': 'label-warning',
            'approved': 'label-info',
            'published': 'label-success',
            'expired': 'label'
        };

        this.type = 'badge-select';
    },

    /**
     * @inheritdoc
     */
    format: function(value) {
        if (this.action === 'edit') {
            var def = this.def.default ? this.def.default : value;
            value = (this.items[value] ? value : false) ||
            (this.items[def] ? def : false) ||
            value;
        }
        return this._super('format', [value]);
    }
})
