$(function() {
	var profileInfo = $('#profileInfo');
	var fName = $('#fName');
	var lName = $('#lName');
	var email = $('#email');
	var sexM = $('#sexM');
	var sexF = $('#sexF');
	var sex = $('.sex');
	var updateToken = $('#updateToken');
	var showPasswordFormID = '#showPasswordForm';
	var showPasswordForm = $(showPasswordFormID);
	var cancelPasswordFormID = '#cancelPasswordForm';
	var passwsordForm = $('#passwsordForm');
	var oldPassword = $('#oldPassword');
	var newPassword = $('#newPassword');
	var newPassword2 = $('#newPassword2');
	var changePassword = $('#changePassword');
	var updateField = $('.updateField');
	var docBody = $('body');
	var userName = $('#userName').text();
	var oldValue;
	var domain;

	setTimeout(function() {
		domain = config.domain;
	}, 200);

	updateField.on({
		focus: function(e) {
			oldValue = $(this).val();
		},
		change: function(e) {
			var self = $(this);
			var fieldLable = self.parent().children().eq(0).children().eq(0).text();
			var field = self.attr('name');
			var value = self.val();

			if (confirm('Esti sigur ca vrei sa-ti schimbi '+fieldLable+'?')) {
				var run = false;

				if (validate()) {
					if (field == 'email') {
						checkEmailExist(function(data) {
							if (data.status == 'usedEmail') {
								alert('Adresa de email este deja "'+email.val()+'" folosita de catre altcineva!');
							} else if (data.status == 'db') {
								alert('Nu s-a putut aptualiza momenta, incearca mai tarziu!');
							} else if (data.status == 'ok') {
								update(field, value, fieldLable);
							}
						});

					} else {
						update(field, value, fieldLable);
					}
				}
			} else {
				self.text(oldValue);
			}

			oldValue = '';
		}
	});

	showPasswordForm.on('click', function(e) {
		e.preventDefault();
		var cancelLink = $('<a />');
		cancelLink.attr({href: '#', id: cancelPasswordFormID.replace('#', '')}).text('Anuleaza schimbarea parolei');
		$(this).addClass('hide').after(cancelLink);
		passwsordForm.removeClass('hide');
	});

	docBody.on('click', cancelPasswordFormID, function(e) {
		e.preventDefault();
		passwsordForm.addClass('hide');
		showPasswordForm.removeClass('hide');
		$(this).remove();
		oldPassword.val('');
		newPassword.val('');
		newPassword2.val('');
	});

	changePassword.on('click', function(e) {
		var oldPasswordValue = oldPassword.val();
		var newPasswordValue = newPassword.val();
		var confirmPasswordValue = newPassword2.val();

		if (oldPasswordValue.length == 0) {
			alert('Nu ai introdus Parola veche!');
		} else if (newPasswordValue.length == 0) {
			alert('Nu ai introdus Parola noua!');
		} else if (confirmPasswordValue.length == 0) {
			alert('Nu ai confirmat Parola noua!');
		} else if (oldPasswordValue.length < 6) {
			alert('Parola veche nu poate contine mai putin de 6 caractere!');
		} else if (newPasswordValue.length < 6) {
			alert('Parola noua nu poate contine mai putin de 6 caractere!');
		} else if (newPasswordValue != confirmPasswordValue) {
			alert('Parolele nu coincid!');
		} else {
			$.post(domain+'user/'+userName+'/changePassword', {
				oldPassword: oldPasswordValue,
				newPassword: newPasswordValue,
				confirmPassword: confirmPasswordValue,
				updateToken: updateToken.val()
			}, function(data) {
				if (data.status == 'ok') {
					alert('Parola a fost schimbata!');
				} else if (data.status == 'error') {
					alert(data.msg);
				}
				updateToken.val(data.newToken);
			}, 'json');
		}
	});

	function update (field, value, fieldLable) {
		$.post(domain+'user/'+userName+'/update', {
			field: field,
			value: value,
			updateToken: updateToken.val()
		}, function(data) {
			if (data.status == 'ok') {
				alert(fieldLable+' a fost schimbat(a)!');
			} else if (data.status == 'error') {
				alert(data.msg);
			}
			updateToken.val(data.newToken);
		}, 'json');
	}

	function validate () {
		var nameRegexp = /^[a-zA-Z]+$/;
		var emailRegexp = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

		if (fName.val() == '') {
			alert(language.translate('validateRequired', [language.translate('formLabelFName')]));
		} else if (fName.val().length < 3 || fName.val().length > 25) {// console.log('length');
			alert(language.translate('validateLengthRange', [language.translate('formLabelFName'), '3', '25']));
		}/* else if (!nameRegexp.test(fName.val())) {
			alert(language.translate('validateAlpha', [language.translate('formLabelFName')]));
		} */else if (lName.val().length > 25) {// console.log('length');
			alert(language.translate('validateMaxLength', [language.translate('formLabelLName'), '25']));
		} /*else if (!nameRegexp.test(lName.val())) {
			alert(language.translate('validateAlpha', [language.translate('formLabelLName')]));
		}*/ else if ($.trim(email.val()) == '') {
			alert(language.translate('validateRequired', [language.translate('formLabelEmail')]));
		} else if (!emailRegexp.test(email.val())) {
			alert(language.translate('validateValidFormat', [language.translate('formLabelEmail')]));
		} else if (!sexM.is(':checked') && !sexF.is(':checked')) {
			alert(language.translate('validateRequired', [language.translate('formLabelSexValidation')]));
		} else {
			return true;
		}
		return false;
	}

	function checkEmailExist (callbeck) {
		$.post(domain+'user/'+userName+'/checkEmailExist', {
			email: email.val()
		}, function(data) {
			callbeck(data);
		}, 'json');
	}
});