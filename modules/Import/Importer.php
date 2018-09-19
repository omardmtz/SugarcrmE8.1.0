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


use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Security\InputValidation\Request;
use Sugarcrm\Sugarcrm\ProcessManager\Registry;
class Importer
{
    /**
     * @var ImportFieldSanitize
     */
    protected $ifs;

    /**
     * @var Currency
     */
    protected $defaultUserCurrency;

    /**
     * @var importColumns
     */
    protected $importColumns;

    /**
     * @var importSource
     */
    protected $importSource;

    /**
     * @var $isUpdateOnly
     */
    protected $isUpdateOnly;

    /**
     * @var  $bean
     */
    protected $bean;

    /**
     * @var sugarToExternalSourceFieldMap
     */
    protected $sugarToExternalSourceFieldMap = array();

    /**
     * Cache the currency symbol so we don't have to create the beans
     *
     * @var array
     */
    protected $cachedCurrencySymbols = array();

    /**
     * Where is the currency_id in the import fields
     * @var bool
     */
    protected $currencyFieldPosition = false;

    /**
     * Flag that tells the importer whether to look for tags
     *
     * @var boolean
     */
    protected $hasTags = false;

    /*
     * @var Request
     */
    protected $request;

    public function __construct($importSource, $bean)
    {
        global $mod_strings, $sugar_config;

        $this->request = InputValidation::getService();

        $this->importSource = $importSource;

        //Vanilla copy of the bean object.
        $this->bean = $bean;

        // use our own error handler
        set_error_handler(array('Importer','handleImportErrors'), E_ALL & ~E_STRICT & ~E_DEPRECATED);

         // Increase the max_execution_time since this step can take awhile
        ini_set("max_execution_time", max($sugar_config['import_max_execution_time'],3600));

        // stop the tracker
        TrackerManager::getInstance()->pause();

        // set the default locale settings
        $this->ifs = $this->getFieldSanitizer();

        //Get the default user currency
        $this->defaultUserCurrency = BeanFactory::getBean('Currencies', '-99');

        //Get our import column definitions
        $this->importColumns = $this->getImportColumns();
        $this->isUpdateOnly = ( isset($_REQUEST['import_type']) && $_REQUEST['import_type'] == 'update' );
    }

    public function import()
    {
        Activity::disable();

        // do we have a currency_id field
        $this->currencyFieldPosition = array_search('currency_id', $this->importColumns);

        // Are we importing tags?
        $this->hasTags = array_search('tag', $this->importColumns);

        //catch output including notices and warnings so import process can run to completion
        $output = '';
        ob_start();
        foreach($this->importSource as $row)
        {
            $this->importRow($row);
        }

        //if any output was produced, then display it as an error.
        //first, replace more than one consecutive spaces with a single space.  This is to condense
        //multiple line/row errors and prevent miscount of rows in list navigation UI
        $output = ob_get_clean();
        if(!empty($output)) {
            $output = preg_replace('/\s+/', ' ', trim($output));
            $this->importSource->writeError( 'Execution', 'Execution Error', $output);
        }


        // save mapping if requested
        if ( isset($_REQUEST['save_map_as']) && $_REQUEST['save_map_as'] != '' )
        {
            $this->saveMappingFile();
        }

        $this->importSource->writeStatus();
        Activity::restoreToPreviousState();
        //All done, remove file.
    }

    /**
     * create array with indexes in correct order
     *
     * Get correct order for imported columns e.g. if there are account_name and account_id in imported columns
     * the field account_id should be processed first to prevent invalid retrieving related account by its name
     *
     * @param array $field_defs
     * @return array of int
     */
    protected function getImportColumnsOrder($field_defs)
    {
        $processed_fields = array();
        $fields_order = array();
        foreach ($this->importColumns as $field_index => $field_name) {
            if (!empty($processed_fields[$field_name])) {
                continue;
            }
            $field_def = $field_defs[$field_name];
            // if field is relate and has id_name
            if (!empty($field_def['type']) && $field_def['type'] == 'relate' && !empty($field_def['id_name'])) {
                // if id_name is in imported columns & has not been processed
                $id_name_key = array_search($field_def['id_name'], $this->importColumns);
                if ($id_name_key !== false) {
                    $key = $field_def['id_name'];
                    if (empty($processed_fields[$key]) || !$processed_fields[$key]) {
                        $fields_order[] = $id_name_key;
                        $processed_fields[$key] = true;
                    }
                }
            }
            if (empty($processed_fields[$field_name]) || !$processed_fields[$field_name]) {
                $fields_order[] = $field_index;
                $processed_fields[$field_name] = true;
            }
        }

        return $fields_order;
    }

