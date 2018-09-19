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
     * Flag indicates, should we disable field.
     * @property {boolean}
     */
    shouldDisable: null,

    /**
     * KB specific parameters.
     * @private
     */
    _tinyMCEConfig: {
        'height': '300',
        'plugins': 'code,textcolor,link,image',
        'toolbar': 'code | bold italic underline strikethrough | bullist numlist | ' +
        'alignleft aligncenter alignright alignjustify | forecolor backcolor | ' +
        'removeformat | image link | fontsizeselect formatselect'
    },

    /**
     * @inheritdoc
     * Additional override fieldSelector property from field's meta.
     */
    initialize: function(opts) {
        if (opts.view.action === 'filter-rows') {
            opts.viewName = 'filter-rows-edit';
        }
        this._super('initialize', [opts]);
        this.shouldDisable = false;
        if (!_.isUndefined(this.def.fieldSelector)) {
            this.fieldSelector = '[data-htmleditable=' + this.def.fieldSelector + ']';
        }
        this.before('render', function() {
            if (this.shouldDisable != this.isDisabled()) {
                this.setDisabled(this.shouldDisable);
                return false;
            }
        }, this);
    },

    /**
     * @inheritdoc
     *
     * Apply document css style to editor.
     */
    getTinyMCEConfig: function() {
        var config = this._super('getTinyMCEConfig'),
            content_css = [];

        config = _.extend(config, this._tinyMCEConfig);

        _.each(document.styleSheets, function(style) {
            if (style.href) {
                content_css.push(style.href);
            }
        });
        config.content_css = content_css;
        config.body_class = 'kbdocument-body';

        config.file_browser_callback = _.bind(this.tinyMCEFileBrowseCallback, this);

        return config;
    },

    /**
     * @inheritdoc
     * Need to strip tags for list and activity stream.
     */
    format: function(value) {
        var result;
        switch (this.view.tplName) {
            case 'audit':
            case 'list':
            case 'activitystream':
                result = this.stripTags(value);
                break;
            default:
                result = this._super('format', [value]);
                break;
        }
        return result;
    },

    /**
     * Strip HTML tags from text.
     * @param {string} value Value to strip tags from.
     * @return {string} Plain text.
     */
    stripTags: function(value) {
        var $el = $('<div/>').html(value),
            texts = $el.contents()
            .map(function() {
                if (this.nodeType === 1 && this.nodeName != 'STYLE' && this.nodeName != 'SCRIPT') {
                    return this.textContent.replace(/ +?\r?\n/g, ' ').trim();
                }
                if (this.nodeType === 3) {
                    return this.textContent.replace(/ +?\r?\n/g, ' ').trim();
                }
            });
        return _.filter(texts, function(value) {
            return (value.length > 0);
        }).join(' ');
    },

    /**
     * @inheritdoc
     * Should check, if field should be disabled while mode change.
     */
    setMode: function(mode) {
        this.shouldDisable = (mode === 'edit' &&
            (this.view.tplName === 'list' ||
            (this.view.tplName == 'flex-list' && (this.tplName == 'subpanel-list' || this.tplName == 'list'))
            )
        );
        this._super('setMode', [mode]);
    },

    /**
     * We are trying to get HTML content instead of raw one because
     * when editor initialized it already contains some HTML (blank <p> or <br> tags).
     * In this case it will be considered as non-empty value for this field even if we don't enter anything.
     * It comes from ticket RS-1072.
     *
     * @override
     * @inheritdoc
     */
    getEditorContent: function() {
        // We can't use getContent({format: 'html'}) due to this issue https://github.com/tinymce/tinymce/issues/794
        // That's why we save HTML Editor content to HTML Field and get content directly from HTML field.
        this._htmleditor.save();
        var text = this._getHtmlEditableField().html();
        //We don't need to get empty html, to prevent model changes.
        if (text !== '') {
            text = this._super('getEditorContent');
        }
        return text;
    },

    /**
     * @inheritdoc
     */
    setViewName: function ()
    {
        this.destroyTinyMCEEditor();
        this._super('setViewName', arguments);
    }
})
