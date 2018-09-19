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


class RelateRecordApi extends SugarApi
{
    public function registerApiRest() {
        return array(
            'fetchRelatedRecord' => array(
                'reqType'   => 'GET',
                'path'      => array('<module>','?',     'link','?',        '?'),
                'pathVars'  => array('module',  'record','',    'link_name','remote_id'),
                'method'    => 'getRelatedRecord',
                'shortHelp' => 'Fetch a single record related to this module',
                'longHelp'  => 'include/api/help/module_record_link_link_name_remote_id_get_help.html',
            ),
            'createRelatedRecord' => array(
                'reqType'   => 'POST',
                'path'      => array('<module>','?',     'link','?'),
                'pathVars'  => array('module',  'record','',    'link_name'),
                'method'    => 'createRelatedRecord',
                'shortHelp' => 'Create a single record and relate it to this module',
                'longHelp'  => 'include/api/help/module_record_link_link_name_post_help.html',
            ),
            'createRelatedLink' => array(
                'reqType'   => 'POST',
                'path'      => array('<module>','?',     'link','?'        ,'?'),
                'pathVars'  => array('module',  'record','',    'link_name','remote_id'),
                'method'    => 'createRelatedLink',
                'shortHelp' => 'Relates an existing record to this module',
                'longHelp'  => 'include/api/help/module_record_link_link_name_remote_id_post_help.html',
            ),
            'createRelatedLinks' => array(
                'reqType' => 'POST',
                'path' => array('<module>', '?', 'link'),
                'pathVars' => array('module', 'record', ''),
                'method' => 'createRelatedLinks',
                'shortHelp' => 'Relates existing records to this module.',
                'longHelp' => 'include/api/help/module_record_link_post_help.html',
            ),
            'updateRelatedLink' => array(
                'reqType'   => 'PUT',
                'path'      => array('<module>','?',     'link','?'        ,'?'),
                'pathVars'  => array('module',  'record','',    'link_name','remote_id'),
                'method'    => 'updateRelatedLink',
                'shortHelp' => 'Updates relationship specific information ',
                'longHelp'  => 'include/api/help/module_record_link_link_name_remote_id_put_help.html',
            ),
            'deleteRelatedLink' => array(
                'reqType'   => 'DELETE',
                'path'      => array('<module>','?'     ,'link','?'        ,'?'),
                'pathVars'  => array('module'  ,'record',''    ,'link_name','remote_id'),
                'method'    => 'deleteRelatedLink',
                'shortHelp' => 'Deletes a relationship between two records',
                'longHelp'  => 'include/api/help/module_record_link_link_name_remote_id_delete_help.html',
            ),
            'createRelatedLinksFromRecordList' => array(
                'reqType' => 'POST',
                'path' => array('<module>', '?', 'link', '?', 'add_record_list', '?'),
                'pathVars' => array('module', 'record', '', 'link_name', '', 'remote_id'),
                'method' => 'createRelatedLinksFromRecordList',
                'shortHelp' => 'Relates existing records from a record list to this record.',
                'longHelp' => 'include/api/help/module_record_links_from_recordlist_post_help.html',
            ),
        );
    }


