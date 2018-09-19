<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

namespace Sugarcrm\IdentityProvider\App\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Current migrations creates user's table on 'up' and drop it on 'down'
 */
class Version20160826150001 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('
            CREATE TABLE tenants
            (
              id CHAR(10) NOT NULL,
              create_time DATETIME,
              modify_time DATETIME,
              display_name VARCHAR(64) NOT NULL,
              region VARCHAR(64) NOT NULL,
              status TINYINT(1) DEFAULT 0,
              providers LONGTEXT,
              PRIMARY KEY (id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');
        $this->addSql('
            CREATE TABLE tenant_providers
            (
              tenant_id CHAR(10) NOT NULL,
              provider_code VARCHAR(50) NOT NULL,
              config LONGTEXT,
              attribute_map LONGTEXT,
              PRIMARY KEY (tenant_id, provider_code),
              FOREIGN KEY fk_tenant_providers_tenants (tenant_id) REFERENCES tenants(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');
        $this->addSql('
            CREATE TABLE users
            (
              tenant_id CHAR(10) NOT NULL,
              id CHAR(36) NOT NULL,
              create_time DATETIME,
              modify_time DATETIME,
              password_hash VARCHAR(255),
              status TINYINT(1) DEFAULT 0,
              attributes LONGTEXT,
              custom_attributes LONGTEXT,
              PRIMARY KEY (tenant_id, id),
              FOREIGN KEY fk_users_tenants (tenant_id) REFERENCES tenants(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');
        $this->addSql('
            CREATE TABLE user_providers
            (
              tenant_id CHAR(10) NOT NULL,
              user_id CHAR(36) NOT NULL,
              provider_code VARCHAR(50) NOT NULL,
              identity_value VARCHAR(255),
              PRIMARY KEY (tenant_id, user_id, provider_code),
              FOREIGN KEY fk_user_providers_users (tenant_id, user_id) REFERENCES users(tenant_id, id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');
        $this->addSql('
            CREATE TABLE consents
            (
              tenant_id CHAR(10) NOT NULL,
              client_id VARCHAR(255) NOT NULL,
              scopes LONGTEXT,
              PRIMARY KEY (tenant_id, client_id),
              FOREIGN KEY fk_consents_tenants (tenant_id) REFERENCES tenants(id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');
        $this->addSql('
        CREATE TABLE sessions
        (
          session_id VARCHAR(128) NOT NULL,
          session_value BLOB NULL,
          session_lifetime MEDIUMINT NULL,
          session_time int UNSIGNED NULL,
          PRIMARY KEY (session_id)
        ) ENGINE=InnoDb DEFAULT CHARSET=utf8;
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE `consents`;');
        $this->addSql('DROP TABLE `user_providers`;');
        $this->addSql('DROP TABLE `users`;');
        $this->addSql('DROP TABLE `tenant_providers`;');
        $this->addSql('DROP TABLE `tenants`;');
        $this->addSql('DROP TABLE `sessions`;');
    }
}
