<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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



// load the correct demo data and main application language file depending upon the installer language selected; if
// it's not found fall back on en_us

use Sugarcrm\Sugarcrm\Util\Files\FileLoader;

if(file_exists("include/language/{$current_language}.lang.php")){
    require_once FileLoader::validateFilePath("include/language/{$current_language}.lang.php");
}
else {
    require_once("include/language/en_us.lang.php");
}

require_once('install/UserDemoData.php');
require_once('install/TeamDemoData.php');

global $sugar_demodata;
if(file_exists("install/demoData.{$current_language}.php")){
    require_once FileLoader::validateFilePath("install/demoData.{$current_language}.php");
}
else {
   require_once("install/demoData.en_us.php");
}

$last_name_count = count($sugar_demodata['last_name_array']);
$first_name_count = count($sugar_demodata['first_name_array']);
$company_name_count = count($sugar_demodata['company_name_array']);
$street_address_count = count($sugar_demodata['street_address_array']);
$city_array_count = count($sugar_demodata['city_array']);
$tags_array_count = count($sugar_demodata['tags_array']);

//Turn disable_workflow to Yes so that we don't run workflow for any seed modules
$_SESSION['disable_workflow'] = "Yes";
global $app_list_strings;
global $sugar_config;
$_REQUEST['useEmailWidget'] = "true";
if(empty($app_list_strings)) {
	$app_list_strings = return_app_list_strings_language('en_us');
}
/*
 * Seed the random number generator with a fixed constant.  This will make all installs of the same code have the same
 * seed data.  This facilitates cross database testing..
 */
mt_srand(93285903);
$db = DBManagerFactory::getInstance();
$timedate = TimeDate::getInstance();
// Set the max time to one hour (helps Windows load the seed data)
ini_set("max_execution_time", "3600");

// ensure we have enough memory
$memory_needed  = 256;

$memory_limit   = ini_get('memory_limit');
if( $memory_limit != "" && $memory_limit != "-1" ){ // if memory_limit is set
    rtrim($memory_limit, 'M');
    $memory_limit_int = (int) $memory_limit;
    if( $memory_limit_int < $memory_needed ){
        ini_set("memory_limit", "$memory_needed" . "M");
    }
}

$large_scale_test = empty($sugar_config['large_scale_test']) ? false : $sugar_config['large_scale_test'];

$seed_user = new User();
$user_demo_data = new UserDemoData($seed_user, $large_scale_test);
installLog("DemoData: Creating Users");
$user_demo_data->create_demo_data();
installLog("DemoData: Done Creating Users");
$number_contacts = 200;
$number_companies = 50;
$number_leads = 200;
$number_cases = 5;

// If large scale test is set to true, increase the seed data.
if($large_scale_test) {
	// increase the cuttoff time to 1 hour
	ini_set("max_execution_time", "3600");
	$number_contacts = 100000;
	$number_companies = 15000;
	$number_leads = 100000;
}

installLog("DemoData: Teams");
$seed_team = new Team();
$team_demo_data = new TeamDemoData($seed_team, $large_scale_test);
$team_demo_data->create_demo_data();
installLog("DemoData: Done Teams");

$possible_duration_hours_arr = array( 0, 1, 2, 3);
$possible_duration_minutes_arr = array('00' => '00','15' => '15', '30' => '30', '45' => '45');
$account_ids = Array();
$accounts = Array();

// Determine the assigned user for all demo data.  This is the default user if set, or admin
$assigned_user_name = "admin";
if(!empty($sugar_config['default_user_name']) &&
	!empty($sugar_config['create_default_user']) &&
	$sugar_config['create_default_user'])
{
	$assigned_user_name = $sugar_config['default_user_name'];
}

// Look up the user id for the assigned user
$seed_user = new User();
$assigned_user_id = $seed_user->retrieve_user_id($assigned_user_name);
$patterns[] = '/ /';
$patterns[] = '/\./';
$patterns[] = '/&/';
$patterns[] = '/\//';
$replacements[] = '';
$replacements[] = '';
$replacements[] = '';
$replacements[] = '';

//create timeperiods - pro only


installLog("DemoData: Time Periods");
$timedate = TimeDate::getInstance();
$timeperiods = TimePeriodsSeedData::populateSeedData();
installLog("DemoData: Done Time Periods");

echo '.';

$tag_ids = createTags($sugar_demodata['tags_array']);

/**
 * Create demo data for tags
 * @param $tags_array has tag names
 * @return an array of tag ids
 */
function createTags($tags_array)
{
    $tag_ids = array();
    for ($i = 0, $j = count($tags_array); $i < $j; $i++) {
        $tagBean = BeanFactory::newBean('Tags');
        $tagBean->name = $tags_array[$i];
        $tagBean->save();
        $tag_ids[$tagBean->id] = $tagBean;
    }
    return $tag_ids;
}

/**
 * Gets random count for tags for each record
 * @return int
 */
function getTagsCountForEachRecord()
{
    return mt_rand(0, 6);
}

/**
 * Adds tags to a given bean
 * @param $bean Bean to which tag is to be added
 */
