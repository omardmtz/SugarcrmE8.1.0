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


$ie = BeanFactory::newBean('InboundEmail');
$ie->disable_row_level_security = true;
$json = getJSONobj();
$rules = new SugarRouting($ie, $current_user);

switch($_REQUEST['routingAction']) {
	case "setRuleStatus":
		$rules->setRuleStatus($_REQUEST['rule_id'], $_REQUEST['status']);
	break;

	case "saveRule":
		$rules->save($_REQUEST);
	break;

	case "deleteRule":
		$rules->deleteRule($_REQUEST['rule_id']);
	break;

	/* returns metadata to construct actions */
	case "getActions":

		$sdd = new SugarDependentDropdown();
		$sdd->init("include/SugarDependentDropdown/metadata/dependentDropdown.php");
		$out = $json->encode($sdd->metadata, true);
		echo $out;
	break;

	/* returns metadata to construct a rule */
	case "getRule":
		$ret = '';
		if(isset($_REQUEST['rule_id']) && !empty($_REQUEST['rule_id']) && isset($_REQUEST['bean']) && !empty($_REQUEST['bean'])) {
		    $bean = BeanFactory::newBean($_REQUEST['bean']);
            if(!empty($bean)) {
				$rule = $rules->getRule($_REQUEST['rule_id'], $bean);

				$ret = array(
					'bean' => $_REQUEST['bean'],
					'rule' => $rule
				);
			}
		} else {
			$bean = BeanFactory::newBean('Empty');
			$rule = $rules->getRule('', $bean);

			$ret = array(
				'bean' => $_REQUEST['bean'],
				'rule' => $rule
			);
		}

		//_ppd($ret);

		$out = $json->encode($ret, true);
		echo $out;
	break;

	case "getStrings":
		$ret = $rules->getStrings();
		$out = $json->encode($ret, true);
		echo $out;
	break;


	default:
		echo "NOOP";
}
