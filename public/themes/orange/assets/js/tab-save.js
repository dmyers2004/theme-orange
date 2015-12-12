document.addEventListener("DOMContentLoaded", function(event) {
	/* start tab handlers */
	/* setup normal tab actions */
	$('.js-tabs a').click(function(e) {
		e.preventDefault();
		$(this).tab('show');
	});

	/* save tab */
	$('.js-tabs li').on('click', function(e) {
		$.jStorage.set(jstab_stripTrailingSlash(location.pathname), $(e.target).attr('href'), 2592000);
	});

	var tab = ($.jStorage !== undefined) ? $.jStorage.get(jstab_stripTrailingSlash(location.pathname),'') : '';

	/* bunch of error handling incase it's not there */
	if (tab === '') {
		tab = '.js-tabs a:first';
	} else {
		tab = '.js-tabs a[href="' + tab + '"]';
	}

	if ($(tab).length > 0) {
		$(tab).tab('show');
	} else {
		if ($('.js-tabs a:first').tab !== undefined) {
			$('.js-tabs a:first').tab('show');
		}
	}
	/* end tab handlers */
});
	
function jstab_stripTrailingSlash(str) {
	if (str.substr(-1) == '/') {
		return str.substr(0, str.length - 1);
	}

return str;
}
