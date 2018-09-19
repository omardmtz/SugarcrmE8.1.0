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
 * @class View.Views.Base.Calls.ResolveConflictsListView
 * @alias SUGAR.App.view.views.BaseCallsResolveConflictsListView
 * @extends View.Views.Base.ResolveConflictsListView
 */
({
    extendsFrom: 'ResolveConflictsListView',

    /**
     * @inheritdoc
     *
     * The invitees field should not be displayed on list views. It is removed
     * before comparing models so that it doesn't get included.
     */
    _buildFieldDefinitions: function(modelToSave, modelInDb) {
        modelToSave.unset('invitees');
        this._super('_buildFieldDefinitions', [modelToSave, modelInDb]);
    }
})
