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

require_once 'include/EditView/EditView2.php';

use Sugarcrm\Sugarcrm\ProcessManager;
use Sugarcrm\Sugarcrm\ProcessManager\Registry;

class pmse_InboxViewShowCase extends SugarView
{
    public $type = 'list';
    public $activityRow = array();
    public $dyn_uid = '';
    public $workFlowType = '';
    public $renderedTemplate = '';
    public $view = '';
    public $showVCRControl = '';
    public $fieldDefs;
    public $offset;
    public $sectionPanels;
    public $returnModule;
    public $returnAction;
    public $returnId;
    public $isDuplicate;
    public $showDetailData;
    public $showSectionPanelsTitles;
    public $viewObject;
    public $populateBean;
    public $pmse;
    private $wrapper;

    /*
     * For each list of beans that a relationship returns, add an entry in the form of
     * [Relationship Name] => [Smarty Template var name]
     */
    protected $relationshipChart = array(
        'Quotes' => array(
            'product_bundles' => 'ordered_bundle_list',
        ),
    );

    public function __construct()
    {
        $this->pmse = PMSE::getInstance();
        $this->wrapper = ProcessManager\Factory::getPMSEObject('PMSEWrapper');
        parent::__construct();
    }

    /**
     * This method assembles and renders the display of the custom template
     * for the edit and detail actions for the ProcessMaker Module.
     * @param type $module The name of the module to be rendered
     * @param type $id The id of the record to be rendered
     * @param type $viewMode This parameter can be 'bpm' 'detail' or 'edit'
     *                        in order to render the adequate template and
     *                        view definition
     */
    public function displayDataForm($module = '', $id = '', $viewMode = 'bpm', $readonly = false)
    {
        if (!empty($module) && !empty($id)) {
            // No need for locked fields on the AWF case
            Registry\Registry::getInstance()->set('skip_locked_field_checks', true);

            $this->bean = BeanFactory::getBean($module, $id);
            $altViewMode = array();
            if (is_array($viewMode)) {
                $isBpm = true;
                $altViewMode = $viewMode;
                $viewMode = $viewMode['displayMode'];
            } else {
                $this->type = 'detail';
                $viewMode = 'detail';
            }

            $this->module = $module;

            if ($readonly) {
                $metaDataFileName = 'detail';
                $metaDataArrayName = 'DetailView';
            } else {
                $metaDataFileName = 'edit';
                $metaDataArrayName = 'EditView';
            }
            $metadataFile = $this->getMetaDataFile($metaDataFileName);


            $viewdefs = [];

            if (isset($GLOBALS['sugar_config']['disable_vcr'])) {
                $this->showVCRControl = !$GLOBALS['sugar_config']['disable_vcr'];
            }

            $mfile = get_custom_file_if_exists($metadataFile);
            if (isset($mfile)) {
                require $metadataFile;
            }

            if (!empty($altViewMode)) {
                $viewdefs = array('BpmView' => $viewdefs[$module][$metaDataArrayName]);
                $this->dyn_uid = $altViewMode['dyn_uid'];

                if ($readonly) {
                    $this->setHeaderFootersReadOnly($viewdefs);
                }
                if($isBpm){
                    $viewdefs['EditView'] = $viewdefs['BpmView'];
                }
                $tmpArray = array();
                $tmpArray[$this->bean->module_name] = $viewdefs;
                $viewdefs = $tmpArray;
            }
            $this->view = ucfirst($viewMode) . 'View';
            if($isBpm){
                $this->view = "EditView";
            }
            if (isset($viewdefs[$this->bean->module_name][$this->view])) {
                $this->defs = $viewdefs[$this->bean->module_name][$this->view];
            } else {
                $this->defs = $viewdefs[$this->bean->module_name]['EditView'];
            }

            $this->focus = $this->bean;
            $tpl = get_custom_file_if_exists('modules/pmse_Inbox/tpls/' . $this->view . '.tpl');
            if($isBpm){
                $tpl = get_custom_file_if_exists('modules/pmse_Inbox/tpls/BpmView.tpl');
            }
            $this->th = new TemplateHandler();
            $this->th->ss = &$this->ss;
            $this->tpl = $tpl;

            if ($this->th->checkTemplate($this->bean->module_name, $this->view)) {
                $this->th->deleteTemplate($this->bean->module_name, $this->view);
            }

            $this->ev = new EditView();
            $this->ev->ss =& $this->ss;
            $this->ev->module = $module;
            $this->ev->th = $this->th;
            $this->ev->focus = $this->bean;
            $this->ev->defs = $this->defs;
            $this->ev->view = $this->view;
            $this->ev->process();
            $this->fieldDefs = $this->ev->fieldDefs;
            $this->sectionPanels = $this->ev->sectionPanels;
            $this->offset = $this->ev->offset;
            $this->returnModule = $this->ev->returnModule;
            $this->returnAction = $this->ev->returnAction;
            $this->returnId = $this->ev->returnId;
            //$this->returnRelationship = $this->ev->returnRelationship;
            //$this->returnName = $this->ev->returnName;

            return $this->setupAll(false, false, $this->bean->module_name, $readonly);
        }
    }

