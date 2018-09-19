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

$(document).ready(function() {
    var currentUrl = app.router.getFragment();
    var cid = 'tba';
    // use this variable to prevent unsaved changes warnings while saving
    var savingConfiguration = false;
    var backUrl = "#bwc/index.php?module=Administration&action=index";
    var getEnabledModules = function() {
            var moduleList = [];
            $.each($('input[data-group=tba_em]:checked'), function(index, item) {
                moduleList.push($(item).val());
            });
            return moduleList;
        };
    var saveConfiguration = function(isTBAEnabled, enabledModulesList) {
            ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVING'));

            var queryString = SUGAR.util.paramsToUrl({
                    module: 'Teams',
                    action: 'savetbaconfiguration',
                    enabled: isTBAEnabled,
                    enabled_modules: enabledModulesList,
                    csrf_token: SUGAR.csrf.form_token
                }) + 'to_pdf=1';

            savingConfiguration = true;

            $.ajax({
                url: 'index.php',
                data: queryString,
                type: 'POST',
                dataType: 'json',
                timeout: 300000,
                success: function(response) {
                    unbindUnsavedChangesListeners();
                    ajaxStatus.flashStatus(SUGAR.language.get('app_strings', 'LBL_DONE_BUTTON_LABEL'));
                    if (response['status'] === false) {
                        ajaxStatus.showStatus(response.message);
                    }
                    app.router.navigate(backUrl, {trigger: true});
                },
                error: function() {
                    savingConfiguration = false;
                    bindUnsavedChangesListeners();
                    ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'ERR_GENERIC_SERVER_ERROR'));
                }
            });
        };
    var initState = {
            isTBAEnabled: $('input#tba_set_enabled').attr('checked') === 'checked',
            enabledModules: getEnabledModules()
        };

    /**
     * Returns true if there are unsaved changes.
     *
     * @returns {Boolean}
     */
    var hasUnsavedChanges = function() {
        var enabledModules = getEnabledModules();
        var isTBAEnabled = $('input#tba_set_enabled').attr('checked') === 'checked';

        return initState.isTBAEnabled !== isTBAEnabled
            || !_.isEqual(enabledModules, initState.enabledModules);
    };

    /**
     * Shows the popup dialog message if necessary to confirm the unsaved changes.
     *
     * @param {Function} onConfirmRoute
     * @returns {Boolean}
     */
    var warnUnsavedChanges = function(onConfirmRoute) {
        if (!hasUnsavedChanges()) {
            return true;
        }

        // replace the url hash back to the current staying page
        app.router.navigate(currentUrl, {trigger: false, replace: true});

        app.alert.show('leave_confirmation', {
            level: 'confirmation',
            messages: app.lang.get('LBL_WARN_UNSAVED_CHANGES'),
            onConfirm: onConfirmRoute || $.noop,
            onCancel: $.noop
        });

        return false;
    };

    /**
     * Binds listeners for route change or reload page events.
     *
     * for more details about warning message for unsaved changes.
     * see sugarcrm/include/javascript/sugar7/plugins/Editable.js
     */
    var bindUnsavedChangesListeners = function() {
        app.routing.before('route', beforeRouteChange);
        $(window).on('beforeunload.' + cid, warnUnsavedChangesOnRefresh);
    };

    /**
     * Popup browser dialog message to confirm the unsaved changes.
     */
    var warnUnsavedChangesOnRefresh = function() {
        if (!hasUnsavedChanges()) {
            return;
        }

        return app.lang.get('LBL_WARN_UNSAVED_CHANGES');
    };

    /**
     * Removes unsaved changes listeners.
     */
    var unbindUnsavedChangesListeners = function() {
        app.routing.offBefore('route', beforeRouteChange);
        $(window).off('beforeunload.' + cid);
    };

    /**
     * Continue navigating target location once user confirms the discard changes.
     *
     * @param {Object} params Parameters that is passed from caller.
     */
    var onConfirmRoute = function(params) {
        unbindUnsavedChangesListeners();
        if (currentUrl === params.targetUrl) {
            app.router.refresh();
        } else {
            app.router.navigate(params.targetUrl, {trigger: true});
        }
    };

    /**
     * Pre-event handler before current router is changed.
     *
     * Pass `onConfirmRoute` as callback to continue navigating after confirmation.
     *
     * @param {Object} args Arguments that is passed from caller.
     * @return {Boolean} True only if it contains unsaved changes.
     */
    var beforeRouteChange = function(args) {
        var params = args || {};
        params.targetUrl = app.router.getFragment();
        var onConfirm = _.bind(onConfirmRoute, {}, params);
        return warnUnsavedChanges(onConfirm);
    };

    $('input[data-group=tba_em]').on('click', function(e) {
        var $td = $(this).closest('td');
        if ($td.hasClass('active')) {
            $td.removeClass('active');
        } else {
            $td.addClass('active');
        }
    });

    if ($('input#tba_set_enabled').attr('checked') === 'checked') {
        $('#tba_em_block').show();
    } else {
        $('#tba_em_block').hide();
    }

    $('input#tba_set_enabled').on('click', function() {
        if ($(this).attr('checked') === 'checked') {
            var enabledModules = getEnabledModules();
            _.each($('input[data-group=tba_em]'), function(item) {
                if (_.indexOf(enabledModules, $(item).val()) !== -1) {
                    $(item).attr('checked', 'checked');
                }
            });
            $('#tba_em_block').show();
        } else {
            $('#tba_em_block').hide();
        }
    });

    $('input[name=save]').on('click', function() {
        var enabledModules = getEnabledModules();
        var isTBAEnabled = $('input#tba_set_enabled').attr('checked') === 'checked';

        if ((initState.isTBAEnabled && $('input#tba_set_enabled').attr('checked') !== 'checked') ||
            _.difference(initState.enabledModules, enabledModules).length > 0) {
            app.alert.show('submit_tba_confirmation', {
                level: 'confirmation',
                messages: app.user.get('type') == 'admin'
                    ? SUGAR.language.get('Teams', 'LBL_TBA_CONFIGURATION_WARNING')
                    : SUGAR.language.get('Teams', 'LBL_TBA_CONFIGURATION_WARNING_NO_ADMIN')
                ,
                onConfirm: function() {
                    saveConfiguration(isTBAEnabled, enabledModules);
                }
            });
        } else {
            saveConfiguration(isTBAEnabled, enabledModules);
        }
    });

    $('input[name=cancel]').on('click', function() {
        unbindUnsavedChangesListeners();
        app.router.navigate(backUrl, {trigger: true});
    });

    bindUnsavedChangesListeners();
});
