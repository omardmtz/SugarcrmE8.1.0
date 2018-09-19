# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@saml @login @sp @same-window @no-user-provision
Feature: SAML SP Initiated Login
  SugarCRM instance is configured:
  - to use external SAML authentication
  - in the same window
  - automatic user provision is off.
  SugarCRM User tries to perform login actions from the SugarCRM side.

  Scenario: Login with correct credentials
    Given I use behat-tests-mango-saml-same-window-no-user-provision Mango instance
    Given I save mango user list
    Given I am on "/"
    When I wait for element ".welcome"
    And I wait for the page to be loaded
    And I click "a[title='Log In']"
    Then I wait for the page to be loaded
    And I wait until the loading is completed
    Then I should see "Enter your username and password"
    And I fill in "user1" for "username"
    And I fill in "user1pass" for "password"
    And I press "Login"
    When I wait until the loading is completed
    Then I should not see "Accounts"
    And I should not see "Contacts"
    And I should see "Log In"
    And I initiate SAML logout