function addTagsToBean($bean)
{
    global $tag_ids;
    $tagCount = getTagsCountForEachRecord();
    if ($tagCount) {
        $bean->load_relationship('tag_link');
        if ($tagCount > 1) {
            $tagArray = array_rand($tag_ids, $tagCount);
        } else {
            // array_rand with a count of 1 returns just a single key, not an array of keys
            $tagArray = array(array_rand($tag_ids, $tagCount));
        }

        foreach ($tagArray as $tag_id) {
            $bean->tag_link->add($tag_ids[$tag_id]);
        }
    }
}

///////////////////////////////////////////////////////////////////////////////
if (file_exists("install/demoData.{$current_language}.php")) {
    $preferred_language = $current_language;
} else {
    $preferred_language = "en_us";
}
$titles = $sugar_demodata['titles'];
$first_name_max = $first_name_count - 1;
$last_name_max = $last_name_count - 1;
$street_address_max = $street_address_count - 1;
$city_array_max = $city_array_count - 1;
$lead_source_max = count($app_list_strings['lead_source_dom']) - 1;
$lead_status_max = count($app_list_strings['lead_status_dom']) - 1;
$title_max = count($titles) - 1;
$contacts = array();
////	ACCOUNTS

// Make a copy of the company name list we can destroy in
// the name of de-duplication during account population.
$accounts_companies_list = $sugar_demodata['company_name_array'];

