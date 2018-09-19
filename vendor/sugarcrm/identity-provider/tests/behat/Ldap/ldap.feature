# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@ldap @login
Feature: LDAP
  New user should not be able to login if provision disabled
  New user should be able to login and see complete registration screen
  User should be able to login using LDAP over SSL
  User should not be able to login if it does not belong to LDAP group
  User should be able to login if it belongs to LDAP group
  User should be able to login if LDAP Authentication checkbox not checked
  User should be able to login with a valid user filter
  User should not be able to login with a valid user filter but user is not in filter
  User should be able to login if username contains comma
  User should be able to login if group membership enabled and "User Attribute" value contains comma
  User should be able to login if group membership enabled and "With User DN" checked
  LDAP Injections in Username Field: User should see correct error messages

  Scenario: New LDAP user tries to login if provision is disabled
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
    Then I login as "user1" with password "user1"
    And I wait until the loading is completed
    And I wait for the page to be loaded
    And I should see "You must specify a valid username and password."

  Scenario: New LDAP user tries to login if provision is enabled
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
      | ldap_auto_create_users | checkbox | checked |
    And I wait for element "input[name=username]"
    Then I login as "user1" with password "user1"
    And I wait for element "input[name=first_name]"
    And I should see "Setup your user information"
    Then I skip login wizard
    And I wait until the loading is completed
    And I wait for the page to be loaded
    And I should see "Home Dashboard"
    Then I logout

  Scenario: User should be able to login using LDAP over SSL
    Given As "sugarAdmin" filling in the following LDAP settings:
      | field | type | value |
      | ldap_hostname | text | ldap_ssl |
      | ldap_port | text | 636 |
      | ldap_base_dn | text |  dc=openldap,dc=com |
      | ldap_login_filter | text | |
      | ldap_bind_attr    | text | dn |
      | ldap_login_attr    | text | uid |
      | ldap_group_checkbox    | checkbox | |
      | ldap_authentication_checkbox    | checkbox | checked |
      | ldap_admin_user    | text | cn=admin,ou=admins,dc=openldap,dc=com |
      | ldap_admin_password    | text | admin&password |
      | ldap_auto_create_users | checkbox | checked |
    And I wait for element "input[name=username]"
    Then I login as "user1" with password "user1"
    And I wait for element "input[name=first_name]"
    And I should see "Setup your user information"
    Then I skip login wizard
    And I wait until the loading is completed
    And I wait for the page to be loaded
    And I should see "Home Dashboard"
    Then I logout

  Scenario: New LDAP user tries to login if provision is enabled and LDAP Authentication checkbox not checked
    Given As "sugarAdmin" filling in the following LDAP settings:
      | field | type | value |
      | ldap_hostname | text | ldap |
      | ldap_port | text | 389 |
      | ldap_base_dn | text |  dc=openldap,dc=com |
      | ldap_login_filter | text | |
      | ldap_bind_attr    | text | dn |
      | ldap_login_attr    | text | entryDN |
      | ldap_group_checkbox    | checkbox | |
      | ldap_authentication_checkbox    | checkbox | |
      | ldap_auto_create_users | checkbox | checked |
    And I wait for element "input[name=username]"
    Then I login as "cn=user1,ou=people,dc=openldap,dc=com" with password "user1"
    And I wait for element "input[name=first_name]"
    And I should see "Setup your user information"
    Then I skip login wizard
    And I wait for the page to be loaded
    And I wait until the loading is completed
    And I should see "Home Dashboard"
    Then I logout

  @groups
  Scenario: LDAP user tries to login if it does not belong to group
    Given As "sugarAdmin" filling in the following LDAP settings:
      | field | type | value |
      | ldap_hostname | text | ldap |
      | ldap_port | text | 389 |
      | ldap_base_dn | text |  dc=openldap,dc=com |
      | ldap_login_filter | text | |
      | ldap_bind_attr    | text | dn |
      | ldap_login_attr    | text | uid |
      | ldap_group_checkbox    | checkbox | checked |
      | ldap_authentication_checkbox    | checkbox | checked |
      | ldap_admin_user    | text | cn=admin,ou=admins,dc=openldap,dc=com |
      | ldap_admin_password    | text | admin&password |
      | ldap_auto_create_users | checkbox | checked |
      | ldap_group_dn | text | ou=groups,dc=openldap,dc=com |
      | ldap_group_name | text | cn=Administrators |
      | ldap_group_attr | text | member |
      | ldap_group_user_attr | text | |
      | ldap_group_attr_req_dn | checkbox | |
    And I wait for element "input[name=username]"
    Then I login as "user1" with password "user1"
    And I wait until the loading is completed
    And I wait for the page to be loaded
    And I should see "You must specify a valid username and password"

  @groups
  Scenario: LDAP user login if it belongs to group
    Given As "sugarAdmin" filling in the following LDAP settings:
      | field | type | value |
      | ldap_hostname | text | ldap |
      | ldap_port | text | 389 |
      | ldap_base_dn | text |  dc=openldap,dc=com |
      | ldap_login_filter | text | |
      | ldap_bind_attr    | text | dn |
      | ldap_login_attr    | text | uid |
      | ldap_group_checkbox    | checkbox | checked |
      | ldap_authentication_checkbox    | checkbox | checked |
      | ldap_admin_user    | text | cn=admin,ou=admins,dc=openldap,dc=com |
      | ldap_admin_password    | text | admin&password |
      | ldap_auto_create_users | checkbox | checked |
      | ldap_group_dn | text | ou=groups,dc=openldap,dc=com |
      | ldap_group_name | text | cn=Administrators |
      | ldap_group_attr | text | member |
      | ldap_group_user_attr | text | |
      | ldap_group_attr_req_dn | checkbox | |
    And I wait for element "input[name=username]"
    Then I login as "abey" with password "abey"
    And I wait for element "input[name=first_name]"
    And I should see "Setup your user information"
    Then I skip login wizard
    And I wait for the page to be loaded
    And I wait until the loading is completed
    And I should see "Home Dashboard"
    Then I logout

  @groups
  Scenario: LDAP user login if it belongs to group and "With User DN" checked
    Given As "sugarAdmin" filling in the following LDAP settings:
      | field | type | value |
      | ldap_hostname | text | ldap |
      | ldap_port | text | 389 |
      | ldap_base_dn | text |  ou=people,dc=openldap,dc=com |
      | ldap_login_filter | text | |
      | ldap_bind_attr    | text | dn |
      | ldap_login_attr    | text | uid |
      | ldap_group_checkbox    | checkbox | checked |
      | ldap_authentication_checkbox    | checkbox | checked |
      | ldap_admin_user    | text | cn=admin,ou=admins,dc=openldap,dc=com |
      | ldap_admin_password    | text | admin&password |
      | ldap_auto_create_users | checkbox | checked |
      | ldap_group_dn | text | ou=groups,dc=openldap,dc=com |
      | ldap_group_name | text | cn=Administrators |
      | ldap_group_attr | text | member |
      | ldap_group_user_attr | text | cn |
      | ldap_group_attr_req_dn | checkbox | checked |
    And I wait for element "input[name=username]"
    Then I login as "abey" with password "abey"
    And I wait for element "input[name=first_name]"
    And I should see "Setup your user information"
    Then I skip login wizard
    And I wait for the page to be loaded
    And I wait until the loading is completed
    And I should see "Home Dashboard"
    Then I logout

  @groups
  Scenario: LDAP user login if it belongs to group and "User Attribute" value contains comma
    Given As "sugarAdmin" filling in the following LDAP settings:
      | field | type | value |
      | ldap_hostname | text | ldap |
      | ldap_port | text | 389 |
      | ldap_base_dn | text |  dc=openldap,dc=com |
      | ldap_login_filter | text | |
      | ldap_bind_attr    | text | dn |
      | ldap_login_attr    | text | cn |
      | ldap_group_checkbox    | checkbox | checked |
      | ldap_authentication_checkbox    | checkbox | checked |
      | ldap_admin_user    | text | cn=admin,ou=admins,dc=openldap,dc=com |
      | ldap_admin_password    | text | admin&password |
      | ldap_auto_create_users | checkbox | checked |
      | ldap_group_dn | text | ou=groups,dc=openldap,dc=com |
      | ldap_group_name | text | cn=Group1 |
      | ldap_group_attr | text | member |
      | ldap_group_user_attr | text | uid |
      | ldap_group_attr_req_dn | checkbox | |
    And I wait for element "input[name=username]"
    Then I login as "user1, comma" with password "user1comma"
    And I wait for element "input[name=first_name]"
    And I should see "Setup your user information"
    Then I skip login wizard
    And I wait for the page to be loaded
    And I wait until the loading is completed
    And I should see "Home Dashboard"
    Then I logout

  Scenario: New LDAP user tries to login if provision is enabled and user filter is present
    Given As "sugarAdmin" filling in the following LDAP settings:
      | field | type | value |
      | ldap_hostname | text | ldap |
      | ldap_port | text | 389 |
      | ldap_base_dn | text |  dc=openldap,dc=com |
      | ldap_login_filter | text | (objectClass=person) |
      | ldap_bind_attr    | text | dn |
      | ldap_login_attr    | text | uid |
      | ldap_group_checkbox    | checkbox | |
      | ldap_authentication_checkbox    | checkbox | checked |
      | ldap_admin_user    | text | cn=admin,ou=admins,dc=openldap,dc=com |
      | ldap_admin_password    | text | admin&password |
      | ldap_auto_create_users | checkbox | checked |
    And I wait for element "input[name=username]"
    Then I login as "user1" with password "user1"
    And I wait for element "input[name=first_name]"
    And I should see "Setup your user information"
    Then I skip login wizard
    And I wait for the page to be loaded
    And I wait until the loading is completed
    And I should see "Home Dashboard"
    Then I logout

  Scenario: New LDAP user tries to login if provision is enabled and user filter is present. User is not in filter
    Given As "sugarAdmin" filling in the following LDAP settings:
      | field | type | value |
      | ldap_hostname | text | ldap |
      | ldap_port | text | 389 |
      | ldap_base_dn | text |  dc=openldap,dc=com |
      | ldap_login_filter | text | (objectClass=person1) |
      | ldap_bind_attr    | text | dn |
      | ldap_login_attr    | text | uid |
      | ldap_group_checkbox    | checkbox | |
      | ldap_authentication_checkbox    | checkbox | checked |
      | ldap_admin_user    | text | cn=admin,ou=admins,dc=openldap,dc=com |
      | ldap_admin_password    | text | admin&password |
      | ldap_auto_create_users | checkbox | checked |
    And I wait for element "input[name=username]"
    Then I login as "user1" with password "user1"
    And I wait until the loading is completed
    And I wait for the page to be loaded
    And I should see "You must specify a valid username and password"

  Scenario: LDAP user tries to login if provision is enabled and username contains comma
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
      | ldap_auto_create_users | checkbox | checked |
    And I wait for element "input[name=username]"
    Then I login as "user, comma" with password "usercomma"
    And I wait for element "input[name=first_name]"
    And I should see "Setup your user information"
    Then I skip login wizard
    And I wait for the page to be loaded
    And I wait until the loading is completed
    And I should see "Home Dashboard"
    Then I logout

  Scenario Outline: LDAP Injections in Username Field: User should see correct error message
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
      | ldap_auto_create_users | checkbox | checked |
    And I wait for element "input[name=username]"
    Then I login as <username> with password <password>
    And I wait until the loading is completed
    And I wait for the page to be loaded
    And I should see "You must specify a valid username and password"
    And I should not see "Bad search filter"
    Then I close alerts
    And I should not see "User Profile"
    And I should not see "Accounts"
    Examples:
      |     username      | password |
      |"user1)(uid=admin" | "user1"  |
      |"user1)(password=*"| "user1"  |
      |"name *"           | "user1"  |
