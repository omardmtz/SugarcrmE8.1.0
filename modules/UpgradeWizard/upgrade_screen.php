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

$step = isset($_REQUEST['confirm_id']) ? 2 : 0;
?>
<html>
<head>
<title>SugarCRM Upgrader</title>
<meta name="viewport" content="initial-scale=1.0">
<meta name="viewport" content="user-scalable=no, width=device-width">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<link rel="stylesheet" href="styleguide/assets/css/upgrade.css?v=<?php echo time();?>"/>
<script src='include/javascript/jquery/jquery-min.js'></script>
<script type="text/javascript" src="<?php echo getJSPath('cache/include/javascript/sugar_grp1.js'); ?>"></script>

<script>
if (top !== self) {
    top.location.href = location.href;
}

$(window).bind("load", function () {

        var uploader = {token: "<?php echo $token ?>"};
        uploader.hideError = function () {
            $('.alert-danger').hide();
            $('.alert-success').hide();
        };
        uploader.displayError = function (error) {
            $('.alert-danger').show();
            $('.alert-success').hide();
            $('.alert-danger span').text(error);
            $('#' + uploader.stages[uploader.stage] + ' .bar').width('100%').addClass('error').removeClass('in-progress');
            $('#' + uploader.stages[uploader.stage] + ' h1')
                .removeClass('color_green')
                .addClass('color_red')
                .find('i')
                .removeClass('fa-cog color_yellow fa-spin')
                .removeClass('color_green')
                .addClass('fa-exclamation-circle')
                .addClass('color_red');
            $('#upload-indicator').hide();
            uploader.clearStatusUpdate();
        };

        uploader.displaySuccess = function (error) {
            $('.alert-danger').hide();
            $('.alert-success').show();
            $('.alert-success span').text(error);
        };

        uploader.updateProgress = function (bar, percent) {
            if (uploader.stage == -1) {
                return;
            }
            $('#upgradeTitle').text('Upgrade Progress ' + (uploader.stage + 1) + ' of ' + uploader.stages.length);
            var $bar = $('#' + bar + ' .bar');
            if (percent == 100) {
                $bar.removeClass('in-progress');
                $bar.removeClass('error');
                $('#' + bar + ' h1')
                    .removeClass('color_red')
                    .addClass('color_green')
                    .find('i')
                    .removeClass('fa-cog color_yellow')
                    .removeClass('fa-spin')
                    .addClass('fa-check-circle')
                    .removeClass('color_red')
                    .addClass('color_green');
            } else {
                $bar.addClass('in-progress');
                $('#' + bar + ' h1 i').addClass('fa-spin');
            }
            $bar.width(percent + '%');
        };
        uploader.STATUS_FREQ = 1000;
        uploader.statusUpdates = false;
        uploader.acceptedLicense = false;
        uploader.stage = 0;
        uploader.stages = ['unpack', 'healthcheck', 'pre', 'commit', 'post', 'cleanup'];
        uploader.counterStages = ['pre', 'post'];
        uploader.updateStatus = function () {
            $.ajax({
                type: 'POST',
                url: 'UpgradeWizard.php',
                data: {
                    token: uploader.token,
                    action: 'status'
                },
                dataType: 'json',
                success: function (e) {
                    if (uploader.statusUpdates) {
                        if (e.data.script_count) {
                            for (var i in e.data.script_count) {
                                uploader.updateProgress(i, Object.keys(e.data.scripts[i]).length / e.data.script_count[i] * 100);
                            }
                        }
                        uploader.setNextStatusUpdate();
                    }
                }

            });

        };
        uploader.setNextStatusUpdate = function () {
            uploader.statusUpdates = true;
            if (!$('a[data-action=gohome]').hasClass('disabled')) {
                $('a[data-action=gohome]').addClass('disabled');
            }
            uploader.updateInterval = setTimeout(uploader.updateStatus, uploader.STATUS_FREQ);
        };
        uploader.clearStatusUpdate = function () {
            uploader.statusUpdates = false;
            $('a[data-action=gohome]').removeClass('disabled');
            if (uploader.updateInterval) {
                clearTimeout(uploader.updateInterval);
            }
        };


        uploader.executeStage = function () {
            uploader.hideError();
            $('[data-step="2"] a[name="next_button"]').hide();
            $('[data-step="2"] a[name="export_button"]').hide();
            $.ajax({
                type: 'POST',
                url: 'UpgradeWizard.php',
                data: {
                    token: uploader.token,
                    action: uploader.stages[uploader.stage]
                },
                dataType: 'json',
                success: function (e) {
                    if (e.status == 'error' || e.status == undefined) {
                        uploader.displayError(e.message || "A server error occurred, please check your logs");
                        if (uploader.stages[uploader.stage] === 'healthcheck') {
                            if(typeof e.healthcheck !== "undefined") {
                                uploader.LoadHealthcheckResult(e.healthcheck, e.status);
                            }
                        }
                    } else {
                        if (e.data === true) {
                            uploader.clearStatusUpdate();
                            uploader.updateProgress(uploader.stages[uploader.stage], 100);
                            $('#upgradeTitle').text('Upgrade Complete');
                            $('a.disabled').removeClass('disabled');
                            $('.modal-header .bar').removeClass('in-progress').width('100%');
                        }
                        else if (e.data == 'pre') {
                            uploader.clearStatusUpdate();
                            uploader.stage = uploader.stages.indexOf(e.data);
                            uploader.LoadHealthcheckResult (e.healthcheck, e.status);

                        } else {
                            uploader.stage = uploader.stages.indexOf(e.data);

                            if (uploader.stage > 0) {
                                uploader.updateProgress(uploader.stages[uploader.stage - 1], 100);
                            } else {
                                uploader.clearStatusUpdate();
                            }
                            var percentComplete = 0;
                            if (uploader.counterStages.indexOf(e.data) == -1) {
                                percentComplete = 20;
                            }
                            uploader.updateProgress(e.data, percentComplete);
                            uploader.executeStage();
                        }

                    }
                },
                error: function (e) {
                    uploader.displayError("A server error occurred, please check your logs");
                }



            })

        }
        ;

        uploader.LoadHealthcheckResult = function (data, status) {
            if (data.length == 0) {
                if (status == 'error' || status == undefined) {
                    data = [
                        { flag: 3, descr: "Cannot find health check scanner", title: "Error", displayNumber: false }
                    ];
                } else {
                    data = [
                        {flag: 1, descr: "Your instance is ready for upgrade!", title: "Success", displayNumber: false}
                    ];
                }
            }
            data = data.sort(uploader._sortByBucket);

            $('[data-step="2"] a[name="export_button"]').show();

            var hcResult = $("#hc-result-data");
            var flagToIcon = [, 'fa-check-circle color_green', 'fa-ellipsis-h color_yellow', 'fa-exclamation-circle color_red'];
            var flag = -1;

            hcResult.html("");
            for (var i = 0; i < data.length; i++) {
                var item = data[i];
                var displayNumber = typeof item.displayNumber == 'undefined' ? true : item.displayNumber;
                var html = ["<h1><i class='fa ", flagToIcon[parseInt(item.flag)], "'></i> "];
                if (displayNumber) {
                    html.push(i + 1, ". ");
                }
                html.push(htmlentities(item.title, 'ENT_NOQUOTES'), "</h1><p>", htmlentities(item.descr, 'ENT_NOQUOTES'));
                if (item.kb) {
                    html.push("<a target='_blank' href='", item.kb, "'> Learn more...</a>");
                }

                if (item.flag > flag) {
                    flag =  item.flag;
                }

                html.push("</p>");
                hcResult.append(html.join(""));
            }
            hcResult.parent().scrollTop(hcResult.height());
            if (flag < 3) {
                $('[data-step="2"] a[name="next_button"]').show();
                $('[data-step="2"] a[name="next_button"]').removeClass('disabled');
                uploader.updateProgress('healthcheck', 100);
            }

        };

        uploader.upload = function(evt) {
            uploader.hideError();
            evt.preventDefault();
            if (!$('#uploadForm input[type=file]')[0].value) {
                uploader.displayError("Please select upgrade package file");
                return;
            }

            var $next = $('[data-step="1"] a[name=next_button]');
            $next.addClass("disabled");

            uploader.stage = uploader.stages.indexOf('unpack');
            uploader.updateProgress('unpack', 20);

            $('#upload-indicator').show();

            var formData = new FormData();
            var $form = $('#uploadForm');
            formData.append('zip', $form.find('input[type=file]')[0].files[0]);
            formData.append('action', $form.find(':hidden[name=action]').val());
            formData.append('token', $form.find(':hidden[name=token]').val());
            $.ajax('UpgradeWizard.php', {
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    error: function (e) {
                        uploader.displayError("A server error occurred, please check your logs");
                    }
                }
            ).complete(function (data) {
                    $next.removeClass("disabled");

                    try {
                        var response = $.parseJSON(data.responseText);
                        if (response.status == 'error') {
                            uploader.displayError(response.message);
                        } else {

                            uploader.stage = uploader.stages.indexOf(response.data);
                            uploader.updateProgress('unpack', 100);
                            /*                              License display disabled for now.
                             if(response.license || response.readme) {
                             uploader.displayLicense(response);
                             } else { */
                            uploader.executeStage();
                            uploader.setNextStatusUpdate();
                            showNextStep();
                            //}
                        }
                    } catch (e) {
                        uploader.displayError(data);
                    }

                });

        };

        uploader.displayLicense = function (response) {
            window.location.hash = 'modal-text';
            $('#licenseText').text(response.license || response.readme);
            window.addEventListener('hashchange', function (e) {
                var hash = window.location.hash.replace('#', '');
                if (hash == 'accepted') {
                    window.removeEventListener('hashchange', arguments.callee);
                    if (uploader.acceptedLicense) {
                        // ensure we launch the rest of the upgrade only once
                        return;
                    } else {
                        uploader.acceptedLicense = true;
                    }
                    uploader.updateProgress('unpack', 100);
                    uploader.executeStage();
                    uploader.setNextStatusUpdate();
                } else if (hash == 'modal-text') {
                    /* do nothing */
                } else {
                    uploader.updateProgress('unpack', 0);
                }
            }, false);
        };

        uploader._sortByBucket = function (item1, item2) {
            if (item1.bucket > item2.bucket) return 1;
            if (item1.bucket < item2.bucket) return -1;
            return 0;
        };

        function showNextStep() {
            var nextStep = currentStep + 1;
            if (nextStep <= maxSteps) {
                $('[data-step="' + currentStep + '"]').hide();
                $('[data-step="' + nextStep + '"]').show();
                currentStep = nextStep;
            }
            return false;
        }

        var currentStep = 0,
            maxSteps = 3,
            hashStep = parseInt(window.location.hash);

        if (hashStep > currentStep) {
            currentStep = hashStep - 1;
        }

        showNextStep();

        $('#uploadForm').submit(uploader.upload);

        $('[data-step="1"] a[name="next_button"]').on('click', function() {
            if (!$(this).hasClass("disabled")) {
                $('#uploadForm').submit();
            }
        });

        $('a[name="export_button"]').on('click', function() {
            $('#exportForm').submit();
        });

        $('input[type="file"]').on('change', function() {
            var $this = $(this),
                text = ($this.val().split('\\').pop() || 'No file chosen...');
            $this.parent().parent().next().text(text);
        });

        $('[data-step="2"] a[name="next_button"]')
            .on('click', showNextStep)
            .on('click', uploader.setNextStatusUpdate)
            .on('click', uploader.executeStage);

    }
);


