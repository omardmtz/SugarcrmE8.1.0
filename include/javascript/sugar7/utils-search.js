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

        app.utils = _.extend(app.utils, {

            'GlobalSearch': {

                /**
                 * Formats models returned by the globalsearch api.
                 *
                 * @param {Data.BeanCollection} collection The collection of models to format.
                 * @param {boolean} linkableHighlights Whether the highlighted fields' `link` flag should be `true` or
                 *   not.
                 */
                formatRecords: function(collection, linkableHighlights) {
                    collection.each(function(model) {
                        if (model.formatted) {
                            return;
                        }

                        var highlights = model.get('_highlights');
                        if (!highlights) {
                            // We add a flag here so that when the user clicks on
                            // `More results...` we won't reformat the existing ones.
                            model.formatted = true;
                            return;
                        }

                        var moduleMeta = app.metadata.getModule(model.get('_module'));
                        var nameFormatValues = _.values(moduleMeta.nameFormat);

                        if (nameFormatValues.length) {
                            var personAttrs = _.pick(model.attributes, nameFormatValues);
                            _.each(personAttrs, function(val, key) {
                                personAttrs[key] = Handlebars.Utils.escapeExpression(val);
                            });

                            if (!highlights.full_name) {
                                var fullname = app.utils.formatNameModel(
                                    model.get('_module'),
                                    _.extend({}, personAttrs, highlights)
                                );

                                // Add the full_name to the highlights, and
                                // format it like it came from the server.
                                highlights.full_name = [fullname];

                                // Remove the other person attributes since
                                // they're all encapsulated in full_name.
                                highlights = _.omit(highlights, _.keys(personAttrs));
                            }
                        }

                        var formattedHighlights = _.map(highlights, function(val, key) {
                            // The `email` highlight contains an array following this
                            // format: {primary: {...}, secondary: {...}}.
                            // Here we'll keep the value of the primary and will
                            // skip the secondary. The secondary will be added
                            // as a new field in the highlights.
                            if (key === 'email') {
                                if (val.primary) {
                                    val = new Handlebars.SafeString(_.first(val.primary));
                                    var label = 'LBL_PRIMARY_EMAIL';
                                } else {
                                    return false;
                                }
                            } else {
                                val = new Handlebars.SafeString(_.first(val));
                            }

                            return {
                                name: key,
                                value: val,
                                label: label || moduleMeta.fields[key].vname,
                                link: linkableHighlights,
                                highlighted: true
                            };
                        });

                        // Since the _.map returns false for secondary email
                        // address we need to clean the array.
                        formattedHighlights = _.reject(formattedHighlights, function(highlight) {
                            return highlight === false;
                        });

                        var highlightedSecondaryEmail = highlights.email ?
                            highlights.email.secondary : null;

                        // Push a the secondary email field in the
                        // formattedHighlights, if any.
                        if (highlightedSecondaryEmail) {
                            formattedHighlights.push({
                                name: 'secondaryEmail',
                                type: 'email',
                                value: new Handlebars.SafeString(highlightedSecondaryEmail.join(', ')),
                                label: moduleMeta.fields.email.vname,
                                link: linkableHighlights,
                                highlighted: true
                            });
                        }

                        model.set('_highlights', formattedHighlights);

                        // We add a flag here so that when the user clicks on
                        // `More results...` we won't reformat the existing ones.
                        model.formatted = true;
                    });
                },

                /**
                 * Gets the view metadata from the given module, patches it to distinguish
                 * primary fields from secondary fields and disables the native inline
                 * ellipsis feature of fields.
                 *
                 * @param {string} module The module to get the metadata from.
                 * @param {Object} [options]
                 * @param {boolean} [options.linkablePrimary] Set to `false` if you want to
                 *   force preventing the primary fields from containing a link.
                 * @return {Object} The metadata object.
                 */
                getFieldsMeta: function(module, options) {
                    options = options || {};
                    var fieldsMeta = {};
                    var meta = _.extend({}, this.meta, app.metadata.getView(module, 'search-list'));
                    _.each(meta.panels, function(panel) {
                        if (panel.name === 'primary') {
                            fieldsMeta.primaryFields = this._setFieldsCategory(panel.fields, 'primary', options);
                        } else if (panel.name === 'secondary') {
                            fieldsMeta.secondaryFields = this._setFieldsCategory(panel.fields, 'secondary', options);
                        }
                    }, this);
                    fieldsMeta.rowactions = meta.rowactions;

                    return fieldsMeta;
                },

                /**
                 * Sets `primary` or `secondary` boolean to fields. Also, we set the
                 * `ellipsis` flag to `false` so that the field doesn't render in a div with
                 * the `ellipsis_inline` class.
                 *
                 * @param {Object} fields The fields.
                 * @param {String} category The field category. It can be `primary` or
                 *   `secondary`.
                 * @param {Object} [options] See {@link #getFieldsMeta} options param for the
                 *   list of available options.
                 * @return {Object} The enhanced fields object.
                 * @private
                 */
                _setFieldsCategory: function(fields, category, options) {
                    var fieldList = {};

                    _.each(fields, function(field) {
                        if (!fieldList[field.name]) {
                            fieldList[field.name] = _.extend({}, fieldList[field.name], field);
                        }
                        fieldList[field.name][category] = true;
                        fieldList[field.name].ellipsis = false;
                        if (category === 'primary' && options.linkablePrimary === false) {
                            fieldList[field.name].link = false;
                        }
                        if (category === 'secondary') {
                            fieldList[field.name].link = false;
                            if (field.type === 'email') {
                                fieldList[field.name].emailLink = false;
                            }
                        }
                    });

                    return fieldList;
                },

                /**
                 * Adds `highlighted` attribute to fields sent as `highlights` by the
                 * globalsearch API for a given model.
                 *
                 * This method clones viewdefs fields and replace them by
                 * the highlighted fields sent by the API.
                 *
                 * @param {Data.Bean} model The model.
                 * @param {Object} viewDefs The view definitions of the fields.
                 *   Could be definition of primary fields or secondary fields.
                 * @param {boolean} [add=false] `true` to add in the viewdefs the highlighted
                 *   fields if they don't already exist. `false` to skip them if they don't
                 *   exist in the viewdefs.
                 */
                highlightFields: function(model, viewDefs, add) {
                    //The array of highlighted fields
                    var highlighted = model.get('_highlights');
                    //The fields vardefs of the model.
                    var varDefs = model.fields;
                    viewDefs = _.clone(viewDefs) || {};

                    _.each(highlighted, function(field) {
                        var hasViewDefs = viewDefs[field.name]; // covers patching existing.
                        var addOrPatchExisting = (hasViewDefs || add); // shall we proceed.

                        // We want to patch the field def only if there is an existing
                        // viewdef for this field or if we want to add it if it doesn't exist
                        // (This is the case for secondary fields).
                        if (!addOrPatchExisting) {
                            return;
                        }

                        // Checks if the model has the field in its primary fields, if it
                        // does, we don't patch the field def because we don't want it to
                        // be in both secondary and primary fields.
                        if (!_.isUndefined(model.primaryFields) && model.primaryFields[field.name]) {
                            return;
                        }
                        viewDefs[field.name] = _.extend({}, varDefs[field.name], viewDefs[field.name], field);
                    });
                    return viewDefs;
                },

                /**
                 * Builds a url for the full search page for the given search
                 *
                 * @param {String} term The term to search
                 * @param {Object} [options]
                 * @param {Array} [options.modules] An array of modules to
                 *    search
                 * @return {String} The route to the full search page.
                 */
                buildSearchRoute: function(term, options) {
                    options = options || {};
                    // Ensure that the term is defined.
                    term = term ? encodeURIComponent(term) : '';
                    var paramString = '?';
                    var firstParamDefined = false;
                    var modules = options.modules;
                    if (modules && modules.length > 0) {
                        paramString = paramString + 'modules=' + modules.join(',');
                        firstParamDefined = true;
                    }
                    var tags = options.tags;
                    if (tags && tags.length > 0) {
                        if (firstParamDefined) {
                            paramString = paramString + '&'
                        }
                        var encodedTags = _.map(tags, function(tag){
                            return encodeURIComponent(tag);
                        });
                        paramString = paramString + 'tags=' + encodedTags.join(',');
                    }
                    return app.router.buildRoute('search', term + paramString);
                }
            }
        });
    });
})(SUGAR.App);
