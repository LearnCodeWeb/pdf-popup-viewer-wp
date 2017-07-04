<?php
defined( 'ABSPATH' ) OR exit; 
/*
Plugin Name: PDF Files Viewer In POPUP
Plugin URI: http://www.knns.com.pk
Description: PDF VIEWER - Free plugin for PDF popup viewer
Author: Zaid Bin Khalid
Version: 1.0.0
Author URI: http://www.knns.com.pk
*/
include_once('url_path_config.php');
include_once('admin/class.enqueue.php');

function z_pdf_viewer_shortcode($atts) {
  extract(
	shortcode_atts(
		array(
			'btntitle' => 'Preview PDF',
			'class' => 'btn-pdf',
			'title'=> 'PDF Viewer',
			'link' => 'javascript:void(0);',
			'id' => 'PDF ID Missing',
			'target' => '_blank',
			),
		$atts)
	);
	return '<style>.btn-pdf{background:#0282E5; width:90%; text-align:center; display:block; padding:8px; border:1px solid #0282E5; color:#FFF !important; text-decoration:none !important; margin:10px; font-weight:500; }</style><script>jQuery(document).ready(function(e) {jQuery("#'.$id.'").colorbox({width:"95%", height:"90%", fixed:true, scrolling: false, maxWidth:"850px", href:"'.zPDFpopupViewer_PLUGIN_URL."pdf_viewer.php?pdfFile=".$id.'"});});</script>
	<a href="'.$link.'" id="'.$id.'" class="'.$class.'" target="'.$target.'" title="'.$title.'" >'.$btntitle.'</a>';
}
add_shortcode('z_pdf_popup', 'z_pdf_viewer_shortcode');

function zPDFpopupViewer_dir_create_activate() {
   
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir = $upload_dir.'/'.zPDFpopupViewer_DIR_NAME;
    if (!is_dir($upload_dir)) {
       return mkdir( $upload_dir, 0755); // Folder permission set here
    }
}
register_activation_hook( __FILE__, 'zPDFpopupViewer_dir_create_activate' );

function zPDFpopupViewer_dir_delete() {
   
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir = $upload_dir.'/'.zPDFpopupViewer_DIR_NAME;
    if(is_dir($upload_dir)) {
		foreach (glob($upload_dir."/*.*") as $filename) {
			if (is_file($filename)) {
				unlink($filename);
			}
		}
		rmdir($upload_dir);
    }
}
register_deactivation_hook( __FILE__, 'zPDFpopupViewer_dir_delete' ); //register_deactivation_hook

//Create Plugins table for PDF file here.
function zPDFpopupViewer_db() {
	global $wpdb;
	$charset_collate	=	$wpdb->get_charset_collate();
	$table_name			=	$wpdb->prefix . 'pdffiles';
	$sql = "CREATE TABLE $table_name (
		id INT NOT NULL AUTO_INCREMENT,
		filename TEXT NOT NULL,
		file_token TEXT NOT NULL,
		date DATE NOT NULL,
		datetime TIMESTAMP NOT NULL,
		user VARCHAR(512) NOT NULL,
		ajaxStatus INT(1) NOT NULL DEFAULT '1', 
		windowTarget VARCHAR(25) NOT NULL,
		PRIMARY KEY (id)
	) $charset_collate;";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta($sql);
}
register_activation_hook( __FILE__, 'zPDFpopupViewer_db' ); //register_uninstall_hook

// Create PDF file settings table here.
function zPDFpopupViewer_adv_db() {
	global $wpdb;
	$charset_collate	=	$wpdb->get_charset_collate();
	$tableSettind		=	$wpdb->prefix . 'pdffiles_advance_settings';
	$sql = "CREATE TABLE $tableSettind ( 
			id INT NOT NULL AUTO_INCREMENT, 
			maxSize INT NOT NULL, 
			parallelUpload INT NOT NULL, 
			extnAllows VARCHAR(50) NOT NULL, 
			btnName VARCHAR(100) NOT NULL, 
			btnTitle VARCHAR(100) NOT NULL, 
			btnClass VARCHAR(100) NOT NULL, 
			datetime TIMESTAMP NOT NULL,
			PRIMARY KEY (id)
	) $charset_collate;";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta($sql);
	
	$data	=	array(
				'maxSize' => 2,
				'parallelUpload' => 50,
				'extnAllows' => '.pdf',
				'btnName' => 'Preview PDF',
				'btnTitle' => 'PDF Preview',
				'btnClass' => 'btn-pdf',

					);
	$wpdb->insert( $tableSettind, $data);
}
register_activation_hook( __FILE__, 'zPDFpopupViewer_adv_db' );

//truncate table on deactivation
function delete_row_on_diactivation(){
	global $wpdb;
	$table		=	$wpdb->prefix . 'pdffiles_advance_settings';
	$wpdb->query($wpdb->prepare('TRUNCATE TABLE '.$table.''));
}
register_deactivation_hook( __FILE__, 'delete_row_on_diactivation' );

//Drop plugins table here.
function zPDFpopupViewer_delete_db() {
	global $wpdb;
	$table_name = $wpdb->prefix . "pdffiles";
	$tableSettind = $wpdb->prefix . "pdffiles_advance_settings";
	
	$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
	$wpdb->query( "DROP TABLE IF EXISTS $tableSettind" );
	
	delete_option("my_plugin_db_version");
}
register_uninstall_hook( __FILE__,'zPDFpopupViewer_delete_db'); //register_uninstall_hook

// register menu
function zPDFpopupViewer_register_menu() {
 	/**
	 * Register a custom menu page.
	 */
	function zPDFpopupViewer_menu_page() {
		$x	=	explode("plugins",zPDFpopupViewer_PLUGIN_PATH);
		$pluginPerma	= wp_unslash($x[1]).'/admin/z-pdf-popup-viewer.php';
		add_menu_page(
			__( 'Custom Menu Title', '' ),
			'PDF Files Upload',
			'manage_options',
			$pluginPerma,
			'',
			'dashicons-media-default',
			25
		);
	}
	add_action( 'admin_menu', 'zPDFpopupViewer_menu_page' );
}
add_action( 'init', 'zPDFpopupViewer_register_menu' );

function themeslug_enqueue_style() {
	wp_enqueue_style( 'colorbox-css', zPDFpopupViewer_PLUGIN_MIAN_URL.'plugin/colorbox/colorbox.css', false );
}

function themeslug_enqueue_script() {
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'colorbox-js', zPDFpopupViewer_PLUGIN_MIAN_URL.'plugin/colorbox/jquery.colorbox-min.js', false );
}

add_action( 'wp_enqueue_scripts', 'themeslug_enqueue_style' );
add_action( 'wp_enqueue_scripts', 'themeslug_enqueue_script' );
