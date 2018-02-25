$(function(e) {
	var forgetPasswordDiv   = $('#forgetPassword');
	var forgetPasswordError = $('#forgetPasswordError');
	var forgetPasswordEmail = $('#forgetPasswordEmail');
	var forgetPasswordToken = $('#forgetPasswordToken').val();
	var forgetPasswordBtn   = $('#forgetPasswordBtn');

	var resetPasswordError 	    = $('#resetPasswordError');
	var resetNewPassword 	    = $('#resetNewPassword');
	var resetConfirmNewPassword = $('#resetConfirmNewPassword');
	var resetPasswordToken 		= $('#resetPasswordToken').val();
	var resetPasswordBtn   		= $('#resetPasswordBtn');

	var emailRegexp = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

	forgetPasswordEmail.on('change keyup', function(e) {
		var value = forgetPasswordEmail.val();

		if (emailRegexp.test(value)) {
			loaderHandler(forgetPasswordEmail, forgetPasswordError);
			$.post('forgetPassword/checkEmail', {
				email: value,
				forgetPasswordToken: forgetPasswordToken
			}, function(output) {
				if (output.status == 'ok') {
					okHandler(forgetPasswordEmail, forgetPasswordError);
				} else if (output.status == 'error') {
					errorHandler(forgetPasswordEmail, output.msg, forgetPasswordError);
				}
			}, 'json');
		} else {
			errorHandler(forgetPasswordEmail, language.translate('validateValidFormat', [language.translate('formLabelEmail')]), forgetPasswordError);
		}
	});

	forgetPasswordBtn.on('click', function(e) {
		var value = forgetPasswordEmail.val();

		if (emailRegexp.test(value)) {
			$(this).addClass('loading').attr('disabled', true);
			$.post('forgetPassword/sendEmail', {
				email: value,
				forgetPasswordToken: forgetPasswordToken
			}, function(output) {
				forgetPasswordDiv.children('.PUbody').addClass(output.status);
				forgetPasswordDiv.children('.PUbody').html(output.msg);

				if (output.status == 'success') {
					forgetPasswordDiv.children('.PUtitle').html(language.translate('congratulations'));
				} else if (output.status == 'error') {
					forgetPasswordDiv.children('.PUtitle').html(language.translate('ops'));
				}
			}, 'json');
		} else {
			errorHandler(forgetPasswordEmail, language.translate('validateValidFormat', [language.translate('formLabelEmail')]), forgetPasswordError);
		}
	});

	//------------------------------------------------------------------------------

	resetNewPassword.on('change keyup', function(e) {
		var value = $.trim(resetNewPassword.val()).length;

		if (value == 0) {
			errorHandler(resetNewPassword, language.translate('validateRequired', [language.translate('formLabelNewPassword')]), resetPasswordError);
		} else if (value < 6) {
			errorHandler(resetNewPassword, language.translate('validateMinLength', [language.translate('formLabelNewPassword'), '6']), resetPasswordError);
		} else {
			okHandler(resetNewPassword, resetPasswordError);
		}
	});

	resetConfirmNewPassword.on('change keyup', function(e) {
		var value  = $.trim(resetConfirmNewPassword.val());
		var value2 = $.trim(resetNewPassword.val());

		if ($.trim(value) == '') {
			errorHandler(resetConfirmNewPassword, language.translate('validateRequired', [language.translate('formLabelConfirmNewPassword')]), resetPasswordError);
		} else if (value2 != value) {
			errorHandler(resetConfirmNewPassword, language.translate('validateMaches', [language.translate('formLabelConfirmNewPassword')]), resetPasswordError);
		} else {
			okHandler(resetConfirmNewPassword, resetPasswordError);
		}
	});

	resetPasswordBtn.on('click', function(e) {
		$(this).addClass('loading').attr('disabled', true);
		$.post(config.domain+'forgetPassword/saveNewPassword', {
			newPassword: resetNewPassword.val(),
			confirmNewPassword: resetConfirmNewPassword.val(),
			resetPasswordToken: resetPasswordToken,
			userID: $('#resetPasswordUserID').val()
		}, function(output) {
			resetPasswordBtn.removeClass('loading').attr('disabled', false);

			if (typeof output.redirect !== 'undefined') {
				window.location = output.redirect;
			} else if (output.status == 'error') {
				for (var e in output.msg) {
					errorHandler($('#'+e), output.msg[e]);
					if (e == 'newPassword') {
						errorHandler(resetNewPassword, output.msg[e], resetPasswordError);
					}
					if (e == 'confirmNewPassword') {
						errorHandler(resetConfirmNewPassword, output.msg[e], resetPasswordError);
					}
				}
			}
		}, 'json');
	});

	//----------------------------------------------------------------------------------

	function errorHandler (id, error, errorDiv) {
		id.addClass('error').removeClass('ok loading');
		errorDiv.html(error).show();
	}

	function okHandler (id, errorDiv) {
		id.removeClass('error loading').addClass('ok');
		errorDiv.html('').hide();
	}

	function loaderHandler (id, errorDiv) {
		id.removeClass('error ok').addClass('loading');
		errorDiv.html('').hide();
	}
});