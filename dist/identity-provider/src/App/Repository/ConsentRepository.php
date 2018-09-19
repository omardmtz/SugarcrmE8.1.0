<?php

namespace Sugarcrm\IdentityProvider\App\Repository;

use Doctrine\DBAL\Connection;
use Sugarcrm\IdentityProvider\Authentication\Consent;

class ConsentRepository
{
    const TABLE = 'consents';

    /**
     * @var Connection
     */
    private $db;

    /**
     * Consent repository constructor.
     *
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param $clientId
     * @param $tenantId
     * @return Consent
     */
    public function findConsentByClientIdAndTenantId($clientId, $tenantId)
    {
        $data = $this->db->fetchAssoc(
            sprintf('SELECT * FROM %s WHERE client_id = ? AND tenant_id = ?', self::TABLE),
            [$clientId, $tenantId]
        );

        if (empty($data)) {
            throw new \RuntimeException('Consent not found.');
        }
        
        return (new Consent())
            ->setClientId($data['client_id'])
            ->setTenantId($data['tenant_id'])
            ->setScopes($data['scopes']);
    }
}
