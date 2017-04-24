<?php
include_once('../url_path_config.php');
global $wpdb;
$table			=	$wpdb->prefix . 'pdffiles';
$pdf			=	'';
$s				=	'';
$getData 		= 	$wpdb->get_results('SELECT * FROM '.$table.' ORDER BY id DESC');

$advTable 		= 	$wpdb->prefix . "pdffiles_advance_settings";
$getAdvData 	= 	$wpdb->get_results('SELECT * FROM '.$advTable.'');
?>
<div class="zPDFpopupViewer-contaienr">
    <div class="col-sm-12">
    	<div id="msg"></div>
		<div class="alert alert-info font-big"><i class="fa fa-exclamation-circle fa-fw"></i> This plugin is created by <strong><?php echo $authorName; ?> Bin Khalid</strong> and it is absolutely free of cost. We just need your feedback. <strong>Thanks!</strong></div>
    </div>
    <div class="col-sm-8">
    	<div class="panel panel-primary">
        	<div class="panel-heading"><i class="fa fa-cogs"></i> Settings and Upload</div>
            <div class="panel-body">
            	<h1><i class="fa fa-file-archive-o"></i> All pdf Files. <kbd id="count"><?php echo count($getData); ?></kbd></h1><br />
                <div class="table-responsive">
                    <table id="dataTables" datatable="" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="15" align="left">Sr#</th>
                                <th align="left">File Name</th>
                                <th align="center">Shortcode</th>
                                <th width="25" align="center">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							if(count($getData)>0){
                                foreach($getData as $val){
                                    $s++;
									if($getAdvData[0]->ajaxStatus==1){
										$code	=	"[z_pdf_popup id=".$val->file_token." btntitle='".$getAdvData[0]->btnName."' class='".$getAdvData[0]->btnClass." ajax' title='".$getAdvData[0]->btnTitle."']";
									}else{
										$code	=	"[z_pdf_popup link=".zPDFpopupViewer_UPLOADS_URL.$val->filename." target='".$getAdvData[0]->windowTarget."' btntitle='".$getAdvData[0]->btnName."' class='".$getAdvData[0]->btnClass."' title='".$getAdvData[0]->btnTitle."']";
									}
									?>
                                    <script type="text/javascript">
                                        jQuery(document).ready(function(e) {
                                            jQuery("#delete_<?php echo $val->id; ?>").on("click",function(){
                                                if(confirm('Are you sure?')){
                                                    jQuery.ajax({
                                                        url:'<?php echo zPDFpopupViewer_PLUGIN_MIAN_URL; ?>ajax/actions.ajax.php?delete=ok&id=<?php echo $val->id; ?>',
														success: function(data){
                                                            a	=	data.split('|^***^|');
                                                            if(a[1]=<?php echo $val->id; ?>){
                                                                jQuery("#msg").html(a[0]);
                                                                jQuery("#<?php echo $val->id; ?>").fadeOut('slow');
                                                                c	=	jQuery("#count").html()-1;
                                                                jQuery("#count").html(c);
                                                                if(c!=0){}else{
                                                                    jQuery("#nofound").html('<td colspan="5"><div class="text-center"><strong>No pdf(s) Found!</strong></div></td>');
                                                                }
                                                            }else{
                                                                jQuery("#msg").html(a[0]);
                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                        });
                                    </script>
                                    <tr id="<?php echo $val->id; ?>">
                                        <td><?php echo $s; ?></td>
                                        <td><i class="fa fa-file-archive-o"></i> <?php echo $val->filename; ?></td>
                                        <td><div class="input-group"><input type="text" id="copyTarget_<?php echo $val->id; ?>" class="form-control" value="<?php echo $code; ?>"><span class="input-group-btn"><button class="btn btn-info" data-clipboard-action="copy" data-clipboard-target="#copyTarget_<?php echo $val->id; ?>" id="copyButton" type="button">Copy</button></span></div></td>
                                        <td align="center"><a href="javascript:void(0);" class="btn btn-danger" id="delete_<?php echo $val->id; ?>" data-toggle='tooltip' title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                                    </tr>
                            <?php
                                }
                            }else{
                                echo '<tr><td colspan="5"><div class="text-center"><strong>No PDF(s) Found!</strong></div></td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
				</div>
                <div class="clearfix"></div>
                <hr />
                <h2><i class="fa fa-arrows"></i> Drag & Drop multiple files up to <kbd><?php echo $getAdvData[0]->parallelUpload; ?></kbd> </h2>
                <div class="col-md-12">
                	<script type="text/javascript">
                    jQuery(document).ready(function(e) {
						//check uncheck script
						<?php if($getAdvData[0]->ajaxStatus==1){?>
							jQuery("#windowTarget").hide();
						<?php }else{ ?>
							jQuery("#windowTarget").show();
						<?php } ?>
						$('input[type="checkbox"]').click(function(){
							if(jQuery("#ajaxStatus").prop("checked") == true){
								jQuery("#windowTarget").hide();
							}
							else if(jQuery("#ajaxStatus").prop("checked") == false){
								jQuery("#windowTarget").show();
							}
						});
						//Submition of advance settings
						jQuery("#pdfAdvanceSettings").on("submit",function(){
							jQuery("html, body").animate({ scrollTop: 0 }, 600);
							jQuery.ajax({
								type:'POST',
								url:'<?php echo zPDFpopupViewer_PLUGIN_MIAN_URL; ?>ajax/actions.ajax.php?advSettings=ok',
								data:jQuery("#pdfAdvanceSettings").serialize(),
								success: function(data){
									a	=	data.split('|^***^|');
									if(a[1]==1){
										jQuery("#msg").html(a[0]);
										setTimeout(function(){window.location.href="";},500);
									}else{
										jQuery("#msg").html(a[0]);
									}
								}
							});
						});
						//Dropzone script
                        Dropzone.autoDiscover = false;
                        var myDropzone = new Dropzone("div#myDrop", 
                         { 
                             paramName: "files", // The name that will be used to transfer the file
                             addRemoveLinks: true,
                             uploadMultiple: true,
                             autoProcessQueue: false,
                             parallelUploads: <?php echo $getAdvData[0]->parallelUpload; ?>,
                             maxFilesize: <?php echo $getAdvData[0]->maxSize; ?>, // MB
                             acceptedFiles: "<?php echo $getAdvData[0]->extnAllows; ?>",
                             url: "<?php echo zPDFpopupViewer_PLUGIN_MIAN_URL; ?>ajax/actions.ajax.php?upload=ok"
                         });
                          /* Add Files Script*/
                         myDropzone.on("success", function(file, message){
                            var input = document.createElement("input");
                            input.type = "hidden";
                            input.name = "rename_files[]";
                            input.value = file.xhr.responseText;
                            file.previewTemplate.appendChild(input);
                            setTimeout(function(){window.location.href="";},500);
                         });
                         
						 myDropzone.on("error", function (data) {
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
                         
                         jQuery("#add_file").on("click",function (){
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
                    <button type="button" id="add_file" class="btn btn-primary" name="submit"><i class="fa fa-upload"></i> Upload File(s)</button>
                    <div class="clearfix"></div>
                </div> <!--/.col-md-6-->
            </div>
        </div>
    </div> <!--/.col-sm-8-->
    <div class="col-sm-4">
    	<div class="panel panel-primary">
        	<div class="panel-heading"><i class="fa fa-cogs"></i> Advance Settings</div>
            <div class="panel-body">
            	<form method="post" id="pdfAdvanceSettings" onsubmit="return false;">
                	<div class="form-group">
                        <div class="col-sm-5"><label class="control-label">Max. File size</label></div>
                        <div class="col-sm-7"><input type="text" class="form-control" name="maxSize" value="<?php echo isset($getAdvData[0]->maxSize)?$getAdvData[0]->maxSize:'20'; ?>" placeholder="Max. file size" /><i class="help-block">Only accept integer value in MB's!</i></div>
                        <div class="clearfix"></div>
                   	</div>
                    <div class="form-group">
                        <div class="col-sm-5"><label class="control-label">Parallel Uploads</label></div>
                        <div class="col-sm-7"><input type="text" class="form-control" name="parallelUpload" value="<?php echo isset($getAdvData[0]->parallelUpload)?$getAdvData[0]->parallelUpload:'50'; ?>" placeholder="Parallel upload files" /><i class="help-block">Only accept integer value!</i></div>
                        <div class="clearfix"></div>
					</div>
                    <div class="form-group">
                        <div class="col-sm-5"><label class="control-label">Extension allows</label></div>
                        <div class="col-sm-7"><input type="text" class="form-control" name="extnAllows" value="<?php echo isset($getAdvData[0]->extnAllows)?strtoupper($getAdvData[0]->extnAllows):'.PDF'; ?>" readonly="readonly" /><i class="help-block">Default extension is ".PDF", We will be back with more features!</i></div>
                        <div class="clearfix"></div>
					</div>
                    <div class="form-group">
                        <div class="col-sm-5"><label class="control-label">Button Name</label></div>
                        <div class="col-sm-7"><input type="text" class="form-control" name="btnName" value="<?php echo isset($getAdvData[0]->btnName)?$getAdvData[0]->btnName:'Preview PDF'; ?>" /><i class="help-block">Default Name is "Preview PDF"!</i></div>
                        <div class="clearfix"></div>
					</div>
                    <div class="form-group">
                        <div class="col-sm-5"><label class="control-label">Button Title</label></div>
                        <div class="col-sm-7"><input type="text" class="form-control" name="btnTitle" value="<?php echo isset($getAdvData[0]->btnTitle)?$getAdvData[0]->btnTitle:'PDF Preview'; ?>" /><i class="help-block">Default title is "PDF Preview", You can change title!</i></div>
                        <div class="clearfix"></div>
					</div>
                    <div class="form-group">
                        <div class="col-sm-5"><label class="control-label">Button Custom Class</label></div>
                        <div class="col-sm-7"><input type="text" class="form-control" name="btnClass" value="<?php echo isset($getAdvData[0]->btnClass)?$getAdvData[0]->btnClass:'btn-pdf ajax'; ?>" /><i class="help-block">"btn-pdf" is a default btn class create your own and place class name here!</i></div>
                        <div class="clearfix"></div>
					</div>
                    <div class="form-group">
                        <div class="col-sm-5"><label class="control-label">Enable ajax (popup)</label></div>
                        <div class="col-sm-7"><input type="checkbox" name="ajaxStatus" id="ajaxStatus" <?php if($getAdvData[0]->ajaxStatus==1){echo 'checked="checked"';}else{} ?> value="1" /><i class="help-block">Default ajax popup is enable, You can open PDF file in a saprate window by unchecking this box!</i></div>
                        <div class="clearfix"></div>
					</div>
                    <div class="form-group" id="windowTarget">
                        <div class="col-sm-5"><label class="control-label">Window target</label></div>
                        <div class="col-sm-7"><select name="windowTarget" class="form-control"><option value="_self" <?php if($getAdvData[0]->windowTarget=="_self"){echo 'selected="selected"';}else{} ?>>Self</option><option value="_blank" <?php if($getAdvData[0]->windowTarget=="_blank"){echo 'selected="selected"';}else{} ?> >Blank</option></select><i class="help-block">Default ajax popup is enable, You open PDF file in saprate window by unchecking this box!</i></div>
                        <div class="clearfix"></div>
					</div>
                    <div class="form-group">
                        <div class="col-sm-5"><label class="control-label">&nbsp;</label></div>
                        <div class="col-sm-7"><button type="submit" name="submitAdv" class="btn btn-block btn-primary">Save Settings</button></div>
                        <div class="clearfix"></div>
					</div>
                </form>
            </div>
        </div>
    </div> <!--/.col-sm-4-->
    
    <div class="clearfix"></div>
</div> <!--/.zPDFpopupViewer-contaienr-->