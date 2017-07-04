<?php require_once("../../../../../wp-load.php");
include_once('../../url_path_config.php');
$hostPath		=	rtrim(ABSPATH,'/');
$current_user 	= 	wp_get_current_user();
$user			=	$current_user->user_login;
if(!empty($_FILES) and $_REQUEST['upload']=='ok')
{
	$valnum = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ'
                     .'abcdefghijklmnopqrstuvwxyz'
                     .'0123456789'); // and any other characters
    //shuffle($seed);
    $rand = '';
    foreach(array_rand($valnum, 10) as $k) $rand .= $valnum[$k];
	$n			=	0;
	$s			=	0;
	foreach($_FILES['files']['name'] as $val)
	{
		$s++;
		$filesName		=	str_replace(" ","-",$_FILES['files']['name'][$n]);
		$files			=	explode(".",$filesName);
		$File_Ext   	=   substr($_FILES['files']['name'][$n], strrpos($_FILES['files']['name'][$n],'.'));
		if($File_Ext==".pdf")
		{
			$fileName	=	$files[0].'-'.$s.time().$File_Ext;
			$path		=	trim(zPDFpopupViewer_UPLOADS_PATH.$fileName);
			move_uploaded_file($_FILES['files']['tmp_name'][$n],$path);
			
			global $wpdb;
			$table 	= 	$wpdb->prefix . "pdffiles";
			$data	=	array(
							'filename'=>trim($fileName),
							'file_token'=>trim($rand),
							'date'=>date('y-m-d'),
							'user'=>trim($user),
							'ajaxStatus' => 1,
							);
			$wpdb->insert($table, $data);
			echo '<div class="notice notice-success"><i class="fa fa-thumbs-up"></i> Uploaded seccessfully</div>|^***^|1|^***^|';
		}
		else
		{
			echo '<div class="notice notice-error"><i class="fa fa-explanation-circle"></i> Extension not good <strong>Please try again.</strong></div>|^***^|2|^***^|';
			exit();
		}
		$n++;
	}
}
if(isset($_REQUEST['delete']) and $_REQUEST['delete']=='ok'){
	extract($_REQUEST);
	global $wpdb;
	$table 		= 	$wpdb->prefix . "pdffiles";
	$getData 	= 	$wpdb->get_results('SELECT * FROM '.$table.' WHERE 1 AND id='.$id.'');
	if(count($getData)>0){
		@unlink(zPDFpopupViewer_UPLOADS_PATH.$getData[0]->filename);
		$where	=	array('id'=>$id);
		$wpdb->delete( $table, $where);
		echo '<div class="notice notice-success"><i class="fa fa-thumbs-up"></i> Deleted Successfully.</div>|^***^|'.$id.'|^***^|';
		exit;
	}else{
		echo '<div class="notice notice-error"><i class="fa fa-explanation-circle"></i> There is some thing wrong <strong>Please try again.</strong></div>|^***^|0|^***^|';
		exit;
	}
}

if(isset($_REQUEST['advSettings']) and $_REQUEST['advSettings']=='ok'){
	extract($_REQUEST);
	global $wpdb;
	$advTable 		= 	$wpdb->prefix . "pdffiles_advance_settings";
	$getData 	= 	$wpdb->get_results('SELECT * FROM '.$advTable.'');
	if(count($getData)>0){
		$advData	=	array(
			'maxSize' => $maxSize,
			'parallelUpload' => $parallelUpload,
			'extnAllows' => trim(strtolower($extnAllows)),
			'btnName' => trim($btnName),
			'btnTitle' => trim($btnTitle),
			'btnClass' => trim($btnClass),
		);
		$wpdb->update($advTable, $advData, array('id'=>1), $format = null, $where_format = null);
		echo '<div class="notice notice-success"><i class="fa fa-thumbs-up"></i> Settings save successfully.</div>|^***^|1|^***^|';
		exit;
	}else{
		echo '<div class="notice notice-error"><i class="fa fa-explanation-circle"></i> There is some thing wrong <strong>Please try again.</strong></div>|^***^|0|^***^|';
		exit;
	}
}

if(isset($_REQUEST['sendName']) and $_REQUEST['sendName']=='popupStatus'){
	extract($_REQUEST);
	global $wpdb;
	$advTable 		= 	$wpdb->prefix . "pdffiles";
	$getData 	= 	$wpdb->get_results('SELECT * FROM '.$advTable.'');
	if(count($getData)>0){
		$advData	=	array(
			'ajaxStatus' => trim($sendStatus),
			'windowTarget' => "",
		);
		$wpdb->update($advTable, $advData, array('id'=>$sendID), $format = null, $where_format = null);
		echo '<div class="notice notice-success"><i class="fa fa-thumbs-up"></i> Settings save successfully.</div>|^***^|1|^***^|';
		exit;
	}else{
		echo '<div class="notice notice-error"><i class="fa fa-explanation-circle"></i> There is some thing wrong <strong>Please try again.</strong></div>|^***^|0|^***^|';
		exit;
	}
}


if(isset($_REQUEST['sendName']) and $_REQUEST['sendName']=='windowStatus'){
	extract($_REQUEST);
	global $wpdb;
	$advTable 		= 	$wpdb->prefix . "pdffiles";
	$getData 	= 	$wpdb->get_results('SELECT * FROM '.$advTable.'');
	if(count($getData)>0){
		$advData	=	array(
			'windowTarget' => trim($sendStatus),
		);
		$wpdb->update($advTable, $advData, array('id'=>$sendID), $format = null, $where_format = null);
		echo '<div class="notice notice-success"><i class="fa fa-thumbs-up"></i> Settings save successfully.</div>|^***^|1|^***^|';
		exit;
	}else{
		echo '<div class="notice notice-error"><i class="fa fa-explanation-circle"></i> There is some thing wrong <strong>Please try again.</strong></div>|^***^|0|^***^|';
		exit;
	}
}

