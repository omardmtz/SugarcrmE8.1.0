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
    extendsFrom: 'Htmleditable_tinymceField',
    /**
     * Sets the content displayed in the non-editor view
     *
     * @param {String} value Sanitized HTML to be placed in view
     */
    setViewContent: function(value) {
        var editable = this._getHtmlEditableField();
        if (this.action == 'list') {
            // Strip HTML tags for ListView.
            value = $('<div/>').html(value).text();
        }
        if (!editable) {
            return;
        }
        if (!_.isUndefined(editable.get(0)) && !_.isEmpty(editable.get(0).contentDocument)) {
            if (editable.contents().find('body').length > 0) {
                editable.contents().find('body').html(value);
            }
        } else {
            editable.html(value);
        }
    }
});
