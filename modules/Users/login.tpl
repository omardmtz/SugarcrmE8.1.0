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
<script type='text/javascript'>
var LBL_LOGIN_SUBMIT = '{sugar_translate module="Users" label="LBL_LOGIN_SUBMIT"}';
var LBL_REQUEST_SUBMIT = '{sugar_translate module="Users" label="LBL_REQUEST_SUBMIT"}';
var LBL_SHOWOPTIONS = '{sugar_translate module="Users" label="LBL_SHOWOPTIONS"}';
var LBL_HIDEOPTIONS = '{sugar_translate module="Users" label="LBL_HIDEOPTIONS"}';
</script>
<table cellpadding="0" align="center" width="100%" cellspacing="0" border="0" style="margin-top: 100px;">
	<tr>
		<td align="center">
		<div class="loginBoxShadow" style="width: 460px;">
			<div class="loginBox">
			<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
				<tr>
					<td align="left"><b>{sugar_translate module="Users" label="LBL_LOGIN_WELCOME_TO"}</b><br>
					    {$LOGIN_IMAGE}
					</td>
				</tr>
				<tr>
					<td align="center">
						<div class="login">
							<form action="index.php" method="post" name="DetailView" id="form" onsubmit="return document.getElementById('cant_login').value == ''">
{sugar_csrf_form_token}
								<table cellpadding="0" cellspacing="2" border="0" align="center" width="100%">
						    	<td scope="row" colspan="2">
						    	    <span class="error" id="browser_warning" style="display:none">
						    	        {sugar_translate label="LBL_ALERT_BROWSER_NOT_SUPPORTED"}
						    	    </span>
						    	    <span class="error" id="ie_compatibility_mode_warning" style="display:none">
						    	        {sugar_translate label="WARN_BROWSER_IE_COMPATIBILITY_MODE_WARNING"}
						    	    </span>
						    	</td>

						    	{if $LOGIN_ERROR !=''}
									<tr>
										<td scope="row" colspan="2"><span class="error">{$LOGIN_ERROR}</span></td>
						    	{if $WAITING_ERROR !=''}
							        <tr>
							            <td scope="row" colspan="2"><span class="error">{$WAITING_ERROR}</span></td>
									</tr>
								{/if}
									</tr>
								{else}
									<tr>
										<td scope="row" width='1%'></td>
										<td scope="row"><span id='post_error' class="error"></span></td>
									</tr>
								{/if}
									<tr>
										<td scope="row" colspan="2" width="100%" style="font-size: 12px; font-weight: normal; padding-bottom: 4px;">
										<input type="hidden" name="module" value="Users">
										<input type="hidden" name="action" value="Authenticate">
										<input type="hidden" name="return_module" value="Users">
										<input type="hidden" name="return_action" value="Login">
										<input type="hidden" id="cant_login" name="cant_login" value="">
										{foreach from=$LOGIN_VARS key=key item=var}
											<input type="hidden" name="{$key}" value="{$var}">
										{/foreach}
										</td>
									</tr>

                                    <tr><td>&nbsp;</td></tr>
                                {if !empty($SELECT_LANGUAGE)}
                                    <tr>
                                        <td scope="row">{sugar_translate module="Users" label="LBL_LANGUAGE"}:</td>
                                        <td><select style='width: 152px' name='login_language' onchange="switchLanguage(this.value)">{$SELECT_LANGUAGE}</select></td>
                                    </tr>
                                {/if}
									<tr>
										<td scope="row" width="30%"><label for="user_name">{sugar_translate module="Users" label="LBL_USER_NAME"}:</label></td>
										<td width="70%"><input type="text" size='35' tabindex="1" id="user_name" name="user_name" value="{$LOGIN_USER_NAME|escape:'html':'UTF-8'}" /></td>
									</tr>
									<tr>
										<td scope="row"><label for="user_password">{sugar_translate module="Users" label="LBL_PASSWORD"}:</label></td>
										<td width="30%"><input type="password" size='26' tabindex="2" id="user_password" name="user_password" value='{$LOGIN_PASSWORD}' /></td>
									</tr>

									<tr>
										<td>&nbsp;</td>
										<td><input title="{sugar_translate module="Users" label="LBL_LOGIN_BUTTON_TITLE"}"  class="button primary" class="button primary" type="submit" tabindex="3" id="login_button" name="Login" value="{sugar_translate module="Users" label="LBL_LOGIN_BUTTON_LABEL"}"><br>&nbsp;</td>
									</tr>
								</table>
							</form>
							
						</div>


					</td>
				</tr>
			</table>
			</div>
			<div class="password">
			
			<form action="index.php" method="post" name="fp_form" id="fp_form" >
{sugar_csrf_form_token}
								<table cellpadding="0" cellspacing="2" border="0" align="center" width="100%">
									<tr>
										<td colspan="2" class="login_more">
										<div  style="cursor: hand; cursor: pointer; display:{$DISPLAY_FORGOT_PASSWORD_FEATURE};" onclick='toggleDisplay("forgot_password_dialog");'>
											<a href='javascript:void(0)'><IMG src="{sugar_getimagepath file='advanced_search.gif'}" border="0" alt="Hide Options" id="forgot_password_dialog_options">{sugar_translate module="Users" label="LBL_LOGIN_FORGOT_PASSWORD"}</a>
										</div>
											<div id="forgot_password_dialog" style="display:none" >
												<input type="hidden" name="entryPoint" value="GeneratePassword">
												<table cellpadding="0" cellspacing="2" border="0" align="center" width="100%" >
													<tr>
														<td colspan="2">
															<div id="generate_success" class='error' style="display:inline;"> </div>
														</td>
													</tr>
													<tr>
														<td scope="row" width="30%"><label for="fp_user_name">{sugar_translate module="Users" label="LBL_USER_NAME"}:</label></td>
														<td width="70%"><input type="text" size='26' id="fp_user_name" name="fp_user_name" value="{$LOGIN_USER_NAME|escape:'html':'UTF-8'}" /></td>
													</tr>
													<tr>
											            <td scope="row" width="30%"><label for="fp_user_mail">{sugar_translate module="Users" label="LBL_EMAIL"}:</label></td>
											            <td width="70%"><input type="text" size='26' id="fp_user_mail" name="fp_user_mail"  value='' ></td>
											     	</tr>
													{$CAPTCHA}
													<tr>
													    <td scope="row" width="30%"><div id='wait_pwd_generation'></div></td>
														<td width="70%"><input title="Email Temp Password" class="button" type="button" style="display:inline" onclick="validateAndSubmit(); return document.getElementById('cant_login').value == ''" id="generate_pwd_button" name="fp_login" value="{sugar_translate module="Users" label="LBL_LOGIN_SUBMIT"}"></td>
													</tr>
												</table>
											</div>
										</td>
									</tr>
								</table>
							</form>
			</div>	
		</div>
		</td>
	</tr>
</table>
<br>
<br>
