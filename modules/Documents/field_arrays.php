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
$fields_array['Document'] = array ('column_fields' => Array("id"
		,"document_name"
		,"description"
		,"category_id"
		,"subcategory_id"
		,"status_id"
		,"active_date"
		,"exp_date"
		,"date_entered"
		,"date_modified"
		,"created_by"
		,"modified_user_id"
		,"team_id"
		,"document_revision_id"
		,"related_doc_id"
		,"related_doc_rev_id"
		,"is_template"
		,"template_type"
		),
        'list_fields' =>  Array("id"
		,"document_name"
		,"description"
		,"category_id"
		,"subcategory_id"
		,"status_id"
		,"active_date"
		,"exp_date"
		,"date_entered"
		,"date_modified"
		,"created_by"
		,"modified_user_id"
		,"team_id"
		,"document_revision_id"
		,"last_rev_create_date"
		,"last_rev_created_by"
		,"latest_revision"
		,"file_url"
		,"file_url_noimage"
		),
        'required_fields' => Array("document_name"=>1,"active_date"=>1,"revision"=>1),
);
?>