<?php
function zPDFpopupViewer_admin_bootstrap() {
	//All compulsory style sheets are added.
    wp_register_style( 'bootstrap-plugin', zPDFpopupViewer_PLUGIN_MIAN_URL . 'bootstrap/css/bootstrap.min.css', false, '' );
	wp_register_style( 'font-awesome-plugin', zPDFpopupViewer_PLUGIN_MIAN_URL . 'font-awesome/css/font-awesome.min.css', false, '' );
	wp_register_style( 'dropzone-style', zPDFpopupViewer_PLUGIN_MIAN_URL . 'plugin/dropzone/dropzone.css', false, '' );
	wp_register_style( 'my-custom', zPDFpopupViewer_PLUGIN_MIAN_URL . 'css/custom.css', false, '1.0.0' );
	
	wp_enqueue_style('bootstrap-plugin');
	wp_enqueue_style('font-awesome-plugin');
	wp_enqueue_style('dropzone-style');
	wp_enqueue_style('my-custom');
	
	//All compulsory scripts are added
	wp_register_script('bootstrap-plugin-js', zPDFpopupViewer_PLUGIN_MIAN_URL . 'bootstrap/js/bootstrap.min.js', array ( 'jquery' ), '', true);
	wp_register_script('dropzone-plugin-js', zPDFpopupViewer_PLUGIN_MIAN_URL . 'plugin/dropzone/dropzone.js', array ( 'jquery' ), '', true);
	wp_register_script('datatables-plugin-js', zPDFpopupViewer_PLUGIN_MIAN_URL . 'js/jquery.dataTables.js', array ( 'jquery' ), '', true);
	wp_register_script('datatables-bootstrap-js', zPDFpopupViewer_PLUGIN_MIAN_URL . 'js/dataTables.bootstrap.js', array ( 'jquery' ), '', true);
	wp_register_script('clipboard-js', zPDFpopupViewer_PLUGIN_MIAN_URL . 'plugin/clipboard/clipboard.min.js', array ( 'jquery' ), '', true);
	wp_register_script('custom-js', zPDFpopupViewer_PLUGIN_MIAN_URL . 'js/custom.js', array ( 'jquery' ), '', true);
	
	wp_enqueue_script('bootstrap-plugin-js');
	wp_enqueue_script('dropzone-plugin-js');
	wp_enqueue_script('datatables-plugin-js');
	wp_enqueue_script('datatables-bootstrap-js');
	wp_enqueue_script('clipboard-js');
	wp_enqueue_script('custom-js');
}
if(isset($_GET['page']) and $_GET['page']==urldecode($url[1])){  //only add in admin panel
	add_action( 'admin_enqueue_scripts', 'zPDFpopupViewer_admin_bootstrap' );
}