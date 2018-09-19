-- Your installation or use of this SugarCRM file is subject to the applicable
-- terms available at
-- http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
-- If you do not agree to all of the applicable terms or do not have the
-- authority to bind the entity as an authorized representative, then do not
-- install or use this SugarCRM file.
--
-- Copyright (C) SugarCRM Inc. All rights reserved.

-- a password of user1 is user1pass, user2's password is user2pass, etc.
DELETE FROM `tenant_providers`;
DELETE FROM `user_providers`;
DELETE FROM `consents`;
DELETE FROM `users`;
DELETE FROM `tenants`;

INSERT INTO `tenants` (`id`, `create_time`, `modify_time`, `display_name`, `region`, `status`, `providers`)
VALUES
  ('0000000001','2018-02-07 14:33:42','2018-02-07 14:33:42','Local Test Tenant','eu',0,'[\"local\"]');

INSERT INTO `tenant_providers` (`tenant_id`, `provider_code`, `config`, `attribute_map`)
VALUES
  ('0000000001','local', '[]',NULL),
  ('0000000001','ldap','{\"baseDn\": \"dc=openldap,dc=com\", \"filter\": \"({uid_key}={username})\", \"uidKey\": \"uid\", \"groupDn\": \"\", \"dnString\": null, \"searchDn\": \"cn=admin,ou=admins,dc=openldap,dc=com\", \"includeUserDN\": false, \"adapter_config\": {\"host\": \"ldap.%%IamNS%%\", \"port\": 389}, \"entryAttribute\": null, \"groupAttribute\": \"\", \"searchPassword\": \"admin\&password\", \"groupMembership\": false, \"userUniqueAttribute\": null, \"adapter_connection_protocol_version\": 3, \"auto_create_users\": true}',NULL),
  ('0000000001','saml','{"idp_sso_url":"https:\/\/sugarcrm-idmeloper-dev.onelogin.com\/trust\/saml2\/http-post\/sso\/735046","idp_slo_url":"https:\/\/sugarcrm-idmeloper-dev.onelogin.com\/trust\/saml2\/http-redirect\/slo\/735046","idp_entity_id":"https:\/\/app.onelogin.com\/saml\/metadata\/735046","sp_entity_id":"php-saml","x509_cert":"-----BEGIN CERTIFICATE-----\nMIIEFTCCAv2gAwIBAgITVt8w4PTGB4LRNuL1ipqpftGjwjANBgkqhkiG9w0BAQUF\nADBYMQswCQYDVQQGEwJVUzERMA8GA1UECgwIU3VnYXJDUk0xFTATBgNVBAsMDE9u\nZUxvZ2luIElkUDEfMB0GA1UEAwwWT25lTG9naW4gQWNjb3VudCA5OTkzODAeFw0x\nNzAxMjkxMTI5MzlaFw0yMjAxMzAxMTI5MzlaMFgxCzAJBgNVBAYTAlVTMREwDwYD\nVQQKDAhTdWdhckNSTTEVMBMGA1UECwwMT25lTG9naW4gSWRQMR8wHQYDVQQDDBZP\nbmVMb2dpbiBBY2NvdW50IDk5OTM4MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIB\nCgKCAQEA1MYPNsLtHGkxsVBK9\/6VXnN2rlcGKI9nhkOvI6Ac\/rFJoj9BqJJ8TF41\nsx1b2QTBGV9Ekxf8H2b\/sIM5dY1aGYZd9HziMT4UzbgKWzdxW2Q\/7ggWZUf2hTkm\noCTm3ULed6vn73jSkrAlP8TDAuJWY42GtA5YQfjvXedEBL57YnI5azKIjLeAvxva\nxJGj6WsxI6jZ8HCON0X5fSvPUlfV0Th9hw3nh7EH3nbw75H\/+O0zP1TE06MIw1sR\nTNxqJKk5WzvnS08YXBbqj3Hbw9zKE9R+vsN9Oj9hHbE+x7T7oZjxLqSzhGwKw5bq\nv6cI2x64rOCRm43p+\/233gv4GLHVBQIDAQABo4HXMIHUMAwGA1UdEwEB\/wQCMAAw\nHQYDVR0OBBYEFKOKXBQVnyGFhgfyl6CvypWqnKJhMIGUBgNVHSMEgYwwgYmAFKOK\nXBQVnyGFhgfyl6CvypWqnKJhoVykWjBYMQswCQYDVQQGEwJVUzERMA8GA1UECgwI\nU3VnYXJDUk0xFTATBgNVBAsMDE9uZUxvZ2luIElkUDEfMB0GA1UEAwwWT25lTG9n\naW4gQWNjb3VudCA5OTkzOIITVt8w4PTGB4LRNuL1ipqpftGjwjAOBgNVHQ8BAf8E\nBAMCB4AwDQYJKoZIhvcNAQEFBQADggEBALb1bXW\/39gAsdhz6oxCiWtbYXyS1rDP\ndnHECepY\/1+zRFIoQPgjcy++3f1nbqHvVCJhh2qULkncBSdvchRkfLAppIf2svpl\nWXQyYzQnTfBJWJzDdDpqJuImYsVL5y8jhIE2mJDsaYR1vmmNHHrZ7GO7o1Z86tSw\n\/2NCl9UGV8hJYsTN800nOwo5SB9TAFjcvoVb2G6GaJdpqI5PPtbc0eBDUEOCyWcr\n6s6PmwR+qdbrG7z5jZpKBNjLhGdYrG\/c8yXuZsMWsg6OTgs9zIPIL99BxOq5NGgM\nYAcpEEeMZZps8zfYfwhodIsttwQO2agEBtEtob5ft9LMMK5kRdIXxzg=\n-----END CERTIFICATE-----\n","provision_user":true,"same_window":true,"request_signing_pkey":"-----BEGIN PRIVATE KEY-----\nMIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQC+ECfVOVPJSyfo\nlS9KcBhxP\/nyEtErPQnVJtTTFn+9JBmtVguUBvMXr0CVL79t9rkNSchYLta174ld\nJvNDY\/1PH+ZudJVS0mJFR2w+iPdtQVBuEZpP4ts9\/xNwQwamxzwmwB+1KSwLjBLU\ny4GqWkPh6JuNQXySNsHqLZzfjOupZfWYIkIVJp71gShwf1hbv119iNyMrVPY3m1X\nQtVaCJT9XWC0XH3y\/+JsUE87vkw4feBGYUTVr19fS4P\/DRsOVXUcTQXT7WEI2tsJ\nrD\/i57HlJaE6ho1NLSS54fcBxe1jWO7hYGWaWgh6dElVCH9aApK14pO\/MvCtGS7G\n3TrXAfNXAgMBAAECggEBAKrvIrPsnAM0eY7+5QpAaGsqC6P\/8mi9u6MdCllSKc40\nsncnJMCbw3NwpVfHGpZOR73AttNARNBZvyOtDSl1uvK3kOmUJlvXZJREGQDg9A4p\nqKllYXApad6HErdrQIcsNlfvgFTQ05ELCECjSlmoVtbM+WEAHYXug1YWcbjIJ4Yv\n4jcQwy4hesCW1QfhrvNjNCgURcPjZza8QhrtmCOiuQdJNUq9qWTeZnHnTqsF2IU4\nK6XNNdLxf2xZ7F+u4hVxcemjti7Nqd5sFkS6jHoKA6KFkdUfepAR5NwE9bZuLHz8\nls3g0rVrzMLdhXPI+8zerWzXJbwI6v\/xaWxA+XCXwFECgYEA+EOYdd00iZ+owkU\/\n8ST5l104qW272igQl6ATsnRNU0s0N7Opoxdi2WWlc1rDI82HTuO4rlQIf6cfLLpP\nssjC1FAihhVgMy3NWxnFByUgZDMVuMsRaIvZ2336Bg4spgjdf9pl5SIhmZS5CuZd\nU9ntt43PuW3H\/zYtg49w9nrQe3kCgYEAw\/xKaQADhbW9M+00WaLrs6uSTSvj1jzV\nzja9gGdjmMMhDq6xSDQBXnvobE5YBA4idBXegNUkaFA38W\/EoZtKVPeOtp2d4FTO\nF8a5lMGoyehz5gJAghdDxr9xxyDC2nhLXxFej5Mblp92v4yj2U\/rq99yRwxO6Uco\n2gamZSU7YU8CgYBY6lzAWelnIPegHI06ILQDsi+I\/vQ4vgCzTXHAiEbpfhXFnWM0\nNjwBAJaxKeCaAhJj\/ss2JIKmtYRE0LWaoqykvc6flyhNLCpQZnpahMGFIYa2GISz\nnOL56bSSVqFHFgW+tMmptv+xscJUVQ036uVoyDGNh\/QJQ64pYEZlALeKgQKBgQDB\n8vJQZssVj3zl3mBoNGq9K5Vk+YJHiXyszk9KuwY9Lx2PwiF\/KrgQIN8qD33axYIj\nD2FabZPSB1DVhZ45r8wnubVp0yFh14r8zJTrOZsn9Pp9LM1Z8FwKW3rlbO5n9ZPh\nSPcjbplmvfhuJ2gerpCzTjVxSiTthpZO7TXN8sKI0QKBgQCJV1u1K1t+SbosI0L9\nAb7UTSjGBfyN5MJk8\/S+XFBfR8BEKNF7jLoJRlIGd4gBrCpCRmOfl\/0y\/LnJkcPn\n5mpN0HdfQ0UG48FvxovA+mlu88mXn9BGcGkl0wCLOqweLoty8x11bj0F\/HzwML+I\nOc2LpfBzb+d2XXEU54BlXH5J5A==\n-----END PRIVATE KEY-----\n","request_signing_cert":"-----BEGIN CERTIFICATE-----\nMIIDdTCCAl2gAwIBAgIJAMoIJg5+hQELMA0GCSqGSIb3DQEBCwUAMFExCzAJBgNV\nBAYTAkJZMQ4wDAYDVQQIDAVNaW5zazEOMAwGA1UEBwwFTWluc2sxETAPBgNVBAoM\nCFN1Z2FyQ1JNMQ8wDQYDVQQDDAZBbmRyZXcwHhcNMTYwODIzMTEyMDU0WhcNMTcw\nODIzMTEyMDU0WjBRMQswCQYDVQQGEwJCWTEOMAwGA1UECAwFTWluc2sxDjAMBgNV\nBAcMBU1pbnNrMREwDwYDVQQKDAhTdWdhckNSTTEPMA0GA1UEAwwGQW5kcmV3MIIB\nIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvhAn1TlTyUsn6JUvSnAYcT\/5\n8hLRKz0J1SbU0xZ\/vSQZrVYLlAbzF69AlS+\/bfa5DUnIWC7Wte+JXSbzQ2P9Tx\/m\nbnSVUtJiRUdsPoj3bUFQbhGaT+LbPf8TcEMGpsc8JsAftSksC4wS1MuBqlpD4eib\njUF8kjbB6i2c34zrqWX1mCJCFSae9YEocH9YW79dfYjcjK1T2N5tV0LVWgiU\/V1g\ntFx98v\/ibFBPO75MOH3gRmFE1a9fX0uD\/w0bDlV1HE0F0+1hCNrbCaw\/4uex5SWh\nOoaNTS0kueH3AcXtY1ju4WBlmloIenRJVQh\/WgKSteKTvzLwrRkuxt061wHzVwID\nAQABo1AwTjAdBgNVHQ4EFgQUM+DmVDOGTb\/l6F8EWgc6gdJYjYgwHwYDVR0jBBgw\nFoAUM+DmVDOGTb\/l6F8EWgc6gdJYjYgwDAYDVR0TBAUwAwEB\/zANBgkqhkiG9w0B\nAQsFAAOCAQEAeVdxprEsLUT3ZHYwox1XzKwDk3UPu67jVtjrjYXLr\/qBqWVbDrAd\nVRimX9fI6pr9MFBzgVbMfZ6VkEwgfNgJnrKja4db\/4fk6qapPLf0FmOhDRjCweU6\nTz4pQ\/QMeNBWbeK6Ekjqyz5mCxrbDmwNb\/tuD6MzEJMtpwNXNcU1X68rI6wY9Cdk\n8BWYNT3MQvf\/NjoR6Feqk\/qdgmEESZM\/QLhj6vb2LxdfHniBGBMFpuwnnzScNHc5\nNzYr9XzUJXcfVRsrKaKa1nLl21zeVKqNqf8SwFbJV9a6\/UYRnnS84hkawIP6WOLH\nDHdqN4yQ\/I7etFrMgWCeypFLCSN+46GHZw==\n-----END CERTIFICATE-----\n","request_signing_method":"RSA_SHA256","sign_authn_request":false,"sign_logout_request":false,"sign_logout_response":false,"attribute_mapping":"{\"name_id\":\"email\"}"}','{\"name_id\": \"email\"}');

