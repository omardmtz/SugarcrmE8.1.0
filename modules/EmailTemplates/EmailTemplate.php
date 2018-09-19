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
 *  EmailTemplate is used to store email email_template information.
 */
class EmailTemplate extends SugarBean {
	// Stored fields
	var $id;
	var $date_entered;
	var $date_modified;
	var $modified_user_id;
	var $created_by;
	var $created_by_name;
	var $modified_by_name;
    var $assigned_user_id;
    var $assigned_user_name;
	var $name;
	var $published;
	var $description;
	var $body;
	var $body_html;
    var $subject;
	var $attachments;
    public $has_variables;
	var $from_name;
	var $from_address;
	var $team_id;
	var $assigned_name;
	var $base_module;
	var $table_name = "email_templates";
	var $object_name = "EmailTemplate";
	var $module_dir = "EmailTemplates";
	var $new_schema = true;
	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = array(
		'base_module'
	);
	// add fields here that would not make sense in an email template
	var $badFields = array(
		'team_id',
		'account_description',
		'contact_id',
		'lead_id',
		'opportunity_amount',
		'opportunity_id',
		'opportunity_name',
		'opportunity_role_id',
		'opportunity_role_fields',
		'opportunity_role',
		'campaign_id',
		// User objects
		'id',
		'date_entered',
		'date_modified',
		'user_preferences',
		'accept_status',
		'user_hash',
		'authenticate_id',
		'sugar_login',
		'reports_to_id',
		'reports_to_name',
		'is_admin',
		'receive_notifications',
		'modified_user_id',
		'modified_by_name',
		'created_by',
		'created_by_name',
		'accept_status_id',
		'accept_status_name',
        'acl_role_set_id',
	);

    protected $storedVariables = array();


	public function __construct() {
		parent::__construct();

		global $current_user;
		if(!empty($current_user)) {
			$this->team_id = $current_user->default_team;	//default_team is a team id
		} else {
			$this->team_id = 1; // make the item globally accessible
		}
	}

	/**
	 * Generates the extended field_defs for creating macros
	 * @param object $bean SugarBean
	 * @param string $prefix "contact_", "user_" etc.
	 * @return
	 */
	function generateFieldDefsJS() {
		global $current_user;

		$contact = BeanFactory::newBean('Contacts');
		$account = BeanFactory::newBean('Accounts');
		$lead = BeanFactory::newBean('Leads');
		$prospect = BeanFactory::newBean('Prospects');

		$loopControl = array(
			'Contacts' => array(
			    'Contacts' => $contact,
			    'Leads' => $lead,
				'Prospects' => $prospect,
			),
			'Accounts' => array(
				'Accounts' => $account,
			),
			'Users' => array(
				'Users' => $current_user,
			),
            'Current User' => array(
                'Users' => $current_user,
            ),
		);

		$prefixes = array(
			'Contacts' => 'contact_',
			'Accounts' => 'account_',
			'Users'	=> 'contact_user_',
            'Current User'  => 'user_',
		);

		$collection = array();
		foreach($loopControl as $collectionKey => $beans) {
			$collection[$collectionKey] = array();
			foreach($beans as $beankey => $bean) {

				foreach($bean->field_defs as $key => $field_def) {
				    if(	($field_def['type'] == 'relate' && empty($field_def['custom_type'])) ||
						($field_def['type'] == 'assigned_user_name' || $field_def['type'] =='link') ||
						($field_def['type'] == 'bool') ||
						(in_array($field_def['name'], $this->badFields)) ) {
				        continue;
				    }

                    // Set a label if it doesn't exist
				    if(!isset($field_def['vname'])) {
				    	$field_def['vname'] = empty($field_def['name']) ? $key : $field_def['name'];
				    }
				    // valid def found, process
				    $optionKey = strtolower("{$prefixes[$collectionKey]}{$key}");
				    $optionLabel = preg_replace('/:$/', "", translate($field_def['vname'], $beankey));
				    $dup=1;
				    foreach ($collection[$collectionKey] as $value){
				    	if($value['name']==$optionKey){
				    		$dup=0;
				    		break;
				    	}
				    }
				    if($dup)
				        $collection[$collectionKey][] = array("name" => $optionKey, "value" => $optionLabel);
				}
			}
		}

		$json = getJSONobj();
		$ret = "var field_defs = ";
		$ret .= $json->encode($collection, false);
		$ret .= ";";
		return $ret;
	}

	function get_summary_text() {
		return "$this->name";
	}

