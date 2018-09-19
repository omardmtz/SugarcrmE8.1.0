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

namespace Sugarcrm\Sugarcrm\Audit\Formatter;

use Sugarcrm\Sugarcrm\Audit\Formatter;

class Email implements Formatter
{

    /**
     * @inheritdoc
     */
    public function formatRows(array &$rows)
    {
        $ids = array_unique(
            array_map(
                function ($row) {
                    return !empty($row['before']) ? $row['before'] : $row['after'];
                },
                array_filter(
                    $rows,
                    function ($row) {
                        return $row['data_type'] == 'email';
                    }
                )
            )
        );

        $addresses = $this->getEmailAddressesForIds($ids);

        array_walk(
            $rows,
            function (&$row) use ($addresses) {
                if ($row['data_type'] == 'email') {
                    foreach (['before', 'after'] as $key) {
                        if (!empty($row[$key]) && !empty($addresses[$row[$key]])) {
                            $row[$key] = [
                                'id' => $row[$key],
                                'email_address' => $addresses[$row[$key]],
                            ];
                        }
                    }
                }
            }
        );
    }

    protected function getEmailAddressesForIds($ids)
    {
        $addresses = [];

        $q = new \SugarQuery();
        $q->select(["id", "email_address"]);
        $q->from(\BeanFactory::newBean('EmailAddresses'))->where()->in('id', $ids);

        $rows = $q->execute();
        foreach ($rows as $row) {
            $addresses[$row['id']] = $row['email_address'];
        }

        return $addresses;
    }
}