    /**
     * Fetches data from the $args array and updates the bean with that data
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how security is applied
     * @param array $args The arguments array passed in from the API
     * @param $primaryBean SugarBean The near side of the link
     * @param $securityTypeLocal string What ACL to check on the near side of the link
     * @param $securityTypeRemote string What ACL to check on the far side of the link
     * @return array Two elements: The link name, and the SugarBean of the far end
     * @throws SugarApiExceptionNotAuthorized
     * @throws SugarApiExceptionNotFound
     */
    protected function checkRelatedSecurity(
        ServiceBase $api,
        array $args,
        SugarBean $primaryBean,
        $securityTypeLocal = 'view',
        $securityTypeRemote = 'view'
    ) {
        $this->requireArgs($args, array('link_name'));
        if ( ! $primaryBean->ACLAccess($securityTypeLocal) ) {
            throw new SugarApiExceptionNotAuthorized('No access to '.$securityTypeLocal.' records for module: '.$args['module']);
        }
        // Load up the relationship
        $linkName = $args['link_name'];
        if ( ! $primaryBean->load_relationship($linkName) ) {
            // The relationship did not load, I'm guessing it doesn't exist
            throw new SugarApiExceptionNotFound('Could not find a relationship named: '.$args['link_name']);
        }
        // Figure out what is on the other side of this relationship, check permissions
        $linkModuleName = $primaryBean->$linkName->getRelatedModuleName();
        $linkSeed = BeanFactory::newBean($linkModuleName);

        // FIXME: No create ACL yet
        if ( $securityTypeRemote == 'create' ) { $securityTypeRemote = 'edit'; }

        // only check here for edit...view and list are checked on formatBean
        if ( $securityTypeRemote == 'edit' && ! $linkSeed->ACLAccess($securityTypeRemote) ) {
            throw new SugarApiExceptionNotAuthorized('No access to '.$securityTypeRemote.' records for module: '.$linkModuleName);
        }

        return array($linkName, $linkSeed);

    }

    /**
     * This function is used to popluate an fields on the relationship from the request
     *
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how security is applied
     * @param array $args The arguments array passed in from the API
     * @param SugarBean $primaryBean The near side of the link
     * @param string $linkName What is the name of the link field that you want to get the related fields for
     * @param SugarBean $seed Related bean seed
     *
     * @return array A list of the related fields pulled out of the $args array
     */
    protected function getRelatedFields(
        ServiceBase $api,
        array $args,
        SugarBean $primaryBean,
        $linkName,
        SugarBean $seed = null
    ) {
        $relatedData = array();
        if (!empty($primaryBean->$linkName) || $primaryBean->load_relationship($linkName)) {
            $otherLink = $primaryBean->$linkName->getLinkForOtherSide();
            if ($seed instanceof SugarBean) {
                foreach ($args as $field => $value) {
                    if (empty($seed->field_defs[$field]['rname_link']) ||
                        empty($seed->field_defs[$field]['link']) ||
                        $seed->field_defs[$field]['link'] != $otherLink
                    ) {
                        continue;
                    }
                    $relatedData[$seed->field_defs[$field]['rname_link']] = $value;
                }
            }
        }

        return $relatedData;
    }

    /**
     * This function is here temporarily until the Link2 class properly handles these for the non-subpanel requests
     * @param ServiceBase $api The API class of the request, used in cases where the API changes how security is applied
     * @param array $args The arguments array passed in from the API
     * @param $primaryBean SugarBean The near side of the link
     * @param $relatedArray array The data for the related fields (such as the contact_role in opportunities_contacts relationship)
     * @return array Two elements, 'record' which is the formatted version of $primaryBean, and 'related_record' which is the formatted version of $relatedBean
     */
    protected function formatNearAndFarRecords(
        ServiceBase $api,
        array $args,
        SugarBean $primaryBean,
        array $relatedArray
    ) {
        $api->action = 'view';
        $recordArray = $this->formatBean($api, $args, $primaryBean);

        return array(
            'record'=>$recordArray,
            'related_record'=>$relatedArray
        );
    }

    public function getRelatedRecord(ServiceBase $api, array $args)
    {
        $primaryBean = $this->loadBean($api, $args);
        
        list($linkName, $relatedBean) = $this->checkRelatedSecurity($api, $args, $primaryBean, 'view', 'view');

        /** @var Link2 $link */
        $link = $primaryBean->$linkName;

        $related = array_values($link->getBeans(array(
            'where' => array(
                'lhs_field' => 'id',
                'operator' => '=',
                'rhs_value' => $args['remote_id'],
            )
        ), array(
            'erased_fields' => !empty($args['erased_fields']),
        )));

        if (!empty($related[0]->id)) {
            $relatedBean = $related[0];
        } else {
            // fall back to manual retrieval in case if the newly created bean is not related to the primary one
            if (!$relatedBean->retrieve($args['remote_id'])) {
                // Retrieve failed, probably doesn't have permissions
                throw new SugarApiExceptionNotFound('Could not find the related bean');
            }

        }

        return $this->formatBean($api, $args, $relatedBean);
        
    }