	function fill_in_additional_detail_fields() {
	    if (empty($this->body) && !empty($this->body_html))
        {
            global $sugar_config;
            $this->body = strip_tags(html_entity_decode($this->body_html, ENT_COMPAT, $sugar_config['default_charset']));
        }
        parent::fill_in_additional_detail_fields();
	}

	function fill_in_additional_parent_fields() {
	}

	function get_list_view_data() {
		global $app_list_strings, $focus, $action, $currentModule;
		$fields = $this->get_list_view_array();
		$fields["DATE_MODIFIED"] = substr($fields["DATE_MODIFIED"], 0 , 10);
		if($fields['BASE_MODULE']!='') {
            $fields['BASE_MODULE'] = $app_list_strings['moduleList'][$fields['BASE_MODULE']];
		}
		return $fields;
	}

//function all string that match the pattern {.} , also catches the list of found strings.
    //the cache will get refreshed when the template bean instance changes.
    //The found url key patterns are replaced with name value pairs provided as function parameter. $tracked_urls.
    //$url_template is used to construct the url for the email message. the template should have place holder for 1 variable parameter, represented by %1
    //$template_text_array is a list of text strings that need to be searched. usually the subject, html body and text body of the email message.
    //$removeme_url_template, if the url has is_optout property checked then use this template.
    function parse_tracker_urls($template_text_array,$url_template,$tracked_urls,$removeme_url_template) {
        global $beanFiles,$beanList, $app_list_strings,$sugar_config;
        if (!isset($this->parsed_urls))
            $this->parsed_urls=array();

        $return_array = $template_text_array;
        if(count($tracked_urls) > 0)
        {
            //parse the template and find all the dynamic strings that need replacement.
            foreach ($template_text_array as $key=>$template_text) {
                if (!empty($template_text)) {

                    if(!isset($this->parsed_urls[$key]) || $this->parsed_urls[$key]['text'] != $template_text) {
                        // Decode the encoded characters for curly braces and any other url-encoded characters
                        $template_text = str_ireplace(
                            array('%7B', '%7D'),
                            array('{', '}'),
                            rawurldecode($template_text)
                        );

                        $matches = $this->_preg_match_tracker_url($template_text);
                        $count = count($matches[0]);
                        $this->parsed_urls[$key]=array('matches' => $matches, 'text' => $template_text);
                    } else {
                        $matches=$this->parsed_urls[$key]['matches'];
                        if(!empty($matches[0])) {
                            $count=count($matches[0]);
                        } else {
                            $count=0;
                        }
                    }

                    //navigate thru all the matched keys and replace the keys with actual strings.

                    if($count > 0)
                    {
                        for ($i=($count -1); $i>=0; $i--) {
                            $url_key_name=$matches[0][$i][0];
                            if (!empty($tracked_urls[$url_key_name])) {
                                if ($tracked_urls[$url_key_name]['is_optout']==1){
                                    $tracker_url = $removeme_url_template;
                                } else {
                                    $tracker_url = sprintf($url_template,$tracked_urls[$url_key_name]['id']);
                                }
                            }
                            if(!empty($tracker_url) && !empty($template_text) && !empty($matches[0][$i][0]) && !empty($tracked_urls[$matches[0][$i][0]])){
                                $template_text=substr_replace($template_text,$tracker_url,$matches[0][$i][1], strlen($matches[0][$i][0]));
                                $template_text=str_replace($sugar_config['site_url'].'/'.$sugar_config['site_url'],$sugar_config['site_url'],$template_text);
                            }
                        }
                    }
                }
                $return_array[$key]=$template_text;
            }
        }
        return $return_array;
    }

    /**
     *
     * Method for replace "preg_match_all" in method "parse_tracker_urls"
     * @param $text string String in which we need to search all string that match the pattern {.}
     * @return array result of search
     */
    private function _preg_match_tracker_url($text)
    {
        $result = array();
        $ind = 0;
        $switch = false;
        for($i = 0; $i < strlen($text); $i++)
        {
            if($text[$i] == '{')
            {
                $ind = $i;
                $switch = true;
            }
            elseif($text[$i] == '}' && $switch === true)
            {
                $switch = false;
                array_push($result, array(substr($text, $ind, $i - $ind + 1), $ind));
            }
        }
        return array($result);
    }