INSERT INTO `users` (`id`, `tenant_id`, `password_hash`, `create_time`, `modify_time`, `status`, `attributes`) VALUES
  ('6f1f6421-6a77-409d-8a59-76308ee399df', '0000000001', '$2y$10$XZqRv/2rA56qz/Y/Sw1D9.021038DkyqXQkkrIeukWvePD4YKcV8y', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 0, NULL),
  ('6b0d13f2-aeb4-4a7e-ba38-a0cf44640499', '0000000001', '$2y$10$MdbT1ouox0b01cUspLHGseO6s60CN6sYvHGQl2j.hoyDoDawHKegq', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 0, NULL),
  ('bbea1845-d6a1-477b-bffe-370174f01710', '0000000001', '$2y$10$KOw.QgPGGcmDZ3vBBRbNTe3/F5xHPAadfhvg4hsrG/tN8CrJTeWES', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 1, NULL),
  ('e842d2e5-cc5f-4876-93fb-a478ba451afe', '0000000001', '$2y$10$bgsdeIMU6zvefbo9PAdhzOxu.EZW0X9i7TGidJf8P2JJQ3YKkpDHK', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 0, NULL),
  ('3b678759-d906-4bab-9d2e-78d4a4fdc025', '0000000001', '$2y$10$MpWZ02kHzd93vblFBFT.Tu74d1tAj3MSpZIrkfZAu5/MWxDimnT/y', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 0, NULL),
  ('1aa16947-9d38-4b4e-8204-331e421fb432', '0000000001', '$2y$10$nCF6AsdPzvyocP2ynoCgUeXlmlpx2ldc1VfNiFSFy1/UGR8btnYCi', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 1, NULL),
  ('14acb450-5292-42e4-9c41-dd864cce01ac', '0000000001', '$2y$10$QE7XRFHgfb.OItUMz15yg./OqC1mHxh4NAz9nsy9nvX97e.0eM19O', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 0, NULL),
  ('5f4e765c-60cc-4a28-b8bb-7f620258c875', '0000000001', '$2y$10$LYVZ69PzYq4PzyRu4HDa2O5QFFhaUXYnGfF494d3mZ1m5DKtxT7qy', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 0, NULL),
  ('aff20d60-c4b7-45d3-abae-008f5e46cb0a', '0000000001', '$2y$10$LQXh7zSYaoPmTjboRYSHEOykYtluL1u4iuwjOqFf.AArvS4XAtBFG', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 1, NULL),
  ('6efcf62d-35af-419a-8c2f-bb2b494de003', '0000000001', '$2y$10$J9J81Pxu2lmrRawGVKj73OOd.QREqBVb2w6sPPQrS1tx88.YDV3P6', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 0, NULL),

  -- Sugar Demo Users
  ('1', '0000000001', '$6$rounds=5000$1234567890$7rCEL47980TXCPg6YiSXtE8K11X/FVuCsty9T7CCPC8J4uCdbnuoq7OWX2adzI/vKZPIJMXYzTTaTEi9pQL.v.', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 0, NULL),
  ('seed_chris_id', '0000000001', '$6$rounds=5000$1234567890$FBL4daItx2vVgID5TcRXjGcXcIpSBkYDEJs93zGZk3i6UyFuYWDV.g1e1cTjbuhGzEzdLJl864m5jwKRtBtSB0', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 0, '{"given_name":"Chris","family_name":"Chris_family","nickname":"max","email":"chris@example.lh","phone_number":"+1234567890","address":{"street_address":"teststreet","locality":"testlocality","region":"testregion","postal_code":"123456","country":"testcountry"}}'),
  ('seed_jim_id', '0000000001', '$6$rounds=5000$1234567890$mS//JgJJjSriHTU/eme/AnhGPO/j4h80i2xQPZbsARF48zQHnM8fx0Tb53hQUGPzKCkG0GKvY2rfdYASwehdh.', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 0, '{"given_name":"jim","family_name":"jim_family","nickname":"max","email":"jim@example.lh","phone_number":"+1234567890","address":{"street_address":"teststreet","locality":"testlocality","region":"testregion","postal_code":"123456","country":"testcountry"}}'),
  ('seed_sarah_id', '0000000001', '$6$rounds=5000$1234567890$g8h7ZGbSDEGc2F2nZj9h0rHd1Q3HuRAc12T3/gKzJazezCn2GHzn8FrrdrNnI6xUvXchpgisSahLmF8m/PikE1', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 0, '{"given_name":"sarah","family_name":"sarah_family","nickname":"max","email":"sarah@example.lh","phone_number":"+1234567890","address":{"street_address":"teststreet","locality":"testlocality","region":"testregion","postal_code":"123456","country":"testcountry"}}'),
  ('seed_will_id', '0000000001', '$6$rounds=5000$1234567890$4Bnt.qfqdxqNdAQuTOQQiopOhzMim6HEKYFAFFRnDLsFilZAkaYjUut3BbmEG1bb8IUdPvHTvwuIpIviWcC8q1', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 0, '{"given_name":"will","family_name":"will_family","nickname":"max","email":"will@example.lh","phone_number":"+1234567890","address":{"street_address":"teststreet","locality":"testlocality","region":"testregion","postal_code":"123456","country":"testcountry"}}'),
  ('seed_max_id', '0000000001', '$6$rounds=5000$1234567890$5ynkieyakDq291MCW0MSxMFIBa7t7p.hiz/x/S/pohettbVNUEzNarzKYxIOxkaa6TU./qb3aZuBKjj0QDuZY.', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 0, '{"given_name":"Max","family_name":"Jensen","nickname":"max","email":"max.jensen@example.lh","phone_number":"+1234567890","address":{"street_address":"teststreet","locality":"testlocality","region":"testregion","postal_code":"123456","country":"testcountry"}}'),
  ('seed_sally_id', '0000000001', '$6$rounds=5000$1234567890$3clR9VInMWxsNX9yKqjUPyUMEDecOLrg5ZPbE6W3D/baIuuVteQb7gEdCzv5YUk5flTS2i0Y4bHsIc7e19AAf0', '2018-02-21 15:39:49', '2018-02-21 15:39:49', 0, '{"given_name":"sally","family_name":"sally_family","nickname":"max","email":"sally@example.lh","phone_number":"+1234567890","address":{"street_address":"teststreet","locality":"testlocality","region":"testregion","postal_code":"123456","country":"testcountry"}}'),

  -- LDAP Users already provisioned
  ('8cefa54e-4567-4073-bc1e-c97e4d6eba9e', '0000000001', NULL, '2018-02-21 15:39:49', '2018-02-21 15:39:49', 0, NULL)
