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

class HomeViewAdditionaldetailsretrieve extends SugarView
{
 	public function display()
 	{
        global $beanList, $beanFiles, $current_user, $app_strings, $app_list_strings;

        $moduleDir = empty($_REQUEST['bean']) ? '' : $_REQUEST['bean'];
        $beanName = empty($beanList[$moduleDir]) ? '' : $beanList[$moduleDir];
        $id = empty($_REQUEST['id']) ? '' : $_REQUEST['id'];

        // Bug 40216 - Add support for a custom additionalDetails.php file
        $additionalDetailsFile = $this->getAdditionalDetailsMetadataFile($moduleDir);

        if(empty($id) || empty($additionalDetailsFile) ) {
                echo 'bad data';
                die();
        }

        require_once($additionalDetailsFile);
        $adFunction = 'additionalDetails' . $beanName;

        if(function_exists($adFunction)) { // does the additional details function exist
            $json = getJSONobj();
            $bean = BeanFactory::getBean($moduleDir, $id);

            //bug38901 - shows dropdown list label instead of database value
            foreach ($bean->field_defs as $field => $value) {
                if ($value['type'] == 'enum' &&
                    !empty($value['options']) &&
                    !empty($app_list_strings[$value['options']]) &&
                    isset($app_list_strings[$value['options']][$bean->$field])
                ) {
                    $bean->$field = $app_list_strings[$value['options']][$bean->$field];
                }
            }

            $bean->ACLFilterFields();
            $arr = array_change_key_case($bean->toArray(), CASE_UPPER);
            $results = $adFunction($arr);

            $retArray['body'] = str_replace(array("\rn", "\r", "\n"), array('','','<br />'), $results['string']);
            $retArray['caption'] = "<div style='float:left'>{$app_strings['LBL_ADDITIONAL_DETAILS']}</div><div style='float: right'>";

            if (!empty($_REQUEST['show_buttons'])) {
                if ($bean->ACLAccess('EditView')) {
                    $editImg = SugarThemeRegistry::current()->getImageURL('edit_inline.png', false);
                    $results['editLink'] = $this->buildButtonLink($results['editLink']);
                    $retArray['caption'] .= <<<EOC
<a style="text-decoration:none;" title="{$GLOBALS['app_strings']['LBL_EDIT_BUTTON']}" href="{$results['editLink']}">
    <img border=0 src="$editImg">
</a>
EOC;
                }
                if ($bean->ACLAccess('DetailView')) {
                    $detailImg = SugarThemeRegistry::current()->getImageURL('view_inline.png', false);
                    $results['viewLink'] = $this->buildButtonLink($results['viewLink']);
                    $retArray['caption'] .= <<<EOC
<a style="text-decoration:none;" title="{$GLOBALS['app_strings']['LBL_VIEW_BUTTON']}" href="{$results['viewLink']}">
    <img border=0 src="$detailImg" style="margin-left:2px;">
</a>
EOC;
                }
                $closeImg = SugarThemeRegistry::current()->getImageURL('close.png', false);
                $retArray['caption'] .= <<<EOC
<a title="{$GLOBALS['app_strings']['LBL_ADDITIONAL_DETAILS_CLOSE_TITLE']}" href="javascript:SUGAR.util.closeStaticAdditionalDetails();">
    <img border=0 src="$closeImg" style="margin-left:2px;">
</a>
EOC;
            }
            $retArray['caption'] .= "";
            $retArray['width'] = (empty($results['width']) ? '300' : $results['width']);
            header("Content-Type: application/json");
            echo $json->encode($retArray);
        }
    }

    /**
     * Builds an appropriate Sidecar or BWC href attribute for the additional
     * details buttons, using the link supplied from the additional details
     * module metadata.
     *
     * @private
     * @param string $link (optional) The link from additional details module
     *   metadata. The function returns an empty string if none is supplied.
     * @return string The href attribute used for the button.
     */
    private function buildButtonLink($link = '')
    {
        if (preg_match('/module=([^&]+)/', $link, $matches) && !isModuleBWC($matches[1])) {
            parse_str(parse_url($link, PHP_URL_QUERY), $params);
            $script = navigateToSidecar(
                buildSidecarRoute($params['module'], $params['record'], translateToSidecarAction($params['action']))
            );
            $link = "javascript:$script;";
        }

        return $link;
    }

    protected function getAdditionalDetailsMetadataFile($moduleName)
    {
        return SugarAutoLoader::existingCustomOne('modules/' . $moduleName . '/metadata/additionalDetails.php');
    }
}