    public function getButtonArray($buttonList = array(), $casId = '', $casIndex = '', $teamId = '', $title='', $idInbox='')
    {
        $buttons = array(
            'claim' => array(
                'id' => 'claimBtn',
                'name' => 'Type',
                'value' => 'Claim',
                'type' => 'button',
                'onclick' => 'javascript:claim_case(\'' . $casId . '\', \'' . $casIndex . '\', \'' .  htmlentities($title, ENT_QUOTES) . '\', \'' . $idInbox . '\');'
            ),
            'approve' => array(
                'id' => 'ApproveBtn',
                'name' => 'Type',
                'value' => 'Approve',
                'type' => 'button',
                'onclick' => "if (app.btSubmitClicked != true) return (check_form('EditView') && confirmAction(this)); else return true;"
            ),
            'reject' => array(
                'id' => 'RejectBtn',
                'name' => 'Type',
                'value' => 'Reject',
                'type' => 'button',
                'onclick' => "if (app.btSubmitClicked != true) return (check_form('EditView') && confirmAction(this)); else return true;"
            ),
            'reassign' => array(
                'id' => 'ReassignBtn',
                'name' => 'Type',
                'value' => 'Reassign',
                'type' => 'button',
                'onclick' => 'reassignForm(\'' . $casId . '\', \'' . $casIndex . '\', \'' . $teamId . '\');'
            ),
            'adhoc' => array(
                'id' => 'AdhocBtn',
                'name' => 'Type',
                'value' => 'Ad-Hoc User',
                'type' => 'button',
                'onclick' => 'adhocForm(\'' . $casId . '\', \'' . $casIndex . '\');'
            ),
            'route' => array(
                'id' => 'RouteBtn',
                'name' => 'Type',
                'value' => 'Route Task',
                'type' => 'button',
                'onclick' => "if (app.btSubmitClicked != true) return (check_form('EditView') && confirmAction(this)); else return true;"
            ),
            'cancel' => array(
                'name' => 'Cancel',
                'value' => 'Cancel',
                'type' => 'button',
                'onclick' => 'parent.App.router.navigate(\'#Home\', {trigger: true})'
            )
        );
        $customButtons = array();

        foreach ($buttonList as $buttonKey => $buttonValue) {
            if ($buttonValue == 'true') {
                switch ($buttonKey) {
                    case 'approve':
                        $customButtons[] = $buttons['approve'];
                        $customButtons[] = $buttons['reject'];
                        break;
                    case 'reassign':
                        $customButtons[] = $buttons['reassign'];
                        break;
                    case 'route':
                        $customButtons[] = $buttons['route'];
                        break;
                    case 'adhoc':
                        $customButtons[] = $buttons['adhoc'];
                        break;
                    case 'claim':
                        $customButtons[] = $buttons['claim'];
                        break;
                }
            }
        }
        $customButtons[] = $buttons['cancel'];
        return $customButtons;
    }

