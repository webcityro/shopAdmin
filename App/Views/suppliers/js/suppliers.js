$(function() {
	var docBody = $('body');

	var suppliers = {
		displaySuppliers: $('#displaySuppliers'),
		splRowClass: 'splRow',
		splIDClass: 'splID',
		splNameClass: 'splName',
		splSiteClass: 'splSite',
		splEmailClass: 'splEmail',
		editLinkClass: 'splEditLink',
		deleteLinkClass: 'splDeleteLink',
		splActionsClass: 'splActions',
		removeLIClass: 'removeLI',
		splFormTitle: $('#splFormTitle'),
		splFormName: $('#splFormName'),
		splFormSite: $('#splFormSite'),
		splFormCotactName: $('#splFormCotactName'),
		splFormPhone1: $('#splFormPhone1'),
		splFormPhone2: $('#splFormPhone2'),
		splFormPhone3: $('#splFormPhone3'),
		splFormFax: $('#splFormFax'),
		splFormEmail: $('#splFormEmail'),
		saveSplBtn: $('#saveSplBtn'),
		cancelSplBtn: $('#cancelSplBtn'),
		id: 0,
		defaultFormTitle: $('#splFormTitle').text(),
		defaultSaveBtnText: $('#saveSplBtn').text(),

		addHTML: function(id, name, site, email) {
			var li = $('<li />');
			var IDSpan = $('<span />');
			var nameSpan = $('<span />');
			var siteSpan = $('<span />');
			var emailSpan = $('<span />');
			var actionsSpan = $('<span />');
			var siteLink = $('<a />');
			var editLink = $('<a />');
			var deleteLink = $('<a />');

			IDSpan.addClass(this.splIDClass).text(id);
			nameSpan.addClass(this.splNameClass).text(name);
			siteSpan.addClass(this.splSiteClass).append(siteLink);
			emailSpan.addClass(this.splEmailClass).text(email);
			siteLink.attr({href: site, target: '_blank'}).text(site);
			editLink.addClass(this.editLinkClass).attr('href', '#').text('Editeaza');
			deleteLink.addClass(this.deleteLinkClass).attr('href', '#').text('Sterge');
			actionsSpan.addClass(this.splActionsClass).append(editLink).append(' / ').append(deleteLink);
			li.addClass(this.splRowClass).attr('data-id', id).append(IDSpan).append(nameSpan).append(siteSpan).append(((email) ? emailSpan : '')).append(actionsSpan);
			this.displaySuppliers.append(li);
		},

		save: function(e) {
			var self = $(e.target);
			var t = this;

			if (this.splFormName.val().length == 0 || this.splFormSite.val().length == 0) {
				alert('Campurile cu steluta(*) sunt obligatorii!');
				return false;
			}

			$.post(config.domain+'suppliers/save'+((this.id != 0) ? '/'+this.id : ''), {
					name: this.splFormName.val(),
					site: this.splFormSite.val(),
					contactName: this.splFormCotactName.val(),
					phone1: this.splFormPhone1.val(),
					phone2: this.splFormPhone2.val(),
					phone3: this.splFormPhone3.val(),
					fax: this.splFormFax.val(),
					email: this.splFormEmail.val()
				}, function(data) {
					if (data.status == 'ok') {
						if (t.id == 0) {
							t.addHTML(data.id, t.splFormName.val(), t.splFormSite.val(), t.splFormEmail.val());
						} else {
							var thisLI = t.displaySuppliers.children('li[data-id="'+t.id+'"]');
							var emailSpan = thisLI.children('.'+t.splEmailClass);

							thisLI.children('.'+t.splNameClass).text(t.splFormName.val());
							thisLI.children('.'+t.splSiteClass).children('a').attr('href', t.splFormSite.val()).text(t.splFormSite.val());

							if (emailSpan.length == 0 && t.splFormEmail.val().length > 0) {
								var span = $('<span />');
								span.addClass('.'+t.splEmailClass).text(t.splFormEmail.val());
								thisLI.children('.'+splSiteClass).after(span);
							} else if (emailSpan.length > 0 && t.splFormEmail.val().length > 0) {
								emailSpan.text('.'+t.splFormEmail.val());
							} else if (emailSpan.length > 0 && t.splFormEmail.val().length == 0) {
								emailSpan.remove();
							}
						}
						$('.'+t.removeLIClass).remove();
						t.cancelEdit();
						t.id = 0;
					} else if (data.status == 'error') {
						alert(data.msg);
					}
				}, 'json');
		},

		getForEdit: function(e) {
			e.preventDefault();
			if (this.id != 0 && !confirm('Esti sigur ca vrei sa editezi alt furnizor innainte de a salva modificarile facute furnizorului curent?')) {
				return false;
			}

			var self = $(e.target);
			var t = this;
			var id = self.parent().parent().attr('data-id');

			$.get(config.domain+'suppliers/getForEdit/'+id, function(data) {
				if (data.status == 'ok') {
					t.id = id;
					t.splFormName.val(data.row.name);
					t.splFormSite.val(data.row.site);
					t.splFormCotactName.val(data.row.contactName);
					t.splFormPhone1.val(data.row.phone1);
					t.splFormPhone2.val(data.row.phone2);
					t.splFormPhone3.val(data.row.phone3);
					t.splFormFax.val(data.row.fax);
					t.splFormEmail.val(data.row.email);

					t.splFormTitle.text('Editeaze furnizorul "'+data.row.name+'"');
					t.saveSplBtn.text('Salveaza');
					t.cancelSplBtn.removeClass('hide');
					// console.log('data', data);
				} else if (data.status == 'error') {
					alert(data.msg);
				}
			}, 'json');
		},

		deleteSupplier: function(e) {
			e.preventDefault();
			if (!confirm('Esti sigur ca vrei sa stergi?')) {
				return false;
			}

			var self = $(e.target);
			var t = this;
			var thisTR = self.parent().parent();
			var id = thisTR.attr('data-id');

			if (!thisTR.hasClass('new')) {
				$.get(config.domain+'suppliers/delete/'+id, function(data) {
					if (data.status == 'ok') {
						thisTR.remove();
					} else if (data.status == 'error') {
						alert(data.msg);
					}
				}, 'json');
			} else {
				thisTR.remove();
			}
		},

		cancelEdit: function() {
			this.id = 0;
			this.splFormName.val('');
			this.splFormSite.val('');
			this.splFormCotactName.val('');
			this.splFormPhone1.val('');
			this.splFormPhone2.val('');
			this.splFormPhone3.val('');
			this.splFormFax.val('');
			this.splFormEmail.val('');
			this.saveSplBtn.text(this.defaultSaveBtnText);
			this.splFormTitle.text(this.defaultFormTitle);
			this.cancelSplBtn.addClass('hide');
		}
	};

	suppliers.saveSplBtn.on('click', function(e) {
		suppliers.save(e);
	});

	suppliers.cancelSplBtn.on('click', function(e) {
		if (confirm('Esti sigur ca vrei sa anulezi?')) {
			suppliers.cancelEdit(e);
		}
	});

	docBody.on('click', '.'+suppliers.deleteLinkClass, function(e) {
		suppliers.deleteSupplier(e);
	});

	docBody.on('click', '.'+suppliers.editLinkClass, function(e) {
		suppliers.getForEdit(e);
	});

	docBody.on('click', '.'+suppliers.saveLinkClass, function(e) {
		suppliers.save(e);
	});
});