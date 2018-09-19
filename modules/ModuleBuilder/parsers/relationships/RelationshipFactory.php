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

require_once 'modules/ModuleBuilder/parsers/constants.php' ;

class RelationshipFactory
{

    /*
     * Construct a new relationship of type as provided by the $definition
     * @param array $definition Complete definition of the relationship, as specified by AbstractRelationship::keys
     */
    static function newRelationship ($definition = array())
    {
        // handle the case where a relationship_type is not provided - set it to Many-To-Many as this was the usual type in ModuleBuilder
        if (!isset($definition ['relationship_type'])) {
            $definition['relationship_type'] = MB_MANYTOMANY;
        }
        $type = !empty($definition['true_relationship_type'])
            ? $definition['true_relationship_type']
            : $definition['relationship_type'];
    	if (!empty ($definition['for_activities']) && $definition['for_activities'] == true) {
        	return new ActivitiesRelationship ($definition);
        }
        
        switch (strtolower($type))
        {
            case strtolower ( MB_ONETOONE ) :
                return new OneToOneRelationship ( $definition ) ;
            
            case strtolower ( MB_ONETOMANY ) :
                return new OneToManyRelationship ( $definition ) ;
                
            case strtolower ( MB_MANYTOONE ) :
                return new ManyToOneRelationship ( $definition ) ;
            
            // default case is Many-To-Many as this was the only type ModuleBuilder could create and so much of the MB code assumes Many-To-Many
            default :
                $definition [ 'relationship_type' ] = MB_MANYTOMANY ;
                return new ManyToManyRelationship ( $definition ) ;
        }
    
    }
}

