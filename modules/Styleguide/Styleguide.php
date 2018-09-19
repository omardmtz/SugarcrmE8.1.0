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


class Styleguide extends Person
{
    public $table_name = 'styleguide';
    public $module_name = 'Styleguide';
    public $module_dir = 'Styleguide';
    public $object_name = 'Styleguide';
    var $id;
    var $name;
    var $date_entered;
    var $date_modified;
    var $modified_user_id;
    var $modified_by_name;
    var $created_by;
    var $created_by_name;
    var $description;
    var $deleted;
    var $created_by_link;
    var $modified_user_link;
    var $activities;
    var $team_id;
    var $team_set_id;
    var $team_count;
    var $team_name;
    var $team_link;
    var $team_count_link;
    var $teams;
    var $assigned_user_id;
    var $assigned_user_name;
    var $assigned_user_link;
    var $salutation;
    var $first_name;
    var $last_name;
    var $full_name;
    var $title;
    var $facebook;
    var $twitter;
    var $googleplus;
    var $department;
    var $do_not_call;
    var $phone_home;
    var $email;
    var $phone_mobile;
    var $phone_work;
    var $phone_other;
    var $phone_fax;
    var $email1;
    var $email2;
    var $invalid_email;
    var $email_opt_out;
    var $primary_address_street;
    var $primary_address_street_2;
    var $primary_address_street_3;
    var $primary_address_city;
    var $primary_address_state;
    var $primary_address_postalcode;
    var $primary_address_country;
    var $alt_address_street;
    var $alt_address_street_2;
    var $alt_address_street_3;
    var $alt_address_city;
    var $alt_address_state;
    var $alt_address_postalcode;
    var $alt_address_country;
    var $assistant;
    var $assistant_phone;
    var $email_addresses_primary;
    var $email_addresses;
    var $picture;
    var $date_start;
    var $birthdate;
    var $radio_button_group;

    public $list_price;
    public $currency_id;

    public function __construct()
    {
        parent::__construct();
        $this->addVisibilityStrategy('OwnerVisibility');
    }

    /**
     * This overrides the default retrieve function setting the default to encode to false
     */
    function retrieve($id='-1', $encode=false,$deleted=true)
    {
        return parent::retrieve($id, false, $deleted);
    }

    /**
     * This overrides the default save function setting assigned_user_id
     * @see SugarBean::save()
     */
    function save($check_notify = FALSE)
    {
        $this->assigned_user_id = $GLOBALS['current_user']->id;
        return parent::save($check_notify);
    }

    /**
     * function to handle removeFile method from FileApi.php.
     * Actual function that removes file calls using js ('save' method with blank filename)
     */
    public function deleteAttachment()
    {
        return true;
    }
}
