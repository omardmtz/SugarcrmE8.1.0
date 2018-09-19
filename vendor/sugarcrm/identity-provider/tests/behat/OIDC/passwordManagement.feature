# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@passwordManagement @oidc
Feature: Password Management
  Verify Password Management page in SugarCRM
  Verify Password Management link in SugarCRM
  Verify Forgot password page in SugarCRM

  Scenario: Verify Password Management page in SugarCRM
    Given I am on the homepage
    And I wait until the loading is completed
    Given I login as "admin" with password "admin"
    And I wait until the loading is completed
    And I wait for the page to be loaded
    When I go to "/index.php?module=Administration&action=PasswordManager"
    And I switch to BWC
    And I wait for the element "#contentTable"
    Then I should see "This option is disabled in SugarCRM for IDM mode and available in Cloud Console"
    When I switch to sidecar
    When I click "#userList"
    And I click ".profileactions-logout"

  Scenario: Verify Password Management link in SugarCRM
    Given I am on the homepage
    And I wait until the loading is completed
    Given I login as "admin" with password "admin"
    And I wait until the loading is completed
    And I wait for the page to be loaded
    And I go to administration
    And I follow "Password Management"
    Then The document should open in a new tab with url "http://console.sugarcrm.local/password-management"
    When I click "#userList"
    And I click ".profileactions-logout"

  Scenario: Verify Forgot password page in SugarCRM
    Given I am on the homepage
    And I wait until the loading is completed
    Then I click "[href='#forgotpassword']"
    Then I should be on "http://console.sugarcrm.local/forgot-password/srn%3Acloud%3Aidp%3Aeu%3A0000000001%3Atenant"
