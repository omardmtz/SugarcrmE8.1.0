# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@local @regression @userManagement
Feature: User management - regression
  Regression testing - verify that existing functionality works as expected

  Scenario: Password tab on user profile
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
    And I click ".profileactions-profile"
    When I wait for the page to be loaded
    And I wait until the loading is completed
    And I switch to BWC
    And I click "#edit_button"
    And I wait for the page to be loaded
    And I wait for the element "#EditView_tabs"
    Then I should see "Password" in the "#EditView_tabs ul" element
    When I switch to sidecar
    And I click "#userList"
    And I click ".profileactions-logout"
