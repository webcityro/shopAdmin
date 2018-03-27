$(function() {
	var docBody = $('body');

	var feeds = {
		displayFeeds: $('#displayFeeds > tbody'),
		feedRowClass: 'feedRow',
		feedIDClass: 'feedID',
		feedNameClass: 'feedName',
		feedLoginTypeClass: 'feedLoginType',
		feedTypeClass: 'feedType',
		feedSupplierClass: 'feedSupplier',
		feedActionsClass: 'feedActions',
		structureItemsClass: 'structureItems',
		structureItemClass: 'structureItem',
		structureItemHeaderClass: 'structureItemHeader',
		structureItemLabelClass: 'structureItemLabel',
		structureItemValueClass: 'structureItemValue',
		structureItemXMLAttributesClass: 'structureItemXMLAttributes',
		structureItemXMLAttributeClass: 'structureItemXMLAttribute',
		structureItemXMLAttributeNameClass: 'structureItemXMLAttributeName',
		structureItemXMLAttributeValueClass: 'structureItemXMLAttributeValue',
		structureItemAttributeNameClass: 'structureItemAttributeName',
		structureItemAttributeValueClass: 'structureItemAttributeValue',
		structureItemExplodesClass: 'structureItemExplodes',
		structureItemExplodeHeaderClass: 'structureItemExplodeHeader',
		structureItemExplodeClass: 'structureItemExplode',
		structureItemExplodeKeyClass: 'structureItemExplodeKey',
		structureItemExplodeValueClass: 'structureItemExplodeValue',
		structureItemFieldClass: 'structureIteField',
		structureItemImageClass: 'structureItemImage',
		structureItemIgnoreClass: 'structureItemIgnore',
		structureItemCategoryClass: 'structureItemCategory',
		structureItemCommonFieldClass: 'structureItemCommonField',
		structureItemProductLinkClass: 'structureItemProductLink',
		structureItemProductClass: 'structureItemProduct',
		structureItemDeleteLinkClass: 'structureItemDeleteLink',
		structureItemIgnoreItemsClass: 'ignore',
		removeTRClass: 'removeTR',
		popupClass: 'feedPopup',
		popupPharagrafClass: 'popupPharagraf',
		popupBodyClass: 'popupBody',
		popupButtonsClass: 'popupButtons',
		popupOkButtonClass: 'popupOkButton',
		selectPathSegmentClass: 'pathSegment',
		popupCancelButtonClass: 'popupCancelButton',
		feedStructureMenuClass: 'feedStructureMenu',
		feedStructureMenuID: 'feedStructureMenu',
		feedStructureMenuItemClass: 'feedStructureMenuItem',
		feedFormTitle: $('#feedFormTitle'),

		feedActive: $('#feedActive'),
		feedFormName: $('#feedFormName'),
		feedSuppliersID: $('#feedSuppliersID'),
		runAfterIDFormRow: $('#runAfterIDFormRow'),
		runAfterID: $('#runAfterID'),
		feedLoginType: $('#feedLoginType'),
		loginDetalies: $('#loginDetalies'),
		feedLoginURL: $('#feedLoginURL'),
		feedLoginUsername: $('#feedLoginUsername'),
		feedLoginPassword: $('#feedLoginPassword'),
		feedLoginSoapClientArgs: $('#feedLoginSoapClientArgs'),
		feedLoginSoapDefineVars: $('#feedLoginSoapDefineVars'),
		feedLoginSoapLoginFunction: $('#feedLoginSoapLoginFunction'),
		feedLoginSoapResultsFunction: $('#feedLoginSoapResultsFunction'),
		feedLoginCurlUsernameFielt: $('#feedLoginCurlUsernameFielt'),
		feedLoginCurlPasswordFielt: $('#feedLoginCurlPasswordFielt'),
		feedMainURL: $('#feedMainURL'),
		downloadbleRow: $('#downloadbleRow'),

		feedTestConnection: $('#feedTestConnection'),
		feedConnectionStatus: $('#feedConnectionStatus'),
		feedStructure: $('#feedStructure'),
		inputData: $('.inputData'),
		feedType: $('#feedType'),
		feedStructure: $('#feedStructure'),
		saveFeedBtn: $('#saveFeedBtn'),
		cancelFeedBtn: $('#cancelFeedBtn'),
		editLinkClass: 'editLink',
		deleteLinkClass: 'deleteLink',
		id: 0,
		defaultFormTitle: $('#feedFormTitle').text(),
		jsonObj: {},
		jsonDefault: {
			id: 0,
			data: {},
			structure: {},
			usedFields: {},
			dbStructure: {repeat: {}, settings: {}},
			structureFile: '',
			connectionStatus: false,
		},
		structureRaw: '',
		jsonKey: 'jsonObj',
		usedFields: {},
		menuItems: {
			menuExplode: {label: 'Explode', callbeck: 'explodeField'},
			menuFields: {
				label: 'Campuri',
				children: {
					id: {label: 'ID produs', callbeck: 'setField|id'},
					name: {label: 'Nume', callbeck: 'setField|name'},
					description: {label: 'Descriere', callbeck: 'setField|description'},
					model: {label: 'Model', callbeck: 'setField|model'},
					upc: {label: 'Cod produs', callbeck: 'setField|upc'},
					quantity: {label: 'Cantitate', callbeck: 'setField|quantity'},
					stock: {label: 'Stoc', callbeck: 'setField|stock'},
					manufacturer: {label: 'Fabricant', callbeck: 'setField|manufacturer'},
					shipping: {label: 'Livrare', callbeck: 'setField|shipping'},
					tax: {label: 'T.V.A', callbeck: 'setField|tax'},
					price: {label: 'Pret', callbeck: 'setField|price'},
					priceSpecial: {label: 'Pret redus (promotie)', callbeck: 'setField|priceSpecial'},
					greenTax: {label: 'Timbru verde', callbeck: 'setField|greenTax'},
					currency: {label: 'Valuta', callbeck: 'setField|currency'},
					warranty: {label: 'Garantie', callbeck: 'setField|warranty'},
					warranty_class: {label: 'Unitate masura garantie', callbeck: 'setField|warranty_class'},
					weight: {label: 'Greutate', callbeck: 'setField|weight'},
					weight_class: {label: 'Unitate masura Greurate', callbeck: 'setField|weight_class'},
					width: {label: 'Latime', callbeck: 'setField|width'},
					length: {label: 'Lungime', callbeck: 'setField|length'},
					length_class: {label: 'Unitate masura Lungime', callbeck: 'setField|length_class'},
					height: {label: 'Inaltime', callbeck: 'setField|height'},
					minimum: {label: 'Cantitate minimum', callbeck: 'setField|minimum'}
				}
			},
			menuProduct: {label: 'Seteaza produs', callbeck: 'setProduct'},
			menuProductLink: {label: 'Link produs', callbeck: 'setProductLink'},
			menuCategory: {label: 'Categorie', callbeck: 'setCaregory'},
			menuCommunField: {label: 'Camp comun (numai daca se foloseste feed multiplu)', callbeck: 'setCommonField'},
			menuAttributeName: {label: 'Denumire atribut', callbeck: 'setAttributeName'},
			menuAttributeValue: {label: 'Valoare atribut', callbeck: 'setAttributeValue'},
			menuImage: {label: 'Imagine', callbeck: 'setImage'},
			menuIgnore: {label: 'Ignora', callbeck: 'setIgnore'}
		},
		menuTarget: '',
		popup: {
			self: '',
			pharagraf: '',
			body: '',
			buttons: '',
			okButton: '',
			cancelButton: ''
		},

		init: function () {
			if (typeof localStorage[this.jsonKey] != 'undefined') {
				this[this.jsonKey] = JSON.parse(localStorage[this.jsonKey]);
				this.id = this.json().id;
				this.populateData();
				this.renderHTML(this.feedStructure);
				this.renderSettings();
			} else {
				this[this.jsonKey] = this.jsonDefault;
				this.json().id = this.id;
			}

			if (this.id != 0) {
				this.feedFormTitle.text('Editeaza feed-ul "'+this.data().feedFormName+'"');
				this.saveFeedBtn.text('Salveaza');
			}
			// this[this.jsonKey].dbStructure.repeat = {};
			// this[this.jsonKey].dbStructure.settings = {};
			// delete this[this.jsonKey].dbStructure.settings.item_1.items.Atribut.value;
			// delete this[this.jsonKey].dbStructure.repeat.item_1;
			// delete this[this.jsonKey].dbStructure.settings.item_1;
			// this[this.jsonKey].dbStructure.repeat.item_0.path = 'content';
			// this.saveJSON();
			console.log('json', this[this.jsonKey]);

			this.popup.self = $('<div />');
			this.popup.pharagraf = $('<p />');
			this.popup.body = $('<div />');
			this.popup.buttons = $('<div />');
			this.popup.okButton = $('<button />');
			this.popup.cancelButton = $('<button />');

			this.popup.self.addClass(this.popupClass).append(this.popup.pharagraf).append(this.popup.body).append(this.popup.buttons);
			this.popup.pharagraf.addClass(this.popupPharagrafClass);
			this.popup.body.addClass(this.popupBodyClass);
			this.popup.buttons.addClass(this.popupButtonsClass).append(this.popup.okButton).append(this.popup.cancelButton);
			this.popup.okButton.addClass('button buttonSmall '+this.popupOkButtonClass).text('Gata');
			this.popup.cancelButton.addClass('button buttonSmall '+this.popupCancelButtonClass).text('Anuleaza');
		},

		json: function () {
			return this[this.jsonKey];
		},

		data: function () {
			return this.json().data;
		},

		structure: function () {
			return this.json().structure;
		},

		dbStructure: function () {
			return this.json().dbStructure;
		},

		saveJSON: function () {
			localStorage.setItem(this.jsonKey, JSON.stringify(this.jsonObj));
		},

		clearJSON: function() {
			localStorage.removeItem(this.jsonKey);
			this[this.jsonKey] = this.jsonDefault;
			console.log('jsonDefault', this[this.jsonKey]);
			this.saveJSON();
		},

		pushDataToJSON: function (e) {
			var self = $(e.target);
			this.data()[self.attr('id')] = (self.attr('type') == 'checkbox') ? self.is(':checked') : self.val();
			this.saveJSON();
		},

		populateData: function () {
			for (var e in this.data()) {
				if (this[e].attr('type') == 'checkbox') {
					this[e].attr('checked', this.data()[e]);
				} else {
					this[e].val(this.data()[e]);
				}
			}
			this.feedLoginType.change();
			// this.feedType.change();
		},

		save: function(e) {
			var self = $(e.target);
			var t = this;

			if (!this.validateLoginDetalies() || !this.validateData()) {
				return false;
			}/* else if (!this.json().connectionStatus) {
				alert('Nu s-a efectuat o conexiune reusita la feed!');
				return false;
			}*/

			$.post(config.domain+'feeds/save/'+this.id, {
					feedFormName: this.feedFormName.val(),
					feedSuppliersID: this.feedSuppliersID.val(),
					runAfterID: this.runAfterID.val(),
					loginType: this.feedLoginType.val(),
					userName: this.feedLoginUsername.val(),
					password: this.feedLoginPassword.val(),
					feedLoginSoapDefineVars: this.feedLoginSoapDefineVars.val(),
					feedLoginSoapClientArgs: this.feedLoginSoapClientArgs.val(),
					feedLoginSoapLoginFunction: this.feedLoginSoapLoginFunction.val(),
					feedLoginSoapResultsFunction: this.feedLoginSoapResultsFunction.val(),
					userNameField: this.feedLoginCurlUsernameFielt.val(),
					passwordField: this.feedLoginCurlPasswordFielt.val(),
					feedLoginURL: this.feedLoginURL.val(),
					mainURL: this.feedMainURL.val(),
					type: this.feedType.val(),
					connectionStatus: this.json().connectionStatus,
					tempFile: this.json().structureFile,
					settings: JSON.stringify(this.dbStructure().settings),
					structure: JSON.stringify(this.dbStructure().repeat),
					feedActive: (this.feedActive.is(':checked')) ? '1' : '0'
				}, function(data) {
					if (data.status == 'ok') {
						var supplierName = t.feedSuppliersID.children('[value="'+t.feedSuppliersID.val()+'"]').text();
						var loginType = t.feedLoginType.children('[value="'+t.feedLoginType.val()+'"]').text();
						var type = t.feedType.children('[value="'+t.feedType.val()+'"]').text();

						if (t.id == 0) {
							var tr = $('<tr />');
							var idTD = $('<td />');
							var nameTD = $('<td />');
							var supplierTD = $('<td />');
							var loginTypeTD = $('<td />');
							var typeTD = $('<td />');
							var actionsTD = $('<td />');
							var editLink = $('<a />');
							var deleteLink = $('<a />');

							tr.addClass(t.feedRowClass).attr('data-id', data.id).append(idTD).append(nameTD).append(supplierTD).append(loginTypeTD).append(typeTD).append(actionsTD);
							idTD.addClass(t.feedIDClass).text(data.id);
							nameTD.addClass(t.feedNameClass).text(t.feedFormName.val());
							supplierTD.addClass(t.feedSupplierClass).text(supplierName);
							loginTypeTD.addClass(t.feedLoginTypeClass).text(loginType);
							typeTD.addClass(t.feedTypeClass).text(type);
							actionsTD.addClass(t.feedActionsClass).append(editLink).append(' / ').append(deleteLink);
							editLink.addClass(t.editLinkClass).attr('href', '#').text('Editeaza');
							deleteLink.addClass(t.deleteLinkClass).attr('href', '#').text('Sterge');

							t.displayFeeds.append(tr);
							t.displayFeeds.children('.'+t.removeTRClass).remove();
						} else {
							var thisTR = t.displayFeeds.children('[data-id="'+t.id+'"]');

							thisTR.children('.'+t.feedNameClass).text(t.feedFormName.val());
							thisTR.children('.'+t.feedSupplierClass).text(supplierName);
							thisTR.children('.'+t.feedLoginTypeClass).text(loginType);
							thisTR.children('.'+t.feedTypeClass).text(type);
						}
						t.cancelEdit();
					}

					if (data.msg.length > 0) {
						alert(data.msg);
					}
				}, 'json');
		},

		getForEdit: function(e) {
			e.preventDefault();
			if (this.id != 0 && !confirm('Esti sigur ca vrei sa editezi alt feed innainte de a salva modificarile facute feed-ului curent?')) {
				return false;
			}
			this.cancelEdit();

			var self = $(e.target);
			var t = this;
			var id = self.parent().parent().attr('data-id');

			$.get(config.domain+'feeds/getForEdit/'+id, function(data) {
				if (data.status == 'ok') {
					t.id = id;
					t.json().id = id;
					t.feedActive.prop('checked', (data.row.active == '1')).change();
					t.feedSuppliersID.val(data.row.supplierID).change();
					t.feedFormName.val(data.row.name).change();
					t.feedLoginType.val(data.row.loginType).change();
					t.feedLoginUsername.val(data.row.loginUserName).change();
					t.feedLoginPassword.val(data.row.loginPassword).change();
					t.feedLoginSoapDefineVars.val(data.row.loginSoapVars).change();
					t.feedLoginSoapClientArgs.val(data.row.loginSoapClientArgs).change();
					t.feedLoginSoapLoginFunction.val(data.row.loginSoapLoginFunction).change();
					t.feedLoginSoapResultsFunction.val(data.row.loginSoapResultsFunction).change();
					t.feedLoginCurlUsernameFielt.val(data.row.loginCURLusernameField).change();
					t.feedLoginCurlPasswordFielt.val(data.row.loginCURLpasswordField).change();
					t.feedLoginURL.val(data.row.loginURL).change();
					t.feedMainURL.val(data.row.mainURL).change();
					t.feedType.val(data.row.type);
					t.data().feedType = data.row.type;
					t.json().connectionStatus = (data.row.connectionStatus == '1') ? true : false;
					t.dbStructure().repeat = JSON.parse(data.row.structure);
					t.dbStructure().settings = JSON.parse(data.row.settings);

					if (t.json().connectionStatus && typeof data.content != 'undefined') {
						t.json().structureFile = data.file;
						t.feedConnectionStatus.text('Conexiune reusita!');
						t.changeType(data.row.type, data.content);
					}


					t.saveJSON();
					t.renderSettings();
					t.feedFormTitle.text('Editeaza feed-ul "'+t.data().feedFormName+'"');
					t.saveFeedBtn.text('Salveaza');
				}

				if (data.msg.length > 0) {
					alert(data.msg);
				}
			}, 'json');
		},

		testConnection: function (e) {
			var self = $(e.target);

			if (this.validateLoginDetalies()) {
				var t = this;
				this.feedConnectionStatus.text('Se conecteaza...');
				self.addClass('loading').attr('disabled', true);

				$.post(config.domain+'feeds/testConnection'+((this.json().structureFile.length > 0) ? '/'+this.json().structureFile : ''), {
					type: this.feedLoginType.val(),
					userName: this.feedLoginUsername.val(),
					password: this.feedLoginPassword.val(),
					feedLoginSoapDefineVars: this.feedLoginSoapDefineVars.val(),
					feedLoginSoapClientArgs: this.feedLoginSoapClientArgs.val(),
					feedLoginSoapLoginFunction: this.feedLoginSoapLoginFunction.val(),
					feedLoginSoapResultsFunction: this.feedLoginSoapResultsFunction.val(),
					userNameField: this.feedLoginCurlUsernameFielt.val(),
					passwordField: this.feedLoginCurlPasswordFielt.val(),
					url: this.feedLoginURL.val(),
					mainURL: this.feedMainURL.val(),
				}, function (data) {
					self.removeClass('loading').attr('disabled', false);

					if (data.status == 'ok') {
						t.json().structureFile = data.file;
						// t.findStructureType(t.structure());
						t.json().connectionStatus = true;
						t.saveJSON();
						t.feedConnectionStatus.text('Conexiune reusita!');
						t.feedStructure.text(data.content);
						t.structureRaw = data.content;

						if (t.feedType.val() != 'none') {
							t.changeType(t.feedType.val(), data.content);
						}
					} else if (data.status == 'error') {
						t.feedConnectionStatus.text(data.msg);
					}
				}, 'json');
			}
		},

		findStructureType: function (structure) {

		},

		changeLoginType: function (e) {
			var self = $(e.target);
			this.loginDetalies.children().addClass('hide');
			this.loginDetalies.children('.'+self.val().toLowerCase()).removeClass('hide');
		},

		changeSupplierID: function (e) {
			var self = $(e.target);
			var value = self.val();

			this.runAfterID.children('[value!="0"]').remove();

			if (typeof feedsListBySupplyers[value] != 'undefined') {
				for (var i in feedsListBySupplyers[value]) {
					if (feedsListBySupplyers[value][i].id != this.id) {
						var option = $('<option />');
						option.attr('value', feedsListBySupplyers[value][i].id).text(feedsListBySupplyers[value][i].name);
						this.runAfterID.append(option);
					}
				}
			}
		},

		changeType: function (type, structure, explodeSpliter) {
			if (type == 'none') {
				return false;
			}
			var parser = new window['parse'+type]();
			var t = this;

			var structureRaw = (typeof structure == 'undefined') ? this.structureRaw : ((typeof explodeSpliter == 'string') ? {spliter: explodeSpliter, structure: structure} : structure);

			if (typeof structureRaw == 'string' && structureRaw.length == 0) {
				var oReq = new XMLHttpRequest();
				oReq.open("GET", config.tempURL+this.json().structureFile, true);
				oReq.responseType = (type == 'Excel') ? "arraybuffer" : 'text';

				oReq.onload = function(e) {
					t.changeType(type, oReq.response);
				}

				oReq.send();
				return true;
			}

			parser.parse(structureRaw, function(json) {
				if (type == 'Explode' && typeof explodeSpliter == 'undefined') {
					t.renderHTML(t.feedStructure, json);
					var spliter = prompt('Care este separatorul dintre produse?');
					t.changeType(type, structureRaw, spliter);
					return true;
				}
				t.json().structure = json;
				t.feedStructure.html('');
				t.renderHTML(t.feedStructure);
				t.saveJSON();
			}, 10);
		},

		validateLoginDetalies: function () {
			var type = this.feedLoginType.val().toLowerCase();

			if (type == 'none' && this.feedMainURL.val().length == 0) {
				alert('Nu ai completat URL-ul principal!')
				return false;
			} else if (type != 'none' && this.feedLoginURL.val().length == 0) {
				alert('Nu ai completat URL-ul de logare!')
				return false;
			}

			if ((type == 'url' || type == 'curl') && (this.feedLoginUsername.val().length == 0 || this.feedLoginPassword.val().length == 0)) {
				alert('Nu ai completat numele de utilizator sau parola!');
				return false;
			}

			if ((type == 'curl') && (this.feedLoginCurlUsernameFielt.val().length == 0 || this.feedLoginCurlPasswordFielt.val().length == 0)) {
				alert('Nu ai completat Key post curl nume de utilizator / parola!');
				return false;
			}/*
			nu sunt sigur
			if (type == 'soap' && (this.feedLoginSoapResultsFunction.val().length == 0 || this.feedLoginSoapLCUL.val().length == 0 || this.feedLoginPassword.val().length == 0)) {
				alert('Nu ai completat CLC, CLUC sau parola!');
				return false;
			}*/
			return true;
		},

		validateData: function() {
			if (this.feedFormName.val().length == 0 || this.feedSuppliersID.val() == '0' || this.feedType.val() == 'none') {
				alert('Campurile marcate cu (*) sunt obligatorii!');
				return false;
			} else if (this.feedLoginURL.val().length == 0 && this.feedMainURL.val().length == 0) {
				alert('N-ai completat nici una dintre adresele URL!');
				return false;
			}
			return true;
		},

		deleteFeed: function(e) {
			e.preventDefault();

			var self = $(e.target);
			var t = this;
			var thisTR = self.parent().parent();
			var id = thisTR.attr('data-id');

			if (!confirm('Esti sigur ca vrei sa stergi feed-ul "'+thisTR.find('.'+this.feedNameClass).text()+'"?')) {
				return false;
			}

			var deleteProducts = confirm('Vrei sa stergi si produsele care apartn acestui feed?');

			$.get(config.domain+'feeds/delete/'+id+'/'+((deleteProducts) ? 'true' : 'false'), function(data) {
				if (data.status == 'ok') {
					thisTR.remove();

					if (id == t.id) {
						t.cancelEdit();
					}
				} else if (data.status == 'error') {
					alert(data.msg);
				}
			}, 'json');
		},

		cancelEdit: function() {
			this.id = 0;
			this.feedFormName.val('').change();
			this.feedSuppliersID.val('0').change();
			this.feedLoginType.val('none').change();
			this.feedLoginUsername.val('').change();
			this.feedLoginPassword.val('').change();
			this.feedLoginSoapDefineVars.val('').change();
			this.feedLoginSoapClientArgs.val('').change();
			this.feedLoginSoapLoginFunction.val('').change();
			this.feedLoginSoapResultsFunction.val('').change();
			this.feedLoginCurlUsernameFielt.val('').change();
			this.feedLoginCurlPasswordFielt.val('').change();
			this.feedLoginURL.val('').change();
			this.feedMainURL.val('').change();
			this.feedType.val('none').change();
			this.json().connectionStatus = false;
			this.saveFeedBtn.text('Adauga');
			this.feedFormTitle.text(this.defaultFormTitle);
			this.feedConnectionStatus.text('');
			this.feedStructure.html('');
			this.feedActive.attr('checked', false).change();
			// this.cancelFeedBtn.addClass('hide');
			this.clearJSON();

			if (this.json().structureFile.length > 0) {
				$.get(config.domain+'feeds/deleteTempFile/'+this.json().structureFile, function(data) {
					if (data.status == 'ok') {

					} else if (data.status == 'error') {
						alert(data.msg);
					}
				}, 'json');
			}

			if (typeof this.usedFields.menuCategory != 'undefined') {
				this.menuItems.menuCategory = this.usedFields.menuCategory;
				delete this.usedFields.menuCategory;
			}

			if (typeof this.usedFields.menuProduct != 'undefined') {
				this.menuItems.menuProduct = this.usedFields.menuProduct;
				delete this.usedFields.menuProduct;
			}

			if (typeof this.usedFields.menuProductLink != 'undefined') {
				this.menuItems.menuProductLink = this.usedFields.menuProductLink;
				delete this.usedFields.menuProductLink;
			}

			for (var i in this.usedFields) {
				this.menuItems.menuFields.children[i] = this.usedFields[i];
				delete this.usedFields[i];
			}
		},

		showMenu: function(e, json) {
			var menu = (json) ? json : this.menuItems;
			var ul = $('<ul />');
			var t = this;

			for (var i in menu) {
				var li = $('<li />');

				li.addClass(this.feedStructureMenuItemClass).text(menu[i].label);

				if (typeof menu[i].callbeck != 'undefined') {
					li.attr('data-callbeck', menu[i].callbeck).css({cursor: 'pointer'}).on('click', function(el) {
						var callbeck = $(el.target).attr('data-callbeck');

						if (callbeck.indexOf('|') != -1) {
							var cb = callbeck.split('|');
							t[cb[0]](el, cb[1])
						} else {
							t[callbeck](el);
						}
					});
				}
				if (typeof menu[i].children == 'object') {
					this.showMenu(li, menu[i].children);
				}

				ul.append(li);
			}

			if (typeof json == 'object') {
				ul.addClass(this.feedStructureMenuClass);
				e.append(ul);
			} else {
				var self = $(e.target);
				var offset = self.offset();

				ul.attr('id', this.feedStructureMenuID).css({top: offset.top+'px', left: offset.left+'px'});
				docBody.append(ul);
				this.menuTarget = self;
			}
		},

		closeMenu: function() {
			// this.menuTarget = '';
			$('#'+this.feedStructureMenuID).remove();
		},

		getItem: function(json, self) {
			var itemClass = ((typeof self == 'undefined') ? this.menuTarget : self).attr('class').split(' ')[0];
			var item = {};

			switch (itemClass) {
				case this.structureItemValueClass:
				item.json = json.value;
				item.key = 'value';
				break;
				case this.structureItemXMLAttributeNameClass:
				item.json = json.attr[this.menuTarget.parent().attr('data-index')].name;
				item.key = 'attributeXMLName';
				break;
				case this.structureItemXMLAttributeValueClass:
				item.json = {name: json.attr[this.menuTarget.parent().attr('data-index')].name, value: json.attr[this.menuTarget.parent().attr('data-index')].value};
				item.key = 'attributeXMLValue';
				break;
				case this.structureItemLabelClass:
				item.json = json.text;
				item.key = 'text';
				break;
				case this.structureItemExplodeValueClass:
				item.json = {value: this.menuTarget.text()};
				item.key = 'explode';
				break;
			}
			return item;
		},

		getClass: function(key) {
			switch (key) {
				case 'value':
				return this.structureItemValueClass;
				break;
				case 'attributeXMLName':
				return this.structureItemXMLAttributeNameClass;
				break;
				case 'attributeXMLValue':
				return this.structureItemXMLAttributeValueClass;
				break;
				case 'text':
				return this.structureItemLabelClass;
				break;
			}
		},

		setField: function(e, fieldName) {
			var t = this;

			this.setItem({
				settings: {field: fieldName},
				setRepeat: true,
				appendNewElement: function (data) {
					var settingsPath = (typeof data.dataSettingsPath != 'undefined') ? data.dataSettingsPath : data.newPath+'|'+data.item.key;
					if (data.element.children().length > 0) {
						data.element.children().first().before(t.renderField(fieldName, settingsPath));
					} else {
						data.element.append(t.renderField(fieldName, settingsPath));
					}
				}
			});
		},

		explodeField: function(e) {
			var item = this.menuTarget;
			var spliter = prompt('Caracterele la care vrei sa fie spart textul.');
			var explodeJson = {explode: {spliter: spliter, items: {}}};
			var t = this;

			if (!spliter) {
				return false;
			}

			if (item.text().indexOf(spliter) > -1) {
				this.setItem({
					settings: explodeJson,
					appendNewElement: function (data) {
						var thisPath = t.getPath(data.li);
						data.element.append(t.rendreExplodes(explodeJson.explode, thisPath, data.explodePath, data.element.text()));
					}
				});
			} else {
				alert('nu s-au gasit caracterele specificate pentru spargerea textului!');
			}
		},

		setItem: function(options) {
			var isExplode = this.menuTarget.hasClass(this.structureItemExplodeValueClass);

			if (isExplode) {
				var path = this.getPath(this.menuTarget);
				var json = {};
				var item = this.getItem(json);
				var itemIndex = this.menuTarget.parent().attr('data-index');
				var dataSettingsPath = this.menuTarget.parent().parent().attr('data-setting-path');
				var structurePath = dataSettingsPath+'/!items/index_'+itemIndex;
				var pArr = structurePath.split('|');
			} else {
				var path = this.getPath(this.menuTarget);
				var json = this.goToJsonPath(path);
				var item = this.getItem(json);
				var structurePath = json.structurePath;
			}
			var t = this;
			// console.log('path', path);
			// console.log('pArr', pArr);
			// console.log('json', json);
			// console.log('item', item);
			// console.log('structurePath', structurePath);
			this.askForPath(structurePath, path, function(np) {
				var explodePath;
				var basePath;
				var field;
				var settings;
				var fieldPath;
				var appendPath;
				var settingsPath;
				var fieldJson = options.settings;
				var itemKey = Object.keys(fieldJson)[0];

				if (isExplode) {
					basePath = pArr[0].split('/');
					appendPath = pArr[0];
					fieldPath = pArr[1];
					field = basePath.pop();
					settings = t.getSettings(basePath.join('/'), field, fieldJson);
					var newPath = np.split('/');
					var key = newPath.pop();
					settingsPath = newPath.join('.').replace(/\!/g, '');
					explodePath = structurePath+'/!explode';

					if (key == 'all') {
						fieldPath = fieldPath.split('/');
						/*if (fieldPath.length > 1) {
							fieldPath.pop();
						}*/

						fieldPath.pop();
						settingsPath = fieldPath.join('.').replace(/\/|\|/g,'.').replace(/\!/g, '');

						eval('settings.'+settingsPath+'.all = fieldJson;');
					} else {
						eval('var setting = settings.'+settingsPath+';');
						eval('settings.'+settingsPath+'.'+key+((typeof setting[key] == 'object') ? '[itemKey] = fieldJson[itemKey];' : ' = {'+itemKey+': fieldJson[itemKey]};'));
					}
				} else {
					appendPath = np;
					basePath = np.split('/');

					if (basePath.length == 1) {
						field = item.key;
						settings = t.getSettings(basePath.join('/'), undefined, fieldJson);
						// console.log(settings);
						settings[item.key] = (typeof settings[item.key] == 'undefined') ? {} : settings[item.key];
						dataSettingsPath = np+'|!this/'+item.key;
					} else {
						field = basePath.pop();
						settings = t.getSettings(basePath.join('/'), field, fieldJson);
						settings[item.key] = (typeof settings[item.key] == 'undefined') ? {} : settings[item.key];
					}

					explodePath = np+'|!'+item.key+'/!explode';

					if (item.key == 'attributeXMLValue') {
						settings[item.key][item.json.name.value] = {};
						settings[item.key][item.json.name.value][itemKey] = fieldJson[itemKey];
					} else {
						settings[item.key][itemKey] = fieldJson[itemKey];
					}
				}

				t.setSetting(basePath.join('/'), field, settings);

				if (typeof options.setRepeat != 'undefined' && options.setRepeat) {
					// console.log('field', field);
					// console.log('settings', settings);
					// console.log('basePath', basePath);
					t.setRepeat(basePath.join('/'), field, settings);
				}

				t.aplyHTMLToPath(appendPath, function(e) {
					options.appendNewElement({
						element: t.getElementForAppendingNewItem(e, dataSettingsPath, (item.key == 'attributeXMLValue') ? item.json.name.value : ''),
						li: e,
						explodePath: explodePath,
						newPath: np,
						settings: settings,
						field: field,
						basePath: basePath,
						settingsPath: settingsPath,
						dataSettingsPath: dataSettingsPath,
						item: item
					});
				});
				t.closeMenu();
			});
		},

		setAttributeName: function (e) {
			var t = this;

			this.setItem({
				settings: {attribute: 'name'},
				setRepeat: true,
				appendNewElement: function (data) {
					var settingsPath = (typeof data.dataSettingsPath != 'undefined') ? data.dataSettingsPath : data.newPath+'|'+data.item.key;
					if (data.element.children().length > 0) {
						data.element.children().first().before(t.renderAttributeName(settingsPath));
					} else {
						data.element.append(t.renderAttributeName(settingsPath));
					}
				}
			});
		},

		setAttributeValue: function (e) {
			var t = this;

			this.setItem({
				settings: {attribute: 'value'},
				setRepeat: true,
				appendNewElement: function (data) {
					var settingsPath = (typeof data.dataSettingsPath != 'undefined') ? data.dataSettingsPath : data.newPath+'|'+data.item.key;
					if (data.element.children().length > 0) {
						data.element.children().first().before(t.renderAttributeValue(settingsPath));
					} else {
						data.element.append(t.renderAttributeValue(settingsPath));
					}
				}
			});
		},

		setImage: function (e) {
			var t = this;

			this.setItem({
				settings: {image: true},
				setRepeat: true,
				appendNewElement: function (data) {
					var settingsPath = (typeof data.dataSettingsPath != 'undefined') ? data.dataSettingsPath : data.newPath+'|'+data.item.key;
					if (data.element.children().length > 0) {
						data.element.children().first().before(t.renderImage(settingsPath));
					} else {
						data.element.append(t.renderImage(settingsPath));
					}
				}
			});
		},

		setIgnore: function (e) {
			var t = this;

			this.setItem({
				settings: {ignore: true},
				setRepeat: true,
				appendNewElement: function (data) {
					var settingsPath = (typeof data.dataSettingsPath != 'undefined') ? data.dataSettingsPath : data.newPath+'|'+data.item.key;
					if (data.element.children().length > 0) {
						data.element.children().first().before(t.renderIgnore(settingsPath));
					} else {
						data.element.append(t.renderIgnore(settingsPath));
					}
					t.aplyIgnoreToItems(data.element);
				}
			});
		},

		setCaregory: function (e) {
			var t = this;

			this.setItem({
				settings: {category: true},
				setRepeat: true,
				appendNewElement: function (data) {
					var settingsPath = (typeof data.dataSettingsPath != 'undefined') ? data.dataSettingsPath : data.newPath+'|'+data.item.key;
					if (data.element.children().length > 0) {
						data.element.children().first().before(t.renderCategory(settingsPath));
					} else {
						data.element.append(t.renderCategory(settingsPath));
					}
				}
			});
		},

		setCommonField: function (e) {
			var t = this;

			this.setItem({
				settings: {commonField: true},
				setRepeat: true,
				appendNewElement: function (data) {
					var settingsPath = (typeof data.dataSettingsPath != 'undefined') ? data.dataSettingsPath : data.newPath+'|'+data.item.key;
					if (data.element.children().length > 0) {
						data.element.children().first().before(t.renderCommonField(settingsPath));
					} else {
						data.element.append(t.renderCommonField(settingsPath));
					}
				}
			});
		},

		setProductLink: function (e) {
			var t = this;

			this.setItem({
				settings: {productLink: true},
				setRepeat: true,
				appendNewElement: function (data) {
					var settingsPath = (typeof data.dataSettingsPath != 'undefined') ? data.dataSettingsPath : data.newPath+'|'+data.item.key;
					if (data.element.children().length > 0) {
						data.element.children().first().before(t.renderProductLink(settingsPath));
					} else {
						data.element.append(t.renderProductLink(settingsPath));
					}
				}
			});
		},

		setProduct: function (e) {
			var t = this;

			this.setItem({
				settings: {product: true},
				setRepeat: true,
				appendNewElement: function (data) {
					var settingsPath = (typeof data.dataSettingsPath != 'undefined') ? data.dataSettingsPath : data.newPath+'|'+data.item.key;
					if (data.element.children().length > 0) {
						data.element.children().first().before(t.renderProduct(settingsPath));
					} else {
						data.element.append(t.renderProduct(settingsPath));
					}
				}
			});
		},

		getElementForAppendingNewItem: function (e, settingsPath, attributeXMLName) {
			return (this.menuTarget.hasClass(this.structureItemExplodeValueClass)) ?
				e.find('.'+this.structureItemExplodesClass+'[data-setting-path="'+settingsPath+'"] > .'+this.structureItemExplodeClass+'[data-index="'+this.menuTarget.parent().attr('data-index')+'"] > .'+this.structureItemExplodeValueClass) :
				((this.menuTarget.attr('class') == this.structureItemXMLAttributeValueClass) ?
					e.find('.'+this.structureItemXMLAttributeValueClass+'[data-name="'+attributeXMLName+'"]') :
					((this.menuTarget.hasClass(this.structureItemLabelClass)) ? e.children('.'+this.structureItemHeaderClass).children('.'+this.structureItemLabelClass) : e.find('.'+this.menuTarget.attr('class'))));
		},

		getPathForDBstructureFromJSON: function(path, newPath) {
			var returnPath = [];
			path = path.split('/');

			for (var i in newPath) {
				returnPath[i] = (newPath[i] == 'all') ? 'all' : path[i];
			}
			return returnPath;
		},

		renderHTML: function(parent, json, path) {
			var json = (json) ? json : this.structure();
			var path = (path) ? path : '';
			var ul = $('<ul />');
			var x = 0;

			if ($.isEmptyObject(json)) {
				return false;
			}

			ul.addClass(this.structureItemsClass);

			for (var e in json) {
				if (e == 'children') {
					continue;
				}

				var thisPath = (!path) ? e : path+'/'+e;
				var li = $('<li />');
				var header = $('<div />');
				var label = $('<span />');
				var value = $('<span />');
				var attrbs = $('<span />');

				li.addClass(this.structureItemClass).attr({'data-path': thisPath, 'data-structure-path': json[e].structurePath}).append(header);
				header.addClass(this.structureItemHeaderClass).append(label).append(attrbs).append(value);
				label.addClass(this.structureItemLabelClass).text(json[e].text.value);
				value.addClass(this.structureItemValueClass).text((typeof json[e].value != 'undefined') ? ' = '+json[e].value.value : '');
				attrbs.addClass(this.structureItemXMLAttributesClass);

				if (typeof json[e].attr == 'object') {
					for (var i in json[e].attr) {
						var attr = $('<span />');
						var attrName = $('<span />');
						var attrValue = $('<span />');


						attr.addClass(this.structureItemXMLAttributeClass).attr({'data-index': i}).append(attrName).append(' = ').append(attrValue);
						attrName.addClass(this.structureItemXMLAttributeNameClass).text(json[e].attr[i].name.value);
						attrValue.addClass(this.structureItemXMLAttributeValueClass).attr('data-name', json[e].attr[i].name.value).text(json[e].attr[i].value.value);
						attrbs.append('[').append(attr).append(']');
					}
				}

				if (typeof json[e].children == 'object') {
					this.renderHTML(li, json[e].children, thisPath);
				} else {

				}
				ul.append(li);
			}
			parent.append(ul);
		},

		renderField: function(field, settingPath) {
			var span = $('<span />');
			var label = (typeof this.menuItems.menuFields.children[field] == 'object') ? this.menuItems.menuFields.children[field].label : this.usedFields[field].label;
			span.addClass(this.structureItemFieldClass).attr({'data-setting-path': settingPath, 'data-field': field}).text('Camp: '+label).append(this.renderDeleteLink('deleteField'));

			if (typeof this.menuItems.menuFields.children[field] == 'object') {
				this.usedFields[field] = this.menuItems.menuFields.children[field];
				delete this.menuItems.menuFields.children[field];
			}

			return span;
		},

		renderProductLink: function(settingPath) {
			var span = $('<span />');
			span.addClass(this.structureItemProductLinkClass).attr('data-setting-path', settingPath).text('Link produs').append(this.renderDeleteLink('deleteProductLink'));

			if (typeof this.menuItems.menuProductLink == 'object') {
				this.usedFields.menuProductLink = this.menuItems.menuProductLink;
				delete this.menuItems.menuProductLink;
			}

			return span;
		},

		renderAttributeName: function(settingPath) {
			var span = $('<span />');
			span.addClass(this.structureItemAttributeNameClass).attr('data-setting-path', settingPath).text('Nume atribut').append(this.renderDeleteLink('deleteAttributName'));
			return span;
		},

		renderAttributeValue: function(settingPath) {
			var span = $('<span />');
			span.addClass(this.structureItemAttributeValueClass).attr('data-setting-path', settingPath).text('Valoare atribut').append(this.renderDeleteLink('deleteAttributValue'));
			return span;
		},

		renderImage: function(settingPath) {
			var span = $('<span />');
			span.addClass(this.structureItemImageClass).attr('data-setting-path', settingPath).text('Imagine').append(this.renderDeleteLink('deleteImage'));
			return span;
		},

		renderIgnore: function(settingPath) {
			var span = $('<span />');
			span.addClass(this.structureItemIgnoreClass).attr('data-setting-path', settingPath).text('Ignora').append(this.renderDeleteLink('deleteIgnore'));
			return span;
		},

		renderCategory: function(settingPath) {
			var span = $('<span />');
			span.addClass(this.structureItemCategoryClass).attr('data-setting-path', settingPath).text('Categorie').append(this.renderDeleteLink('deleteCategory'));

			if (typeof this.menuItems.menuCategory == 'object') {
				this.usedFields.menuCategory = this.menuItems.menuCategory;
				delete this.menuItems.menuCategory;
			}
			return span;
		},

		renderCommonField: function(settingPath) {
			var span = $('<span />');
			span.addClass(this.structureItemCommonFieldClass).attr('data-setting-path', settingPath).text('Camp comun').append(this.renderDeleteLink('deleteCommonField'));

			if (typeof this.menuItems.menuCommunField == 'object') {
				this.usedFields.menuCommunField = this.menuItems.menuCommunField;
				delete this.menuItems.menuCommunField;
			}
			return span;
		},

		renderProduct: function(settingPath) {
			var span = $('<span />');
			span.addClass(this.structureItemProductClass).attr('data-setting-path', settingPath).text('Produs').append(this.renderDeleteLink('deleteProduct'));

			if (typeof this.menuItems.menuProduct == 'object') {
				this.usedFields.menuProduct = this.menuItems.menuProduct;
				delete this.menuItems.menuProduct;
			}
			return span;
		},

		rendreExplodes: function(json, basePath, settingPath, value) {
			var explodesUL = $('<ul />');
			var headerLI = $('<li />');
			var value = value.replace(' = ', '');
			var explodeArr = value.split(json.spliter);

			explodesUL.addClass(this.structureItemExplodesClass).attr({'data-path': basePath, 'data-setting-path': settingPath}).append(headerLI);
			headerLI.addClass(this.structureItemExplodeHeaderClass).text('Explde("'+((value.length > 30) ? value.substr(0, 20) : value)+'" la "'+json.spliter+'")').append(this.renderDeleteLink('deleteExplode'));

			for (var i in explodeArr) {
				var explodeLI = $('<li />');
				var keySpan = $('<span />');
				var valueSpan = $('<span />');

				explodeLI.addClass(this.structureItemExplodeClass).attr('data-index', i).append(keySpan).append(' = ').append(valueSpan);
				keySpan.addClass(this.structureItemExplodeKeyClass).text(i);
				valueSpan.addClass(this.structureItemExplodeValueClass).text(explodeArr[i]);
				explodesUL.append(explodeLI);

				if (typeof json.all == 'object' && typeof json.items['index_'+i] == 'undefined') {
					json.items['index_'+i] = json.all;
				}

				if (typeof json.items['index_'+i] == 'object') {
					this.runRenders(json.items['index_'+i], valueSpan, settingPath+'/items/index_'+i, basePath);
				}
			}
			return explodesUL;
		},

		renderSettings: function() {
			// console.log('renderSettings');
			var settings = this.dbStructure().settings;
			var t = this;

			for (var i in settings) { // item_0 > path / items
				for (var x in settings[i].items) { // product_name
					for (var y in settings[i].items[x]) { // text / value /attribute
						// console.log('settings', settings, 'x', x);
						this.aplyHTMLToPath(settings[i].path+'/'+x, function(e) {
							var path = t.getPath(e);
							var json = t.goToJsonPath(path);
							var itemClass = t.getClass(y);
							var el;
							// console.log(el);

							if (y == 'attributeXMLValue') {
								for (var attributeXMLName in settings[i].items[x][y]) {
									el = e.find('.'+itemClass+'[data-name="'+attributeXMLName+'"]');
									t.runRenders(settings[i].items[x][y][attributeXMLName], el, settings[i].path+'/'+x+'|!'+y+'/'+attributeXMLName, path);
								}
							} else {
								el = (itemClass == t.structureItemLabelClass) ? e.children('.'+t.structureItemHeaderClass).children('.'+t.structureItemLabelClass) : e.find('.'+itemClass);
								t.runRenders(settings[i].items[x][y], el, settings[i].path+'/'+x+'|!'+y, path);
							}
						});
					}
				}

				if (typeof settings[i].this == 'object') {
					for (var x in settings[i].this) { // text / value /attribute
						// console.log('settings', settings, 'x', x);
						this.aplyHTMLToPath(settings[i].path, function(e) {
							var path = t.getPath(e);
							var json = t.goToJsonPath(path);
							var itemClass = t.getClass(x);
							var el;

							if (x == 'attributeXMLValue') {
								for (var attributeXMLName in settings[i].this[x]) {
									el = e.find('.'+itemClass+'[data-name="'+attributeXMLName+'"]');
									t.runRenders(settings[i].this[x][attributeXMLName], el, settings[i].path+'|!'+x+'/'+attributeXMLName, path);
								}
							} else {
								el = (itemClass == t.structureItemLabelClass) ? e.children('.'+t.structureItemHeaderClass).children('.'+t.structureItemLabelClass) : e.find('.'+itemClass);
								t.runRenders(settings[i].this[x], el, settings[i].path+'|!this/!'+x, path);
							}
							// console.log(el);
						});
					}
				}
			}
		},

		renderDeleteLink: function(func) {
			var link = $('<a />');

			link.addClass(this.structureItemDeleteLinkClass).attr({href: '#', 'data-function': func}).text('X');
			return link;
		},

		runRenders: function(setting, el, path, thisPath) {
			if (typeof setting.field != 'undefined') {
				el.append(this.renderField(setting.field, path));
			}

			if (typeof setting.attribute != 'undefined') {
				if (setting.attribute == 'name') {
					el.append(this.renderAttributeName(path));
				} else {
					el.append(this.renderAttributeValue(path));
				}
			}

			if (typeof setting.image != 'undefined') {
				el.append(this.renderImage(path));
			}

			if (typeof setting.category != 'undefined') {
				el.append(this.renderCategory(path));
			}

			if (typeof setting.commonField != 'undefined') {
				el.append(this.renderCommonField(path));
			}

			if (typeof setting.productLink != 'undefined') {
				el.append(this.renderProductLink(path));
			}

			if (typeof setting.product != 'undefined') {
				el.append(this.renderProduct(path));
			}

			if (typeof setting.explode == 'object') {
				el.append(this.rendreExplodes(setting.explode, thisPath, path+'/!explode', el.text()));
			}

			if (typeof setting.ignore != 'undefined' && setting.ignore) {
				el.append(this.renderIgnore(path));
				this.aplyIgnoreToItems(el);
			}
		},

		aplyIgnoreToItems: function(e) {
			var el = e.find('.'+this.structureItemFieldClass+
				', .'+this.structureItemAttributeNameClass+
				', .'+this.structureItemAttributeValueClass+
				', .'+this.structureItemImageClass+
				', .'+this.structureItemCategoryClass+
				', .'+this.structureItemExplodesClass+
				', .'+this.structureItemProductLinkClass+
				', .'+this.structureItemProductClass);
			el.addClass(this.structureItemIgnoreItemsClass);
		},

		aplyHTMLToPath: function(path, callbeck, elements, i) {
			var pathArr = path.split('/');
			var structure = ((typeof elements != 'undefined') ? elements :
				this.feedStructure).children('.'+this.structureItemsClass).children('.'+this.structureItemClass);
			structure = (this.data().feedType == 'Explode' && typeof elements == 'undefined') ? structure.children('.'+this.structureItemsClass).children('.'+this.structureItemClass) : structure;
			var i = (typeof i != 'undefined') ? i+1 : 0;
			var regex = /\:\([0-9]+\)/g;
			var t = this;

			if (regex.test(pathArr[i])) {
				var thisElementArr = pathArr[i].split(':(');
				var elementIndex = thisElementArr[1].replace(')', '');
			}

			structure.each(function(x, e) {
				var el = $(e);
				var p = el.attr('data-structure-path').split('/');

				if ((pathArr[i] == 'all' || pathArr[i] == p[i]) || (typeof elementIndex != 'undefined' && thisElementArr[0] == p[i] && elementIndex == x)) {
					if (i < pathArr.length - 1 && pathArr[i] != 'explode') {
						t.aplyHTMLToPath(path, callbeck, el, i);
					} else {
						callbeck(el);
					}
				}
			});
		},

		goToJsonPath: function(path) {
			var json = this.structure();
			var pathArr = path.split('/');

			for (var i in pathArr) {
				json = json[pathArr[i]];

				if (typeof json.children == 'object' && pathArr.length - 1 != i) {
					json = json.children;
				}
			}
			return json;
		},

		setStructure: function(path, key, json) {
			var path = path.replace('/', '.children.');
			eval('this.json().structure.'+path+'.'+key+' = json;');
		},

		setDbStructure: function(path, json) {
			var path = path.replace('/', '.children.');
			eval('this.dbStructure().'+path+' = json;');
			this.saveJSON();
		},

		setRepeat: function(path, key, json, repeater) {
			var repeat = (typeof repeater == 'object') ? repeater : this.dbStructure().repeat;
			var pathArr = (typeof path == 'object') ? path : path.split('/');
			var pathSegment = pathArr.shift();
			var index = 'item_'+Object.keys(repeat).length;

			if ((typeof json.text != 'undefined' && typeof json.text.ignore != 'undefined') || (typeof json.value != 'undefined' && typeof json.value.ignore != 'undefined')) {
				var tempRepeat = repeat;
				var ignoreIndex = 0;
				repeat = {};

				for (var i in tempRepeat) {
					if (i.indexOf('_ignore_items_') == 0 && index.indexOf('_ignore_items_') == -1) {
						if (tempRepeat[i].path == pathSegment) {
							index = i;
						} else {
							ignoreIndex++;
						}
					} else {
						index = '_ignore_items_'+ignoreIndex;
						repeat[index] = {path: pathSegment, items: {}};
					}
					repeat[i] = tempRepeat[i];
				}
			} else {
				for (var i in repeat) {
					if (repeat[i].path == pathSegment && i.indexOf('_ignore_items_') == -1) {
						index = i;
						break;
					}
				}
			}

			repeat[index] = (typeof repeat[index] == 'object') ? repeat[index] : {path: pathSegment, items: {}};

			if (pathArr.length > 0) {
				if (typeof repeat[index].children == 'object') {
					repeat[index].children = this.setRepeat(pathArr.join('/'), key, json, repeat[index].children);
				} else {
					repeat[index].children = this.setRepeat(pathArr.join('/'), key, json, {});
				}
			} else {
				if (typeof repeater == 'undefined' && Object.keys(json).indexOf(key) > -1) {
					repeat[index].this = (typeof repeat[index].this == 'object') ? repeat[index].this : {};
					repeat[index].this[key] = this.cleanRepeat(json[key]);
				} else if (typeof repeat[index].items[key] == 'undefined') {
					repeat[index].items[key] = this.cleanRepeat(json);
				} else {
					var jsonKey = Object.keys(repeat[index].items[key])[0];
					repeat[index].items[key] = this.cleanRepeat(json);
					// repeat[index].items[key][jsonKey] = json[jsonKey];
					// repeat[index].items[key][jsonKey] = this.cleanRepeat(json[jsonKey]);
					// console.log('repeat[index].items[key]', repeat[index].items[key]);
				}
			}

			if (typeof repeater == 'object') {
				return repeat;
			}

			this.dbStructure().repeat = repeat;
			this.saveJSON();
		},

		cleanRepeat: function (json) {
			var cleanJson = {};

			for (var i in json) {
				if (typeof json[i] == 'object') {
					cleanJson[i] = this.cleanRepeat(json[i]);

					if (Object.keys(((i == 'explode' || i == 'children') && typeof cleanJson[i].items == 'object') ? cleanJson[i].items : cleanJson[i]).length == 0 ||
						(i == 'explode' && typeof cleanJson[i].items == 'undefined') || (typeof cleanJson[i].path == 'string' && Object.keys(cleanJson[i]).length == 1)) {
						delete cleanJson[i];
					}
				} else if ((typeof json[i] != 'undefined' && json[i].length > 0) || (typeof json[i] == 'boolean')) {
					cleanJson[i] = json[i];
				}
			}
			return cleanJson;
		},

		setSetting: function(path, key, json) {
			var settings = this.dbStructure().settings;
			var index = 'item_'+Object.keys(settings).length;

			for (var i in settings) {
				if (settings[i].path == path) {
					index = i;
				}
			}

			this.dbStructure().settings[index] = (typeof this.dbStructure().settings[index] == 'object') ?
													this.dbStructure().settings[index] : {path: path, items: {}};
			if (path.indexOf('/') == -1 && Object.keys(json).indexOf(key) > -1) {
				this.dbStructure().settings[index].this = json;
			} else {
				this.dbStructure().settings[index].items[key] = json;
			}

			this.saveJSON();
		},

		getSettings: function(path, key, options) {
			var settings = this.dbStructure().settings;

			for (var i in settings) {
				if (settings[i].path == path &&
					   ((typeof options == 'object' &&
						typeof options.ignore == 'object' &&
						i.indexOf('_ignore_items_') != -1) ||
					typeof options == 'undefined' ||
					(typeof options.ignore == 'undefined' && i.indexOf('_ignore_items_') == -1))) {
					if (path.indexOf('/') == -1 && typeof key == 'undefined') {
						settings[i].this = (typeof settings[i].this == 'undefined') ? {} : settings[i].this;
						return settings[i].this;
					} else if (typeof settings[i].items != 'undefined' && typeof settings[i].items[key] != 'undefined') {
						return settings[i].items[key];
					}
				}
			}

			return {};
		},

		getPath: function(self) {
			if (typeof self.attr('data-path') == 'undefined') {
				while (true) {
					self = self.parent();

					if (typeof self.attr('data-path') != 'undefined') {
						return self.attr('data-path');
						break;
					}
				}
			} else {
				return self.attr('data-path');
			}
		},

		askForPath: function(path, itemPath, callbeck) {
			this.popup.pharagraf.text('Alege calea dorita.');
			this.popup.body.html('');

			if (path.indexOf('|') != -1) {
				var pArr = path.split('|');
				path = pArr[1];
				this.popup.body.append(selectSegment).append('<span style="font-weight: bold;">'+pArr[0]+'<span>');
			}

			var pathArr = path.split('/');
			var itemPathArr = itemPath.split('/');
			var t = this;
			var newPath = '';

			for (var i in pathArr) {
				if (pathArr[i].substr(0, 1) == '!') {
					var selectSegment = $('<input />');

					selectSegment.addClass(this.selectPathSegmentClass).attr('type', 'hidden').val(pathArr[i]);
					this.popup.body.append(selectSegment);
					continue;
				}

				var selectSegment = $('<select />');
				var selectOption1 = $('<option />');
				var selectOption2 = $('<option />');
				var selectOption3 = $('<option />');

				selectSegment.addClass(this.selectPathSegmentClass).append(selectOption1).append(selectOption2);
				selectOption1.attr('value', pathArr[i]).text(pathArr[i].replace('index_', ''));

				if (typeof itemPathArr[i] != 'undefined') {
					selectOption3.attr('value', pathArr[i]+':('+itemPathArr[i].replace('item_', '')+')').text('Doar asta');
					selectOption1.after(selectOption3);
				}
				selectOption2.attr('value', 'all').text('Toate');

				this.popup.body.append(selectSegment).append('<span> / <span>');
			}

			this.popup.body.children('span').last().remove();

			this.popup.okButton.off('click');
			this.popup.cancelButton.off('click');
			this.popup.okButton.on('click', function(e) {
				$('.'+t.selectPathSegmentClass).each(function(i, ele) {
					var select = $(ele);

					newPath += ((newPath.length > 0) ? '/' : '') + select.val();
				});

				callbeck(newPath);
				t.popup.self.remove();
			});

			this.popup.cancelButton.on('click', function(e) {
				t.popup.self.remove();
			});
			docBody.append(this.popup.self);
		},

		dataExists: function() {
			return $.isEmptyObject(this.data());
		},

		deleteExplode: function(e) {
			if (!confirm('Esti sigur ca vrei sa stergi acest explode?')) {
				return false;
			}

			var thisUL = e.parent().parent();

			thisUL.find('.'+this.structureItemFieldClass+' > .'+this.structureItemDeleteLinkClass).addClass('dontAsk').click();
			this.deleteItem(false, thisUL);
		},

		deleteField: function (e) {
			var parent = e.parent();

			if (!e.hasClass('dontAsk') && !confirm('Esti sigur ca vrei sa stergi campul "'+parent.text().replace('Camp: ', '')+'"?')) {
				return false;
			}

			this.deleteItem('field', parent);

			var field = parent.attr('data-field');
			this.menuItems.menuFields.children[field] = this.usedFields[field];
			delete this.usedFields[field];
		},

		deleteAttributName: function (e) {
			var parent = e.parent();

			if (!e.hasClass('dontAsk') && !confirm('Esti sigur ca vrei sa stergi denumirea atributului?')) {
				return false;
			}

			this.deleteItem('attribute', parent);
		},

		deleteAttributValue: function (e) {
			var parent = e.parent();

			if (!e.hasClass('dontAsk') && !confirm('Esti sigur ca vrei sa stergi valoarea atributului?')) {
				return false;
			}

			this.deleteItem('attribute', parent);
		},

		deleteImage: function (e) {
			var parent = e.parent();

			if (!e.hasClass('dontAsk') && !confirm('Esti sigur ca vrei sa stergi aceasta imagine?')) {
				return false;
			}

			this.deleteItem('image', parent);
		},

		deleteIgnore: function (e) {
			var parent = e.parent();

			if (!e.hasClass('dontAsk') && !confirm('Esti sigur ca vrei sa stergi ignorarea acestor elemente?')) {
				return false;
			}

			this.deleteItem('ignore', parent);
		},

		deleteCategory: function (e) {
			var parent = e.parent();

			if (!e.hasClass('dontAsk') && !confirm('Esti sigur ca vrei sa stergi categoria produsului?')) {
				return false;
			}

			this.deleteItem('category', parent);
			this.menuItems.menuCategory = this.usedFields.menuCategory;
			delete this.usedFields.menuCategory;
		},

		deleteCommonField: function (e) {
			var parent = e.parent();

			if (!e.hasClass('dontAsk') && !confirm('Esti sigur ca vrei sa stergi campul comun?')) {
				return false;
			}

			this.deleteItem('commonField', parent);
			this.menuItems.menuCommunField = this.usedFields.menuCommunField;
			delete this.usedFields.menuCommunField;
		},

		deleteProductLink: function (e) {
			var parent = e.parent();

			if (!e.hasClass('dontAsk') && !confirm('Esti sigur ca vrei sa stergi link-ul produsului?')) {
				return false;
			}
			this.deleteItem('productLink', parent);
			this.menuItems.menuProductLink = this.usedFields.menuProductLink;
			delete this.usedFields.menuProductLink;
		},

		deleteProduct: function (e) {
			var parent = e.parent();

			if (!e.hasClass('dontAsk') && !confirm('Esti sigur ca vrei sa stergi produsul?')) {
				return false;
			}

			this.deleteItem('product', parent);
			this.menuItems.menuProduct = this.usedFields.menuProduct;
			delete this.usedFields.menuProduct;
		},

		deleteItem: function (type, parent) {
			var path = parent.attr('data-setting-path');
			var thisPath = this.getPath(parent);
			var json = this.goToJsonPath(thisPath);
			var item = this.getItem(json, parent.parent());
			console.log('item', item);
			var pArr = path.split('|');
			var pathArr = pArr[0].split('/');
			var settingPath = ((pathArr.length == 1) ? pArr[1].replace('this/', '') : pArr[1]).replace(/\!|\//g, '')+'.'+type;
			var field = (pathArr.length == 1) ? item.key : pathArr.pop();
			var basePath = pathArr.join('/');
			console.log('basePath', basePath)
			console.log('pArr', pArr)
			var settings = (pArr[1].indexOf('!this/') > -1) ? this.getSettings(basePath) : this.getSettings(basePath, field);
			console.log(settings);
			var t = this;

			if (type) {
				this.deleteRepeat(path, type);
			}

			console.log('delete settings.'+settingPath+';');
			eval('delete settings.'+settingPath+';');
			this.setSetting(basePath, field, settings);

			this.aplyHTMLToPath((pathArr.length == 1) ? basePath : basePath+'/'+field, function(e) {
				e.find('.'+parent.attr('class').split(' ')[0]+'[data-setting-path="'+path+'"]').remove();
			});
		},

		deleteRepeat: function (path, key, repeater) {
			var repeat = (typeof repeater == 'object') ? repeater : this.dbStructure().repeat;
			var path = path.split('|');
			var pathArr = path[0].split('/');
			var pathSegment = pathArr.shift();
			// console.log('repeat', repeat);
			// console.log('path', path);
			// console.log('pathArr', pathArr);
			// console.log('pathSegment', pathSegment);
			// console.log('key', key);

			for (var i in repeat) {
				if (repeat[i].path == pathSegment) {
					if (pathArr.length > 1) {
						repeat[i].children = this.deleteRepeat(pathArr.join('/')+'|'+path[1], key, repeat[i].children);
					} else {
						if (path[1].indexOf('/') > -1 && typeof repeater == 'object') {
							eval('delete repeat[i].items[pathArr[0]].'+path[1].replace(/\//g, '.').replace(/\!/g, '')+'.'+key+';');
						} else {
							var thisPath = path[1].replace(/\!/g, '').split('/');

							if (typeof repeater == 'undefined' && thisPath[0] == 'this') {
								delete repeat[i].this[thisPath[1]][key];
							} else {
								delete repeat[i].items[pathArr[0]][path[1].replace('!', '')][key];
							}
						}
					}
					break;
				}
			}

			if (typeof repeater == 'object') {
				return repeat;
			}

			this.dbStructure().repeat = this.cleanRepeat(repeat);
			this.saveJSON();
		}
};

	feeds.saveFeedBtn.on('click', function(e) {
		feeds.save(e);
	});

	feeds.cancelFeedBtn.on('click', function(e) {
		if (!feeds.dataExists() && !confirm('Esti sigur ca vrei sa anulezi editarea feed-ului curent?')) {
			return false;
		}
		feeds.cancelEdit();
	});

	feeds.feedTestConnection.on('click', function(e) {
		feeds.testConnection(e);
	});

	feeds.inputData.on('change', function(e) {
		feeds.pushDataToJSON(e);
	});

	feeds.feedLoginType.on('change', function(e) {
		feeds.changeLoginType(e);
	});

	feeds.feedSuppliersID.on('change', function(e) {
		feeds.changeSupplierID(e);
	});

	feeds.feedType.on('change', function(e) {
		feeds.changeType($(this).val());
		feeds.feedStructure.html('');
		feeds.renderHTML(feeds.feedStructure, feeds.structure());
	});

	docBody.on('click', '.'+feeds.deleteLinkClass, function(e) {
		feeds.deleteFeed(e);
	});

	docBody.on('click', '.'+feeds.structureItemLabelClass+
					  ', .'+feeds.structureItemValueClass+
					  // ', .'+feeds.structureItemXMLAttributeNameClass+

					  ', .'+feeds.structureItemExplodeKeyClass+
					  ', .'+feeds.structureItemExplodeValueClass+
					  ', .'+feeds.structureItemXMLAttributeValueClass, function(e) {
		feeds.showMenu(e);
	});

	docBody.on('click', function(e) {
		var classes = [feeds.feedStructureMenuItemClass,
					   feeds.structureItemLabelClass,
						feeds.structureItemValueClass,
						// feeds.structureItemXMLAttributeNameClass,
						feeds.structureItemXMLAttributeValueClass,
						feeds.structureItemExplodeKeyClass,
						feeds.structureItemExplodeValueClass];
		if (classes.indexOf($(e.target).attr('class')) == -1) {
			feeds.closeMenu();
		}
	});

	/*docBody.on('click', '.'+feeds.structureItemHeaderClass, function(e) {
		feeds.showMenu(e);
	});*/

	docBody.on('click', '.'+feeds.editLinkClass, function(e) {
		feeds.getForEdit(e);
	});

	docBody.on('click', '.'+feeds.structureItemDeleteLinkClass, function(e) {
		e.preventDefault();
		var self = $(e.target);
		feeds[self.attr('data-function')](self);
	});

	feeds.init();
});