    protected function importRow($row)
    {
        global $sugar_config, $mod_strings, $current_user, $current_language;

        $focus = BeanFactory::newBean($this->bean->module_dir);
        $focus->unPopulateDefaultValues();
        $focus->save_from_post = false;
        $focus->team_id = null;
        $this->ifs->createdBeans = array();
        $this->importSource->resetRowErrorCounter();
        $do_save = true;

        // set the currency for the row, if it has a currency_id in the row
        if ($this->currencyFieldPosition !== false && !empty($row[$this->currencyFieldPosition])) {
            $currency_id = $row[$this->currencyFieldPosition];
            if (!isset($this->cachedCurrencySymbols[$currency_id])) {
                /** @var Currency $currency */
                $currency = BeanFactory::getBean('Currencies', $currency_id);
                $this->cachedCurrencySymbols[$currency_id] = $currency->symbol;
                unset($currency);
            }
            $this->ifs->currency_symbol = $this->cachedCurrencySymbols[$currency_id];
            $this->ifs->currency_id = $currency_id;
        }

        // Collect email addresses, and add them before save
        $emailAddresses = array(
            'non-primary' => array()
        );

        $importRequiredFields = $focus->get_import_required_fields();

        // to get a list of locked fields for this imported record
        $lockedFields = array();
        $fieldNum = array_search('id', $this->importColumns);
        if ($fieldNum) {
            $importedId = strip_tags(trim($row[$fieldNum]));
            if ($importedId) {
                $importedBean = BeanFactory::getBean($this->bean->module_dir, $importedId);
                if ($importedBean) {
                    $lockedFields = $importedBean->getLockedFields();
                }
            }
        }

                //flip the array so we can use it to get the key #
        $flippedVals = array_flip($this->importColumns);
        $fields_order = $this->getImportColumnsOrder($focus->getFieldDefinitions());
        $importFields = array();
        foreach ($fields_order as $fieldNum) {
            // loop if this column isn't set
            if ( !isset($this->importColumns[$fieldNum]) )
                continue;

            // get this field's properties
            $field           = $this->importColumns[$fieldNum];
            $this->correctRealNameFieldDef($focus, array($field));
            $importFields[] = $field;
            $fieldDef        = $focus->getFieldDefinition($field);
            $fieldTranslated = translate((isset($fieldDef['vname'])?$fieldDef['vname']:$fieldDef['name']), $focus->module_dir)." (".$fieldDef['name'].")";
            $defaultRowValue = '';
            // Bug 37241 - Don't re-import over a field we already set during the importing of another field
            if ( !empty($focus->$field) )
                continue;

            // translate strings
            global $locale;
            if(empty($locale))
            {
                $locale = Localization::getObject();
            }
            if ( isset($row[$fieldNum]) )
            {
                $rowValue = strip_tags(trim($row[$fieldNum]));
            }
            else if( isset($this->sugarToExternalSourceFieldMap[$field]) && isset($row[$this->sugarToExternalSourceFieldMap[$field]]) )
            {
                $rowValue = strip_tags(trim($row[$this->sugarToExternalSourceFieldMap[$field]]));
            }
            else
            {
                $rowValue = '';
            }

            // If there is an default value then use it instead
            if (!empty($_REQUEST["default_value_$field"])) {

                if ($fieldDef['type'] == 'relate' && !empty($row[$fieldNum]) && $row[$fieldNum] != $_REQUEST["default_value_$field"]) {
                    if (!empty($fieldDef['id_name']) && empty($row[$fieldDef['id_name']])) {
                        $focus->{$fieldDef['id_name']} = '';
                    }
                }

                $defaultRowValue = $this->populateDefaultMapValue($field, $_REQUEST["default_value_$field"], $fieldDef);

                if(!empty($fieldDef['custom_type']) && $fieldDef['custom_type'] == 'teamset' && empty($rowValue))
                {
                    $rowValue = implode(', ', SugarFieldTeamset::getTeamsFromRequest($field));
                }

                if( empty($rowValue))
                {
                    $rowValue = $defaultRowValue;
                    //reset the default value to empty
                    $defaultRowValue='';
                }
            }

            // Bug 22705 - Don't update the First Name or Last Name value if Full Name is set
            if ( in_array($field, array('first_name','last_name')) && !empty($focus->full_name) )
                continue;

            // loop if this value has not been set
            if ( !isset($rowValue) )
                continue;

            // If the field is required and blank then error out
            if (array_key_exists($field, $importRequiredFields) && empty($rowValue) && $rowValue != '0') {
                $this->importSource->writeError( $mod_strings['LBL_REQUIRED_VALUE'],$fieldTranslated,'NULL');
                $do_save = false;
            }

            // Handle the special case 'Sync to Mail Client'
            if ($focus->object_name == "Contact") {
                // Handle the special case 'Sync to Mail Client'
                if ($field == 'sync_contact') {
                    /**
                     * Bug #41194 : if true used as value of sync_contact - add curent user to list to sync
                     */
                    if (true == $rowValue || 'true' == strtolower($rowValue)) {
                        $focus->sync_contact = $focus->id;
                    } elseif (false == $rowValue || 'false' == strtolower($rowValue)) {
                        $focus->sync_contact = '';
                    } else {
                        $bad_names = array();
                        $returnValue = $this->ifs->synctooutlook($rowValue, $fieldDef, $bad_names);
                        // try the default value on fail
                        if (!$returnValue && !empty($defaultRowValue)) {
                            $returnValue = $this->ifs->synctooutlook($defaultRowValue, $fieldDef, $bad_names);
                        }
                        if (!$returnValue) {
                            $this->importSource->writeError(
                                $mod_strings['LBL_ERROR_SYNC_USERS'],
                                $fieldTranslated,
                                $bad_names
                            );
                            $do_save = 0;
                        } else {
                            $focus->sync_contact = $returnValue;
                        }
                    }
                }
                // Handle preferred_language to sync to current_language if not set
                if ($field == 'preferred_language' && empty($rowValue)) {
                    $rowValue = $current_language;
                }
            }

            // Handle email, email1 and email2 fields ( these don't have the type of email )
            if ( $field == 'email' || $field == 'email1' || $field == 'email2' )
            {
                $returnValue = $this->ifs->email($rowValue, $fieldDef, $focus);
                // try the default value on fail
                if ( !$returnValue && !empty($defaultRowValue) )
                    $returnValue = $this->ifs->email( $defaultRowValue, $fieldDef);
                if ( $returnValue === FALSE )
                {
                    $do_save=0;
                    $this->importSource->writeError( $mod_strings['LBL_ERROR_INVALID_EMAIL'], $fieldTranslated, $rowValue);
                }
                else
                {
                    $rowValue = $returnValue;

                    $defaultOptout  = !empty($GLOBALS['sugar_config']['new_email_addresses_opted_out']);
                    $address = array(
                        'email_address' => $rowValue,
                        'primary_address' => $field == 'email',
                        'invalid_email' => false,
                        'opt_out' => $defaultOptout,
                    );

                    // check for current opt_out and invalid email settings for this email address
                    // if we find any, set them now
                    $emailres = $focus->db->query( "SELECT opt_out, invalid_email FROM email_addresses WHERE email_address = '".$focus->db->quote($rowValue)."'");
                    if ( $emailrow = $focus->db->fetchByAssoc($emailres) )
                    {
                        $address = array_merge($address, $emailrow);
                    }

                    if ($field === 'email') {
                        //if the opt out column is set, then attempt to retrieve the values
                        if(isset($flippedVals['email_opt_out'])){
                            //if the string for this value has a length, then use it.
                            if(isset($row[$flippedVals['email_opt_out']]) && strlen($row[$flippedVals['email_opt_out']])>0){
                                $address['opt_out'] = $row[$flippedVals['email_opt_out']];
                            }
                        }

                        //if the invalid email column is set, then attempt to retrieve the values
                        if(isset($flippedVals['invalid_email'])){
                            //if the string for this value has a length, then use it.
                            if(isset($row[$flippedVals['invalid_email']]) && strlen($row[$flippedVals['invalid_email']])>0){
                                $address['invalid_email'] = $row[$flippedVals['invalid_email']];
                            }
                        }
                        $emailAddresses['primary'] = $address;
                    } else {
                        $emailAddresses['non-primary'][] = $address;
                    }
                }
            }

            if ($field == 'email_addresses_non_primary') {
                $nonPrimaryAddresses = $this->handleNonPrimaryEmails($rowValue, $defaultRowValue, $fieldTranslated, $flippedVals);
                $emailAddresses['non-primary'] = array_merge($emailAddresses['non-primary'], $nonPrimaryAddresses);
            }

            // Handle splitting Full Name into First and Last Name parts
            if ( $field == 'full_name' && !empty($rowValue) )
            {
                $this->ifs->fullname($rowValue,$fieldDef,$focus);
            }

            // to maintain 451 compatiblity
            if(!isset($fieldDef['module']) && $fieldDef['type']=='relate')
                $fieldDef['module'] = ucfirst($fieldDef['table']);

            if (!isset($fieldDef['module']) && $fieldDef['type']=='enum') {
                $fieldDef['module'] = $focus->module_name;
            }

            if(isset($fieldDef['custom_type']) && !empty($fieldDef['custom_type']))
                $fieldDef['type'] = $fieldDef['custom_type'];

            // If the field is empty then there is no need to check the data
            if( !empty($rowValue) )
            {
                //Start
                $rowValue = $this->sanitizeFieldValueByType($rowValue, $fieldDef, $defaultRowValue, $focus, $fieldTranslated);
                if ($rowValue === FALSE) {
					/* BUG 51213 - jeff @ neposystems.com */
                    $do_save = false;
                    continue;
				}
            }

            // if the parent type is in singular form, get the real module name for parent_type
            if (isset($fieldDef['type']) && $fieldDef['type']=='parent_type') {
                $rowValue = get_module_from_singular($rowValue);
            }

            // do not import if the record contains locked fields
            if (in_array($field, $lockedFields)) {
                $do_save = false;
                $this->importSource->writeError(
                    $mod_strings['LBL_RECORD_CONTAIN_LOCK_FIELD'],
                    $field,
                    $rowValue
                );
                break;
            }

            $focus->$field = $rowValue;
            unset($defaultRowValue);
        }

        // Now try to validate flex relate fields
        if ( isset($focus->field_defs['parent_name']) && isset($focus->parent_name) && ($focus->field_defs['parent_name']['type'] == 'parent') )
        {
            // populate values from the picker widget if the import file doesn't have them
            $parent_idField = $focus->field_defs['parent_name']['id_name'];
            if ( empty($focus->$parent_idField) && !empty($_REQUEST[$parent_idField]) )
                $focus->$parent_idField = $_REQUEST[$parent_idField];

            $parent_typeField = $focus->field_defs['parent_name']['type_name'];

            if ( empty($focus->$parent_typeField) && !empty($_REQUEST[$parent_typeField]) )
                $focus->$parent_typeField = $_REQUEST[$parent_typeField];
            // now validate it
            $returnValue = $this->ifs->parent($focus->parent_name,$focus->field_defs['parent_name'],$focus, empty($_REQUEST['parent_name']));
            if ( !$returnValue && !empty($_REQUEST['parent_name']) )
                $returnValue = $this->ifs->parent( $_REQUEST['parent_name'],$focus->field_defs['parent_name'], $focus);
        }

        // check to see that the indexes being entered are unique.
        if (isset($_REQUEST['enabled_dupes']) && $_REQUEST['enabled_dupes'] != "")
        {
            $toDecode = html_entity_decode  ($_REQUEST['enabled_dupes'], ENT_QUOTES);
            $enabled_dupes = json_decode($toDecode);
            $idc = new ImportDuplicateCheck($focus);

            if ( $idc->isADuplicateRecord($enabled_dupes) )
            {
                $this->importSource->markRowAsDuplicate($idc->_dupedFields);
                $this->_undoCreatedBeans($this->ifs->createdBeans);
                return;
            }
        }
        //Allow fields to be passed in for dup check as well (used by external adapters)
        else if( !empty($_REQUEST['enabled_dup_fields']) )
        {
            $toDecode = html_entity_decode  ($_REQUEST['enabled_dup_fields'], ENT_QUOTES);
            $enabled_dup_fields = json_decode($toDecode);
            $idc = new ImportDuplicateCheck($focus);
            if ( $idc->isADuplicateRecordByFields($enabled_dup_fields) )
            {
                $this->importSource->markRowAsDuplicate($idc->_dupedFields);
                $this->_undoCreatedBeans($this->ifs->createdBeans);
                return;
            }
        }

        // if the id was specified
        $newRecord = true;
        if ( !empty($focus->id) )
        {
            $focus->id = $this->_convertId($focus->id);

            // check if it already exists
            $query = "SELECT * FROM {$focus->table_name} WHERE id='".$focus->db->quote($focus->id)."'";
            $result = $focus->db->query($query)
            or sugar_die("Error selecting sugarbean: ");

            $dbrow = $focus->db->fetchByAssoc($result);

            if (isset ($dbrow['id']) && $dbrow['id'] != -1)
            {
                // if it exists but was deleted, just remove it
                // if you import a record, and specify the ID,
                // and the record ID already exists and is deleted... the "old" deleted record
                // should be removed and replaced with the new record you are importing.
                if (isset ($dbrow['deleted']) && $dbrow['deleted'] == 1)
                {
                    $this->removeDeletedBean($focus);
                    $focus->new_with_id = true;
                }
                else
                {
                    if( ! $this->isUpdateOnly )
                    {
                        $this->importSource->writeError($mod_strings['LBL_ID_EXISTS_ALREADY'],'ID',$focus->id);
                        $this->_undoCreatedBeans($this->ifs->createdBeans);
                        return;
                    }

                    $clonedBean = $this->cloneExistingBean($focus);
                    if($clonedBean === FALSE)
                    {
                        $this->importSource->writeError($mod_strings['LBL_RECORD_CANNOT_BE_UPDATED'],'ID',$focus->id);
                        $this->_undoCreatedBeans($this->ifs->createdBeans);
                        return;
                    }
                    else
                    {
                        $focus = $clonedBean;
                        $this->correctRealNameFieldDef($focus, $importFields);
                        $newRecord = FALSE;
                    }
                }
            }
            else
            {
                $focus->new_with_id = true;
            }
        }

        try {
            // Update e-mails here, because we're calling retrieve, and it overwrites the emailAddress object
            if ($focus->hasEmails()) {
                $this->handleEmailUpdate($focus, $emailAddresses);
            }
        } catch (Exception $e) {
            $this->importSource->writeError(
                $e->getMessage(),
                $fieldTranslated,
                $focus->id
            );
            $do_save = false;
        }

        if ($do_save)
        {
            // Need to ensure the imported record has an ID
            if ($newRecord && empty($focus->id)) {
                $focus->id = create_guid();
                $focus->new_with_id = true;
            }

            $this->handleTagsImport($focus, $row);
            $this->saveImportBean($focus, $newRecord);
            // Update the created/updated counter
            $this->importSource->markRowAsImported($newRecord);
        }
        else
            $this->_undoCreatedBeans($this->ifs->createdBeans);

        unset($defaultRowValue);

    }

