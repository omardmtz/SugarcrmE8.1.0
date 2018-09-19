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
 * @class View.Fields.Base.SourceField
 * @alias SUGAR.App.view.fields.BaseSourceField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * @inheritdoc
     * Format source field based on what model is received.
     */
    format: function(value) {
        var subject = value ? value.subject : null;

        if (!subject) {
            return '';
        }

        // Try to create a link for the source field
        this.buildRoute(subject._module, subject.id);

        // When we receive full data (name & module & id), return name
        if (subject._module && subject.id && subject.name) {
            return subject.name;
        }

        // If data is incomplete, fall back to label
        var labelValue = this._getValidLabelValue(this.module, subject._type);

        if (labelValue) {
            return labelValue;
        }

        // If no label found, fall back to name
        if (subject.name) {
            return subject.name;
        }

        // Worst case scenario: try to display the id of the source or empty string
        return subject.id ? subject.id : '';
    },

    /**
     * Builds the route for the source model.
     * @param {string} module The module to link to.
     * @param {string} id The record id to link to.
     */
    buildRoute: function(module, id) {
        if (_.isUndefined(module) || _.isUndefined(id) || _.isEmpty(module)) {
            return;
        }

        var oldModule = module;
        if (module === 'Users') {
            module = 'Employees';
        }

        if (app.acl.hasAccess('view', oldModule)) {
            this.href = '#' + app.router.buildRoute(module, id);
        } else {
            this.href = undefined;
        }
    },

    /**
     * Dynamically generate label for model in case, and if the label is
     * defined return label value or null.
     * @param {Object} module Current audit module.
     * @param {string} type Source type.
     * @return {string|null} Label value or null if the label is not found.
     * @private
     */
    _getValidLabelValue: function(module, type) {
        var fullLabel = 'LBL_AUDIT_SUBJECT_' + type.toUpperCase();
        var labelValue =  app.lang.get(fullLabel, module);

        // If we get the same value from translate, return null
        return fullLabel === labelValue ? null : labelValue;
    }
})
