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
{literal}
<script type="text/javascript">
<!--
	var user_notices = new Array();
	var delay = 25000
	var index = 0;
	var lastIndex = 0;
	var noticeTeamID = 1;
	var noticeTitleIndex = 1;
	var noticeBodyIndex = 2;
	var noticeURLTitleIndex = 3;
	var noticeURLIndex = 4;
	var scrollerHeight=58
	var bodyHeight = ''
	var scrollSpeed = 1;
	var curTeam = 'all';
	var scrolling = true;
    var scrollTimeOut;
    var noticeTimeOut;
	
    {/literal}{foreach name=rowIteration from=$data key=id item=rowData}{literal}
	user_notices.push(["{/literal}{$rowData.id}{literal}","{/literal}{$rowData.name}{literal}", "{/literal}{$rowData.description|regex_replace:"/\r*\n/":'<br>'}{literal}", "{/literal}{$rowData.url_title}{literal}", "{/literal}{$rowData.url}{literal}"]);
    {/literal}{/foreach}{literal}
	
	function stopNotice(){
			scrolling = false;
	}
	function startNotice(){
			scrolling = true;
	}
	function scrollNotice(){
		if(scrolling && typeof document.getElementById('NOTICEBODY') != 'undefined'){
    		var notice_body = document.getElementById('NOTICEBODY')
    		var daddy = document.getElementById('daddydiv')
            
            try{
			if(typeof notice_body != 'undefined') {
        		if(parseInt(notice_body.style.top) > bodyHeight *-1 ){
            		notice_body.style.top = (parseInt(notice_body.style.top) - scrollSpeed) + 'px';
            	}else{
            		notice_body.style.top =scrollerHeight + "px"	
            	}
            }
            } catch (e)
            {
				scrolling = false;
			}
		}
    	    noticeTimeOut = setTimeout("scrollNotice()", 100);
	}
	function nextNotice(){
        
		if(scrolling){
			if(user_notices.length > 0){
				if(index >= user_notices.length	){
					index = 0;	
				}
				var notice_body = document.getElementById('NOTICEBODY');
				if(curTeam != 'all'){
					notice_body.innerHTML = '<p><b>' +  user_notices[index][noticeTitleIndex] + '</b><br>' + user_notices[index][noticeBodyIndex] +'<br><a href="' + user_notices[index][noticeURLIndex]+ '" target="_blank">'+user_notices[index][noticeURLTitleIndex]+'</a></p>';
				}else{
					
					for(var i = 0; i < user_notices.length; i++){
					notice_body.innerHTML += '<p><b>' +  user_notices[i][noticeTitleIndex] + '</b><br>' + user_notices[i][noticeBodyIndex] +'<br><a href="' + user_notices[i][noticeURLIndex]+ '" target="_blank">'+user_notices[i][noticeURLTitleIndex]+'</a></p>';
					}
					
					}
				notice_body.style.top = scrollerHeight/2 +'px'
				bodyHeight= parseInt(notice_body.offsetHeight);
				
				
				index++;
				}
				if(curTeam != 'all'){
					
					
				scrollTimeOut = setTimeout("nextNotice()", delay);
				}
				
		}
	}
-->
</script>
{/literal}
<table  border="0" cellpadding="0" cellspacing="0" width='100%' >
<tr>
<td colspan='1' valign='top' height='50px' class="teamNoticeBox">

<div id='daddydiv' style="position:relative;width=100%;height:50px;overflow:hidden" onmouseover="stopNotice();" onmouseout="startNotice();">
<table width="100%" cellspacing="0" cellpadding="0" border="0" height="30">
<tr>
    <td height="30" class="teamNoticeText"><div id='NOTICEBODY' style="position:absolute;left:0px;top:0px;width:100%;z-index: 1;"></div></td>
</tr>
</table>
</div></td>
</tr>

</table>
{literal}
<script type="text/javascript">

    YAHOO.util.Event.onDOMReady(function(){

        clearTimeout(scrollTimeOut);    // clear any previous timeouts
        clearTimeout(noticeTimeOut);

        nextNotice(); scrollNotice();   // these will set new timeouts 

   });
    
</script>
{/literal}

