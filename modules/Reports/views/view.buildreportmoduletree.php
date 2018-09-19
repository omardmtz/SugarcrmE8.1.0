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
require_once('modules/Reports/config.php');

class ReportsViewBuildreportmoduletree extends SugarView
{
    /**
     * Listing of modules that should not be reported on. This is a simple key
     * value pair like 'module_name' => true.
     *
     * @var array
     */
    protected $nonReportableModules = array(
        'Currencies' => true,
    );

    protected function isRelationshipReportable($rel)
    {
        global $beanList;

        if (empty($rel)) {
            return false;
        }

        if (empty($beanList[$rel->lhs_module]) || empty($beanList[$rel->rhs_module])) {
            return false;
        }

        // Bug 37311 - Don't allow reporting on relationships to the currencies module
        return empty($this->nonReportableModules[$rel->lhs_module]) &&
               empty($this->nonReportableModules[$rel->rhs_module]);
    }

    /**
     * @see SugarView::display()
     */
    public function display()
    {
        global $beanFiles, $beanList, $app_list_strings;
        if(empty($beanFiles)) {
            include('include/modules.php');
        }

        $ACLAllowedModules = getACLAllowedModules();
        $module_array = array();

        $module = BeanFactory::newBean($_REQUEST['report_module']);
        $bean_name = $module->object_name;
        $linked_fields = $module->get_linked_fields();

        foreach($linked_fields as $linked_field)
        {
            $module->load_relationship($linked_field['name']);
            $field = $linked_field['name'];
            if(empty($module->$field) || (isset($linked_field['reportable']) && $linked_field['reportable'] == false))
            {
                continue;
            }
            $relationship = $module->$field->_relationship;

            if ($this->isRelationshipReportable($relationship) === false) {
                continue;
            }

            $bean_is_lhs = $module->$field->_get_bean_position();
            if($bean_is_lhs == true && isset($beanList[$relationship->rhs_module])) {
                $link_bean = $beanList[$relationship->rhs_module];
                $link_module = $relationship->rhs_module;
            }
            else if (isset($beanList[$relationship->lhs_module])){
                $link_bean =  $beanList[$relationship->lhs_module];
                $link_module = $relationship->lhs_module;
            }
            if (!isset($ACLAllowedModules[$link_module])) {
                continue;
            }

			$custom_label = 'LBL_' . strtoupper ( $relationship->relationship_name . '_FROM_' . $link_module  ) . '_TITLE';
			$custom_subpanel_label  = 'LBL_' . strtoupper ( $link_module) . '_SUBPANEL_TITLE';
			//bug 47834
			$lang = $GLOBALS['current_language'];
			foreach(SugarAutoLoader::existing("custom/modules/".$_REQUEST['report_module']."/Ext/Language/".$lang.".lang.ext.php") as $file) {
			    require $file;
			}//end 47834
			// Bug 37308 - Check if the label was changed in studio
		    //bug 47834 -  added check to see if the new label name is set in custom $lang.lang.ext.php file
            if (translate($custom_label, $_REQUEST['report_module']) != $custom_label && isset($mod_strings[$custom_label]))
			{
				$linked_field['label'] = translate($custom_label, $_REQUEST['report_module']);
            }
			// Bug 37308 - Check if the label was changed in studio
			//bug 47834 -  added check to see if the new label name is set in custom $lang.lang.ext.php file
            elseif (translate($custom_subpanel_label, $_REQUEST['report_module']) != $custom_subpanel_label && $link_module != $_REQUEST['report_module'] && isset($mod_strings[$custom_subpanel_label]))
			{
				$linked_field['label'] = translate($custom_subpanel_label, $_REQUEST['report_module']);
            }
            elseif (! empty($linked_field['vname']))
            {
                $linked_field['label'] = translate($linked_field['vname'], $_REQUEST['report_module']);
            } else {
                $linked_field['label'] =$linked_field['name'];
            }
            $linked_field['label'] = preg_replace('/:$/','',$linked_field['label']);
            $linked_field['label'] = addslashes($linked_field['label']);

            $module_array[] = $this->_populateNodeItem($bean_name,$link_module,$linked_field);
        }

        // Sort alphabetically
        function compare_text($a, $b) {
            return strnatcmp($a['text'], $b['text']);
        }
        usort($module_array, 'compare_text');

        $json = getJSONobj();
        echo $json->encode($module_array);
    }

    protected function _populateNodeItem($bean_name,$link_module,$linked_field)
    {
        $node = array();
        $node['text'] = $linked_field['label'];
        $node['href'] = "javascript:SUGAR.reports.populateFieldGrid('". $link_module . "','".$linked_field['relationship']."','".$bean_name."','".str_replace(array('&#039;', '&#39;'), "\'", $linked_field['label'])."');";
        $node['leaf'] = false;
        $node['category'] = $link_module;
        $node['relationship_name'] = $linked_field['relationship'];
        $node['link_name'] = $linked_field['name'];
        $node['link_module'] = $link_module;
        return $node;
    }
}
