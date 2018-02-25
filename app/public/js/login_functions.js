$(document).ready(function(e) {
	var userName = $('#loginUserName');
	var password = $('#loginPassword');
	var rememberMe = $('#rememberMe');
	var login = $('#loginBtn');
	var loginToken = $('#loginToken');
	var error = $('.error');
	var to;

	userName.on('keyup', function(e) {
		var value = $.trim(userName.val());
		if (value.length >= 3) {
			clearTimeout(to);
			to = setTimeout(function() {
				loaderHandler(userName);
				$.post('login/checkUserName', {
					userName: value,
					loginToken: loginToken.val()
				}, function(output){
					if (output.status == 'ok' ) {
						okHandler(userName);
					} else if (output.status == 'error' ) {
						errorHandler(userName, output.msg);
					}
				}, 'json');
			}, 2000);
		}
    });

	password.on('keyup', function(e) {
		if (password.val().length >= 6) {
			clearTimeout(to);
			to = setTimeout(function() {
				loaderHandler(password);
				$.post('login/checkPassword', {
					userName: userName.val(),
					password: password.val(),
					loginToken: loginToken.val()
				}, function(output){
					if (output.status == 'ok') {
						okHandler(password);
					} else if (output.status == 'error' ) {
						errorHandler(password, output.msg);
					}
				}, 'json');
			}, 2000);
		}
    });

    $(document.body).on('click', '#loginBtn', function(e) {
    	e.preventDefault();

		if (password.val().length >= 6) {
			loaderHandler(userName);
			loaderHandler(password);
			$.post('login/run', {
				userName: userName.val(),
				password: password.val(),
				loginToken: loginToken.val(),
				rememberMe: (rememberMe.is(':checked')) ? 'on' : ''
			}, function(output){
				if (output.status == 'ok') {
					$('body').fadeOut(1500, function () {
						$.get(output.redirect, function(data) {
							document.open();
							document.write(data);
							document.close();
							$('body').hide();
							$('body').fadeIn(1500);
						});
					});
				} else if (output.status == 'error' ) {
					switch (output.what) {
						case 'userName':
						password.removeClass('loading');
						errorHandler(userName, output.msg);
						break;
						case 'password':
						userName.removeClass('loading');
						errorHandler(password, output.msg);
						break;
						case 'noInput':
						errorHandler(userName, output.msg);
						errorHandler(password, output.msg);
						break;
					}


				}
			}, 'json');
		}
    });

	function errorHandler (id, err) {
		id.removeClass('ok loading').addClass('error');
		error.html(err);
	}

	function okHandler (id) {
		id.removeClass('error loading').addClass('ok');
		error.html('');
	}

	function loaderHandler (id) {
		id.removeClass('error ok').addClass('loading');
		error.html('');
	}
});
