document.addEventListener("DOMContentLoaded", function(event) {
	$('body.packageloadorder .js-floppy-o').on('click',function(e) {
		e.preventDefault();

		var data = $("#mainform").mvcForm2Obj();

		$.ajax({
			type: "POST",
			url: '/admin/configure/package_load_order/save',
			data: data,
			success: function(data) {
				if (data.err == false) {
					/* good - redirect */
					mvc.redirect('/admin/configure/package_load_order');
				} else {
					/* error */
					$.noticeAdd({"text":"Error Saving","stay":true,"type":"danger"});
				}
			},
		});
	});
}); /* end onload */