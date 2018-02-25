$(document).ready(function(e) {
    var okImg = '<img src="' + config.okIcon + '" width="20" height="20" />';
	var errorImg = '<img src="' + config.errorIcon + '" width="20" height="20" />';
	
	$('#userName').keyup(function(e) {
		if ($(this).val().length >= 3) {
			$.post('login/ajax', {
				jQueryLogin: 'userName',
				userName: $(this).val(),
				token: $('#token').val()
			}, function(output){
				if (output == 'ok' ) {
					$('#errors').html('');
					$('#userNameFeedBeck').html(okImg);
				} else {
					$('#userNameFeedBeck').html(errorImg+'<div class="error">'+output+'</div>');
				}
			});
		}
    });
	
	$('#password').keyup(function(e) {
		if ($(this).val().length >= 6) {
			$.post('login/ajax', {
				jQueryLogin: 'password',
				userName: $('#userName').val(),
				password: $(this).val(),
				token: $('#token').val()
			}, function(output){
				if (output.status == 'ok') {
					$('#errors').html('');
					$('#passwordFeedBeck').html(okImg);
				} else if (output.status == 'error' ) {
					if (output.userName != '') {
						$('#userNameFeedBeck').html(errorImg+'<div class="error">'+output.userName+'</div>');
					} else {
						$('#passwordFeedBeck').html(errorImg+'<div class="error">'+output.password+'</div>');
					}
				}
			}, 'json');
		}
    });

    $(document.body).on('click', '#logInBtn', function(e) {
    	e.preventDefault();

		if ($(this).val().length >= 6) {
			$.post('login/ajax', {
				jQueryLogin: 'login',
				userName: $('#userName').val(),
				password: $('#password').val(),
				token: $('#token').val(),
				rememberMe: ($('#rememberMe').is(':checked')) ? 'on' : ''
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
					for (var e in output.errors) {
						if (e == 'top') {
							$('#top').html(errorImg+'<td colspan="2" class="feedBeck" style="text-align: center; color: #f00;"><div class="error">'+output.errors[e]+'</div></td>');
						} else {
							$('#'+e+'FeedBeck').html(errorImg+'<div class="error">'+output.errors[e]+'</div>');
						}
					};
					
				}
			}, 'json');
		}
    });
});