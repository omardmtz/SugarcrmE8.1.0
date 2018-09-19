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
 * @class View.Fields.Base.ImageField
 * @alias SUGAR.App.view.fields.BaseImageField
 * @extends View.Fields.Base.BaseField
 */
({
    fieldTag: 'input[type=file]',

    /**
     * @inheritdoc
     *
     * This field doesn't support `showNoData`.
     */
    showNoData: false,

    events: {
        "click .delete": "delete",
        "change input[type=file]": "selectImage"
    },

    plugins: ['File', 'FieldDuplicate'],

    /**
     * @inheritdoc
     *
     * The direction for this field should always be `ltr`.
     */
    direction: 'ltr',

    /**
     * @override
     *
     * FIXME: The {@link #model} used by this view should be a {@link Data.Bean}
     * and not a simple {@link Backbone.Model}. This should be removed when
     * {@link View.Views.Base.HistorySummaryView} view is refactored to use a
     * true {@link Data.MixedBeanCollection}.
     */
    initialize: function(options) {
        app.view.Field.prototype.initialize.call(this, options);

        // FIXME: This needs an API instead. SC-3369 should address this.
        // Also, this field should extend the file field to inherit these
        // error properties.
        app.error.errorName2Keys['tooBig'] = 'ERROR_MAX_FILESIZE_EXCEEDED';
        app.error.errorName2Keys['uploadFailed'] = 'ERROR_UPLOAD_FAILED';

        // FIXME: we should have a {@link Da
        if (_.isFunction(this.model.addValidationTask)) {
            this.model.hasImageRequiredValidator = true;
            this.model.addValidationTask('image_required_' + this.cid, _.bind(this._doValidateImageField, this));
        }
    },

    /**
     * @override
     * @private
     */
    _dispose: function() {
        //Remove specific validation task from the model
        this.model.hasImageRequiredValidator = false;
        if (this.model.removeValidationTask) {
            this.model.removeValidationTask('image_required_' + this.cid);
        }
        app.view.Field.prototype._dispose.call(this);
    },

    /**
     * Handler to refresh field state.
     *
     * Called from {@link app.plugins._onFieldDuplicate}
     */
    onFieldDuplicate: function() {
        if (this.disposed || this.view.name !== 'merge-duplicates') {
            return;
        }
        this.render();
    },

    /**
     * @override
     * @private
     */
    _render: function() {
        this.model.fileField = this.name;
        app.view.Field.prototype._render.call(this);

        //Define default sizes
        if (this.view && this.view.meta && this.view.meta.type === 'list') {
            this.width = this.height = this.$el.parent().height() || 42;
            this.def.width = this.def.height = undefined;
        } else {
            this.width = parseInt(this.def.width || this.def.height, 10) || 42;
            this.height = parseInt(this.def.height, 10) || this.width;
        }

        //Resize widget before the image is loaded
        this.resizeWidth(this.width);
        this.resizeHeight(this.height);
        this.$('.image_field').removeClass('hide');
        //Resize widget once the image is loaded
        this.$('img').addClass('hide').on('load', $.proxy(this.resizeWidget, this));
        return this;
    },

    /**
     * @override
     * @param value
     * @return value
     */
    format: function(value) {
        if (value) {
            value = this.buildUrl() + "&_hash=" + value;
        }
        return value;
    },

    /**
     * @override
     */
    bindDataChange: function() {
        //Keep empty for edit because you cannot set a value of an input type `file`
        var viewType = this.view.name || this.options.viewName;
        var ignoreViewType = ["edit", "create"];
        if ((_.indexOf(ignoreViewType, viewType) < 0)
            && (this.view.action !== "edit")
            && (this.view.name !== 'merge-duplicates')) {
            app.view.Field.prototype.bindDataChange.call(this);
        }
    },

    /**
     * @override
     */
    bindDomChange: function() {
        //Override default behavior
        this.$(this.fieldTag).on('focus', _.bind(this.handleFocus, this));
    },

    /**
     * This is the custom implementation of bindDomChange. Here we upload the image to give a preview to the user.
     * @param e
     */
    selectImage: function(e) {
        var self = this,
            $input = self.$('input[type=file]');

        //Set flag to indicate we are previewing an image
        self.preview = true;

        //Remove error message
        self.clearErrorDecoration();

        // Upload a temporary file for preview
        self.model.uploadFile(
            self.name,
            $input,
            {
                field: self.name,
                //Callbacks
                success: function(rsp) {
                    //read the guid
                    var fileId = (rsp[self.name]) ? rsp[self.name]['guid'] : null;
                    var url = app.api.buildFileURL({
                        module: self.module,
                        id: 'temp',
                        field: self.name,
                        fileId: fileId
                    }, {keep: true});
                    // show image
                    var image = $('<img>').addClass('hide').attr('src', url).on('load', $.proxy(self.resizeWidget, self));
                    self.$('.image_preview').html(image);

                    // Add the guid to the list of fields to set on the model.
                    if (fileId) {
                        if (!self.model.fields[self.name + '_guid']) {
                            self.model.fields[self.name + '_guid'] = {
                                type: 'file_temp',
                                group: self.name
                            };
                        }
                        self.model.unset(self.name);
                        self.model.set(self.name + '_guid', fileId);
                    }
                },
                error: function(resp) {
                    var errors = errors || {},
                        fieldName = self.name;
                    errors[fieldName] = {};

                    switch (resp.error) {
                        case 'request_too_large':
                           errors[fieldName].tooBig = true;
                           break;
                        default:
                            errors[fieldName].uploadFailed = true;
                    }
                    self.model.unset(fieldName + '_guid');
                    self.model.trigger('error:validation:' + this.field, errors[fieldName]);
                    self.model.trigger('error:validation', errors);
                }
            },
            { temp: true }); //for File API to understand we upload a temporary file
    },

    /**
     * Calls when deleting the image or canceling the preview
     * @param e
     */
    'delete': function(e) {
        var self = this;
        //If we are previewing a file and want to cancel
        if (this.preview === true) {
            self.preview = false;
            self.clearErrorDecoration();
            self.render();
            return;
        }

        // If it's a duplicate, don't delete the file
        if (this._duplicateBeanId) {
            self.model.unset(self.name);
            self.model.set(self.name, null);
            self.render();
            return;
        }

        var confirmMessage = app.lang.get('LBL_IMAGE_DELETE_CONFIRM', self.module);
        if (confirm(confirmMessage)) {
            //Otherwise delete the image
            app.api.call('delete', self.buildUrl({htmlJsonFormat: false}), {}, {
                    success: function(response) {
                        //Need to fire the change event twice so model.previous(self.name) is also changed.
                        self.model.unset(self.name);
                        self.model.set(self.name, null);
                        if (response.record && response.record.date_modified) {
                            self.model.set('date_modified', response.record.date_modified);
                        }
                        if (!self.disposed) {
                            self.render();
                        }
                    },
                    error: function(data) {
                        // refresh token if it has expired
                        app.error.handleHttpError(data, {});
                    }}
            );
        }
    },

    /**
     * Build URI for File API
     * @param options
     */
    buildUrl: function(options) {
        return app.api.buildFileURL({
            module: this._duplicateBeanModule ? this._duplicateBeanModule : this.module,
            id: this._duplicateBeanId ? this._duplicateBeanId : this.model.id,
            field: this.name
        }, options);
    },

    /**
     * Resize widget based on field defs and image size
     */
    resizeWidget: function() {
        var image = this.$('.image_preview img, .image_detail img');

        if (!image[0]) return;

        var isDefHeight = !_.isUndefined(this.def.height) && this.def.height > 0,
            isDefWidth = !_.isUndefined(this.def.width) && this.def.width > 0;

        //set width/height defined in field defs
        if (isDefWidth) {
            image.css('width', this.width);
        }
        if (isDefHeight) {
            image.css('height', this.height);
        }

        if (!isDefHeight && !isDefWidth)
            image.css({
                'height': this.height,
                'width': this.width
            });

        //now resize widget
        //we resize the widget based on current image height
        this.resizeHeight(image.height());
        //if height was defined but not width, we want to resize image width to keep
        //proportionality: this.height/naturalHeight = newWidth/naturalWidth
        if (isDefHeight && !isDefWidth) {
            var newWidth = Math.floor((this.height / image[0].naturalHeight) * image[0].naturalWidth);
            image.css('width', newWidth);
            this.resizeWidth(newWidth);
        }

        image.removeClass('hide');
        this.$('.delete').remove();
        var icon = this.preview === true ? 'times' : 'trash-o';
        image.closest('label, a').after('<span class="image_btn delete fa fa-' + icon + ' " />');
    },

    /**
     * Utility function to append px to an integer
     *
     * @param size
     * @return {string}
     */
    formatPX: function(size) {
        size = parseInt(size, 10);
        return size + 'px';
    },

    /**
     * Resize the elements carefully to render a pretty input[type=file]
     * @param height (in pixels)
     */
    resizeHeight: function(height) {
        var $image_field = this.$('.image_field'),
            isEditAndIcon = this.$('.fa-plus').length > 0;

        if (isEditAndIcon) {
            var $image_btn = $image_field.find('.image_btn');
            var edit_btn_height = parseInt($image_btn.css('height'), 10);

            var previewHeight = parseInt(height, 10);
            //Remove the edit button height in edit view so that the icon is centered.
            previewHeight -= edit_btn_height ? edit_btn_height : 0;
            previewHeight = this.formatPX(previewHeight);

            $image_field.find('.fa-plus').css({lineHeight: previewHeight});
        }


        var totalHeight = this.formatPX(height);
        $image_field.css({'height': totalHeight, minHeight: totalHeight, lineHeight: totalHeight});
        $image_field.find('label').css({lineHeight: totalHeight});
    },

    /**
     * Resize the elements carefully to render a pretty input[type=file]
     * @param width (in pixels)
     */
    resizeWidth: function(width) {
        var $image_field = this.$('.image_field'),
            width = this.formatPX(width),
            isInHeaderpane = $(this.el).closest('.headerpane').length > 0,
            isInRowFluid = $(this.el).closest('.row-fluid').closest('.record').length > 0;

        if (isInHeaderpane || !isInRowFluid) {
            //Need to fix width
            $image_field.css({'width': width});
        } else {
            //Width will be the biggest possible
            $image_field.css({'maxWidth': width});
        }
    },

    /**
     * Custom requiredValidator for image field because we need to check if the
     * input inside the view is empty or not.
     *
     * @param {Object} fields Hash of field definitions to validate.
     * @param {Object} errors Error validation errors.
     * @param {Function} callback Async.js waterfall callback.
     */
    _doValidateImageField: function(fields, errors, callback) {
        if (this.def.required && !this.model.get(this.name + '_guid') && !this.model.get(this.name)) {
            errors[this.name] = errors[this.name] || {};
            errors[this.name].required = true;
        }

        callback(null, fields, errors);
    },

    /**
     * Handles errors message
     *
     * @override
     * @param errors
     */
    handleValidationError: function(errors) {
        var errorMessages = [];

        if (this.action === 'detail') {
            this.setMode('edit');
        }

        //Change the preview of the image widget
        this.$('.image_preview').html('<i class="fa fa-times"></i>');
        //Put the cancel icon
        this.$('label').after('<span class="image_btn delete fa fa-times" />');

        this.$el.closest('.record-cell').addClass("error");
        this.$el.addClass('input-append error');

        _.each(errors, function(errorContext, errorName) {
            errorMessages.push(app.error.getErrorString(errorName, errorContext));
        });
        this.$('.image_field').append(this.exclamationMarkTemplate(errorMessages));
    },

    /**
     * @override
     */
    clearErrorDecoration: function() {
        //Remove the current icon
        this.$('.delete').remove();
        //Remove error message
        this.$('.error-tooltip').remove();
        this.$el.closest('.record-cell').removeClass('error');
        this.$el.removeClass('input-append error');
    }
})
