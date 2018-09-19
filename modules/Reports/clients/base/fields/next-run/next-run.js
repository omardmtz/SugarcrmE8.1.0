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
 * This field is to open a pop up scheduler when clicking on Schedule Report
 *
 * @class View.Fields.Base.Reports.NextRunField
 * @alias SUGAR.App.view.fields.BaseReportsNextRunField
 * @extends View.Fields.Base.DatetimecomboField
 */
({

    extendsFrom: 'DatetimecomboField',

    events: {
        'click a': 'openScheduler'
    },

    /**
     * Opens the old BWC schedule report popup
     */
    openScheduler: function() {

        // Temp fix for when session cookie isn't set
        // FIXME this will be removed when we move scheduler to sidecar
        var openScheduler = function() {
            window.open(
                'index.php?module=Reports&action=add_schedule&to_pdf=true&id=' + this.model.id,
                'test',
                'width=400,height=250,resizable=1,scrollbars=1'
            );
        };
        app.bwc.login(null, _.bind(openScheduler, this));
    }
})
