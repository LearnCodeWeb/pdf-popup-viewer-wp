<?php
error_reporting(0);
$pdfunpdfVersion 	= 	"1.0.0";
$authorName			=	'Zaid';
$upload = wp_upload_dir();
$upload_dir = $upload['basedir'];
$upload_url = $upload['baseurl'];

define('zPDFpopupViewer_DIR_NAME','zpdf_upload');
define('zPDFpopupViewer_PLUGIN_PATH', rtrim(plugin_dir_path(__FILE__),'/'));
define('zPDFpopupViewer_UPLOADS_PATH', wp_normalize_path($upload_dir.'/'.zPDFpopupViewer_DIR_NAME.'/'));
define('zPDFpopupViewer_UPLOADS_URL', $upload_url.'/'.zPDFpopupViewer_DIR_NAME.'/');
define('zPDFpopupViewer_PLUGIN_URL',plugin_dir_url( __FILE__ ));
define('zPDFpopupViewer_PLUGIN_MIAN_URL',zPDFpopupViewer_PLUGIN_URL.'admin/'); // Plugin Main panel URL
define('zPDFpopupViewer_PLUGIN',dirname(__FILE__));
$url	=	explode("=",$_SERVER['QUERY_STRING']);