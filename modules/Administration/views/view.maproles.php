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

/**
 * Map package ACL roles to the instance's ones
 */
class ViewMapRoles extends SugarView
{
    /**
     * {@inheritDoc}
     *
     * @param bool $browserTitle Ignored
     */
    protected function _getModuleTitleParams($browserTitle = false)
    {
        global $mod_strings;

        return array(
            $mod_strings['LBL_UW_MAP_ACL_ROLES'],
        );
    }

    /** {@inheritDoc} */
    public function display()
    {
        echo $this->getModuleTitle(false);

        $packageRoles = $this->getPackageRoles();
        $instanceRoles = $this->getInstanceRoles();
        $map = $this->getDefaultMap($packageRoles, $instanceRoles);
        $this->ss->assign(array(
            'package_roles' => $packageRoles,
            'instance_roles' => $instanceRoles,
            'map' => $map,
        ));

        echo $this->ss->fetch('modules/Administration/templates/MapRoles.tpl');
    }

    /**
     * Returns instance ACL roles
     */
    protected function getInstanceRoles()
    {
        $result = array('' => translate('LBL_UW_DO_NOT_MAP_ROLE'));
        $roles = MBHelper::getRoles();
        foreach ($roles as $role) {
            $result[$role->id] = $role->name;
        }

        return $result;
    }

    /**
     * Returns package ACL roles
     */
    protected function getPackageRoles()
    {
        $manifest = $this->getManifest();
        if (isset($manifest['installdefs']['roles'])) {
            return $manifest['installdefs']['roles'];
        }

        return array();
    }

    /**
     * Returns current package manifest
     */
    protected function getManifest()
    {
        $post = InputValidation::getService();
        $s_manifest = $post->getValidInputPost('s_manifest', array(
            'Assert\PhpSerialized' => array("base64Encoded" => true)
        ));

        return $s_manifest;
    }

    /**
     * Returns default map of package roles to instance roles
     *
     * @param array $packageRoles Package ACL roles
     * @param array $instanceRoles Instance ACL roles
     *
     * @return array
     */
    protected function getDefaultMap(array $packageRoles, array $instanceRoles)
    {
        $map = array();
        $unmapped = $packageRoles;

        // first, map by ID
        foreach ($unmapped as $packageRoleId => $_) {
            if (isset($instanceRoles[$packageRoleId])) {
                $map[$packageRoleId] = $packageRoleId;
                unset($unmapped[$packageRoleId]);
            }
        }

        // then map by name
        $mapReverse = $map;
        $instanceRolesReverse = array_flip($instanceRoles);
        foreach ($unmapped as $packageRoleId => $name) {
            if (isset($instanceRolesReverse[$name])) {
                $instanceRoleId = $instanceRolesReverse[$name];
                // avoid mapping more than one package role to the same instance role
                if (!isset($mapReverse[$instanceRoleId])) {
                    $map[$packageRoleId] = $instanceRoleId;
                    $mapReverse[$instanceRoleId] = $packageRoleId;
                }
            }
        }

        return $map;
    }
}
