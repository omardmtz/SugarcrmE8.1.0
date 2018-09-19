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
 * Extensions to the Contacts sidecar bean.
 * Included by JSGroupings.php
 */
(function(app) {
    app.events.on("app:sync:complete", function(){

        //Very important to prevent infinite loop. Otherwise it is Bean initialize method who is extended
        if (!app.data.getModelClasses()['Contacts']) {
            return;
        }

        var contactsClass = app.data.getBeanClass("Contacts");

        /**
         * Custom validation needed for Contacts beans when changing portal_name field.  We need to do a uniqueness
         * check to make sure no two contacts have the same portal_name since it is the user id for Portal.
         *
         * @param {Object} fields Hash of field definitions to validate.
         * @param {Object} errors Error validation errors
         * @param {Function} callback Async.js waterfall callback
         */
        contactsClass.prototype._doValidatePortalName = function(fields, errors, callback) {
            var self = this,
                skip = false;
            if(_.isUndefined(this.get("id"))){
                // If new and portal_name is not set, skip checking portal_name
                if(!this.has("portal_name") || this.get("portal_name") === ""){
                    skip = true;
                }
            } else {
                // If not new and portal name has not changed since last sync, skip checking portal_name
                if (_.isUndefined(this.changedAttributes(this.getSynced())["portal_name"])) {
                    skip = true;
                }
            }

            if(skip){
                callback(null, fields, errors);
                return;
            }

            // portal_name was changed
            var currentName = self.get("portal_name");
            var alertOptions = {
                title: app.lang.get("LBL_VALIDATING"),
                level: "process"
            };
            app.alert.show('validation', alertOptions);

            app.api.records('read', 'Contacts', null, {
                filter: [
                    {
                        portal_name: currentName
                    }
                ]
            }, {
                success: function(data){
                    if(data.records && data.records.length > 0){
                        /**
                         * If there is more than one Contact with this portal_name
                         * or the found record is not the same as the current one,
                         *   then we have a duplicate.
                         */
                        if(data.records.length > 1 || data.records[0].id != self.get("id")){
                            errors['portal_name'] = {
                                ERR_EXISTING_PORTAL_USERNAME: ''
                            };
                        }
                    }
                },
                error: function(){
                    errors['portal_name'] = {
                        ERR_PORTAL_NAME_CHECK: ''
                    };
                },
                /**
                 * After check is done, close alert and trigger the completion of the validation to the editor
                 */
                complete: function(){
                    app.alert.dismiss('validation');
                    callback(null, fields, errors);
                }
            });
            return;
        };

        /**
         * Adds the validation task to the model
         * @override
         * @param options
         */
        contactsClass.prototype.initialize = function(options) {
            app.data.beanModel.prototype.initialize.call(this, options);

            this.addValidationTask('portal_name_unique', _.bind(this._doValidatePortalName, this));
        };
    });



})(SUGAR.App);
