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


class ActivitiesApiHelper extends SugarBeanApiHelper
{
    /**
     * Formats the bean so it is ready to be handed back to the API's client. Certain fields will get extra processing
     * to make them easier to work with from the client end.
     *
     * @param $bean SugarBean The bean you want formatted
     * @param $fieldList array Which fields do you want formatted and returned (leave blank for all fields)
     * @param $options array Currently no options are supported
     * @return array The bean in array format, ready for passing out the API to clients.
     */
    public function formatForApi(SugarBean $bean, array $fieldList = array(), array $options = array())
    {
        $record = parent::formatForApi($bean, $fieldList, $options);

        $requestBean = isset($options['requestBean']) ? $options['requestBean'] : null;
        $displayFields = $this->getDisplayModule($record, $requestBean);
        $record['display_parent_type'] = $displayFields['module'];
        $record['display_parent_id'] = $displayFields['id'];

        return $record;
    }

    /**
     * For non-homepage requests and link/unlink activities, flip the parent
     * record that's displayed so that the event is noticeable.
     * @param  array     $record The individual activity, as an array.
     * @param  SugarBean $requestBean   The request's context's bean.
     * @return array     Associative array with two keys, 'module' and 'id'.
     */
    protected function getDisplayModule(array $record, SugarBean $requestBean = null)
    {
        $array = array(
            'module' => isset($record['parent_type']) ? $record['parent_type'] : '',
            'id' => isset($record['parent_id']) ? $record['parent_id'] : '',
        );

        if (!is_null($requestBean) && $this->isRecordLinkAction($record)) {
            // Verify that the context matches record's parent module.
            if ($requestBean->module_name === $record['parent_type']) {
                $array['module'] = $record['data']['subject']['module'];
                $array['id'] = $record['data']['subject']['id'];
            }
        }

        return $array;
    }

    /**
     * Checks to see if the activity type on a record is link or unlink
     * @param array $record The API formatted record array
     * @return boolean
     */
    protected function isRecordLinkAction($record)
    {
        if (isset($record['activity_type'])) {
            return $record['activity_type'] === 'link' || $record['activity_type'] === 'unlink';
        }
        return false;
    }
}
