var ret = false;

$(document).ready(function(e) {
	var singupDiv   = $('#singUp');
	var fName       = $('#fName');
	var lName       = $('#lName');
	var userName    = $('#userName');
	var password    = $('#password');
	var password2   = $('#password2');
	var email 	    = $('#email');
	var sex 	    = $('.sex');
	var sexM 	    = $('#sexM');
	var sexF 	    = $('#sexF');
	var agree       = $('#agree');
	var singupToken = $('#singupToken').val();

	setTimeout(function() {
		domain = config.domain;
	}, 200);

	fName.on('change', function(e) {
		checkName($(this), 'F');
	});

	lName.on('change', function(e) {
		checkName($(this), 'L');
	});

	userName.on('change', function(e) {
		if (checkUserName($(this))) {
			checkExists($(this), language.translate('validateUniq', [language.translate('formLabelUserName')]));
		}
	});

	password.on('change', function(e) {
		checkPasswords($(this), 1);
	});

	password2.on('change', function(e) {
		checkPasswords($(this), 2);
	});

	email.on('change', function(e) {
		if (checkEmail($(this))) {
			if (checkEmail($(this))) {
				checkExists($(this), language.translate('validateUniq', [language.translate('formLabelEmail')]));
			}
		}
	});

	sex.on('change', function(e) {
		checkSex(sexM, sexF, sex);
	});

	agree.on('change', function(e) {
		checkAgree(agree);
	});

	$('#singupBtn').on('click', function(e) {
		e.preventDefault();
		var run = true;


		if (!checkName(fName, 'F')) {
			// console.log('fName');
			run = false;
		}
		if (!checkName(lName, 'L')) {
			// console.log('fName');
			run = false;
		}
		if (checkUserName(userName)) {
			checkExists(userName, language.translate('validateUniq', [language.translate('formLabelUserName')]), function(rez) {
				if (!rez) {
					// console.log('userName');
					run = false;
				}
			});
		} else {
			// console.log('userName');
			run = false;
		}
		if (!checkPasswords(password, 1)) {
			// console.log('password');
			run = false;
		}
		if (!checkPasswords(password2, 2)) {
			// console.log('password2');
			run = false;
		}
		if (checkEmail(email)) {
			checkExists(email, language.translate('validateUniq', [language.translate('formLabelEmail')]), function(rez) {
				if (!rez) {
					// console.log('email');
					run = false;
				}
			});
		} else {
			// console.log('email');
			run = false;
		}

		if (!checkSex(sexM, sexF, sex)) {
			// console.log('sex');
			run = false;
		}
		if (!checkAgree(agree)) {
			// console.log('agree');
			run = false;
		}

		if (run) {
			// console.log('validation passed');
			$(this).addClass('loading').attr({disabled: true});
			$.post(domain+'singup/run', {
				fName: fName.val(),
				lName: lName.val(),
				userName : userName.val(),
				password : password.val(),
				password2 : password2.val(),
				email : email.val(),
				sex : (sexM.is(':checked')) ? 'm' : 'f',
				singupToken : singupToken
			}, function(output) {
				$('#singupBtn').removeClass('loading').attr({disabled: false});
				// console.log(output);
				singupDiv.children('.PUbody').addClass(output.status);
				if (output.status == 'success') {
					singupDiv.children('.PUtitle').html(language.translate('congratulations'));
					singupDiv.children('.PUbody').html(output.msg);
				} else if (output.status == 'error') {
					singupDiv.children('.PUtitle').html(language.translate('ops'));
					if (output.msg == '') {
						for (var e in output.errors) {
							errorHandler($('#'+e), output.errors[e]);
						}
					} else {
						singupDiv.children('.PUbody').html(output.msg);
					}
				}
			}, 'json');
		} else {

		}
	});
});

function errorHandler (id, error) {
	id.parent().children('.feedBeck').remove();
	var feedBeck = $('<div>', {class: 'feedBeck error'});
	feedBeck.html(error);
	id.removeClass('ok loading');
	id.addClass('error');
	id.parent().append(feedBeck);
}

function okHandler (id) {
	id.removeClass('error loading');
	id.addClass('ok');
	id.parent().children('.feedBeck').remove();
}

function loaderHandler (id) {
	id.removeClass('error ok').addClass('loading');
	id.next('.feedBeck').remove();
}

