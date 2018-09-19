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
/*********************************************************************************

 * Description:
 ********************************************************************************/
//find all mailboxes of type bounce.

/**
 * Retrieve the attached error report for a bounced email if it exists.
 *
 * @param Email $email
 * @return string
 */
function retrieveErrorReportAttachment($email)
{
    $contents = "";
    $db = DBManagerFactory::getInstance();
    $query = "SELECT description FROM notes WHERE file_mime_type = 'message/rfc822' AND email_type='Emails'
        AND email_id = " . $db->quoted($email->id) . " AND deleted=0";
    $rs = $GLOBALS['db']->query($query);
    while ($row = $GLOBALS['db']->fetchByAssoc($rs)) 
		$contents .= $row['description'];

    return $contents;
}

/**
 * Create a bounced log campaign entry
 *
 * @param array $row
 * @param Email $email
 * @param string $email_description
 * @return string
 */
function createBouncedCampaignLogEntry($row,$email, $email_description)
{
    $GLOBALS['log']->debug("Creating bounced email campaign log");
    $bounce = BeanFactory::newBean('CampaignLog');
    $bounce->campaign_id=$row['campaign_id'];
    $bounce->target_tracker_key=$row['target_tracker_key'];
    $bounce->target_id= $row['target_id'];
    $bounce->target_type=$row['target_type'];
    $bounce->list_id=$row['list_id'];
    $bounce->marketing_id=$row['marketing_id'];

    $bounce->activity_date=$email->date_created;
    $bounce->related_type='Emails';
    $bounce->related_id= $email->id;

    // Do we have the phrase permanent error|failure in the email body.
    if (preg_match('/permanent[ ]*(error|failure)/', $email_description))
    {
        $bounce->activity_type='invalid email';
        markEmailAddressInvalid($row['more_information']);
    }
    else 
        $bounce->activity_type='send error';
        
    $return_id=$bounce->save();
    return $return_id;
}

/**
 * Given an email address, mark it as invalid.
 *
 * @param $email_address
 */
function markEmailAddressInvalid($email_address)
{
    if(empty($email_address))
        return;
    $sea = BeanFactory::newBean('EmailAddresses');
    $rs = $sea->retrieve_by_string_fields( array('email_address_caps' => trim(strtoupper($email_address))) );
    if($rs != null)
    {
        $sea->AddUpdateEmailAddress($email_address, 1, null, $rs->id);
    }
}

/**
 * Get the existing campaign log entry by tracker key.
 * 
 * @param string Target Key
 * @return array Campaign Log Row
 */
function getExistingCampaignLogEntry($identifier)
{
    $row = FALSE;
    $targeted = BeanFactory::newBean('CampaignLog');
    $where="campaign_log.activity_type='targeted' and campaign_log.target_tracker_key='{$identifier}'";
    $query=$targeted->create_new_list_query('',$where);
    $result=$targeted->db->query($query);
    $row=$targeted->db->fetchByAssoc($result);
    
    return $row;
}

/**
 * Scan the bounced email searching for a valid target identifier.
 * 
 * @param string Email Description
 * @return array Results including matches and identifier
 */
function checkBouncedEmailForIdentifier($email_description)
{
    $matches = array();
    $identifiers = array();
    $found = FALSE;
    //Check if the identifier is present in the header.
    if(preg_match('/X-CampTrackID: [a-z0-9\-]*/',$email_description,$matches)) 
    {
        $identifiers = preg_split('/X-CampTrackID: /',$matches[0],-1,PREG_SPLIT_NO_EMPTY);
        $found = TRUE;
        $GLOBALS['log']->debug("Found campaign identifier in header of email");  
    }
    else if( preg_match('/index.php\?entryPoint=removeme&identifier=[a-z0-9\-]*/',$email_description, $matches) )
    {
        $identifiers = preg_split('/index.php\?entryPoint=removeme&identifier=/',$matches[0],-1,PREG_SPLIT_NO_EMPTY);
        $found = TRUE;
        $GLOBALS['log']->debug("Found campaign identifier in body of email");
    }
    
    return array('found' => $found, 'matches' => $matches, 'identifiers' => $identifiers);
}

function campaign_process_bounced_emails(&$email, &$email_header) 
{
	global $sugar_config;
	$emailFromAddress = $email_header->fromaddress;
	$email_description = $email->raw_source;
    	
	//if raw_source is empty, try using the description instead
    	if (empty($email_description)){
        	$email_description = $email->description;
	}

    $email_description .= retrieveErrorReportAttachment($email);

	if (preg_match('/MAILER-DAEMON|POSTMASTER/i',$emailFromAddress)) 
	{
	    $email_description=quoted_printable_decode($email_description);
		$matches=array();
		
		//do we have the identifier tag in the email?
		$identifierScanResults = checkBouncedEmailForIdentifier($email_description);
		
		if ( $identifierScanResults['found'] ) 
		{
			$matches = $identifierScanResults['matches'];
			$identifiers = $identifierScanResults['identifiers'];

			if (!empty($identifiers)) 
			{
				//array should have only one element in it.
				$identifier = trim($identifiers[0]);
				$row = getExistingCampaignLogEntry($identifier);
				
				//Found entry
				if (!empty($row)) 
				{
					//do not create another campaign_log record is we already have an
					//invalid email or send error entry for this tracker key.
					$query_log = "select * from campaign_log where target_tracker_key='{$row['target_tracker_key']}'"; 
					$query_log .=" and (activity_type='invalid email' or activity_type='send error')";
                    $targeted = BeanFactory::newBean('CampaignLog');
					$result_log=$targeted->db->query($query_log);
					$row_log=$targeted->db->fetchByAssoc($result_log);

					if (empty($row_log)) 
					{
						$return_id = createBouncedCampaignLogEntry($row, $email, $email_description);	
						return TRUE;
					}				
					else 
					{
					    $GLOBALS['log']->debug("Warning: campaign log entry already exists for identifier $identifier");
					    return FALSE;
					}
				} 
				else 
				{
				    $GLOBALS['log']->info("Warning: skipping bounced email with this tracker_key(identifier) in the message body: ".$identifier);
					return FALSE;
				}			
    		} 
    		else 
    		{
    			$GLOBALS['log']->info("Warning: Empty identifier for campaign log.");
    			return FALSE;
    		}
    	}  
    	else 
    	{
    	    $GLOBALS['log']->info("Warning: skipping bounced email because it does not have the removeme link.");	
    		return FALSE;	
      	}
  } 
  else 
  {
	$GLOBALS['log']->info("Warning: skipping bounced email because the sender is not MAILER-DAEMON.");
	return FALSE;
  }
}
?>