    /**
     * Handles importing of tags for a row
     *
     * @param SugarBean $focus The parent sugar bean
     * @param array $row The row of data being imported
     */
    public function handleTagsImport($focus, $row)
    {
        // Handle tags import - this needs to be done only when we have an
        // ID for the parent record as relationships don't like it when you
        // don't have a real record to relate to
        if ($this->hasTags !== false && $focus->isTaggable()) {
            $sfh = new SugarFieldHandler();

            // Get the Tag SugarField for handling the saving
            $sfTag = $sfh->getSugarField('tag');

            // Build an argument list for this save
            $tagFieldName = $focus->getTagField();
            $tagField = $focus->field_defs[$tagFieldName];

            // Build the params array so the field can save what needs saving
            $params[$tagFieldName] = array();

            // Get the tags from the bean if they exist. Using a fresh bean
            // because at this point $focus is the new data.
            // THIS LINE IS FOR MERGING TAGS INSTEAD OF OVERRIDING THEM
            //$currentTags = $sfTag->getTagValues(BeanFactory::getBean($focus->module_dir, $focus->id), $tagFieldName);

            // Get the tags from the row. Tags are separated by double quotes.
            // ex Value1,Value2,Value3 and then merged with existing tags
            $importTags = $sfTag->getTagValuesFromImport($row[$this->hasTags]);

            // Now get all tags and massage them a little bit for uniqueness.
            // THIS LINE IS FOR MERGING TAGS INSTEAD OF OVERRIDING THEM
            //$allTags = array_merge($currentTags, $importTags);
            $allTags = $importTags;

            // Holds the lowercase tag name to make sure tags are unique for this record
            $tagCheck = array();
            foreach ($allTags as $tag) {
                // Simple cleansing
                $tag = trim($tag);

                // If the tag, after trim, is empty, move on
                if (!empty($tag)) {
                    $tagLower = strtolower($tag);

                    // If we haven't touched this tag yet, hit it
                    if (!isset($tagCheck[$tagLower])) {
                        // Add it to the params array
                        $params[$tagFieldName][] = array('name' => $tag);

                        // Mark that it has been checked
                        $tagCheck[$tagLower] = 1;
                    }
                }
            }

            // Now save the field
            $sfTag->apiSave($focus, $params, $tagFieldName, $tagField);
        }
    }

