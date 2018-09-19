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

require_once('include/SearchForm/SearchForm2.php');

class EmployeesSearchForm extends SearchForm {
    /**
     * This builds an EmployeesSearchForm from a classic search form.
     */
    public function __construct($oldSearchForm, $module = '') {
        if (!($oldSearchForm instanceof SearchForm) && !empty($module)) {
            $oldSearchForm = new SearchForm($oldSearchForm, $module);
        }
        parent::__construct($oldSearchForm->seed, $oldSearchForm->module, $oldSearchForm->action);
        $this->setup(
            // $searchdefs
            array($oldSearchForm->module => $oldSearchForm->searchdefs),
            // $searchFields
            array($oldSearchForm->module => $oldSearchForm->searchFields),
            // $tpl
            $oldSearchForm->tpl,
            // $displayView
            $oldSearchForm->displayView,
            // listViewDefs
            $oldSearchForm->listViewDefs);
        
        $this->lv = $oldSearchForm->lv;
    }
    
    public function generateSearchWhere($add_custom_fields = false, $module = '') {
        $onlyActive = false;
        if (isset($this->searchFields['open_only_active_users']['value'])) {
            if ( $this->searchFields['open_only_active_users']['value'] == 1) {
                $onlyActive = true;
            }
            unset($this->searchFields['open_only_active_users']['value']);
        }
        $where_clauses = parent::generateSearchWhere($add_custom_fields, $module);
        
        if ( $onlyActive ) {
            $where_clauses[] = "users.employee_status = 'Active'";
        }
        
        // Add in code to remove portal/group/hidden users
        $where_clauses[] = "users.portal_only = 0";
        $where_clauses[] = "(users.is_group = 0 or users.is_group is null)";
        $where_clauses[] = "users.show_on_employees = 1";
        return $where_clauses;
    }
}