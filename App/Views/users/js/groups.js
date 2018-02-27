$(function(e) {
	const docBody = $('body');

	const userGroups = {
		displayUserGroups: $('#displayUserGroups'),
		showAddGroupBtn: $('#showAddGroupBtn'),
		addGroupForm: $('#addGroupForm'),
		addGroupFormTitle: $('#addGroupFormTitle'),
		addUserGroupErrors: $('#addUserGroupErrors'),
		groupName: $('#groupName'),
		sectionPermitions: $('#sectionPermitions'),
		permissions: $('.permissions'),
		groupPermission: $('.groupPermission'),
		addGroupBtn: $('#addGroupBtn'),
		cancelAddGroup: $('#cancelAddGroup'),
		addGroupToken: $('#addUserGroupToken'),
		deleteToken: $('#deleteToken'),
		editGroup: $('.editGroup'),
		deleteGroup: $('.deleteGroup'),
		noGroupsClass: 'noGroups',
		permissionClass: 'permissions',

		defaultFormTitleText: '',
		defaultAddGroupBtnText: '',

		permissions: permissions,
		sectionsLabels: sections,
		errors:  	 new errorHandler(),
		editID: 0,

		init: function() {
			this.defaultFormTitleText = this.addGroupFormTitle.text();
			this.defaultAddGroupBtnText = this.addGroupBtn.text();
		},

		addOrUpdate: function(e) {
			this.validateGroupName();
			this.validatePermitions();
			this.errors.setContainer(this.addUserGroupErrors);

			if (!this.errors.hasErrors()) {
				$.post(config.domain+'/users/groups/addOrUpdate/'+this.editID, {
					name: this.groupName.val(),
					permissions: JSON.stringify(this.permissions),
					addOrUpdateToken: this.addGroupToken.val()
				},
				function(data, textStatus, xhr) {
					userGroups.addGroupToken.val(data.data.newToken);

					if (data.status) {
						if (userGroups.editID == 0) {
							let tr = $('<tr />');
							let idTD = $('<td />');
							let nameTD = $('<td />');
							let actionsTD = $('<td />');
							let editLink = $('<a />');
							let deleteLink = $('<a />');

							idTD.text(data.data.id);
							nameTD.text(userGroups.groupName.val());
							editLink.attr('href', '#').addClass('editGroup').text(language.translate('edit'));
							deleteLink.attr('href', '#').addClass('deleteGroup').text(language.translate('delete'));
							actionsTD.append(editLink).append(' / ').append(deleteLink);
							tr.data('id', data.data.id).append(idTD).append(nameTD).append(actionsTD);
							userGroups.displayUserGroups.children('tbody').append(tr);
							$('.'+userGroups.noGroupsClass).remove();
							flashMessage('success', language.translate('addedGroup', [userGroups.groupName.val()]));
						} else {
							userGroups.displayUserGroups.children('tbody').find('[data-id="'+userGroups.editID+'"]').children().eq(1).text(userGroups.groupName.val());
							flashMessage('success', language.translate('updatedGroup', [userGroups.groupName.val()]));
						}
						userGroups.cancelAddGroup.click();
					} else {
						for (let i in data.errors) {
							userGroups.errors.setError(data.errors[i], ((isNaN(i)) ? i : undefined));
						}
						userGroups.errors.getErrors();
					}
				}, 'json');
			} else {
				this.errors.getErrors();
			}
		},

		edit: function(e) {
			let self = $(e.target);
			let id = self.parent().parent().data('id');

			if ($.trim(this.groupName.val()) !== '' && !confirm(language.translate('askCancelCurrenGroupToEditAnotherOne'))) {
				return false;
			}

			this.cancelAddGroup.click();

			$.get(config.domain+'/users/groups/get/'+id, function(data) {
				if (data.status) {
					let row = data.data.row;
					userGroups.editID = row.id;
					userGroups.permissions = JSON.parse(row.permissions);
					userGroups.groupName.val(row.name);
					userGroups.addGroupFormTitle.text(language.translate('yourEditingTheGroup', [row.name]));
					userGroups.addGroupBtn.text(language.translate('save'));

					for (let i in userGroups.permissions) {
						let checkAll = true;
						let allCheckbox;
						let thisSection = $('.'+userGroups.permissionClass+'[data-section="'+i+'"]');
						let thisCheckboxes = thisSection.find('input.'+userGroups.groupPermission.attr('class'));

						thisCheckboxes.each(function(index, el) {
							let thisCheckbox = $(el);

							if (thisCheckbox.val() !== 'all') {
								let value = userGroups.permissions[i][thisCheckbox.val()];
								thisCheckbox.prop('checked', value);

								if (!value) {
									checkAll = false;
								}
							} else {
								allCheckbox = thisCheckboxes;
							}
						});

						if (checkAll) {
							allCheckbox.prop('checked', true);
						}
						userGroups.showAddGroupBtn.addClass('hide');
					}

					userGroups.addGroupForm.removeClass('hide');
				} else {
					flashMessage('error', data.errors[0]);
				}
			}, 'json');
		},

		delete: function(e) {
			let self = $(e.target);
			let thisTR = self.parent().parent();
			let id = thisTR.data('id');
			let name = thisTR.children().eq(1).text();

			if (id == this.editID) {
				if (confirm(language.translate('deleteTheGroupYourEditing'))) {
					this.cancelAddGroup.click();
				} else return false;
			} else if (!confirm(language.translate('deleteGroup', [name]))) {
				return false;
			}

			$.post(config.domain+'/users/groups/delete/'+id, {
				deleteToken: this.deleteToken.val()
			}, function(data, textStatus, xhr) {
				userGroups.deleteToken.val(data.data.newToken);

				if (data.status) {
					if (thisTR.siblings().length == 0) {
						let newTR = $('<tr />');
						let newTD = $('<td />');

						newTD.attr('colspan', 3).text(language.translate('noGroupsAdded'));
						newTR.addClass(userGroups.noGroupsClass).append(newTD);
						thisTR.after(newTR);
					}

					thisTR.remove();
					flashMessage('success', language.translate('deletedGroup', [name]));
				} else {
					flashMessage('error', data.errors[0]);
				}
			}, 'json');
		},

		cancelEdit: function(e) {
			this.groupName.val('');
			this.groupPermission.prop('checked', false).change();
			this.addUserGroupErrors.addClass('hide').html('');
			$('.'+this.errors.getFieldErrorClass()).remove();
			this.addGroupFormTitle.text(this.defaultFormTitleText);
			this.addGroupBtn.text(this.defaultAddGroupBtnText);
			this.editID = 0;
			this.addGroupForm.addClass('hide');
			this.showAddGroupBtn.removeClass('hide');
		},

		changePermission: function(e) {
			let self = $(e.target);
			let action = self.val();
			let value = self.is(':checked');
			let section = self.parent().parent().data('section');
			let checkAll = false;

			if (action == 'all') {
				self.parent().siblings().children().each(function(index, el) {
					let checkbox = $(el);
					checkbox.prop('checked', value).change();
				});
				return;
			}

			this.permissions[section][action] = value;

			for (let i in this.permissions[section]) {
				if (this.permissions[section][i]) {
					checkAll = true;
				} else {
					checkAll = false;
					break;
				}
			}

			self.parent().parent().children().first().children().first().prop('checked', checkAll);
		},

		validateGroupName: function() {
			let value = this.groupName.val();
			let fieldID = this.groupName.attr('id');

			if (value.length == 0) {
				this.errors.setError(language.translate('validateRequired', [language.translate('formLabelFName')]), fieldID);
			} else if (value.length > 25) {
				this.errors.setError(language.translate('validateMaxLength', [language.translate('formLabelFName'), '25']), fieldID);
			}
		},

		validatePermitions: function() {
			for (let i in this.permissions) {
				if (!this.permissions[i].view) {
					for (let x in this.permissions[i]) {
						if (this.permissions[i][x]) {
							this.errors.setError(language.translate('errorPermissionWithoutViewingRight', [this.sectionsLabels[i]]));
							break;
						}
					}
				}
			}
		}
	};

	userGroups.init();

	userGroups.showAddGroupBtn.on('click', function(e) {
		userGroups.addGroupForm.removeClass('hide');
		$(this).addClass('hide');
	});

	userGroups.addGroupBtn.on('click', function(e) {
		userGroups.addOrUpdate(e);
	});

	userGroups.editGroup.on('click', function(e) {
		userGroups.edit(e);
	});

	userGroups.deleteGroup.on('click', function(e) {
		userGroups.delete(e);
	});

	userGroups.cancelAddGroup.on('click', function(e) {
		userGroups.cancelEdit(e);
	});

	userGroups.groupPermission.on('change', function(e) {
		userGroups.changePermission(e);
	});
});