    /**
     * Creates a record and relates it to a primary record.
     *
     * @param ServiceBase $api The API class of the request.
     * @param array $args The arguments array passed in from the API.
     * @return array Two elements, 'record' which is the formatted version of $primaryBean, and 'related_record' which is the formatted version of $relatedBean
     * @throws SugarApiExceptionError In case if the module API has improper interface
     */
    public function createRelatedRecord(ServiceBase $api, array $args)
    {
        $primaryBean = $this->loadBean($api, $args);
        list($linkName) = $this->checkRelatedSecurity($api, $args, $primaryBean, 'view','create');

        /** @var Link2 $link */
        $link = $primaryBean->$linkName;
        $module = $link->getRelatedModuleName();
        $moduleApi = $this->getModuleApi($api, $module);
        $moduleApiArgs = $this->getModuleApiArgs($args, $module);
        $relatedBean = $moduleApi->createBean($api, $moduleApiArgs, array(
            'not_use_rel_in_req' => true,
            'new_rel_id' => $primaryBean->id,
            'new_rel_relname' => $link->getLinkForOtherSide(),
        ));

        $args['remote_id'] = $relatedBean->id;

        $relatedArray = $this->getRelatedRecord($api, $args);

        return $this->formatNearAndFarRecords($api, $args, $primaryBean, $relatedArray);
    }

    public function createRelatedLink(ServiceBase $api, array $args)
    {
        $api->action = 'save';
        $args['ids'] = array($args['remote_id']);
        $return = $this->createRelatedLinks($api, $args);
        return array(
            'record' => $return['record'],
            'related_record' => $return['related_records'][0],
        );
    }

    /**
     * Relates existing records to related bean.
     *
     * @param ServiceBase $api The API class of the request.
     * @param array $args The arguments array passed in from the API.
     * @param string $securityTypeLocal What ACL to check on the near side of the link
     * @param string $securityTypeRemote What ACL to check on the far side of the link
     * @return array Array of formatted fields.
     * @throws SugarApiExceptionInvalidParameter If wrong arguments are passed
     * @throws SugarApiExceptionNotFound If bean can't be retrieved.
     */
    public function createRelatedLinks(
        ServiceBase $api,
        array $args,
        $securityTypeLocal = 'view',
        $securityTypeRemote = 'view'
    ) {
        $this->requireArgs($args, array('ids'));
        $ids = $this->normalizeLinkIds($args['ids']);

        $result = array(
            'related_records' => array(),
        );

        $primaryBean = $this->loadBean($api, $args);

        list($linkName) = $this->checkRelatedSecurity(
            $api,
            $args,
            $primaryBean,
            $securityTypeLocal,
            $securityTypeRemote
        );
        $relatedModuleName = $primaryBean->$linkName->getRelatedModuleName();

        foreach ($ids as $id => $additionalValues) {
            $relatedBean = BeanFactory::retrieveBean($relatedModuleName, $id);

            if (!$relatedBean || $relatedBean->deleted) {
                throw new SugarApiExceptionNotFound('Could not find the related bean');
            }

            $relatedData = $this->getRelatedFields($api, $args, $primaryBean, $linkName, $relatedBean);
            $primaryBean->$linkName->add(array($relatedBean), array_merge($relatedData, $additionalValues));

            $result['related_records'][] = $this->formatBean($api, $args, $relatedBean);
        }
        //Clean up any hanging related records.
        SugarRelationship::resaveRelatedBeans();

        $result['record'] = $this->formatBean($api, $args, $primaryBean);

        return $result;
    }

