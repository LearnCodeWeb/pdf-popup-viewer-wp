<?php 
define( 'WP_USE_THEMES', false );
require('../../../wp-blog-header.php'); #modify to match path of that file on your server
include_once('url_path_config.php');
global $wpdb;
$charset_collate	=	$wpdb->get_charset_collate();
$tableName			=	$wpdb->prefix . 'pdffiles';
$query 		= 	'SELECT * FROM '.$tableName.' WHERE file_token="'.trim($_REQUEST['pdfFile']).'"';
$getData	=	$wpdb->get_results($query);
$fileName	=	explode("-",trim($getData[0]->filename));
$count		=	count($getData);
if($count==1){
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>PDF | <?php echo trim($fileName[0]); ?></title>
</head>
<style>iframe{width:98%; margin:0px auto; height:100%;}</style>
<body>
	<iframe src="https://docs.google.com/gview?url=<?php echo zPDFpopupViewer_UPLOADS_URL.trim($getData[0]->filename); ?>&embedded=true" frameborder="0"></iframe>
</body>
</html>
<?php }else{ ?>
	<div style="padding:30px; text-align:center; position: relative; top: 40%; background: #ccc; box-shadow:0px 0px 2px #333333 inset;"><strong>We did not find any file for view!</strong></div>
<?php } ?>