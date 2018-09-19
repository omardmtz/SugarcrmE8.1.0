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

<!-- BEGIN: main -->
<html {$langHeader}>
<head>
<script type="text/javascript" src="modules/InboundEmail/InboundEmail.js?v={VERSION_MARK}"></script>
<script type="text/javascript" src="include/javascript/sugar_3.js?v={VERSION_MARK}"></script>
<script type="text/javascript" src="cache/include/javascript/sugar_grp1_yui.js?v={VERSION_MARK}"></script>
<script type="text/javascript" src="include/SugarFields/Teamset/Teamset.js?v={VERSION_MARK}"></script>
{$languageStrings}
<script type="text/javascript" src="cache/include/javascript/sugar_grp1_yui.js??v={VERSION_MARK}"></script>
<script type="text/javascript" src="cache/include/javascript/sugar_grp1.js??v={VERSION_MARK}"></script>
<script type="text/javascript" language="Javascript">
currentFolders = {$group_folder_array};
{literal}
	function checkFolderName(newFolder) {
	    var duplicate = false;
        for (var i in currentFolders) {
           if (currentFolders[i] == newFolder) {
               duplicate = true;
           }
        }
        if(newFolder == "" || duplicate) {
            alert(document.getElementById('errorMessage').value);
            return false;
        }
        return true;
	}

	function checkTeamSetData() {
        if (!SUGAR.collection.prototype.validateTemSet('EditView', 'team_name')) {
        	alert({/literal}'{$app_strings.ERR_NO_PRIMARY_TEAM_SPECIFIED}'{literal});
        	return false;
        } // if
        var teamIdsArray = SUGAR.collection.prototype.getTeamIdsfromUI('EditView', 'team_name');
		var primaryTeamId = SUGAR.collection.prototype.getPrimaryTeamidsFromUI('EditView', 'team_name');
		document.getElementById('primaryTeamId').value = primaryTeamId;
		document.getElementById('teamIds').value = teamIdsArray.join(",");
		return true
	} // fn

	function addNewGroupFolder() {
	    var newFolder = document.getElementById('groupFolderAddName').value;
        if (checkFolderName(newFolder) && checkTeamSetData()) {
		  document.getElementById('EditView').submit();
		}
	}

	function editGroupFolder() {
        var newFolder = document.getElementById('groupFolderAddName').value;
        if (checkFolderName(newFolder) && checkTeamSetData()) {
          document.getElementById('EditView').submit();
        }
	} // fn


{/literal}
</script>
{$CSS}
</head>
<body>
<form action="index.php" method="post" name="EditView" id="EditView">
{sugar_csrf_form_token}
	<input type="hidden" name="module" value="InboundEmail">
	<input type="hidden" name="action" value="SaveGroupFolder">
	<input type="hidden" id="errorMessage" name="errorMessage" value="{$app_strings.LBL_EMAIL_ERROR_ADD_GROUP_FOLDER}">
	<input type="hidden" name="record" value="{$ID}">
	<input type="hidden" name="to_pdf" value="1">
	<input type="hidden" name="isDuplicate" value=false>
	<input type="hidden" name="return_module">
	<input type="hidden" name="return_action">
	<input type="hidden" name="return_id">
	<input type="hidden" name="groupFoldersUser" value="">
	<input type="hidden" id="primaryTeamId" name="primaryTeamId">
	<input type="hidden" id="teamIds" name="teamIds">


	<table width="100%" border="0" align="center" cellspacing="{$GRIDLINE}" cellpadding="0">
		<tr>
		<td NOWRAP style="padding: 8px;" valign="top">
			<div style="{$createGroupFolderStyle}">
				<b>{$app_strings.LBL_EMAIL_SETTINGS_GROUP_FOLDERS_CREATE}:</b>
			</div>
			<div style="{$editGroupFolderStyle}">
				<b>{$app_strings.LBL_EMAIL_SETTINGS_GROUP_FOLDERS_EDIT}:</b>
			</div>
			<br />

			<div>
				{$app_strings.LBL_EMAIL_FOLDERS_NEW_FOLDER}:
			</div>
			<div>
				<input type="text" value="{$groupFolderName}" name="groupFolderAddName" id="groupFolderAddName">
			</div>
			<br />

			<div>
				{$app_strings.LBL_EMAIL_FOLDERS_ADD_THIS_TO}:
			</div>
			<div>
				<select name="groupFoldersAdd" id="groupFoldersAdd">{$group_folder_options}</select>
			</div>
			<br />
			<div>
				{$app_strings.LBL_EMAIL_FOLDERS_USING_TEAM}:
			</div>
			<div>
				{$TEAM_SET_FIELD}
			</div>
			<br />
			<input type="button" style="{$createGroupFolderStyle}" class="button" value="   {$app_strings.LBL_EMAIL_FOLDERS_ADD_NEW_FOLDER}   " {literal} onclick="addNewGroupFolder();" {/literal}>
			<input type="button" style="{$editGroupFolderStyle}" class="button" value="   {$app_strings.LBL_EMAIL_SAVE}   " onclick="editGroupFolder();" >
			<input type="button" class="button" value="   {$app_strings.LBL_EMAIL_CLOSE}   " onclick="window.close();">
		</td>
		</tr>
	</table>
	<br />
</form>
{$JAVASCRIPT}
</body>
</html>
<!-- END: main -->