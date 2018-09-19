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


class TemplateInt extends TemplateRange
{
	var $type = 'int';

    /**
     * @var int|null
     */
    public $len = null;

    var $supports_unified_search = true;

	public function __construct(){
		parent::__construct();
		$this->vardef_map['autoinc_next'] = 'autoinc_next';
		$this->vardef_map['autoinc_start'] = 'autoinc_start';
		$this->vardef_map['auto_increment'] = 'auto_increment';
        
        $this->vardef_map['min'] = 'ext1';
        $this->vardef_map['max'] = 'ext2';
        $this->vardef_map['disable_num_format'] = 'ext3';
    }

	function get_html_edit(){
		$this->prepare();
		return "<input type='text' name='". $this->name. "' id='".$this->name."' title='{" . strtoupper($this->name) ."_HELP}' size='".$this->size."' maxlength='".$this->len."' value='{". strtoupper($this->name). "}'>";
	}

    public function populateFromPost(Request $request = null)
    {
        if (!$request) {
            $request = InputValidation::getService();
        }

        parent::populateFromPost($request);
		if (isset($this->auto_increment))
		{
		    $this->auto_increment = $this->auto_increment == "true" || $this->auto_increment === true;
		}
	}

    function get_field_def(){
		$vardef = parent::get_field_def();
		$vardef['disable_num_format'] = isset($this->disable_num_format) ? $this->disable_num_format : $this->ext3;//40005

        $vardef['min'] = isset($this->min) ? $this->min : $this->ext1;
        $vardef['max'] = isset($this->max) ? $this->max : $this->ext2;
        $vardef['min'] = filter_var($vardef['min'], FILTER_VALIDATE_INT);
        $vardef['max'] = filter_var($vardef['max'], FILTER_VALIDATE_INT);
        if ($vardef['min'] !== false || $vardef['max'] !== false)
        {
            $vardef['validation'] = array(
                'type' => 'range',
                'min' => $vardef['min'],
                'max' => $vardef['max']
            );
        }

        if(!empty($this->auto_increment))
		{
			$vardef['auto_increment'] = $this->auto_increment;
			if ((empty($this->autoinc_next)) && isset($this->module) && isset($this->module->table_name))
			{
                $db = DBManagerFactory::getInstance();
                $auto = $db->getAutoIncrement($this->module->table_name, $this->name);
                $this->autoinc_next = $vardef['autoinc_next'] = $auto;
			}
		}
		return $vardef;
    }

    function save($df){
        $next = false;
		if (!empty($this->auto_increment) && (!empty($this->autoinc_next) || !empty($this->autoinc_start)) && isset($this->module))
        {
            if (!empty($this->autoinc_start) && $this->autoinc_start > $this->autoinc_next)
			{
				$this->autoinc_next = $this->autoinc_start;
			}
			if(isset($this->module->table_name)){
                $db = DBManagerFactory::getInstance();
                //Check that the new value is greater than the old value
                $oldNext = $db->getAutoIncrement($this->module->table_name, $this->name);
                if ($this->autoinc_next > $oldNext)
                {
                    $db->setAutoIncrementStart($this->module->table_name, $this->name, $this->autoinc_next);
                }
			}
			$next = $this->autoinc_next;
			$this->autoinc_next = false;
        }
		parent::save($df);
		if ($next)
		  $this->autoinc_next = $next;
    }
}
