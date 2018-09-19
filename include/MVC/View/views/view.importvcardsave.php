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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class ViewImportvcardsave extends SugarView
{
    public $type = 'save';

    /**
     * @see SugarView::display()
     */
    public function display()
    {
        $redirect = "index.php?action=Importvcard&module={$_REQUEST['module']}";

        if (!empty($_FILES['vcard'])
            && is_uploaded_file($_FILES['vcard']['tmp_name'])
            && $_FILES['vcard']['error'] == 0
        ) {
            $vcard = new vCard();
            try {
                $record = $vcard->importVCard($_FILES['vcard']['tmp_name'], $_REQUEST['module']);
            } catch (Exception $e) {
                SugarApplication::redirect($redirect . '&error=vcardErrorRequired');
            }

            SugarApplication::redirect("index.php?action=DetailView&module={$_REQUEST['module']}&record=$record");
        } else {
            switch ($_FILES['vcard']['error']) {
                case UPLOAD_ERR_FORM_SIZE:
                    $redirect .= "&error=vcardErrorFilesize";
                    break;
                default:
                    $redirect .= "&error=vcardErrorDefault";
                    $GLOBALS['log']->info('Upload error code: ' . $_FILES['vcard']['error'] . '.');
                    break;
            }

            SugarApplication::redirect($redirect);
        }
    }
}