    protected function sanitizeFieldValueByType($rowValue, $fieldDef, $defaultRowValue, $focus, $fieldTranslated)
    {
        global $mod_strings, $app_list_strings;
        switch ($fieldDef['type'])
        {
            case 'enum':
            case 'multienum':
                if ( isset($fieldDef['type']) && $fieldDef['type'] == "multienum" )
                    $returnValue = $this->ifs->multienum($rowValue,$fieldDef);
                else
                    $returnValue = $this->ifs->enum($rowValue,$fieldDef);
                // try the default value on fail
                if ( !$returnValue && !empty($defaultRowValue) )
                {
                    if ( isset($fieldDef['type']) && $fieldDef['type'] == "multienum" )
                        $returnValue = $this->ifs->multienum($defaultRowValue,$fieldDef);
                    else
                        $returnValue = $this->ifs->enum($defaultRowValue,$fieldDef);
                }
                if ( $returnValue === FALSE )
                {
                    $opts = $this->ifs->getOptions($fieldDef['type'], $fieldDef);
                    $this->importSource->writeError(
                        ($opts ?
                            $mod_strings['LBL_ERROR_NOT_IN_ENUM'] . implode(",", $opts) :
                            $mod_strings['LBL_ERROR_ENUM_EMPTY']
                        ),
                        $fieldTranslated,
                        $rowValue
                    );
                    return false;
                }
                else
                    return $returnValue;

                break;
            case 'relate':
            case 'parent':
                $returnValue = $this->ifs->relate($rowValue, $fieldDef, $focus, empty($defaultRowValue));
                if (!$returnValue && !empty($defaultRowValue))
                    $returnValue = $this->ifs->relate($defaultRowValue,$fieldDef, $focus);
                // Bug 33623 - Set the id value found from the above method call as an importColumn
                if ($returnValue !== false && !in_array($fieldDef['id_name'], $this->importColumns))
                    $this->importColumns[] = $fieldDef['id_name'];
                return $rowValue;
                break;
            case 'teamset':
                $this->ifs->teamset($rowValue,$fieldDef,$focus);
                if (Team::$nameTeamsetMapping[$fieldDef['name']]) {
                    $this->importColumns[] = Team::$nameTeamsetMapping[$fieldDef['name']];
                }
                if (!empty($fieldDefs['id_name'])) {
                    $this->importColumns[] = $fieldDefs['id_name'];
                }
                return $rowValue;
                break;
            case 'fullname':
                return $rowValue;
                break;
            default:
                $fieldtype = $fieldDef['type'];
                $returnValue = $this->ifs->$fieldtype($rowValue, $fieldDef, $focus);
                // try the default value on fail
                if ( !$returnValue && !empty($defaultRowValue) )
                    $returnValue = $this->ifs->$fieldtype($defaultRowValue,$fieldDef, $focus);
                if ( !$returnValue )
                {
                    $this->importSource->writeError($mod_strings['LBL_ERROR_INVALID_'.strtoupper($fieldDef['type'])],$fieldTranslated,$rowValue,$focus);
                    return FALSE;
                }
                return $returnValue;
        }
    }

