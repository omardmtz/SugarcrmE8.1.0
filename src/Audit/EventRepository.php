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

namespace Sugarcrm\Sugarcrm\Audit;

use DBManagerFactory;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use InvalidArgumentException;
use JsonSerializable;
use SugarBean;
use Sugarcrm\Sugarcrm\DataPrivacy\Erasure\FieldList as ErasureFieldList;
use Sugarcrm\Sugarcrm\Security\Context;
use Sugarcrm\Sugarcrm\Security\Subject;
use Sugarcrm\Sugarcrm\Util\Uuid;
use TimeDate;

class EventRepository
{
    /**
     * @var Connection
     */
    private $conn;

    /**
     * @var Context
     */
    private $context;

    /**
     * Constructor
     *
     * @param Connection $conn
     * @param Context $context
     */
    public function __construct(Connection $conn, Context $context)
    {
        $this->conn = $conn;
        $this->context = $context;
    }

    /**
     * Registers update in EventRepository. Then saves audited fields.
     * @param SugarBean $bean
     * @param FieldChangeList $changedFields
     * @return string id of audit event created
     * @throws DBALException
     */
    public function registerUpdate(SugarBean $bean, FieldChangeList $changedFields)
    {
        return $this->save($bean, 'update', $this->context, $changedFields);
    }

    /**
     * Registers the update and attributes it to the provided subject
     *
     * @param SugarBean $bean The updated bean
     * @param Subject $subject The subject to attribute the update to
     * @param FieldChangeList $changedFields
     *
     * @return string id of audit event created
     * @throws DBALException
     */
    public function registerUpdateAttributedToSubject(
        SugarBean $bean,
        Subject $subject,
        FieldChangeList $changedFields
    ) {
        return $this->save($bean, 'update', [
            'subject' => $subject,
            'attributes' => [],
        ], $changedFields);
    }

    /**
     * Registers erasure EventRepository. Then saves audited fields.
     * @param SugarBean $bean
     * @param ErasureFieldList $fields list of fields to be erased
     * @return string id of audit event created
     * @throws DBALException
     * @throws InvalidArgumentException
     */
    public function registerErasure(SugarBean $bean, ErasureFieldList $fields)
    {
        if (count($fields) === 0) {
            throw new InvalidArgumentException("Fields to be erased can not be empty.");
        }

        return $this->save($bean, 'erasure', $this->context, $fields);
    }

    /**
     * Saves EventRepository
     * @param SugarBean $bean SugarBean that was changed
     * @param string $eventType Audit event type
     * @param array|JsonSerializable $source The source of the event
     * @param array|jsonSerializable $data Event data
     * @return string id of record saved
     * @throws DBALException
     */
    private function save(SugarBean $bean, string $eventType, $source, $data)
    {
        $id =  Uuid::uuid1();

        $this->conn->insert(
            'audit_events',
            ['id' => $id,
            'type' => $eventType,
            'parent_id' => $bean->id,
            'module_name' => $bean->module_name,
            'source' => json_encode($source),
            'data' => json_encode($data),
            'date_created' => TimeDate::getInstance()->nowDb(),]
        );

        return $id;
    }

    /**
     * Retrieves latest audit events for given instance of bean and fields
     *
     * @param SugarBean $bean
     * @param array $fields
     * @return array[]
     */
    public function getLatestBeanEvents(SugarBean $bean, array $fields)
    {
        if (empty($fields)) {
            return [];
        }

        if (in_array('email', $fields)) {
            if (empty($bean->emailAddress->hasFetched)) {
                $emailsRaw = $bean->emailAddress->getAddressesByGUID($bean->id, $bean->module_name);
            } else {
                $emailsRaw = $bean->emailAddress->addresses;
            }

            if (count($fields) == 1 && $fields[0] === 'email' && empty($emailsRaw)) {
                return [];
            }
        }

        $auditTable = $bean->get_audit_table_name();

        $selectWithLJoin = "SELECT  atab.field_name, atab.date_created, atab.after_value_string, ae.source, ae.type
                            FROM {$auditTable} atab
                            LEFT JOIN audit_events ae ON (ae.id = atab.event_id) 
                            LEFT JOIN {$auditTable} atab2 ON";

        $leftJoinCond = [];
        $leftJoinCond[] = 'atab2.parent_id = atab.parent_id AND atab2.field_name = atab.field_name
                           AND (atab2.date_created > atab.date_created
                                    OR (atab2.date_created = atab.date_created AND atab2.id > atab.id))';

        $where = [];
        $where[] = 'atab2.id is NULL AND atab.parent_id = ?';

        $addLJoinCond = [];
        $addWhere = [];
        $params = [$bean->id];
        $paramTypes = [null];
        $nonEmailFields = array_diff($fields, ['email']);
        if ($nonEmailFields) {
            $addLJoinCond[] = "(atab.field_name != 'email')";
            $addWhere[] = 'atab.field_name IN (?)';
            $params[] = $nonEmailFields;
            $paramTypes[] = Connection::PARAM_STR_ARRAY;
        }

        if (in_array('email', $fields) && !empty($emailsRaw)) {
            $addLJoinCond[] = "(atab.field_name = 'email' AND atab2.after_value_string = atab.after_value_string)";
            $addWhere[] = "(atab.field_name = 'email' AND atab.after_value_string IN (?))";
            $emailIds = array_column($emailsRaw, 'email_address_id');
            $params[] = $emailIds;
            $paramTypes[] = Connection::PARAM_STR_ARRAY;
        }

        $leftJoinCond[] = sprintf('(%s)', implode(' OR ', $addLJoinCond));
        $where[] = sprintf('(%s)', implode(' OR ', $addWhere));

        $sql = sprintf(
            '%s %s WHERE %s',
            $selectWithLJoin,
            implode(' AND ', $leftJoinCond),
            implode(' AND ', $where)
        );

        $stmt = $this->conn->executeQuery($sql, $params, $paramTypes);

        $db = DBManagerFactory::getInstance();

        $return = [];
        while ($row = $stmt->fetch()) {
            $row['source'] = json_decode($row['source'], true);
            //convert date
            $row['date_created'] = $db->fromConvert($row['date_created'], 'datetime');
            $return[] = $row;
        }

        return $return;
    }
}