installLog("DemoData: Companies + Related Calls, Notes Meetings and Bugs");
for($i = 0; $i < $number_companies; $i++) {

    if (count($accounts_companies_list) > 0) {
        // De-populate a copy of the company name list
        // as each name is used to prevent duplication.
        $account_num = array_rand($accounts_companies_list);
        $account_name = $accounts_companies_list[$account_num];
	    unset($accounts_companies_list[$account_num], $account_num);
    } else {
        // We've run out of preset company names so start generating new ones.
        $account_name =
            $sugar_demodata['first_name_array'][array_rand($sugar_demodata['first_name_array'])] . ' ' .
            $sugar_demodata['last_name_array'][array_rand($sugar_demodata['last_name_array'])] . ' ' .
            $sugar_demodata['company_name_suffix_array'][array_rand($sugar_demodata['company_name_suffix_array'])];
    }

	// Create new accounts.
	$account = new Account();
	$account->name = $account_name;
	$account->phone_office = create_phone_number();
	$account->assigned_user_id = $assigned_user_id;
	$account->emailAddress->addAddress(createEmailAddress(), true);
	$account->emailAddress->addAddress(createEmailAddress());
	$account->website = createWebAddress();
    $account->billing_address_street = $sugar_demodata['street_address_array'][mt_rand(0, $street_address_count-1)];
    $account->billing_address_city = $sugar_demodata['city_array'][mt_rand(0, $city_array_count-1)];

	if($i % 3 == 1)	{
		$account->billing_address_state = "NY";
		$assigned_user_id = mt_rand(9,11);
		if($assigned_user_id == 9) {
			$account->assigned_user_name = "seed_will";
		} else if($assigned_user_id == 10) {
			$account->assigned_user_name = "seed_chris";
		} else {
            $account->assigned_user_name = "seed_jim";
        }

		$account->assigned_user_id = $account->assigned_user_name."_id";
	} else {
		$account->billing_address_state = "CA";
		$assigned_user_id = mt_rand(6,8);
		if($assigned_user_id == 6) {
			$account->assigned_user_name = "seed_sarah";
		} elseif($assigned_user_id == 7) {
			$account->assigned_user_name = "seed_sally";
		} else {
			$account->assigned_user_name = "seed_max";
		}

		$account->assigned_user_id = $account->assigned_user_name."_id";
	}

	// If this is a large scale test, switch to the bulk teams 90% of the time.
	if ($large_scale_test) {
		$assigned_team = $team_demo_data->get_random_team();
		$account->assigned_user_id = $account->team_id;
		$account->assigned_user_name = $assigned_team;
		if(mt_rand(0,100) < 90) {
		  $account->team_id = $assigned_team;
		  $account->team_set_id = $account->team_id;
		} else {
		  $account->team_id = $assigned_team;
		  $teams = $team_demo_data->get_random_teamset();
		  $account->load_relationship('teams');
		  $account->teams->add($teams);
		}
	} else if(mt_rand(0,100) < 50) {
		$account->team_id = $account->billing_address_state == "CA" ? "West" : "East";
		$teams = $team_demo_data->get_random_teamset();
		$account->load_relationship('teams');
		$account->teams->add($teams);
	} else {
		$account->team_id = $account->billing_address_state == "CA" ? "West" : "East";
		$account->team_set_id = $account->team_id;
	}
	$account->billing_address_postalcode = mt_rand(10000, 99999);
	$account->billing_address_country = 'USA';
	$account->shipping_address_street = $account->billing_address_street;
	$account->shipping_address_city = $account->billing_address_city;
	$account->shipping_address_state = $account->billing_address_state;
	$account->shipping_address_postalcode = $account->billing_address_postalcode;
	$account->shipping_address_country = $account->billing_address_country;
    $account->industry = array_rand($app_list_strings['industry_dom']);
    $account->account_type = "Customer";

    $account->save();
    addTagsToBean($account);

	$account_ids[] = $account->id;
	$accounts[] = $account;

    $contact = new Contact();
    $contact->first_name = $sugar_demodata['first_name_array'][mt_rand(0, $first_name_max)];
    $contact->last_name = $sugar_demodata['last_name_array'][mt_rand(0, $last_name_max)];
    $contact->assigned_user_id = $account->assigned_user_id;
    $contact->primary_address_street = $sugar_demodata['street_address_array'][mt_rand(0, $street_address_max)];
    $contact->primary_address_city = $sugar_demodata['city_array'][mt_rand(0, $city_array_max)];
    $contact->lead_source = array_rand($app_list_strings['lead_source_dom']);
    $contact->title = $titles[mt_rand(0, $title_max)];
    $contact->emailAddress->addAddress(createEmailAddress(), true, true);
    $contact->emailAddress->addAddress(createEmailAddress(), false, false, false, true);
    $contact->portal_active = 1;
    $contact->portal_name = $contact->first_name . $contact->last_name . $i;
    $contact->portal_password = User::getPasswordHash($contact->first_name . $contact->last_name . $i);
    $contact->preferred_language = $preferred_language;
    $contact->phone_work = create_phone_number();
    $contact->phone_home = create_phone_number();
    $contact->phone_mobile = create_phone_number();
    // Fill in a bogus address
    $contact->primary_address_state = $account->billing_address_state;
    $contact->team_id = $account->team_id;
    $contact->team_set_id = $account->team_set_id;
    $contact->assigned_user_name = $account->assigned_user_name;
    $contact->primary_address_postalcode = mt_rand(10000, 99999);
    $contact->primary_address_country = 'USA';
    $contact->save();
    $contacts[] = $contact->id;

    for($c = 0; $c < $number_cases; $c++) {
        // Create a case for the account
        $case = new aCase();
        $case->account_id = $account->id;
        $case->priority = array_rand($app_list_strings['case_priority_dom']);
        $case->status = array_rand($app_list_strings['case_status_dom']);
        $case->type = array_rand($app_list_strings['case_type_dom']);
        $case->name = $sugar_demodata['case_seed_names'][mt_rand(0, 4)];
        $case->assigned_user_id = $account->assigned_user_id;
        $case->assigned_user_name = $account->assigned_user_name;
        $case->team_id = $account->team_id;
        $case->team_set_id = $account->team_set_id;
        $case->portal_viewable = 1;
        $case->save();
        $case->load_relationship('tag_link');
        addTagsToBean($case);
    }

	// Create a bug for the account
	$bug = new Bug();
	$bug->account_id = $account->id;
	$bug->priority = array_rand($app_list_strings['bug_priority_dom']);
	$bug->status = array_rand($app_list_strings['bug_status_dom']);
	$bug->type = array_rand($app_list_strings['bug_type_dom']);
	$bug->name = $sugar_demodata['bug_seed_names'][mt_rand(0,4)];
	$bug->assigned_user_id = $account->assigned_user_id;
	$bug->assigned_user_name = $account->assigned_user_name;
	$bug->team_id = $account->team_id;
	$bug->team_set_id = $account->team_set_id;
    $bug->portal_viewable = 1;
	$bug->save();
    addTagsToBean($bug);

	$note = new Note();
	$note->parent_type = 'Accounts';
	$note->parent_id = $account->id;
	$seed_data_index = mt_rand(0,3);
	$note->name = $sugar_demodata['note_seed_names_and_Descriptions'][$seed_data_index][0];
	$note->description = $sugar_demodata['note_seed_names_and_Descriptions'][$seed_data_index][1];
	$note->assigned_user_id = $account->assigned_user_id;
	$note->assigned_user_name = $account->assigned_user_name;
	$note->team_id = $account->team_id;
	$note->team_set_id = $account->team_set_id;
    $note->save();
    addTagsToBean($note);

	$call = new Call();
    $call->set_created_by = false;
	$call->parent_type = 'Accounts';
	$call->parent_id = $account->id;
	$call->name = $sugar_demodata['call_seed_data_names'][mt_rand(0,3)];
	$call->assigned_user_id = $account->assigned_user_id;
	$call->assigned_user_name = $account->assigned_user_name;
    $call->created_by = $call->assigned_user_id;
    $call->created_by_name = $call->assigned_user_name;
	$call->direction='Outbound';
	$call->date_start = create_date(). ' ' . create_time();
	$call->duration_hours='0';
	$call->duration_minutes='30';
	$call->account_id =$account->id;
    $call->status = array_rand($app_list_strings['call_status_dom']);
	$call->team_id = $account->team_id;
	$call->team_set_id = $account->team_set_id;
    $call->contacts_arr[0] = $contact->id;
	$call->save();
    addTagsToBean($call);
    $call->setContactInvitees($call->contacts_arr);

    //Set the user to accept the call
    $seed_user->id = $call->assigned_user_id;
    $call->set_accept_status($seed_user,'accept');

    if ($i % 10 === 0) {
        echo '.';
    }
}
installLog("DemoData: Done Companies + Related Calls, Notes Meetings and Bugs");