	function parse_email_template($template_text_array, $focus_name, $focus, &$macro_nv) {


		global $beanFiles, $beanList, $app_list_strings;

		// generate User instance that owns this "Contact" for contact_user_* macros
		$user = BeanFactory::newBean('Users');
        if(!empty($focus->assigned_user_id)){
		  $user->retrieve($focus->assigned_user_id);
        }

		if(!isset($this->parsed_entities))
			$this->parsed_entities=array();

		//parse the template and find all the dynamic strings that need replacement.
        // Bug #48111 It's strange why prefix for User module is contact_user (see self::generateFieldDefsJS method)
        if ($beanList[$focus_name] == 'User')
        {
            $pattern_prefix = '$contact_user_';
        }
        else
        {
            $pattern_prefix = '$'.strtolower($beanList[$focus_name]).'_';
        }
		$pattern_prefix_length = strlen($pattern_prefix);
		$pattern = '/\\'.$pattern_prefix.'[A-Za-z_0-9]*/';

		foreach($template_text_array as $key=>$template_text) {
			if(!isset($this->parsed_entities[$key])) {
				$matches = array();
				$count = preg_match_all($pattern, $template_text, $matches, PREG_OFFSET_CAPTURE);

				if($count != 0) {
					for($i=($count -1); $i>=0; $i--) {
						if(!isset($matches[0][$i][2])) {
							//find the field name in the bean.
							$matches[0][$i][2]=substr($matches[0][$i][0],$pattern_prefix_length,strlen($matches[0][$i][0]) - $pattern_prefix_length);

							//store the localized strings if the field is of type enum..
							if(isset($focus->field_defs[$matches[0][$i][2]]) && $focus->field_defs[$matches[0][$i][2]]['type']=='enum' && isset($focus->field_defs[$matches[0][$i][2]]['options'])) {
								$matches[0][$i][3]=$focus->field_defs[$matches[0][$i][2]]['options'];
							}
						}
					}
				}
				$this->parsed_entities[$key]=$matches;
			} else {
				$matches=$this->parsed_entities[$key];
				if(!empty($matches[0])) {
					$count=count($matches[0]);
				} else {
					$count=0;
				}
			}

			for ($i=($count -1); $i>=0; $i--) {
				$field_name=$matches[0][$i][2];

				// cn: feel for User object attribute key and assign as found
				if(strpos($field_name, "user_") === 0) {
					$userFieldName = substr($field_name, 5);
					$value = $user->$userFieldName;
					//_pp($userFieldName."[{$value}]");
				} else {
					$value = $focus->{$field_name};
				}

				//check dom
				if(isset($matches[0][$i][3])) {
					if(isset($app_list_strings[$matches[0][$i][3]][$value])) {
						$value=$app_list_strings[$matches[0][$i][3]][$value];
					}
				}

                //generate name value pair array of macros and corresponding values for the targed.
                $macro_nv[$matches[0][$i][0]] =$value;

				$template_text=substr_replace($template_text,$value,$matches[0][$i][1], strlen($matches[0][$i][0]));
			}

			//parse the template for tracker url strings. patter for these strings in {[a-zA-Z_0-9]+}

			$return_array[$key]=$template_text;
		}

		return $return_array;
	}

    /**
     * Convenience method to convert raw value into appropriate type format.
     *
     * @deprecated Use {@link EmailTemplate::convertToType()} instead.
     * @param string $type
     * @param string $value
     * @return string
     */
    public function _convertToType($type, $value)
    {
        LoggerManager::getLogger()->deprecated('EmailTemplate::_convertToType() has been deprecated. Use ' .
            'EmailTemplate::convertToType() instead.');

        return self::convertToType($type, $value);
    }

    /**
     * Convenience method to convert raw value into appropriate type format.
     *
     * @param string $type
     * @param string $value
     * @param bool $htmlTarget text values only get converted if true
     * @return string
     */
    private static function convertToType($type, $value, $htmlTarget = false)
    {
        switch ($type) {
            case 'currency':
                return currency_format_number($value);
            case 'text':
            case 'longtext':
                return $htmlTarget ? nl2html($value) : $value;
            default:
                return $value;
        }
    }

    /**
     * Convenience method to parse for user's values in a template.
     *
     * @deprecated Use {@link EmailTemplate::parseUserValues()} instead.
     * @param array $repl_arr
     * @param User $user
     * @return array
     */
    public function _parseUserValues($repl_arr, &$user)
    {
        LoggerManager::getLogger()->deprecated('EmailTemplate::_parseUserValues() has been deprecated. Use ' .
            'EmailTemplate::parseUserValues() instead.');

        return self::parseUserValues($repl_arr, $user);
    }

