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


/**
 * The ADAMDynaForm class is a helper class wich have some functionality delegated
 * in order to create default dynaforms
 *
 * @codeCoverageIgnore
 * @deprecated since version pmse2
 */
class PMSEDynaForm
{
    /**
     * The Bpm Dynaform object.
     * @var \BpmDynaForm
     */
    protected $dynaform;

    /**
     * Generally the instance of the module that the dynaform belongs to.
     * @var string
     */
    protected $baseModule;

    /**
     * The class constructor.
     */
    public function __construct()
    {
        $this->dynaform = BeanFactory::newBean('pmse_BpmDynaForm'); //new BpmDynaForm();
    }

    /**
     * Gets the dynaform object.
     * @return \BpmDynaForm
     * @codeCoverageIgnore
     */
    public function getDynaForm()
    {
        return $this->dynaform;
    }

    /**
     * Sets the dynaform object
     * @param \BpmDynaForm $dynaForm
     * @codeCoverageIgnore
     */
    public function setDynaForm($dynaForm)
    {
        $this->dynaform = $dynaForm;
    }

    /**
     * Gets the base module name
     * @return string
     * @codeCoverageIgnore
     */
    public function getBaseModule()
    {
        return $this->baseModule;
    }

    /**
     * Sets the base module object which is a string
     * @param string $baseModule
     * @codeCoverageIgnore
     */
    public function setBaseModule($baseModule)
    {
        $this->baseModule = $baseModule;
    }

    /**
     * Generates a default dynaform based in the base module name.
     * @param string $baseModule
     * @param array $keys
     * @param boolean $update
     * @return boolean
     */
    public function generateDefaultDynaform($baseModule, $keys, $update = false)
    {
        $this->baseModule = $baseModule;
        $params = array();
        if ($update) {
            $this->dynaform->retrieve_by_string_fields(array(
                    'prj_id' => $keys['prj_id'],
                    'pro_id' => $keys['pro_id'],
                    'name' => 'Default'
                ));
        } else {
            $params['dyn_uid'] = PMSEEngineUtils::generateUniqueID();
        }
        $params['name'] = 'Default';
        $params['description'] = 'Default';
        $params['prj_id'] = isset($keys['prj_id']) ? $keys['prj_id'] : null;
        $params['pro_id'] = isset($keys['pro_id']) ? $keys['prj_id'] : null;
        $params['dyn_module'] = $this->baseModule;
        $params['dyn_name'] = "Default";
        $params['dyn_description'] = "Default";
        $moduleViewDefs = get_custom_file_if_exists('modules/' . $baseModule . '/metadata/editviewdefs.php');
        $viewdefs = array();
        if (!@include_once($moduleViewDefs)) {
            return false;
        } else {
            $params['dyn_view_defs'] = array('BpmView' => $viewdefs[$baseModule]['EditView']);
            return $this->saveDynaform($baseModule, $params);
        }

    }

    /**
     * Save the dynaform data in a BpmDynaform record
     * @param string $baseModule
     * @param array $params
     * @param boolean $newWithId
     * @return \BpmDynaForm
     */
    public function saveDynaform($baseModule, $params, $newWithId = false)
    {
        $this->baseModule = $baseModule;
        foreach ($params as $key => $value) {
            $this->dynaform->$key = $value;
            if ($key == 'dyn_view_defs') {
                $this->dynaform->$key = json_encode($value);
            }
        }
        $this->dynaform->dyn_module = $this->baseModule;
        $this->dynaform->new_with_id = $newWithId;
        $this->dynaform->save();
        return $this->dynaform;
    }
}
