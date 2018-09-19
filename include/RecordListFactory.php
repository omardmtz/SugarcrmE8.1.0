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

/**
 * Factory to create Record Lists.
 */
class RecordListFactory
{
    /**
     * Retrieves the data for a record list
     * @param string $id
     * @param $user
     *
     * @return array id, module, records
     */
    public static function getRecordList($id, $user = null)
    {
        $data = null;
        $conn = DBManagerFactory::getConnection();

        if ($user == null) {
            $user = $GLOBALS['current_user'];
        }
        $row = $conn->executeQuery(
            'SELECT * FROM record_list WHERE id = ? AND assigned_user_id = ?',
            [$id, $user->id]
        )->fetch();

        if (!empty($row['records'])) {
            $data = $row;
            $data['records'] = json_decode($data['records']);
        }

        return $data;
    }

    /**
     * Saves a record list object and returns the id
     * @param      $recordList
     * @param      $module
     * @param      $id
     * @param      $user
     *
     * @return string
     */
    public static function saveRecordList($recordList, $module, $id = null, $user = null)
    {
        global $dictionary;
        
        $db = DBManagerFactory::getInstance();

        if ($user == null) {
            $user = $GLOBALS['current_user'];
        }

        $currentTime = $GLOBALS['timedate']->nowDb();

        if (empty($id)) {
            $id = create_guid();
            $db->insertParams(
                'record_list',
                $dictionary['RecordList']['fields'],
                array(
                    'id' => $id,
                    'assigned_user_id' => $user->id,
                    'module_name' => $module,
                    'records' => json_encode($recordList),
                    'date_modified' => $currentTime,
                )
            );
        } else {
            $db->updateParams(
                'record_list',
                $dictionary['RecordList']['fields'],
                array(
                    'records' => json_encode($recordList),
                    'date_modified' => $currentTime,
                ),
                array('id' => $id)
            );
        }

        return $id;
    }

    /**
     * Deletes a record list based on record list id
     * @param $recordListId
     *
     * @return mixed
     */
    public static function deleteRecordList($recordListId)
    {
        return DBManagerFactory::getConnection()
            ->executeUpdate('DELETE FROM record_list WHERE id = ?', [$recordListId]);
    }
}
