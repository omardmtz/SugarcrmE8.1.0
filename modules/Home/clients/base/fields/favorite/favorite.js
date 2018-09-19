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
 * @class View.Fields.Base.Dashboards.FavoriteField
 * @alias SUGAR.App.view.fields.DashboardsBaseFavoriteField
 * @extends View.Fields.Base.FavoriteField
 */
({
    // FIXME TY-1463 Remove this file.
    /**
     * Check first if the model exists before rendering.
     *
     * The dashboards currently reside in the Home module. The Home module does
     * not have favorites enabled. The dashboards do have favorites enabled.
     * In order to show the favorite icon on dashboards, we need to bypass
     * the favoritesEnabled check.
     *
     * @override
     * @private
     */
    _render: function() {
        // can't favorite something without an id
        if (!this.model.get('id')) {
            return null;
        }
        return app.view.Field.prototype._render.call(this);
    }
})
