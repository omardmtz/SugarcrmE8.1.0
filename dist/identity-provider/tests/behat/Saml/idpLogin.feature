# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

Feature: SAML IDP-initiated login
  When I log in to my SAML provider
  I want to be able to open SugarCRM instantly using link/button on IdP page

  @login @saml
  Scenario Outline: SAML IDP-initated login
    Given I use <mangoInstance> Mango instance
    Then I save mango user list
    Given I initiate IDP login for SPEntityId <SPEntityID>
    When I wait for the page to be loaded
    Then I should see "Enter your username and password"
    When I fill in "user1" for "username"
    And I fill in "user1pass" for "password"
    And I press "Login"
    Then I should be redirected to "/index.php"
    When I wait until the loading is completed
    Then I should not see "You must specify a valid username and password."
    When I skip login wizard
    And I wait until the loading is completed
    Then I should see "Home Dashboard"
    And I logout

  Examples:
    | mangoInstance                      | SPEntityID                    |
    | behat-tests-mango-saml-base        | logoutFlowWithRedirectBinding |
    | behat-tests-mango-saml-same-window | samlSameWindowRedirect        |