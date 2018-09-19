# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@local @regression
Feature: User datetime preference
  When user has custom datetime preference
  And Mango has password expiration
  User can login - BR-5763

  @br-5763 @regression
  Scenario: Login user with custom datetime preference
    Given I am on "/"
    When As "sugarAdmin" I create Sugar user with the following properties:
      | field  | value |
      | user_name | userDateTime |
      | user_hash | userDateTime |
      | email1    | userDateTime@test.com |
      | status    | Active |
    Then I wait for the page to be loaded
    Given I login as "userDateTime" with password "userDateTime"
    Then I skip login wizard
    And I update user preferences with this data:
      | field | value |
      | datef | m.d.Y |
      | timef | h:i A |
    And I logout
    Then I wait until the loading is completed
    And I wait for the page to be loaded
    Given I login as "userDateTime" with password "userDateTime"
    Then I wait until the loading is completed
    And I wait for the page to be loaded
    And I wait until the loading is completed
    And I wait for the page to be loaded
    And I should see "Home Dashboard"
    And I wait until the loading is completed
    And I wait for the page to be loaded
    And I logout
    And I wait until the loading is completed
    And I wait for the page to be loaded
    # now the second time to check the bug
    Given I login as "userDateTime" with password "userDateTime"
    Then I wait until the loading is completed
    And I wait for the page to be loaded
    And I wait until the loading is completed
    And I wait for the page to be loaded
    And I should see "Home Dashboard"
    And I logout