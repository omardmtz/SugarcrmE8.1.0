# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@local
Feature: Login

  @login
  Scenario: Login and logout
    Given I am on "/"
    When I wait until the loading is completed
    And I fill in "admin" for "username"
    And I fill in "admin" for "password"
    And I press "login_button"
    And I wait until the loading is completed
    And I skip login wizard
    Then I should not see "You must specify a valid username and password."
    Then I should see "Home Dashboard"
    When I click "#userList"
    And I click ".profileactions-logout"