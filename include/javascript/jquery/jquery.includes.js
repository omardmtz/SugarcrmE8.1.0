var header_template = '\
<!DOCTYPE html>\
<head>\
  <meta charset="utf-8">\
  <title>SugarCRM</title>\
  <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">\
  <meta name="apple-mobile-web-app-capable" content="yes">\
  <meta name="apple-mobile-web-app-status-bar-style" content="black">\
  <link rel="shortcut icon" href="../img/avatar_16.png">\
  <link rel="apple-touch-icon" href="../img/badge.png">\
  <link rel="apple-touch-icon-precomposed" href="../img/badge.png">\
  <meta name="description" content="">\
  <meta name="author" content="">\
  <link href="../css/bootstrap-mobile.css" rel="stylesheet">\
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet" type="text/css">\
  <script src="../js/stal.js"></script>\
</head>\
';

var footer_template = '\
</html>\
';

var stream_items_template = '\
{{#aloop}}\
<article>\
  <i class="{{starred}}"></i>\
  <div title="{{name}}">\
    {{{content}}}\
  </div>\
  <span id="listing-action-item1">\
    <i class="grip">|||</i>\
    <span class="hide actions">\
     {{{actions}}}\
    </span>\
  </span>\
</article>\
{{/aloop}}\
';