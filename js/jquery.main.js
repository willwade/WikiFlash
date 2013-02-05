$(document).ready(function () {
	$('.signup').click(function (e) {
		e.preventDefault();
		$('#modalwikivideo').modal({persist:true}); 
	});
  	
});
/**
 * When the close event is called, this function will be used to 'close'
 * the overlay, container and data portions of the modal dialog.
 *
 * The SimpleModal close function will still perform some actions that
 * don't need to be handled here.
 *
 * onClose callbacks need to handle 'closing' the overlay, container
 * and data.
 */
function modalClose (dialog) {
	dialog.data.fadeOut('slow', function () {
		dialog.container.hide('slow', function () {
			dialog.overlay.slideUp('slow', function () {
				$.modal.close();
			});
		});
	});
}

function modalCloseReturn (dialog) {
	dialog.data.fadeOut('slow', function () {
		dialog.container.hide('slow', function () {
			dialog.overlay.slideUp('slow', function () {
				document.location.href='http://otwikiflash.net/action/home';
				$.modal.close();
			});
		});
	});
}

