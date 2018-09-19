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
 * Setup Complete wizard page for the FirstLoginWizard.
 *
 * @class View.Views.Base.SetupCompleteWizardPageView
 * @alias SUGAR.App.view.views.BaseSetupCompleteWizardPageView
 * @extends View.Views.Base.WizardPageView
 */
({
    extendsFrom: "WizardPageView",
    /**
     * Name of wizard being displayed
     */
    wizardName : "",
    /**
     * Set flag for admin or user wizard so we can render the correct template
     * @override
     * @param options
     */
    initialize: function(options){
        //Extend default events to add listener for click events on links
        //FIXME: events should be data action driven instead of tied to css
        //TY-573 will address this problem
        this.events = _.extend({}, this.events, {
            "click a.thumbnail": "linkClicked",
            "click [name=start_sugar_button]:not(.disabled)": "next"
        });
        this._super("initialize", [options]);
        this.wizardName = this.context.get("wizardName") || "user";
    },
    /**
     * @override
     * @return {boolean}
     */
    isPageComplete: function(){
        return true;
    },
    /**
     * Event handler whenever a link is clicked that makes sure wizard is finished
     * We need to use app router for Sugar app links on complete page.
     * External links should always open onto new pages.
     *
     * @param ev
     */
    //FIXME: Each link should have its own handler. Will be addressed in TY-573
    linkClicked: function(ev){
        var href, redirectUrl,
            target = this.$(ev.currentTarget);
        if(this.$(target).attr("target") !== "_blank") {
            app.user.unset('show_wizard');
            ev.preventDefault();
            //Show the header bar since it is likely hidden
            $("#header").show();
            href = this.$(target).attr("href");
            // Check if bwc link; if so, we need to do bwc.login first
            if (href.indexOf('#bwc/') === 0) {
                redirectUrl = href.split('#bwc/')[1];
                app.bwc.login(redirectUrl);
            } else {
                // Not bwc, so use router navigate instead
                app.router.navigate($(ev.currentTarget).attr("href"), {trigger: true});
            }
        }
    },

    /**
     * @inheritdoc
     *
     * When the setup complete page is disposed, we can update user object since
     * the user setup is complete, to prevent routing to the setup wizard.
     */
    _dispose: function() {
        this._super('_dispose');
        app.user.unset('show_wizard');
    }
})
