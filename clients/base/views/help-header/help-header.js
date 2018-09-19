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
 * View for managing the help component's header bar.
 *
 * @class View.Views.Base.HelpHeaderView
 * @alias SUGAR.App.view.views.BaseHelpHeaderView
 * @extends View.View
 */
({
    /**
     * @deprecated Since 7.9. Will be removed in 7.11.
     * Please use {@link View.Layouts.Base.HelpLayout.close} instead.
     */
    triggerClose: function() {
        app.logger.warn('The function `View.Layouts.Base.Help-HeaderView.triggerClose`' +
            ' is deprecated in 7.9.0.0 and will be removed in 7.11.0.0.' +
            'Please use `View.Layouts.Base.HelpLayout.close` instead.');
        app.events.trigger('app:help:toggle', false, this);
    }
})
