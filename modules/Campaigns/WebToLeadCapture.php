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
use Sugarcrm\Sugarcrm\DependencyInjection\Container;
use Sugarcrm\Sugarcrm\Security\Context;
use Sugarcrm\Sugarcrm\Security\Subject\WebToLead;

require_once('include/formbase.php');



global $app_strings, $app_list_strings, $sugar_config, $timedate, $current_user;

$mod_strings = return_module_language($sugar_config['default_language'], 'Leads');

$app_list_strings['record_type_module'] = array('Contact'=>'Contacts', 'Account'=>'Accounts', 'Opportunity'=>'Opportunities', 'Case'=>'Cases', 'Note'=>'Notes', 'Call'=>'Calls', 'Email'=>'Emails', 'Meeting'=>'Meetings', 'Task'=>'Tasks', 'Lead'=>'Leads','Bug'=>'Bugs',

'Report'=>'Reports',  'Quote'=>'Quotes'
);

/**
 * To make your changes upgrade safe create a file called leadCapture_override.php and place the changes there
 */
$users = array(
	'PUT A RANDOM KEY FROM THE WEBSITE HERE' => array('name'=>'PUT THE USER_NAME HERE', 'pass'=>'PUT THE USER_HASH FOR THE RESPECTIVE USER HERE'),
);

$request = InputValidation::getService();

$previousSoftFail = $request->getSoftFail();
$request->setSoftFail(false);

$redirect = $request->getValidInputPost(
    'redirect',
    array(
        'Assert\Url' => array(
            'protocols' => array('http', 'https'),
        )
    ),
    ''
);

$request->setSoftFail($previousSoftFail);

