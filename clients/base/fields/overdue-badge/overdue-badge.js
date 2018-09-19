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
 * EventStatusField is a field for Meetings/Calls that shows a date field as a badge when event is overdue
 *
 * FIXME: This component will be moved out of clients/base folder as part of MAR-2274 and SC-3593
 *
 * @class View.Fields.Base.OverdueBadgeField
 * @alias SUGAR.App.view.fields.BaseOverdueBadgeField
 * @extends View.Fields.Base.BaseField
 */
({
    _render: function() {
        var now = new Date(),
            due_date = this.model.get(this.name),
            date = new Date(due_date);
        this.model.set('overdue', !_.isNull(due_date) && date < now);
        this._super('_render');
    }
})
