<?php
include_once('../url_path_config.php');
global $wpdb;
$table            =    $wpdb->prefix . 'pdffiles';
$pdf            =    '';
$s                =    '';
$getData         =     $wpdb->get_results('SELECT * FROM ' . $table . ' ORDER BY id DESC');

$advTable         =     $wpdb->prefix . "pdffiles_advance_settings";
$getAdvData     =     $wpdb->get_results('SELECT * FROM ' . $advTable . '');
?>

<div class="zPDFpopupViewer-contaienr">
    <div class="wrap">
        <div id="msg"></div>
        <div class="notice update-nag is-dismissible"><i class="fa fa-exclamation-circle fa-fw"></i> This plugin is created by <strong><?php echo $authorName; ?> Bin Khalid</strong> and it is absolutely free of cost. We just need your feedback. <strong>Thanks!</strong></div>
    </div>
    <div class="main-box">
        <div class="box-heading"><i class="fa fa-cogs"></i> Settings and Upload</div>
        <div class="main-box-body">
            <h1 class="clearfix"><i class="fa fa-file-archive-o"></i> All pdf Files. <kbd id="count"><?php echo count($getData); ?></kbd> <button type="button" class="button action showAll fright">Show All</button></h1><br />
            <table id="dataTables" datatable="" class="wp-list-table widefat striped">
                <thead>
                    <tr>
                        <th width="25" align="left">Sr#</th>
                        <th align="left">File Name</th>
                        <th align="center">Shortcode</th>
                        <th align="center">Enable POPUP</th>
                        <th align="center">Enable Page <br /><small>First disable popup</small></th>
                        <th width="50" align="center">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($getData) > 0) {
                        foreach ($getData as $val) {
                            $s++;
                            if ($val->ajaxStatus == 1) {
                                $code    =    "[z_pdf_popup id=" . $val->file_token . " btntitle='" . $getAdvData[0]->btnName . "' class='" . $getAdvData[0]->btnClass . " ajax' title='" . $getAdvData[0]->btnTitle . "']";
                            } else {
                                $code    =    "[z_pdf_popup link=" . zPDFpopupViewer_UPLOADS_URL . $val->filename . " target='" . $val->windowTarget . "' btntitle='" . $getAdvData[0]->btnName . "' class='" . $getAdvData[0]->btnClass . "' title='" . $getAdvData[0]->btnTitle . "']";
                            }
                    ?>
                            <script type="text/javascript">
                                jQuery(document).ready(function(e) {
                                    jQuery("#delete_<?php echo $val->id; ?>").on("click", function() {
                                        if (confirm('Are you sure?')) {
                                            jQuery.ajax({
                                                url: '<?php echo zPDFpopupViewer_PLUGIN_MIAN_URL; ?>ajax/actions.ajax.php?delete=ok&id=<?php echo $val->id; ?>',
                                                success: function(data) {
                                                    a = data.split('|^***^|');
                                                    if (a[1] = <?php echo $val->id; ?>) {
                                                        jQuery("#msg").html(a[0]);
                                                        jQuery("#<?php echo $val->id; ?>").fadeOut('slow');
                                                        c = jQuery("#count").html() - 1;
                                                        jQuery("#count").html(c);
                                                        if (c != 0) {} else {
                                                            jQuery("#nofound").html('<td colspan="5"><div class="text-center"><strong>No pdf(s) Found!</strong></div></td>');
                                                        }
                                                    } else {
                                                        jQuery("#msg").html(a[0]);
                                                    }
                                                }
                                            });
                                        }
                                    });

                                    jQuery("#ajaxStatus<?php echo $val->id; ?>").on("click", function() {
                                        if ($(this).prop('checked')) {
                                            sendAjex('1', <?php echo $val->id; ?>, 'popupStatus');
                                        } else {
                                            sendAjex('0', <?php echo $val->id; ?>, 'popupStatus');
                                        }
                                    });

                                    //check uncheck script
                                    <?php if ($val->ajaxStatus == 1) { ?>
                                        jQuery("#windowTarget<?php echo $val->id; ?>").hide();
                                    <?php } else { ?>
                                        jQuery("#windowTarget<?php echo $val->id; ?>").show();
                                        jQuery("#helpMsg<?php echo $val->id; ?>").hide();
                                    <?php } ?>
                                    //show hide popup action
                                    jQuery('#ajaxStatus<?php echo $val->id; ?>').click(function() {
                                        if (jQuery("#ajaxStatus<?php echo $val->id; ?>").prop("checked") == true) {
                                            jQuery("#windowTarget<?php echo $val->id; ?>").hide();
                                            jQuery("#helpMsg<?php echo $val->id; ?>").show();
                                        } else if (jQuery("#ajaxStatus<?php echo $val->id; ?>").prop("checked") == false) {
                                            jQuery("#windowTarget<?php echo $val->id; ?>").show();
                                            jQuery("#helpMsg<?php echo $val->id; ?>").hide();
                                        }
                                    });

                                    jQuery("#windowTarget<?php echo $val->id; ?>").on("change", function() {
                                        var _this = jQuery(this).val();
                                        sendAjex(_this, <?php echo $val->id; ?>, 'windowStatus');
                                    });
                                });
                            </script>
                            <tr id="<?php echo $val->id; ?>">
                                <td><?php echo $s; ?></td>
                                <td><i class="fa fa-file-archive-o"></i> <?php echo $val->filename; ?></td>
                                <td>
                                    <div class="input-group"><input type="text" id="copyTarget_<?php echo $val->id; ?>" value="<?php echo $code; ?>"><span class="input-group-btn"><button class="button action" data-clipboard-action="copy" data-clipboard-target="#copyTarget_<?php echo $val->id; ?>" id="copyButton" type="button">Copy</button></span></div>
                                </td>
                                <td>
                                    <input type="checkbox" name="ajaxStatus" id="ajaxStatus<?php echo $val->id; ?>" <?php if ($val->ajaxStatus == 1) {
                                                                                                                        echo 'checked="checked"';
                                                                                                                    } ?> value="1" />
                                </td>
                                <td>
                                    <span id="helpMsg<?php echo $val->id; ?>">First disable popup to enable page view</span>
                                    <select name="windowTarget" id="windowTarget<?php echo $val->id; ?>">
                                        <option value="">Select window location</option>
                                        <option value="_self" <?php if ($val->windowTarget == "_self") {
                                                                    echo 'selected="selected"';
                                                                } ?>>Self</option>
                                        <option value="_blank" <?php if ($val->windowTarget == "_blank") {
                                                                    echo 'selected="selected"';
                                                                } ?>>Blank</option>
                                    </select>
                                </td>
                                <td align="center"><a href="javascript:void(0);" class="button action" id="delete_<?php echo $val->id; ?>" data-toggle='tooltip' title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                            </tr>
                    <?php
                        }
                    } else {
                        echo '<tr><td colspan="6"><div class="text-center"><strong>No PDF(s) Found!</strong></div></td></tr>';
                    }
                    ?>
                </tbody>
            </table>
            <div class="clearfix"></div>
            <h2><i class="fa fa-arrows"></i> Drag & Drop multiple files up to <kbd><?php echo $getAdvData[0]->parallelUpload; ?></kbd> </h2>
            <div class="drop-box">
                <script type="text/javascript">
                    function sendAjex(sendStatus, sendId, sendName) {
                        jQuery.ajax({
                            url: '<?php echo zPDFpopupViewer_PLUGIN_MIAN_URL; ?>ajax/actions.ajax.php?sendStatus=' + sendStatus + '&sendID=' + sendId + '&sendName=' + sendName,
                            success: function(data) {
                                a = data.split('|^***^|');
                                if (a[1] == 1 || a[1] == 1) {
                                    jQuery("#msg").html(a[0]);
                                    setTimeout(function() {
                                        window.location.href = "";
                                    }, 500);
                                } else {
                                    jQuery("#msg").html(a[0]);
                                }
                            }
                        });
                    }
                    Dropzone.autoDiscover = false;
                    jQuery(document).ready(function(e) {
                        //Submition of advance settings
                        jQuery("#pdfAdvanceSettings").on("submit", function() {
                            jQuery("html, body").animate({
                                scrollTop: 0
                            }, 600);
                            jQuery.ajax({
                                type: 'POST',
                                url: '<?php echo zPDFpopupViewer_PLUGIN_MIAN_URL; ?>ajax/actions.ajax.php?advSettings=ok',
                                data: jQuery("#pdfAdvanceSettings").serialize(),
                                success: function(data) {
                                    a = data.split('|^***^|');
                                    if (a[1] == 1) {
                                        jQuery("#msg").html(a[0]);
                                        setTimeout(function() {
                                            window.location.href = "";
                                        }, 500);
                                    }
                                    if (a[1] == 5) {
                                        jQuery("#msg").html(a[0]);
                                    } else {
                                        jQuery("#msg").html(a[0]);
                                    }
                                }
                            });
                        });
                        //Dropzone script
                        var myDropzone = new Dropzone("div#myDrop", {
                            paramName: "files", // The name that will be used to transfer the file
                            url: "<?php echo zPDFpopupViewer_PLUGIN_MIAN_URL; ?>ajax/actions.ajax.php?upload=ok",
                            addRemoveLinks: true,
                            uploadMultiple: true,
                            autoProcessQueue: false,
                            parallelUploads: <?php echo $getAdvData[0]->parallelUpload; ?>,
                            maxFilesize: <?php echo $getAdvData[0]->maxSize; ?>, // MB
                            acceptedFiles: "<?php echo $getAdvData[0]->extnAllows; ?>"
                        });
                        /* Add Files Script*/
                        myDropzone.on("success", function(file, message) {
                            var input = document.createElement("input");
                            input.type = "hidden";
                            input.name = "rename_files[]";
                            input.value = file.xhr.responseText;
                            file.previewTemplate.appendChild(input);
                            setTimeout(function() {
                                window.location.href = "";
                            }, 500);
                        });

                        myDropzone.on("error", function(data) {
                            $previewElement = $(data.previewElement);
                            var filename = $previewElement.find('.dz-filename span').text(),
                                errormsg = $previewElement.find('.dz-error-message span').text();
                            swal({
                                title: filename,
                                text: "<span style='color:#ed5565'>" + errormsg + "<span>",
                                html: true
                            });
                        });

                        myDropzone.on("complete", function(file) {
                            myDropzone.removeFile(file);
                        });

                        jQuery("#add_file").on("click", function() {
                            myDropzone.processQueue();
                        });
                    });
                </script>
                <div class="dropzone dz-clickable" id="myDrop">
                    <div class="dz-default dz-message" data-dz-message="">
                        <span>Drop files here to upload</span>
                    </div>
                </div>
                <strong class="text-right text-danger">Only <?php echo strtoupper($getAdvData[0]->extnAllows); ?> files allow.</strong> <strong class="label label-default">Only <?php echo $getAdvData[0]->maxSize; ?>MB allow!</strong>
                <hr />
                <div class="col-sm-12">
                    <button type="button" id="add_file" class="button action" name="submit"><i class="fa fa-upload"></i> Upload File(s)</button>
                </div>
                <div class="clearfix"></div>
            </div>
            <!--/.drop-box-->
        </div>
        <!--/.main-box-body-->
    </div>
    <!--/.main-box-->
    <div class="setting-box">
        <div class="box-heading"><i class="fa fa-cogs"></i> Advance Settings</div>
        <div class="setting-box-body">
            <form method="post" id="pdfAdvanceSettings" onsubmit="return false;">
                <div class="form-margin">
                    <label class="control-label">Max. File size</label>
                    <input type="text" class="field-control" name="maxSize" value="<?php echo isset($getAdvData[0]->maxSize) ? $getAdvData[0]->maxSize : '20'; ?>" placeholder="Max. file size" />
                    <div><i class="help-block">Only accept integer value in MB's!</i></div>
                </div>
                <div class="form-margin">
                    <label class="control-label">Parallel Uploads</label>
                    <input type="text" class="field-control" name="parallelUpload" value="<?php echo isset($getAdvData[0]->parallelUpload) ? $getAdvData[0]->parallelUpload : '50'; ?>" placeholder="Parallel upload files" />
                    <div><i class="help-block">Only accept integer value!</i></div>
                </div>
                <div class="form-margin">
                    <label class="control-label">Extension allows</label>
                    <input type="text" class="field-control" name="extnAllows" value="<?php echo isset($getAdvData[0]->extnAllows) ? strtoupper($getAdvData[0]->extnAllows) : '.PDF'; ?>" readonly="readonly" />
                    <div><i class="help-block">Default extension is ".PDF", We will be back with more features!</i></div>
                    <div class="clearfix"></div>
                </div>
                <div class="form-margin">
                    <label class="control-label">Button Name</label>
                    <input type="text" class="field-control" name="btnName" value="<?php echo isset($getAdvData[0]->btnName) ? $getAdvData[0]->btnName : 'Preview PDF'; ?>" />
                    <div><i class="help-block">Default Name is "Preview PDF"!</i></div>
                    <div class="clearfix"></div>
                </div>
                <div class="form-margin">
                    <label class="control-label">Button Title</label>
                    <input type="text" class="field-control" name="btnTitle" value="<?php echo isset($getAdvData[0]->btnTitle) ? $getAdvData[0]->btnTitle : 'PDF Preview'; ?>" />
                    <div><i class="help-block">Default title is "PDF Preview", You can change title!</i></div>
                    <div class="clearfix"></div>
                </div>
                <div class="form-margin">
                    <label class="control-label">Button Custom Class</label>
                    <input type="text" class="field-control" name="btnClass" value="<?php echo isset($getAdvData[0]->btnClass) ? $getAdvData[0]->btnClass : 'btn-pdf ajax'; ?>" />
                    <div><i class="help-block">"btn-pdf" is a default btn class create your own and place class name here!</i></div>
                    <div class="clearfix"></div>
                </div>
                <button type="submit" name="submitAdv" class="button action">Save Settings</button>

            </form>
        </div>
    </div>
    <!--/.setting-box-->
    <div class="clearfix"></div>
</div>
<!--/.zPDFpopupViewer-contaienr-->