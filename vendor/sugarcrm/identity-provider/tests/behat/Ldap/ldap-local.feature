# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@ldap @login @local
Feature: Mixed LDAP and Local authentication
  User with "LDAP auth only" should be able to login with LDAP, but not with Local credentials.
  User without "LDAP auth only" should be able to login both with LDAP and Local credentials.

  Scenario: Existing LDAP user tries to login using local auth, but is configured with "LDAP Authentication Only"
    Given As "sugarAdmin" filling in the following LDAP settings:
      | field | type | value |
      | ldap_hostname | text | ldap |
      | ldap_port | text | 389 |
      | ldap_base_dn | text |  dc=openldap,dc=com |
      | ldap_login_filter | text | |
      | ldap_bind_attr    | text | dn |
      | ldap_login_attr    | text | uid |
      | ldap_group_checkbox    | checkbox | |
      | ldap_authentication_checkbox    | checkbox | checked |
      | ldap_admin_user    | text | cn=admin,ou=admins,dc=openldap,dc=com |
      | ldap_admin_password    | text | admin&password |
      | ldap_auto_create_users | checkbox | |
    And I wait for element "input[name=username]"
    Then As "sugarAdmin" I create Sugar user with the following properties:
      | field  | value |
      | user_name | user1 |
      | user_hash | user1Local |
      | email1    | user1@test.com |
      | status    | Active |
      | external_auth_only | 1 |
    And I wait for element "input[name=username]"
    Then I login as "user1" with password "user1"
    And I wait for element "input[name=first_name]"
    And I should see "Setup your user information"
    Then I skip login wizard
    And I wait until the loading is completed
    And I wait for the page to be loaded
    And I should see "Accounts"
    And I wait until the loading is completed
    Then I logout
    And I wait for element "input[name=username]"
    Then I login as "user1" with password "user1Local"
    And I should see "Invalid Credentials"
    And I should not see "Accounts"

  Scenario: Existing LDAP user tries to login using local auth, and is configured without "LDAP Authentication Only"
    Given As "sugarAdmin" filling in the following LDAP settings:
      | field | type | value |
      | ldap_hostname | text | ldap |
      | ldap_port | text | 389 |
      | ldap_base_dn | text |  dc=openldap,dc=com |
      | ldap_login_filter | text | |
      | ldap_bind_attr    | text | dn |
      | ldap_login_attr    | text | uid |
      | ldap_group_checkbox    | checkbox | |
      | ldap_authentication_checkbox    | checkbox | checked |
      | ldap_admin_user    | text | cn=admin,ou=admins,dc=openldap,dc=com |
      | ldap_admin_password    | text | admin&password |
      | ldap_auto_create_users | checkbox | |
    And I wait for element "input[name=username]"
    Then As "sugarAdmin" I create Sugar user with the following properties:
      | field  | value |
      | user_name | user1 |
      | user_hash | user1Local |
      | email1    | user1@test.com |
      | status    | Active |
      | external_auth_only | 0 |
    And I wait for element "input[name=username]"
    Then I login as "user1" with password "user1"
    And I wait for element "input[name=first_name]"
    And I should see "Setup your user information"
    Then I skip login wizard
    And I wait until the loading is completed
    And I wait for the page to be loaded
    And I should see "Accounts"
    And I wait until the loading is completed
    Then I logout
    And I wait for element "input[name=username]"
    Then I login as "user1" with password "user1Local"
    And I should not see "Invalid Credentials"
    And I wait for the page to be loaded
    And I should see "Accounts"
    Then I logout
