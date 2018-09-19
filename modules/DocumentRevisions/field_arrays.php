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
/*********************************************************************************

 * Description:  Contains field arrays that are used for caching
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$fields_array['DocumentRevision'] = array ('column_fields' => Array("id"
		,"change_log"
		,"document_id"
		,"date_entered"
		,"created_by"
		,"filename"
		,"file_ext"
		,"file_mime_type"
		,"revision"
		,"date_modified"
		,"deleted"				
		),
        'list_fields' =>  Array("id"
		,"change_log"
		,"document_id"
		,"date_entered"
		,"created_by"
		,"filename"
		,"file_ext"
		,"file_mime_type"
		,"revision"
		,"date_modified"
		,"deleted"		
		,"latest_revision_id"		
		),
        'required_fields' => Array("revision"=>1),
);
?>