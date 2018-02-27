$(function(e) {
	$('#menu').on('click', function(e) {
		e.preventDefault();
		$('nav#navbar').slideToggle(500, 'swing');
	});

	$('#userInfoBtn').on('click', function(e) {
		e.preventDefault();
		$('#UIinner').slideToggle(500, 'swing');
	});

	$('.PUclose').on('click', function(e) {
		e.preventDefault();
		$(this).parent('.popup').fadeOut(2000, function(e) {
			$('.PUbg').hide();
		});
	});

	$('.alertclose').on('click', function(e) {
		e.preventDefault();
		$(this).parent('.alert').slideUp(800);
	});

	$('#newAccountLink').on('click', function(e) {
		e.preventDefault();
		showPupup('#singUp');
	});

	$('#forgetPasswordLink').on('click', function(e) {
		e.preventDefault();
		showPupup('#forgetPassword');
	});
});

function showPupup (e) {
	$(e).parent('.PUbg').show();
	$(e).css({display: 'tabel'}).show();
}

function flashMessage(type, msg, time) {
	let alertDiv = $('<div />');
	let alertBody = $('<span />');
	let alertCloseLink = $('<a />');

	alertBody.addClass('alertBody').text(msg);
	alertCloseLink.attr('href', '#').html('&times;');
	alertDiv.addClass('alert '+type).append(alertBody).append(alertCloseLink);
	$('body').prepend(alertDiv);

	window.scrollTo(0, 0);

	if (time !== false) {
		setTimeout(function() {
			alertDiv.slideUp(500).remove();
		}, (typeof time === 'number') ? time : 10000);
	}
}

function getInit (callbeck) {
	var url = domain+'ajax/jsGetInit';

	var hr = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	hr.open("GET", url, true);
	hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

	hr.onreadystatechange = function() {
		if(hr.readyState == 4 && hr.status == 200) {
			callbeck(JSON.parse(hr.responseText));
		}
	}
	hr.send();
}

var config;

getInit(function(data) {
	config = data;
});

var language = new language();