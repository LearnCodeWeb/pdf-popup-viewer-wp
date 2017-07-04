$.fn.dataTable.ext.errMode = 'none';
jQuery(document).ready(function(e) {
	jQuery('#dataTables').dataTable({
        "ordering": false,
        "info":     false,
		aLengthMenu: [
        [5, 10, 20, 50, -1],
        [5, 10, 20, 50, "All"]
		],
		//iDisplayLength: 5,
	});
	jQuery(e.trigger).attr('title', 'Copied');
});

var clipboard = new Clipboard('#copyButton');
clipboard.on('success', function(e) {
	console.log(e);
	console.info('Action:', e.action);
    console.info('Text:', e.text);
    console.info('Trigger:', e.trigger);

    e.clearSelection();
});
clipboard.on('error', function(e) {
	console.log(e);
	console.error('Action:', e.action);
    console.error('Trigger:', e.trigger);
});

var oTable;

jQuery(document).ready(function() {
	jQuery('.showAll').click( function () {
		var oSettings = oTable.fnSettings();
		oSettings._iDisplayLength = -1;
		oTable.fnDraw();
	});
	
	oTable = jQuery('#dataTables').dataTable();
});