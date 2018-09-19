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

class ForecastManagerWorksheetHooks
{
    /**
     * This method, just set the date_modified to the value from the db, vs the user formatted value that sugarbean sets
     * after it has been retrieved
     *
     * @param ForecastManagerWorksheet $worksheet
     * @param string $event
     * @param array $params
     */
    public static function fixDateModified(ForecastManagerWorksheet $worksheet, $event, $params = array())
    {
        $worksheet->date_modified = $worksheet->fetched_row['date_modified'];
    }

    /**
     * Set the Manager Saved Flag
     *
     * If the person saving the worksheet is the one who the worksheet is assigned to and
     * it's a draft_save_type from the manager worksheet view, then set the flag to be true
     *
     * @param ForecastManagerWorksheet $worksheet
     * @param $event
     * @param array $params
     */
    public static function setManagerSavedFlag(ForecastManagerWorksheet $worksheet, $event, $params = array())
    {
        if ($event == 'before_save' && $worksheet->draft == true
            && $worksheet->manager_saved == false && in_array($worksheet->draft_save_type, array('commit','draft'))
            && $worksheet->assigned_user_id == $worksheet->modified_user_id) {
                $worksheet->manager_saved = true;
        }
    }

    /**
     * This checks to see if the only thing that has changed is the quota, if it is, then don't update the date
     * modified
     *
     * @param ForecastManagerWorksheet $worksheet       The Bean
     * @param string $event                             Which event is being fired
     * @param array $params                             Extra Params
     */
    public static function draftRecordQuotaOnlyCheck(ForecastManagerWorksheet $worksheet, $event, $params = array())
    {
        // this should only run on before_save and when the worksheet is a draft record
        // and the draft_save_type is assign_quota
        if ($event == 'before_save' && $worksheet->draft == 1 && $worksheet->draft_save_type == 'assign_quota') {
            $mm = MetaDataManager::getManager();
            $views = $mm->getModuleViews($worksheet->module_name);

            $fields = $views['list']['meta']['panels'][0]['fields'];

            $onlyQuotaChanged = true;

            foreach ($fields as $field) {
                if (($field['type'] == 'currency') && preg_match('#\.[\d]{6}$#', $worksheet->{$field['name']}) === 0) {
                    $worksheet->{$field['name']} = SugarMath::init($worksheet->{$field['name']}, 6)->result();
                }

                if ($worksheet->fetched_row[$field['name']] !== $worksheet->{$field['name']}) {
                    if ($field['name'] !== 'quota') {
                        $onlyQuotaChanged = false;
                        break;
                    }
                }
            }

            if ($onlyQuotaChanged === true) {
                $worksheet->update_date_modified = false;
            }
        }
    }
}
