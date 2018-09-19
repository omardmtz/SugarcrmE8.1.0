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

require_once('include/SearchForm/SearchForm.php');

use Sugarcrm\Sugarcrm\Security\InputValidation\InputValidation;
use Sugarcrm\Sugarcrm\Security\InputValidation\Request;

class SearchFormReports extends SearchForm
{
    /**
     * @see SearchForm::setup()
     */
    function setup()
    {
        parent::setup();

        $this->xtpl->assign('LOADING_IMAGE',getStudioIcon('loading', 'loading', 16, 16));
        $this->xtpl->assign('HELP_IMAGE',SugarThemeRegistry::current()->getImageURL('help-dashlet.gif'));
        $this->xtpl->assign('CLOSE_IMAGE',SugarThemeRegistry::current()->getImageURL('close.gif'));
    }

    /**
     * @see SearchForm::displayHeader()
     */
    function displayHeader($view)
    {
        global $current_user;
        $GLOBALS['log']->debug('SearchForm.php->displayHeader()');
        $header_text = '';
        $module = $this->request->getValidInputRequest('module', 'Assert\Mvc\ModuleName');
        $action = $this->request->getValidInputRequest('action');
        if(is_admin($current_user) && $module != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
            $header_text = "<a href='index.php?action=index&module=DynamicLayout&from_action=SearchForm&from_module=".$module ."'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDITLAYOUT'])."</a>";
        }

        echo "<form name='search_form' class='search_form'>" .
             "<input type='hidden' name='searchFormTab' value='{$view}'/>" .
             "<input type='hidden' name='module' value='".htmlspecialchars($module, ENT_QUOTES, 'UTF-8')."'/>" .
             "<input type='hidden' name='action' value='".htmlspecialchars($action, ENT_QUOTES, 'UTF-8')."'/>" .
             "<input type='hidden' name='query' value='true'/>";
    }

    /**
     * @see SearchForm::displayWithHeaders()
     */
    function displayWithHeaders($view, $basic_search_text = '', $advanced_search_text = '', $saved_views_text = '')
    {
        $GLOBALS['log']->debug('SearchForm.php->displayWithHeaders()');
        $this->displayHeader($view);
        echo "<div id='{$this->module}basic_searchSearchForm' class='edit view search basic' " . (($view == 'basic_search') ? '' : "style='display: none'") . ">" . $basic_search_text . "</div>";
        echo "<div id='{$this->module}advanced_searchSearchForm' class='edit view search advanced' " . (($view == 'advanced_search') ? '' : "style='display: none'") . ">" . $advanced_search_text . "</div>";
        echo '</form>';
        echo "
                <script>
                    function toggleInlineSearch(){
                        if (document.getElementById('inlineSavedSearch').style.display == 'none'){
                            document.getElementById('showSSDIV').value = 'yes'
                            document.getElementById('inlineSavedSearch').style.display = '';

                            document.getElementById('up_down_img').src='".SugarThemeRegistry::current()->getImageURL('basic_search.gif')."';
                            document.getElementById('up_down_img').setAttribute('alt','".translate('LBL_ALT_HIDE_OPTIONS')."');

                        }else{

                            document.getElementById('up_down_img').src='".SugarThemeRegistry::current()->getImageURL('advanced_search.gif')."';
                            document.getElementById('up_down_img').setAttribute('alt','".translate('LBL_ALT_SHOW_OPTIONS')."');
                            document.getElementById('showSSDIV').value = 'no';
                            document.getElementById('inlineSavedSearch').style.display = 'none';
                        }
                    }


                </script>
            ";
    }

    function displayAdvanced($header = true, $return = false, $listViewDefs='', $lv='')
    {
        global $app_strings;

        $SAVED_SEARCHES_OPTIONS = '';
        $savedSearch = BeanFactory::newBean('SavedSearch');
        $SAVED_SEARCHES_OPTIONS = $savedSearch->getSelect($this->module);
        $str = "";
        if(!empty($SAVED_SEARCHES_OPTIONS) && $this->showSavedSearchOptions){
            $str .= "   <span class='white-space'>
                        &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<b>{$app_strings['LBL_SAVED_SEARCH_SHORTCUT']}</b>&nbsp;
                        {$SAVED_SEARCHES_OPTIONS}
                        <span id='go_btn_span' style='display:none'><input tabindex='2' title='go_select' id='go_select'  onclick='SUGAR.searchForm.clear_form(this.form); return false;' class='button' type='button' name='go_select' value=' {$app_strings['LBL_GO_BUTTON_LABEL']} '/></span>
                    </span>";
        }
        $str .= "
                <script>
                    function toggleInlineSearch(){
                        if (document.getElementById('inlineSavedSearch').style.display == 'none'){
                            document.getElementById('showSSDIV').value = 'yes'
                            document.getElementById('inlineSavedSearch').style.display = '';

                            document.getElementById('up_down_img').src='".SugarThemeRegistry::current()->getImageURL('basic_search.gif')."';
                            document.getElementById('up_down_img').setAttribute('alt','".translate('LBL_ALT_HIDE_OPTIONS')."');

                        }else{

                            document.getElementById('up_down_img').src='".SugarThemeRegistry::current()->getImageURL('advanced_search.gif')."';
                            document.getElementById('up_down_img').setAttribute('alt','".translate('LBL_ALT_SHOW_OPTIONS')."');
                            document.getElementById('showSSDIV').value = 'no';
                            document.getElementById('inlineSavedSearch').style.display = 'none';
                        }
                    }


                </script>
            ";
        $this->xtpl->assign('ADVANCED_BUTTONS',$str);
        $this->xtpl->assign('LBL_DELETE_CONFIRM',translate('LBL_DELETE_CONFIRM', 'SavedSearch'));
        return parent::displayAdvanced($header, $return, $listViewDefs, $lv);
    }

    public static function retrieveReportsSearchDefs()
     {
         $searchdefs = array();
         $searchFields = array();

         $defs = SugarAutoLoader::loadWithMetafiles('Reports', 'searchdefs');
         if($defs) {
             require $defs;
         }
         $searchFields = SugarAutoLoader::loadSearchFields('Reports');

         return array('searchdefs' => $searchdefs, 'searchFields' => $searchFields );
     }


}