</script>

</head>
<body>

<div class="upgrade">
    <div id="alerts" class="alert-top">
        <div class="alert-wrapper">
            <div class="alert alert-danger alert-block" data-flag="3">
                <button class="btn btn-link btn-invisible close" data-action="close">
                </button>
                <strong>Error</strong>
                <span></span>
            </div>
            <div class="alert alert-success alert-block" data-send="ok">
                <button class="btn btn-link btn-invisible close" data-action="close">
                    <i class="fa fa-times"></i>
                </button>
                <strong>Success</strong>
                <span></span>
            </div>
        </div>
    </div>
    <div class="modal" data-step="1">
        <div class="modal-header modal-header-upgrade row-fluid">
            <span class="step-circle">
                <span><?php echo ($step + 1) ?></span>
            </span>

            <div class="upgrade-title span8">
                <h3>Sugar Upgrader: Upload</h3>
                <span>Upload the upgrade package</span>
            </div>
            <div class="progress-section span4 pull-right">
                <span><img src="themes/default/images/company_logo.png" alt="SugarCRM" class="logo"></span>

                <div class="progress progress-success">
                    <div class="bar in-progress" style="width: 33%;"></div>
                </div>
            </div>
        </div>
        <div class="modal-body record">
            <div id="unpack" class="row-fluid ">
                <h1><i class="fa fa-cog color_yellow"></i>Upload the upgrade package</h1>

                <p>Please provide the upgrade package files. <a target="_blank" href="http://support.sugarcrm.com/03_Training/06_Upgrade_Training/" target="_blank">Learn more...</a></p>
                <form id="uploadForm">
                    <input type="hidden" name="token" value="<?php echo $token ?>">
                    <input type="hidden" name="action" value="unpack">
                <p>
                    <span class="upload-file">
                        <span class="upload-field-custom btn btn-primary" style="width: 84px;">
                          <label class="file-upload focus">
                              <span style="width: 84px;"><strong>Choose File</strong></span>

                              <input type="file" name="zip" style="width: 0px;">
                          </label>
                        </span>
                        <span>No file chosen...</span>
                    </span>
                </p>
                </form>
            </div>
        </div>
        <div class="modal-footer">
          <span class="version">Upgrader version <?php echo $upgraderVesion?></span>
          <span sfuuid="25" class="detail">
            <a class="btn btn-invisible btn-link" href="index.php#bwc/index.php?module=Administration&action=index">Cancel</a>
            <a class="btn btn-primary" href="javascript:void(0);" name="next_button">Upload</a>
          </span>
        </div>
    </div>

    <div class="modal" data-step="2">
        <div class="modal-header modal-header-upgrade row-fluid">
            <span class="step-circle">
                <span>2</span>
            </span>

            <div class="upgrade-title span7">
                <h3>Sugar Health Check</h3>
                <span>Review health check results</span>
            </div>
            <div class="progress-section span5 pull-right">
                <span><img src="themes/default/images/company_logo.png" alt="SugarCRM" class="logo"></span>

                <div class="progress progress-success">
                    <div class="bar in-progress" style="width: 33%;"></div>
                </div>
            </div>
        </div>
        <div class="modal-body record">
            <div class="row-fluid" id="hc-result-data">
                <h1><i class="fa fa-cog fa-spin color_yellow"></i>Performing health check. Please wait...</h1>
            </div>
        </div>
        <div class="modal-footer">
          <span class="version">Upgrader version <?php echo $upgraderVesion?></span>
          <span sfuuid="25" class="detail">
            <a class="btn btn-invisible btn-link" href="javascript:void(0);" name="export_button">Export Log</a>
            <a class="btn btn-primary" href="index.php" data-action="gohome">Go to Home Page</a>
            <a class="btn btn-primary disabled" href="javascript:void(0);" name="next_button">Confirm</a>
          </span>
        </div>
    </div>

    <div class="modal" data-step="3">
        <div class="modal-header modal-header-upgrade row-fluid">
            <span class="step-circle">
                <span>3</span>
            </span>

            <div class="upgrade-title span8">
                <h3 id="upgradeTitle">Sugar Upgrader: Upload</h3>
                <span>Upgrading the instance...</span>
            </div>
            <div class="progress-section span4 pull-right">
                <span><img src="themes/default/images/company_logo.png" alt="SugarCRM" class="logo"></span>

                <div class="progress progress-success">
                    <div class="bar in-progress" style="width: 66%;"></div>
                </div>
            </div>
        </div>
        <div class="modal-body record">
            <div id="unpack" class="row-fluid">
                <h1 class="color_green"><i class="fa fa-check-circle color_green"></i>Upload the upgrade package</h1>

                <div class="upgrade-check">
                    <div class="progress progress-success ">
                        <div class="bar" style="width: 100%;"></div>
                    </div>
                </div>
            </div>
            <div id="healthcheck" class="row-fluid">
                <h1><i class="fa fa-cog color_yellow"></i>Healthcheck</h1>

                <div class="healthcheck-check">
                    <div class="progress progress-success ">
                        <div class="bar in-progress" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
            <div id="hc-result-data" class="row-fluid"></div>
            <div id="pre" class="row-fluid">
                <h1><i class="fa fa-cog color_yellow fa-spin"></i>Pre-upgrade</h1>

                <div class="upgrade-check">
                    <div class="progress progress-success ">
                        <div class="bar in-progress" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
            <div id="commit" class="row-fluid ">
                <h1><i class="fa fa-cog color_yellow"></i>Upgrade</h1>

                <div class="upgrade-check">
                    <div class="progress progress-success ">
                        <div class="bar in-progress" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
            <div id="post" class="row-fluid ">
                <h1><i class="fa fa-cog color_yellow"></i>Post-upgrade</h1>

                <div class="upgrade-check">
                    <div class="progress progress-success ">
                        <div class="bar in-progress" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
            <div id="cleanup" class="row-fluid ">
                <h1><i class="fa fa-cog color_yellow"></i>Cleanup</h1>

                <div class="upgrade-check">
                    <div class="progress progress-success ">
                        <div class="bar in-progress" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          <form id="exportForm" class="invisible">
                <input type="hidden" name="action" value="exportlog">
                <input type="hidden" name="token" value="<?php echo $token ?>">
          </form>
          <span class="version">Upgrader version <?php echo $upgraderVesion?></span>
          <span sfuuid="25" class="detail">
            <a class="btn btn-invisible" href="javascript:void(0);" name="export_button">Export Log</a>
            <a class="btn btn-primary disabled" href="index.php" data-action="gohome">Go to Home Page</a>
          </span>
        </div>
    </div>
</div>
</body>
</html>
