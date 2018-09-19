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
    extendsFrom: 'Htmleditable_tinymceField',

    /**
     * @inheritdoc
     */
    addCustomButtons: function (editor) {
        editor.addButton('sugarfieldbutton', {
            title: app.lang.get('LBL_SUGAR_FIELD_SELECTOR', 'pmse_Emails_Templates'),
            class: 'mce_selectfield',
            icon: 'fullpage',

            onclick: _.bind(this._showVariablesBook, this)
        });
        editor.addButton('sugarlinkbutton', {
            title: app.lang.get('LBL_SUGAR_LINK_SELECTOR', 'pmse_Emails_Templates'),
            class: 'mce_selectfield',
            image: 'styleguide/assets/img/record-link.svg',
            onclick: _.bind(this._showLinksDrawer, this)
        });
    },

    /**
     * Save the TinyMCE editor's contents to the model
     * @private
     */
    _saveEditor: function(force){
        var save = force | this._isDirty;
        if(save){
            this.model.set(this.name, this.getEditorContent(), {silent: true});
            this._isDirty = false;
        }
    },

    /**
     * Finds textarea or iframe element in the field template
     *
     * @return {HTMLElement} element from field template
     * @private
     */
    _getHtmlEditableField: function() {
        return this.$el.find(this.fieldSelector);
    },

    /**
     * Sets TinyMCE editor content
     *
     * @param {String} value HTML content to place into HTML editor body
     */
    setEditorContent: function(value) {
        if(_.isEmpty(value)){
            value = "";
        }
        if (this._isEditView() && this._htmleditor && this._htmleditor.dom) {
            this._htmleditor.setContent(value);
        }
    },

    /**
     * Retrieves the  TinyMCE editor content
     *
     * @return {String} content from the editor
     */
    getEditorContent: function() {
        return this._htmleditor.getContent({format: 'raw'});
    },

    /**
     * Destroy TinyMCE Editor on dispose
     *
     * @private
     */
    _dispose: function() {
        this.destroyTinyMCEEditor();
        app.view.Field.prototype._dispose.call(this);
    },
    /**
     * When in edit mode, the field includes an icon button for opening an address book. Clicking the button will
     * trigger an event to open the address book, which calls this method to do the dirty work. The selected recipients
     * are added to this field upon closing the address book.
     *
     * @private
     */
    _showVariablesBook: function() {
        /**
         * Callback to add recipients, from a closing drawer, to the target Recipients field.
         * @param {undefined|Backbone.Collection} recipients
         */
        var addVariables = _.bind(function(variables) {
            if (variables && variables.length > 0) {
                this.model.set(this.name, this.buildVariablesString(variables));
            }

        }, this);
        app.drawer.open(
            {
                layout:  "compose-varbook",
                context: {
                    module: "pmse_Emails_Templates",
                    mixed:  true
                }
            },
            function(variables) {
                addVariables(variables);
            }
        );
    },
    buildVariablesString: function(recipients) {
        var result = '' , newExpression = '', currentValue, i, aux, aux2;
        _.each(recipients.models, function(model) {
            newExpression += '{::' + model.attributes.rhs_module + '::' + model.attributes.id + '::}';
        });

        var bm = this._htmleditor.selection.getBookmark();
        this._htmleditor.selection.moveToBookmark(bm);
        this._htmleditor.selection.setContent(newExpression);

        return currentValue = this._htmleditor.getContent();
    },

    /**
     * Open a drawer with a list of related fields that we want to link to in an email
     * Create a variable like {::href_link::Accounts::contacts::name::} which is understood
     * by the backend to replace the variable with the correct Sugar link
     *
     * @private
     */
    _showLinksDrawer: function() {
        var self = this;
        var baseModule = this.model.get('base_module');
        app.drawer.open({
                layout:  "compose-sugarlinks",
                context: {
                    module: "pmse_Emails_Templates",
                    mixed:  true,
                    skipFetch: true,
                    baseModule: baseModule
                }
            },
            function(field) {
                if (_.isUndefined(field)) {
                    return;
                }
                var link = '{::href_link::' + baseModule;

                //Target module doesn't need second part of variable
                //The second part is for related modules
                //Example {::href_link::Accounts::name::}} is for the target module Account's record
                //{{::href_link::Accounts::contacts::name::}} is for the related contacts's record
                if (baseModule !== field.get('value')) {
                    link += '::' + field.get('value');
                }
                link += '::name::}';
                self._htmleditor.selection.setContent(link);
                self.model.set(self.name, self._htmleditor.getContent())
            }
        );
    }

})
