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
 * @class View.Fields.Base.TagField
 * @alias SUGAR.App.view.fields.BaseTagField
 * @extends View.Fields.Base.BaseField
 */
({
    /**
     * HTML tag of the append tag checkbox.
     *
     * @property {String}
     */
    appendTagInput: 'input[name=append_tag]',

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        this._super('initialize', [options]);

        // init bean collection used for type aheads
        this.filterResults = app.data.createBeanCollection('Tags');

        // initialize value to empty array
        if (!this.model.has(this.name)) {
            this.model.setDefault(this.name, []);
        }

        // Set append as default when mass updating tags
        this.appendTagValue = true;
        this.model.setDefault('tag_type', this.appendTagValue ? '1' : '0');
    },

    /**
     * @inheritdoc
     */
    _render: function() {
        this.setTagList();

        this._super('_render');

        this.initializeSelect2();
        this.$select2.on('change', _.bind(this.storeValues, this));
        this.$select2.on('select2-selecting', this.handleNewSelection);
    },

    /**
     * Set up tagList variable for use in the list view
     */
    setTagList: function() {
        var self = this;
        this.value = this.getFormattedValue();
        this.tagList = [];
        if (this.value) {
            _.each(this.value, function(tag){
                if (_.isString(tag)) {
                    self.tagList.push(tag);
                } else {
                    self.tagList.push(tag.name);
                }
            })
            this.tagList = this.tagList.join(', ');
        }
    },

    /**
     * @inheritdoc
     */
    format: function(value) {
        return _.map(value, function(tag){
            return _.extend(tag, {encodedValue: encodeURIComponent(tag.name)});
        });
    },

    /**
     * Overrides select2 function. For more details, check out select2's documentation
     *
     * @param term
     * @param results
     * @return {Mixed}
     * @private
     */
    _createSearchChoice: function(term, results) {
        // If tag is for filter, don't allow new choices to be selected
        if (this.view.action === 'filter-rows') {
            return false;
        }

        // Trim up the term for sanity sake
        term = $.trim(term);

        // Check previously found results to see tag exists with different casing
        if (results && results.length) {
            if (_.find(results, function(tag) {
                return tag.text.toLowerCase() === term.toLowerCase();
            })) {
                return false;
            }
        }
        
        // Check if input is empty after trim
        if (term === '') {
            return false;
        }

        // Check for existence amongst tags that exist but haven't been saved yet
        if (this.checkExistingTags(term)) {
            return false;
        }

        return {
            id: term,
            text: term + ' ' + app.lang.get('LBL_TAG_NEW_TAG'),
            locked: false,
            newTag: true
        };
    },

    /**
     * Check tag select2's currently selected tags for term to see if it already exists (case insensitive)
     * @param term term to be checked
     * @return {boolean} `true` if tag exists already, `false` otherwise
     */
    checkExistingTags: function(term) {
        if (this.$select2 && _.isFunction(this.$select2.val)) {
            var currentSelections = this.$select2.val().split(',');
        }
        if (currentSelections && currentSelections.length) {
            if (_.find(currentSelections, function(tag) {
                return tag.toLowerCase() === term.toLowerCase();
            })) {
                return true;
            }
        }

        return false;
    },

    /**
     * Overrides select2 function. For more details, check out select2's documentation
     * @param query
     * @private
     */
    _query: function(query) {
        var self = this,
            shortlist = {results: []};

        // Trim the query term right up front since it needs to be clean
        query.term = $.trim(query.term);

        this.filterResults.filterDef = {
            'filter': [{
                'name_lower': { '$starts': query.term.toLowerCase() }
            }]
        };

        // Tags should always be available because it's public
        this.filterResults.module = 'Tags';
        this.filterResults.fetch({
            success: function(data) {
                shortlist.results = self.parseRecords(data.models);

                //Format results so that already existing records don't show up
                shortlist.results = _.reject(shortlist.results, function(result) {
                    return self.checkExistingTags(result.text)
                });

                query.callback(shortlist);
            },
            error: function() {
                app.alert.show('collections_error', {
                    level: 'error',
                    messages: 'LBL_TAG_FETCH_ERROR'
                });
            }
        });
    },

    /**
     * Upon selection of a tag, if it's a new tag, get rid of the text indicating new tag
     * @param {event} e
     */
    handleNewSelection: function(e) {
        // For new tags, look for New Tag indicator and remove it if it's there
        if (e.object.newTag) {
            var newTagIdx = e.object.text.lastIndexOf(' ' + app.lang.get('LBL_TAG_NEW_TAG'));
            e.object.text = e.object.text.substr(0, newTagIdx);
        }
    },

    /**
     * Initialize select2 jquery widget
     */
    initializeSelect2: function() {
        var self = this,
            escapeChars = '!\"#$%&\'()*+,./:;<=>?@[\\]^`{|}~';

        this.$select2 = this.$('.select2field').select2({
            placeholder: '',
            minimumResultsForSearch: 5,
            minimumInputLength: 1,
            tags: true,
            multiple: true,
            closeOnSelect: true,
            width: '100%',
            containerCssClass: 'select2-choices-pills-close',
            tokenSeparators: [','],

            initSelection: function(element, callback) {
                var data = self.parseRecords(self.value);
                callback(data);
            },

            createSearchChoice: _.bind(this._createSearchChoice, this),
            query: _.debounce(_.bind(this._query, this), 300),

            sortResults: function(results, container, query) {
                results = _.sortBy(results, 'text');
                return results;
            }
        });

        this.setSelect2Records();

        // Workaround to make select2 treat enter the same as it would a comma (INT-668)
        this.$('.select2-search-field > input.select2-input').on('keyup', function(e) {
            if (e.keyCode === 13) {
                var val = self.$('input.select2-input').val();

                // Trim the tag
                val = $.trim(val);

                // Prevent blank tags
                if (val === '') {
                    return;
                }

                // Sanitize input
                if (escapeChars.indexOf(val.charAt(0)) >= 0) {
                    val = '\\\\' + val;
                }

                var tags = self.$select2.select2('data');

                // If the current input already exists as a tag (case insensitive), just exit
                var exists = _.find(tags, function(tag) {
                    return tag.id.toLowerCase() === val.toLowerCase();
                });
                if (exists) {
                    // Close the search box and return
                    self.$select2.select2('close');
                    // Re-opens the search box with the default message
                    // (this is to maintain consistency with select2's OOB tokenizer)
                    self.$select2.select2('open');
                    return;
                }

                // Otherwise, create a tag out of current input
                tags.push({id: val, text: val, locked: false});
                self.$select2.select2('data', tags, true);
                e.preventDefault();

                // Close the search box
                self.$select2.select2('close');
            }
        });
    },

    /**
     * Format related records in select2 format
     * @param {array} list of objects/beans
     */
    parseRecords: function(list) {
        var results = [];

        _.each(list, function(item) {
            var record = item;

            // we may have a bean from a collection
            if (_.isFunction(record.toJSON)) {
                record = record.toJSON();
            }
            if (_.isString(record)) {
                results.push({id: record, text: record});
            } else {
                results.push({id: record.name, text: record.name});
            }
        });

        return results;
    },

    /**
     * Store selected/removed values on our field which is put to the server
     * @param {event} e - event data
     */
    storeValues: function(e) {
        this.value = app.utils.deepCopy(this.value) || [];
        if (e.added) {
            app.analytics.trackEvent('click', 'tag_pill_added');
            // Check if added is an array or a single object
            if (_.isArray(e.added)) {
                // Even if it is an array, only one object gets added at a time,
                // so we just need it to be the first element
                e.added = e.added[0];
            }

            // Check to see if the tag we're adding has already been added.
            var valFound = _.find(this.value, function(vals) {
                return vals.name === e.added.text;
            });

            if (!valFound) {
                this.value.push(e.added.text);
            }
        } else if (e.removed) {
            app.analytics.trackEvent('click', 'tag_pill_removed');
            // Remove the tag
            this.value = _.reject(this.value, function(record) {
                if (_.isString(record)) {
                    return record === e.removed.text;
                } else {
                    return record.name === e.removed.text;
                }
            });
        }
        this.model.set('tag', this.value);
    },

    /**
     * Sanitize the tags and set the select2
     */
    setSelect2Records: function() {
        var escapeChars = '!\"#$%&\'()*+,./:;<=>?@[\\]^`{|}~';
        var records = _.map(this.value, function(record) {
            if (_.isString(record)) {
                // If a special character is the first character of a tag, it breaks select2 and jquery and everything
                // So escape that character if it's the first char
                if (escapeChars.indexOf(record.charAt(0)) >= 0) {
                    return '\\\\' + record;
                }
                return record;
            }
            if (escapeChars.indexOf(record.name.charAt(0)) >= 0) {
                return '\\\\' + record.name;
            }
            return record.name;
        });

        this.$select2.select2('val', records);

    },

    /**
     * Avoid rendering process on Select2 change in order to keep focus
     * @override
     */
    bindDataChange: function() {
        if (this.model) {
            this.model.on('change:' + this.name, function() {
                if (!_.isEmpty(this.$select2.data('select2'))) {
                    this.setTagList();
                    this.setSelect2Records();
                } else {
                    this.render();
                }
            }, this);
        }
    },

    /**
     * Override to remove default DOM change listener, we use Select2 events instead
     * @override
     */
    bindDomChange: function() {
        // Borrowed from team set
        var $el = this.$(this.appendTagInput);
        if ($el.length) {
            $el.on('change', _.bind(function() {
                this.appendTagValue = $el.prop('checked');
                this.model.set('tag_type', this.appendTagValue ? '1' : '0');
            }, this));
        }
    },

    /**
     * @inheritdoc
     */
    unbindDom: function() {
        // This line is likewise borrowed from team set
        this.$(this.appendTagInput).off();
        this.$('.select2field').select2('destroy');
        this._super('unbindDom');
    }

})
