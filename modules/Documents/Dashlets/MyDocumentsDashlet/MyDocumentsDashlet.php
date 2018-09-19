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


class MyDocumentsDashlet extends DashletGeneric { 

    public function __construct($id, $def = null)
	{
		global $current_user, $app_strings;
		require('modules/Documents/Dashlets/MyDocumentsDashlet/MyDocumentsDashlet.data.php');

        parent::__construct($id, $def);

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'Documents');

        $this->searchFields = $dashletData['MyDocumentsDashlet']['searchFields'];
        $this->columns = $dashletData['MyDocumentsDashlet']['columns'];

        $this->seedBean = BeanFactory::newBean('Documents');        
    }

    function displayOptions() {
        $this->processDisplayOptions();

        $types = getDocumentsExternalApiDropDown();
        $this->currentSearchFields['doc_type']['input'] = '<select size="3" multiple="true" name="doc_type[]">'
	                                              . get_select_options_with_id($types, (empty($this->filters['doc_type']) ? '' : $this->filters['doc_type']))
	                                              . '</select>';
        $this->configureSS->assign('searchFields', $this->currentSearchFields);
        return $this->configureSS->fetch($this->configureTpl);
    }
}
