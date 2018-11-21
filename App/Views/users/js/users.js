$(function(e) {
	const docBody = $('body');

	var users = {
		displayUsers:   $('#displayUsers'),
		showAddAdmin:   $('#showAddAdmin'),
		addAdminForm:   $('#addAdminForm'),
		cancelAddAdmin: $('#cancelAddAdmin'),
		fName:          $('#fName'),
		lName:          $('#lName'),
		userName:       $('#userName'),
		password:       $('#password'),
		password2:      $('#password2'),
		email: 	       $('#email'),
		status: 	       $('#status'),
		groupID:	       $('#groupID'),
		singupToken:    $('#singupToken'),
		deleteToken:    $('#deleteToken'),

		editUser:  	 	 $('.editUser'),
		deleteUser:  	 $('.deleteUser'),
		docBody: 		 $('body'),
		errors:  		 new errorHandler(),
		editID: 			 0,

		add: function(e) {
			var self = $(e.target);
			this.errors.setContainer('#addUserErrors');

			this.validate.userName();
			this.validate.email();
			this.validate.name(this.fName, 'F');
			this.validate.name(this.lName, 'L');
			this.validate.passwords(this.password, 1);
			this.validate.passwords(this.password2, 2);
			this.validate.sex();

			if (!users.errors.hasErrors()) {
				// console.log('validation passed');
				// self.addClass('loading').attr({disabled: true});

				$.post(config.domain+'users/addOrUpdate/'+this.editID, {
					fName:       this.fName.val(),
					lName:       this.lName.val(),
					userName:    this.userName.val(),
					password:    this.password.val(),
					password2:   this.password2.val(),
					email: 	    this.email.val(),
					sex: 		   (this.sexM.is(':checked')) ? 'm' : 'f',
					groupID:		 this.groupID.val(),
					status: 		 this.status.val(),
					singupToken: this.singupToken
				}, function(data) {
					self.removeClass('loading').attr({disabled: false});
					console.log(data);
					if (output.status) {
						users.singupToken.val(data.data.newToken);

						if (users.editID == 0) {
							let newTR = $('<tr />');
							let idTD = $('<td />');
							let userNameTD = $('<td />');
							let fNameTD = $('<td />');
							let lNameTD = $('<td />');
							let emailTD = $('<td />');
							let sexTD = $('<td />');
							let singupDateTD = $('<td />');
							let lastLoginDateTD = $('<td />');
							let lastLogoutDateTD = $('<td />');
							let actionTD = $('<td />');
							let editLink = $('<a />');
							let deleteLink = $('<a />');

							idTD.text(data.data.id);
							userNameTD.text(users.userName.val());
							fNameTD.text(users.fName.val());
							lNameTD.text(users.lName.val());
							emailTD.text(users.email.val());
							sexTD.text(language.translate('formLabelSex'+(users.sexM.is(':checked') ? 'M' : 'F')));
							singupDateTD.text(language.translate('now'));
							lastLoginDateTD.text(language.translate('never'));
							lastLogoutDateTD.text(language.translate('never'));
							editLink.attr('href', '#').addClass(users.editUser.attr('class')).text(language.translate('edit'));
							deleteLink.attr('href', '#').addClass(users.deleteUser.attr('class')).text(language.translate('delete'));
							actionTD.append(editLink).append(' / ').append(deleteLink);
							newTR.append(idTD).append(userNameTD).append(fNameTD).append(lNameTD).append(emailTD).append(sexTD).append(singupDateTD).append(lastLoginDateTD).append(lastLoginDateTD).append(actionTD);
							usets.displayUsers.children('tbody').append(newTR);

							flashMessage('success', language.translate('addedUser', [users.userName.val()]));
						} else {
							let userTR = users.displayUsers.children('tbody tr[data-id="'+users.editID+'"]');

							userTR.children().eq(2).text(users.fName.val());
							userTR.children().eq(3).text(users.lName.val());
							userTR.children().eq(4).text(users.email.val());
							userTR.children().eq(5).text(language.translate('formLabelSex'+(users.sexM.is(':checked') ? 'M' : 'F')));
							flashMessage('success', language.translate('updatedUser', [users.userName.val()]));
						}
					} else {
						if (typeof data.errors[0] !== 'undefined') {
							flashMessage('error', data.errors[0]);
							return false;
						}

						for (var e in data.errors) {
							users.errors.setError(data.errors[e], e);
						}
						users.errors.getErrors();
					}
				}, 'json');
			} else {
				users.errors.getErrors();
			}
		},

		edit: function (e) {
			let self = $(e.target);
			let thisTR = self.parent().parent();
			let id = thisTR.data('id');
			let userName = thisTR.children().eq().text();

			if (this.userName.val() !== '' && !confirm(language.translate('askCancelOneUserEditToStartEditingAnotherUser', [userName]))) {
				return false;
			}

			$.get(config.domain+'/users/groups/get/'+id, function(data) {
				if (data.status) {

				} else {
					flashMessage('error', data.error[0]);
				}
			}, 'json');
		},

		cancelAdd: function(e) {
			this.addAdminForm.hide();
			this.fName.val('');
			this.lName.val('');
			this.userName.val('');
			this.password.val('');
			this.password2.val('');
			this.email.val('');
			this.status.val('m');
		},

		delete: function(e) {
			$('.deleteUserError').remove();

			var self = $(e.target);
			var userID = self.data('id');
			var userTR = self.parent().parent();
			var thisUserName = userTR.children().eq(1).text();

			if (confirm(language.translate('confirmUserDelete', [thisUserName]))) {
				$.post(config.domain+'users/delete/'+userID, {deleteToken: this.deleteToken.val()},
				function(data) {
					if (data.status == 'ok') {
						userTR.next().remove();
						userTR.remove();
						errorTR.remove();
						users.deleteToken.val(data.newToken);
					} else if (data.status == 'error') {
						users.addErrorRowToUserDisplayTable(data.msg, userTR);
					}
				}, 'json');
			}
		},

		addErrorRowToUserDisplayTable: function(msg, e) {
			var errorTR = $('<tr />');
			var errorTD = $('<td />');

			errorTD.attr('colspan', '3');
			errorTR.append(errorTD);

			if (e.prop('tagName').toLowerCase() == 'tr') {
				e.before(errorTR);
			} else {
				e.prepend(errorTR);
			}

			users.errors.setContainer(errorTD);
			users.errors.setError(msg);
			users.errors.getErrors();

			setTimeout(function() {
				errorTD.remove();
			}, 10000);
		},

		validate: {
			name: function(id, what) {
				var value = $.trim(id.val());
				var regexp = /^[a-zA-Z]+$/;
				var fieldID = id.attr('id');

				if (what == 'F') {
					if (value == '') {
						users.errors.setError(language.translate('validateRequired', [language.translate('formLabelFName')]), fieldID);
					} else if (value.length < 3 || value.length > 25) {// console.log('length');
						users.errors.setError(language.translate('validateLengthRange', [language.translate('formLabelFName'), '3', '25']), fieldID);
					} else if (!regexp.test(value)) {
						users.errors.setError(language.translate('validateAlpha', [language.translate('formLabelFName')]), fieldID);
					}
				} else if (what == 'L') {
					if (value.length > 25) {
						users.errors.setError(language.translate('validateMaxLength', [language.translate('formLabelLName'), '25']), fieldID);
					} else if (value.length > 0 &&!regexp.test(value)) {
						users.errors.setError(language.translate('validateAlpha', [language.translate('formLabelLName')]), fieldID);
					}
				}
			},

			userName: function() {
				var value = users.userName.val();
				var fieldID = users.userName.attr('id');
				var regexp = /^[a-zA-Z0-9-_.]+$/;

				if ($.trim(value) == '') {
					users.errors.setError(language.translate('validateRequired', [language.translate('formLabelUserName')]), fieldID);
				} else if (value.length < 4 || value.length > 32) {
					users.errors.setError(language.translate('validateLengthRange', [language.translate('formLabelUserName'), '4', '32']), fieldID);
				} else if (!regexp.test(value)) {
					users.errors.setError(language.translate('validateAlNumCustom', [language.translate('formLabelUserName'), '_-.']), fieldID);
				}
			},

			passwords: function(id, p) {
				var value = $.trim(id.val());
				var fieldID = id.attr('id');

				if (p==1) {
					value = value.length;
					if (value == 0) {
						users.errors.setError(language.translate('validateRequired', [language.translate('formLabelPassword')]), fieldID);
					} else if (value < 6) {
						users.errors.setError(language.translate('validateMinLength', [language.translate('formLabelPassword'), '6']), fieldID);
					}
				} else if (p==2) {
					if ($.trim(value) == '') {
						users.errors.setError(language.translate('validateRequired', [language.translate('formLabelPassword2Validation')]), fieldID);
					} else if (users.password.val() != value) {
						users.errors.setError(language.translate('validateMaches', [language.translate('formLabelPasswords')]), fieldID);
					}
				}
			},

			email: function() {
				var value = users.email.val();
				var fieldID = users.email.attr('id');
				var regexp = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

				if ($.trim(value) == '') {
					users.errors.setError(language.translate('validateRequired', [language.translate('formLabelEmail')]), fieldID);
				} else if (!regexp.test(value)) {
					users.errors.setError(language.translate('validateValidFormat', [language.translate('formLabelEmail')]), fieldID);
				}
			},

			sex: function() {
				var fieldID = 'formSexGroup';

				if (!users.sexM.is(':checked') && !users.sexF.is(':checked')) {
					users.errors.setError(language.translate('validateRequired', [language.translate('formLabelSexValidation')]), fieldID);
				}
			}
		}
	};

	users.addAdminForm.hide();

	users.showAddAdmin.on('click', function(e) {
		users.addAdminForm.show();
	});

	users.cancelAddAdmin.on('click', function(e) {
		users.cancelAdd(e);
	});

	$('#singupBtn').on('click', function(e) {
		e.preventDefault();
		users.add(e);
	});

	users.editUser.on('click', function(e) {
		e.preventDefault();
		users.edit(e);
	});

	users.deleteUser.on('click', function(e) {
		e.preventDefault();
		users.delete(e);
	});

	function loaderHandler (id) {
		id.removeClass('error ok').addClass('loading');
		id.next('.feedBeck').remove();
	}
});