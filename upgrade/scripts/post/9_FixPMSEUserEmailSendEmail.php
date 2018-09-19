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
 * Add `id` in the configuration for user emails for AWF Send Email Event.
 */
class SugarUpgradeFixPMSEUserEmailSendEmail extends UpgradeScript
{
    public $order = 9012;
    public $type = self::UPGRADE_CUSTOM;

    /**
     * Returns an updated JSON array
     * @param array $oldJSON
     * @return array $newJSON
     */
    protected function parseToNewFormat($objJSON)
    {
        if ($objJSON['type'] === 'email') {
            $userId = $this->getUserID($objJSON["value"]);
            if (!empty($userId)) {
                $objJSON['id'] = $userId;
            }
        }
        return $objJSON;
    }

    /**
     * Returns the userID for the email provided else null
     * @param $emailAddress
     * @return $userId
     */
    protected function getUserID($emailAddress)
    {
        $userId = null;

        // create the query
        $query  = " SELECT eabr.bean_id";
        $query .= " FROM email_addr_bean_rel eabr ";
        $query .= " JOIN email_addresses ea on ea.id = eabr.email_address_id";
        $query .= " where ea.email_address = ?";
        $query .= " and ea.deleted = 0 ";
        $query .= " and eabr.deleted = 0 " ;

        $con = $this->db->getConnection();
        $result = $con->executeQuery($query, array($emailAddress));
        $row = $result->fetch();

        if (!empty($row['bean_id'])) {
            $userId = $row['bean_id'];
        }
        return $userId;
    }

    public function run()
    {
        // this will happen when upgraded to versions 7.9 or above
        if (version_compare($this->from_version, '7.7.0.0', '>=') && version_compare($this->to_version, '7.9.0.0', '==')) {
            $query = " SELECT e1.id as event_id, e2.evn_params as params ";
            $query .= " FROM pmse_bpmn_event e1 ";
            $query .= " INNER JOIN pmse_bpm_event_definition e2 ON e1.id = e2.id ";
            $query .= " WHERE ( e2.evn_type = ? OR e2.evn_type = ? )";
            $query .= "AND e1.evn_marker = ? AND e1.evn_behavior = ?";

            $con = $this->db->getConnection();
            $result = $con->executeQuery($query, array('INTERMEDIATE', 'END', 'MESSAGE', 'THROW'));

            while ($row = $result->fetch()) {
                $oldJSON = json_decode(html_entity_decode($row['params']), true);
                if ($oldJSON !== null) {
                    $newJSON = array(
                        'to' => array(),
                        'cc' => array(),
                        'bcc' => array(),
                    );

                    foreach (array_keys($newJSON) as $index) {
                        foreach ($oldJSON[$index] as $value) {
                            $parsed = $this->parseToNewFormat($value);
                            if (empty($parsed)) {
                                continue;
                            }
                            array_push($newJSON[$index], $parsed);
                        }
                    }

                    $newJSON = json_encode(array(
                        'to' => $newJSON['to'],
                        'cc' => $newJSON['cc'],
                        'bcc' => $newJSON['bcc'],
                    ));

                    $event_id = $row['event_id'];
                    $builder = $this->db->getConnection()->createQueryBuilder();
                    $query = $builder->update('pmse_bpm_event_definition')
                                    ->set('evn_params', $this->db->quoted($newJSON))
                                    ->where($builder->expr()->eq('id', $this->db->quoted($event_id)));
                    $query->execute();
                }
            }
        }
    }
}
