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
 * View that displays a Bar with module name and filter toggles for per module
 * search and module creation.
 *
 * @class View.Views.FilterView
 * @alias SUGAR.App.layout.FilterView
 * @extends View.View
 */
({
    previousTerms: {},
    events: {
        'keyup .dataTables_filter input': 'queueAndDelay'
    },
    _renderHtml: function() {
        // this needs to be reset every render because the field set on the collection changes
        this.searchFields = this.getSearchFields();
        app.view.View.prototype._renderHtml.call(this);
        this.layout.off("list:search:toggle", null, this);
        this.layout.on("list:search:toggle", this.toggleSearch, this);
    },
    getSearchFields: function() {
        var self = this;
        var moduleMeta = app.metadata.getModule(this.module);
        var results = new Array();
        // Allowed fields limited to int, varchar because of search limitations
        var allowedFields = ["int","varchar","name"];
        _.each(moduleMeta.fields, function(fieldMeta, fieldName) {
            var fMeta = fieldMeta;
            if(fMeta.unified_search && _.indexOf(self.collection.fields, fieldName) >= 0 && _.indexOf(allowedFields, fMeta.type) !== -1) {
                results.push(app.lang.get(fMeta.vname, self.module));
            }
        });
        return results;
    },
    queueAndDelay: function(evt) {
        var self = this;

        if(!self.debounceFunction) {
            self.debounceFunction = _.debounce(function(){
                var term, previousTerm;
                
                previousTerm = self.getPreviousTerm(this.module);
                term = self.$(evt.currentTarget).val();
                self.setPreviousTerm(term, this.module);
                
                if(term && term.length) {
                    self.setPreviousTerm(term, this.module);
                    self.fireSearchRequest(term);
                } else if(previousTerm && !term.length) {
                    self.fireSearchRequest();
                } 
            }, app.config.requiredElapsed || 500);
        } 
        self.debounceFunction();
    },
    fireSearchRequest: function(term) {
        var self = this;
        self.setPreviousTerm(term, this.module);
        this.layout.trigger("list:search:fire", term);
    },
    setPreviousTerm: function(term, module) {
        if(app.cache.has('previousTerms')) {
            this.previousTerms = app.cache.get('previousTerms');
        }
        if(module) {
            this.previousTerms[module] = term;
        }
        app.cache.set("previousTerms", this.previousTerms);
    },
    getPreviousTerm: function(module) {
        if(app.cache.has('previousTerms')) {
            this.previousTerms = app.cache.get('previousTerms');
            return this.previousTerms[module];
        }
    },
    toggleSearch: function() {
        var isOpened;
        this.$('.dataTables_filter').toggle();

        // Trigger toggled event. Presently, this is for the list-bottom view.
        // If the 'Show More' button is clicked and filter is opened, 
        // list-bottom adds q:term to pagination call. 
        isOpened = this.$('.dataTables_filter').is(':visible');
        this.layout.trigger('list:filter:toggled', isOpened);

        // Always clear last search term
        this.$('.dataTables_filter input').val('').focus();

        // If toggling filters closed, return to full "unfiltered" records 
        if(!isOpened) {
            this.fireSearchRequest();
        }
        return false;
    }
})