    public function display()
    {
        $id_flow = $this->request->getValidInputRequest('id', 'Assert\Guid');
        $time_data = $GLOBALS['timedate'];
        $expected_time = 0;
        $expected_time_warning = false;
        $expected_time_message = '';

        global $current_user;

        //extract cas_id and cas_index
        $beanFlow = BeanFactory::retrieveBean('pmse_BpmFlow', $id_flow, array('encode' => false));
        $cas_id = $beanFlow->cas_id;
        $cas_index = $beanFlow->cas_index;

        $query = new SugarQuery();
        $query->from(BeanFactory::newBean('pmse_Inbox'));
        $query->joinTable('pmse_bpm_flow', array('alias' => 'bpmFlow', 'joinType' => 'LEFT', 'linkingTable' => true))
            ->on()
            ->equalsField('bpmFlow.cas_id', 'pmse_inbox.cas_id');
        $query->joinTable('pmse_bpmn_process', array('alias' => 'bpmnProcess', 'joinType' => 'INNER', 'linkingTable' => true))
            ->on()
            ->equalsField('pmse_inbox.pro_id', 'bpmnProcess.id');

        // Set up the base fields for the select
        $fields = array(
            // Processes fields (pmse_inbox)
            'id', 'cas_id', 'cas_title',
            // Process Definition Name (pmse_bpmn_process)
            'bpmnProcess.name',
        );

        // Now get the flow fields (pmse_bpm_flow)
        $fields = array_merge(
            $fields,
            array(
                'bpmFlow.cas_index','bpmFlow.bpmn_id','bpmFlow.cas_flow_status',
                'bpmFlow.cas_sugar_module','bpmFlow.cas_sugar_object_id',
                'bpmFlow.cas_sugar_action','bpmFlow.cas_adhoc_type',
                'bpmFlow.cas_task_start_date','bpmFlow.cas_delegate_date',
                'bpmFlow.cas_start_date','bpmFlow.cas_due_date',
            )
        );

        $query->select($fields);
        $query->where()->queryAnd()->equals("bpmFlow.cas_id", $cas_id)->equals("bpmFlow.cas_index", $cas_index);
        $record = $query->execute();
        $caseData = current($record);

        $totalNotes = 0;

        $smarty = new Sugar_Smarty();

        $smarty->assign('caseData', $caseData);
        $simpleRouting = false;

        switch ($caseData['cas_flow_status']) {
            case 'FORM':
                //TODO: if form still having two differents forms, depending of act_task_type
                global $sugar_config;

                //FORM TEMPLATE SECTION
                global $db;
$select = <<<FLIST
                pmse_bpmn_activity.id, pmse_bpmn_activity.name actiname,  pmse_bpmn_activity.date_entered,
                pmse_bpm_activity_definition.name,
                pmse_bpmn_activity.date_modified, pmse_bpmn_activity.modified_user_id, pmse_bpmn_activity.created_by,
                pmse_bpmn_activity.description, pmse_bpmn_activity.deleted, pmse_bpmn_activity.assigned_user_id, act_uid prj_id,
                pmse_bpm_activity_definition.pro_id, pmse_bpm_activity_definition.act_type, act_is_for_compensation, act_start_quantity,
                act_completion_quantity, act_task_type, act_implementation, act_instantiate, act_script_type, act_script,
                act_loop_type, act_test_before, act_loop_maximum, act_loop_condition, act_loop_cardinality, act_loop_behavior,
                act_is_adhoc, act_is_collapsed, act_completion_condition, act_ordering, act_cancel_remaining_instances, act_protocol,
                act_method, act_is_global, act_referer, act_default_flow, act_master_diagram, act_duration, act_duration_unit,
                act_send_notification, act_assignment_method, act_assign_team, act_assign_user, act_value_based_assignment, act_reassign,
                act_reassign_team, act_adhoc, act_adhoc_behavior,act_adhoc_team, act_response_buttons,act_last_user_assigned,
                act_field_module,act_fields,act_readonly_fields,act_expected_time,act_required_fields, act_related_modules,
                act_service_url,act_service_params,act_service_method,act_update_record_owner,execution_mode
FLIST;
                $sql = "SELECT $select
                    FROM pmse_bpmn_activity
                    INNER JOIN pmse_bpm_activity_definition
                        ON pmse_bpm_activity_definition.id = pmse_bpmn_activity.id
                    WHERE pmse_bpmn_activity.id = '{$caseData['bpmn_id']}'";
                $resultActi = $db->Query($sql);


                $this->activityRow = $db->fetchByAssoc($resultActi);
                $activityName = $this->activityRow['actiname'];
                $taskName = $this->activityRow['name'];
                $smarty->assign('nameTask', $taskName);
                $smarty->assign('flowId', $id_flow);

                //DUE DATE SECCION
                $data_aux = new stdClass();
                $data_aux->cas_task_start_date = $caseData['cas_task_start_date'];
                $data_aux->cas_delegate_date = $caseData['cas_delegate_date'];
                // Commenting out below line. We don't want due date to be calculated dynamically. Once a process due date is set it should stay.
                // $expTime = PMSECaseWrapper::expectedTime($this->activityRow['act_expected_time'], $data_aux);
                $caseWrapper = ProcessManager\Factory::getPMSEObject('PMSECaseWrapper');
                $expTime = $caseWrapper->processDueDateTime($caseData['cas_due_date']);
                $expected_time = $expTime['expected_time'];
                $expected_time_warning = $expTime['expected_time_warning'];
                if ($expected_time_warning == true) {
                    $expected_time_message = "Overdue";
                } else {
                    $expected_time_message = "Due Date";
                }

                $displayMode = array('displayMode' => 'bpm', 'dyn_uid' => $this->activityRow['act_type']);
                //INIT CLAIM CASE AND DEFINE DISPLAY MODE
                $cat = $caseData['cas_adhoc_type'];
                $csd = $caseData['cas_start_date'];
                $cam = $this->activityRow['act_assignment_method'];

                $reclaimCaseByUser = $cat == '' && $csd == '' && $cam == 'selfservice';

                $beanTemplate = $this->displayDataForm($caseData['cas_sugar_module'], $caseData['cas_sugar_object_id'],
                    $displayMode, $reclaimCaseByUser);
                if ($cat == '' && $csd == '' && $cam == 'selfservice') {
                    $displayMode = 'detail';
                }
                //BUTTON SECTIONS
                $customButtons = array();
                $defaultButtons = $this->getButtonArray(array('approve' => true, 'reject' => true));
                if ($reclaimCaseByUser) {
                    $this->defs['BPM']['buttons']['claim'] = true;
                    $customButtons = array('claim' => true);
                } elseif ($cat == '') {
                    $this->defs['BPM']['buttons']['approve'] = (strtoupper($this->activityRow['act_response_buttons']) == 'APPROVE') ? true : false;
                    $this->defs['BPM']['buttons']['route'] = (strtoupper($this->activityRow['act_response_buttons']) == 'ROUTE') ? true : false;
                } else {
                    $this->defs['BPM']['buttons']['route'] = true;
                }

                $taskContinue = false;
                if (!$reclaimCaseByUser && !empty($beanFlow->cas_adhoc_actions)) {
                    $buttons = json_decode($beanFlow->cas_adhoc_actions);
                    unset($buttons['link_cancel']);
                    unset($buttons['edit']);
                    $continue = array_search('continue', $buttons);
                    if ($continue !== false) {
                        unset($buttons[$continue]);
                        $taskContinue = true;
                    }
                    foreach($buttons as $key => $value) {
                        $this->defs['BPM']['buttons'][$value] = $customButtons[$value] = true;
                    }
                }

                //ASSIGN SECTION
                $smarty->assign('cas_id', $cas_id);
                $smarty->assign('idInbox', $caseData['id']);
                $smarty->assign('cas_index', $cas_index);
                $smarty->assign('cas_current_user_id', $current_user->id);
                $smarty->assign('act_name', $activityName);
                $smarty->assign('act_adhoc_behavior', $this->activityRow['act_adhoc_behavior']);
                $smarty->assign('act_adhoc', $this->activityRow['act_adhoc'] == 1 ? true : false);
                $smarty->assign('act_reassign', $this->activityRow['act_reassign'] == 1 ? true : false);
                $smarty->assign('act_note', true);
                $smarty->assign('expected_time_warning', $expected_time_warning);
                $smarty->assign('expected_time_message', $expected_time_message);
                $smarty->assign('expected_time', $expected_time);
                $smarty->assign('reclaimCaseByUser', $reclaimCaseByUser);
                $smarty->assign('totalNotes', $totalNotes);
                $smarty->assign('task_continue', $taskContinue);
                $smarty->assign('SUGAR_URL', $sugar_config['site_url']);
                $smarty->assign('SUGAR_AJAX_URL',
                    $sugar_config['site_url'] . "/index.php?module=pmse_Inbox&action=ajaxapi");
                $apiSupported = 'false';
                $smarty->assign('SUGAR_REST', $apiSupported);

                //verify if is a claim case form if not add validate fields
                if (!$reclaimCaseByUser) {
                    $valid = $this->validationsRequiredFields();
                    $smarty->assign('validations', $valid);
                } else {
                    $smarty->assign('validations', array());
                }
                $idInbox = isset($caseData['idInbox']) ? $caseData['idInbox'] : null;
                $customButtons = $this->getButtonArray($customButtons, $cas_id, $cas_index,
                    $this->focus->team_id, $caseData['cas_title'], $idInbox);
                if (count($customButtons) > 1) {
                    $smarty->assign('customButtons', $customButtons);
                } else {
                    $smarty->assign('customButtons', $defaultButtons);
                }

                //TPL SECTION

                $openHeaderTemplate = 'modules/pmse_Inbox/tpls/showCaseRoute.tpl';

                $closeHeaderTemplate = 'modules/pmse_Inbox/tpls/showCaseCloseHeader.tpl';
                $openFooterTemplate = 'modules/pmse_Inbox/tpls/showCaseOpenFooter.tpl';
                $closeFooterTemplate = 'modules/pmse_Inbox/tpls/showCaseRouteFooter.tpl';

                //DISPLAY SECTION
                $smarty->display($openHeaderTemplate);
                if ($displayMode == 'detail') {
                    $smarty->display($closeHeaderTemplate);
                }


                // Displaying the Bean Form filled with data
                echo $beanTemplate;

                if ($displayMode == 'detail') {
                    $smarty->display($openFooterTemplate);
                }

                $smarty->display($closeFooterTemplate);

                break;
            default:
                global $sugar_config;
                $smarty->assign('siteUrl', $sugar_config['site_url']);
                $smarty->display('modules/pmse_Inbox/tpls/showCaseDefault.tpl');
                break;
        }
    }

