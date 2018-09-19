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
$base_path = '../../../';
$link_path = '../';
if (substr($_SERVER['REQUEST_URI'], -1) == '/') {
    $base_path .= '../';
    $link_path .= '../';
}
?>

<!DOCTYPE HTML>
<html>

    <head>
        <title>SugarCRM Auto Generated API Exceptions Documentation</title>
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
    </head>

    <body>

        <h2>SugarCRM API Exceptions</h2>

        <p><a href="<?php echo $link_path ?>help">SugarAPI help documentation</a></p>
        <div class="container-fluid">

            <div class="row-fluid">

                <div class="span2"><h1>Type</h1></div>
                <div class="span1"><h1>HTTP Code</h1></div>
                <div class="span4"><h1>Description</h1></div>
                <div class="span2"><h1>Default error code</h1></div>
                <div class="span3"><h1>Default error message</h1></div>
            </div>

        <?php foreach ($exceptions as $exception): ?>

            <div class="row-fluid line" id="<?php echo $exception['element_id'] ?>">
                <div class="row-fluid">
                    <div class="span2">
                        <?php echo $exception['type'] ?>
                    </div>
                    <div class="span1">
                        <?php echo $exception['code'] ?>
                    </div>
                    <div class="span4">
                        <?php echo $exception['desc'] ?>
                    </div>
                    <div class="span2">
                        <?php echo $exception['label'] ?>
                    </div>
                    <div class="span3">
                        <?php echo $exception['message'] ?>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>

        </div>
    </body>
</html>
