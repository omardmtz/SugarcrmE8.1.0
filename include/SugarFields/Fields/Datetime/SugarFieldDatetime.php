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


class SugarFieldDatetime extends SugarFieldBase {

    function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {

        // Create Smarty variables for the Calendar picker widget
        if(!isset($displayParams['showMinutesDropdown'])) {
           $displayParams['showMinutesDropdown'] = false;
        }

        if(!isset($displayParams['showHoursDropdown'])) {
           $displayParams['showHoursDropdown'] = false;
        }

        if(!isset($displayParams['showNoneCheckbox'])) {
           $displayParams['showNoneCheckbox'] = false;
        }

        if(!isset($displayParams['showFormats'])) {
           $displayParams['showFormats'] = false;
        }

        if(!isset($displayParams['hiddeCalendar'])) {
           $displayParams['hiddeCalendar'] = false;
        }

        // jpereira@dri - #Bug49552 - Datetime field unable to follow parent class methods
        //jchi , bug #24557 , 10/31/2008
        if(isset($vardef['name']) && ($vardef['name'] == 'date_entered' || $vardef['name'] == 'date_modified')){
            return $this->getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
        }
        //end
        return parent::getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex);
        // ~ jpereira@dri - #Bug49552 - Datetime field unable to follow parent class methods
    }

    function getImportViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex)
    {
        $displayParams['showMinutesDropdown'] = false;
        $displayParams['showHoursDropdown'] = false;
        $displayParams['showNoneCheckbox'] = false;
        $displayParams['showFormats'] = true;
        $displayParams['hiddeCalendar'] = false;

        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch($this->findTemplate('EditView'));
    }

    function getWirelessEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        global $timedate;
        $datetime_prefs = $GLOBALS['current_user']->getUserDateTimePreferences();
        $datetime = explode(' ', $vardef['value']);

        // format date and time to db format
        $date_start = $timedate->swap_formats($datetime[0], $datetime_prefs['date'], $timedate->dbDayFormat);

        // pass date parameters to smarty
        if ($datetime_prefs['date'] == 'Y-m-d' || $datetime_prefs['date'] == 'Y/m/d' || $datetime_prefs['date'] == 'Y.m.d'){
            $this->ss->assign('field_order', 'YMD');
        }
        else if ($datetime_prefs['date'] == 'd-m-Y' || $datetime_prefs['date'] == 'd/m/Y' || $datetime_prefs['date'] == 'd.m.Y'){
            $this->ss->assign('field_order', 'DMY');
        }
        else{
            $this->ss->assign('field_order', 'MDY');
        }
        $this->ss->assign('date_start', $date_start);

        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex, false);
        return $this->fetch($this->findTemplate('WirelessEditView'));
    }

    function getSearchViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        if($this->isRangeSearchView($vardef)) {
           $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
           $id = isset($displayParams['idName']) ? $displayParams['idName'] : $vardef['name'];
           $this->ss->assign('original_id', "{$id}");
           $this->ss->assign('id_range', "range_{$id}");
           $this->ss->assign('id_range_start', "start_range_{$id}");
           $this->ss->assign('id_range_end', "end_range_{$id}");
           $this->ss->assign('id_range_choice', "{$id}_range_choice");
           return $this->fetch('include/SugarFields/Fields/Datetimecombo/RangeSearchForm.tpl');
        }
        return $this->getSmartyView($parentFieldArray, $vardef, $displayParams, $tabindex, 'EditView');
    }

    public function getEmailTemplateValue($inputField, $vardef, $context = null){
        global $timedate;
        // This does not return a smarty section, instead it returns a direct value
        if(isset($context['notify_user'])) {
            $user = $context['notify_user'];
        } else {
            $user = $GLOBALS['current_user'];
        }
        if($vardef['type'] == 'date') {
            if(!$timedate->check_matching_format($inputField, TimeDate::DB_DATE_FORMAT)) {
                return $inputField;
            }
            // convert without TZ
            return $timedate->to_display($inputField, $timedate->get_db_date_format(),  $timedate->get_date_format($user));
        } else {
            if(!$timedate->check_matching_format($inputField, TimeDate::DB_DATETIME_FORMAT)) {
                return $inputField;
            }
            return $timedate->to_display_date_time($inputField, true, true, $user);
        }
    }

    public function save($bean, $inputData, $field, $def, $prefix = '') {
        global $timedate;
        if ( !isset($inputData[$prefix.$field]) ) {
            return;
        }

        $bean->$field = $this->convertFieldForDB($inputData[$prefix.$field]);

    }

    /**
     * Convert field for DB
     * @param array|string $value Can be an array of datetime strings or a single datetime string
     * @return array|string The converted $value
     */
    public function convertFieldForDB($value)
    {
        $timedate = TimeDate::getInstance();
        $values = array();
        if (is_array($value)) {
            $values = $value;
        } else {
            $values[] = &$value;
        }
        foreach ($values as &$curr) {
            $offset = strlen(trim($curr)) < 11 ? false : true;
            if (!$timedate->check_matching_format($curr, TimeDate::DB_DATE_FORMAT)) {
                $curr = $timedate->to_db_date($curr, $offset);
            }
        }
        return $value;
    }



    /**
     * Unformat a value from an API Format
     * @param $value - the value that needs unformatted
     * @return string - the unformatted value
     */
    public function apiUnformatField($value)
    {
        global $current_user;
        if (strlen(trim($value)) < 11) {
            $newValue = TimeDate::getInstance()->fromIsoDate($value, $current_user);
        } else {
            $newValue = TimeDate::getInstance()->fromIso($value, $current_user);
        }

        if (is_object($newValue)) {
            $value = $newValue->asDb();
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function fixForFilter(&$value, $columnName, SugarBean $bean, SugarQuery $q, SugarQuery_Builder_Where $where, $op)
    {
        if($op === '$daterange') {
            return true;
        }
        $dateLengthCheck = is_array($value) ? reset($value) : $value;
        if(strlen(trim($dateLengthCheck)) < 11) {
            if(!is_array($value)) {
                $dateParsed = date_parse($value);
            } else {
                $dateParsed[0] = date_parse($value[0]);
                $dateParsed[1] = date_parse($value[1]);
            }
            switch($op)
            {
                case '$gt':
                    $value = date("Y-m-d", strtotime($value . "+1 day"));
                    $dateParsed = date_parse($value);
                    $value = gmdate(
                        'Y-m-d\TH:i:s',
                        gmmktime(0, 0, 0, $dateParsed['month'], $dateParsed['day'], $dateParsed['year'])
                    );
                    break;
                case '$gte':
                    $value = gmdate(
                        'Y-m-d\TH:i:s',
                        gmmktime(0, 0, 0, $dateParsed['month'], $dateParsed['day'], $dateParsed['year'])
                    );
                    break;
                case '$lt':
                    $value = date("Y-m-d", strtotime($value . "-1 day"));
                    $dateParsed = date_parse($value);
                    $value = gmdate(
                        'Y-m-d\TH:i:s',
                        gmmktime(23, 59, 59, $dateParsed['month'], $dateParsed['day'], $dateParsed['year'])
                    );
                    break;
                case '$lte':
                    $value = gmdate(
                        'Y-m-d\TH:i:s',
                        gmmktime(23, 59, 59, $dateParsed['month'], $dateParsed['day'], $dateParsed['year'])
                    );
                    break;
                case '$between':
                case '$dateBetween':
                    $value[0] = gmdate(
                        'Y-m-d\TH:i:s',
                        gmmktime(0, 0, 0, $dateParsed[0]['month'], $dateParsed[0]['day'], $dateParsed[0]['year'])
                    );

                    $value[1] = gmdate(
                        'Y-m-d\TH:i:s',
                        gmmktime(23, 59, 59, $dateParsed[1]['month'], $dateParsed[1]['day'], $dateParsed[1]['year'])
                    );
                    break;
                case '$starts':
                case '$equals':
                    $value = array();
                    $value[0] = gmdate(
                        'Y-m-d\TH:i:s',
                        gmmktime(0, 0, 0, $dateParsed['month'], $dateParsed['day'], $dateParsed['year'])
                    );

                    $value[1] = gmdate(
                        'Y-m-d\TH:i:s',
                        gmmktime(23, 59, 59, $dateParsed['month'], $dateParsed['day'], $dateParsed['year'])
                    );
                    $where->between($columnName, $this->apiUnformatField($value[0]), $this->apiUnformatField($value[1]), $bean);
                    return false;
            }
        }

        return true;
    }

    /**
     * @see SugarFieldBase::importSanitize()
     */
    public function importSanitize(
        $value,
        $vardef,
        $focus,
        ImportFieldSanitize $settings
        )
    {
        global $timedate;

        $format = $timedate->merge_date_time($settings->dateformat, $settings->timeformat);

        if ( !$timedate->check_matching_format($value, $format) ) {
            $parts = $timedate->split_date_time($value);
            if(empty($parts[0])) {
               $datepart = $timedate->getNow()->format($settings->dateformat);
            }
            else {
               $datepart = $parts[0];
            }
            if(empty($parts[1])) {
                $timepart = $timedate->fromTimestamp(0)->format($settings->timeformat);
            } else {
                $timepart = $parts[1];
                // see if we can get by stripping the seconds
                if(strpos($settings->timeformat, 's') === false) {
                    $sep = $timedate->timeSeparatorFormat($settings->timeformat);
                    // We are assuming here seconds are the last component, which
                    // is kind of reasonable - no sane time format puts seconds first
                    $timeparts = explode($sep, $timepart);
                    if(!empty($timeparts[2])) {
                        $timepart = join($sep, array($timeparts[0], $timeparts[1]));
                    }
                }
            }

            $value = $timedate->merge_date_time($datepart, $timepart);
            if ( !$timedate->check_matching_format($value, $format) ) {
                return false;
            }
        }

        try {
            $date = SugarDateTime::createFromFormat($format, $value, new DateTimeZone($settings->timezone));
            if ((int) $date->year < 100) {
                return false;
            }
        } catch(Exception $e) {
            return false;
        }
        return $date->asDb();
    }

    /**
     * Handles export field sanitizing for field type
     *
     * @param $value string value to be sanitized
     * @param $vardef array representing the vardef definition
     * @param $focus SugarBean object
     * @param $row Array of a row of data to be exported
     *
     * @return string sanitized value
     */
    public function exportSanitize($value, $vardef, $focus, $row=array())
    {
        $timedate =  TimeDate::getInstance();
        $db = DBManagerFactory::getInstance();

        //If it's in ISO format, convert it to db format
        if(preg_match('/(\d{4})\-?(\d{2})\-?(\d{2})T(\d{2}):?(\d{2}):?(\d{2})\.?\d*([Z+-]?)(\d{0,2}):?(\d{0,2})/i', $value)) {
           $value = $timedate->fromIso($value)->asDb();
        } else if(preg_match("/" . TimeDate::DB_DATE_FORMAT . "/", $value)) {
           $value = $timedate->fromDbDate($value)->asDb();
        }

        $value = $timedate->to_display_date_time($db->fromConvert($value, 'datetime'));
        return preg_replace('/([pm|PM|am|AM]+)/', ' \1', $value);
    }

    /**
     * {@inheritDoc}
     */
    public function apiFormatField(
        array &$data,
        SugarBean $bean,
        array $args,
        $fieldName,
        $properties,
        array $fieldList = null,
        ServiceBase $service = null
    ) {
        global $timedate;
        $this->ensureApiFormatFieldArguments($fieldList, $service);

        if(empty($bean->$fieldName)) {
            $data[$fieldName] = '';
            return;
        }

        $theDate = (!empty($bean->fetched_row[$fieldName])) ? $bean->fetched_row[$fieldName] : $bean->$fieldName;

        $dbType = DBManagerFactory::getInstance()->getFieldType($properties);
        $date = $timedate->fromDbType($theDate, $dbType);

        if ( $date == null ) {
            // Could not parse date... try User format
            $date = $timedate->fromUserType($bean->$fieldName,$properties['type']);
            if ( $date == null ) {
                return;
            }
        }

        if ( $properties['type'] == 'date' ) {
            // It's just a date, not a datetime
            $data[$fieldName] = $timedate->asIsoDate($date);
        } else if ( $properties['type'] == 'time' ) {
            $data[$fieldName] = $timedate->asIsoTime($date);
        } else {
            $data[$fieldName] = $timedate->asIso($date);
        }
    }

    /**
     * @see SugarFieldBase::apiSave
     */
    public function apiSave(SugarBean $bean, array $params, $field, $properties) {
        global $timedate;

        $inputDate = $params[$field];

        if ( empty($inputDate) ) {
            $bean->$field = '';
            return;
        }

        if ( $properties['type'] == 'date' ) {
            // It's just a date, not a datetime
            $date = $timedate->fromIsoDate($inputDate);
        } else if ( $properties['type'] == 'time' ) {
            $date = $timedate->fromIsoTime($inputDate);
        }

        // if both of those fail above, lets check to make sure it's not the full ISO String
        if (empty($date)) {
            $date = $timedate->fromIso($inputDate);
        }

        if (empty($date)) {
            throw new SugarApiExceptionInvalidParameter("Did not recognize $field as a date/time, it looked like {$params[$field]}");
        }


        $bean->$field = $timedate->asDbType($date,$properties['type']);
    }

}
