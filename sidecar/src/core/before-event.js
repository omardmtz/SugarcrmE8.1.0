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
 * Regular expression used to split event strings.
 *
 * @private
 * @type {RegExp}
 */
const eventSplitter = /\s+/;

/**
 * Events API used to process various event actions and handle inputs such
 * as event maps and space-separated event names.
 *
 * This is taken from the Backbone.js library to simplify event-handling.
 *
 * @param {Object} obj Scope in which the `action` will be executed.
 * @param {string} action The function being executed (e.g. 'triggerBefore')
 * @param {string|Object} name Event(s) to trigger before. Accepts
 *   multiple space-separated event names, or an event map.
 * @param {Array} [rest] List of arguments to be passed to the before event.
 * @return {boolean} `false` if an event map or space-separated event names
 *   were used, `true` otherwise.
 * @private
 */
const eventsApi = function (obj, action, name, rest) {
    if (!name) return true;

    // Handle event maps.
    if (typeof name === 'object') {
        for (let key in name) {
            obj[action].apply(obj, [key, name[key]].concat(rest));
        }

        return false;
    }

    // Handle space separated event names.
    if (eventSplitter.test(name)) {
        let names = name.split(eventSplitter);
        for (let i = 0, l = names.length; i < l; i++) {
            obj[action].apply(obj, [names[i]].concat(rest));
        }

        return false;
    }

    return true;
};

/**
 * An optimized event-triggering method used to speed up common event calls
 * with 1, 2, or 3 arguments. Prevents the event from triggering if any
 * provided before callback returns `false`.
 *
 * This is taken from the Backbone.js library to simplify event-dispatching.
 *
 * @param {Array} events The list of before event listeners.
 * @param {Array} [args] Arguments passed to the before event callback.
 * @return {boolean} `true` if the before event should be triggered, `false`
 *   otherwise.
 * @private
 */
const triggerEvents = function (events, args) {
    let stop = false;
    let i = -1;
    let l = events.length;
    let ev;
    switch (args.length) {
        case 0:
        case 1:
        case 2:
        case 3:
            while (++i < l) stop = (ev = events[i]).callback.call(ev.ctx, args[0], args[1], args[2]) === false || stop;
            break;
        default:
            while (++i < l) stop = (ev = events[i]).callback.apply(ev.ctx, args) === false || stop;
    }

    return !stop;
};

/**
 * `BeforeEvent` is a mixin that provides methods to create hooks that run
 * before a certain event.
 *
 * Usage Example:
 *
 * ```
 * const BeforeEvent = require('core/before-event');
 * _.extend(MyObject, BeforeEvent);
 * ```
 *
 * @alias Core/BeforeEvent
 * @mixin
 */
module.exports = {
    /**
     * Adds a callback/hook to be fired before an action is taken. If that
     * callback returns `false`, the action should not be taken.
     *
     * The following example binds a callback function and passes the scope
     * from the view component to use in that callback:
     *
     * ```
     * model.before('save', this.doSomethingBeforeSave, this);
     * ```
     *
     * Multiple space-separated event names can be bound to a single callback:
     *
     * ```
     * view.before('save dispose', this.callback, this);
     * ```
     *
     * This method also supports an event map syntax, as an alternative to
     * positional arguments:
     *
     * ```
     * this.before({
     *     render: this.doSomethingBeforeRender,
     *     dispose: this.doSomethingBeforeDispose,
     * });
     * ```
     *
     * @param {string|Object} name Event(s) to trigger before. Accepts multiple
     *   space-separated event names or an event map.
     * @param {Function} callback Function to be called.
     * @param {Object} [context] Value to be assigned to `this` when the
     *   callback is fired.
     * @return {Object} Instance of this class.
     */
    before(name, callback, context) {
        if (!eventsApi(this, 'before', name, [callback, context]) || !callback) {
            return this;
        }

        this._before = this._before || {};
        let events = this._before[name] || (this._before[name] = []);

        events.push({
            callback: callback,
            context: context,
            ctx: context || this,
        });

        return this;
    },

    /**
     * Triggers the before callback for the given event `name` or list of
     * events.
     *
     * The following example triggers the callback bound to the before `save`
     * event given:
     *
     * ```
     * this.triggerBefore('save');
     * ```
     *
     * Multiple events can be triggered as well:
     *
     * ```
     * this.triggerBefore('save render dispose');
     * ```
     *
     * Custom arguments (e.g. `a`, `b`, `c`) can be passed to the callback:
     *
     * ```
     * this.triggerBefore('save', a, b, c);
     * ```
     *
     * @param {string} name The before event(s) to trigger.
     * @return {boolean} Returns `true` if the event should be triggered,
     *   `false` otherwise.
     */
    triggerBefore(name) {
        let stop = false;

        if (!this._before) {
            return !stop;
        }

        let args = Array.prototype.slice.call(arguments, 1);

        // Handle space separated event names.
        if (eventSplitter.test(name)) {
            let names = name.split(eventSplitter);
            for (let i = 0, l = names.length; i < l; i++) {
                stop = !this.triggerBefore.apply(this, [names[i]].concat(args)) || stop;
            }

            return !stop;
        }

        let events = this._before[name];
        let allEvents = this._before.all;

        if (events) {
            stop = (triggerEvents(events, args) === false);
        }

        if (allEvents) {
            stop = (triggerEvents(allEvents, args) === false) || stop;
        }

        return !stop;
    },

    /**
     * Removes a previously-bound callback function from a before event.
     *
     * If no context is given, all of the versions of the callback with
     * different contexts will be removed:
     *
     * ```
     * this.offBefore('render', this.onRenderBefore);
     * ```
     *
     * If no callback is given, all callbacks for the before event will
     * be removed:
     *
     * ```
     * this.offBefore('render');
     * ```
     *
     * If no event is specified, all callbacks for all before events
     * will be removed from the object:
     *
     * ```
     * this.offBefore();
     * ```
     *
     * @param {string} [name] Event(s) to remove the listeners for.
     * @param {Function} [callback] Callback to remove specifically for
     *   a given event.
     * @param {Object} [context] Context to use when determining which
     *   callback to remove.
     * @return {Object} Instance of this class.
     */
    offBefore(name, callback, context) {
        // This is taken from the Backbone.js library to simplify event-handling.
        let retain;
        let ev;
        let events;
        let names;

        if (!this._before || !eventsApi(this, 'offBefore', name, [callback, context])) {
            return this;
        }

        if (!name && !callback && !context) {
            this._before = void 0;
            return this;
        }

        names = name ? [name] : _.keys(this._before);
        for (let i = 0, l = names.length; i < l; i++) {
            name = names[i];
            events = this._before[name];
            if (events) {
                this._before[name] = retain = [];
                if (callback || context) {
                    for (let j = 0, k = events.length; j < k; j++) {
                        ev = events[j];
                        if ((callback && callback !== ev.callback) || (context && context !== ev.ctx)) {
                            retain.push(ev);
                        }
                    }
                }

                if (!retain.length) {
                    delete this._before[name];
                }
            }
        }

        return this;
    },
};