    protected function setupOriginalEditView () {
        global $mod_strings;
        if (isset($this->bean) && isset($this->th->ss)) {
            $mod_strings = array_merge($mod_strings, return_module_language($GLOBALS['current_language'], $this->bean->module_name));
            $moduleView = ViewFactory::loadView('edit', $this->bean->module_dir, $this->bean);
            $moduleView->bean = $this->bean;
            $moduleView->ss = $this->th->ss;
            $moduleView->ss->assign("exclude_default_footer", true);
            //Set the view to use an Empty Sugarview to prevent the display of any data. We only want to populate
            //the bean and smarty template
            $mockEv =  new SugarView();
            $mockEv->isDuplicate = false;
            $moduleView->ev = $mockEv;
            $moduleView->display();
            //Include the JSLanguage for both pmse_Inbox and the target module
            echo $moduleView->_getModLanguageJS();
            $moduleView->module = $this->bean->module_dir;
            echo $moduleView->_getModLanguageJS();
        }
    }

    public function setupAll($showTitle = false, $ajaxSave = false, $moduleName = '', $readonly = false)
    {
        global $mod_strings, $sugar_config, $app_strings, $app_list_strings, $theme, $current_user;

        if (isset($this->defs['templateMeta']['javascript'])) {
            if (is_array($this->defs['templateMeta']['javascript'])) {
                $this->th->ss->assign('externalJSFile', $this->defs['templateMeta']['javascript']);
            } else {
                $this->th->ss->assign('scriptBlocks', $this->defs['templateMeta']['javascript']);
            }
        }

        $this->setupOriginalEditView();

        $this->th->ss->assign('id', $this->fieldDefs['id']['value']);
        $this->th->ss->assign('offset', $this->offset + 1);
        $this->th->ss->assign('APP', $app_strings);
        $this->th->ss->assign('MOD', $mod_strings);
        $this->th->ss->assign('footerTpl', isset($this->defs['templateMeta']['form']['footerTpl']) ? $this->defs['templateMeta']['form']['footerTpl'] : null);
        $this->fieldDefs = $this->setDefaultAllFields($this->fieldDefs); // default editview
        if ($readonly) {
            $this->fieldDefs = $this->setReadOnlyAllFields($this->fieldDefs);
        } else {
            $this->fieldDefs = $this->processReadOnlyFields($this->fieldDefs);
            $this->fieldDefs = $this->processRequiredFields($this->fieldDefs);
        }
        $this->th->ss->assign('fields', $this->fieldDefs);
        $this->th->ss->assign('detailView', $readonly);
        $this->sectionPanels = $this->processSectionPanels($this->sectionPanels);
        $this->th->ss->assign('sectionPanels', $this->sectionPanels);
        $this->th->ss->assign('config', $sugar_config);
        $this->th->ss->assign('returnModule', $this->returnModule);
        $this->th->ss->assign('returnAction', $this->returnAction);
        $this->th->ss->assign('returnId', $this->returnId);
        $this->th->ss->assign('isDuplicate', $this->isDuplicate);
        $this->th->ss->assign('def', $this->defs);
        $this->th->ss->assign('useTabs',
            isset($this->defs['templateMeta']['useTabs']) && isset($this->defs['templateMeta']['tabDefs']) ? $this->defs['templateMeta']['useTabs'] : false);
        $this->th->ss->assign('maxColumns',
            isset($this->defs['templateMeta']['maxColumns']) ? $this->defs['templateMeta']['maxColumns'] : 2);
        $this->th->ss->assign('module', $moduleName);
        $this->th->ss->assign('current_user', $current_user);
        $this->th->ss->assign('bean', $this->focus);
        $this->th->ss->assign('gridline', $current_user->getPreference('gridline') == 'on' ? '1' : '0');
        $this->th->ss->assign('tabDefs',
            isset($this->defs['templateMeta']['tabDefs']) ? $this->defs['templateMeta']['tabDefs'] : false);
        $this->th->ss->assign('VERSION_MARK', getVersionedPath(''));

        if (isset($this->relationshipChart[$moduleName])) {
            foreach($this->relationshipChart[$moduleName] as $relName => $ssName) {
                $this->bean->load_relationship($relName);
                $tempBeanList = $this->bean->$relName->getBeans();
                $this->th->ss->assign($ssName, $tempBeanList);
            }
        }

        global $js_custom_version;
        global $sugar_version;

        $this->th->ss->assign('SUGAR_VERSION', $sugar_version);
        $this->th->ss->assign('JS_CUSTOM_VERSION', $js_custom_version);

        //this is used for multiple forms on one page
        if (!empty($this->formName)) {
            $form_id = $this->formName;
            $form_name = $this->formName;
        } else {
            $form_id = $this->view;
            $form_name = $this->view;
        }

        if ($ajaxSave && empty($this->formName)) {
            $form_id = 'form_' . $this->view . '_' . $moduleName;
            $form_name = $form_id;
            $this->view = $form_name;
       }

        $form_name = $form_name == 'QuickCreate' ? "QuickCreate_{$moduleName}" : $form_name;
        $form_id = $form_id == 'QuickCreate' ? "QuickCreate_{$moduleName}" : $form_id;

        if (isset($this->defs['templateMeta']['preForm'])) {
            $this->th->ss->assign('preForm', $this->defs['templateMeta']['preForm']);
        }

        if (isset($this->defs['templateMeta']['form']['closeFormBeforeCustomButtons'])) {
            $this->th->ss->assign('closeFormBeforeCustomButtons',
                $this->defs['templateMeta']['form']['closeFormBeforeCustomButtons']);
        }

        if (isset($this->defs['templateMeta']['form']['enctype'])) {
            $this->th->ss->assign('enctype', 'enctype="' . $this->defs['templateMeta']['form']['enctype'] . '"');
        }

        //for SugarFieldImage, we must set form enctype to "multipart/form-data"
        foreach ($this->fieldDefs as $field) {
            if (isset($field['type']) && $field['type'] == 'image') {
                $this->th->ss->assign('enctype', 'enctype="multipart/form-data"');
                break;
            }
        }

        $this->th->ss->assign('showDetailData', $this->showDetailData);
        $this->th->ss->assign('showSectionPanelsTitles', $this->showSectionPanelsTitles);
        $this->th->ss->assign('form_id', $form_id);
        $this->th->ss->assign('form_name', $form_name);//$form_name change by id form showCaseForm
        $this->th->ss->assign('set_focus_block', get_set_focus_js());

        $this->th->ss->assign('form',
            isset($this->defs['templateMeta']['form']) ? $this->defs['templateMeta']['form'] : null);
        $this->th->ss->assign('includes',
            isset($this->defs['templateMeta']['includes']) ? $this->defs['templateMeta']['includes'] : null);
        $this->th->ss->assign('view', $this->view);


        $admin = new Administration();
        $admin->retrieveSettings();
        if (isset($admin->settings['portal_on']) && $admin->settings['portal_on']) {
            $this->th->ss->assign("PORTAL_ENABLED", true);
        } else {
            $this->th->ss->assign("PORTAL_ENABLED", false);
        }

        //Calculate time & date formatting (may need to calculate this depending on a setting)
        global $timedate;

        $this->th->ss->assign('CALENDAR_DATEFORMAT', $timedate->get_cal_date_format());
        $this->th->ss->assign('USER_DATEFORMAT', $timedate->get_user_date_format());
        $time_format = $timedate->get_user_time_format();
        $this->th->ss->assign('TIME_FORMAT', $time_format);

        $date_format = $timedate->get_cal_date_format();
        $time_separator = ':';
        if (preg_match('/\d+([^\d])\d+([^\d]*)/s', $time_format, $match)) {
            $time_separator = $match[1];
        }

        // Create Smarty variables for the Calendar picker widget
        $t23 = strpos($time_format, '23') !== false ? '%H' : '%I';
        if (!isset($match[2]) || $match[2] == '') {
            $this->th->ss->assign('CALENDAR_FORMAT', $date_format . ' ' . $t23 . $time_separator . '%M');
        } else {
            $pm = $match[2] == 'pm' ? '%P' : '%p';
            $this->th->ss->assign('CALENDAR_FORMAT', $date_format . ' ' . $t23 . $time_separator . '%M' . $pm);
        }

        $this->th->ss->assign('CALENDAR_FDOW', $current_user->get_first_day_of_week());
        $this->th->ss->assign('TIME_SEPARATOR', $time_separator);

        $seps = get_number_seperators();
        $this->th->ss->assign('NUM_GRP_SEP', $seps[0]);
        $this->th->ss->assign('DEC_SEP', $seps[1]);

        if ($this->view == 'EditView' || $this->view == 'BpmView') {
            $height = $current_user->getPreference('text_editor_height');
            $width = $current_user->getPreference('text_editor_width');

            $height = isset($height) ? $height : '300px';
            $width = isset($width) ? $width : '95%';

            $this->th->ss->assign('RICH_TEXT_EDITOR_HEIGHT', $height);
            $this->th->ss->assign('RICH_TEXT_EDITOR_WIDTH', $width);
        } else {
            $this->th->ss->assign('RICH_TEXT_EDITOR_HEIGHT', '100px');
            $this->th->ss->assign('RICH_TEXT_EDITOR_WIDTH', '95%');
        }

        $this->th->ss->assign('SHOW_VCR_CONTROL', $this->showVCRControl);

        $str = $this->showTitle($showTitle);
        $ajaxSave = false;
        //Use the output filter to trim the whitespace
        $this->th->ss->load_filter('output', 'trimwhitespace');
        $form_name = $this->view;
        if ($this->th->checkTemplate($this->bean->module_name, $this->view) && !empty($this->dyn_uid)) {
            $nameTemplateTmp = $this->dyn_uid;
        } else {
            $nameTemplateTmp = 'PMSEDetailView';
        }

        foreach ($this->fieldDefs as $field) {
            if (isset($field['viewType']) && ($field['viewType'] == 'DetailView') && !empty($field['name'])) {
                $arrReadOnlyFields[] = $field['name'];
            }
            if (!empty($field['required'])) {
                $arrRequiredFields[] = $field['name'];
            }
        }

        if (isset($arrReadOnlyFields)) {
            $this->th->ss->assign('readOnlyFields', $arrReadOnlyFields);
        }
        if (isset($arrRequiredFields)) {
            $this->th->ss->assign('requiredFields', $arrRequiredFields);
        }
        $this->th->buildTemplate($this->bean->module_name, $nameTemplateTmp, $this->tpl, $ajaxSave, $this->defs);
        $this->th->deleteTemplate($this->bean->module_name, $form_name);
        $newTplFile = $this->th->cacheDir . $this->th->templateDir . $this->bean->module_name . '/' . $nameTemplateTmp . '.tpl';
        $str .= $this->th->ss->fetch($newTplFile);
        return $str;
    }

