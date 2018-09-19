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

class ViewValidateRelatedField extends ViewAjax
{
    var $vars = array("tmodule", "link", "related");

    public function __construct()
    {
        parent::__construct();
        foreach($this->vars as $var)
        {
            if (empty($_REQUEST[$var]))
                sugar_die("Required paramter $var not set in ViewRelFields");
            $this->$var = $_REQUEST[$var];
        }
        $mb = new ModuleBuilder();
        $this->package = empty($_REQUEST['package']) || $_REQUEST['package'] == 'studio' ? "" : $mb->getPackage($_REQUEST['package']);
    }

    function display() {
        $linkName = $this->link;

        if (empty ($this->package))
        {
            //First, create a dummy bean to access the relationship info
            $focus = BeanFactory::newBean($this->tmodule);
            $focus->id = create_guid();
            //Next, figure out what the related module is
            if(!$focus->load_relationship($linkName)){
                echo "Invalid Link : \$$linkName";
                return;
            }
            $relatedModule = $focus->$linkName->getRelatedModuleName();
        } else {
            $module = $this->package->getModule ($this->tmodule);
            $linksFields = $module->getLinkFields();
            if (empty($linksFields[$linkName]))
            {
                echo "Invalid Link \$$linkName";
                return;
            }
            $relatedModule = $linksFields[$linkName]['module'];
        }

        $mbModule = null;
        if (!empty($this->package))
            $mbModule = $this->package->getModuleByFullName($relatedModule);

        if (empty($mbModule)) {
            //If the related module is deployed, use create a seed bean with the bean factory
            $relBean = BeanFactory::newBean($relatedModule);
            $field_defs = $relBean->field_defs;
        } else {
            //Otherwise the mbModule will exist and we can pull the vardef from there
            $field_defs = $mbModule->getVardefs(false);
            $field_defs = $field_defs['fields'];
        }

        //First check if the field exists
        if(!isset($field_defs[$this->related]) || !is_array($field_defs[$this->related]))
        {
            echo(json_encode("Unknown Field : $this->related"));
        }
        //Otherwise, send it to the formula builder to evaluate further
        else
        {
            echo json_encode($field_defs[$this->related]);
        }
    }
}