unset($accounts_companies_list);

$account_max = count($account_ids) - 1;

///////////////////////////////////////////////////////////////////////////////
////	DEMO CONTACTS
installLog("DemoData: Contacts");

for($i=0; $i<$number_contacts; $i++) {
	$contact = new Contact();
	$contact->first_name = $sugar_demodata['first_name_array'][mt_rand(0,$first_name_max)];
	$contact->last_name = $sugar_demodata['last_name_array'][mt_rand(0,$last_name_max)];
	$contact->assigned_user_id = $account->assigned_user_id;
    $contact->primary_address_street = $sugar_demodata['street_address_array'][mt_rand(0, $street_address_max)];
    $contact->primary_address_city = $sugar_demodata['city_array'][mt_rand(0, $city_array_max)];
    $contact->lead_source = array_rand($app_list_strings['lead_source_dom']);
	$contact->title = $titles[mt_rand(0,$title_max)];
	$contact->emailAddress->addAddress(createEmailAddress(), true, true);
	$contact->emailAddress->addAddress(createEmailAddress(), false, false, false, true);
	$assignedUser = new User();
	$assignedUser->retrieve($contact->assigned_user_id);
    $contact->portal_active = 1;
    $contact->portal_name = $contact->first_name.$contact->last_name.$i;
    $contact->portal_password = User::getPasswordHash($contact->first_name.$contact->last_name.$i);
    $contact->preferred_language = $preferred_language;
	$contact->phone_work = create_phone_number();
	$contact->phone_home = create_phone_number();
	$contact->phone_mobile = create_phone_number();
	$account_number = mt_rand(0,$account_max);
	$account_id = $account_ids[$account_number];
	// Fill in a bogus address
	$contacts_account = $accounts[$account_number];
	$contact->primary_address_state = $contacts_account->billing_address_state;
	$contact->team_id = $contacts_account->team_id;
	$contact->team_set_id = $contacts_account->team_set_id;
	$contact->assigned_user_id = $contacts_account->assigned_user_id;
	$contact->assigned_user_name = $contacts_account->assigned_user_name;
	$contact->primary_address_postalcode = mt_rand(10000,99999);
	$contact->primary_address_country = 'USA';

	$contact->save();
    addTagsToBean($contact);

    $contacts[] = $contact->id;

    // Create a linking table entry to assign an account to the contact.
	$contact->set_relationship('accounts_contacts', array('contact_id'=>$contact->id ,'account_id'=> $account_id, 'primary_account' => 1), false);

	//Create new tasks
	$task = new Task();
	$key = array_rand($sugar_demodata['task_seed_data_names']);
	$task->name = $sugar_demodata['task_seed_data_names'][$key];
	//separate date and time field have been merged into one.
	$task->date_due = create_date() . ' ' . create_time();
	$task->date_due_flag = 0;
	$task->team_id = $contacts_account->team_id;
	$task->team_set_id = $contacts_account->team_set_id;
	$task->assigned_user_id = $contacts_account->assigned_user_id;
	$task->assigned_user_name = $contacts_account->assigned_user_name;
	$task->priority = array_rand($app_list_strings['task_priority_dom']);
	$task->status = array_rand($app_list_strings['task_status_dom']);
	$task->contact_id = $contact->id;
	if ($contact->primary_address_city == "San Mateo") {
		$task->parent_id = $account_id;
		$task->parent_type = 'Accounts';
	}

	$task->save();
    addTagsToBean($task);

	//Create new meetings
	$meeting = new Meeting();
    $meeting->set_created_by = false;
	$key = array_rand($sugar_demodata['meeting_seed_data_names']);
	$meeting->name = $sugar_demodata['meeting_seed_data_names'][$key];
	$meeting->date_start = create_date(). ' ' . create_time();
    $meeting->duration_hours = array_rand($possible_duration_hours_arr);
	$meeting->duration_minutes = array_rand($possible_duration_minutes_arr);
	$meeting->assigned_user_id = $assigned_user_id;
	$meeting->team_id = $contacts_account->team_id;
	$meeting->team_set_id = $contacts_account->team_set_id;
	$meeting->assigned_user_id = $contacts_account->assigned_user_id;
	$meeting->assigned_user_name = $contacts_account->assigned_user_name;
    $meeting->created_by = $meeting->assigned_user_id;
    $meeting->created_by_name = $meeting->assigned_user_name;
	$meeting->description = $sugar_demodata['meeting_seed_data_descriptions'];
	$meeting->status = array_rand($app_list_strings['meeting_status_dom']);
	$meeting->contact_id = $contact->id;
	$meeting->parent_id = $account_id;
	$meeting->parent_type = 'Accounts';
    // dont update vcal
    $meeting->update_vcal  = false;
    $meeting->contacts_arr[0] = $contact->id;
	$meeting->save();
    addTagsToBean($meeting);
    $meeting->setContactInvitees($meeting->contacts_arr);
	// leverage the seed user to set the acceptance status on the meeting.
	$seed_user->id = $meeting->assigned_user_id;
    $meeting->set_accept_status($seed_user,'accept');

	//Create new emails
	$email = new Email();
    $email->id = \Sugarcrm\Sugarcrm\Util\Uuid::uuid1();
    $email->new_with_id = true;
    BeanFactory::registerBean($email);

	$key = array_rand($sugar_demodata['email_seed_data_subjects']);
	$email->name = $sugar_demodata['email_seed_data_subjects'][$key];
	$email->date_start = create_date();
	$email->time_start = create_time();
    $email->date_sent = create_past_date() . ' ' . create_time();
	$email->duration_hours = array_rand($possible_duration_hours_arr);
	$email->duration_minutes = array_rand($possible_duration_minutes_arr);
	$email->assigned_user_id = $assigned_user_id;
	$email->team_id = $contacts_account->team_id;
	$email->team_set_id = $contacts_account->team_set_id;
	$email->assigned_user_id = $contacts_account->assigned_user_id;
	$email->assigned_user_name = $contacts_account->assigned_user_name;
	$email->description = $sugar_demodata['email_seed_data_descriptions'];
    $email->description_html = $sugar_demodata['email_seed_data_description_html'];
	$email->status = 'sent';
    $email->state = $i % 2 === 0 ? 'Draft' : 'Archived';
	$email->parent_id = $account_id;
	$email->parent_type = 'Accounts';
    if (key($sugar_demodata['email_seed_data_types']) === null) {
        reset($sugar_demodata['email_seed_data_types']);
    }
    $email->type = current($sugar_demodata['email_seed_data_types']);
    next($sugar_demodata['email_seed_data_types']);

	$email->load_relationship('contacts');
	$email->contacts->add($contact);
	$email->load_relationship('accounts');
	$email->accounts->add($contacts_account);

    if ($email->state === 'Archived') {
        $from = BeanFactory::newBean('EmailParticipants');
        $from->new_with_id = true;
        $from->id = \Sugarcrm\Sugarcrm\Util\Uuid::uuid1();
        BeanFactory::registerBean($from);
        $from->parent_type = 'Users';
        $from->parent_id = $contacts_account->assigned_user_id;
        $email->load_relationship('from');
        $email->from->add($from);
    }

    $to = BeanFactory::newBean('EmailParticipants');
    $to->new_with_id = true;
    $to->id = \Sugarcrm\Sugarcrm\Util\Uuid::uuid1();
    BeanFactory::registerBean($to);
    $to->parent_type = $contact->getModuleName();
    $to->parent_id = $contact->id;
    $email->load_relationship('to');
    $email->to->add($to);

    // Add $email to the resave queue to force it to be saved now that all of the relationships have been saved. The
    // email won't be added to the queue twice. This guarantees that the email will only be saved once.
    SugarRelationship::addToResaveList($email);

    // The relationship between Emails and EmailParticipants requires that we resave the beans. Just in case something
    // prevented them from being resaved already, try it one last time.
    SugarRelationship::resaveRelatedBeans();

    if ($i % 10 === 0) {
        echo '.';
    }
}
installLog("DemoData: Done Contacts");
installLog("DemoData: Leads");

