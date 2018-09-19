# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@oidc @userManagement
Feature: User management
  When Mango is in IDM mode
  User management should be limited

  Scenario: No password tab on user profile
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
    Then I should not see "Password" in the "#EditView_tabs ul" element
    When I switch to sidecar
    And I click "#userList"
    And I click ".profileactions-logout"

  Scenario: Disabled user properties for editing in Mango
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
    Then I should see an "span#user_name.sugar_field" element
    And I should not see a "input#user_name.sugar_field" element
    Then I should see an "span#first_name.sugar_field" element
    And I should not see a "input#first_name.sugar_field" element
    Then I should see an "span#last_name.sugar_field" element
    And I should not see a "input#last_name.sugar_field" element
    Then I should see an "span#title.sugar_field" element
    And I should not see a "input#title.sugar_field" element
    Then I should see an "span#department.sugar_field" element
    And I should not see a "input#department.sugar_field" element
    Then I should see an "input#status[type='hidden']" element
    Then I should see an "span#address_street.sugar_field" element
    And I should not see a "input#address_street.sugar_field" element
    Then I should see an "span#address_city.sugar_field" element
    And I should not see a "input#address_city.sugar_field" element
    Then I should see an "span#address_state.sugar_field" element
    And I should not see a "input#address_state.sugar_field" element
    Then I should see an "span#address_country.sugar_field" element
    And I should not see a "input#address_country.sugar_field" element
    Then I should see an "span#address_postalcode.sugar_field" element
    And I should not see a "input#address_postalcode.sugar_field" element
    Then I should see an "span#reports_to_id.sugar_field" element
    And I should not see a "input#reports_to_name" element
    When I switch to sidecar
    And I click "#userList"
    And I click ".profileactions-logout"

  Scenario: Disable mass update fields in IDM mode
  Given I am on "/"
  When I wait until the loading is completed
  And I fill in "admin" for "username"
  And I fill in "admin" for "password"
  And I press "login_button"
  And I wait for the page to be loaded
  And I wait until the loading is completed
  And I skip login wizard
  Then I go to administration
  And I click "#user_management"
  And I switch to BWC
  And I wait for the page to be loaded
  And I wait until the loading is completed
  And I wait for the element "#massall_top"
  And I click "#massall_top"
  And I click "#massupdate_listview_top"
  Then I should not see an "#mass_status" element
  When I switch to sidecar
  And I click "#userList"
  And I click ".profileactions-logout"
