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
use Symfony\Component\Validator\Constraints as AssertBasic;

class TeamsController extends SugarController {


	public function action_DisplayInlineTeams(){
		$this->view = 'ajax';
		$body = '';
		$primary_team_id = isset($_REQUEST['team_id']) ? $_REQUEST['team_id'] : '';
		$caption = '';
		if(!empty($_REQUEST['team_set_id'])){
			require_once('modules/Teams/TeamSetManager.php');
			$teams = TeamSetManager::getTeamsFromSet($_REQUEST['team_set_id']);

			foreach($teams as $row){
				if($row['id'] == $primary_team_id) {
				   $body = $row['display_name'] . '*<br/>' . $body;
				} else {
				   $body .= $row['display_name'].'<br/>';
				}
			}
		}
		global $theme;
		$json = getJSONobj();
		$retArray = array();

		$retArray['body'] = $body;
		$retArray['caption'] = $caption;
	    $retArray['width'] = '100';
	    $retArray['theme'] = $theme;
	    header("Content-Type: application/json");
	    echo $json->encode($retArray);
	}
    /**
     * This method handles the saving team-based access configuration.
     */
    public function action_saveTBAConfiguration()
    {
        if ($GLOBALS['current_user']->isAdminForModule('Users')) {
            $request = InputValidation::getService();
            $validators = array(
                'Assert\Choice' => array(
                    'choices' => ['true', 'false']
                )
            );

            $tbaConfigurator = new TeamBasedACLConfigurator();

            $enabled = isTruthy($request->getValidInputPost('enabled', $validators, false));

            // if enabled or become enabled do usual job
            if ($enabled) {
                $validators = array(
                    'Assert\Delimited' => array(
                        new AssertBasic\Type(array('type' => 'string')),
                    ),
                );
                $enabledModules = $request->getValidInputPost('enabled_modules', $validators, array());

                $tbaConfigurator->setGlobal($enabled);

                $actionsList = array_keys(ACLAction::getUserActions($GLOBALS['current_user']->id));
                $disabledModules = array_values(array_diff($actionsList, $enabledModules));

                $tbaConfigurator->setForModulesList($disabledModules, false);
                $tbaConfigurator->setForModulesList($enabledModules, true);

                // remove TBA values from disabled modules
                foreach ($disabledModules as $moduleName) {
                    // $moduleBean might be null, e.g. custom module is disabled
                    $moduleBean = BeanFactory::newBean($moduleName);
                    if ($moduleBean) {
                        $tbaConfigurator->removeAllTBAValuesFromBean($moduleBean);
                    }
                }
            } elseif (TeamBasedACLConfigurator::isEnabledGlobally()) {
                // $enabled is false and TBA is enabled here, so TBA is becoming disabled

                // do disable
                $tbaConfigurator->setGlobal(false);

                // clear ALL TBA data
                $tbaConfigurator->removeTBAValuesFromAllTables(array());
            }

            echo json_encode(array('status' => true));
        } else {
            echo json_encode(array(
                'status' => false,
                'message' => $GLOBALS['app_strings']['EXCEPTION_NOT_AUTHORIZED']
            ));
        }
    }
}
