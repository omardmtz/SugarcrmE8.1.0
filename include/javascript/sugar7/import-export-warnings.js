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

(function (app) {

    app.events.on("app:login:success", function () {
        app.cache.set("show_project_import_warning", true);
        app.cache.set("show_project_export_warning", true);
        app.cache.set("show_br_import_warning", true);
        app.cache.set("show_br_export_warning", true);
        app.cache.set("show_emailtpl_import_warning", true);
        app.cache.set("show_emailtpl_export_warning", true);
    });

})(SUGAR.App);