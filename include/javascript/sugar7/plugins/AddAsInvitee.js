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
        /**
         * Add As Invitee plugin manages listening for changes to the parent
         * and assigned user fields on event type records (Calls & Meetings)
         * and adds the person record as an invitee on the call/meeting.
         *
         * This plugin listens for changes to the parent and assigned user
         * fields, but waits to do this until render to accommodate create
         * scenario where data may have been pre-populated without triggering
         * a change event.
         *
         * In create scenario, a change listener is added immediately on render,
         * but for record edit view, we need to wait until after sync so we can
         * detect a change event accurately.
         *
         * For invitees that are added automatically in the create scenario,
         * we want these to be set as "default" so they are not reported as
         * unsaved changes if the user attempts to leave the page.
         *
         * This plugin is built to enhance {@link View.Views.Base.RecordView}
         * and its descendants.
         */
        app.plugins.register('AddAsInvitee', ['view'], {
            onAttach: function() {
                this.once('render', function() {
                    this.turnOffAutoInviteParent();

                    if (this.isFieldPrepopulatedOnCreate('parent_name')) {
                        this.handleParentChange(this.model, {default: true});
                    }

                    if (this.isFieldPrepopulatedOnCreate('assigned_user_name')) {
                        this.handleAssignedUserChange(this.model, {default: true});
                    }

                    if (this.isFieldPrepopulatedOnCreate('contact_id')) {
                        this.addContactFromContactIdField(this.model, {default: true});
                    }

                    if (this.model.isNew()) {
                        this.addChangeListener('parent_name', this.handleParentChange);
                        this.addChangeListener('assigned_user_name', this.handleAssignedUserChange);
                    } else {
                        this.model.once('sync', function() {
                            this.addChangeListener('parent_name', this.handleParentChange);
                            this.addChangeListener('assigned_user_name', this.handleAssignedUserChange);
                        }, this);
                    }
                }, this);
            },

            /**
             * Check if currently creating a record with the specified field
             * already pre-populated
             *
             * @param {string} fieldName
             * @return {Boolean}
             */
            isFieldPrepopulatedOnCreate: function(fieldName) {
                return this.model.isNew() && !_.isUndefined(this.model.get(fieldName));
            },

            /**
             * Adds an event listener for changes to the specified field and
             * calls the specified callback.
             *
             * @param {string} fieldName The field on which to listen for
             * changes.
             * @param {Function} callback The function that is called when a
             * change occurs.
             */
            addChangeListener: function(fieldName, callback) {
                this.model.on('change:' + fieldName, callback, this);
            },

            /**
             * If parent field changes, check if it is a possible invitee add as
             * an invitee
             *
             * Prevent invitee from being deleted in case where we are linking
             * the bean automatically.
             *
             * @param {Object} model
             * @param {Object} [options] Additional options to pass along when
             *   adding an invitee to the collection.
             */
            handleParentChange: function(model, options) {
                var parent = app.data.createBean(model.get('parent_type'), {
                    id: model.get('parent_id'),
                    name: model.get('parent_name')
                });

                if (this.isPossibleInvitee(parent)) {
                    if (this._isCreateAndLinkAction(parent, model)) {
                        parent.deletable = false;
                    }
                    this.addAsInvitee(parent, options);
                }
            },

            /**
             * Adds the assigned user as an invitee if the assigned user is
             * changed.
             *
             * @param {Data.Bean} model
             * @param {Object} [options] Additional options to pass along when
             *   adding an invitee to the collection.
             */
            handleAssignedUserChange: function(model, options) {
                var user;

                user = app.data.createBean('Users', {
                    id: model.get('assigned_user_id'),
                    name: model.get('assigned_user_name')
                });

                if (this.isPossibleInvitee(user)) {
                    this.addAsInvitee(user, options);
                }
            },

            /**
             * Adds the contact defined in the contact field as an invitee
             *
             * Prevent invitee from being deleted in case where we are linking
             * the bean automatically.
             *
             * @param {Data.Bean} model
             * @param {Object} [options] Additional options to pass along when
             *   adding an invitee to the collection.
             */
            addContactFromContactIdField: function(model, options) {
                var contact = app.data.createBean('Contacts', {
                    id: model.get('contact_id'),
                    name: model.get('contact_name')
                });

                if (this.isPossibleInvitee(contact)) {
                    if (this._isCreateAndLinkAction(contact, model)) {
                        contact.deletable = false;
                    }
                    this.addAsInvitee(contact, options);
                }
            },

            /**
             * Will the bean be linked automatically?
             * (for example, create & relate action from subpanel)
             *
             * @param {Data.Bean} invitee
             * @param {Data.Bean} model
             * @private
             */
            _isCreateAndLinkAction: function(invitee, model) {
                return (!_.isUndefined(model.link) &&
                    !_.isUndefined(model.link.bean) &&
                    model.link.bean.module === invitee.module &&
                    model.link.bean.id === invitee.id);
            },

            /**
             * Person is a possible invitee if it has an id and is one of the
             * possible invitee modules.
             *
             * @param {Object} person
             * @return {Boolean}
             */
            isPossibleInvitee: function(person) {
                var inviteeModuleList = ['Leads', 'Contacts', 'Users'];
                var invitees = this.model.get('invitees');

                return (!_.isEmpty(person.id) &&
                    _.contains(inviteeModuleList, person.module) &&
                    !_.isUndefined(invitees));
            },

            /**
             * Tell the server not to auto invite parent field because we are managing
             * this behavior on the front end. This is a flag sent to the server to
             * maintain the mobile app behavior which auto invites the parent record
             */
            turnOffAutoInviteParent: function() {
                this.model.setDefault('auto_invite_parent', false);
            },

            /**
             * Add the given person as an invitee
             *
             * @param {Object} person
             * @param {Object} [options] Additional options to pass along when
             *   adding an invitee to the collection.
             */
            addAsInvitee: function(person, options) {
                var complete;

                complete = _.bind(function complete(request) {
                    options = _.extend({merge: true}, (options || {}));
                    this.model.get('invitees').add(person, options);
                }, this);

                if (person.fields && _.has(person.fields, 'email') && _.isFunction(person.fetch)) {
                    person.fetch({fields: ['email'], complete: complete});
                } else {
                    complete();
                }
            }
        });
    });
})(SUGAR.App);