for($i=0; $i<$number_leads; $i++)
{
	$lead = new Lead();
	$lead->account_name = $sugar_demodata['company_name_array'][mt_rand(0,$company_name_count-1)];
	$lead->first_name = $sugar_demodata['first_name_array'][mt_rand(0,$first_name_max)];
	$lead->last_name = $sugar_demodata['last_name_array'][mt_rand(0,$last_name_max)];
    $lead->primary_address_street = $sugar_demodata['street_address_array'][mt_rand(0, $street_address_max)];
    $lead->primary_address_city = $sugar_demodata['city_array'][mt_rand(0, $city_array_max)];
	$lead->lead_source = array_rand($app_list_strings['lead_source_dom']);
	$lead->title = $sugar_demodata['titles'][mt_rand(0,$title_max)];
	$lead->phone_work = create_phone_number();
	$lead->phone_home = create_phone_number();
	$lead->phone_mobile = create_phone_number();
	$lead->emailAddress->addAddress(createEmailAddress(), true);
	// Fill in a bogus address
	$lead->primary_address_state = $sugar_demodata['primary_address_state'];
	$leads_account = $accounts[$account_number];
	$lead->primary_address_state = $leads_account->billing_address_state;
	$lead->status = array_rand($app_list_strings['lead_status_dom']);
	if ($lead->status === 'Converted') {
		$lead->converted = true;
	}
	$lead->lead_source = array_rand($app_list_strings['lead_source_dom']);
	if($i % 3 == 1)
	{
		$lead->billing_address_state = $sugar_demodata['billing_address_state']['east'];
			$assigned_user_id = mt_rand(9,10);
			if($assigned_user_id == 9)
			{
				$lead->assigned_user_name = "seed_will";
				$lead->assigned_user_id = $lead->assigned_user_name."_id";
			}
			else
			{
				$lead->assigned_user_name = "seed_chris";
				$lead->assigned_user_id = $lead->assigned_user_name."_id";
			}

			$lead->assigned_user_id = $lead->assigned_user_name."_id";
		}
		else
		{
			$lead->billing_address_state = $sugar_demodata['billing_address_state']['west'];
			$assigned_user_id = mt_rand(6,8);
			if($assigned_user_id == 6)
			{
				$lead->assigned_user_name = "seed_sarah";
			}
			else if($assigned_user_id == 7)
			{
				$lead->assigned_user_name = "seed_sally";
			}
			else
			{
				$lead->assigned_user_name = "seed_max";
			}

			$lead->assigned_user_id = $lead->assigned_user_name."_id";
		}


	// If this is a large scale test, switch to the bulk teams 90% of the time.
	if ($large_scale_test)
	{
		if(mt_rand(0,100) < 90) {
			$assigned_team = $team_demo_data->get_random_team();
			$lead->team_id = $assigned_team;
			$lead->assigned_user_id = $account->assigned_user_id;
			$lead->assigned_user_name = $assigned_team;
		}
        else {

			$teams = $team_demo_data->get_random_teamset();
			$lead->load_relationship('teams');
			$lead->teams->add($teams);
			$lead->assigned_user_id = $account->assigned_user_id;
		}
	}
    else {
    	if(mt_rand(0,100) < 50) {
    		$lead->team_id = $lead->billing_address_state == "CA" ? "West" : "East";
			$teams = $team_demo_data->get_random_teamset();
			$lead->load_relationship('teams');
			$lead->teams->add($teams);
    	} else {
			$lead->team_id = $lead->billing_address_state == "CA" ? "West" : "East";
			$lead->team_set_id = $lead->team_id;
    	}
	}
	$lead->primary_address_postalcode = mt_rand(10000,99999);
	$lead->primary_address_country = $sugar_demodata['primary_address_country'];
	$lead->save();
    $leads[] = $lead->id;

    if ($i % 10 === 0) {
        echo '.';
    }
}
installLog("DemoData: Done Leads");


