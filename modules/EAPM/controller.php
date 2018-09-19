<?PHP
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

class EAPMController extends SugarController
{
    /**
     * API implementation
     * @var ExternalAPIPlugin
     */
    protected $api;

    var $action_remap = array('detailview'=>'editview', 'DetailView'=>'EditView');

    var $admin_actions = array('listview', 'index');

    //use for check result of Connect
    protected $failed = false;

	public function process() {
		if(!is_admin($GLOBALS['current_user']) && in_array(strtolower($this->action), $this->admin_actions)) {
			$this->hasAccess = false;
		}
		parent::process();
	}

    protected function failed($error)
    {
        SugarApplication::appendErrorMessage($error);
        $GLOBALS['log']->error("Login error: $error");
        $url = 'index.php?module=EAPM&action=EditView&record='.$this->bean->id;

        if($this->return_module == 'Import'){
            $url .= "&application={$this->bean->application}&return_module={$this->return_module}&return_action={$this->return_action}";
        } elseif ($this->failed && empty($this->bean->id) && $this->action === 'Save') {
            //Save recently entered data, when creating a new record
            $redirectArgs = array();
            foreach (array('name', 'url', 'application') as $key) {
                if (!empty($_POST[$key])) {
                    $redirectArgs[$key] = $_POST[$key];
                }
            }
            if (!empty($redirectArgs)) {
                $url .= '&' . http_build_query($redirectArgs);
            }
        }
        return $this->set_redirect($url);
    }

    public function pre_save()
    {
        if (!empty($_POST['password']) && $_POST['password'] == EAPM::$passwordPlaceholder) {
            unset($_POST['password']);
        }
        parent::pre_save();
        $this->api = ExternalAPIFactory::loadAPI($this->bean->application,true);
        if(empty($this->api)) {
            return $this->failed(translate('LBL_AUTH_UNSUPPORTED', $this->bean->module_dir));
        }
        if(empty($this->bean->id)){
            $eapmBean = EAPM::getLoginInfo($this->bean->application,true);
            if($eapmBean){
                SugarApplication::appendErrorMessage(translate('LBL_APPLICATION_FOUND_NOTICE', $this->bean->module_dir));
                $this->bean->id = $eapmBean->id;
            }
        } elseif (!empty($_POST['application'])) {
            $this->failed = true;
            return $this->failed(translate('LBL_CHANGE_APPLICATION_ERROR', $this->bean->module_dir));
        }
        $this->bean->validated = false;
        $this->bean->save_cleanup();
        $this->api->loadEAPM($this->bean);

        if (!$this->bean->deleted && $this->api->authMethod != 'oauth') {
            // OAuth beans have to be handled specially.
            $reply = $this->api->checkLogin();
            if (!$reply['success']) {
                $this->failed = true;
            }
        }
    }

    public function action_save(){
        //Check result of the Connect
        if (!$this->failed) {
            $this->bean->save(!empty($this->bean->notify_on_save));
        }
    }

    protected function post_save()
    {
        global $current_user;

        if(!$this->bean->deleted) {
            // do not load bean here since password is already encoded
            if ( $this->api->authMethod != 'oauth' ) {
                // OAuth beans have to be handled specially.
                
                $reply = $this->api->checkLogin();
                if ( !$reply['success'] ) {
                    return $this->failed(translate('LBL_AUTH_ERROR', $this->bean->module_dir));
                } else {
                    $this->bean->validated();
                }
            }
        }
        if($this->return_module == 'Users'){
            $this->return_action = 'EditView';
        }
        parent::post_save();

        if($this->return_module == 'Import'){
            $this->set_redirect("index.php?module=Import&action=Step1&import_module=". $this->return_action . "&application=" . $this->bean->application);
        }
        if($this->module == 'EAPM') {
            $this->set_redirect('index.php?module=Users&action=EditView&record=' . $current_user->id);
        }
        // Override the redirect location to add the hash
        $this->redirect_url = $this->redirect_url.'#tab5';
        if ( $this->api->authMethod == 'oauth' && !$this->bean->deleted ) {
            // It's OAuth, we have to handle this specially.
            // We need to create a new window to handle the OAuth, and redirect this window back to the edit view
            // So we will handle that in javascript.
            $popup_warning_msg = string_format($GLOBALS['mod_strings']['LBL_ERR_POPUPS_DISABLED'], array($_SERVER['HTTP_HOST']) );
            echo('<script src="'.getJSPath('modules/EAPM/EAPMEdit.js').'" type="text/javascript"></script><script type="text/javascript">EAPMPopupAndRedirect("index.php?module=EAPM&action=oauth&record='.$this->bean->id.'", "'.$this->redirect_url.'", \''.$popup_warning_msg.'\'); </script>');

            // To prevent the normal handler from issuing a header call and destroying our neat little javascript we'll
            // end right here.
            sugar_cleanup(true);
        } elseif ($this->api->authMethod == 'oauth2' && !$this->bean->deleted) {

            $client = $this->api->getClient();
            $loginUrl = $client->createAuthUrl();

            echo '<script src="' . getJSPath('modules/EAPM/EAMPOauth.js') . '" type="text/javascript"></script><script type="text/javascript">EAMPOauth.startOauthAuthentication("' . $loginUrl . '","index.php?module=EAPM&action=oauth&record='.$this->bean->id . '");</script>';

            // To prevent the normal handler from issuing a header call and destroying our neat little javascript we'll
            // end right here.
            sugar_cleanup(true);
        }
    }