    /**
     * Convenience method to parse for user's values in a template.
     *
     * @param array $replacementsArray
     * @param User $user
     * @return array
     */
    private static function parseUserValues(array $replacementsArray, User $user)
    {
        foreach ($user->field_defs as $def) {
            if (($def['type'] == 'relate' && empty($def['custom_type'])) ||
                $def['type'] == 'assigned_user_name'
            ) {
                continue;
            }

            $fieldName = "contact_user_{$def['name']}";

            if ($def['type'] == 'enum') {
                $translated = translate($def['options'], 'Users', $user->{$def['name']});

                if (isset($translated) && !is_array($translated)) {
                    $replacementsArray[$fieldName] = $translated;
                } else {
                    // unset enum field, make sure we have a match string to replace with ""
                    $replacementsArray[$fieldName] = '';
                }
            } else {
                if (isset($user->{$def['name']})) {
                    // bug 47647 - allow for fields to translate before adding to template
                    $replacementsArray[$fieldName] = self::convertToType($def['type'], $user->{$def['name']});
                } else {
                    $replacementsArray[$fieldName] = '';
                }
            }
        }

        return $replacementsArray;
    }

    /**
     * Process template variables replacing them with their appropriate data values from supplied bean
     *
     * @param string $string
     * @param string $bean_name
     * @param SugarBean $focus
     * @param bool $htmlTarget - set to true only if the destination of the merge is an html field
     * @return mixed
     */
    public static function parse_template_bean($string, $bean_name, &$focus, $htmlTarget = false)
    {
		global $current_user;
		global $beanFiles, $beanList;
		$repl_arr = array();

		// cn: bug 9277 - create a replace array with empty strings to blank-out invalid vars
		$acct = BeanFactory::newBean('Accounts');
		$contact = BeanFactory::newBean('Contacts');
		$lead = BeanFactory::newBean('Leads');
		$prospect = BeanFactory::newBean('Prospects');

		foreach($lead->field_defs as $field_def) {
			if(($field_def['type'] == 'relate' && empty($field_def['custom_type'])) || $field_def['type'] == 'assigned_user_name') {
         		continue;
			}
            $repl_arr = EmailTemplate::add_replacement($repl_arr, $field_def, array(
                'contact_'         . $field_def['name'] => '',
                'contact_account_' . $field_def['name'] => '',
            ));
		}
		foreach($prospect->field_defs as $field_def) {
			if(($field_def['type'] == 'relate' && empty($field_def['custom_type'])) || $field_def['type'] == 'assigned_user_name') {
         		continue;
			}
            $repl_arr = EmailTemplate::add_replacement($repl_arr, $field_def, array(
                'contact_'         . $field_def['name'] => '',
                'contact_account_' . $field_def['name'] => '',
            ));
		}
		foreach($contact->field_defs as $field_def) {
			if(($field_def['type'] == 'relate' && empty($field_def['custom_type'])) || $field_def['type'] == 'assigned_user_name') {
         		continue;
			}
            $repl_arr = EmailTemplate::add_replacement($repl_arr, $field_def, array(
                'contact_'         . $field_def['name'] => '',
                'contact_account_' . $field_def['name'] => '',
            ));
		}
		foreach($acct->field_defs as $field_def) {
			if(($field_def['type'] == 'relate' && empty($field_def['custom_type'])) || $field_def['type'] == 'assigned_user_name') {
         		continue;
			}
            $repl_arr = EmailTemplate::add_replacement($repl_arr, $field_def, array(
                'account_'         . $field_def['name'] => '',
                'account_contact_' . $field_def['name'] => '',
            ));
		}
		// cn: end bug 9277 fix


		// feel for Parent account, only for Contacts traditionally, but written for future expansion
		if(isset($focus->account_id) && !empty($focus->account_id)) {
			$acct->retrieve($focus->account_id);
		}

		if($bean_name == 'Contacts') {
			// cn: bug 9277 - email templates not loading account/opp info for templates
			if(!empty($acct->id)) {
				foreach($acct->field_defs as $field_def) {
					if(($field_def['type'] == 'relate' && empty($field_def['custom_type'])) || $field_def['type'] == 'assigned_user_name') {
	             		continue;
					}

					if($field_def['type'] == 'enum') {
                        $translated = translate($field_def['options'], 'Accounts', $acct->{$field_def['name']});

						if(isset($translated) && ! is_array($translated)) {
                            $repl_arr = EmailTemplate::add_replacement($repl_arr, $field_def, array(
                                'account_'         . $field_def['name'] => $translated,
                                'contact_account_' . $field_def['name'] => $translated,
                            ));
						} else { // unset enum field, make sure we have a match string to replace with ""
                            $repl_arr = EmailTemplate::add_replacement($repl_arr, $field_def, array(
                                'account_'         . $field_def['name'] => '',
                                'contact_account_' . $field_def['name'] => '',
                            ));
						}
					} else {
                        // bug 47647 - allow for fields to translate before adding to template
                        $translated = self::convertToType($field_def['type'], $acct->{$field_def['name']}, $htmlTarget);
                        $repl_arr = EmailTemplate::add_replacement($repl_arr, $field_def, array(
                            'account_'         . $field_def['name'] => $translated,
                            'contact_account_' . $field_def['name'] => $translated,
                        ));
					}
				}
			}

			if(!empty($focus->assigned_user_id)) {
				$user = BeanFactory::getBean('Users', $focus->assigned_user_id);
                $repl_arr = self::parseUserValues($repl_arr, $user);
			}
		} elseif($bean_name == 'Users') {
			/**
			 * This section of code will on do work when a blank Contact, Lead,
			 * etc. is passed in to parse the contact_* vars.  At this point,
			 * $current_user will be used to fill in the blanks.
			 */
            $repl_arr = self::parseUserValues($repl_arr, $current_user);
		} else {
			// assumed we have an Account in focus
			foreach($contact->field_defs as $field_def) {
				if(($field_def['type'] == 'relate' && empty($field_def['custom_type'])) || $field_def['type'] == 'assigned_user_name' || $field_def['type'] == 'link') {
             		continue;
				}

				if($field_def['type'] == 'enum') {
                    $translated = translate($field_def['options'], 'Accounts', $contact->{$field_def['name']});

					if(isset($translated) && ! is_array($translated)) {
                        $repl_arr = EmailTemplate::add_replacement($repl_arr, $field_def, array(
                            'contact_'         . $field_def['name'] => $translated,
                            'contact_account_' . $field_def['name'] => $translated,
                        ));
					} else { // unset enum field, make sure we have a match string to replace with ""
                        $repl_arr = EmailTemplate::add_replacement($repl_arr, $field_def, array(
                            'contact_'         . $field_def['name'] => '',
                            'contact_account_' . $field_def['name'] => '',
                        ));
					}
                } elseif (isset($contact->{$field_def['name']})) {
                    // bug 47647 - allow for fields to translate before adding to template
                    $translated = self::convertToType($field_def['type'], $contact->{$field_def['name']}, $htmlTarget);
                    $repl_arr = EmailTemplate::add_replacement($repl_arr, $field_def, array(
                        'contact_'         . $field_def['name'] => $translated,
                        'contact_account_' . $field_def['name'] => $translated,
                    ));
                }
			}
		}

		///////////////////////////////////////////////////////////////////////
		////	LOAD FOCUS DATA INTO REPL_ARR
		foreach($focus->field_defs as $field_def) {
            if (isset($focus->{$field_def['name']})) {
				if(($field_def['type'] == 'relate' && empty($field_def['custom_type'])) || $field_def['type'] == 'assigned_user_name') {
             		continue;
				}

				if($field_def['type'] == 'enum' && isset($field_def['options'])) {
                    $translated = translate($field_def['options'], $bean_name, $focus->{$field_def['name']});

					if(isset($translated) && ! is_array($translated)) {
                        $repl_arr = EmailTemplate::add_replacement($repl_arr, $field_def, array(
                            strtolower($beanList[$bean_name])."_".$field_def['name'] => $translated,
                        ));
					} else { // unset enum field, make sure we have a match string to replace with ""
                        $repl_arr = EmailTemplate::add_replacement($repl_arr, $field_def, array(
                            strtolower($beanList[$bean_name])."_".$field_def['name'] => '',
                        ));
					}
				} else {
                    // bug 47647 - translate currencies to appropriate values
                    $repl_arr = EmailTemplate::add_replacement($repl_arr, $field_def, array(
                        strtolower($beanList[$bean_name]) . '_' . $field_def['name']
                            => self::convertToType($field_def['type'], $focus->{$field_def['name']}, $htmlTarget),
                    ));
				}
			} else {
				if($field_def['name'] == 'full_name') {
                    $repl_arr = EmailTemplate::add_replacement($repl_arr, $field_def, array(
                        strtolower($beanList[$bean_name]).'_full_name' => $focus->get_summary_text(),
                    ));
				} else {
                    $repl_arr = EmailTemplate::add_replacement($repl_arr, $field_def, array(
                        strtolower($beanList[$bean_name])."_".$field_def['name'] => '',
                    ));
				}
			}
		} // end foreach()

		krsort($repl_arr);
		reset($repl_arr);
		//20595 add nl2br() to respect the multi-lines formatting
		if(isset($repl_arr['contact_primary_address_street'])){
		    $repl_arr['contact_primary_address_street'] = nl2br($repl_arr['contact_primary_address_street']);
		}
		if(isset($repl_arr['contact_alt_address_street'])){
		    $repl_arr['contact_alt_address_street'] = nl2br($repl_arr['contact_alt_address_street']);
		}

		foreach ($repl_arr as $name=>$value) {
			if($value != '' && is_string($value)) {
				$string = str_replace("\$$name", $value, $string);
			} else {
				$string = str_replace("\$$name", ' ', $string);
			}
		}

		return $string;
	}

