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



class DocumentsViewExtdoc extends SugarView
{
    var $options = array('show_header' => false, 'show_title' => false, 'show_subpanels' => false, 'show_search' => true, 'show_footer' => false, 'show_javascript' => false, 'view_print' => false,);

    public function init($bean = null, $view_object_map = array())
    {
        $this->seed = $bean;
    }

 	public function display(){

        global $mod_strings;
        $file_search = trim($this->request->getValidInputRequest('name_basic'));
        $apiName = $this->request->getValidInputRequest('apiName');
        $isPopup = $this->request->getValidInputRequest('isPopup');
        $elemBaseName = $this->request->getValidInputRequest('elemBaseName');

        if ($apiName === null) {
            $apiName = 'IBMSmartCloud';
        } else {
            $tmpApi = ExternalAPIFactory::loadAPI($apiName, true);
            if ( $tmpApi === false )
            {
                $GLOBALS['log']->error(string_format($mod_strings['ERR_INVALID_EXTERNAL_API_ACCESS'], array($apiName)));
                return;
            }
        }

        // See if we are running as a popup window
        if (isTruthy($isPopup) && !empty($elemBaseName) ) {
            $isPopup = true;
        } else {
            $isPopup = false;
        }

         // bug50952 - must actually make sure we can log in, not just that we've got a EAPM record
         // getLoginInfo only checks to see if user has logged in correctly ONCE to ExternalAPI
         // Need to manually attempt to fetch the EAPM record, we don't want to give them the signup screen when they just have a deactivated account.
         $eapmBean = EAPM::getLoginInfo($apiName,true);
         $api = ExternalAPIFactory::loadAPI($apiName,true);
         $validSession = true;

         if(!empty($eapmBean))
         {
             try {
               $api->loadEAPM($eapmBean);
               // $api->checkLogin() does the same thing as quickCheckLogin plus actually makes sure the user CAN log in to the API currently
               $loginCheck = $api->checkLogin($eapmBean);
               if(isset($loginCheck['success']) && !$loginCheck['success'])
               {
                   $validSession = false;
               }
             } catch(Exception $ex) {
               $validSession = false;
               $GLOBALS['log']->error(string_format($mod_strings['ERR_INVALID_EXTERNAL_API_LOGIN'], array($apiName)));
             }
         }

         if (!$validSession || empty($eapmBean))
         {
             // Bug #49987 : Documents view.extdoc.php doesn't allow custom override
             $tpl_file = SugarAutoLoader::existingCustomOne('include/externalAPI/'.$apiName.'/'.$apiName.'Signup.'.$GLOBALS['current_language'].'.tpl');

             if ($tpl_file) {
                 $smarty = new Sugar_Smarty();
                 echo $smarty->fetch($tpl_file);
             } else  {
                 $output = string_format(translate('LBL_ERR_FAILED_QUICKCHECK','EAPM'), array($apiName));
                 $output .= '<form method="POST" target="_EAPM_CHECK" action="index.php">';
                 $output .= '<input type="hidden" name="module" value="EAPM">';
                 $output .= '<input type="hidden" name="action" value="Save">';
                 $output .= '<input type="hidden" name="record" value="'.$eapmBean->id.'">';
                 $output .= '<input type="hidden" name="active" value="1">';
                 $output .= '<input type="hidden" name="closeWhenDone" value="1">';
                 $output .= '<input type="hidden" name="refreshParentWindow" value="1">';

                 $output .= '<br><input type="submit" value="'.$GLOBALS['app_strings']['LBL_EMAIL_OK'].'">&nbsp;';
                 $output .= '<input type="button" onclick="lastLoadedMenu=undefined;DCMenu.closeOverlay();return false;" value="'.$GLOBALS['app_strings']['LBL_CANCEL_BUTTON_LABEL'].'">';
                 $output .= '</form>';
                 echo $output;
             }

             return;
         }

        $searchDataLower = $api->searchDoc($file_search,true);

        // In order to emulate the list views for the SugarFields, I need to uppercase all of the key names.
        $searchData = array();

        if ( is_array($searchDataLower) ) {
            foreach ( $searchDataLower as $row ) {
                $newRow = array();
                foreach ( $row as $key => $value ) {
                    $newRow[strtoupper($key)] = $value;
                }

                if ( $isPopup ) {
                    // We are running as a popup window, we need to replace the direct url with some javascript
                    $newRow['DOC_URL'] = "javascript:window.opener.SUGAR.field.file.populateFromPopup('"
                        . htmlspecialchars($elemBaseName, ENT_QUOTES, 'UTF-8')
                        . "','" . htmlspecialchars($newRow['ID'], ENT_QUOTES, 'UTF-8')
                        . "','" . htmlspecialchars($newRow['NAME'], ENT_QUOTES, 'UTF-8')
                        . "','" . htmlspecialchars($newRow['URL'], ENT_QUOTES, 'UTF-8')
                        . "','" . htmlspecialchars($newRow['URL'], ENT_QUOTES, 'UTF-8')
                        . "'); window.close();";
                }else{
                    $newRow['DOC_URL'] = $newRow['URL'];
                }
                $searchData[] = $newRow;
            }
        }

        $displayColumns = array(
            'NAME' => array(
                'label' => 'LBL_LIST_EXT_DOCUMENT_NAME',
                'type' => 'varchar',
                'link' => true,
                ),
            'DATE_MODIFIED' => array(
                'label' => 'LBL_DATE',
                'type' => 'date',
                ),
        );

        $ss = new Sugar_Smarty();
        $ss->assign('searchFieldLabel',translate('LBL_SEARCH_EXTERNAL_DOCUMENT','Documents'));
        $ss->assign('displayedNote',translate('LBL_EXTERNAL_DOCUMENT_NOTE','Documents'));
        $ss->assign('APP',$GLOBALS['app_strings']);
        $ss->assign('MOD',$GLOBALS['mod_strings']);
        $ss->assign('data', $searchData);
        $ss->assign('displayColumns',$displayColumns);
        $ss->assign('imgPath',SugarThemeRegistry::current()->getImageURL($apiName.'_image_inline.png'));

        if ( $isPopup ) {
            $ss->assign('linkTarget','');
            $ss->assign('isPopup',1);
            $ss->assign('elemBaseName',$elemBaseName);
        } else {
            $ss->assign('linkTarget','_new');
            $ss->assign('isPopup',0);
            $ss->assign('elemBaseName','');
        }
        $ss->assign('apiName',$apiName);
        $ss->assign('DCSEARCH',$file_search);

        if ( $isPopup ) {
            // Need the popup header... I feel so dirty.
            ob_start();
            echo('<div class="dccontent">');
            insert_popup_header($GLOBALS['theme'], false);
            $output_html = ob_get_contents();
            ob_end_clean();

            $output_html .= get_form_header(translate('LBL_SEARCH_FORM_TITLE','Documents'), '', false);

            echo($output_html);
        }

        $ss->display('modules/Documents/tpls/view.extdoc.tpl');

        if ( $isPopup ) {
            // Close the dccontent div
            echo('</div>');
        }
 	}
}
