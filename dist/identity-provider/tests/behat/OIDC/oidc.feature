# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@login @oidc
Feature: OIDC flow
  Verify OIDC login flow for local user. User not exists in Sugar
  Verify OIDC login flow for local user. Tenant does not provided in auth url
  Verify OIDC login flow for local user. User rejects consent request
  Verify OIDC login flow for LDAP user who has previously been provisioned. User not exists in Sugar
  Verify OIDC login flow for LDAP user who has previously been provisioned. User exists in Sugar
  Verify OIDC login flow for LDAP user who has not previously been provisioned
  Verify OIDC login flow for legacy clients with invalid password
  Verify OIDC login flow for legacy clients
  Verify OIDC refresh token flow
  Verify OIDC sudo flow

  Scenario: Verify OIDC login flow for local user. User not exists in Sugar
    Given I try to get Mango resource for "opi" platform
    Then I navigate to OIDC provider with tenant "srn:cloud:idp:eu:0000000001:tenant"
    Then I should see IdP login page
    Given I do IdP login as "max" with password "max"
    Then I confirm consent request
    Then I get access_token of OPI platform
    When I use access_token for GET request "/rest/v11/me?platform=opi"
    Then I verify response contains "user_name" with value "max"
    And I verify response contains "full_name" with value "Max Jensen"
    And I verify response contains "address_country" with value "testcountry"

  Scenario: Verify OIDC login flow for local user. Tenant does not provided in auth url
    Given I try to get Mango resource for "opi" platform
    Then I navigate to OIDC provider with tenant ""
    And I should see IdP login page
    Then I fill in "srn:cloud:idp:eu:0000000001:tenant" for "tid"
    Then I do IdP login as "max" with password "max"
    Then I confirm consent request
    Then I get access_token of OPI platform
    When I use access_token for GET request "/rest/v11/me?platform=opi"
    Then I verify response contains "user_name" with value "max"
    And I verify response contains "full_name" with value "Max Jensen"
    And I verify response contains "address_country" with value "testcountry"

  Scenario: Verify OIDC login flow for local user. User rejects consent request
    Given I try to get Mango resource for "opi" platform
    Then I navigate to OIDC provider with tenant "srn:cloud:idp:eu:0000000001:tenant"
    Then I should see IdP login page
    Given I do IdP login as "max" with password "max"
    Then I reject consent request
    And I check that current url contains "No consent"

  Scenario: Verify OIDC login flow for LDAP user who has previously been provisioned. User not exists in Sugar
    Given I try to get Mango resource for "opi" platform
    Then I navigate to OIDC provider with tenant "srn:cloud:idp:eu:0000000001:tenant"
    Then I should see IdP login page
    Given I do IdP login as "abey" with password "abey"
    Then I confirm consent request
    Given I get access_token of OPI platform
    Then I use access_token for GET request "/rest/v10/me?platform=opi"
    And I verify response contains "user_name" with value "abey"

  Scenario: Verify OIDC login flow for LDAP user who has previously been provisioned. User exists in Sugar
    Given I try to get Mango resource for "base" platform
    Then I navigate to OIDC provider with tenant "srn:cloud:idp:eu:0000000001:tenant"
    Then I should see IdP login page
    Given I do IdP login as "admin" with password "admin"
    Then I confirm consent request
    Given I get access_token of OPI platform
    Then I use access_token for GET request "/rest/v10/me"
    And I verify response contains "user_name" with value "admin"

  Scenario: Verify OIDC login flow for LDAP user who has not previously been provisioned
    Given I try to get Mango resource for "opi" platform
    Then I navigate to OIDC provider with tenant "srn:cloud:idp:eu:0000000001:tenant"
    Then I should see IdP login page
    Given I do IdP login as "tandav" with password "tandav"
    Then I confirm consent request
    Given I get access_token of OPI platform
    Then I use access_token for GET request "/rest/v10/me?platform=opi"
    And I verify response contains "user_name" with value "tandav"

  Scenario: Verify OIDC login flow for legacy clients with invalid password
    Given I am on the homepage
    And I wait until the loading is completed
    Given I login as "user2" with password "user3pass"
    Then I should see "Invalid credentials"

  Scenario Outline: Verify OIDC login flow for legacy clients
    Given I am on the homepage
    And I wait until the loading is completed
    Given I login as <username> with password <password>
    And I skip login wizard
    Then I should not see "Invalid credentials"
    Then I should see "Home Dashboard"
    When I click "#userList"
    And I click ".profileactions-logout"
    Examples:
      |     username | password    |
      |"sally"       | "sally"     |
      |"user2"       | "user2pass" |
      |"tandav"      | "tandav"    |

  Scenario: Verify OIDC refresh token flow
    Given I am on the homepage
    And I wait until the loading is completed
    Given I login as "sally" with password "sally"
    And I skip login wizard
    Then I should not see "Invalid credentials"
    Then I should see "Home Dashboard"
    And I change access token to "expired"
    When I am on "/#Meetings"
    And I wait until the loading is completed
    Then I compare access token with "expired" as "notEquals"
    Then I should see "Meetings List Dashboard"
    When I click "#userList"
    And I click ".profileactions-logout"

  Scenario: Verify OIDC sudo flow
    Given I am on the homepage
    And I wait until the loading is completed
    Given I login as "admin" with password "admin"
    And I skip login wizard
    Then I get access_token from local storage
    Then I use access_token for POST request "/rest/v11/oauth2/sudo/sally"
    And I get access_token form sugar token response
    And I use access_token for GET request "/rest/v11/me"
    Then I verify response contains "user_name" with value "sally"
    When I click "#userList"
    And I click ".profileactions-logout"
