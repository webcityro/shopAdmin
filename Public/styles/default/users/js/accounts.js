$(function(e) {
	const docBody = $('body');

	var users = {
		displayUsers:   			$('#displayUsers'),
		profileTable:   			$('#profileTable'),
		showAddAdmin:   			$('#showAddAdmin'),
		addAdminForm:   			$('#addAdminForm'),
		formTitle:      			$('#usersFormTitle'),
		saveBtn: 		 			$('#saveBtn'),
		cancelBtn: 		 			$('#cancelBtn'),
		updateProfileBtn: 		$('#updateProfileBtn'),
		cancelUpdateProfileBtn: $('#cancelUpdateProfileBtn'),
		updatePasswordBtn:	   $('#updatePasswordBtn'),
		cancelUpdatePasswordBtn:$('#cancelUpdatePasswordBtn'),
		closeUpdateProfileBtn:  $('#closeUpdateProfileBtn'),
		closeUpdatePasswordBtn: $('#closeUpdatePasswordBtn'),
		fName:          			$('#fName'),
		lName:          			$('#lName'),
		userName:       			$('#userName'),
		oldPassword:    			$('#oldPassword'),
		password:       			$('#password'),
		email: 	       			$('#email'),
		sexM: 	       			$('#sexM'),
		sexF: 	       			$('#sexF'),
		sex:  	       			$('.sex'),
		status: 	       			$('#status'),
		groupID:	       			$('#groupID'),

		defaultFormTitle:			'',
		defaultStatus: 			'',
		defaultGroupID:   		'',

		editUser:  	 	 			$('.editUser'),
		deleteUser:  	 			$('.deleteUser'),
		docBody: 		 			$('body'),
		editID: 			 			0,

		init: function() {
			this.defaultFormTitle = this.formTitle.text();
			this.defaultStatus = this.status.val();
			this.defaultGroupID = this.groupID.val();
			this.addAdminForm.hide();
		},

		add: function(e) {
			if (!auth.hasPermission('users/accounts', 'add')) {
				flashMessage('error', language.translate('dontHavePermissionForAction'));
				return false;
			}

			var self = $(e.target);
			errorsHandler.clear();
			errorsHandler.setContainer('#addUserErrors');

			this.validate.userName();
			this.validate.email();
			this.validate.name(this.fName, 'F');
			this.validate.name(this.lName, 'L');
			this.validate.passwords(this.password);
			this.validate.sex();

			if (!errorsHandler.hasErrors()) {
				ajax({
					method: 'POST',
					route: 'users.accounts.add',
					fields: {
						fName:       this.fName.val(),
						lName:       this.lName.val(),
						userName:    this.userName.val(),
						password:    this.password.val(),
						email: 	    this.email.val(),
						sex: 		   (this.sexM.prop('checked')) ? 'm' : (this.sexF.prop('checked') ? 'f' : null),
						groupID:		 this.groupID.val(),
						status:		 this.status.val()
					},
					success: function(data) {
						if (users.displayUsers.children('tbody').children().length < 10) {
						console.log(data);
							let newTR = $('<tr />');
							let idTD = $('<th />');
							let userNameTD = $('<td />');
							let fNameTD = $('<td />');
							let lNameTD = $('<td />');
							let emailTD = $('<td />');
							let sexTD = $('<td />');
							let groupTD = $('<td />');
							let lastLoginDateTD = $('<td />');
							let lastLogoutDateTD = $('<td />');
							let actionTD = $('<td />');
							let editLink = $('<a />');
							let deleteLink = $('<a />');
							let profileLink = $('<a />');

							idTD.text(data.newUser.id);
							profileLink.attr('href', Slim.Router.pathFor('users.profile', {user: users.userName.val()})).text(users.userName.val());

							if (auth.hasPermission('users/profile', 'view')) {
								userNameTD.append(profileLink);
							} else {
								userNameTD.text(users.userName.val());
							}

							fNameTD.text(users.fName.val());
							lNameTD.text(users.lName.val());
							emailTD.text(users.email.val());
							sexTD.text(language.translate('formLabelSex'+(users.sexM.is(':checked') ? 'M' : 'F')));
							groupTD.text(users.groupID.find('option[value="'+users.groupID.val()+'"]').text());
							lastLoginDateTD.text(language.translate('never'));
							lastLogoutDateTD.text(language.translate('never'));
							editLink.attr('href', '#').addClass(users.editUser.attr('class')).text(language.translate('edit'));
							deleteLink.attr('href', '#').addClass(users.deleteUser.attr('class')).text(language.translate('delete'));

							if (auth.hasPermission('users/accounts', 'edit')) {
								actionTD.append(editLink);
							}

							if (auth.hasPermission('users/accounts', 'delete')) {
								actionTD.append(' / ').append(deleteLink);
							}

							newTR.attr('data-id', data.newUser.id).append(idTD).append(userNameTD).append(fNameTD).append(lNameTD).append(emailTD).append(sexTD).append(groupTD).append(lastLoginDateTD).append(lastLogoutDateTD).append(actionTD);
							users.displayUsers.children('tbody').append(newTR);
						}
						flashMessage('success', language.translate('addedUser', [users.userName.val()]));
						users.cancelForm();
					}
				});
			} else {
				errorsHandler.getErrors();
			}
		},

		update: function(e) {
			if (!auth.hasPermission('users/accounts', 'edit')) {
				flashMessage('error', language.translate('dontHavePermissionForAction'));
				return false;
			}

			var self = $(e.target);
			errorsHandler.clear();
			errorsHandler.setContainer('#addUserErrors');

			this.validate.email();
			this.validate.name(this.fName, 'F');
			this.validate.name(this.lName, 'L');
			this.validate.sex();

			if (!errorsHandler.hasErrors()) {
				ajax({
					method: 'POST',
					route: ['users.accounts.update', {id: this.editID}],
					fields: {
						fName:       this.fName.val(),
						lName:       this.lName.val(),
						email: 	    this.email.val(),
						sex: 		   (this.sexM.prop('checked')) ? 'm' : (this.sexF.prop('checked') ? 'f' : null),
						groupID:		 this.groupID.val(),
						status:		 this.status.val()
					},
					success: function(data) {
						let userTR = users.displayUsers.find('tr[data-id="'+users.editID+'"]');

						userTR.children().eq(2).text(users.fName.val());
						userTR.children().eq(3).text(users.lName.val());
						userTR.children().eq(4).text(users.email.val());
						userTR.children().eq(5).text(language.translate('formLabelSex'+(users.sexM.is(':checked') ? 'M' : 'F')));

						if (!auth.isOwner) {
							userTR.children().eq(6).text(users.groupID.find('option[value="'+users.groupID.val()+'"]').text());
						}
						flashMessage('success', language.translate('updatedUser', [userTR.children().eq(1).text()]));
						users.cancelForm();
					}
				});
			} else {
				errorsHandler.getErrors();
			}
		},

		edit: function (e) {
			if (!auth.hasPermission('users/accounts', 'edit')) {
				flashMessage('error', language.translate('dontHavePermissionForAction'));
				return false;
			}

			let self = $(e.target);
			let thisTR = self.parent().parent();
			let id = thisTR.data('id');
			let userName = thisTR.children().eq(1).text();

			ajax({
				method: 'GET',
				route:  ['users.accounts.get', {id: id}],
				success: function(data) {
					users.editID = data.user.id;
					users.addAdminForm.show();
					users.formTitle.text(language.translate('editingTheUser', [data.user.userName]));
					users.fName.val(data.user.fName);
					users.lName.val(data.user.lName);
					users.userName.val(data.user.userName).parent().addClass('hide');
					users.password.parent().addClass('hide');
					users['sex'+data.user.sex.toUpperCase()].prop('checked', true);
					users.email.val(data.user.email);

					if (data.user.id == auth.user.id) {
						users.groupID.parent().addClass('hide');
						users.status.parent().addClass('hide');
					} else {
						users.groupID.val(data.user.groupID);
						users.status.val(data.user.active);
					}
				}
			});
		},

		updateProfile: function(e) {
			let self = $(e.target);

			errorsHandler.setContainer('#updateProfileErrors');

			this.validate.email();
			this.validate.name(this.fName, 'F');
			this.validate.name(this.lName, 'L');
			this.validate.sex();

			if (!errorsHandler.hasErrors()) {
				ajax({
					method: 'POST',
					route: 'users.profile.update',
					fields: {
						fName:       this.fName.val(),
						lName:       this.lName.val(),
						email: 	    this.email.val(),
						sex: 		   (this.sexM.prop('checked')) ? 'm' : (this.sexF.prop('checked') ? 'f' : null)
					},
					success: function(data) {
						let profileTRs = users.profileTable.children('tbody').children('tr');
						console.log('profileTRs', profileTRs);

						profileTRs.eq(0).find('td').text(users.fName.val());
						profileTRs.eq(1).find('td').text(users.lName.val());
						profileTRs.eq(2).find('td').text(users.email.val());
						profileTRs.eq(3).find('td').text(language.translate('formLabelSex'+(users.sexM.prop('checked') ? 'M' : 'F')));

						auth.user.fName = users.fName.val();
						auth.user.lName = users.lName.val();
						auth.user.email = users.email.val();
						auth.user.sex = users.sexM.prop('checked') ? 'm' : 'f';

						flashMessage('success', language.translate('updatedProfile'));
						users.closeUpdateProfileBtn.click();
					}
				});
			} else {
				errorsHandler.getErrors();
			}
		},

		updatePassword: function(e) {
			let self = $(e.target);

			errorsHandler.setContainer('#updatePasswordErrors');
			this.validate.passwords(this.oldPassword, 'formLabelCurrentPassword');
			this.validate.passwords(this.password, 'formLabelNewPassword');

			if (!errorsHandler.hasErrors()) {
				ajax({
					method: 'POST',
					route: 'users.profile.changePassword',
					fields: {
						password:    this.password.val(),
						oldPassword: this.oldPassword.val()
					},
					success: function(data) {
						flashMessage('success', language.translate('updatedPassword'));
						users.cancelUpdatePasswordBtn.click();
					}
				})
			} else {
				errorsHandler.getErrors();
			}
		},

		cancelUpdateProfile: function(e) {
			let self = $(e.target);

			users.fName.val(auth.user.fName);
			users.lName.val(auth.user.lName);
			users.email.val(auth.user.email);
			users.sexM.prop('checked', auth.user.sex == 'm');
			users.sexF.prop('checked', auth.user.sex == 'f');
			errorsHandler.clear();
		},

		cancelUpdatePassword: function(e) {
			let self = $(e.target);
			this.password.val('');
			this.oldPassword.val('');
			errorsHandler.clear();
		},


		cancelForm: function(e) {
			this.addAdminForm.hide();
			this.formTitle.text(this.defaultFormTitle);
			this.fName.val('');
			this.lName.val('');
			this.userName.val('').parent().removeClass('hide');
			this.password.val('').parent().removeClass('hide');
			this.email.val('');
			this.sexM.prop('checked', false);
			this.sexF.prop('checked', false);
			this.status.val(this.defaultStatus).parent().removeClass('hide');
			this.groupID.val(this.defaultGroupID).parent().removeClass('hide');
			this.editID = 0;
			errorsHandler.clear();
		},

		delete: function(e) {
			if (!auth.hasPermission('users/accounts', 'delete')) {
				flashMessage('error', language.translate('dontHavePermissionForAction'));
				return false;
			}

			var self = $(e.target);
			var userTR = self.parent().parent();
			var userID = userTR.data('id');
			var thisUserName = userTR.children().eq(1).text();

			modal(language.translate('warning'), '<p class="lead text-center">'+language.translate('confirmUserDelete', [thisUserName])+'</p>',
			function(modalBody) {
				ajax({
					method: 'POST',
					route: ['users.accounts.delete', {id: userID}],
					success: function(data) {
						userTR.remove();

						if (userID == users.editID) {
							users.cancelBtn.click();
						}

						flashMessage('success', language.translate('deletedUser', [thisUserName]));
					}
				});
				return true;
			}, language.translate('yes'), language.translate('no'));
		},

		validate: {
			name: function(id, what) {
				var value = $.trim(id.val());
				var regexp = /^[a-zA-Z]+$/;
				var fieldID = id.attr('id');

				if (what == 'F') {
					if (value == '') {
						errorsHandler.setError(language.translate('validateRequired', [language.translate('formLabelFName')]), fieldID);
					} else if (value.length < 3 || value.length > 25) {// console.log('length');
						errorsHandler.setError(language.translate('validateLengthRange', [language.translate('formLabelFName'), '3', '25']), fieldID);
					} else if (!regexp.test(value)) {
						errorsHandler.setError(language.translate('validateAlpha', [language.translate('formLabelFName')]), fieldID);
					}
				} else if (what == 'L') {
					if (value.length > 25) {
						errorsHandler.setError(language.translate('validateMaxLength', [language.translate('formLabelLName'), '25']), fieldID);
					} else if (value.length > 0 &&!regexp.test(value)) {
						errorsHandler.setError(language.translate('validateAlpha', [language.translate('formLabelLName')]), fieldID);
					}
				}
			},

			userName: function() {
				var value = users.userName.val();
				var fieldID = users.userName.attr('id');
				var regexp = /^[a-zA-Z0-9-_.]+$/;

				if ($.trim(value) == '') {
					errorsHandler.setError(language.translate('validateRequired', [language.translate('formLabelUserName')]), fieldID);
				} else if (value.length < 4 || value.length > 32) {
					errorsHandler.setError(language.translate('validateLengthRange', [language.translate('formLabelUserName'), '4', '32']), fieldID);
				} else if (!regexp.test(value)) {
					errorsHandler.setError(language.translate('validateAlNumCustom', [language.translate('formLabelUserName'), '_-.']), fieldID);
				}
			},

			passwords: function(id, label) {
				var value = $.trim(id.val());
				var fieldID = id.attr('id');

				if (value.length == 0) {
					errorsHandler.setError(language.translate('validateRequired', [language.translate(label || 'formLabelPassword')]), fieldID);
				} else if (value.length < 8) {
					errorsHandler.setError(language.translate('validateMinLength', [language.translate(label || 'formLabelPassword'), '8']), fieldID);
				}
			},

			email: function() {
				var value = users.email.val();
				var fieldID = users.email.attr('id');
				var regexp = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

				if ($.trim(value) == '') {
					errorsHandler.setError(language.translate('validateRequired', [language.translate('formLabelEmail')]), fieldID);
				} else if (!regexp.test(value)) {
					errorsHandler.setError(language.translate('validateValidFormat', [language.translate('formLabelEmail')]), fieldID);
				}
			},

			sex: function() {
				var fieldID = 'sex';

				if (!users.sexM.is(':checked') && !users.sexF.is(':checked')) {
					errorsHandler.setError(language.translate('validateRequired', [language.translate('formLabelSexValidation')]), fieldID);
				}
			}
		}
	};

	users.init();

	users.showAddAdmin.on('click', function(e) {
		users.addAdminForm.show();
	});

	users.cancelBtn.on('click', function(e) {
		users.cancelForm(e);
	});

	users.saveBtn.on('click', function(e) {
		e.preventDefault();

		if (users.editID == 0) {
			users.add(e);
		} else {
			users.update(e);
		}
	});

	users.updateProfileBtn.on('click', function(e) {
		users.updateProfile(e);
	});

	users.cancelUpdateProfileBtn.on('click', function(e) {
		users.cancelUpdateProfile(e);
	});

	users.updatePasswordBtn.on('click', function(e) {
		users.updatePassword(e);
	});

	users.cancelUpdatePasswordBtn.on('click', function(e) {
		users.cancelUpdatePassword(e);
	});


	docBody.on('click', '.'+users.editUser.attr('class'), function(e) {
		e.preventDefault();

		if (users.userName.val() !== '') {
			modal(language.translate('warning'), '<p class="lead text-center">'+language.translate('askCancelOneUserEditToStartEditingAnotherUser', [users.userName.val()])+'</p>',
			function(modalBody) {
				users.edit(e);
				return true;
			}, language.translate('yes'), language.translate('no'));
			return false;
		}
		users.edit(e);
	});

	docBody.on('click', '.'+users.deleteUser.attr('class'), function(e) {
		e.preventDefault();
		users.delete(e);
	});
});