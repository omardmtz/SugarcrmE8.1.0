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
 * vCard implementation
 * @api
 */
class vCard
{
	protected $properties = array();

	protected $name = 'no_name';

	public function clear()
	{
		$this->properties = array();
	}

	function loadContact($contactid, $module='Contacts')
	{
		global $app_list_strings;
        $contact = $this->getBean($module, $contactid);
		// Bug 21824 - Filter fields exported to a vCard by ACLField permissions.
		$contact->ACLFilterFields();
		// cn: bug 8504 - CF/LB break Outlook's vCard import
		$bad = array("\n", "\r");
		$good = array("=0A", "=0D");
		$encoding = '';
		if(strpos($contact->primary_address_street, "\n") || strpos($contact->primary_address_street, "\r")) {
			$contact->primary_address_street = str_replace($bad, $good, $contact->primary_address_street);
			$encoding = 'QUOTED-PRINTABLE';
		}

		$this->setName(from_html($contact->first_name), from_html($contact->last_name), $app_list_strings['salutation_dom'][from_html($contact->salutation)]);
		if ( isset($contact->birthdate) )
            $this->setBirthDate(from_html($contact->birthdate));
		$this->setPhoneNumber(from_html($contact->phone_fax), 'FAX');
		$this->setPhoneNumber(from_html($contact->phone_home), 'HOME');
		$this->setPhoneNumber(from_html($contact->phone_mobile), 'CELL');
		$this->setPhoneNumber(from_html($contact->phone_work), 'WORK');
		$this->setEmail(from_html($contact->email1));
		$this->setAddress(from_html($contact->primary_address_street), from_html($contact->primary_address_city), from_html($contact->primary_address_state), from_html($contact->primary_address_postalcode), from_html($contact->primary_address_country), 'WORK', $encoding);
		if ( isset($contact->account_name) )
            $this->setORG(from_html($contact->account_name), from_html($contact->department));
        else
            $this->setORG('', from_html($contact->department));
		$this->setTitle($contact->title);
	}

	function setTitle($title){
		$this->setProperty("TITLE",$title );
	}
	function setORG($org, $dep){
		$this->setProperty("ORG","$org;$dep" );
	}
	function setAddress($address, $city, $state,$postal, $country, $type, $encoding=''){
		if(!empty($encoding)) {
			$encoding = ";ENCODING={$encoding}";
		}
		$this->setProperty("ADR;$type$encoding",";;$address;$city;$state;$postal;$country" );
	}

	function setName($first_name, $last_name, $prefix){
		$this->name = strtr($first_name.'_'.$last_name, ' ' , '_');
		$this->setProperty('N',$last_name.';'.$first_name.';;'.$prefix );
		$this->setProperty('FN',"$prefix $first_name $last_name");
	}

	function setEmail($address){
		$this->setProperty('EMAIL;INTERNET', $address);
	}

	function setPhoneNumber( $number, $type)
	{
		if($type != 'FAX') {
		    $this->setProperty("TEL;$type", $number);
		}
		else {
		    $this->setProperty("TEL;WORK;$type", $number);
		}
	}
	function setBirthDate($date){
			$this->setProperty('BDAY',$date);
	}
	function getProperty($name){
		if(isset($this->properties[$name]))
			return $this->properties[$name];
		return null;
	}

	function setProperty($name, $value){
		$this->properties[$name] = $value;
	}

	function toString(){
	    global $locale;
		$temp = "BEGIN:VCARD\n";
		foreach($this->properties as $key=>$value){
		    if(!empty($value)) {
			    $temp .= $key. ';CHARSET='.strtolower($locale->getExportCharset()).':'.$value."\n";
		    } else {
		        $temp .= $key. ':'.$value."\n";
		    }
		}
		$temp.= "END:VCARD\n";


		return $temp;
	}

	function saveVCard(){
		global $locale;
		$content = $this->toString();
		if ( !defined('SUGAR_PHPUNIT_RUNNER') ) {
            header("Content-Disposition: attachment; filename={$this->name}.vcf");
            header("Content-Type: text/x-vcard; charset=".$locale->getExportCharset());
            header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
            header("Last-Modified: " . TimeDate::httpTime() );
            header("Cache-Control: max-age=0");
            header("Pragma: public");
            //bug45856 IIS Doesn't like this to be set and it causes the vCard to not get saved
            if (preg_match('/iis/i', $_SERVER['SERVER_SOFTWARE']) === 0) {
                header("Content-Length: ".strlen($content));
            }
        }

		print $locale->translateCharset($content, 'UTF-8', $locale->getExportCharset());
	}

