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
<script src="//widgets.twimg.com/j/2/widget.js" type="text/javascript"></script>
<script type="text/javascript" src="{sugar_getjspath file='include/connectors/formatters/default/company_detail.js'}"></script>
<script type="text/javascript">
function show_ext_rest_twitter(event)
{literal} 
{
    var xCoordinate = event.clientX;
    var yCoordinate = event.clientY;
    var isIE = document.all?true:false;
      
    if(isIE) 
    {
        xCoordinate = xCoordinate + document.body.scrollLeft;
        yCoordinate = yCoordinate + document.body.scrollTop;
    }

{/literal}

    cd = new CompanyDetailsDialog("twitter_popup_div", '<div id="twitter_div" name="twitter_div">' + SUGAR.language.get('app_strings', 'LBL_TWITTER_DATA_LOADING') + '</div>', xCoordinate, yCoordinate);
    cd.setHeader("{$fields.{{$mapping_name}}.value|trim}");
    cd.display();

    mapping_name_value = "{$fields.{{$mapping_name}}.value|trim}";

    if (mapping_name_value.length > 0) 
    {ldelim}
        twitter_popup = new TWTR.Widget({literal}{
          id: 'twitter_div',
          version: 2,
          type: 'profile',
          rpp: 4,
          interval: 6000,
          width: 250,
          height: 300,
          theme: {
            shell: {
              background: '#ffffff',
              color: '#000000'
            },
            tweets: {
              background: '#ffffff',
              color: '#000000',
              links: '#0b578f'
            }
          },
          features: {
            scrollbar: true,
            loop: false,
            live: true,
            hashtags: true,
            timestamp: true,
            avatars: false,
            behavior: 'all'
          }
        }{/literal}).render().setUser('{$fields.{{$mapping_name}}.value}').start();
    {rdelim} 
    else 
    {ldelim}
        document.getElementById('twitter_div').innerHTML = SUGAR.language.get('app_strings', 'LBL_TWITTER_DATA_EMPTY');
    {rdelim}
{rdelim}
</script>