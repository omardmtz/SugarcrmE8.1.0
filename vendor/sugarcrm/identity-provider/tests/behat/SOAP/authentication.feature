# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

@login @soap @oidc @local
Feature: SOAP login check
  As a SOAP developer
  I want to be able to login to SugarCRM instance that uses Local authentication
  I want to be able to login to SugarCRM instance that uses OIDC authentication
  So that I can obtain SOAP session for further invocation of methods


  Scenario Outline: SOAP login to SugarCRM instance with correct credentials results in success
    Given I use SOAP service WSDL of <version> version at <instance> Mango instance
    And I call SOAP function "login" with user <user> and <plain?> password <password>
    Then I should see SOAP response property "name_value_list][1][value" equals to <user>
  Examples:
    | instance                      | version |   user      |  password  | plain? |
    | behat-tests-mango             | 4_1     |   admin     |  admin     | false  |
    | behat-tests-mango-oidc        | 4_1     |   admin     |  admin     | true   |
    | behat-tests-mango             | 4       |   admin     |  admin     | false  |
    | behat-tests-mango-oidc        | 4       |   admin     |  admin     | true   |
    | behat-tests-mango             | 3_1     |   admin     |  admin     | false  |
    | behat-tests-mango-oidc        | 3_1     |   admin     |  admin     | true   |
    | behat-tests-mango             | 3       |   admin     |  admin     | false  |
    | behat-tests-mango-oidc        | 3       |   admin     |  admin     | true   |
    | behat-tests-mango             | 2_1     |   admin     |  admin     | false  |
    | behat-tests-mango-oidc        | 2_1     |   admin     |  admin     | true   |
    | behat-tests-mango             | 2       |   admin     |  admin     | false  |
    | behat-tests-mango-oidc        | 2       |   admin     |  admin     | true   |


  Scenario Outline: SOAP login to SugarCRM instance with incorrect credentials results in failure with correct message
    Given I use SOAP service WSDL of <version> version at <instance> Mango instance
    And I call SOAP function "login" with user <user> and <plain?> password <password>
    And expect SOAP exception with message "Invalid Login"
  Examples:
    | instance                      | version |   user      |  password               | plain?  |
    | behat-tests-mango             | 4_1     |   admin     |  wrong-admin-password   | false   |
    | behat-tests-mango-oidc        | 4_1     |   admin     |  wrong-admin-password   | true    |
    | behat-tests-mango             | 4       |   admin     |  wrong-admin-password   | false   |
    | behat-tests-mango-oidc        | 4       |   admin     |  wrong-admin-password   | true    |
    | behat-tests-mango             | 3_1     |   admin     |  wrong-admin-password   | false   |
    | behat-tests-mango-oidc        | 3_1     |   admin     |  wrong-admin-password   | true    |
    | behat-tests-mango             | 3       |   admin     |  wrong-admin-password   | false   |
    | behat-tests-mango-oidc        | 3       |   admin     |  wrong-admin-password   | true    |
    | behat-tests-mango             | 2_1     |   admin     |  wrong-admin-password   | false   |
    | behat-tests-mango-oidc        | 2_1     |   admin     |  wrong-admin-password   | true    |
    | behat-tests-mango             | 2       |   admin     |  wrong-admin-password   | false   |
    | behat-tests-mango-oidc        | 2       |   admin     |  wrong-admin-password   | true    |
