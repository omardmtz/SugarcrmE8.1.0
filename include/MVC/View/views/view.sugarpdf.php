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



class ViewSugarpdf extends SugarView{
    
    var $type ='sugarpdf';
    /**
     * It is set by the "sugarpdf" request parameter and it is use by SugarpdfFactory to load the good sugarpdf class.
     * @var String
     */
    var $sugarpdf='default';
    /**
     * The sugarpdf object (Include the TCPDF object).
     * The atributs of this object are destroy in the output method.
     * @var Sugarpdf object
     */
    var $sugarpdfBean=NULL;

    
    public function __construct()
    {
        parent::__construct();

        if (isset($_REQUEST["sugarpdf"])) {
            $this->sugarpdf = $this->request->getValidInputRequest('sugarpdf', 'Assert\ComponentName');
        } else {
            $module = $this->request->getValidInputRequest('module', 'Assert\Mvc\ModuleName');
            $record = $this->request->getValidInputRequest('record', 'Assert\Guid');
            header('Location:index.php?module=' . $module . '&action=DetailView&record=' . $record);
        }
    }
     
     function preDisplay(){
         $this->sugarpdfBean = SugarpdfFactory::loadSugarpdf($this->sugarpdf, $this->module, $this->bean, $this->view_object_map);
         
         // ACL control
        if(!empty($this->bean) && !$this->bean->ACLAccess($this->sugarpdfBean->aclAction)){
            ACLController::displayNoAccess(true);
            sugar_cleanup(true);
        }
        
        if(isset($this->errors)){
          $this->sugarpdfBean->errors = $this->errors;
        }
     }
     
    function display(){
        $this->sugarpdfBean->process();
        $this->sugarpdfBean->Output($this->sugarpdfBean->fileName,'I');
     }

}

