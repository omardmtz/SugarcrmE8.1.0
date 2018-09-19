{*
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
*}
<!DOCTYPE html>
<head>
<script language="javascript">
    {literal}
    function loadApp(auth, siteUrl) {
        localStorage.setItem('_AuthAccessToken', auth.access_token);
        localStorage.setItem('_AuthRefreshToken', auth.refresh_token);
        localStorage.setItem('_DownloadToken', auth.download_token);
        window.location.href = (siteUrl || '').replace(/\/*$/, '') + '/mobile';
    }
    {/literal}    
    loadApp({$authorization|@json}, '{$siteUrl|escape:javascript}')        
</script>
</head>
<body/>
</html>