    /**
     * Normalizes related record IDs obtained from API arguments
     *
     * @param mixed $ids
     *
     * @return array Associative array where key is record ID, value is additional link values
     * @throws SugarApiExceptionInvalidParameter
     */
    protected function normalizeLinkIds($ids)
    {
        if (!is_array($ids)) {
            throw new SugarApiExceptionInvalidParameter(
                sprintf('Related record IDs must be array, %s given', gettype($ids))
            );
        }

        $normalized = array();
        foreach ($ids as $record) {
            if (is_array($record)) {
                if (!isset($record['id'])) {
                    throw new SugarApiExceptionInvalidParameter('Related record ID is not specified in array notation');
                }
                $id = $record['id'];
                $additionalValues = $record;
                unset($additionalValues['id']);
            } else {
                $id = $record;
                $additionalValues = array();
            }
            $normalized[$id] = $additionalValues;
        }

        return $normalized;
    }

    public function updateRelatedLink(ServiceBase $api, array $args)
    {
        $api->action = 'save';
        $primaryBean = $this->loadBean($api, $args);

        list($linkName, $relatedBean) = $this->checkRelatedSecurity($api, $args, $primaryBean, 'view','edit');

        // Make sure the link isn't a readonly link
        if (isset($primaryBean->field_defs[$linkName])) {
            $def = $primaryBean->field_defs[$linkName];
            if (isset($def['type']) && $def['type'] == 'link' && !empty($def['readonly'])) {
                throw new SugarApiExceptionNotAuthorized("Cannot update related records on readonly relationships");
            }
        }

        $relatedBean->retrieve($args['remote_id']);
        if ( empty($relatedBean->id) ) {
            // Retrieve failed, probably doesn't have permissions
            throw new SugarApiExceptionNotFound('Could not find the related bean');
        }
        BeanFactory::registerBean($relatedBean);

        // updateBean may remove the relationship. see PAT-337 for details
        $this->updateBean($relatedBean, $api, $args);
        $relatedBean->retrieve($args['remote_id']);

        //if a filename guid exists, then use moduleapi to make final move from tmp dir if applicable
        if (isset($args['filename_guid'])) {
            $moduleApi = $this->getModuleApi($api, $linkName);
            $relArgs = array(
                'module' => $relatedBean->module_name,
                'record' => $relatedBean->id,
                'filename_guid' => $args['filename_guid'],
            );
            $moduleApi->updateRecord($api, $relArgs);
        }

        $relatedArray = array();

        // Make sure there is a related object
        if (!empty($primaryBean->$linkName)) {
            $relObj = $primaryBean->$linkName->getRelationshipObject();
        }

        if (!empty($relObj)) {
            if ($primaryBean->module_name === $relObj->getLHSModule()){
                $lhsBean = $primaryBean;
                $rhsBean = $relatedBean;
            } else {
                $lhsBean = $relatedBean;
                $rhsBean = $primaryBean;
            }
            // If the relationship still exists, we need to save changes to relationship fields
            if ($relObj->relationship_exists($lhsBean, $rhsBean)) {
                $relatedData = $this->getRelatedFields($api, $args, $primaryBean, $linkName, $relatedBean);
                // This function add() is actually 'addOrUpdate'. Here we use it for update only.
                $primaryBean->$linkName->add(array($relatedBean),$relatedData);

                // BR-2964, related objects are not populated
                $primaryBean->$linkName->refreshRelationshipFields($relatedBean);

                // BR-2937 The edit view cache issue for relate documents of a module
                // nomad still needs this related array

                $relatedArray = $this->formatBean($api, $args, $relatedBean);
            }
            // If the relationship has been removed, we don't need to update the relationship fields
            else {
                // Prepare the ralated bean data for formatNearAndFarRecords() below
                $relatedArray = $this->formatBean($api, $args, $relatedBean);
                // This record is unlinked to primary bean
                $relatedArray['_unlinked'] = true;
            }
        }

        //Clean up any hanging related records.
        SugarRelationship::resaveRelatedBeans();

        // This forces a re-retrieval of the bean from the database
        BeanFactory::unregisterBean($relatedBean);

        return $this->formatNearAndFarRecords($api,$args,$primaryBean,$relatedArray);
    }

