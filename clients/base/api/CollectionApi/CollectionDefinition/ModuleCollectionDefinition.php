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
 * Collection of beans from multiple modules
 */
class ModuleCollectionDefinition extends AbstractCollectionDefinition
{
    /**
     * The key in collection definition that identifies sources
     *
     * @var string
     */
    protected static $sourcesKey = 'modules';

    /** {@inheritDoc} */
    public function getSourceModuleName($source)
    {
        return $source;
    }

    /** {@inheritDoc} */
    protected function loadDefinition()
    {
        global $dictionary;

        self::loadDictionary();
        if (!isset($dictionary['collections'][$this->name]) || !is_array($dictionary['collections'][$this->name])) {
            throw new SugarApiExceptionNotFound('Collection not found');
        }

        return $dictionary['collections'][$this->name];
    }

    /**
     * Loads collection definitions into global dictionary
     */
    protected static function loadDictionary()
    {
        global $dictionary;
        static $loaded = false;

        if (!$loaded) {
            require 'modules/CollectionDictionary.php';
        }
    }
}
