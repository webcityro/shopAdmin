$(function(e) {
	const docBody = $('body');

	const userGroups = {
		displayUserGroups: $('#displayUserGroups'),
		showAddGroupBtn: $('#showAddGroupBtn'),
		addGroupForm: $('#addGroupForm'),
		addGroupFormTitle: $('#addGroupFormTitle'),
		addUserGroupErrors: $('#addUserGroupErrors'),
		groupName: $('#groupName'),
		groupLevel: $('#groupLevel'),
		sectionPermitions: $('#sectionPermitions'),
		permissions: $('.permissions'),
		groupPermission: $('.groupPermission'),
		addGroupBtn: $('#saveBtn'),
		cancelAddGroup: $('#cancelBtn'),
		editGroup: $('.editGroup'),
		deleteGroup: $('.deleteGroup'),
		noGroupsClass: 'noGroups',
		permissionClass: 'permissions',

		defaultFormTitleText: '',
		defaultAddGroupBtnText: '',

		sections: sections,
		errors:  	 new errorHandler(),
		editID: 0,

		init: function() {
			this.defaultFormTitleText = this.addGroupFormTitle.text();
			this.defaultAddGroupBtnText = this.addGroupBtn.text();
			this.addGroupForm.addClass('hide');
		},

		addOrUpdate: function(e) {
			if ((!auth.hasPermission('users/groups', 'add') && this.editID == 0) ||
				(!auth.hasPermission('users/groups', 'edit') && this.editID != 0)) {
				flashMessage('error', language.translate('dontHavePermissionForAction'));
				return false;
			}

			this.validateGroupName();
			this.validateGroupLevel();
			this.validatePermitions();
			errorsHandler.setContainer(this.addUserGroupErrors);

			if (!errorsHandler.hasErrors()) {
				let permissions = {};

				for (let i in this.sections) {
					permissions[i] = (typeof permissions[i] === 'object') ? permissions[i] : {};

					for (let x in this.sections[i].actions) {
						permissions[i][x] = this.sections[i].actions[x].value;
					}
				}

				ajax({
					method: 'POST',
					route: (this.editID == 0) ? 'users.groups.add' : ['users.groups.update', {id: this.editID}],
					fields: {
						groupName: this.groupName.val(),
						groupLevel: this.groupLevel.val(),
						permissions: JSON.stringify(permissions)
					},
					success: function(data) {
						if (userGroups.editID == 0) {
							userGroups.addNewGroupRow(data.newGroup.id);
						} else {
							let tr = userGroups.displayUserGroups.children('tbody').find('[data-id="'+userGroups.editID+'"]');
							tr.children().eq(1).text(userGroups.groupName.val());

							if (tr.children().eq(2).text() != userGroups.groupLevel.val()) {
								tr.children().eq(2).text(userGroups.groupLevel.val());
								userGroups.findTR(tr);
							}
							flashMessage('success', language.translate('updatedGroup', [userGroups.groupName.val()]));
						}
						userGroups.cancelAddGroup.click();
					}
				});
			} else {
				errorsHandler.getErrors();
			}
		},

		addNewGroupRow: function(id) {
			let tr = $('<tr />');
			let idTD = $('<th />');
			let nameTD = $('<td />');
			let levelTD = $('<td />');
			let usersCountTD = $('<td />');
			let actionsTD = $('<td />');
			let editLink = $('<a />');
			let deleteLink = $('<a />');

			idTD.text(id);
			nameTD.text(this.groupName.val());
			levelTD.text(this.groupLevel.val());
			usersCountTD.text('0');
			editLink.attr('href', '#').addClass('editGroup').text(language.translate('edit'));
			deleteLink.attr('href', '#').addClass('deleteGroup').text(language.translate('delete'));

			if (auth.hasPermission('users/groups', 'edit')) {
				actionsTD.append(editLink);
			}

			if (auth.hasPermission('users/groups', 'delete')) {
				actionsTD.append(' / ').append(deleteLink);
			}

			tr.attr({'data-id': id, 'data-users-count': '0'}).append(idTD).append(nameTD).append(levelTD).append(usersCountTD).append(actionsTD);
			this.findTR(tr);

			flashMessage('success', language.translate('addedGroup', [this.groupName.val()]));
		},

		findTR: function (tr) {
			let tbody = this.displayUserGroups.children('tbody');
			let noGroupsTR = $('.'+this.noGroupsClass);

			if (noGroupsTR.length == 1) {
				noGroupsTR.remove();
				tbody.append(tr);
			} else {
				let findTR = false;

				tbody.children().each(function(index, el) {
					let thisTR = $(el);

					if (findTR) { return false }

					if (thisTR.data('level') == userGroups.groupLevel.val()) {
						thisTR.after(tr);
						findTR = true;
					} else if (thisTR.data('level') > userGroups.groupLevel.val()) {
						thisTR.before(tr);
						findTR = true;
					}
				});

				if (!findTR) {
					tbody.append(tr);
				}
			}
		},

		edit: function(e) {
			if (!auth.hasPermission('users/groups', 'edit')) {
				flashMessage('error', language.translate('dontHavePermissionForAction'));
				return false;
			}

			let self = $(e.target);
			let id = self.parent().parent().data('id');
			let level = self.parent().parent().data('level');

			$.get(Slim.Router.pathFor('users.groups.get', {id: id}), function(data) {
				if (data.status) {
					let row = data.data.group;
					let permissions = JSON.parse(row.permissions);

					userGroups.editID = row.id;
					userGroups.groupName.val(row.name);
					userGroups.groupLevel.val(row.level);
					userGroups.addGroupFormTitle.text(language.translate('yourEditingTheGroup', [row.name]));

					for (let i in permissions) {
						let checkAll = true;
						let allCheckbox;
						let thisSection = $('.'+userGroups.permissionClass+'[data-section="'+i+'"]');
						let thisCheckboxes = thisSection.find('input.'+userGroups.groupPermission.attr('class'));

						thisCheckboxes.each(function(index, el) {
							let thisCheckbox = $(el);

							if (thisCheckbox.val() != 'all') {
								let value = permissions[i][thisCheckbox.val()];
								thisCheckbox.prop('checked', value).change();

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
			if (!auth.hasPermission('users/groups', 'delete')) {
				flashMessage('error', language.translate('dontHavePermissionForAction'));
				return false;
			}

			let self = $(e.target);
			let thisTR = self.parent().parent();
			let id = thisTR.data('id');
			let usersCount = thisTR.data('users-count');
			let name = thisTR.children().eq(1).text();

			let body = $('<div />');
			let p = $('<p />');
			let row = $('<div />');
			let colLeft = $('<div />');
			let colRight = $('<div />');
			let labelLeft = $('<label />');
			let labelRight = $('<label />');
			let groupsSelect = $('<select />');
			let defaultOption = $('<option />');
			let deleteAllUsersCheckbox = $('<input />');

			if (usersCount > 0) {
				body.append(p).append(row);
				p.addClass('lead text-center').text(language.translate('deleteGroupPharagraf', [name, usersCount]));
				row.addClass('row').append(colLeft).append(colRight);
				colLeft.addClass('col-sd-6 col-md-6 text-center form-group').append(labelLeft).append(groupsSelect);
				labelLeft.attr('for', 'moveUsersToGroupID').text(language.translate('choseGroupToMoveUsers'));
				groupsSelect.addClass('form-control').attr('id', 'moveUsersToGroupID').append(defaultOption);
				defaultOption.val('0').text(language.translate('choseGroup'));

				this.displayUserGroups.children('tbody').children('tr').each(function(index, el) {
					let tr = $(el);

					if (tr.data('id') != id) {
						let option = $('<option />');
						option.val(tr.data('id')).text(tr.children().eq(1).text());
						groupsSelect.append(option);
					}
				});

				colRight.addClass('col-sd-6 col-md-6 text-center form-group').append(labelRight);
				labelRight.addClass('form-ckeck-label').attr('for', 'deleteUsersFronThisGroup').append(deleteAllUsersCheckbox).append(language.translate('deleteAllUsersFromThisGroup'));
				deleteAllUsersCheckbox.addClass('form-check-input').attr({type: 'checkbox', id: 'deleteUsersFronThisGroup'});
			} else {
				body.append(p);
				p.addClass('lead text-center').text(language.translate('deleteGroup', [name]));
			}

			modal(language.translate('warning'), body, function(modalBody) {
				if (usersCount > 0 && groupsSelect.val() == '0' && !deleteAllUsersCheckbox.is(':checked')) {
					let errorP = $('<p />');

					errorP.addClass('help-text').text(language.translate('noOptionChosen'));
					modalBody.prepend(errorP);
					return false;
				}

				ajax({
					method: 'POST',
					route: ['users.groups.delete', {id: id}],
					fields: {
						action: (usersCount == 0) ? 'none' : ((groupsSelect.val() == '0') ? 'delete' : 'move'),
						moveToID: (usersCount == 0) ? 'none' :  groupsSelect.val()
					},
					success: function(data) {
						if (thisTR.siblings().length == 0) {
							let newTR = $('<tr />');
							let newTD = $('<td />');

							newTD.attr('colspan', 3).text(language.translate('noGroupsAdded'));
							newTR.addClass(userGroups.noGroupsClass).append(newTD);
							thisTR.after(newTR);
						} else if (usersCount > 0 && groupsSelect.val() != '0') {
							let moveToTR = thisTR.siblings('[data-id="'+groupsSelect.val()+'"]');
							let newUsersCount = Number(moveToTR.data('users-count')) + Number(usersCount);

							moveToTR.data('users-count', newUsersCount).children().eq(2).text(newUsersCount);
						}

						if (id == userGroups.editID) {
							userGroups.cancelAddGroup.click();
						}

						thisTR.remove();
						flashMessage('success', language.translate('deletedGroup', [name]));
					}
				});
				return true;
			}, language.translate('delete'));
		},

		cancelEdit: function(e) {
			this.groupName.val('');
			this.groupLevel.val(auth.group.level == 0 ? 1 : auth.group.level);
			this.groupPermission.prop('checked', false).change();
			this.addUserGroupErrors.addClass('hide').html('');
			$('.'+this.errors.getFieldErrorClass()).remove();
			this.addGroupFormTitle.text(this.defaultFormTitleText);
			this.editID = 0;
			this.addGroupForm.addClass('hide');
			this.showAddGroupBtn.removeClass('hide');
		},

		changePermission: function(e) {
			let self = $(e.target);
			let action = self.val();
			let value = self.is(':checked');
			let section = self.parent().parent().parent().data('section');
			let checkAll = false;

			if (action == 'all') {
				self.parent().parent().siblings().children().children().each(function(index, el) {
					let checkbox = $(el);

					if (!checkbox.prop('disabled')) {
						checkbox.prop('checked', value).change();
					}
				});
				return;
			}

			this.sections[section].actions[action].value = value;

			for (let i in this.sections[section].actions) {
				if (this.sections[section].actions[i].value) {
					checkAll = true;
				} else {
					checkAll = false;
					break;
				}
			}

			self.parent().parent().parent().children().first().children().first().children().first().prop('checked', checkAll);
		},

		validateGroupName: function() {
			let value = this.groupName.val();
			let fieldID = this.groupName.attr('id');

			if (value.length == 0) {
				errorsHandler.setError(language.translate('validateRequired', [language.translate('formLabelFName')]), fieldID);
			} else if (value.length > 25) {
				errorsHandler.setError(language.translate('validateMaxLength', [language.translate('formLabelFName'), '25']), fieldID);
			}
		},

		validateGroupLevel: function() {
			let value = this.groupLevel.val();
			let fieldID = this.groupLevel.attr('id');

			if (value.length == 0) {
				errorsHandler.setError(language.translate('validateRequired', [language.translate('level')]), fieldID);
			} else if (!auth.isOwner && value < auth.group.level) {
				errorsHandler.setError(language.translate('lowGroupLevel'), fieldID);
			}
		},

		validatePermitions: function() {
			for (let i in this.sections) {
				for (let x in this.sections[i].actions) {
					if (this.sections[i].actions[x].value && !this.sections[i].actions.view.value) {
						errorsHandler.setError(language.translate('errorPermissionWithoutViewingRight', [this.sections[i].label]));
						break;
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
		e.preventDefault();
		userGroups.addOrUpdate(e);
	});

	docBody.on('click', '.'+userGroups.editGroup.attr('class'), function(e) {
		e.preventDefault();

		if ($.trim(userGroups.groupName.val())) {
				modal(language.translate('warning'), '<p class="lead text-center">'+language.translate('askCancelCurrenGroupToEditAnotherOne')+'</p>',
				function(modalBody) {
					userGroups.cancelAddGroup.click();
					userGroups.edit(e);
					return true;
				}, language.translate('yes'), language.translate('no'));
				return false;
		}
		userGroups.edit(e);
	});

	docBody.on('click', '.'+userGroups.deleteGroup.attr('class'), function(e) {
		e.preventDefault();
		userGroups.delete(e);
	});

	userGroups.cancelAddGroup.on('click', function(e) {
		e.preventDefault();
		userGroups.cancelEdit(e);
	});

	userGroups.groupPermission.on('change', function(e) {
		userGroups.changePermission(e);
	});
});