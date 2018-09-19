<?php

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

/**
 * This file is here to provide a HTML template for the rest help api.
 */

$theme = new SidecarTheme();

$css_files = $theme->getCSSURL();

// Fixes pathing for help requests ending in '/'
$base_path = '../../';
$link_path = './help/';
if (substr($_SERVER['REQUEST_URI'], -1) == '/') {
    $base_path .= '../';
    $link_path = '';
}
?>

<!DOCTYPE HTML>
<html>

    <head>
        <title>SugarCRM Auto Generated API Help</title>
        <?php
        foreach ($css_files as $css) {
            echo '<link rel="stylesheet" href="' . $base_path . $css . '">';
        }
        ?>
        <style>

            body {
                padding: 5px;
            }

            .container-fluid div{
                background-color: @NavigationBar;
            }

            .line{
                border-bottom: 1px solid black;
            }

            .score{
                text-align: right;
            }

            .pre-scrollable {
                width: 600px;
                background-color: white;
                color: red;
            }

            .table {

                background-color: white;
            }

            .table td {
                white-space: normal;
                word-wrap: break-word;
            }

            h2{
                padding-top: 30px;
            }

            .well-small {
                background-color: white;
            }

            .alert {
                padding: 20px;
                text-align: center;
            }

        </style>

        <?php foreach ($jsfiles as $file): ?>
        <script type="text/javascript" src="<?php echo $base_path . $file ?>"></script>
        <?php endforeach; ?>

        <script type="text/javascript">
            function openLongHelp($hash) {
                var link = $('#help_link_' + $hash);
                if ($('#endpoint_' + $hash + '_full').hasClass('collapse')) { // closed
                    link.click();
                }
                $('html, body').animate({ scrollTop: link.offset().top }, 1000);
            }
        </script>
    </head>

    <body>

        <h2>SugarCRM API</h2>
        <p><a href="<?php echo $link_path ?>exceptions">Documentation on SugarAPI exceptions and error messages</a></p>
        <div class="container-fluid">

            <div class="row-fluid">

                <div class="span4"><h1>Endpoint</h1></div>
                <div class="span2"><h1>Method</h1></div>
                <div class="span3"><h1>Description</h1></div>
                <div class="span2"><h1>Exceptions</h1></div>
                <div class="span1 score"><h1>Score</h1></div>
            </div>

        <?php
            foreach ( $endpointList as $i => $endpoint )
            {
                if ( empty($endpoint['shortHelp']) ) { continue; }
        ?>

            <div class="row-fluid line">

                <div class="row-fluid">

                    <div class="span3">
                        <?php echo htmlspecialchars($endpoint['reqType']) ?>
                        <span id="help_link_<?php echo $i ?>" class="btn-link" type="button" data-toggle="collapse"
                              data-target="#endpoint_<?php echo $i ?>_full">
                            <?php echo htmlspecialchars($endpoint['fullPath']) ?>
                        </span>
                    </div>

                    <div class="span2">

                        <?php echo $endpoint['method']; ?>
                    </div>

                    <div class="span3">
                        <?php echo htmlspecialchars($endpoint['shortHelp']) ?>
                    </div>

                    <div class="span2">
                        <?php
                        if (empty($endpoint['exceptions'])) {
                            $exceptions = 'None';
                        } else {
                            $exceptions = '';
                            foreach ($endpoint['exceptions'] as $eid => $etype) {
                                $exceptions .= "<a href=\"{$link_path}exceptions#{$eid}\">$etype</a>, ";
                            }
                            $exceptions = rtrim($exceptions, ", ");
                        }
                        ?>
                        <?php echo $exceptions ?>
                    </div>

                    <div class="span1 score">
                        <?php echo sprintf("%.02f",$endpoint['score']) ?>
                    </div>

                </div>

                <div id="endpoint_<?php echo $i ?>_full" class="row-fluid collapse">

                    <div class="span12 well">

                        <?php

                            if (!empty($endpoint['longHelp']) && file_exists($endpoint['longHelp']) )
                            {
                                echo file_get_contents($endpoint['longHelp']);
                            }
                            else
                            {
                                echo '<span class="lead">No additional help.</span>';
                            }
                        //Hide the file links if no long help exists
                        if(!empty($endpoint['longHelp'])) { ?>

                            <div class="pull-right muted">
                                <i class="fa fa-file"></i>
                                <?php echo "./" . htmlspecialchars($endpoint['longHelp']); ?>
                            </div>

                        </div>

                        <div class="pull-right">
                            <i class="fa fa-file"></i>
                            <?php echo "./" . htmlspecialchars($endpoint['file']); ?>
                        </div>
                        <?php } ?>

                </div>

            </div>

        <?php
            }
        ?>

        </div>

    </body>
</html>
