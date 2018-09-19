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

<div id="tourStart">
    <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>{$APP.LBL_TOUR_WELCOME}</h3>
    </div>

	<div class="modal-body" {if $view_calendar_url}style="overflow: auto;"{/if}>
        <div style="float: left;">
            <div style="float: left; width: 300px;">
				{$APP.LBL_TOUR_FEATURES_670}
				<p>{$APP.LBL_TOUR_VISIT} <a href="javascript:void window.open('http://support.sugarcrm.com/02_Documentation/01_Sugar_Editions/{$appList.documentation.$sugarFlavor}')">{$APP.LNK_TOUR_DOCUMENTATION}</a>.</p>

                {if $view_calendar_url}
                <div style="border-top: 1px solid #F5F5F5;padding-top: 3px;" >
                    <p>{$view_calendar_url}</p>
                </div>
                {/if}

            </div>
            <div class="well" style="float: left; width: 220px; margin-left: 20px;"><img src="{sugar_getjspath file='themes/default/images/pt-screen0-thumb.png'}" width="220" id="thumbnail_0" class="thumb"></div>
        </div>
        </div>
        <div class="clear"></div>
    <div class="modal-footer">
    <a href="#" class="btn btn-primary">{$APP.LBL_TOUR_TAKE_TOUR}</a>
    <a href="#" class="btn btn-invisible">{$APP.LBL_TOUR_SKIP}</a>
    </div>
</div>
<div id="tourEnd" style="display: none;">
    <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>{$APP.LBL_TOUR_DONE}</h3>
    </div>

	<div class="modal-body">
		<div style="float: left;">
			<div style="float: left; width: 360px; margin-right: 50px;">
			<p>
			{$APP.LBL_TOUR_REFERENCE_1} <a href="javascript:void window.open('http://support.sugarcrm.com/02_Documentation/01_Sugar_Editions/{$appList.documentation.$sugarFlavor}')">{$APP.LNK_TOUR_DOCUMENTATION}</a> {$APP.LBL_TOUR_REFERENCE_2}
<br>
				<i class="fa fa-arrow-right fa-lg" style="float: right; position: relative; right: -33px; top: -30px;"></i>
			</p>
			</div>
			<div style="float: left">
				<img src="{sugar_getjspath file='themes/default/images/pt-profile-link.png'}" width="152" height="221">
			</div>
		</div>
	</div>
    <div class="clear"></div>

    <div class="modal-footer">
    <a href="#" class="btn btn-primary">{$APP.LBL_TOUR_BTN_DONE}</a>
    <a href="#" class="btn">{$APP.LBL_TOUR_BACK}</a>

    </div>
</div>

<script type="text/javascript">
    {literal}
    $('#thumbnail_0').live("click", function(){
        $("#tour_screenshot .modal-header h3").html("{/literal}{$APP.LBL_TOUR_WELCOME}{literal}");
        $("#tour_screenshot .modal-body").html("<img src='themes/default/images/pt-screen0-full.png' width='600'>");
        $("#tour_screenshot").modal("show");
    });
    {/literal}
</script>
