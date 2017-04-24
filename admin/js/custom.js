$.fn.dataTable.ext.errMode = 'none';
jQuery(document).ready(function(e) {
	jQuery('#dataTables').dataTable({
        "ordering": false,
        "info":     false,
		aLengthMenu: [
        [5, 10, 20, 50, -1],
        [5, 10, 20, 50, "All"]
		],
		iDisplayLength: 5,
	});
	jQuery(e.trigger).attr('title', 'Copied').tooltip('fixTitle').tooltip('show');
});

jQuery(function () {
	jQuery("body").tooltip({
		selector: '[data-toggle="tooltip"]',
		container: 'body'
	});
});

/*jQuery('[data-toggle="popover"]').popover({
    trigger: 'click',
    placement: 'left',
    container: 'body',
	html:true,
});

jQuery('[data-toggle=popover]').on('click', function (e) {
   jQuery('[data-toggle=popover]').not(this).popover('hide');
});*/


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