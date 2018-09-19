# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@saml @idp
Feature: Check SAML IdP initiated log out
  Log in as SAML user and check SAML IdP initiated log out

  @logout
  Scenario: Check SAML IdP initiated log out
    Given I use behat-tests-mango-saml-base Mango instance
    Given I save mango user list
    Given I am on "/"
    When I wait for the page to be loaded
    And I wait until the loading is completed
    And I wait until login popup is opened
    And I switch to login popup
    And I wait until the loading is completed
    Then I should see "Enter your username and password"
    When I fill in "username" with "user1"
    And I fill in "password" with "user1pass"
    And I press "Login"
    And I switch to main window
    And I wait until the loading is completed
    And I skip login wizard
    And I wait for element ".profileactions-logout"
    And I initiate SAML logout
    And I wait until login popup is opened
    And I switch to login popup
    And I wait until the loading is completed
    Then I should see "Enter your username and password"
    And I close login popup
