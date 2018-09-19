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
 * Collection of beans related to the primary bean by means of multiple links
 */
class RelateCollectionDefinition extends AbstractCollectionDefinition
{
    /**
     * The key in collection definition that identifies sources
     *
     * @var string
     */
    protected static $sourcesKey = 'links';

    /**
     * Primary bean
     *
     * @var SugarBean
     */
    protected $bean;

    /**
     * Constructor
     *
     * @param SugarBean $bean Primary bean
     * @param string $name Collection name
     */
    public function __construct(SugarBean $bean, $name)
    {
        $this->bean = $bean;

        parent::__construct($name);
    }

    /** {@inheritDoc} */
    public function getSourceModuleName($source)
    {
        if (!$this->bean->load_relationship($source)) {
            throw new SugarApiExceptionError(
                $this->getErrorMessage('Unable to load link %s', array($source))
            );
        }

        $moduleName = $this->bean->$source->getRelatedModuleName();

        return $moduleName;
    }

    /** {@inheritDoc} */
    protected function loadDefinition()
    {
        $definition = $this->bean->getFieldDefinition($this->name);
        if (!is_array($definition) || !isset($definition['type']) || $definition['type'] !== 'collection') {
            throw new SugarApiExceptionNotFound('Collection not found');
        }

        return $definition;
    }

    /**
     * {@inheritDoc}
     *
     * Adds primary module name to the error message
     */
    protected function getErrorMessage($format, array $arguments)
    {
        return parent::getErrorMessage($format, $arguments) . ' in module ' . $this->bean->module_name;
    }
}
