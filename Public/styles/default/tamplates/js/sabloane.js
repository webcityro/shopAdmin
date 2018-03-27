var sablon;

$(function(e) {
	var addSablonFormID		= $('#addSablonForm');
	var editSablonFormID	= $('#editSablonForm');
	var groupsDivID			= $('#groups');
	var sablonNameID		= $('#sablonName');
	var sablonTitleID		= $('#sablonTitle');
	var saveSablonBtnID		= '#saveSablon';
	var deleteSablon		= $('.deleteSablon');
	var addSablonBtn   		= $('#addSablonBtn');
	var displayCats    		= $('.displayCats');
	var moveBtn	    		= $('.moveUp, .moveDown');
	// var domain += 'sabloane/';

	var url = domain+'sabloane';

	sablon = {
		id: groupsDivID.data('sablonid'),
		name: '',
		json: {},
		groupFieldSetClass: 'group',
		groupLegendClass: 'groupNameBar',
		groupIdClass: 'groupID',
		groupNameClass: 'groupName',
		groupMenuClass: 'groupMenu',
		moveUpBtnClass: 'moveUp',
		moveDownBtnClass: 'moveDown',
		editabileTextClass: 'text',
		groupAddCaracteristicsBtnClass: 'addCaracteristicsBtn',
		crtIdTDClass: 'crtID',
		crtNameTDClass: 'crtName',
		crtUMTDClass: 'crtUM',
		crtDescTDClass: 'crtDesc',
		crtExtraInfoTDClass: 'crtExtraInfo',
		crtHideLabelTDClass: 'crtHideLabel',
		crtAscunsTDClass: 'crtHidden',
		crtOrdineTDClass: 'crtOrder',
		crtActiuniTDClass: 'crtActions',
		hideLabelCheckBoxClass: 'crtIsHiddenLabel',
		hiddenCheckBoxClass: 'crtIsHidden',
		saveOrCancelClass: 'saveOrCancel',
		saveLinkClass: 'saveLink',
		cancelLinkClass: 'candelLink',
		deleteCrtClass: 'deleteCrt',
		deleteGroupBtnClass: 'deleteGroupBtn',
		editClass: 'edit',
		addGroupBtnID: 'addGroupBtn',
		oldSablonName: '',
		oldGroupName: '',

		addOrEditSablon: function(name, action) {
			var value = $.trim(name);
			var id = (action == 'insert') ? categories.id : this.id;
			var t = this;

			if ((action == 'update' && value != this.oldSablonName) || (action == 'insert')) {
				if (categories.id == 0 && action == 'insert') {
					alert('Nu ai ales o categorie!');
				} else if (value.length == 0 || value.length > 50) {
					alert('Numele sablonului trebuie sa contina intre 1 si 50 caractere!');
				} else {
					$.post(url+'/addOrEditSablon/'+id+'/'+action, {
						name: value
					}, function(data) {
						if (data.status == 'ok') {
							if (action == 'insert') {
								window.location = url+'/edit/'+data.id;
							} else if (action == 'update') {
								alert('Numele sablonului a fost schimbat!');
							}
						} else if (data.status == 'error') {
							alert(data.msg);

							if (action == 'update') {
								sablonTitleID.text(t.oldSablonName);
							}
						}
						t.oldSablonName = '';
					}, 'json');
				}
			}
		},
		addGroup: function(e) {
			var groupFieldSet = $('<fieldset />');
			var groupLegend = $('<legend />');
			var groupNameSpan = $('<span />');
			var addGroupCaracteristicsButton = $('<button />');
			var deleteGroupButton = $('<button />');
			var groupMenuDiv = $('<div />');
			var moveUpBtn = $('<div />');
			var moveDownBtn = $('<div />');

			var crtTable = $('<table />');
			var crtThead = $('<thead>');
			var crtTbody = $('<tbody>');
			var crtTR = $('<tr>');

			var crtIdTH = $('<th>');
			var crtCaracteristicaTH = $('<th>');
			var crtUMTH = $('<th>');
			var crtDescTH = $('<th>');
			var crtExtraInfoTH = $('<th>');
			var crtHideLabelTH = $('<th>');
			var crtAscunsTH = $('<th>');
			var crtOrdineTH = $('<th>');
			var crtActiuniTH = $('<th>');

			crtIdTH.text('ID');
			crtCaracteristicaTH.text('Caracteristica');
			crtUMTH.text('UM');
			crtDescTH.text('Descriere');
			crtExtraInfoTH.text('Extra info');
			crtHideLabelTH.text('Ascunde label');
			crtAscunsTH.text('Ascunde?');
			crtOrdineTH.text('Ordine');
			crtActiuniTH.text('Actiuni');

			crtTR.append(crtIdTH).append(crtCaracteristicaTH).append(crtUMTH).append(crtDescTH).append(crtExtraInfoTH).append(crtHideLabelTH).append(crtAscunsTH).append(crtOrdineTH).append(crtActiuniTH);
			crtThead.append(crtTR);

			crtTbody.addClass();
			crtTable.append(crtThead).append(crtTbody);

			groupFieldSet.addClass(this.groupFieldSetClass).attr('data-sort', groupsDivID.children().length+1).append(groupLegend).append(crtTable).append(groupMenuDiv);
			groupMenuDiv.addClass(this.groupMenuClass).append(addGroupCaracteristicsButton).append(moveUpBtn).append(moveDownBtn).append(deleteGroupButton);
			groupLegend.addClass(this.groupLegendClass).append(groupNameSpan);
			groupNameSpan.addClass(this.groupNameClass+' new').attr('contenteditable', true).text('Numele grupului...').focus().select();
			addGroupCaracteristicsButton.addClass('button buttonSmall '+this.groupAddCaracteristicsBtnClass).text('Adauga o caracteristica');
			deleteGroupButton.addClass('button buttonSmall '+this.deleteGroupBtnClass).text('Sterge grupul');
			moveUpBtn.addClass(this.moveUpBtnClass).attr('data-type', 'attributesGroups');
			moveDownBtn.addClass(this.moveDownBtnClass).attr('data-type', 'attributesGroups');

			groupsDivID.append(groupFieldSet);
		},
		addCaracteristics: function(e) {
			var self = $(e.target);
			var tBody = self.parent().prev().children().last();
			var crtTR = $('<tr>');

			var crtIdTD = $('<td>');
			var crtNameTD = $('<td>');
			var crtUMTD = $('<td>');
			var crtDescTD = $('<td>');
			var crtExtraInfoTD = $('<td>');
			var crtHideLabelTD = $('<td>');
			var crtAscunsTD = $('<td>');
			var crtOrdineTD = $('<td>');
			var crtActiuniTD = $('<td>');

			var listingCheckBox = $('<input />');
			var hideLabelCheckBox = $('<input />');
			var importantCheckBox = $('<input />');
			var powerSearchCheckBox = $('<input />');
			var hiddenCheckBox = $('<input />');

			var groupMenuDiv = $('<div />');

			var moveUpBtn = $('<div />');
			var moveDownBtn = $('<div />');

			var saveOrCancelDiv = $('<div />');

			var saveLink = $('<a />');
			var cancelLink = $('<a />');

			crtIdTD.addClass(this.crtIdTDClass);
			crtNameTD.addClass(this.crtNameTDClass+' '+this.editabileTextClass).attr('contenteditable', true);
			crtUMTD.addClass(this.crtUMTDClass+' '+this.editabileTextClass).attr('contenteditable', true);
			crtDescTD.addClass(this.crtDescTDClass+' '+this.editabileTextClass).attr('contenteditable', true);
			crtExtraInfoTD.addClass(this.crtExtraInfoTDClass+' '+this.editabileTextClass).attr('contenteditable', true);
			crtHideLabelTD.addClass(this.crtHideLabelTDClass).append(hideLabelCheckBox);
			crtAscunsTD.addClass(this.crtAscunsTDClass).append(hiddenCheckBox);
			crtOrdineTD.addClass(this.crtOrdineTDClass).append(groupMenuDiv);
			crtActiuniTD.addClass(this.crtActiuniTDClass).append(saveOrCancelDiv);

			listingCheckBox.addClass(this.listingCheckBoxClass).attr('type', 'checkbox');
			hideLabelCheckBox.addClass(this.hideLabelCheckBoxClass).attr('type', 'checkbox');
			importantCheckBox.addClass(this.importantCheckBoxClass).attr('type', 'checkbox');
			hiddenCheckBox.addClass(this.hiddenCheckBoxClass).attr('type', 'checkbox');

			groupMenuDiv.addClass(this.groupMenuClass).append(moveUpBtn).append(moveDownBtn);

			moveUpBtn.addClass(this.moveUpBtnClass).attr('data-type', 'attributes');
			moveDownBtn.addClass(this.moveDownBtnClass).attr('data-type', 'attributes');

			saveOrCancelDiv.addClass(this.saveOrCancelClass).append(saveLink).append(' / ').append(cancelLink);
			saveLink.addClass(this.saveLinkClass).attr('href', '#').text('Salveaza');
			cancelLink.addClass(this.cancelLinkClass).attr('href', '#').text('Anuleaza');

			crtTR.addClass('new').append(crtIdTD).append(crtNameTD).append(crtUMTD).append(crtDescTD).append(crtExtraInfoTD).append(crtHideLabelTD).append(crtAscunsTD).append(crtOrdineTD).append(crtActiuniTD);
			tBody.append(crtTR);
		},
		editCaracteristics: function(e) {
			var self = $(e.target);
			var thisTR = (self.prop('tagName').toLowerCase() == 'td') ? self.parent() : self.parent().parent();

			if (!thisTR.hasClass(this.editClass) && !thisTR.hasClass('new')) {
				var id = thisTR.data('id');

				this.json['edit'+id] = {
					label: thisTR.children('.'+this.crtNameTDClass).text(),
					um: thisTR.children('.'+this.crtUMTDClass).text(),
					desc: thisTR.children('.'+this.crtDescTDClass).text(),
					info: thisTR.children('.'+this.crtExtraInfoTDClass).text(),
					hideLabel: (thisTR.children('.'+this.crtHideLabelTDClass).is(':checked')),
					hide: (thisTR.children('.'+this.crtAscunsTDClass).is(':checked'))
				};

				thisTR.addClass(this.editClass);
				thisTR.children('.'+this.crtActiuniTDClass).children('.'+this.saveOrCancelClass).removeClass('hide');
			}
		},
		cancelEditCaracteristics: function(e) {
			var self = $(e.target);
			var thisTR = self.parent().parent().parent();

			if (!thisTR.hasClass('new')) {
				var id = thisTR.data('id');

				thisTR.children('.'+this.crtNameTDClass).text(this.json['edit'+id].label);
				thisTR.children('.'+this.crtUMTDClass).text(this.json['edit'+id].um);
				thisTR.children('.'+this.crtDescTDClass).text(this.json['edit'+id].desc);
				thisTR.children('.'+this.crtExtraInfoTDClass).text(this.json['edit'+id].info);
				thisTR.children('.'+this.crtHideLabelTDClass).attr('checked', this.json['edit'+id].hideLabel);
				thisTR.children('.'+this.crtAscunsTDClass).attr('checked', this.json['edit'+id].hide);

				this.json['edit'+id] = undefined;

				thisTR.removeClass(this.editClass);
				self.parent().addClass('hide');
			} else {
				thisTR.remove();
			}
		},
		insertGroup: function(e) {
			var self = $(e.target);
			var value = self.text();

			if (value != this.oldGroupName) {
				if (value.length == 0 || value.length > 50) {
					alert('Numele trebuie sa contina intre 1 si 50 caractere!');
				} else {
					value = value.charAt(0).toUpperCase()+value.substr(1);
					self.text(value);
					var groupIdClass = this.groupIdClass;
					var sort = (self.hasClass('new')) ? self.parent().parent().prev().data('sort')+1 : self.parent().parent().data('sort');
					sort = (sort > 0) ? sort : 1;

					if (self.hasClass('new')) {
						var insertGroupURL = url+'/addOrUpdateGroup/'+this.id+'/insert';
					} else {
						var editAll = confirm('Apasa ok daca vrei sa modifica numele accestui grup pt toate celelate produse care il detin sau apasa cancel pt a modifica doar pt accest sablon!');
						var insertGroupURL = url+'/addOrUpdateGroup/'+self.parent().parent().data('id')+'/update/'+this.id+'/'+editAll;
					}

					$.post(insertGroupURL, {
						name: value,
						sort: sort
					}, function(data) {
						if (data.status == 'ok') {
							if (self.hasClass('new')) {
								var newSpan = $('<span />');

								self.removeClass('new');
								newSpan.addClass(groupIdClass).text('#'+data.id);
								self.before(newSpan);
								// alert('S-a adaugat grupul "'+value+'"!');
							} else {
								self.prev('span').text('#'+data.id);
								alert('S-a aptualizat grupul "'+value+'"!');
							}
							self.parent().parent().attr({'data-id': data.id, 'data-sort': sort});
						} else if (data.status == 'error') {
							alert(data.msg)
						}
					}, 'json');
				}
			}
			this.oldGroupName = '';
		},
		insertOreditCaracteristics: function(e) {
			var self = $(e.target);
			var thisTR = self.parent().parent().parent();

			var name = thisTR.children('.'+this.crtNameTDClass).text();
			var um = thisTR.children('.'+this.crtUMTDClass).text();
			var desc = thisTR.children('.'+this.crtDescTDClass).text();
			var info= thisTR.children('.'+this.crtExtraInfoTDClass).text();
			var hideLabel = (thisTR.children('.'+this.crtHideLabelTDClass).children('.'+this.hideLabelCheckBoxClass).is(':checked')) ? '1' : '0';
			var hide = (thisTR.children('.'+this.crtAscunsTDClass).children('.'+this.hiddenCheckBoxClass).is(':checked')) ? '1' : '0';
			var sort = thisTR.parent().children().length;
			var groupID = thisTR.parent().parent().parent().data('id');
			var id = (!thisTR.hasClass('new')) ? thisTR.data('id') : '';
			var t = this;

			if (name.length == 0) {
				alert("Nu ai completat Numele caranteristicii!");
			} else {
				var sablonID = groupsDivID.data('sablonid');
				var attrURL = url+"/insertOreditAttributes/"+sablonID;
				thisTR.children('.'+this.crtNameTDClass).text(name);

				if (!thisTR.hasClass("new")) {
					if (name != this.json['edit'+id].label) {
						var editAll = confirm('Apasa ok daca vrei sa modifica numele accestui atribut pt toate celelate produse care il detin sau apasa cancel pt a modifica doar pt accest sablon!');
						attrURL += '/'+id+'/'+editAll;
					} else {
						attrURL += '/'+id+'/false/false';
					}
				}

				$.post(attrURL, {
					name: name,
					um: um,
					desc: desc,
					info: info,
					hideLabel: hideLabel,
					hide: hide,
					sort: sort,
					groupID: groupID
				}, function(data) {
					if(data.status == "ok"){
						if (thisTR.hasClass("new")) {
							var deleteCrtLink = $('<a />');

							deleteCrtLink.addClass(t.deleteCrtClass).attr('data-id', data.id).text('Sterge');
							thisTR.children('.'+t.crtActiuniTDClass).append(deleteCrtLink);
							thisTR.removeClass('new').attr({'data-id': data.id, 'data-sort': sort});
							thisTR.children('.'+t.crtActiuniTDClass).children('.'+t.saveOrCancelClass).addClass('hide').append(' / ');
						} else {
							thisTR.children('.'+t.crtActiuniTDClass).children(t.deleteCrtClass).attr('data-id', data.id);
							thisTR.attr('data-id', data.id);
							thisTR.children('.'+t.crtActiuniTDClass).children('.'+t.saveOrCancelClass).addClass('hide');
						}
						thisTR.children('.'+t.crtIdTDClass).text('#'+data.id);
					} else if(data.status == "error"){
						alert(data.msg);
					}
				}, 'json');
			}
		},
		sort: function(e) {
			var self = $(e.target);
			var type = self.data('type');
			var direction = self.attr('class').replace('move', '').toLowerCase();

			if (type == 'attributesGroups') {
				var allGroups = groupsDivID.children();
				var thisFieldSet = self.parent().parent();
				var nrOfGroups = allGroups.length;
				var thisGroup = self.parent().parent();
				var switchFieldSet = (direction == 'down') ?
									 ((thisFieldSet.data('id') == allGroups.last().data('id')) ? allGroups.first() : thisFieldSet.next()) :
									 ((thisFieldSet.data('id') == allGroups.first().data('id')) ? allGroups.last() : thisFieldSet.prev());
				var count = nrOfGroups;
			} else {
				var thisFieldSet = self.parent().parent().parent();
				var thisGroup = thisFieldSet.parent();
				var allAttributes = thisGroup.children();
				var nrOfAttributes = allAttributes.length;
				var switchFieldSet = (direction == 'down') ?
									 ((thisFieldSet.data('id') == allAttributes.last().data('id')) ? allAttributes.first() : thisFieldSet.next()) :
									 ((thisFieldSet.data('id') == allAttributes.first().data('id')) ? allAttributes.last() : thisFieldSet.prev());
				var count = nrOfAttributes;
			}

			var sort = thisFieldSet.attr('data-sort');
			var newSort = switchFieldSet.attr('data-sort');
			var id = thisFieldSet.attr('data-id');
			var switchID = switchFieldSet.attr('data-id');
			// console.log('self');
			// console.log('type', type);
			// console.log('thisGroup', thisGroup);
			// console.log('thisFieldSet', thisFieldSet);
			// console.log('id', id);
			// console.log('sort', sort);
			// console.log('direction', direction);
			// console.log('nrOfGroups', nrOfGroups);
			// console.log('newSort', newSort);
			// console.log('switchID', switchID);
			// console.log('switchFieldSet', switchFieldSet);
			$.get(url+'/sort/'+type+'/'+newSort+'/'+sort+'/'+id+'/'+switchID+'/'+this.id+'/'+count, function(data) {
				if (data.status == 'ok') {
					thisFieldSet.remove();
					thisFieldSet.attr('data-sort', newSort);
					switchFieldSet.attr('data-sort', sort);
					var appender = (type == 'attributesGroups') ? groupsDivID : thisGroup;

					if (newSort == 1 && sort == count) { // mut ultima innainte de prima
							for (var x = 0; x <= count; x++) {
								appender.children().eq(x).attr('data-sort', x+2);
							}
							appender.prepend(thisFieldSet);
						} else if (newSort == count && sort == 1) { // mut prima dupa ultima
							for (var x = 0; x < count; x++) {
								appender.children().eq(x).attr('data-sort', x+1);
							}
							appender.append(thisFieldSet);
						} else if (direction == 'up') {
							switchFieldSet.before(thisFieldSet);
						} else if (direction == 'down') {
							switchFieldSet.after(thisFieldSet);
						}
				} else if (data.status == 'error') {
					alert(data.msg)
				}
			}, 'json');
		},
		deleteCaracteristics: function(e) {
			var self = $(e.target);
			var thisTR = self.parent().parent();
			var thisTbody = thisTR.parent();
			var id = thisTR.data('id');
			var sort = thisTR.data('sort');
			var label = thisTR.children('.'+this.crtNameTDClass).text();

			if (confirm('Esti sigur ca vrei sa stergi atributul "'+label+'"?')) {
				var deleteAll = confirm('Apasa ok daca vrei sa stergi accest atribut din toate celelate sabloane si produse sau apasa cancel daca vrei sa-l stergi numai din accest sablon si din produsele care ii corespund.');
				$.get(url+'/delete/attribute/'+this.id+'/'+id+'/'+deleteAll, function(data) {
					if (data.status == 'ok') {
						thisTR.remove();
						/*var allTRs = thisTbody.children();

						for (var x = sort; x < allTRs.length; x++) {
							allTRs.eq(x).attr('data-sort', x);
						}*/
					} else if (data.status == 'error') {
						alert(data.msg);
					}
				}, 'json');
			}
		},
		deleteGroup: function(e) {
			var self = $(e.target);
			var thisFieldSet = self.parent().parent();
			var thisSpan = thisFieldSet.children('.'+this.groupLegendClass).children('.'+this.groupNameClass);

			if (!thisSpan.hasClass('new')) {
				var id = thisFieldSet.data('id');
				var label = thisSpan.text();
				var sablonID = thisFieldSet.parent().data('sablonid');

				if (confirm('Esti sigur ca vrei sa stergi grupul "'+label+'"?')) {
					$.get(url+'/delete/group/'+sablonID+'/'+id, function(data) {
						if (data.status == 'ok') {
							var sort = thisFieldSet.data('sort');
							var allGroups = groupsDivID.children();
							thisFieldSet.remove();

							for (var x = sort; x < allGroups.length; x++) {
								allGroups.eq(x).attr('data-sort', x-1);
							}
						} else if (data.status == 'error') {
							alert(data.msg);
						}
					}, 'json');
				}
			} else {
				thisFieldSet.remove();
			}
		},
		delete: function(e) {
			var self = $(e.target);
			var thisLI = self.parent().parent();
			var id = self.data('id');
			var label = thisLI.children('span').text();

			if (confirm('Esti sigur ca vrei sa stergi sablonul "'+label+'"?')) {
				var deleteProducts = confirm('Vrei sa stergu si produsele cate corespund accestui sablon?');
				$.get(url+'/delete/sablon/'+id+'/0/'+deleteProducts, function(data) {
					if (data.status == 'ok') {
						thisLI.remove();
					} else if (data.status == 'error') {
						alert(data.msg);
					}
				}, 'json');
			}
		}
	};

	categories.init();

	docBody.on('click', '#'+sablon.addGroupBtnID, function(e) {
		sablon.addGroup(e);
	});

	docBody.on('click', '.'+sablon.groupAddCaracteristicsBtnClass, function(e) {
		sablon.addCaracteristics(e);
	});

	docBody.on('blur', '.'+sablon.groupNameClass, function(e) {
		sablon.insertGroup(e);
	});

	docBody.on('focus', '.'+sablon.groupNameClass, function(e) {
		sablon.oldGroupName = $(this).text();
	});

	sablonTitleID.on({
		blur: function(e) {
			sablon.addOrEditSablon($(this).text(), 'update');
		},
		focus: function(e) {
			sablon.oldSablonName = $(this).text();
		}
	});

	docBody.on('click', '.'+sablon.moveUpBtnClass+', .'+sablon.moveDownBtnClass, function(e) {
		sablon.sort(e);
	});

	docBody.on('click', '.'+sablon.saveLinkClass, function(e) {
		e.preventDefault();
		sablon.insertOreditCaracteristics(e);
	});

	docBody.on('click', '.'+sablon.cancelLinkClass, function(e) {
		e.preventDefault();
		sablon.cancelEditCaracteristics(e);
	});

	docBody.on('click', '.'+sablon.deleteCrtClass, function(e) {
		e.preventDefault();
		sablon.deleteCaracteristics(e);
	});

	docBody.on('click', '.'+sablon.deleteGroupBtnClass, function(e) {
		sablon.deleteGroup(e);
	});

	docBody.on('keyup', '.'+sablon.crtNameTDClass+', .'+sablon.crtUMTDClass+', .'+sablon.crtDescTDClass+', .'+sablon.crtExtraInfoTDClass, function(e) {
		sablon.editCaracteristics(e);
	});

	docBody.on('change', '.'+sablon.hideLabelCheckBoxClass+', .'+sablon.hiddenCheckBoxClass, function(e) {
		sablon.editCaracteristics(e);
	});

	addSablonBtn.on('click', function(e) {
		sablon.addOrEditSablon(sablonNameID.val(), 'insert');
	});

	deleteSablon.on('click', function(e) {
		e.preventDefault();
		sablon.delete(e);
	});
});