installLog("DemoData: Products Metadata");
foreach($sugar_demodata['manufacturer_seed_data_names'] as $v){
	$manufacturer = new Manufacturer;
	$manufacturer->name = $v;
	$manufacturer->status = "Active";
	$manufacturer->list_order = "1";
	$manufacturer->save();
	$manufacturer_id_arr[] = $manufacturer->id;
}

echo '.';

$list_order = 1;
foreach($sugar_demodata['shipper_seed_data_names'] as $v){
	$shipper = new Shipper;
	$shipper->name = $v;
	$shipper->status = "Active";
	$shipper->list_order = $list_order;
	$list_order++;
	$shipper->save();
	$ship_id_arr[] = $shipper->id;
}

echo '.';

foreach($sugar_demodata['productcategory_seed_data_names'] as $v){
	$category = new ProductCategory;
	$category->name = $v;
	$category->list_order = "1";
    $key = array_rand($sugar_demodata['users']);
    $category->assigned_user_id = $sugar_demodata['users'][$key]['id'];
	$category->save();
	$productcategory_id_arr[] = $category->id;
}

echo '.';

$list_order = 1;
foreach($sugar_demodata['producttype_seed_data_names'] as $v){
	$type = new ProductType;
	$type->name = $v;
	$type->list_order = $list_order;
	$type->save();
	$producttype_id_arr[] = $type->id;
	$list_order++;
}

echo '.';


foreach($sugar_demodata['taxrate_seed_data'] as $v){
	$taxrate = new TaxRate;
	$taxrate->name = $v['name'];
	$taxrate->value = $v['value'];
	$taxrate->status = "Active";
	$taxrate->list_order = "1";
	$taxrate->disable_num_format = TRUE;
	$taxrate->save();
	$taxrate_id_arr[] = $taxrate->id;
};

echo '.';


foreach($sugar_demodata['currency_seed_data'] as $v){
	if ( $v['name'] == $_SESSION["default_currency_name"] )
		continue;
	$currency = new Currency;
	$currency->name = $v['name'];
	$currency->status = "Active";
	$currency->conversion_rate = $v['conversion_rate'];
	$currency->iso4217 = $v['iso4217'];
	$currency->symbol = $v['symbol'];
	$currency->save();
}

