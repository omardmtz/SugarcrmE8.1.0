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
<table border='0' cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td width='99%'>
<input type="hidden" class="sugar_field" id="{{$displayParams.key}}_address_street" value="{$fields.{{$displayParams.key}}_address_street.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}">
<input type="hidden" class="sugar_field" id="{{$displayParams.key}}_address_city" value="{$fields.{{$displayParams.key}}_address_city.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}">
<input type="hidden" class="sugar_field" id="{{$displayParams.key}}_address_state" value="{$fields.{{$displayParams.key}}_address_state.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}">
<input type="hidden" class="sugar_field" id="{{$displayParams.key}}_address_country" value="{$fields.{{$displayParams.key}}_address_country.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}">
<input type="hidden" class="sugar_field" id="{{$displayParams.key}}_address_postalcode" value="{$fields.{{$displayParams.key}}_address_postalcode.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}">
{$fields.{{$displayParams.key}}_address_street.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}<br>
{$fields.{{$displayParams.key}}_address_city.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br} {$fields.{{$displayParams.key}}_address_state.value|escape:'htmlentitydecode'|strip_tags|url2html|nl2br}&nbsp;&nbsp;{$fields.{{$displayParams.key}}_address_postalcode.value|escape:'htmlentitydecode'|strip_tags|url2html|nl2br}<br>
{$fields.{{$displayParams.key}}_address_country.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}
</td>
{{if !empty($displayParams.enableConnectors)}}
<td class="dataField">
{{sugarvar_connector view='DetailView'}} 
</td>
{{/if}}
<td class='dataField' width='1%'>
{{* 
다음 사용자 지정 코드는 주소 필드의 두번재 칸에 보여줄 수 있습니다.
거래처 DetailView에서 기본으로 제공되는 button 정보를 복사하여 예제로 사용할 수 있습니다.
현재 값이 어떻게 설정되었는지 modules/Accounts/views/view.detail.php 파일을 열어 확인할 수 있습니다.
*}}
{$custom_code_{{$displayParams.key}}}
</td>
</tr>
</table>