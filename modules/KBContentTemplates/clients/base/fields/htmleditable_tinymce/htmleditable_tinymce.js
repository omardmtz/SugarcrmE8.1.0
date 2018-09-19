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
({
    extendsFrom: 'BaseKBContentsHtmleditable_tinymceField',

    /**
     * Override to load handlebar templates from `KBContents module
     * @inheritdoc
     */
    _loadTemplate: function() {
        var module = this.module;
        this.module = 'KBContents';
        this._super('_loadTemplate');
        this.module = module;
    }
})
