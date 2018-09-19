# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@saml @logout @sp @same-window
Feature: SAML SP Initiated Logout
  SugarCRM instance is configured:
  - to use external SAML authentication
  - in the same window
  - automatic user provision is on.
  SugarCRM User performs logout from the SugarCRM side.

  Scenario: Logout
    Given I use behat-tests-mango-saml-same-window Mango instance
    Given I save mango user list
    Given I am on "/"
    When I wait for element ".welcome"
    And I wait for the page to be loaded
    And I wait until the loading is completed
    And I click "a[title='Log In']"
    Then I wait for the page to be loaded
    Then I should see "Enter your username and password"
    And I fill in "user1" for "username"
    And I fill in "user1pass" for "password"
    And I press "Login"
    When I wait until the loading is completed
    When I skip login wizard
    And I wait for element "#userList"
    And I logout
    When I wait until the loading is completed
    And I wait for element ".welcome"
    Then I should see "You have been logged out."
    When I click "a[title='Log In']"
    And I wait for the page to be loaded
    And I wait for element ".welcome"
    Then I wait for element "a[name='external_login_button']"
    And I should not see a "a.disabled[name='external_login_button']" element
    And I should see "Log In"
