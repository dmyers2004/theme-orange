var mvc = (mvc) || {};
var o = (o) || {};

var pleaseWaitDiv = $('<div class="modal fade bs-example-modal-sm" id="myPleaseWait" tabindex="-1"role="dialog" aria-hidden="true" data-backdrop="static"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-header"><h4 class="modal-title"><span class="glyphicon glyphicon-time"></span> Processing</h4></div><div class="modal-body"><div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span class="sr-only"></span></div></div></div></div></div></div>');

/*
hide / show modals
pleaseWaitDiv.modal('show');
pleaseWaitDiv.modal('hide');
*/

/**
* On Ready
*/
$(function() {

	/* handle shift when selecting group access */
	$('input.js-shift-key').click(function(event) {
		if (event.shiftKey) {
			$('[data-group="' + $(this).data('group') + '"]').prop('checked',($(this).prop('checked') || false));
		}
	});

}); /* end onready */
