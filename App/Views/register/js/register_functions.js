var errorImg = '<img src="'+config.errorIcon+'" width="25" height="25">';
var okImg = '<img src="'+config.okIcon+'" width="25" height="25">';
var loaderImg = '<img src="'+config.loaderIcon+'">';
var registerLoaderImg = '<img src="'+config.loaderIcon+'">';
var ret = false;
var status;
var domain;

$(document).ready(function(e) {
	setTimeout(function() {
		domain = config.domain;
	}, 100);
	$('#userName').bind('change', function(e) {
		checkUserName($(this).val());
	});

	$('#password').change(function(e) {
		checkPasswords($(this).val(), 1);
	});

	$('#password2').change(function(e) {
		checkPasswords($(this).val(), 2);
	});

	$('#email').change(function(e) {
		checkEmail($(this).val());
	});

	$('.sex').change(function(e) {
		checkSex();
	});

	$('#singUpBtn').click(function(e) {
		e.preventDefault();
		registerUser();
	});
});

function checkUserName (value) {
	ret = false;

	var regexp = /^[a-zA-Z0-9-_.]+$/;
	if ($.trim(value) == '') {
		status = errorImg+'<div class="error">'+language.translate('validateRequired', [language.translate('formLabelUserName')])+'</div>';
	} else if (value.length < 3 || value.length > 25) {
		status = errorImg+'<div class="error">'+language.translate('validateLengthRange', [language.translate('formLabelUserName'), '3', '25'])+'</div>';
	} else if (!regexp.test(value)) {
		status = errorImg+'<div class="error">'+language.translate('validateAlNumCustom', [language.translate('formLabelUserName'), '_-.'])+'</div>';
	} else {
		$('#userNameFeedBeck').html(loaderImg);

		var url = domain+'singup/ajax';
		var sendQuery = 'jQueryCheck=userName&param='+value;

		var hr = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
		hr.open("POST", url, false);
		hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		hr.onreadystatechange = function() {
			if(hr.readyState == 4 && hr.status == 200) {
				output = hr.responseText;

				if (output == 'ok') {
					status = okImg;
					ret = true;
				} else if (output == 'error') {
					status = errorImg+'<div class="error">'+language.translate('validateUniq', [language.translate('formLabelUserName')])+'</div>';
				} else {
					alert(output);
				}
			}
		}
		hr.send(sendQuery);
	}
	$('#userNameFeedBeck').html(status);
	return ret;
}

function checkPasswords(value, p) {
	ret = false;
	var value = $.trim(value);

	if (p==1) {
		value = value.length;
		if (value == 0) {
			status = errorImg+'<div class="error">'+language.translate('validateRequired', [language.translate('formLabelPassword')])+'</div>';
		} else if (value < 6) {
			status = errorImg+'<div class="error">'+language.translate('validateMinLength', [language.translate('formLabelPassword'), '6'])+'</div>';
		} else {
			ret = true;
			status = okImg;
		}
		$('#passwordFeedBeck').html(status);
	} else if (p==2) {
		if ($.trim(value) == '') {
			status = errorImg+'<div class="error">'+language.translate('validateRequired', [language.translate('formLabelPassword2Validation')])+'</div>';
			ret = false;
		} else if ($('#password').val() != value) {
			status = errorImg+'<div class="error">'+language.translate('validateMaches', [language.translate('formLabelPasswords')])+'</div>';
		} else {
			ret = true;
			status = okImg;
		}
		$('#password2FeedBeck').html(status);
	}
	return ret;
}

function checkEmail(value) {
	ret = false;

	if ($.trim(value) == '') {
		status = errorImg+'<div class="error">'+language.translate('validateRequired', [language.translate('formLabelEmail')])+'</div>';
	} else if (value.length > 8) {
		$('#emailFeedBeck').html(loaderImg);

		var url = domain+'singup/ajax';
		var sendQuery = 'jQueryCheck=email&param='+value;

		var hr = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
		hr.open("POST", url, false);
		hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		hr.onreadystatechange = function() {
			if(hr.readyState == 4 && hr.status == 200) {
				output = hr.responseText;

				if (output == 'ok') {
					status = okImg;
					ret = true;
				} else if (output == 'error') {
					status = errorImg+'<div class="error">'+language.translate('validateUniq', [language.translate('formLabelEmail')])+'</div>';
				} else {
					alert(output);
				}
			}
		}
		hr.send(sendQuery);
	}
	$('#emailFeedBeck').html(status);

	return ret;
}

function checkSex() {
	ret = false;

	if ($('#m').attr('checked') || $('#f').attr('checked')) {
		ret = true;
		status = okImg;

		$('#dobDayFeedBeck, #dobMonthFeedBeck, #dobYearFeedBeck, #taraFeedBeck').html('');
		$('#dobDey').focus();
	} else {
		status = errorImg+'<div class="error">'+language.translate('validateRequired', [language.translate('formLabelSexValidation')])+'</div>';
	}
	$('#sexFeedBeck').html(status);
	return ret;
}

function checkAgree () {
	return ($('#agree').is(':checked')) ? true : false;
}

function registerUser () {
		var run = true;
		var userName = $('#userName').val();
		var password = $('#password').val();
		var password2 = $('#password2').val();
		var email = $('#email').val();
		var sex = ($('#m').attr('checked')) ? $('#m').val() : $('#f').val();
		var token = $('#token').val();

		if (!checkUserName(userName)) {
			alert('userName');
			run = false;
		} else if (!checkPasswords(password, 1)) {
			alert('password');
			run = false;
		} else if (!checkPasswords(password2, 2)) {
			alert('password2');
			run = false;
		} else if (!checkEmail(email)) {
			alert('email');
			run = false;
		} else if (!checkSex()) {
			alert('sex');
			run = false;
		} else if (!checkAgree()) {
			alert('agree');
			run = false;
		}

		if (run) {
			$('#singUpForm').html('<tr><td>' + registerLoaderImg + '</td></tr>');
			$.post(domain+'singup/run', {
				userName : userName,
				password : password,
				password2 : password2,
				email : email,
				sex : sex,
				token : token,
				jQuery : 'true'
			}, function(output) {
				$('#singUpForm').hide().html('<tr><td>' + output + '</td></tr>').fadeIn(1000);
			});
		} else {

		}
		return false;
	}