    protected function cloneExistingBean($focus)
    {
        $existing_focus = clone $this->bean;
        if (!($existing_focus->retrieve($focus->id) instanceOf SugarBean) || !$existing_focus->ACLAccess('edit')) {
            return FALSE;
        }
        else
        {
            $newData = $focus->toArray();
            foreach ( $newData as $focus_key => $focus_value )
                if ( in_array($focus_key,$this->importColumns) )
                    $existing_focus->$focus_key = $focus_value;

            return $existing_focus;
        }
    }

    protected function removeDeletedBean($focus)
    {
        global $mod_strings;

        $query2 = "DELETE FROM {$focus->table_name} WHERE id='".$focus->db->quote($focus->id)."'";
        $result2 = $focus->db->query($query2) or sugar_die($mod_strings['LBL_ERROR_DELETING_RECORD']." ".$focus->id);
        if ($focus->hasCustomFields())
        {
            $query3 = "DELETE FROM {$focus->table_name}_cstm WHERE id_c='".$focus->db->quote($focus->id)."'";
            $result2 = $focus->db->query($query3);
        }
    }

    protected function saveImportBean($focus, $newRecord)
    {
        global $timedate, $current_user;

        // Populate in any default values to the bean
        $focus->populateDefaultValues();

        if ( !isset($focus->assigned_user_id) || $focus->assigned_user_id == '' && $newRecord )
        {
            $focus->assigned_user_id = $current_user->id;
        }
        if ( !isset($focus->team_id) || $focus->team_id == '' && $newRecord )
        {
            $focus->team_id = $current_user->default_team;
        }
        /*
        * Bug 34854: Added all conditions besides the empty check on date modified.
        */
        if ( ( !empty($focus->new_with_id) && !empty($focus->date_modified) ) ||
             ( empty($focus->new_with_id) && $timedate->to_db($focus->date_modified) != $timedate->to_db($timedate->to_display_date_time($focus->fetched_row['date_modified'])) )
        )
            $focus->update_date_modified = false;

        // Bug 53636 - Allow update of "Date Created"
        if (!empty($focus->date_entered)) {
        	$focus->update_date_entered = true;
        }

        $focus->optimistic_lock = false;
        if ( $focus->object_name == "Contact" && isset($focus->sync_contact) )
        {
            //copy the potential sync list to another varible
            $list_of_users=$focus->sync_contact;
            //and set it to false for the save
            $focus->sync_contact=false;
        }
        else if($focus->object_name == "User" && !empty($current_user) && $focus->is_admin && !is_admin($current_user) && is_admin_for_module($current_user, 'Users')) {
            sugar_die($GLOBALS['mod_strings']['ERR_IMPORT_SYSTEM_ADMININSTRATOR']);
        }
        //bug# 46411 importing Calls will not populate Leads or Contacts Subpanel
        if (!empty($focus->parent_type) && !empty($focus->parent_id))
        {
            foreach ($focus->relationship_fields as $key => $val)
            {
                if ($val == strtolower($focus->parent_type))
                {
                    $focus->$key = $focus->parent_id;
                }
            }
        }
        //bug# 40260 setting it true as the module in focus is involved in an import
        $focus->in_import=true;
        // call any logic needed for the module preSave
        $focus->beforeImportSave();

        // Bug51192: check if there are any changes in the imported data
        $hasDataChanges = false;
        $dataChanges=$focus->db->getAuditDataChanges($focus);

        if(!empty($dataChanges)) {
            foreach($dataChanges as $field=>$fieldData) {
                if($fieldData['data_type'] != 'date' || strtotime($fieldData['before']) != strtotime($fieldData['after'])) {
                    $hasDataChanges = true;
                    break;
                }
            }
        }

        // if modified_user_id is set, set the flag to false so SugarBEan will not reset it
        if (isset($focus->modified_user_id) && $focus->modified_user_id && !$hasDataChanges) {
            $focus->update_modified_by = false;
        }
        // if created_by is set, set the flag to false so SugarBEan will not reset it
        if (isset($focus->created_by) && $focus->created_by) {
            $focus->set_created_by = false;
        }

        if ( $focus->object_name == "Contact" && isset($list_of_users) )
            $focus->process_sync_to_outlook($list_of_users);
        // Before calling save, we need to clear out any existing registered AWF
        // triggered start events so they can continue to trigger.
        Registry\Registry::getInstance()->drop('triggered_starts');
        $focus->save(false);

        //now that save is done, let's make sure that parent and related id's were saved as relationships
        //this takes place before the afterImportSave()
        $this->checkRelatedIDsAfterSave($focus);

        // call any logic needed for the module postSave
        $focus->afterImportSave();

        // Add ID to User's Last Import records
        if ( $newRecord ) {
            $importModule = $this->request->getValidInputRequest('import_module', 'Assert\Mvc\ModuleName', '');
            $this->importSource->writeRowToLastImport($importModule, ($focus->object_name == 'Case' ? 'aCase' : $focus->object_name), $focus->id);
        }

    }

