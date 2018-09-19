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
    app.events.on('app:init', function() {
        app.plugins.register('Chart', ['view'], {

            chart_loaded: false,
            chartCollection: null,
            chart: null,
            total: 0,

            /**
             * Attach code for when the plugin is registered on a view or layout
             *
             * @param component
             * @param plugin
             */
            onAttach: function(component, plugin) {
                this.on('init', function() {
                    if (this.meta.config) {
                        return;
                    }
                    // This event fires when a preview is closed.
                    // We listen to this event to call the chart resize method
                    // in case the window was resized while the preview was open.
                    app.events.on('preview:close', function() {
                        if (_.isUndefined(app.drawer) || app.drawer.isActive(this.$el)) {
                            this.resize();
                        }
                    }, this);
                    // This event fires when the dashlet is collapsed or opened.
                    // We listen to this event to call the chart resize method
                    // in case the window was resized while the dashlet was closed.
                    this.layout.on('dashlet:collapse', function(collapse) {
                        if (!collapse) {
                            this.resize();
                        }
                    }, this);
                    // This event fires when the dashlet is dragged and dropped.
                    // We listen to this event to call the chart resize method
                    // because the size of the dashlet can change in the dashboard.
                    this.layout.context.on('dashlet:draggable:stop', function() {
                        this.resize();
                    }, this);
                    // Resize chart on window resize.
                    // This event also fires when the sidebar is collapsed or opened.
                    // We listen to this event to call the chart resize method
                    // in case the window was resized while the sidebar was closed.
                    $(window).on('resize.' + this.cid, _.debounce(_.bind(this.resize, this), 100));
                    // Resize chart on print.
                    this.handlePrinting('on');

                    // If the chartResize method is not defined in the component
                    // set it to the default method below
                    if (!_.isFunction(this.chartResize)) {
                        this.chartResize = this._chartResize;
                    }
                    // If the hasChartData method is not defined in the component
                    // set it to the default method below
                    if (!_.isFunction(this.hasChartData)) {
                        this.hasChartData = this._hasChartData;
                    }
                }, this);

                this.on('render', function() {
                    // This on click event is required to dismiss the dropdown legend
                    this.$('.sc-chart').on('click', _.bind(function() {
                        this.chart.dispatch.call('chartClick', this)
                    }, this));
                    this.renderChart();
                }, this);
            },

            /**
             * A default function for determining if chart has data.
             * Can be overridden in views by defining hasChartData method.
             */
            _hasChartData: function() {
                return this.total !== 0;
            },

            /**
             * A default function for calling the chart update method.
             * Can be overridden in views by defining chartResize method.
             */
            _chartResize: function() {
                this.chart.update();
            },

            /**
             * Destroy tooltips on dispose.
             */
            onDetach: function() {
                if (this.meta.config) {
                    return;
                }
                if (this.layout) {
                    this.layout.off(null, null, this);
                }
                if (this.layout && this.layout.context) {
                    this.layout.context.off(null, null, this);
                }
                $(window).off('resize.' + this.cid);
                this.handlePrinting('off');
            },

            /**
             * Checks to see if the chart model and data are available before rendering
             */
            isChartReady: function() {
                if (this.meta.config || this.disposed) {
                    return false;
                }

                if (!this.$el || (this.$el.parents().length > 0 && !this.$el.is(':visible'))) {
                    return false;
                }

                if (!_.isFunction(this.chart) || !this.hasChartData()) {
                    this.chart_loaded = false;
                    this.displayNoData(true);
                    return false;
                }

                this.displayNoData(false);
                return true;
            },

            /**
             * Checks to see if the chart is available and is displayed before resizing
             */
            resize: function() {
                if (!this.chart_loaded) {
                    return;
                }
                // This handles the case of preview open and dashlet collapsed.
                // We don't need to handle the case of collapsed sidepane
                // because charts can resize when inside an invisible container.
                // It is being inside a display:none container that causes problems.
                if (!this.$el || (this.$el.parents().length > 0 && !this.$el.is(':visible'))) {
                    return;
                }

                this.chartResize();
            },

            /**
             * Attach and detach a resize method to the print event
             * @param {string} The state of print handling.
             */
            handlePrinting: function(state) {
                var self = this,
                    mediaQueryList = window.matchMedia && window.matchMedia('print');
                var pausecomp = function(millis) {
                        // www.sean.co.uk
                        var date = new Date(),
                            curDate = null;
                        do {
                            curDate = new Date();
                        } while (curDate - date < millis);
                    };
                var printResize = function(mql) {
                        if (mql.matches) {
                            if (!_.isUndefined(self.chart.legend) && _.isFunction(self.chart.legend.showAll)) {
                                self.chart.legend.showAll(true);
                            }
                            self.chart.width(640).height(320);
                            self.resize();
                            pausecomp(200);
                        } else {
                            browserResize();
                        }
                    };
                var browserResize = function() {
                        if (!_.isUndefined(self.chart.legend) && _.isFunction(self.chart.legend.showAll)) {
                            self.chart.legend.showAll(false);
                        }
                        self.chart.width(null).height(null);
                        self.resize();
                    };

                if (state === 'on') {
                    if (window.matchMedia) {
                        mediaQueryList.addListener(printResize);
                    } else if (window.attachEvent) {
                        window.attachEvent('onbeforeprint', printResize);
                        window.attachEvent('onafterprint', browserResize);
                    } else {
                        window.onbeforeprint = printResize;
                        window.onafterprint = browserResize;
                    }
                } else {
                    if (window.matchMedia) {
                        mediaQueryList.removeListener(printResize);
                    } else if (window.detachEvent) {
                        window.detachEvent('onbeforeprint', printResize);
                        window.detachEvent('onafterprint', browserResize);
                    } else {
                        window.onbeforeprint = null;
                        window.onafterprint = null;
                    }
                }
            },

            /**
             * Toggle display of dashlet content and NoData message
             * @param {boolean} state The visibilty state of the dashlet content.
             */
            displayNoData: function(state) {
                this.$('[data-content="chart"]').toggleClass('hide', state);
                this.$('[data-content="nodata"]').toggleClass('hide', !state);
            }

        });
    });
})(SUGAR.App);