;

INSERT INTO `user_providers` (`tenant_id`, `user_id`, `provider_code`, `identity_value`) VALUES
  ('0000000001', '6f1f6421-6a77-409d-8a59-76308ee399df', 'local', 'user1'),
  ('0000000001', '6b0d13f2-aeb4-4a7e-ba38-a0cf44640499', 'local', 'user2'),
  ('0000000001', 'bbea1845-d6a1-477b-bffe-370174f01710', 'local', 'user3'),
  ('0000000001', 'e842d2e5-cc5f-4876-93fb-a478ba451afe', 'local', 'user4'),
  ('0000000001', '3b678759-d906-4bab-9d2e-78d4a4fdc025', 'local', 'user5'),
  ('0000000001', '1aa16947-9d38-4b4e-8204-331e421fb432', 'local', 'user6'),
  ('0000000001', '14acb450-5292-42e4-9c41-dd864cce01ac', 'local', 'user7'),
  ('0000000001', '5f4e765c-60cc-4a28-b8bb-7f620258c875', 'local', 'user8'),
  ('0000000001', 'aff20d60-c4b7-45d3-abae-008f5e46cb0a', 'local', 'user9'),
  ('0000000001', '6efcf62d-35af-419a-8c2f-bb2b494de003', 'local', 'user10'),

  -- Sugar Demo Users
  ('0000000001', '1', 'local', 'admin'),
  ('0000000001', 'seed_chris_id', 'local', 'chris'),
  ('0000000001', 'seed_jim_id', 'local', 'jim'),
  ('0000000001', 'seed_sarah_id', 'local', 'sarah'),
  ('0000000001', 'seed_will_id', 'local', 'will'),
  ('0000000001', 'seed_max_id', 'local', 'max'),
  ('0000000001', 'seed_sally_id', 'local', 'sally'),

  -- LDAP Users already provisioned
  ('0000000001', '8cefa54e-4567-4073-bc1e-c97e4d6eba9e', 'ldap', 'abey')
;

INSERT INTO `consents` (`client_id`, `tenant_id`, `scopes`) VALUES
  ('testLocal1', '0000000001', '["hydra", "openid", "offline"]'),
  ('mangoOIDCClientId', '0000000001', '["hydra", "openid", "offline"]');