    /**
     * Look for edit footers and headers and replace them with the detail version, if it exists. If not, do nothing
     *
     * @param $viewdefs
     */
    public function setHeaderFootersReadOnly(&$viewdefs)
    {
        $tpls = array(
            'footerTpl',
            'headerTpl',
        );

        foreach($tpls as $tpl) {
            if (isset($viewdefs['BpmView']['templateMeta']['form'][$tpl])) {
                $original = $viewdefs['BpmView']['templateMeta']['form'][$tpl];
                $new = '';

                if (strpos($original, 'EditViewFooter.tpl') !== false) {
                    $new = str_replace('EditViewFooter.tpl', 'DetailViewFooter.tpl', $original);
                } else if (strpos($original, 'EditViewHeader.tpl') !== false) {
                    $new = str_replace('EditViewHeader.tpl', 'DetailViewHeader.tpl', $original);
                }

                if (file_exists($new)) {
                    $viewdefs['BpmView']['templateMeta']['form'][$tpl] = $new;
                }
            }
        }
    }

    public function showTitle($showTitle = false)
    {
        global $mod_strings, $app_strings;

        if (is_null($this->viewObject)) {
            $this->viewObject = (!empty($GLOBALS['current_view'])) ? $GLOBALS['current_view'] : new SugarView();
        }

        if ($showTitle) {
            return $this->viewObject->getModuleTitle();
        }

        return '';
    }

