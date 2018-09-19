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
 * view.showduplicates.php
 *
 * This is the SugarView subclass to handle displaying the list of duplicate Leads found when attempting to create
 * a new Lead.  This class is called from the LeadFormBase class handleSave function.
 */
class ViewShowDuplicates extends SugarView
{

    function display()
    {
        global $app_strings;
        global $app_list_strings;
        global $theme;
        global $current_language;
        global $mod_strings;

        $db = DBManagerFactory::getInstance();

        if(!isset($_SESSION['SHOW_DUPLICATES']))
        {
            $GLOBALS['log']->error("Unauthorized access to this area.");
            sugar_die("Unauthorized access to this area.");
        }

        parse_str($_SESSION['SHOW_DUPLICATES'],$_POST);
        $post = array_map("securexss", $_POST);
        foreach ($post as $k => $v) {
            $_POST[$k] = $v;
        }
        unset($_SESSION['SHOW_DUPLICATES']);

        $lead = BeanFactory::newBean('Leads');
        $leadForm = new LeadFormBase();
        $GLOBALS['check_notify'] = FALSE;

        $query = 'SELECT id, first_name, last_name, title FROM leads WHERE deleted=0 ';

        $duplicates = $_POST['duplicate'];
        $count = count($duplicates);
        if ($count > 0)
        {
            $query .= "and (";
            $first = true;
            foreach ($duplicates as $duplicate_id)
            {
                if (!$first) $query .= ' OR ';
                $first = false;
                $query .= "id=".$db->quoted($duplicate_id)." ";
            }
            $query .= ')';
        }

        $duplicateLeads = array();

        $result = $db->query($query);
        $i=0;
        while (($row=$db->fetchByAssoc($result)) != null) {
            $duplicateLeads[$i] = $row;
            $i++;
        }

        $this->ss->assign('FORMBODY', $leadForm->buildTableForm($duplicateLeads));

        $fields = array_merge($lead->column_fields, $lead->additional_column_fields, array(
            // Bug 25311 - Add special handling for when the form specifies many-to-many relationships
            'relate_to',
            'relate_id',
        ));

        $params = array();
        foreach ($fields as $field) {
            $value = $this->request->getValidInputPost('Leads' . $field);
            if ($value) {
                $params[$field] = $value;
            }
        }

        $input = '';
        foreach ($params as $param => $value) {
            $input .= '<input type="hidden" name="' . htmlspecialchars($param, ENT_QUOTES, 'UTF-8')
                . '" value="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '">' . "\n";
        }

        $input .= get_teams_hidden_inputs('Leads');

        $emailAddress = BeanFactory::newBean('EmailAddresses');
        $input .= $emailAddress->getEmailAddressWidgetDuplicatesView($lead);

        $this->ss->assign('RETURN_MODULE', $this->request->getValidInputPost('return_module', 'Assert\Mvc\ModuleName'));
        $this->ss->assign('RETURN_ACTION', $this->request->getValidInputPost('return_action'));

        ///////////////////////////////////////////////////////////////////////////////
        ////	INBOUND EMAIL WORKFLOW
        if(isset($_REQUEST['inbound_email_id'])) {
            $inbound_email_id = $this->request->getValidInputRequest('inbound_email_id', 'Assert\Guid');
            $this->ss->assign('INBOUND_EMAIL_ID', $inbound_email_id);
            $this->ss->assign('RETURN_MODULE', 'Emails');
            $this->ss->assign('RETURN_ACTION', 'EditView');
            $this->ss->assign('START', $this->request->getValidInputRequest('start'));
        }
        ////	END INBOUND EMAIL WORKFLOW
        ///////////////////////////////////////////////////////////////////////////////
        $popup = $this->request->getValidInputRequest('popup', null, 'false');
        $input .= '<input type="hidden" name="popup" value="' . htmlspecialchars($popup, ENT_QUOTES, 'UTF-8') . '">';
        $to_pdf = $this->request->getValidInputRequest('to_pdf', null, 'false');
        $input .= '<input type="hidden" name="to_pdf" value="' . htmlspecialchars($to_pdf, ENT_QUOTES, 'UTF-8') . '">';
        $create = $this->request->getValidInputRequest('to_pdf', null, 'false');
        $input .= '<input type="hidden" name="create" value="' . htmlspecialchars($create, ENT_QUOTES, 'UTF-8') . '">';
        $this->ss->assign('RETURN_ID', $this->request->getValidInputPost('return_id', 'Assert\Guid'));

        $this->ss->assign('INPUT_FIELDS', $input);

        $saveLabel = string_format($app_strings['LBL_SAVE_OBJECT'], array($this->module));
        $this->ss->assign('TITLE', getClassicModuleTitle('Leads', array($this->module, $saveLabel), true));
        //Load the appropriate template
        $this->ss->displayCustom('modules/Leads/tpls/ShowDuplicates.tpl');
    }

}
