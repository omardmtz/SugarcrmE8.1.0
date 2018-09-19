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
 * @class View.Views.Base.Emails.ComposeAddressbookListView
 * @alias SUGAR.App.view.views.BaseEmailsComposeAddressbookListView
 * @extends View.Views.Base.FlexListView
 */
({
    extendsFrom: 'FlexListView',

    /**
     * Changed the address book view to use an independent mass collection
     *
     * The address book collection is not the same as the list view
     * collection and therefore we need to preserve the state of the address
     * book collection through changes to the list view collection.
     * `independentMassCollection: true` allows us to indicate that the
     * collections should not be treated as the same so that we are always
     * adding to the collection instead of resetting with completely new data.
     *
     * @property {boolean}
     */
    independentMassCollection: true,

    /**
     * @inheritdoc
     */
    initialize: function(options) {
        var plugins = [
            'ListColumnEllipsis',
            'Pagination',
            'MassCollection'
        ];

        this.plugins = _.union(this.plugins || [], plugins);
        this._super('initialize', [options]);
    },

    /**
     * Removes the event listeners that were added to the mass collection.
     *
     * @inheritdoc
     */
    unbindData: function() {
        var massCollection = this.context.get('mass_collection');

        if (massCollection) {
            this.stopListening(massCollection);
        }

        this._super('unbindData');
    },

    /**
     * Listens for changes to the list's mass collection. Anytime new rows are
     * selected or deselected, those models are synchronized in the selected
     * recipients collection (`this.model.get('to_collection')`).
     *
     * When the user enters a search term, the list is re-rendered with the
     * results of the search. Any recipients in the result set that have
     * already been selected are checked automatically.
     *
     * @inheritdoc
     */
    _render: function() {
        var massCollection = this.context.get('mass_collection');
        var selectedRecipients;
        var selectedRecipientsInList;

        /**
         * Models in the mass collection may be beans for Contacts, Leads, etc.
         * Those beans need to be converted to EmailParticipants beans when
         * they are added to the selected recipients collection.
         *
         * @param {Data|Bean} model
         * @return {Data|Bean}
         */
        function convertModelToEmailParticipant(model) {
            return app.data.createBean('EmailParticipants', {
                _link: 'to',
                parent: {
                    _acl: model.get('_acl') || {},
                    _erased_fields: model.get('_erased_fields') || [],
                    type: model.module,
                    id: model.get('id'),
                    name: model.get('name')
                },
                parent_type: model.module,
                parent_id: model.get('id'),
                parent_name: model.get('name')
            });
        }

        this._super('_render');

        selectedRecipients = this.model.get('to_collection');

        if (massCollection) {
            // Stop listening to changes on the mass collection. Those event
            // handlers will be recreated.
            this.stopListening(massCollection);

            // A single row was checked or all rows were checked.
            this.listenTo(massCollection, 'add', function(model) {
                var ep = convertModelToEmailParticipant(model);

                selectedRecipients.add(ep);
            });

            // A single row was unchecked or all rows were unchecked.
            this.listenTo(massCollection, 'remove', function(model) {
                var existingRecipient = selectedRecipients.findWhere({parent_id: model.get('id')});

                selectedRecipients.remove(existingRecipient);
            });

            // The mass collection was cleared. Only remove the recipients from
            // the selected recipients collection that are visible in the list
            // view.
            this.listenTo(massCollection, 'reset', function(newCollection, prevCollection) {
                var resetRecipients;
                var selectedRecipientsNotInList = _.difference(prevCollection.previousModels, this.collection.models);

                newCollection.add(selectedRecipientsNotInList);
                resetRecipients = _.map(newCollection.models, convertModelToEmailParticipant);
                selectedRecipients.reset(resetRecipients);
            });

            if (selectedRecipients.length > 0) {
                // Only add, to the mass collection, recipients that are
                // visible in the list view.
                selectedRecipientsInList = this.collection.filter(function(model) {
                    return !!selectedRecipients.findWhere({parent_id: model.get('id')});
                });
                massCollection.add(selectedRecipientsInList);
            }
        }
    }
})