    protected function getPanelWithFillers($panel)
    {
        $addFiller = true;
        foreach ($panel as $row) {
            if (count($row) == $this->defs['templateMeta']['maxColumns'] || 1 == count($panel)) {
                $addFiller = false;
                break;
            }
        }

        if ($addFiller) {
            $rowCount = count($panel);
            $filler = count($panel[$rowCount - 1]);
            while ($filler < $this->defs['templateMeta']['maxColumns']) {
                $panel[$rowCount - 1][$filler++] = array('field' => array('name' => ''));
            }
        }
        return $panel;
    }

    public function processReadOnlyFields($fieldDefs)
    {
        if (!empty($fieldDefs) && !empty($this->activityRow)) {

            $readOnlyFields = array();
            if (isset($this->activityRow['act_readonly_fields'])) {
                $readOnlyFields = json_decode(base64_decode($this->activityRow['act_readonly_fields']));
            }

            foreach ($fieldDefs as $fieldKey => $field) {
                if (!empty($readOnlyFields) && in_array($fieldKey, $readOnlyFields)) {
                    $fieldDefs[$fieldKey]['viewType'] = 'DetailView';
                } else {
                    $fieldDefs[$fieldKey]['viewType'] = 'EditView';
                }
            }
            return $fieldDefs;
        } else {
            return $fieldDefs;
        }
    }

