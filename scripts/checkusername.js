function checkUsername(username, path, option, current_acc) {

	expression = /(&\B)|(^&)|(#\B)|(^#)/;
	if (expression.exec(username)) {
		username = 'erro';
	}

	$.get(DEFAULT_URL + "/search_username.php", {
		option: option,
		username: username,
		path: path,
        current_acc : current_acc
	}, function (response) {
		$('#checkUsername').html(response);
	});

}