	public function saveVCardApi(ServiceBase $api)
	{
        global $locale;
        $content = $this->toString();
        $api->setHeader("Content-Disposition","attachment; filename={$this->name}.vcf");
        $api->setHeader("Content-Type","text/x-vcard; charset=".$locale->getExportCharset());
        $api->setHeader("Expires","Mon, 26 Jul 1997 05:00:00 GMT" );
        $api->setHeader("Last-Modified", TimeDate::httpTime() );
        $api->setHeader("Cache-Control","max-age=0");
        $api->setHeader("Pragma","public");

		return $locale->translateCharset($content, 'UTF-8', $locale->getExportCharset());
	}

    /**
     * Method parses vCard and creates a Bean in Sugar from it
     *
     * @param $filename - Uploaded vCard file
     * @param string $module - Module we're importing
     * @return string - id of the created bean
     * @throws SugarException if all required fields are not present
     */
    public function importVCard($filename, $module = 'Contacts')
    {
        global $current_user;
        $lines = file($filename);
        $start = false;

        $bean = $this->getBean($module);
        $bean->assigned_user_id = $current_user->id;
        $email_suffix = 1;

        for ($index = 0; $index < sizeof($lines); $index++) {
            $line = $lines[$index];

            // check the encoding and change it if needed
            $locale = Localization::getObject();
            $encoding = false;
            //detect charset
            if (preg_match("/CHARSET=([A-Z]+([A-Z0-9]-?)*):/", $line, $matches)) {
                //found charset hint in vcard
                $encoding = $matches[1];
            } else {
                //use locale to detect charset automatically
                $encoding = $locale->detectCharset($line);
            }
            if ($encoding !== $GLOBALS['sugar_config']['default_charset']) {
                $line = $locale->translateCharset($line, $encoding);
            }

            $line = trim($line);
            if ($start) {
                //VCARD is done
                if (substr_count(strtoupper($line), 'END:VCARD')) {
                    if (!isset($bean->last_name) && !empty($fullname)) {
                        $bean->last_name = $fullname;
                    }
                    break;
                }
                $keyvalue = explode(':', $line);
                if (sizeof($keyvalue) == 2) {
                    $value = $keyvalue[1];
                    for ($newindex = $index + 1; $newindex < sizeof($lines), substr_count($lines[$newindex], ':') == 0; $newindex++) {
                        $value .= $lines[$newindex];
                        $index = $newindex;
                    }
                    $values = explode(';', $value);
                    $size = count($values);
                    $key = strtoupper($keyvalue[0]);
                    $key = strtr($key, '=', '');
                    $key = strtr($key, ',', ';');
                    $keys = explode(';', $key);

                    if ($keys[0] == 'TEL') {
                        if (substr_count($key, 'WORK') > 0) {
                            if (substr_count($key, 'FAX') > 0) {
                                if (!isset($bean->phone_fax)) {
                                    $bean->phone_fax = $value;
                                }
                            } else {
                                if (!isset($bean->phone_work)) {
                                    $bean->phone_work = $value;
                                }
                            }
                        }
                        if (substr_count($key, 'HOME') > 0) {
                            if (substr_count($key, 'FAX') > 0) {
                                if (!isset($bean->phone_fax)) {
                                    $bean->phone_fax = $value;
                                }
                            } else {
                                if (!isset($bean->phone_home)) {
                                    $bean->phone_home = $value;
                                }
                            }
                        }
                        if (substr_count($key, 'CELL') > 0) {
                            if (!isset($bean->phone_mobile)) {
                                $bean->phone_mobile = $value;
                            }
                        }
                        if (substr_count($key, 'FAX') > 0) {
                            if (!isset($bean->phone_fax)) {
                                $bean->phone_fax = $value;
                            }
                        }
                    }

                    if ($keys[0] == 'N') {
                        if ($size > 0) {
                            $bean->last_name = $values[0];
                        }
                        if ($size > 1) {
                            $bean->first_name = $values[1];
                        }
                        if ($size > 3) {
                            $bean->salutation = $values[3];
                        }
                    }

                    if ($keys[0] == 'FN') {
                        $fullname = $value;
                    }

                }
                if ($keys[0] == 'ADR') {
                    if (substr_count($key, 'WORK') > 0 && (substr_count($key, 'POSTAL') > 0 || substr_count($key, 'PARCEL') == 0)) {

                        if (!isset($bean->primary_address_street) && $size > 2) {
                            $textBreaks = array("\n", "\r");
                            $vcardBreaks = array("=0A", "=0D");
                            $bean->primary_address_street = str_replace($vcardBreaks, $textBreaks, $values[2]);
                        }
                        if (!isset($bean->primary_address_city) && $size > 3) {
                            $bean->primary_address_city = $values[3];
                        }
                        if (!isset($bean->primary_address_state) && $size > 4) {
                            $bean->primary_address_state = $values[4];
                        }
                        if (!isset($bean->primary_address_postalcode) && $size > 5) {
                            $bean->primary_address_postalcode = $values[5];
                        }
                        if (!isset($bean->primary_address_country) && $size > 6) {
                            $bean->primary_address_country = $values[6];
                        }
                    }
                }

                if ($keys[0] == 'TITLE') {
                    $bean->title = $value;
                }

                if ($keys[0] == 'EMAIL') {
                    $field = 'email' . $email_suffix;
                    if (!isset($bean->$field)) {
                        $bean->$field = $value;
                    }

                    if ($email_suffix == 1) {
                        $_REQUEST['email1'] = $value;
                    }

                    $email_suffix++;
                }

                if ($keys[0] == 'ORG') {
                    if (!empty($value)) {
                        if ($bean instanceof Contact || $bean instanceof Lead) {
                            $accountBean = $this->getBean('Accounts');
                            // It's a contact, we better try and match up an account
                            $full_company_name = trim($values[0]);
                            // Do we have a full company name match?
                            $result = $accountBean->retrieve_by_string_fields(array('name' => $full_company_name, 'deleted' => 0));
                            if (!isset($result->id)) {
                                // Try to trim the full company name down, see if we get some other matches
                                $vCardTrimStrings = array('/ltd\.*/i' => '',
                                    '/llc\.*/i' => '',
                                    '/gmbh\.*/i' => '',
                                    '/inc\.*/i' => '',
                                    '/\.com/i' => '',
                                );
                                // Allow users to override the trimming strings
                                if (SugarAutoLoader::existing('custom/include/vCardTrimStrings.php')) {
                                    include('custom/include/vCardTrimStrings.php');
                                }
                                $short_company_name = trim(preg_replace(array_keys($vCardTrimStrings), $vCardTrimStrings, $full_company_name), " ,.");

                                $result = $accountBean->retrieve_by_string_fields(array('name' => $short_company_name, 'deleted' => 0));
                            }

                            if ($bean instanceof Lead || !isset($result->id)) {
                                // We could not find a parent account, or this is a lead so only copy the name, no linking
                                $bean->account_id = '';
                                $bean->account_name = $full_company_name;
                            } else {
                                $bean->account_id = $result->id;
                                $bean->account_name = $result->name;
                            }
                            if ($size > 1) {
                                $bean->department = $values[1];
                            }
                        } else {
                            $bean->department = $value;
                        }
                    }

                }

            }

            //FOUND THE BEGINING OF THE VCARD
            if (!$start && substr_count(strtoupper($line), 'BEGIN:VCARD')) {
                $start = true;
            }

        }

        foreach ($bean->get_import_required_fields() as $key => $value) {
            if (empty($bean->$key)) {
                throw new SugarException('LBL_EMPTY_REQUIRED_VCARD');
            }
        }

        if ($bean instanceof Contact && empty($bean->account_id) && !empty($bean->account_name)) {
            // We need to create a new account
            $accountBean = $this->getBean('Accounts');
            // Populate the newly created account with all of the contact information
            foreach ($bean->field_defs as $field_name => $field_def) {
                if (!empty($bean->$field_name)) {
                    $accountBean->$field_name = $bean->$field_name;
                }
            }
            $accountBean->name = $bean->account_name;
            $accountBean->save();
            $bean->account_id = $accountBean->id;
        }

        $beanId = $bean->save();
        return $beanId;
    }

    /**
     * @param string $module
     * @param string $id Optional
     * @return SugarBean|null
     */
    protected function getBean($module, $id = null)
    {
        return BeanFactory::getBean($module, $id);
    }
}