    /**
     * Add replacement(s) to the collection based on field definition
     *
     * @param array $data
     * @param array $field_def
     * @param array $replacement
     * @return array
     */
    protected static function add_replacement($data, $field_def, $replacement)
    {
        foreach ($replacement as $key => $value)
        {
            // @see defect #48641
            if ('multienum' == $field_def['type']) {
                $value = implode(', ', unencodeMultienum($value));
            }
            $data[$key] = $value;
        }
        return $data;
    }

    /**
     * Iterate over an array of Beans and invoke parse_template_bean for each Bean in array
     *
     * @param string $string
     * @param array $bean_arr
     * @param bool $htmlTarget - indicates whether the destination field is an Html field
     * @return mixed
     */
    public static function parse_template($string, $bean_arr, $htmlTarget = false)
    {
		foreach($bean_arr as $bean_name => $bean_id) {
		    $focus = BeanFactory::getBean($bean_name, $bean_id);

			if($bean_name == 'Leads' || $bean_name == 'Prospects') {
				$bean_name = 'Contacts';
			}

            $string = EmailTemplate::parse_template_bean($string, $bean_name, $focus, $htmlTarget);
		}
		return $string;
	}

	function bean_implements($interface) {
		switch($interface) {
			case 'ACL':return true;
		}
		return false;
	}