    protected function saveMappingFile()
    {
        global $current_user;

        $firstrow = InputValidation::getService()->getValidInputRequest(
            'firstrow',
            array('Assert\PhpSerialized' => array('base64Encoded' => true))
        );
        $mappingValsArr = $this->importColumns;
        $mapping_file = BeanFactory::newBean('Import_1');
        $mapping_file->delimiter = $_REQUEST['custom_delimiter'];
        $mapping_file->enclosure = html_entity_decode($_REQUEST['custom_enclosure'], ENT_QUOTES);

        if ( isset($_REQUEST['has_header']) && $_REQUEST['has_header'] == 'on')
        {
            $header_to_field = array ();
            foreach ($this->importColumns as $pos => $field_name)
            {
                if (isset($firstrow[$pos]) && isset($field_name))
                {
                    $header_to_field[$firstrow[$pos]] = $field_name;
                }
            }

            $mappingValsArr = $header_to_field;
        }
        //get array of values to save for duplicate and locale settings
        $advMapping = $this->retrieveAdvancedMapping();

        //merge with mappingVals array
        if(!empty($advMapping) && is_array($advMapping))
        {
            $mappingValsArr = array_merge($mappingValsArr,$advMapping);
        }

        //set mapping
        $mapping_file->setMapping($mappingValsArr);

        // save default fields
        $defaultValues = array();
        for ( $i = 0; $i < $_REQUEST['columncount']; $i++ )
        {
            if (isset($this->importColumns[$i]) && !empty($_REQUEST[$this->importColumns[$i]]))
            {
                $field = $this->importColumns[$i];
                $fieldDef = $this->bean->getFieldDefinition($field);
                if(!empty($fieldDef['custom_type']) && $fieldDef['custom_type'] == 'teamset')
                {
                    $teams = SugarFieldTeamset::getTeamsFromRequest($field);
                    if(isset($_REQUEST['primary_team_name_collection']))
                    {
                        $primary_index = $_REQUEST['primary_team_name_collection'];
                    }

                    //If primary_index was selected, ensure that the first Array entry is the primary team
                    if(isset($primary_index))
                    {
                        $count = 0;
                        $new_teams = array();
                        foreach($teams as $id=>$name)
                        {
                            if($primary_index == $count++)
                            {
                                $new_teams[$id] = $name;
                                unset($teams[$id]);
                                break;
                            }
                        }

                        foreach($teams as $id=>$name)
                        {
                            $new_teams[$id] = $name;
                        }
                        $teams = $new_teams;
                    } //if

                    $json = getJSONobj();
                    $defaultValues[$field] = $json->encode($teams);
                }
                else
                {
                    $defaultValues[$field] = $_REQUEST[$this->importColumns[$i]];
                }
            }
        }
        $mapping_file->setDefaultValues($defaultValues);
        $result = $mapping_file->save( $current_user->id,  $_REQUEST['save_map_as'], $_REQUEST['import_module'], $_REQUEST['source'],
            ( isset($_REQUEST['has_header']) && $_REQUEST['has_header'] == 'on'), $_REQUEST['custom_delimiter'], html_entity_decode($_REQUEST['custom_enclosure'],ENT_QUOTES)
        );
    }


    protected function populateDefaultMapValue($field, $fieldValue, $fieldDef)
    {
        global $timedate, $current_user;

        // encodeMultienumValue already checks for array
        $defaultRowValue = encodeMultienumValue($fieldValue);

        // translate default values to the date/time format for the import file
        if( $fieldDef['type'] == 'date' && $this->ifs->dateformat != $timedate->get_date_format() )
            $defaultRowValue = $timedate->swap_formats($defaultRowValue, $this->ifs->dateformat, $timedate->get_date_format());

        if( $fieldDef['type'] == 'time' && $this->ifs->timeformat != $timedate->get_time_format() )
            $defaultRowValue = $timedate->swap_formats($defaultRowValue, $this->ifs->timeformat, $timedate->get_time_format());

        if( ($fieldDef['type'] == 'datetime' || $fieldDef['type'] == 'datetimecombo') && $this->ifs->dateformat.' '.$this->ifs->timeformat != $timedate->get_date_time_format() )
            $defaultRowValue = $timedate->swap_formats($defaultRowValue, $this->ifs->dateformat.' '.$this->ifs->timeformat,$timedate->get_date_time_format());

        if ( in_array($fieldDef['type'],array('currency','float','int','num')) && $this->ifs->num_grp_sep != $current_user->getPreference('num_grp_sep') )
            $defaultRowValue = str_replace($current_user->getPreference('num_grp_sep'), $this->ifs->num_grp_sep,$defaultRowValue);

        if ( in_array($fieldDef['type'],array('currency','float')) && $this->ifs->dec_sep != $current_user->getPreference('dec_sep') )
            $defaultRowValue = str_replace($current_user->getPreference('dec_sep'), $this->ifs->dec_sep,$defaultRowValue);

        $user_currency_symbol = $this->defaultUserCurrency->symbol;
        if ( $fieldDef['type'] == 'currency' && $this->ifs->currency_symbol != $user_currency_symbol )
            $defaultRowValue = str_replace($user_currency_symbol, $this->ifs->currency_symbol,$defaultRowValue);

        return $defaultRowValue;
    }

    protected function getImportColumns()
    {
        $importable_fields = $this->bean->get_importable_fields();
        $importColumns = array();
        foreach ($_REQUEST as $name => $value)
        {
            // only look for var names that start with "fieldNum"
            if (strncasecmp($name, "colnum_", 7) != 0)
                continue;

            // pull out the column position for this field name
            $pos = substr($name, 7);

            if ( isset($importable_fields[$value]) )
            {
                // now mark that we've seen this field
                $importColumns[$pos] = $value;
            }
        }

        return $importColumns;
    }

    protected function getFieldSanitizer()
    {
        $ifs = new ImportFieldSanitize();
        $copyFields = array('dateformat','timeformat','timezone','default_currency_significant_digits','num_grp_sep','dec_sep','default_locale_name_format');
        foreach($copyFields as $field)
        {
            $fieldKey = "importlocale_$field";
            $ifs->$field = $this->importSource->$fieldKey;
        }

        $currency = BeanFactory::getBean('Currencies', $this->importSource->importlocale_currency);
        $ifs->currency_symbol = $currency->symbol;

        return $ifs;
    }

    /**
     * Sets a translation map from sugar field key to external source key used while importing a row.  This allows external sources
     * to return a data set that is an associative array rather than numerically indexed.
     *
     * @param  $translator
     * @return void
     */
    public function setFieldKeyTranslator($translator)
    {
        $this->sugarToExternalSourceFieldMap = $translator;
    }

