# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@saml @login @sp @same-window
Feature: SAML SP Initiated Login
  SugarCRM instance is configured:
  - to use external SAML authentication
  - in the same window
  - automatic user provision is on.
  SugarCRM User tries to perform login actions from the SugarCRM side.

  Scenario: Explore SugarCRM instance landing page
    Given I use behat-tests-mango-saml-same-window Mango instance
    Given I save mango user list
    Given I am on "/"
    When I wait for element ".welcome"
    Then I should see "Log In"
    Then I should see "Show log in form"

  Scenario: Login with incorrect credentials
    Given I use behat-tests-mango-saml-same-window Mango instance
    Given I save mango user list
    Given I am on "/"
    When I wait for element ".welcome"
    And I wait for the page to be loaded
    And I click "a[title='Log In']"
    Then I wait for the page to be loaded
    And I wait until the loading is completed
    Then I wait to be redirected to the saml server
    Then I should see "Enter your username and password"
    And I fill in "user1" for "username"
    And I fill in "someIncorrectPassword" for "password"
    And I press "Login"
    Then I should see "Incorrect username or password"

  Scenario: Login with correct credentials
    Given I use behat-tests-mango-saml-same-window Mango instance
    Given I save mango user list
    Given I am on "/"
    When I wait for element ".welcome"
    Then I wait for the page to be loaded
    And I click "a[title='Log In']"
    Then I wait for the page to be loaded
    And I wait until the loading is completed
    Then I wait to be redirected to the saml server
    Then I should see "Enter your username and password"
    And I fill in "user1" for "username"
    And I fill in "user1pass" for "password"
    And I press "Login"
    Then I wait for the page to be loaded
    When I wait until the loading is completed
    When I skip login wizard
    When I wait until the loading is completed
    Then I should see "Accounts"
    Then I should see "Contacts"
    But I should not see "You must specify a valid username and password."
    And I logout

  Scenario: Login with correct credentials returns user on the initially requested page
    Given Mark scenario as pending because Mango currently does not support relay-state

    Given I use behat-tests-mango-saml-same-window Mango instance
    Given I save mango user list
    Given I am on "/#Accounts"
    When I wait for element ".welcome"
    And I wait for the page to be loaded
    And I click "a[title='Log In']"
    Then I wait to be redirected to the saml server
    Then I wait for the page to be loaded
    And I wait until the loading is completed
    Then I should see "Enter your username and password"
    And I fill in "user1" for "username"
    And I fill in "user1pass" for "password"
    And I press "Login"
    When I wait until the loading is completed
    When I skip login wizard
    When I wait until the loading is completed
    Then I should be on "/#Accounts"
    But I should not see "You must specify a valid username and password."
    And I logout
