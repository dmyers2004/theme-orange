document.addEventListener("DOMContentLoaded", function(event) {
	$('body.loadorder .js-floppy-o').on('click',function(e) {
		e.preventDefault();

		var data = $("#mainform").mvcForm2Obj();

		$.ajax({
			type: "POST",
			url: '/admin/configure/packages/load_order_save',
			data: data,
			success: function(data) {
				if (data.err == false) {
					/* good - redirect */
					mvc.redirect('/admin/configure/packages/load-order');
				} else {
					/* error */
					$.noticeAdd({"text":"Error Saving","stay":true,"type":"danger"});
				}
			},
		});
	});

	/* so we don't need to require plugin_filter_input we will add it manually */
	$('input.editfield').keypress(function(event) {
		var controlKeys = [8, 9, 13, 35, 36, 37, 39];
		var isControlKey = controlKeys.join(",").match(new RegExp(event.which));

		if (!event.which || (48 <= event.which && event.which <= 57) || isControlKey) {
			switch($(this).val().length) {
				case 0:
					if (event.which == 48) {
						event.preventDefault();
					}
				break;
				case 2:
					if (event.which != 48) {
						event.preventDefault();
					}
				break;
				case 3:
					event.preventDefault();
				break;
			}

			return;
		} else {
			event.preventDefault();
		}
	});

}); /* end onload */