    public function setDefaultAllFields($fieldDefs)
    {
        foreach ($fieldDefs as $fieldKey => $field) {
            $fieldDefs[$fieldKey]['viewType'] = 'EditView';
        }
        return $fieldDefs;
    }


    public function setReadOnlyAllFields($fieldDefs)
    {
        foreach ($fieldDefs as $fieldKey => $field) {
            $fieldDefs[$fieldKey]['viewType'] = 'DetailView';
        }
        return $fieldDefs;
    }

    public function processRequiredFields($fieldDefs)
    {
        if (!empty($fieldDefs) && !empty($this->activityRow)) {

            $requiredFields = array();
            if (isset($this->activityRow['act_required_fields'])) {
                $requiredFields = json_decode(base64_decode($this->activityRow['act_required_fields']));
            }

            foreach ($fieldDefs as $fieldKey => $field) {
                if (!empty($requiredFields) && in_array($fieldKey, $requiredFields)) {
                    $fieldDefs[$fieldKey]['required'] = true;
                    $fieldDefs[$fieldKey]['importable'] = 'required';
                }
            }
            return $fieldDefs;
        } else {
            return $fieldDefs;
        }
    }

    public function validationsRequiredFields()
    {
        $requiredFields = '';
        foreach ($this->fieldDefs as $fieldKey => $field) {
            if (isset($this->fieldDefs[$fieldKey]['required'])
                && $this->fieldDefs[$fieldKey]['required']
                && $field['viewType'] != 'DetailView') {
                $requiredFields .= '"' . $this->fieldDefs[$fieldKey]['name'] . '",';
            }
        }
        if (!empty($requiredFields)) {
            $requiredFields = '[' . substr($requiredFields, 0, -1) . ']';
        }
        return $requiredFields;
    }

