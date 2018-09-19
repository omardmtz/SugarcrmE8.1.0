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
 * @class View.Views.Base.DupecheckListEditView
 * @alias SUGAR.App.view.views.BaseDupecheckListEditView
 * @extends View.Views.Base.DupecheckListView
 */
({
    extendsFrom: 'DupecheckListView',
    additionalTableClasses: 'duplicates-selectedit',

    addActions: function() {
        if (this.actionsAdded) return;
        this._super('addActions');

        var firstRightColumn = this.rightColumns[0];
        if (firstRightColumn && _.isArray(firstRightColumn.fields)) {
            //Prepend Select and Edit action
            firstRightColumn.fields.unshift({
                type: 'rowaction',
                label: 'LBL_LISTVIEW_SELECT_AND_EDIT',
                css_class: 'btn btn-invisible btn-link ellipsis_inline',
                tooltip: 'LBL_LISTVIEW_SELECT_AND_EDIT',
                event: 'list:dupecheck-list-select-edit:fire'
            });
            this.rightColumns[0] = firstRightColumn;
        }
        this.actionsAdded = true;
    }
})
