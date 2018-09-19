# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@local
Feature: bwc

  @bwc
  Scenario: Testing BWC and sidecar
    Given I am on "/"
    When I wait until the loading is completed
    And I fill in "admin" for "username"
    And I fill in "admin" for "password"
    And I press "login_button"
    And I wait for the page to be loaded
    And I wait until the loading is completed
    And I skip login wizard
    Then I should not see "You must specify a valid username and password."
    Then I should see "Home Dashboard"
    When I click "#userList"
    And I click ".administration"
    When I wait for the page to be loaded
    And I wait until the loading is completed
    And I switch to BWC
    And I follow "Password Management"
    And I wait for the page to be loaded
    Then I should see "Password Requirements"
    Then I should see "LDAP Support"
    Then I should see "SAML Authentication"
    When I switch to sidecar
    And I follow "Accounts"
    When I wait until the loading is completed
    Then I should see "Accounts"
    Then I should see "My Accounts"
    When I click "#userList"
    And I click ".profileactions-logout"