    /**
     * If a bean save is not done for some reason, this method will undo any of the beans that were created
     *
     * @param array $ids ids of user_last_import records created
     */
    protected function _undoCreatedBeans( array $ids )
    {
        $focus = BeanFactory::newBean('Import_2');
        foreach ($ids as $id)
            $focus->undoById($id);
    }

    /**
     * clean id's when being imported
     *
     * @param  string $string
     * @return string
     */
    protected function _convertId($string)
    {
        return preg_replace_callback(
            '|[^A-Za-z0-9\-\_\.]|',
            create_function(
            // single quotes are essential here,
            // or alternative escape all $ as \$
            '$matches',
            'return ord($matches[0]);'
                 ) ,
            $string);
    }

    public function retrieveAdvancedMapping()
    {
        $advancedMappingSettings = array();

        //harvest the dupe index settings
        if( isset($_REQUEST['enabled_dupes']) )
        {
            $toDecode = html_entity_decode  ($_REQUEST['enabled_dupes'], ENT_QUOTES);
            $dupe_ind = json_decode($toDecode);

            foreach($dupe_ind as $dupe)
            {
                $advancedMappingSettings['dupe_'.$dupe] = $dupe;
            }
        }

        foreach($_REQUEST as $rk=>$rv)
        {
            //harvest the import locale settings
            if(strpos($rk,'portlocale_')>0)
            {
                $advancedMappingSettings[$rk] = $rv;
            }

        }
        return $advancedMappingSettings;
    }

    public static function getImportableModules()
    {
        global $beanList;
        $importableModules = array();
        foreach ($beanList as $moduleName => $beanName)
        {
            $tmp = BeanFactory::newBean($moduleName);
            if( !empty($tmp->importable))
            {
                $label = isset($GLOBALS['app_list_strings']['moduleList'][$moduleName]) ? $GLOBALS['app_list_strings']['moduleList'][$moduleName] : $moduleName;
                $importableModules[$moduleName] = $label;
            }
        }

        asort($importableModules);
        return $importableModules;
    }


    /**
     * Replaces PHP error handler in Step4
     *
     * @param int    $errno
     * @param string $errstr
     * @param string $errfile
     * @param string $errline
     */
    public static function handleImportErrors($errno, $errstr, $errfile, $errline)
    {
        // Error was suppressed with the @-operator.
        if (error_reporting() === 0) {
            return false;
        }

        $GLOBALS['log']->fatal("Caught error: $errstr");

        if ( !defined('E_DEPRECATED') )
            define('E_DEPRECATED','8192');
        if ( !defined('E_USER_DEPRECATED') )
            define('E_USER_DEPRECATED','16384');

        $isFatal = false;
        switch ($errno)
        {
            case E_USER_ERROR:
                $message = "ERROR: [$errno] $errstr on line $errline in file $errfile<br />\n";
                $isFatal = true;
                break;
            case E_USER_WARNING:
            case E_WARNING:
                $message = "WARNING: [$errno] $errstr on line $errline in file $errfile<br />\n";
                break;
            case E_USER_NOTICE:
            case E_NOTICE:
                $message = "NOTICE: [$errno] $errstr on line $errline in file $errfile<br />\n";
                break;
            case E_STRICT:
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                // don't worry about these
                // $message = "STRICT ERROR: [$errno] $errstr on line $errline in file $errfile<br />\n";
                $message = "";
                break;
            default:
                $message = "Unknown error type: [$errno] $errstr on line $errline in file $errfile<br />\n";
                break;
        }

        // check to see if current reporting level should be included based upon error_reporting() setting, if not
        // then just return
        if (error_reporting() & $errno)
        {
            echo $message;
        }

        if ($isFatal)
        {
            exit(1);
        }
    }


    /**
	 * upon bean save, the relationships are saved by SugarBean->save_relationship_changes() method, but those values depend on
     * the request object and is not reliable during import.  This function makes sure any defined related or parent id's are processed
	 * and their relationship saved.
	 */
    public function checkRelatedIDsAfterSave($focus)
    {
        if(empty($focus)){
            return false;
        }

        //check relationship fields first
        if(!empty($focus->parent_id) && !empty($focus->parent_type)){
            $relParentName = strtolower($focus->parent_type);
            $relParentID = strtolower($focus->parent_id);
        }
        if(!empty($focus->related_id) && !empty($focus->related_type)){
            $relName = strtolower($focus->related_type);
            $relID = strtolower($focus->related_id);
        }

        //now refresh the bean and process for parent relationship
        $focus->retrieve($focus->id);
        if(!empty($relParentName) && !empty($relParentID)){

            //grab the relationship and any available ids
            if(!empty($focus->$relParentName)){
                $rel_ids=array();
                $focus->load_relationship($relParentName);
                $rel_ids = $focus->$relParentName->get();

                //if the current parent_id is not part of the stored rels, then add it
                if(!in_array($relParentID, $rel_ids)){
                    $focus->$relParentName->add($relParentID);
                }
            }
        }

        //now lets process any related fields
        if(!empty($relName) && !empty($relID)){
            if(!empty($focus->$relName)){
                $rel_ids=array();
                $focus->load_relationship($relName);
                $rel_ids = $focus->$relName->get();

                //if the related_id is not part of the stored rels, then add it
                if(!in_array($relID, $rel_ids)){
                    $focus->$relName->add($relID);
                }
            }
        }
    }

    /**
     * Fill the emailAddress object with e-mails
     *
     * @param SugarBean $bean Target bean
     * @param array $addresses Addresses to be added
     */
    protected function handleEmailUpdate(SugarBean $bean, array $addresses)
    {
        // Make sure that operating on email addresses is possible
        if (!isset($bean->emailAddress->addresses) || !is_array($bean->emailAddress->addresses)) {
            throw new RuntimeException("Trying to handle email addresses in a Bean that doesn't support it.");
        }

        if (!empty($addresses['primary'])) {
            foreach ($bean->emailAddress->addresses as $key => $value) {
                if ($value['primary_address']) {
                    unset($bean->emailAddress->addresses[$key]);
                    break;
                }
            }
            $this->importAddress($bean, $addresses['primary']);
        }

        if (!empty($addresses['non-primary'])) {
            foreach ($bean->emailAddress->addresses as $key => $value) {
                if (!$value['primary_address']) {
                    unset($bean->emailAddress->addresses[$key]);
                }
            }
            foreach ($addresses['non-primary'] as $address) {
                $this->importAddress($bean, $address);
            }
        }

        // Necessary for legacy compatibility
        $bean->emailAddress->populateLegacyFields($bean);
    }

