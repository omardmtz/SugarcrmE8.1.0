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
 * @class View.Views.Base.MassaddtolistProgressiew
 * @alias SUGAR.App.view.views.BaseMassaddtolistProgressView
 * @extends View.Views.Base.MassupdateProgressView
 */
({
    extendsFrom: 'MassupdateProgressView',

    /**
     * Set of labels.
     *
     * @property
     */
    _labelSet: {
        'update': {
            PROGRESS_STATUS: 'TPL_MASSADDTOLIST_PROGRESS_STATUS',
            DURATION_FORMAT: 'TPL_MASSADDTOLIST_DURATION_FORMAT',
            FAIL_TO_ATTEMPT: 'TPL_MASSADDTOLIST_FAIL_TO_ATTEMPT',
            WARNING_CLOSE: 'TPL_MASSADDTOLIST_WARNING_CLOSE',
            WARNING_INCOMPLETE: 'TPL_MASSADDTOLIST_WARNING_INCOMPLETE',
            SUCCESS: 'TPL_MASSADDTOLIST_SUCCESS',
            TITLE: 'TPL_MASSADDTOLIST_TITLE'
        }
    }

})