echo '.';
$dollar_id = '-99';
foreach($sugar_demodata['producttemplate_seed_data'] as $v){
	$manufacturer_id_max = count($manufacturer_id_arr) - 1;
	$productcategory_id_max = count($productcategory_id_arr) - 1;
	$producttype_id_max = count($producttype_id_arr) - 1;
	$template = new ProductTemplate;
	$template->manufacturer_id = $manufacturer_id_arr[mt_rand(0,$manufacturer_id_max)];
	$template->category_id = $productcategory_id_arr[mt_rand(0,$manufacturer_id_max)];
	$template->type_id = $producttype_id_arr[mt_rand(0,$manufacturer_id_max)];
	$template->currency_id = $dollar_id;
	$template->name = $v['name'];
	$template->tax_class = $v['tax_class'];
	$template->cost_price = $v['cost_price'];
	$template->cost_usdollar = $v['cost_usdollar'];
	$template->list_price = $v['list_price'];
	$template->list_usdollar = $v['list_usdollar'];
	$template->discount_price = $v['discount_price'];
	$template->discount_usdollar = $v['discount_usdollar'];
	$template->pricing_formula = $v['pricing_formula'];
	$template->mft_part_num = $v['mft_part_num'];
	$template->pricing_factor = $v['pricing_factor'];
	$template->status = $v['status'];
	$template->weight = $v['weight'];
	$template->date_available = $v['date_available'];
	$template->qty_in_stock = $v['qty_in_stock'];
	$template->save();
}
installLog("DemoData: Done Products Metadata");
echo '.';

installLog("DemoData: Contracts");
include_once('modules/TeamNotices/DefaultNotices.php');
///
/// SEED DATA FOR CONTRACTS
///
include_once('modules/Contracts/Contract.php');
foreach($sugar_demodata['contract_seed_data'] as $v){
	$contract = new Contract();
    $contract->currency_id = '-99';
	$contract->name = $v['name'];
	$contract->reference_code = $v['reference_code'];
	$contract->status = 'signed';
	$contract->account_id = $account->id;
	$contract->total_contract_value = $v['total_contract_value'];
	$contract->team_id = 1;
	$contract->assigned_user_id = 'seed_will_id';
	$contract->start_date = $v['start_date'];
	$contract->end_date = $v['end_date'];
	$contract->company_signed_date = $v['company_signed_date'];
	$contract->customer_signed_date = $v['customer_signed_date'];
	$contract->description = $v['description'];
	$contract->save();
}
installLog("DemoData: Done Contracts");

echo '.';

///
/// SEED DATA FOR KNOWLEDGE BASE
///
installLog("DemoData: KB");
$categoryIds = array();
foreach ($sugar_demodata['kbcategories_array'] as $name => $v) {
    $kbCategory = BeanFactory::newBean('Categories');
    $kbCategory->name = $name;

    $KBContent = BeanFactory::newBean('KBContents');
    $rootCategory = BeanFactory::getBean(
        'Categories',
        $KBContent->getCategoryRoot(),
        array('use_cache' => false)
    );
    $rootCategory->append($kbCategory);
    $idCategory = $kbCategory->save();
    array_push($categoryIds, $idCategory);

    if (count($v) > 0) {
        foreach ($v as $subname) {
            $kbSubCategory = BeanFactory::newBean('Categories');
            $kbSubCategory->name = $subname;

            $KBSubContent = BeanFactory::newBean('KBContents');
            $rootSubCategory = BeanFactory::getBean(
                'Categories',
                $idCategory,
                array('use_cache' => false)
            );
            $rootSubCategory->append($kbSubCategory);
            $idSubCategory = $kbSubCategory->save();
            array_push($categoryIds, $idSubCategory);
        }
    }
}

$system_config = new Administration();
$system_config->saveSetting('KBContents', 'languages', $sugar_demodata['kbdocuments_languages'], 'base');

echo '.';

foreach($sugar_demodata['kbdocuments_seed_data'] as $v){
    $kbdocContent = new KBContent();
    $kbdocContent->team_id = 1;
    $kbdocContent->team_set_id = 1;
    $kbdocContent->assigned_user_id = 'seed_will_id';
    $kbdocContent->assigned_user_name = "seed_will";
    $kbdocContent->name = $v['name'];
    $kbdocContent->kbdocument_body = $v['body'];
    $kbdocContent->tag = $v['tag'];
    $kbdocContent->status = $sugar_demodata['kbdocuments_statuses'][array_rand($sugar_demodata['kbdocuments_statuses'])];
    $kbdocContent->active_date = isset($v['active_date']) ? $v['active_date'] : null;
    $kbdocContent->exp_date = isset($v['exp_date']) ? $v['exp_date'] : null;
    $kbdocContent->useful = isset($v['useful']) ? $v['useful'] : 0;
    $kbdocContent->notuseful = isset($v['notuseful']) ? $v['notuseful'] : 0;
    $kbdocContent->category_id = $categoryIds[array_rand($categoryIds)];
    $idDocument = $kbdocContent->save();
    if (isset($v['localizations'])) {
        foreach($v['localizations'] as $localization) {
            $KBLocalization = clone(BeanFactory::retrieveBean('KBContents', $idDocument));
            unset($KBLocalization->id);
            unset($KBLocalization->kbarticle_id);
            $KBLocalization->language = $localization['language'];
            $KBLocalization->name = $localization['name'];
            $KBLocalization->kbdocument_name = $localization['name'];
            $KBLocalization->kbdocument_body = $localization['body'];
            $KBLocalization->save();
        }
    }
    if (isset($v['revisions'])) {
        foreach($v['revisions'] as $revision) {
            $KBRevision = clone(BeanFactory::retrieveBean('KBContents', $idDocument));
            unset($KBRevision->id);
            unset($KBRevision->revision);
            $KBRevision->name = $revision['name'];
            $KBRevision->save();
        }
    }
}