    public function processSectionPanels($panels)
    {
        $readOnlyFields = array();
        if (isset($this->activityRow['act_readonly_fields'])) {
            $readOnlyFields = json_decode(base64_decode($this->activityRow['act_readonly_fields']));
        }
        foreach ($panels as $panelKey => $panel) {
            foreach ($panel as $rowKey => $row) {
                foreach ($row as $fieldKey => $field) {
                    if (!empty($readOnlyFields) && in_array($field['field']['name'], $readOnlyFields)) {
                        $panels[$panelKey][$rowKey][$fieldKey]['field']['hideLabel'] = 0;
                        if (!empty($panels[$panelKey][$rowKey][$fieldKey]['colspan'])) {
                            $panels[$panelKey][$rowKey][$fieldKey]['colspan'] = $panels[$panelKey][$rowKey][$fieldKey]['colspan'] - 1;
                        }
                    }
                }
            }
        }
        return $panels;
    }


    protected function _displaySubPanels()
    {
        if (!empty($this->bean->id) &&
            (SugarAutoLoader::existingCustom('modules/' . $this->module . '/metadata/subpaneldefs.php') ||
                SugarAutoLoader::loadExtension("layoutdefs", $this->module))
        ) {
            $GLOBALS['focus'] = $this->bean;
            $subpanel = new SubPanelTiles($this->bean, $this->module);
            echo $subpanel->display();
        }
    }


    public function getAjaxRelationships($relationships)
    {
        $ajaxrels = array();
        $relationshipList = $relationships->getRelationshipList();
        foreach ($relationshipList as $relationshipName) {
            $rel = $relationships->get($relationshipName)->getDefinition();
            $rel ['lhs_module'] = translate($rel['lhs_module']);
            $rel ['rhs_module'] = translate($rel['rhs_module']);

            //#28668  , translate the relationship type before render it .
            switch ($rel['relationship_type']) {
                case 'one-to-one':
                    $rel['relationship_type_render'] = translate('LBL_ONETOONE');
                    break;
                case 'one-to-many':
                    $rel['relationship_type_render'] = translate('LBL_ONETOMANY');
                    break;
                case 'many-to-one':
                    $rel['relationship_type_render'] = translate('LBL_MANYTOONE');
                    break;
                case 'many-to-many':
                    $rel['relationship_type_render'] = translate('LBL_MANYTOMANY');
                    break;
                default:
                    $rel['relationship_type_render'] = '';
            }
            $rel ['name'] = $relationshipName;
            if ($rel ['is_custom'] && isset($rel ['from_studio']) && $rel ['from_studio']) {
                $rel ['name'] = $relationshipName . "*";
            }
            $ajaxrels [] = $rel;
        }
        return $ajaxrels;
    }
}
