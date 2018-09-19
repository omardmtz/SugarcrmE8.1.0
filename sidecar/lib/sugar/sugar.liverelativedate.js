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

const DateUtils = require('utils/date');

/**
 * LiveRelativeDate plugin is a jQuery/Zepto plugin for sidecar that converts a
 * date into a relative time with a timer to keep it relative to current time.
 *
 * To use it is simple, just run:
 *
 * ```
 * $('[data-relative-to]').liverelativedate();
 * ```
 *
 * To define the attribute where the ISO-8601 date can be found (defaults to
 * `datetime` attribute of used by `<time>` tag):
 *
 * ```
 * $('[data-my-iso-8601]').liverelativedate({
 *     'attr': 'data-my-iso-8601'
 * });
 * ```
 *
 * To get the current update interval configured:
 *
 * ```
 * $.liverelativedate('interval');
 * ```
 *
 * To change the current update interval, do:
 *
 * ```
 * $.liverelativedate('interval', 10e3);
 * ```
 *
 * This plugin has a hard dependency with {@link Utils.Date} functions
 * available in Sidecar library. If you wish to override that behavior,
 * provide the override on those utility functions.
 *
 */
(function(plugin) {
    plugin(window.jQuery || window.Zepto, DateUtils);
}(function($, date) {

    var updateInterval = 6e4, // every minute
        $liveDates = $([]),
        timeout;

    /**
     * This is the running cycle. It will automatically stop if we don't have
     * dates to listen to.
     */
    var run = function() {
        if (!$liveDates.length) {
            return;
        }
        timeout = setTimeout(run, updateInterval);
        liveRelativeDateGlobal.update();
    };

    /**
     * List of methods available from the plugin directly. These are the global
     * methods.
     */
    var liveRelativeDateGlobal = {

        /**
         * Updates provided Elements or all stored elements if none provided.
         *
         * On each update of an element, we check if the new date is different
         * from the old (to minimize the DOM changes) and trigger an event
         * `change.liveRelativeDate` if it is, to check if any subscribed
         * listeners want to stop the DOM update.
         *
         * This method also keeps the `$liveDates` array clean from lost
         * elements that weren't properly destroyed.
         *
         * @param {jQuery} [$el] The jQuery elements to update. If empty, will
         *   update all stored elements.
         */
        update: function($el) {
            $el = $el || $liveDates;

            $el.each(function(i, el) {
                var $this = $(el),
                    options = $this.data('liveRelativeDate'),
                    value;

                if (!options) {
                    // auto clean up in case some one forgot to destroy it.
                    $liveDates = $liveDates.not(el);
                    return;
                }

                value = options.date ? $this.data(options.date) : $this.attr('datetime');

                var from = $this.html(),
                    // TODO we need to support dateOnly which would show "today" if there is no time available
                    to = date(value).fromNow();

                if (from === to) {
                    return;
                }

                var e = $.Event('change.liveRelativeDate');
                $this.trigger(e, [from, to]);

                if (!e.isDefaultPrevented()) {
                    $this.html(to);
                }
            });
        },

        /**
         * Pauses the relative date time update.
         */
        pause: function() {
            clearTimeout(timeout);
            timeout = null;
        },

        /**
         * Resumes the live relative date auto update if it isn't already
         * running.
         */
        resume: function() {
            if (!timeout) {
                run();
            }
        },

        /**
         * Sets the interval if the param is provided, returns the current
         * configured interval if no param is given.
         *
         * @param {number} [interval] The interval to set. Pass `undefined` to
         *   get the current value.
         *
         * @return {number|undefined} The current interval or `undefined` if
         *   setting the value.
         */
        interval: function(interval) {
            if (interval === undefined) {
                return updateInterval;
            }
            updateInterval = interval;
        }
    };

    /**
     * List of methods that are available based on the provided jQuery
     * elements.
     */
    var liveRelativeDateLocal = {

        /**
         * Adds a set of Elements to the current live relative dates list.
         *
         * Accepts options that will be stored in the element itself to be used
         * during the update of its content.
         * This might **not** trigger an update to the added elements
         * automatically, but will make sure the timer is running for the new
         * elements.
         *
         * @param {jQuery} $el The set of elements to add to the updater.
         * @param {Object} options The list of options to use in the
         *   {@link #update} method.
         * @return {jQuery} The elements being added.
         */
        add: function($el, options) {

            if (!$el || $el.length === 0) {
                return $el;
            }
            options = options || {};

            $el.data('liveRelativeDate', JSON.stringify(options));
            $liveDates = $liveDates.add($el);

            liveRelativeDateGlobal.resume();

            return $el;
        },

        /**
         * Clears a set of Elements from the list of live dates to update.
         *
         * Clears the data `live-relative-date` from the element.
         * Doesn't restore the original value of the element when this plugin
         * was applied.
         *
         * @param {jQuery} $el The jQuery elements to remove from the listener.
         * @return {jQuery} The elements removed from the list of updates.
         */
        destroy: function($el) {

            $liveDates = $liveDates.not($el);
            $el.removeAttr('data-live-relative-date');
            return $el;
        },

        /**
         * Returns `true` if the passed Elements are being tracked/updated by
         * this plugin.
         *
         * @param {jQuery} $els The elements to check.
         * @return {Boolean} Returns `true` if all elements are being tracked
         *   by the plugin, `false` otherwise.
         */
        isLiveRelativeDate: function($els) {

            var tracked = true;
            $.each($els, function(el) {
                if ($(el).data('liveRelativeDate') === undefined) {
                    tracked = false;
                    return true;
                }
            });
            return tracked;
        }
    };

    $.liverelativedate = liveRelativeDateGlobal;

    $.fn.liverelativedate = function(method, options) {

        if (!liveRelativeDateLocal[method]) {
            options = method;
            method = 'add';
        }

        return liveRelativeDateLocal[method](this, options);
    };
}));
