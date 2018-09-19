# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@soap @userManagement @regression
Feature: SOAP user management
  As a SOAP developer
  I want to be able to create users on the instance in non-IDM mode
  I want to be able to update users on the instance in non-IDM mode

  Scenario Outline: User creation on the instance in non-IDM mode
    Given I use SOAP service WSDL of <version> version at behat-tests-mango Mango instance
    And I login with <plain?> password
    When I call authenticated SOAP function set_entry with arguments:
      """
module_name: Users
name_value_list: [{name: id, value: <username>},{name: new_with_id, value: 1},{name: user_name, value: <username>}]
      """
    Then I expect no SOAP exception
  Examples:
    | version | plain? | username         |
    | 4_1     | false  | new_soap_user_41 |
    | 3       | false  | new_soap_user_30 |

  Scenario Outline: User creation on the instance in non-IDM mode
    Given I use SOAP service WSDL of <version> version at behat-tests-mango Mango instance
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
    And I should see SOAP response property "entry_list][0][name_value_list][0][value" is not "Access to this object is denied since it has been deleted or does not exist"
  Examples:
    | version | plain? | username                     |
    | 4_1     | false  | new_soap_user_set_entries_41 |
    | 3_1     | false  | new_soap_user_set_entries_31 |

  Scenario Outline: User update on the instance in non-IDM mode
    Given I use SOAP service WSDL of <version> version at behat-tests-mango Mango instance
    And I login with <plain?> password
    When I call authenticated SOAP function set_entry with arguments:
      """
module_name: Users
name_value_list: [{name: id, value: <username>},{name: last_name, value: Updated}]
      """
    And I call authenticated SOAP function get_entry with arguments:
      """
module_name: Users
id: <username>
select_fields: [id,last_name]
      """
    Then I expect no SOAP exception
    And I should see SOAP response property "entry_list][0][name_value_list][1][value" equals to "Updated"
  Examples:
    | version | plain? | username         |
    | 4_1     | false  | new_soap_user_41 |
    | 3       | false  | new_soap_user_30 |

  Scenario Outline: User update on the instance in non-IDM mode
    Given I use SOAP service WSDL of <version> version at behat-tests-mango Mango instance
    And I login with <plain?> password
    When I call authenticated SOAP function set_entries with arguments:
      """
module_name: Users
name_value_lists: [[{name: id, value: <username>},{name: last_name, value: Updated}]]
      """
    And I call authenticated SOAP function get_entry with arguments:
      """
module_name: Users
id: <username>
select_fields: [id,last_name]
      """
    Then I expect no SOAP exception
    And I should see SOAP response property "entry_list][0][name_value_list][1][value" equals to "Updated"
  Examples:
    | version | plain? | username                     |
    | 4_1     | false  | new_soap_user_set_entries_41 |
    | 3_1     | false  | new_soap_user_set_entries_31 |
