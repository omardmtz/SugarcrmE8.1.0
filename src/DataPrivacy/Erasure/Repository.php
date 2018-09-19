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

namespace Sugarcrm\Sugarcrm\DataPrivacy\Erasure;

use Doctrine\DBAL\Connection;

/**
 * This class interacts the database for erasure.
 */
class Repository
{
    /**
     * DB table name
     */
    const DB_TABLE = 'erased_fields';

    /**
     * @var Connection
     */
    private $conn;

    /**
     * Constructor
     *
     * @param Connection $conn
     */
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Add the erased fields.
     *
     * @param string $table the table of the bean
     * @param string $id the id of the bean
     * @param FieldList $fields the list of fields to be erased
     */
    public function addBeanFields($table, $id, FieldList $fields)
    {
        if (count($fields) === 0) {
            return;
        }

        $old = $this->getBeanFields($table, $id);

        //Case I: add new fields that do not exist before
        if ($old === null) {
            $this->insertBeanFields($table, $id, $fields);
            return;
        }

        //Case II: update with the additional fields (i.e., $fields contains any field that are not in $old)
        $old = FieldList::fromArray($old);
        $diff = $fields->without($old);
        if (count($diff) === 0) {
            return;
        }

        $new = $old->with($fields);
        $this->updateBeanFields($table, $id, $new);
    }

    /**
     * Remove the erased fields.
     *
     * @param string $table the table of the bean
     * @param string $id the id of the bean
     * @param FieldList $fields the list of fields to be erased
     */
    public function removeBeanFields($table, $id, FieldList $fields)
    {
        if (count($fields) === 0) {
            return;
        }
        
        $old = $this->getBeanFields($table, $id);
        if ($old === null) {
            return;
        }

        $old = FieldList::fromArray($old);
        $new = $old->without($fields);
        //Case I:  delete the record if all the fields are to be removed
        if ($new->count() === 0) {
            $this->deleteBeanFields($table, $id);
        } else {
            //Case II: update the record with the fields in $old but not in $fields
            $this->updateBeanFields($table, $id, $new);
        }
    }

    /**
     * Get the erased fields.
     *
     * @param string $table the table of the bean
     * @param string $id the id of the bean
     * @return array|null a list of erased fields, or null if no row is found.
     */
    public function getBeanFields($table, $id) : ?array
    {
        $query = sprintf(
            'SELECT data FROM %s WHERE bean_id = ? AND table_name = ?',
            self::DB_TABLE
        );

        $stmt = $this->conn->executeQuery($query, array($id, $table));
        $data = $stmt->fetchColumn();
        if ($data === false) {
            return null;
        }
        return json_decode($data, true);
    }

    /**
     * Insert a new record of the erased fields.
     *
     * @param string $table the table of the bean
     * @param string $id the id of the bean
     * @param FieldList $fields the list of fields to be erased
     */
    private function insertBeanFields($table, $id, FieldList $fields)
    {
        $data = json_encode($fields);

        $this->conn->insert(
            self::DB_TABLE,
            array('bean_id' => $id, 'table_name' => $table, 'data' => $data)
        );
    }

    /**
     * Update an existing record of the erased fields.
     *
     * @param string $table the table of the bean
     * @param string $id the id of the bean
     * @param FieldList $fields the list of fields to be erased
     */
    private function updateBeanFields($table, $id, FieldList $fields)
    {
        $data = json_encode($fields);

        $this->conn->update(
            self::DB_TABLE,
            array('data' => $data),
            array('bean_id' => $id, 'table_name' => $table)
        );
    }


    /**
     * Delete an existing record if no erased fields exists.
     *
     * @param string $table the table of the bean
     * @param string $id the id of the bean
     */
    private function deleteBeanFields($table, $id)
    {
        $this->conn->delete(
            self::DB_TABLE,
            array('bean_id' => $id, 'table_name' => $table)
        );
    }
}
