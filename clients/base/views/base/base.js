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
 * The Base View that all Views should extend from before extending
 * {@link #View.View}.
 *
 * Use this controller to specify your customizations for the Base platform.
 * This should contain any special override that only applies to Base platform
 * and not to Sidecar's library.
 *
 * Any View in a module can skip the default fallback and extend this one
 * directly. In your `BaseModuleMyView` component that lives in the file
 * `modules/<module>/clients/base/views/my-view/my-view.js`, you can
 * directly extend the `BaseView` skipping the normal extend flow which will
 * extend automatically from `BaseMyView` that might live in
 * `clients/base/views/my-view/my-view.js`. Simply define your controller
 * with:
 *
 * ```
 * ({
 *     extendsFrom: 'BaseView',
 *     // ...
 * })
 * ```
 *
 * This controller exists to force the component to be created and not fallback
 * to the default flow (which happens when the component isn't found).
 *
 * @class View.Views.Base.BaseView
 * @alias SUGAR.App.view.views.BaseBaseView
 * @extends View.View
 */
({
    /**
     * The Base View will always clear any tooltips after `render`.
     */
    initialize: function() {
        this._super('initialize', arguments);
        if (app.tooltip) {
            this.on('render', app.tooltip.clear);
        }
    }
})
