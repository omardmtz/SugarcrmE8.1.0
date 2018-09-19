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
(function(app) {

    /**
     * Tooltip Helper.
     *
     * Provides interface to work with tooltips in the entire app.
     *
     * @class Sugar.Tooltip
     * @alias SUGAR.App.tooltip
     * @singleton
     */
    app.augment('tooltip', {

        /**
         * Stores the current tooltip that is visible in the app.
         *
         * @property {?Element}
         * @private
         */
        _$currentTip: null,

        /**
         * Shows and hide tooltips using jquery event delegation.
         *
         * Tooltips are created dynamically based on `hover` event.
         * On touch devices tooltips are disabled.
         */
        init: function() {
            if (Modernizr.touch) {
                this._disable();
                return;
            }
            this._enable();
        },

        /**
         * Prevents showing tooltips on ellipsis inline when the tooltip is not
         * needed.
         */
        _onShow: function(event) {
            if (event.namespace !== 'bs.tooltip') {
                return;
            }
            var target = event.target;
            var $target = $(target);
            var showTooltip = ($target.attr('rel') === 'tooltip' || target.offsetWidth < target.scrollWidth);

            if (!showTooltip) {
                event.preventDefault();
            }
        },

        /**
         * When the tooltip is shown, store it for later disposal if the
         * element that caused this tooltip is removed from the DOM.
         *
         * TODO this should be replaced by `inserted.bs.tooltip` once we
         * upgrade to latest version of the tooltip library.
         */
        _saveTip: function(event) {
            if (event.namespace !== 'bs.tooltip') {
                return;
            }
            var $target = $(event.target);
            this._$currentTip = $target.data('bs.tooltip').tip();
        },

        /**
         * Disable tooltips in the entire app. This is useful for debugging on
         * touch devices or for unit testing.
         *
         * Use {@link #_enable} to turn the tooltips on again.
         *
         * @private
         */
        _disable: function() {

            var $html = $('html');
            $html.tooltip('destroy');

            $html.off('.tooltip');

            /**
             * Original tooltip plugin backup (if not backed up already)
             */
            if (!this._tooltip) {
                this._tooltip = $.fn.tooltip;
            }

            $.fn.tooltip = function() {
                return this;
            };
        },

        /**
         * Enables the tooltip plugin if previously disabled. This is useful
         * for debugging on touch devices or for unit testing.
         *
         * Use {@link #_disable} to turn the tooltips off.
         *
         * @private
         */
        _enable: function() {
            $.fn.tooltip = this._tooltip || $.fn.tooltip;

            var $html = $('html');
            $html.tooltip({
                selector: '.ellipsis_inline, [rel=tooltip]',
                container: 'body',
                trigger: 'hover'
            });

            _.bindAll(this, '_saveTip', 'clear');

            $html.on('show.bs.tooltip', this._onShow);
            $html.on('shown.bs.tooltip', this._saveTip);

            /*
             * Dispose any tooltip that might be showing when we click anywhere
             * in the DOM. This preserves old functionality.
             * This also saves us from some layouts/views/fields being disposed
             * and not calling {@link #clear}
             */
            $html.on('click.tooltip', this.clear);
        },

        /**
         * Clears any tooltips that might be shown in the app.
         *
         * This is useful for components to clear any tooltips after render:
         *     this.on('render', app.tooltip.clear);
         *
         * Currently it supports only the last tooltip shown, because the
         * implementation only allows 1 tooltip to be shown at a time.
         * Later this function can provide a clear of all tooltips if we allow
         * having more than 1 showing at the same time.
         */
        clear: function() {
            if (this._$currentTip) {
                this._$currentTip.detach();
            }
        }
    });

})(SUGAR.App);