installLog("DemoData: Done KB");

echo '.';

installLog("DemoData: Email Templates");
///
/// SEED DATA FOR EMAIL TEMPLATES
///
if(!empty($sugar_demodata['emailtemplates_seed_data'])) {
	foreach($sugar_demodata['emailtemplates_seed_data'] as $v){
	    $EmailTemp = new EmailTemplate();
	    $EmailTemp->name = $v['name'];
	    $EmailTemp->description = $v['description'];
	    $EmailTemp->subject = $v['subject'];
	    $EmailTemp->body = $v['text_body'];
	    $EmailTemp->body_html = $v['body'];
	    $EmailTemp->deleted = 0;
	    $EmailTemp->team_id = 1;
	    $EmailTemp->published = 'off';
	    $EmailTemp->text_only = 0;
	    $id =$EmailTemp->save();
	}
}

installLog("DemoData: Done Email Templates");

echo '.';

//enable portal
$system_config = new Administration();
$system_config->retrieveSettings();
$GLOBALS['system_config'] = $system_config;
$installerStrings = $GLOBALS['mod_strings'];
$GLOBALS['mod_strings'] = return_module_language($GLOBALS['current_language'], 'ModuleBuilder');
include('modules/ModuleBuilder/parsers/parser.portalconfig.php');
$portalConfig = new ParserModifyPortalConfig();
$_REQUEST['appStatus'] = 'true';
$_REQUEST['maxQueryResult'] = '20';
$portalConfig->handleSave();
$GLOBALS['mod_strings']  = $installerStrings;

    installLog("DemoData: Products");
    include('install/seed_data/products_SeedData.php');
    installLog("DemoData: Quotes");
    include('install/seed_data/quotes_SeedData.php');

    installLog("DemoData: Opportunities");
    $opportunity_ids = OpportunitiesSeedData::populateSeedData($number_companies*3, $app_list_strings, $accounts, $sugar_demodata['users']);

    foreach($contacts as $id)
    {
        $contact->retrieve($id);
        // This assumes that there will be one opportunity per company in the seed data.
        $opportunity_key = array_rand($opportunity_ids);
        $contact->set_relationship('opportunities_contacts', array('contact_id'=>$contact->id ,'opportunity_id'=> $opportunity_ids[$opportunity_key], 'contact_role'=>$app_list_strings['opportunity_relationship_type_default_key']), false);
    }

    installLog("DemoData: Done Opportunities");

    echo '.';

    installLog("DemoData: Forecasts");
    ForecastsSeedData::populateSeedData($timeperiods);

    installLog("DemoData: Done Forecasts");

    echo '.';

installLog("DemoData: Data Privacy");

$userSally = BeanFactory::getBean('Users', 'seed_sally_id');

foreach ($sugar_demodata['dataprivacy_seed_data'] as $i => $seedDp) {
    $dp = BeanFactory::newBean('DataPrivacy');
    $status = 'Open';
    foreach ($seedDp as $field => $value) {
        if ($field == 'status') {
            $status = $value;
            $dp->status = 'Open'; // can not create a non Open record
            continue;
        }
        $dp->$field = $value;
    }
    $randomUser = $i % count($sugar_demodata['users']);
    $randomUser = $sugar_demodata['users'][$randomUser]['id'];
    $dp->assigned_user_id = $randomUser;
    $dp->set_created_by = false;
    $dp->created_by = $randomUser;
    $dp->update_modified_by = false;
    $dp->modified_user_id = 'seed_sally_id'; // always sally
    $dp->date_opened = $timedate->nowDbDate();
    if ($dp->status && ($dp->status == 'Closed' || $dp->status == 'Rejected')) {
        $dp->date_closed = $timedate->nowDbDate();
    }
    $dp->save();
    if ($status != 'Open') {
        $dp->retrieve();
        $dp->status = $status; // real status we want
        $dp->save();
    }

    // relate to either contact or lead
    switch ($i % 2) {
        case 0:
            $dp->set_relationship(
                'contacts_dataprivacy',
                array('dataprivacy_id' => $dp->id, 'contact_id' => $contacts[$i]),
                false
            );
            break;
        case 1:
            $dp->set_relationship(
                'leads_dataprivacy',
                array('dataprivacy_id' => $dp->id, 'lead_id' => $leads[$i]),
                false
            );
            break;
    }

    if ($i % 10 === 0) {
        echo '.';
    }
}
installLog("DemoData: Done Data Privacy");

    installLog("DemoData: Ent Reports");
    include('install/seed_data/entreport_SeedData.php');
    installLog("DemoData: Done Ent Reports");

//This is set to yes at the begininning of this file
unset($_SESSION['disable_workflow']);