    public function deleteRelatedLink(ServiceBase $api, array $args)
    {
        $primaryBean = $this->loadBean($api, $args);

        list($linkName, $relatedBean) = $this->checkRelatedSecurity($api, $args, $primaryBean, 'view','view');

        $relatedBean->retrieve($args['remote_id']);
        if ( empty($relatedBean->id) ) {
            // Retrieve failed, probably doesn't have permissions
            throw new SugarApiExceptionNotFound('Could not find the related bean');
        }
        BeanFactory::registerBean($relatedBean);

        $primaryBean->$linkName->delete($primaryBean->id,$relatedBean);
        
        //Clean up any hanging related records.
        SugarRelationship::resaveRelatedBeans();

        // Get fresh copies of primary and related beans so that the newly deleted relationship
        // shows as deleted. See BR-1055, BR-1630
        $primaryBean = BeanFactory::getBean($primaryBean->module_name, $primaryBean->id, array('use_cache' => false));
        $relatedBean = BeanFactory::getBean($relatedBean->module_name, $relatedBean->id, array('use_cache' => false));

        //Because the relationship is now deleted, we need to pass the $relatedBean data into formatNearAndFarRecords
        return $this->formatNearAndFarRecords($api,$args,$primaryBean, $this->formatBean($api, $args, $relatedBean));
    }

    /**
     * Relates existing records to related bean.
     *
     * @param ServiceBase $api The API class of the request.
     * @param array $args The arguments array passed in from the API.
     * @return array Array of formatted fields.
     * @throws SugarApiExceptionNotFound If bean can't be retrieved.
     */
    public function createRelatedLinksFromRecordList(ServiceBase $api, array $args)
    {
        Activity::disable();

        $result = array(
            'related_records' => array(
                'success' => array(),
                'error' => array(),
            ),
        );

        $this->requireArgs($args, array('module', 'record', 'remote_id', 'link_name'));

        $primaryBean = $this->loadBean($api, $args);

        list($linkName) = $this->checkRelatedSecurity($api, $args, $primaryBean, 'view', 'view');

        $recordList = RecordListFactory::getRecordList($args['remote_id']);
        $relatedBeans = $primaryBean->$linkName->add($recordList['records']);

        if ($relatedBeans === true) {
            $result['related_records']['success'] = $recordList['records'];
        } elseif (is_array($relatedBeans)) {
            $result['related_records']['success'] = array_diff($recordList['records'], $relatedBeans);
            $result['related_records']['error']   = $relatedBeans;
        }

        SugarRelationship::resaveRelatedBeans();

        Activity::restoreToPreviousState();
        $result['record'] = $this->formatBean($api, $args, $primaryBean);

        return $result;
    }

    /**
     * Returns the API implementation for the given module
     *
     * @param ServiceBase $api
     * @param string $module Relate module name
     *
     * @return ModuleApi Module API implementation
     */
    protected function getModuleApi(ServiceBase $api, $module)
    {
        $moduleApi = $this->loadModuleApi($api, $module);
        if ($moduleApi instanceof ModuleApi) {
            return $moduleApi;
        }

        return new ModuleApi();
    }

    /**
     * Loads the API implementation for the given module
     *
     * @param ServiceBase $api
     * @param string $module Relate module name
     *
     * @return ModuleApi Module API implementation
     */
    protected function loadModuleApi(ServiceBase $api, $module)
    {
        $templates = array(
            array('custom/modules/%s/clients/%s/api/', 'Custom%sApi'),
            array('modules/%s/clients/%s/api/', '%sApi'),
        );

        foreach ($templates as $template) {
            list($directoryTemplate, $classTemplate) = $template;
            $class = sprintf($classTemplate, $module);
            $file = sprintf($directoryTemplate, $module, $api->platform) . $class . '.php';
            if (file_exists($file)) {
                require_once $file;
                return new $class;
            }
        }

        return null;
    }

    /**
     * Returns arguments for internal call to Module API
     *
     * @param array $args RelateRecord API arguments
     * @param string $module Relate module name
     * @return array Module API arguments
     */
    protected function getModuleApiArgs(array $args, $module)
    {
        $args['relate_module'] = $args['module'];
        $args['relate_record'] = $args['record'];
        $args['module'] = $module;
        unset($args['record']);
        unset($args['link_name']);

        return $args;
    }
}