if (isset($_POST['campaign_id']) && !empty($_POST['campaign_id'])) {
	    //adding the client ip address
	    $_POST['client_id_address'] = query_client_ip();
		$campaign_id=$_POST['campaign_id'];
        // create and activate subject for audit
        $subject = new WebToLead();
        $context = Container::getInstance()->get(Context::class);
        $context->activateSubject($subject);
        $context->setAttribute('campaign_id', $campaign_id);
		$campaign = BeanFactory::newBean('Campaigns');
        $camp_query  = 'SELECT name, id FROM campaigns WHERE id = ' . $campaign->db->quoted($campaign_id);
		$camp_query .= " and deleted=0";
        $camp_result=$campaign->db->query($camp_query);
        $camp_data = $campaign->db->fetchByAssoc($camp_result);
        // Bug 41292 - have to select marketing_id for new lead
        $db = DBManagerFactory::getInstance();
        $marketing = BeanFactory::newBean('EmailMarketing');
        $marketing_query = $marketing->create_new_list_query(
            'date_start DESC, date_modified DESC',
            'campaign_id = ' . $campaign->db->quoted($campaign_id) .' AND status = \'active\' AND date_start < '
                . $db->convert('', 'today'),
            array('id')
        );
        $marketing_result = $db->limitQuery($marketing_query, 0, 1, true);
        $marketing_data = $db->fetchByAssoc($marketing_result);
        // .Bug 41292
		if (isset($_REQUEST['assigned_user_id']) && !empty($_REQUEST['assigned_user_id'])) {
			$current_user = BeanFactory::getBean('Users', $_REQUEST['assigned_user_id']);
		} 

	    if(isset($camp_data) && $camp_data != null ){
			$leadForm = new LeadFormBase();
            $lead = BeanFactory::newBean('Leads');
			$prefix = '';
			if(!empty($_POST['prefix'])){
				$prefix = $_POST['prefix'];
			}

			if(empty($lead->id)) {
                $lead->id = create_guid();
                $lead->new_with_id = true;
            }
            $GLOBALS['check_notify'] = true;

            //bug: 47574 - make sure, that webtolead_email1 field has same required attribute as email1 field
            if(isset($lead->required_fields['email1'])){
                $lead->required_fields['webtolead_email1'] = $lead->required_fields['email1'];
            }
            
            //bug: 42398 - have to unset the id from the required_fields since it is not populated in the $_POST
            unset($lead->required_fields['id']);
            unset($lead->required_fields['team_name']);
            unset($lead->required_fields['team_count']);

            // Bug #52563 : Web to Lead form redirects to Sugar when duplicate detected
            // prevent duplicates check
            $_POST['dup_checked'] = true;

            // checkRequired needs a major overhaul before it works for web to lead forms.
            $lead = $leadForm->handleSave($prefix, false, false, false, $lead, false);
            
			if(!empty($lead)){
				
	            //create campaign log
	            $camplog = BeanFactory::newBean('CampaignLog');
	            $camplog->campaign_id  = $_POST['campaign_id'];
	            $camplog->related_id   = $lead->id;
	            $camplog->related_type = $lead->module_dir;
	            $camplog->activity_type = "lead";
	            $camplog->target_type = $lead->module_dir;
	            $camplog->activity_date=$timedate->now();
	            $camplog->target_id    = $lead->id;
                if(isset($marketing_data['id']))
                {
                    $camplog->marketing_id = $marketing_data['id'];
                }
	            $camplog->save();

		        //link campaignlog and lead

		        if (isset($_POST['email']) && $_POST['email'] != null)
                {
                    $lead->email1 = $_POST['email'];
		        } 
                //in case there are old forms used webtolead_email1
                elseif (isset($_POST['webtolead_email1']) && $_POST['webtolead_email1'] != null)
                {
                    $lead->email1 = $_POST['webtolead_email1'];
                }
                
		        if (isset($_POST['email2']) && $_POST['email2'] != null)
                {
                    $lead->email2 = $_POST['email2'];
		        } 
                //in case there are old forms used webtolead_email2
                elseif (isset($_POST['webtolead_email2']) && $_POST['webtolead_email2'] != null)
                {
                    $lead->email2 = $_POST['webtolead_email2'];
                }
                
		        $lead->load_relationship('campaigns');
		        $lead->campaigns->add($camplog->id);

            // Set the default treatment of email opt_out if no explicit actions are present in form data
            // The presence of the email_opt_in form variable supersedes any presence of the email_opt_out or
            // webtolead_email_opt_out form variables, which are supported primarily for legacy compatibility.
            $optOut = !empty($GLOBALS['sugar_config']['new_email_addresses_opted_out']);
            if (isset($_POST['email_opt_in'])) {
                $optIn = ($_POST['email_opt_in'] == 'on');
                $optOut = !$optIn;
            } elseif (isset($_POST['webtolead_email_opt_out']) || isset($_POST['email_opt_out'])) {
                $optOut = true;
            }

            if (isset($lead->email1) && !empty($lead->email1)) {
                _setDefaultEmailProperties($lead, 'email1', $optOut);
            }
            if (isset($lead->email2) && !empty($lead->email2)) {
                _setDefaultEmailProperties($lead, 'email2', $optOut);
            }

                if(!empty($GLOBALS['check_notify'])) {
                    $lead->save($GLOBALS['check_notify']);
                }
                else {
                    $lead->save(FALSE);
                }
            }

            $redirect_url = $request->getValidInputPost(
                'redirect_url',
                array(
                    'Assert\Url' => array(
                        'protocols' => array('http', 'https'),
                    ),
                ),
                ''
            );

            if ($redirect_url !== null) {
                $params = array();
                foreach ($_REQUEST as $param => $_) {
                    if (is_array($_)) {
                        $params[$param] = $request->getValidInputRequest($param, 'Assert\ArrayRecursive');
                    } else {
                        $params[$param] = $request->getValidInputRequest($param);
                    }
                }
                unset($params['redirect_url'], $params['submit']);
                if (empty($lead)) {
                    $params['error'] = 1;
                }

				// Check if the headers have been sent, or if the redirect url is greater than 2083 characters (IE max URL length)
				//   and use a javascript form submission if that is the case.
			    if(headers_sent() || strlen($redirect_url) > 2083){
    				echo '<html ' . get_language_header() . '><head><title>SugarCRM</title></head><body>';
    				echo '<form name="redirect" action="' . htmlspecialchars($redirect_url, ENT_COMPAT, 'UTF-8') . '" method="GET">';
    				foreach ($params as $param => $value) {
						echo '<input type="hidden" name="'
                            . htmlspecialchars($param, ENT_COMPAT, 'UTF-8') . '" value="'
                            . htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '">';
    				}
    				echo '</form><script language="javascript" type="text/javascript">document.redirect.submit();</script>';
    				echo '</body></html>';
    			}
				else{
                    if (count($params) > 0) {
                        $query_string = http_build_query($params);
                        $delimiter = strpos($redirect_url, '?') === false ? '?' : '&';
                        $redirect_url .= $delimiter . $query_string;
                    }
                    // deactivate the subject for audit
                    $context->deactivateSubject($subject);
    				header("Location: {$redirect_url}");
    				die();
			    }
			}
			else{
				echo $mod_strings['LBL_THANKS_FOR_SUBMITTING_LEAD'];
			}
            // deactivate the subject for audit
            $context->deactivateSubject($subject);
			sugar_cleanup();
			// die to keep code from running into redirect case below
			die();
	    }
	   else{
	  	  echo $mod_strings['LBL_SERVER_IS_CURRENTLY_UNAVAILABLE'];
	  }
    // deactivate the subject for audit
    $context->deactivateSubject($subject);
}

if (!empty($redirect)) {
    if(headers_sent()){
    	echo '<html ' . get_language_header() . '><head><title>SugarCRM</title></head><body>';
        echo '<form name="redirect" action="' . $redirect . '" method="GET">';
    	echo '</form><script language="javascript" type="text/javascript">document.redirect.submit();</script>';
    	echo '</body></html>';
    }
    else{
        header("Location: $redirect");
    	die();
    }
}

echo $mod_strings['LBL_SERVER_IS_CURRENTLY_UNAVAILABLE'];

/**
 * Set the Email properties on the supplied lead.
 * @param SugarBean $lead
 * @param string $emailField
 * @param bool $optOut
 */
function _setDefaultEmailProperties(SugarBean $lead, $emailField, $optOut = false)
{
    $invalidEmail = empty($ea) ? false : $ea['invalid_email'];
    $primary = empty($ea) ? true : $ea['primary_address'];

    if (empty($lead->email) || !is_array($lead->email)) {
        $lead->email = array();
    }
    $lead->email[] = array(
        'email_address' => $lead->$emailField,
        'primary_address' => $primary,
        'opt_out' => $optOut,
        'invalid_email' => $invalidEmail,
    );
}
?>
