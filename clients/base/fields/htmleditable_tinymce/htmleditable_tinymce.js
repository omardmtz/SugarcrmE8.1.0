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
 * @class View.Fields.Base.Htmleditable_tinymceField
 * @alias SUGAR.App.view.fields.BaseHtmleditable_tinymceField
 * @extends View.Fields.Base.BaseField
 */
({
    plugins: ['Tinymce'],

    fieldSelector: '.htmleditable', //iframe or textarea selector
    _htmleditor: null, // TinyMCE html editor
    _isDirty: false,
    // When the model already has the value being set, there is no need to trigger the "SetContent" event, which calls
    // our callback to save the content to the model. But we don't want to short-circuit events in TinyMCE's workflow,
    // so the following flag can be toggled to false to indicate that we don't need to save the content to the model
    // inside of the callback.
    _saveOnSetContent: true,

    /**
     * Render an editor for edit view or an iframe for others
     *
     * @private
     */
    _render: function() {

        this.destroyTinyMCEEditor();

        this._super('_render');

        this._getHtmlEditableField().attr('name', this.name);
        if (this._isEditView()) {
            this._renderEdit(this.def.tinyConfig || null);
        } else {
            this._renderView();
        }
    },

    /**
     * Populate the editor or textarea with the value from the model
     */
    bindDataChange: function() {
        this.model.on('change:' + this.name, function(model, value) {
            if (this._isEditView()) {
                this._saveOnSetContent = false; // the model already has the value being set, so don't set it again
                this.setEditorContent(value);
            } else {
                this.setViewContent(value);
            }
        }, this);
    },

    /**
     * Determines if the iframe is loaded and has a body element
     *
     * @param {Object} editable A reference to a field jQuery object
     * @protected
     */
    _iframeHasBody: function(editable) {
        return editable.contents().length > 0 && editable.contents().find('body').length > 0;
    },

    /**
     * Sets the content displayed in the non-editor view
     *
     * @param {String} value Sanitized HTML to be placed in view
     */
    setViewContent: function(value){
        var editable = this._getHtmlEditableField();
        var styleExists = false;
        var styleSrc = 'styleguide/assets/css/iframe-sugar.css';

        if (!editable) {
            return;
        }

        if (this._iframeHasBody(editable)) {
            // Only add the stylesheet that is sugar-specific while making sure not to add any duplicates
            editable.contents().find('link[rel="stylesheet"]').each(function() {
                if ($(this).attr('href') === styleSrc) {
                    styleExists = true;
                }
            });

            if (!styleExists) {
                // Add the tinyMCE specific stylesheet to the iframe
                editable.contents().find('head').append($('<link/>', {
                    rel: 'stylesheet',
                    href: styleSrc,
                    type: 'text/css'
                }));
            }

            editable.contents().find('body').html(value);
        } else {
            editable.html(value);
        }
    },

    /**
     * Render editor for edit view
     *
     * @param {Array} value TinyMCE config settings
     * @private
     */
    _renderEdit: function(options) {
        var self = this;
        this.initTinyMCEEditor(options);
        this._getHtmlEditableField().on('change', function(){
            self.model.set(self.name, self._getHtmlEditableField().val());
        });
    },

    /**
     * Render read-only view for other views
     *
     * @private
     */
    _renderView: function() {
        this.setViewContent(this.value);
    },

    /**
     * Is this an edit view?  If the field contains a textarea, it will assume that it's in an edit view.
     *
     * @return {Boolean}
     * @private
     */
    _isEditView: function() {
        return this.action === 'edit';
    },

    /**
     * Returns a default TinyMCE init configuration for the htmleditable widget.
     * This function can be overridden to provide a custom TinyMCE configuration.
     *
     * See [TinyMCE Configuration Documentation](http://www.tinymce.com/wiki.php/Configuration)for details.
     *
     * @return {Object} TinyMCE configuration to use with this widget
     */
    getTinyMCEConfig: function(){
        return {
            // Location of TinyMCE script
            script_url: 'include/javascript/tinymce4/tinymce.min.js',

            // General options
            theme: 'modern',
            skin: 'sugar',
            plugins: 'code,textcolor',
            browser_spellcheck: true,

            // User Interface options
            width: '100%',
            height: '100%',
            menubar: false,
            statusbar: false,
            resize: false,
            toolbar: 'code | bold italic underline strikethrough | bullist numlist | alignleft aligncenter ' +
                'alignright alignjustify | forecolor backcolor | fontsizeselect | formatselect | fontselect',

            // Sets the text of the Target element of the link plugin. To disable
            // this completely, set target_list: false
            target_list: [
                {
                    text: app.lang.getAppString('LBL_TINYMCE_TARGET_SAME'),
                    value: ''
                },
                {
                    text: app.lang.getAppString('LBL_TINYMCE_TARGET_NEW'),
                    value: '_blank'
                }
            ],

            // Output options
            entity_encoding: 'raw',

            // URL options
            relative_urls: false,
            convert_urls: false
        };
    },

    /**
     * Initializes the TinyMCE editor.
     *
     * @param {Object} optConfig Optional TinyMCE config to use when initializing editor.  If none provided, will load config provided from {@link getTinyMCEConfig}.
     */
    initTinyMCEEditor: function(optConfig) {
        var self = this;
        if(_.isEmpty(this._htmleditor)){
            var config = _.extend({}, this.getTinyMCEConfig(), optConfig || {});
            var __superSetup__ = config.setup;
            // Preserve custom setup if it exists, add setup function needed for widget to work properly
            config.setup = function(editor){
                if(_.isFunction(__superSetup__)){
                    __superSetup__.call(this, editor);
                }
                self._htmleditor = editor;
                self._htmleditor.on('init', function(event) {
                    self.setEditorContent(self.getFormattedValue());
                    $(event.target.getWin()).blur(function(e){ // Editor window lost focus, update model immediately
                        self._saveEditor(true);
                    });
                });
                self._htmleditor.on('deactivate', function(ed){
                    self._saveEditor();
                });
                self._htmleditor.on('change', function(ed, l) {
                    // Changes have been made, mark widget as dirty so we don't lose them
                    self._isDirty = true;
                });
                self._htmleditor.on('paste', function() {
                    // Some content has been pasted, mark widget as dirty so we don't lose pasted content.
                    self._isDirty = true;
                });
                self.addCustomButtons(editor);
            };
            config.oninit = function(inst) {
                self.context.trigger('tinymce:oninit', inst);
            };

            this._getHtmlEditableField().tinymce(config);
        }
    },

    /**
     * Add custom buttons.
     * @param {Object} editor TinyMCE editor
     */
    addCustomButtons: function(editor) {},

    /**
     * Destroy TinyMCE Editor instance
     */
    destroyTinyMCEEditor: function() {
        // Clean up existing TinyMCE editor
        if(!_.isNull(this._htmleditor)){
            try {
                // A known issue with Firefox and TinyMCE produces a NS_ERROR_UNEXPECTED Exception
                this._saveEditor(true);
                this._htmleditor.remove();
                this._htmleditor.destroy();
            } catch (e) {
            }
            this._htmleditor = null;
        }
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
    }

})
