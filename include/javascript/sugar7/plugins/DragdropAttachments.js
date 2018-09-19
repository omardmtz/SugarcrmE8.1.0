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
(function (app) {
    app.events.on('app:init', function () {
        app.plugins.register('DragdropAttachments', ['view', 'field'], {
            events: {
                'dragenter [data-attachable=true]': 'expandNewPost',
                'dragover [data-attachable=true]': 'dragoverNewPost',
                'dragleave [data-attachable=true]': 'shrinkNewPost',
                'drop [data-attachable=true]': 'dropAttachment'
            },

            expandNewPost: function(event) {
                this.$(event.currentTarget).addClass('dragdrop');
                return false;
            },

            dragoverNewPost: function(event) {
                return false;
            },

            shrinkNewPost: function(event) {
                event.stopPropagation();
                event.preventDefault();
                this.$(event.currentTarget).removeClass('dragdrop');
                return false;
            },

            /**
             * Handler that is called when user drops file on the file field.
             *
             * Example to override the default behavior in the view:
             *
             *     this.before('attachments:drop', this._onAttachmentDrop, this);
             *
             *     _onAttachmentDrop: function(event) {
             *         // the override code
             *         // return false to make sure we won't execute the default behavior
             *         return false;
             *     }
             *
             * The override cannot trigger the `attachments:drop` event (because it would trigger the
             * event while the before event is happening).
             *
             * @param {Event} event Drop event.
             */
            dropAttachment: function(event) {
                if (!this.triggerBefore('attachments:drop', event)) {
                    return;
                }
                this._onAttachmentDropDefault(event);
                this.trigger('attachments:drop', event);
            },

            /**
             * Default handler for 'attachments:drop' event.
             * This event is triggered when user drops file on the file field.
             *
             * @param {Event} event Drop event.
             * @private
             */
            _onAttachmentDropDefault: function(event) {

                // Use originalEvent to access the dataTransfer property since it may not exist on the jQuery event
                // see http://bugs.jquery.com/ticket/7808 for more information
                var text = $.trim(event.originalEvent.dataTransfer.getData('text')),
                    container = this.$(event.currentTarget);
                this.shrinkNewPost(event);

                if (text.length) {
                    container.append(' ' + text).trigger('change');
                }

                _.each(event.originalEvent.dataTransfer.files, function(file, i) {
                    var fileReader = new FileReader();

                    // Set up the callback for the FileReader.
                    fileReader.onload = (function(file, view) {
                        return function(e) {
                            var container,
                                sizes = ['B', 'KB', 'MB', 'GB'],
                                size_index = 0,
                                size = file.size,
                                unique = _.uniqueId('activitystream_attachment');

                            // Is the file too large?
                            if (size > app.config.uploadMaxsize) {
                                app.alert.show('file_too_big', {
                                    level: 'error',
                                    messages: 'ERROR_MAX_FILESIZE_EXCEEDED'
                                });
                                return;
                            }

                            while (size > 1024 && size_index < sizes.length - 1) {
                                size_index++;
                                size /= 1024;
                            }

                            size = Math.round(size);

                            view.dragdrop_attachments = view.dragdrop_attachments || {};
                            view.dragdrop_attachments[unique] = file;
                            container = $("<div class='activitystream-pending-attachment' id='" + unique + "'></div>");

                            // TODO: Review creation of inline HTML
                            var $close = $('<a class="close">&times;</a>');
                            $close.on('click', function(e) {
                                container.trigger('close');
                            }).appendTo(container);
                            app.accessibility.run($close, 'click');

                            container.append(file.name + ' (' + size + ' ' + sizes[size_index] + ')');

                            if (file.type.indexOf('image/') !== -1) {
                                container.append("<img style='display:block;' src='" + e.target.result + "' />");
                            } else {
                                container.append('<div>No preview available</div>');
                            }

                            container.appendTo(view.$(event.currentTarget).parent());
                            container.on('close', function() {
                                $(this).remove();
                                delete view.dragdrop_attachments[container.attr('id')];
                                view.trigger('attachments:remove');
                            });

                            view.trigger('attachments:add');
                        };
                    })(file, this);

                    fileReader.readAsDataURL(file);
                }, this);

                event.stopPropagation();
                event.preventDefault();
            },

            getAttachments: function() {
                return this.dragdrop_attachments || {};
            },

            clearAttachments: function() {
                this.$('.activitystream-pending-attachment').trigger('close');
                this.dragdrop_attachments = {};
            },

            onAttach: function(component, plugin) {
                component.on('render', function() {
                    this.$('[data-attachable=true]').attr('dropzone', 'copy');
                });

                component.on('attachments:process', function() {
                    var self = this,
                        attachments = this.getAttachments(),
                        callback = _.after(_.size(attachments), this.clearAttachments),
                        noteAttrs = this._mapNoteParentAttributes();

                    component.trigger('attachments:start');

                    if (_.size(attachments) > 0){
                        app.alert.show('uploading_attachments', {
                            level: 'process',
                            title: app.lang.get('LBL_UPLOADING')
                        });
                    }

                    _.each(attachments, function(file) {
                        var note = app.data.createBean('Notes');
                        note.set(_.extend({
                            'name': file.name,
                            'assigned_user_id': app.user.id
                        }, noteAttrs));
                        async.waterfall([
                            //save the note
                            function(callback) {
                                note.save(null, {
                                    success: function(noteModel) {
                                        callback(null, noteModel);
                                    },
                                    error: function() {
                                        callback(true);
                                    }
                                });
                            },
                            //then upload the file attached to the note
                            function(note, callback) {
                                var data = new FormData(),
                                url = app.api.buildFileURL({
                                    module: note.module,
                                    id: note.id,
                                    field: 'filename'
                                });

                                data.append('filename', file);
                                data.append('OAuth-Token', app.api.getOAuthToken());
                                $.ajax({
                                    url: url,
                                    type: 'POST',
                                    data: data,
                                    processData: false,
                                    contentType: false
                                }).then(function() {
                                    callback(null);
                                }, function() {
                                    callback(true);
                                });
                            },
                            //then create the 'attach' type activity
                            function(callback) {
                                var activity = app.data.createBean('Activities'),
                                    payload = {
                                        activity_type: 'attach',
                                        parent_id: noteAttrs.parent_id || null,
                                        parent_type: noteAttrs.parent_type || null,
                                        data: {
                                            noteId: note.id,
                                            filename: file.name,
                                            mimetype: file.type,
                                            size: file.size
                                        }
                                    };

                                activity.save(payload, {
                                    success: function(activityModel) {
                                        self.collection.add(activityModel);
                                        callback(null, activityModel);
                                    },
                                    error: function() {
                                        callback(true);
                                    }
                                });
                            }
                        ], function(err, activity) {
                            var options;

                            app.alert.dismiss('uploading_attachments');
                            component.trigger('attachments:end');
                            if (err) {
                                app.alert.show('upload_failed', {
                                    level: 'error',
                                    messages: app.lang.get('LBL_EMAIL_ATTACHMENT_UPLOAD_FAILED')
                                });
                            } else {
                                options = _.extend({recursive: false}, self.context.get('collectionOptions') || {});
                                self.context.reloadData(options);
                                self.clearAttachments.call(self);
                            }
                        });
                    });
                });
            },

            /**
             * Map parentId and parentType into note attributes
             * Do nothing if parentId or parentType are empty
             *
             * @private
             */
            _mapNoteParentAttributes: function() {
                var parentId = this.context.parent.get('model').id;
                var parentType = this.context.parent.get('model').module;

                if (parentType && parentId) {
                    switch (parentType) {
                        case 'Contacts':
                            return {
                                'parent_id': parentId,
                                'parent_type': parentType,
                                'contact_id': parentId
                            };
                        case 'Home':
                            return {
                                'parent_type': parentType
                            };
                        default:
                            return {
                                'parent_id': parentId,
                                'parent_type': parentType
                            };
                    }
                } else if (parentType) {
                    return {
                        'parent_type': parentType
                    };
                }
                return {};
            }
        });
    });
})(SUGAR.App);