    /**
     * Handles non-primary emails string read from CSV file
     *
     * @param mixed     $rowValue        Serialized data
     * @param mixed     $defaultRowValue Default value in case if row value is empty
     * @param string    $fieldTranslated Name of CSV column
     * @param array $columnMap array of colummn names and indices keyed by column name
     *
     * @return array                     Collection of parsed non-primary e-mails
     */
    protected function handleNonPrimaryEmails($rowValue, $defaultRowValue, $fieldTranslated, $columnMap = array())
    {
        $parsed = $this->parseNonPrimaryEmails($rowValue, $fieldTranslated, $columnMap);
        if (!$parsed && !empty($defaultRowValue)) {
            $parsed = $this->parseNonPrimaryEmails($defaultRowValue, $fieldTranslated. $columnMap);
        }

        return $parsed;
    }

    /**
     * Imports a single email address into bean
     *
     * @param SugarBean $bean    Target bean
     * @param array     $address Address properties
     */
    protected function importAddress(SugarBean $bean, array $address)
    {
        // make sure that operating on email addresses is possible
        if (!isset($bean->emailAddress->addresses) || !is_array($bean->emailAddress->addresses)) {
            return;
        }

        // look if bean already has same email address
        foreach ($bean->emailAddress->addresses as $beanAddress) {
            if ($beanAddress['email_address'] == $address['email_address']) {
                // if found, preserve the values of attributes that are not being imported
                // e.g. previous versions of SugarCRM don't export invalid and opt_out
                $address = array_merge($beanAddress, $address);
                break;
            }
        }

        // provide default attributes in case they were not inherited from existing address
        $address = array_merge(array(
            'reply_to_address' => false,
            'email_id' => null,
        ), $address);

        $bean->emailAddress->addAddress(
            $address['email_address'],
            $address['primary_address'],
            $address['reply_to_address'],
            $address['invalid_email'],
            $address['opt_out'],
            $address['email_id']
        );

        $bean->emailAddress->dontLegacySave = true;
    }

    /**
     * Parses serialized data of non-primary email addresses
     *
     * @param string $value           Serialized data in the following format:
     *                                email_address1[,invalid_email1[,opt_out1]][;email_address2...]
     * @param string $fieldTranslated Name of CSV column
     * @param array $columnMap array of colummn names and indices keyed by column name
     *
     * @return array                  Collection of address properties
     * @see serializeNonPrimaryEmails()
     */
    protected function parseNonPrimaryEmails($value, $fieldTranslated, $columnMap = array())
    {
        global $mod_strings;

        $result = array();

        if (empty($value)) {
            return $result;
        }

        // explode serialized value into groups of attributes
        $emails = explode(';', $value);
        foreach ($emails as $email) {
            // explode serialized attributes
            $attrs = explode(',', $email);
            if (!$attrs) {
                continue;
            }

            $email_address = array_shift($attrs);
            if (!$this->ifs->email($email_address, array())) {
                $this->importSource->writeError(
                    $mod_strings['LBL_ERROR_INVALID_EMAIL'],
                    $fieldTranslated,
                    $email_address
                );
                continue;
            }

            $defaultOptout  = !empty($GLOBALS['sugar_config']['new_email_addresses_opted_out']);
            $address = array(
                'email_address' => $email_address,
                'primary_address' => false,
                'invalid_email' => false,
                'opt_out' => $defaultOptout,
            );

            // check for current opt_out and invalid email settings for this email address
            // if we find any, set them now
            $db = DBManagerFactory::getInstance();
            $queryStr = "SELECT opt_out, invalid_email FROM email_addresses WHERE email_address = ".
                $db->quoted($email_address);
            $queryResult = $db->query($queryStr);

            if ($row = $db->fetchByAssoc($queryResult)) {
                $address = array_merge($address, $row);
            }

            // check for invalid_email in $attrs and use its value if that column name is mapped
            if ($attrs) {
                $invalid_email = array_shift($attrs);
                if (isset($columnMap['invalid_email'])) {
                    $invalid_email = $this->ifs->bool($invalid_email, array());
                    if ($invalid_email === false) {
                        $this->importSource->writeError(
                            $mod_strings['LBL_ERROR_INVALID_BOOL'],
                            $fieldTranslated,
                            $invalid_email
                        );
                        continue;
                    }
                    $address['invalid_email'] = $invalid_email;
                }
            }

            // check for email_opt_out in $attrs and use its value if that column name is mapped
            if ($attrs) {
                $opt_out = array_shift($attrs);
                if (isset($columnMap['email_opt_out'])) {
                    $opt_out = $this->ifs->bool($opt_out, array());
                    if ($opt_out === false) {
                        $this->importSource->writeError(
                            $mod_strings['LBL_ERROR_INVALID_BOOL'],
                            $fieldTranslated,
                            $opt_out
                        );
                        continue;
                    }
                    $address['opt_out'] = $opt_out;
                }
            }

            $result[] = $address;
        }

        return $result;
    }

    /**
     * Replaces user_name with full name when use_real_name preference setting is enabled and this is a user name field
     * and vice versa when use_real_name preference setting is disabled
     *
     * @param SugarBean $focus
     * @param $importFields
     */
    protected function correctRealNameFieldDef(SugarBean $focus, $importFields)
    {
        global $current_user;

        $useRealNames = $current_user->getPreference('use_real_names');

        foreach ($importFields as $fieldName) {
            if (!empty($focus->field_defs[$fieldName]['type']) &&
                $focus->field_defs[$fieldName]['type'] == 'relate' &&
                !empty($focus->field_defs[$fieldName]['module']) &&
                $focus->field_defs[$fieldName]['module'] == 'Users' &&
                !empty($focus->field_defs[$fieldName]['rname']) &&
                in_array($focus->field_defs[$fieldName]['rname'], array('full_name', 'user_name'))
            ) {
                $focus->field_defs[$fieldName]['rname'] = $useRealNames ? 'full_name' : 'user_name';
            }
        }
    }
}