    static function getTypeOptionsForSearch(){
        $template = BeanFactory::newBean('EmailTemplates');
        $optionKey = $template->field_defs['type']['options'];
        $options = $GLOBALS['app_list_strings'][$optionKey];
        if (!is_admin($GLOBALS['current_user'])) {
            unset($options['workflow']);
            unset($options['system']);
        }

        return $options;
    }

	function is_used_by_email_marketing() {
		$query = "select id from email_marketing where template_id='$this->id' and deleted=0";
		$result = $this->db->query($query);
		if($this->db->fetchByAssoc($result)) {
			return true;
		}
		return false;
	}

    /**
     * Allows us to save variables of template as they are
     */
    public function cleanBean()
    {
        $this->storedVariables = array();
        $this->body_html = preg_replace_callback('/\{::[^}]+::\}/', array($this, 'storeVariables'), $this->body_html);
        parent::cleanBean();
        $this->body_html = str_replace(array_values($this->storedVariables), array_keys($this->storedVariables), $this->body_html);
    }

    /**
     * Replacing variables of templates by their md5 hash
     *
     * @param array $text result of preg_replace_callback
     * @return string md5 hash of result
     */
    protected function storeVariables($text)
    {
        if (isset($this->storedVariables[$text[0]]) == false) {
            $this->storedVariables[$text[0]] = md5($text[0]);
        }
        return $this->storedVariables[$text[0]];
    }

    /**
     * {@inheritdoc}
     */
    public function save($check_notify = false)
    {
        // check if email has dynamic variables
        $templateData = $this->subject . ' ' . $this->body . ' ' . $this->body_html;
        $this->has_variables = static::checkStringHasVariables($templateData);
        return parent::save($check_notify);
    }

    /**
     * Checks a template string and returns true if the template has dynamic variables added
     *
     * @param string $tplStr The template string to check for dynamic variables
     * @return bool True if string has variables, false if no variables detected
     */
    public static function checkStringHasVariables($tplStr)
    {
        $pattern = '/\$[a-zA-Z]+_[a-zA-Z0-9_]+/';
        return !!preg_match($pattern, $tplStr);
    }
}
