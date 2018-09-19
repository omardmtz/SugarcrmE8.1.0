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


use Sugarcrm\Sugarcrm\ProcessManager;

class PMSEProjectCRUDApi extends ModuleApi
{
    public function registerApiRest() {
        return array(
            'create' => array(
                'reqType' => 'POST',
                'path' => array('pmse_Project'),
                'pathVars' => array('module'),
                'method' => 'createRecord',
//                'shortHelp' => 'This method create a new Process Definition record',
            ),
            'update' => array(
                'reqType' => 'PUT',
                'path' => array('pmse_Project','?'),
                'pathVars' => array('module','record'),
                'method' => 'updateRecord',
//                'shortHelp' => 'This method updates a Process Definition record',
            ),
            'delete' => array(
                'reqType' => 'DELETE',
                'path' => array('pmse_Project','?'),
                'pathVars' => array('module','record'),
                'method' => 'deleteRecord',
//                'shortHelp' => 'This method deletes a Process Definition record',
            ),
        );
    }

    public function deleteRecord(ServiceBase $api, array $args)
    {
        $this->requireArgs($args,array('module','record'));

        $projectBean = BeanFactory::getBean($args['module'], $args['record']);
        $projectBean->prj_status = 'INACTIVE';
        $projectBean->save();

        $diagramBean =  BeanFactory::newBean('pmse_BpmnDiagram')->retrieve_by_string_fields(array('prj_id'=>$args['record']));
        $diagramBean->deleted = 1;
        $diagramBean->save();

        $processBean = BeanFactory::newBean('pmse_BpmnProcess')->retrieve_by_string_fields(array('prj_id'=>$args['record']));
        $processBean->deleted = 1;
        $processBean->save();

        $processDefinitionBean = BeanFactory::newBean('pmse_BpmProcessDefinition')->retrieve_by_string_fields(array('prj_id'=>$args['record']));
        $processDefinitionBean->deleted = 1;
        $processDefinitionBean->save();

        while($relatedDepBean = BeanFactory::newBean('pmse_BpmRelatedDependency')->retrieve_by_string_fields(array('prj_id'=>$args['record'], 'deleted'=>0))) {
            $relatedDepBean->deleted = 1;
            $relatedDepBean->save();
        }


        $bean = $this->loadBean($api, $args, 'delete');
        $bean->mark_deleted($args['record']);

        return array('id'=>$bean->id);
    }

    /**
     * @inheritDoc
     */
    protected function saveBean(SugarBean $bean, ServiceBase $api, array $args)
    {
        parent::saveBean($bean, $api, $args);

        $bean->saveRelatedBeans();
    }

    public function createRecord(ServiceBase $api, array $args)
    {
        if (!isset($args['picture_duplicateBeanId'])) {
            return parent::createRecord($api, $args);
        }

        $id = $args['picture_duplicateBeanId'];

        $exporter = ProcessManager\Factory::getPMSEObject('PMSEProjectExporter');
        $project = $exporter->getProject(array('id' => $id));

        $project['project']['name'] =  $args['name'];
        $project['project']['assigned_user_id'] =  $args['assigned_user_id'];
        $project['project']['description'] =  $args['description'];
        $project['project']['prj_status'] = $args['prj_status'];

        $importer = ProcessManager\Factory::getPMSEObject('PMSEProjectImporter');
        $project['_module']['project'] = 'pmse_Project';

        // The importation always changes the project status to INACTIVE except when the case is Copy from a Process Definition
        $savedProject = $importer->saveProjectData($project['project'], true);
        $project['project']['id'] = $savedProject['id'];
        $project['project']['warnings'] = array($savedProject['br_warning'], $savedProject['et_warning']);

        return $project['project'];
    }
}
