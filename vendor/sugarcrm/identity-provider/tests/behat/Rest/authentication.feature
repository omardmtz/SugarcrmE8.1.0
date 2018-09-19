# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.
@login @rest
Feature: Checks credentials for the user and replies.

  Scenario: Check unauthenticated request
    Given I send a POST request to "authenticate" with body:
     """
        tid=srn:cluster:iam:eu:0000000001:tenant&user_name=user2&password=user2pass
     """
    Then the JSON should be equal to:
        """
        {"status":"error", "error":"The request could not be authorized"}
        """
    And the response status code should be 401

  Scenario: Check request with invalid token scope
    Given I get access_token for "offline" scope
    Then I add access_token to header
    Then I send a POST request to "authenticate" with body:
     """
        tid=srn:cluster:iam:eu:0000000001:tenant&user_name=user2&password=user2pass
     """
    Then the JSON should be equal to:
        """
        {"status":"error", "error":"The request could not be authorized"}
        """
    And the response status code should be 401

  Scenario Outline: Send authenticated requests and check responses
    Given I get access_token for "idp.auth.password" scope
    Then I add access_token to header
    Then I send a POST request to "authenticate" with body:
        """
        <request>
        """
    Then the JSON should be equal to:
        """
        <result>
        """
    And the response status code should be <statusCode>
    Examples:
      | request                                                                                 | statusCode | result                                                                                                                                                                                                                    |
      |                                                                                         | 401        | {"status":"error","error":"Field tid is required. Field user_name is required. Field password is required."}                                                                                                              |
      | tid=                                                                                    | 401        | {"status":"error","error":"Field tid is required. Field user_name is required. Field password is required."}                                                                                                              |
      | tid=&user_name=                                                                         | 401        | {"status":"error","error":"Field tid is required. Field user_name is required. Field password is required."}                                                                                                              |
      | tid=&user_name=&password=                                                               | 401        | {"status":"error","error":"Field tid is required. Field user_name is required. Field password is required."}                                                                                                              |
      | tid=tid&user_name=user_name                                                             | 401        | {"status":"error","error":"Field password is required."}                                                                                                                                                                  |
      | tid=invalid-tid&user_name=invalidUser&password=invalidPass                              | 401        | {"status":"error","error":"APP ERROR: Invalid tenant id"}                                                                                                                                                                 |
      | tid=srn:cluster:iam:eu:0000000000:tenant&user_name=invalidUser&password=invalidPass     | 401        | {"status":"error","error":"APP ERROR: Tenant not exists or deleted"}                                                                                                                                                      |
      | tid=srn:cluster:iam:eu:0000000001:tenant&user_name=invalidUser&password=invalidPass     | 401        | {"status":"error","error":"Invalid credentials"}                                                                                                                                                                          |
      | tid=srn:cluster:iam:eu:0000000001:tenant&user_name=user1&password=user1pass             | 200        | {"status":"success","user":{"sub":"srn:cluster:iam:eu:0000000001:user:6f1f6421-6a77-409d-8a59-76308ee399df","id_ext":{"preferred_username":"user1","status":"0","created_at":"2018-02-21 15:39:49","updated_at":"2018-02-21 15:39:49","tid":"srn:cluster:iam:eu:0000000001:tenant"}}} |
      | tid=srn:cluster:iam:eu:0000000001:tenant&user_name=user3&password=user3pass             | 401        | {"status":"error","error":"Invalid credentials"}                                                                                                                                                                          |
      | tid=srn:cluster:iam:eu:0000000001:tenant&user_name=sally&password=sally                 | 200        | {"status":"success","user":{"sub":"srn:cluster:iam:eu:0000000001:user:seed_sally_id","id_ext":{"preferred_username":"sally","status":"0","created_at":"2018-02-21 15:39:49","updated_at":"2018-02-21 15:39:49","given_name":"sally","family_name":"sally_family","nickname":"max","email":"sally@example.lh","phone_number":"+1234567890","address":{"street_address":"teststreet","locality":"testlocality","region":"testregion","postal_code":"123456","country":"testcountry"},"tid":"srn:cluster:iam:eu:0000000001:tenant"}}} |
      | tid=0000000001&user_name=sally&password=sally                                           | 200        | {"status":"success","user":{"sub":"srn:cluster:iam:eu:0000000001:user:seed_sally_id","id_ext":{"preferred_username":"sally","status":"0","created_at":"2018-02-21 15:39:49","updated_at":"2018-02-21 15:39:49","given_name":"sally","family_name":"sally_family","nickname":"max","email":"sally@example.lh","phone_number":"+1234567890","address":{"street_address":"teststreet","locality":"testlocality","region":"testregion","postal_code":"123456","country":"testcountry"},"tid":"srn:cluster:iam:eu:0000000001:tenant"}}} |
      | tid=1&user_name=sally&password=sally                                                    | 200        | {"status":"success","user":{"sub":"srn:cluster:iam:eu:0000000001:user:seed_sally_id","id_ext":{"preferred_username":"sally","status":"0","created_at":"2018-02-21 15:39:49","updated_at":"2018-02-21 15:39:49","given_name":"sally","family_name":"sally_family","nickname":"max","email":"sally@example.lh","phone_number":"+1234567890","address":{"street_address":"teststreet","locality":"testlocality","region":"testregion","postal_code":"123456","country":"testcountry"},"tid":"srn:cluster:iam:eu:0000000001:tenant"}}} |
      | tid=srn:cluster:iam:eu:0000000001:tenant&user_name=admin&password=admin                 | 200        | {"status":"success","user":{"sub":"srn:cluster:iam:eu:0000000001:user:1","id_ext":{"preferred_username":"admin","status":"0","created_at":"2018-02-21 15:39:49","updated_at":"2018-02-21 15:39:49","tid":"srn:cluster:iam:eu:0000000001:tenant"}}} |
      | tid=srn:cluster:iam:eu:0000000001:tenant&user_name=abey&password=abey                   | 200        | {"status":"success","user":{"sub":"srn:cluster:iam:eu:0000000001:user:8cefa54e-4567-4073-bc1e-c97e4d6eba9e","id_ext":{"preferred_username":"abey","status":"0","created_at":"2018-02-21 15:39:49","updated_at":"2018-02-21 15:39:49","tid":"srn:cluster:iam:eu:0000000001:tenant"}}} |
