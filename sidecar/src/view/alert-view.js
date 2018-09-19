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
const Component = require('view/component');

/**
 * Base class for alerts.
 *
 * Extend this class to provide custom alert behavior:
 * ```
 * const AlertView = require('view/alert-view');
 * const TemplateManager = require('view/template');
 * let PortalAlertView = AlertView.extend({
 *    initialize: function(options) {
 *       AlertView.prototype.initialize.call(this, options);
 *
 *       // You may override and/or pre-compile alert template
 *       this.tpl = 'my-alert';
 *       TemplateManager.compile('my-alert', 'handlebars code...');
 *    },
 *
 *    render: function(options) {
 *        // Provide your custom rendering logic.
 *        // For example, switch between different templates
 *        this.tpl = 'alert2';
 *        AlertView.prototype.render.call(this, options);
 *    },
 *
 *    close: function() {
 *        // Provide your custom dismiss logic: animation, fade effects, etc.
 *    }
 * });
 * ```
 *
 * @module View/AlertView
 * @class
 * @extends View/Component
 */
const AlertView = Component.extend({
    /**
     * The default alert template.
     *
     * @memberOf View/AlertView
     * @type {string}
     * @instance
     */
    tpl: '<div class="alert alert-block">{{#if title}}<strong>{{title}}</strong>{{/if}}{{#each messages}}{{./this}}{{/each}}</div>',

    /**
     * Renders an alert.
     *
     * The method executes a pre-compiled template and replaces the inner
     * HTML of this view root DOM element.
     * Additionally, `alert-[level]` class is added to the root element.
     *
     * @memberOf View/AlertView
     * @instance
     */
    render: function() {
        var tpl = Handlebars.compile(this.tpl);
        this.$el.html(tpl(this.options));
        this.$('.alert').addClass('alert-' + this.options.level);
    }
});

module.exports = AlertView;
