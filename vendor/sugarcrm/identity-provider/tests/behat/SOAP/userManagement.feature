# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@soap @userManagement @oidc
Feature: SOAP user management
  As a SOAP developer
  I want to make sure I can't create users on the instance in IDM mode
  I want to make sure I can't update OIDC users on the instance in IDM mode

  Scenario Outline: User creation on the instance in IDM mode
    Given I use SOAP service WSDL of <version> version at behat-tests-mango-oidc Mango instance
    And I login with <plain?> password
    When I call authenticated SOAP function set_entry with arguments:
      """
module_name: Users
name_value_list:
  - {name: user_name, value: <username>}
      """
    Then I expect SOAP exception
  Examples:
    | version | plain? | username             |
    | 4_1     | true   | new_soap_user_idm_41 |
    | 3       | true   | new_soap_user_idm_30 |

  Scenario Outline: User creation on the instance in IDM mode
    Given I use SOAP service WSDL of <version> version at behat-tests-mango-oidc Mango instance
    And I login with <plain?> password
    When I call authenticated SOAP function set_entries with arguments:
      """
module_name: Users
name_value_lists: [[{name: id, value: <username>},{name: new_with_id, value: 1},{name: user_name, value: <username>}]]
      """
    And I call authenticated SOAP function get_entry with arguments:
      """
module_name: Users
id: <username>
      """
    Then I expect no SOAP exception
    And I should see SOAP response property "entry_list][0][name_value_list][0][value" equals to "Access to this object is denied since it has been deleted or does not exist"
  Examples:
    | version | plain? | username                         |
    | 4_1     | true   | new_soap_user_idm_set_entries_41 |
    | 3_1     | true   | new_soap_user_idm_set_entries_31 |

  Scenario Outline: User update on the instance in IDM mode
    Given I use SOAP service WSDL of <version> version at behat-tests-mango-oidc Mango instance
    And I login with <plain?> password
    When I call authenticated SOAP function set_entry with arguments:
      """
module_name: Users
name_value_list: [{name: id, value: 1},{name: last_name, value: Updated}]
      """
    And I call authenticated SOAP function get_entry with arguments:
      """
module_name: Users
id: 1
select_fields: [id,last_name]
      """
    Then I expect no SOAP exception
    And I should see SOAP response property "entry_list][0][name_value_list][1][value" is not "Updated"
  Examples:
    | version | plain? |
    | 4_1     | true   |
    | 3       | true   |

  Scenario Outline: User update on the instance in IDM mode
    Given I use SOAP service WSDL of <version> version at behat-tests-mango-oidc Mango instance
    And I login with <plain?> password
    When I call authenticated SOAP function set_entries with arguments:
      """
module_name: Users
name_value_lists: [[{name: id, value: 1},{name: last_name, value: Updated}]]
      """
    And I call authenticated SOAP function get_entry with arguments:
      """
module_name: Users
id: 1
select_fields: [id,last_name]
      """
    Then I expect no SOAP exception
    And I should see SOAP response property "entry_list][0][name_value_list][1][value" is not "Updated"
  Examples:
    | version | plain? |
    | 4_1     | true   |
    | 3_1     | true   |