function ajax (url, data, callbeck) {
	var queryStr = '';

	for (var e in data) {
		queryStr += e+'='+data[e]+'&';
	}

	queryStr = queryStr.replace(/\&+$/, '');
	var hr = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	hr.open("POST", url, true);
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			callbeck(hr.responseText);
		}
	}
	hr.send(queryStr);
}

function checkExists (id, error, callbeck) {
	var value = id.val();
	var ret = false;

	loaderHandler(id);
	ajax(domain+'singup/checkExists/'+value, {userName: value}, function(output) {
		if (output == 'ok') {
			okHandler(id);
			ret = true;
		} else if (output == 'error') {
			errorHandler(id, error);
		} else {
			console.log(output);
		}

		if (typeof callbeck == 'function') {
			callbeck(ret);
		}
	});
}

function checkName (id, what) {
	var value = $.trim(id.val());
	var regexp = /^[a-zA-Z]+$/;

	if (what == 'F') {
		if (value == '') {
			errorHandler(id, language.translate('validateRequired', [language.translate('formLabelFName')]));
		} else if (value.length < 3 || value.length > 25) {// console.log('length');
			errorHandler(id, language.translate('validateLengthRange', [language.translate('formLabelFName'), '3', '25']));
		} else if (!regexp.test(value)) {
			errorHandler(id, language.translate('validateAlpha', [language.translate('formLabelFName')]));
		} else {
			okHandler(id);
			return true;
		}
	} else if (what == 'L') {
		if (value == '') {
			okHandler(id);
			return true;
		} else if (value.length > 25) {
			errorHandler(id, language.translate('validateMaxLength', [language.translate('formLabelLName'), '25']));
		} else if (!regexp.test(value)) {
			errorHandler(id, language.translate('validateAlpha', [language.translate('formLabelLName')]));
		} else {
			okHandler(id);
			return true;
		}
	}
	return false;
}

function checkUserName (id) {
	var value = id.val();
	var regexp = /^[a-zA-Z0-9-_.]+$/;

	if ($.trim(value) == '') {
		errorHandler(id, language.translate('validateRequired', [language.translate('formLabelUserName')]));
	} else if (value.length < 4 || value.length > 32) {
		errorHandler(id, language.translate('validateLengthRange', [language.translate('formLabelUserName'), '4', '32']));
	} else if (!regexp.test(value)) {
		errorHandler(id, language.translate('validateAlNumCustom', [language.translate('formLabelUserName'), '_-.']));
	} else {
		return true;
	}
	return false;
}

function checkPasswords(id, p) {
	var value = $.trim(id.val());

	if (p==1) {
		value = value.length;
		if (value == 0) {
			errorHandler(id, language.translate('validateRequired', [language.translate('formLabelPassword')]));
		} else if (value < 6) {
			errorHandler(id, language.translate('validateMinLength', [language.translate('formLabelPassword'), '6']));
		} else {
			okHandler(id);
			return true;
		}
	} else if (p==2) {
		if ($.trim(value) == '') {
			errorHandler(id, language.translate('validateRequired', [language.translate('formLabelPassword2Validation')]));
		} else if ($('#password').val() != value) {
			errorHandler(id, language.translate('validateMaches', [language.translate('formLabelPasswords')]));
		} else {
			okHandler(id);
			return true;
		}
	}
	return false;
}

function checkEmail(id) {
	var value = id.val();
	var regexp = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

	if ($.trim(value) == '') {
		errorHandler(id, language.translate('validateRequired', [language.translate('formLabelEmail')]));
	} else if (!regexp.test(value)) {
		errorHandler(id, language.translate('validateValidFormat', [language.translate('formLabelEmail')]));
	} else {
		return true;
	}
	return false;
}

function checkSex(sexM, sexF, sex) {
	if (!sexM.is(':checked') && !sexF.is(':checked')) {
		errorHandler(sex, language.translate('validateRequired', [language.translate('formLabelSexValidation')]));
	} else {
		okHandler(sex);
		return true;
	}
	return false;
}

function checkAgree (agree) {
	if (agree.is(':checked')) {
		okHandler(agree);
		return true;
	} else {
		errorHandler(agree, language.translate('validateNotAgree'));
	}
	return false;
}