    protected function action_oauth()
    {
        if(empty($this->bean->id)) {
            return $this->set_redirect('index.php');
        }
		if(!$this->bean->ACLAccess('save')){
			ACLController::displayNoAccess(true);
			sugar_cleanup(true);
			return true;
		}
        if(empty($_REQUEST['oauth_error'])) {
            $this->api = ExternalAPIFactory::loadAPI($this->bean->application,true);
            $this->api->getConnector();
            $source = $this->api->connector_source;

            if ($source && $source->hasTestingEnabled()) {
                try {
                    if (!$source->test()) {
                        sugar_die(translate('LBL_CONNECT_TEST_FAIL', $this->bean->module_dir));
                    }
                } catch (Exception $e) {
                    sugar_die(translate('LBL_CONNECT_TEST_FAIL', $this->bean->module_dir));
                }
            }

            $reply = $this->api->checkLogin($this->bean);
            if ( !$reply['success'] ) {
                return $this->failed(translate('LBL_AUTH_ERROR', $this->bean->module_dir));
            } else {
                $this->bean->validated();
            }
        }
        
        // This is a tweak so that we can automatically close windows if requested by the external account system
        if ( isset($_REQUEST['closeWhenDone']) && $_REQUEST['closeWhenDone'] == 1 ) {
            $callback = $this->request->getValidInputRequest(
                'callbackFunction',
                array(
                    'Assert\Choice' => array(
                        'choices' => array(
                            'hideExternalDiv',
                            'reload',
                            'close',
                            '',
                        )
                    )
                ),
                ''
            );

            $application = $this->request->getValidInputRequest(
                'application',
                array(
                    'Assert\Choice' => array(
                        'choices' => array_keys(ExternalAPIFactory::loadFullAPIList())
                    )
                )
            );

            if (!empty($callback) && !empty($application)) {
                $js = printf(
                    "<script type=\"text/javascript\">window.opener.%s(\"%s\"); window.close();</script>",
                    $callback,
                    $application
                );
            }else if(!empty($_REQUEST['refreshParentWindow'])){
                $js = '<script type="text/javascript">window.opener.location.reload();window.close();</script>';
            }else{
                $js = '<script type="text/javascript">window.close();</script>';
            }
            echo($js);
            return;
        }            
        
        // redirect to detail view, as in save
        return parent::post_save();
    }

    protected function pre_QuickSave(){
        if(!empty($_REQUEST['application'])){
            $eapmBean = EAPM::getLoginInfo($_REQUEST['application'],true);
            if (!$eapmBean) {
                $this->bean->application = $_REQUEST['application'];
                $this->bean->assigned_user_id = $GLOBALS['current_user']->id;
            }else{
                $this->bean = $eapmBean;
            }
            $this->pre_save();
                    
        }else{
            sugar_die("Please pass an application name.");
        }
    }
    
	public function action_QuickSave(){
        $this->api = ExternalAPIFactory::loadAPI($this->bean->application,true);
        $this->action_save();

        if ( $this->api->authMethod == 'oauth' ) {
            $this->action_oauth();
        }
	}

    /** {@inheritdoc} */
    protected function action_delete()
    {
        if ($this->bean->application == 'Google') {
            $api = new ExtAPIGoogle();
            $api->revokeToken();
        } else {
            parent::action_delete();
        }
    }

    public function action_GoogleOauth2Redirect()
    {
        $this->view = 'googleoauth2redirect';
    }

    protected function post_QuickSave(){
        $this->post_save();
    }

    protected function pre_Reauthenticate(){
        $this->pre_save();
    }

    protected function action_Reauthenticate(){
        if ( $this->api->authMethod == 'oauth' ) {
            // OAuth beans have to be handled specially.
            
            $reply = $this->api->checkLogin();
            if ( !$reply['success'] ) {
                return $this->failed(translate('LBL_AUTH_ERROR', $this->bean->module_dir));
            } else {
                $this->bean->validated();
            }
        } else {
            // Normal auth methods go through this.
            $this->action_save();
        }
    }

    protected function post_Reauthenticate(){
        $this->post_save();
    }

    protected function action_FlushFileCache()
    {
        $api = ExternalAPIFactory::loadAPI($_REQUEST['api']);
        if ( $api == false ) {
            echo 'FAILED';
            return;
        }

        if ( method_exists($api,'loadDocCache') ) {
            $api->loadDocCache(true);
        }

        echo $GLOBALS['mod_strings']['LBL_SUCCESS'];
    }

    protected function remapAction() {
        if ( $this->do_action == 'DetailView' ) {
            $this->do_action = 'EditView';
            $this->action = 'EditView';
        }
        
        parent::remapAction();
    }

}
