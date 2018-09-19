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

<div align='center' style='background:lightgray'>

{if $csrfAuthFailure}
<h3 style='color:red'>Cross Site Request Forgery (XSRF) Attack Detected</h3>
<h4>Form authentication failure ({$module} -> {$action}). Contact your administrator.</h4>
{else}
<h3 style='color:red'>Possible Cross Site Request Forgery (XSRF) Attack Detected</h3>
<h4>If you think this is a mistake please ask your administrator to add the following site to the acceptable referer list</h4>
<h3>{$host}</h3>
<h4><a href='javascript:void(0);' onclick='document.getElementById("directions").style.display="";'>Click here for directions to add this site to the acceptable referer list</a></h4>
</div>
<div id='directions' style='display:none'>
    <h3>Directions:</h3>
    <ol>
        <li>On your file system go to the root of your SugarCRM instance
        <li>Open the file config_override.php. If it does not exist, create it. (it should be at the same level as index.php and config.php)
        <li>Make sure the file starts with <pre>&lt;?php</pre> followed by a new line
        <li>Add the following line to your config_override.php file<br> <pre>$sugar_config['http_referer']['list'][] = '{$host}';</pre>
        <li>Save the file and it should work
    </ol>
    <h3>Attempted action ({$action}):</h3>
    If you feel this is a valid action that should be allowed from any referer, add the following to your config_override.php file
    <ul><li><pre>$sugar_config['http_referer']['actions'] =array( {$whiteListString} ); </pre></ul>
</div>
{/if}
