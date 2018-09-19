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

$json = getJSONObj();
$out = "";

switch($_REQUEST['adminAction']) {
	///////////////////////////////////////////////////////////////////////////
	////	REPAIRXSS
	case "refreshEstimate":
		include("include/modules.php"); // provide $moduleList
        $target = '';
        if (!empty($_REQUEST['bean'])) {
            $target = $_REQUEST['bean'];
        }

		$count = 0;
		$toRepair = array();

		if($target == 'all') {
			$hide = array('Activities', 'Home', 'iFrames', 'Calendar', 'Dashboard');

			sort($moduleList);
			$options = array();

			foreach($moduleList as $module) {
				if(!in_array($module, $hide)) {
					$options[$module] = $module;
				}
			}

			foreach($options as $module) {
			    $bean = BeanFactory::newBean($module);
			    if(empty($bean)) continue;

				$q = "SELECT count(*) as count FROM {$bean->table_name}";
				$r = $bean->db->query($q);
				$a = $bean->db->fetchByAssoc($r);

				$count += $a['count'];

				// populate to_repair array
				$q2 = "SELECT id FROM {$bean->table_name}";
				$r2 = $bean->db->query($q2);
				$ids = '';
				while($a2 = $bean->db->fetchByAssoc($r2)) {
					$ids[] = $a2['id'];
				}
				$toRepair[$module] = $ids;
			}
		} elseif(in_array($target, $moduleList)) {
		    $bean = BeanFactory::newBean($target);
			$q = "SELECT count(*) as count FROM {$bean->table_name}";
			$r = $bean->db->query($q);
			$a = $bean->db->fetchByAssoc($r);

			$count += $a['count'];

			// populate to_repair array
			$q2 = "SELECT id FROM {$bean->table_name}";
			$r2 = $bean->db->query($q2);
            $ids = array();
			while($a2 = $bean->db->fetchByAssoc($r2)) {
				$ids[] = $a2['id'];
			}
			$toRepair[$target] = $ids;
		}

		$out = array('count' => $count, 'target' => $target, 'toRepair' => $toRepair);
	break;

	case "repairXssExecute":
		if(!empty($_REQUEST['bean']) && !empty($_REQUEST['id'])) {
			$target = $_REQUEST['bean'];
			$ids = $json->decode(from_html($_REQUEST['id']));
			$count = 0;
			foreach($ids as $id) {
				if(!empty($id)) {
				    $bean = BeanFactory::getBean($target, $id);
					$bean->new_with_id = false;
                    $bean->processed = true; // bypassing before_save/after_save hook logic
					$bean->save(); // cleanBean() is called on save()
					$count++;
				}
			}

			$out = array('msg' => "success", 'count' => $count);
		} else {
			$mod_strings = return_module_language($GLOBALS['current_language'], 'Administration');
			$out = array('msg' => $mod_strings['LBL_REPAIRXSSEXECUTE_FAILED']);
		}
	break;
	////	END REPAIRXSS
	///////////////////////////////////////////////////////////////////////////

	default:
		die();
	break;
}

$ret = $json->encode($out, true);
echo $ret;
