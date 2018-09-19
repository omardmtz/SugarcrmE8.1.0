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
 * @class View.Fields.Base.FollowField
 * @alias SUGAR.App.view.fields.BaseFollowField
 * @extends View.Fields.Base.RowactionField
 */
({
    /**
     * @inheritdoc
     *
     * This field doesn't support `showNoData`.
     */
    showNoData: false,

    events: {
        'click [data-event="list:follow:fire"]': 'toggleFollowing'
    },

    extendsFrom: 'RowactionField',

    initialize: function(options) {
        this._super("initialize", [options]);
        this.format();
    },
    bindDataChange: function() {
        if (this.model) {
            this.model.on("change:following", this.resetLabel, this);
        }

        // when the record is marked as favorite, it is subsequently followed by the current user (on server-side)
        // need to sync the client-side model, so an event is fired on the context from the favorite field upon success
        // set following on the model so we don't have to make a server request to get the latest value
        this.model.on("favorite:active", function() {
            this.model.set("following", true);
        }, this);
    },
    /**
     * Set current label and value since the follow button relates to the following
     *
     * @param value
     */
    format: function(value) {
        value = this.model.get("following");

        //For record view, the label should be diffent from other views
        //It also needs to have mouseover handlers for updating text
        if(this.tplName === "detail") {
            var label = value ? "LBL_FOLLOWING" : "LBL_FOLLOW";
            this.label = app.lang.get(label, this.module);
        } else {
            var label = value ? "LBL_UNFOLLOW" : "LBL_FOLLOW";
            this.label = app.lang.get(label, this.module);
        }
        return value;
    },
    /**
     * Reset label and triggers "show" handler to update parent controller dom
     */
    resetLabel: function() {
        this.render();
        //It should trigger the handler "show" to update parent controller
        //i.e. actiondropdown
        this.trigger("show");
    },
    unbindDom: function() {
        this.$("[data-hover=true]").off();
        this._super("unbindDom");
    },
    _render: function () {
        var module, mouseoverText, mouseoverClass, self = this;

        module = app.metadata.getModule(this.model.module);
        if (!module.activityStreamEnabled) {
            this.hide();
        } else {
            this._super("_render");

            if (this.tplName !== "detail") {
                return;
            }

            if (this.model.get("following")) {
                mouseoverText = app.lang.get("LBL_UNFOLLOW");
                mouseoverClass = "label-important";
            } else {
                mouseoverText = app.lang.get("LBL_FOLLOW");
                mouseoverClass = "label-success";
            }

            this.$("[data-hover=true]").on("mouseover",function () {
                $(this).text(mouseoverText).attr("class", "label").addClass(mouseoverClass);
            }).on("mouseout", function () {
                    var kls = self.model.get("following") ? "label-success" : "";
                    $(this).text(self.label).attr("class", "label").addClass(kls);
                });
        }
    },
    /**
     * Call REST API for subscribe and unsubscribe
     *
     * @param {Event} event The `click` event.
     */
    toggleFollowing: function(event) {
        var isFollowing = this.model.get("following");

        if(!_.isUndefined(isFollowing)) {
            var options = {
                alerts: false
            };
            if (this.model.follow(!isFollowing, options) === false) {
                app.logger.error('Unable to follow "' + this.model.module + '" record "' + this.model.id);
                return;
            }
        }
    }
})
