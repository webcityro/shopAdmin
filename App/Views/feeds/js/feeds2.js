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
		structureItemAttributesClass: 'structureItemAttributes',
		structureItemAttributeClass: 'structureItemAttribute',
		structureItemAttributeNameClass: 'structureItemAttributeName',
		structureItemAttributeValueClass: 'structureItemAttributeValue',
		structureItemExplodesClass: 'structureItemExplodes',
		structureItemExplodeClass: 'structureItemExplode',
		structureItemExplodeKeyClass: 'structureIteExplodeKey',
		structureItemExplodeValueClass: 'structureIteExplodeValue',
		structureItemFieldClass: 'structureIteField',
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
		menuItems: {
			menuExplode: {label: 'Explode', callbeck: 'explodeField'},
			menuFields: {
				label: 'Campuri',
				children: {
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
					currency: {label: 'Valuta', callbeck: 'setField|currency'},
					weight: {label: 'Greurate', callbeck: 'setField|weight'},
					weight_class: {label: 'Unitate masura Greurate', callbeck: 'setField|weight_class'},
					width: {label: 'Latime', callbeck: 'setField|width'},
					length: {label: 'Lungime', callbeck: 'setField|length'},
					length_class: {label: 'Unitate masura Lungime', callbeck: 'setField|length_class'},
					height: {label: 'Inaltime', callbeck: 'setField|height'},
					minimum: {label: 'Cantitate minimum', callbeck: 'setField|minimum'}
				}
			}
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
			// this.structureRaw = testJSON;
			if (typeof localStorage[this.jsonKey] != 'undefined') {
				// console.log('localStorage[this.jsonKey]', localStorage[this.jsonKey]);
				this.jsonObj = JSON.parse(localStorage[this.jsonKey]);
				this.id = this.json().id;
				// this.json().structureRaw = testJSON;
			// console.log('this.dbStructure()', this.dbStructure());
				this.populateData();
				this.renderHTML(this.feedStructure);
			} else {
				this[this.jsonKey] = this.jsonDefault;
				this.json().id = this.id;
			}

			if (this.id != 0) {
				this.feedFormTitle.text('Editeaza feed-ul "'+this.data().feedFormName+'"');
				this.saveFeedBtn.text('Salveaza');
			}

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
			// this.dbStructure().settings = {};
			// this.saveJSON();
			console.log('feedJSON', this.json());
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

					// if (typeof data.content !== 'undefined' || (data.row.type == 'Excel' && typeof data.file == 'string')) {
						t.json().structureFile = data.file;
						t.feedConnectionStatus.text('Conexiune reusita!');
						t.changeType(data.row.type, data.content);
					// }

					t.saveJSON();
					t.feedFormTitle.text('Editeaza feed-ul "'+t.data().feedFormName+'"');
					t.saveFeedBtn.text('Salveaza');

					console.log('data', data);
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
				console.log('parser return', json);
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

		renderHTML: function(parent, json, path) {
			var json = (json) ? json : this.structure();
			var path = (path) ? path : '';
			var ul = $('<ul />');
			var x = 0;

			// console.log('json', json);
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
				attrbs.addClass(this.structureItemAttributesClass);

				if (typeof json[e].attr == 'object') {
					for (var i in json[e].attr) {
						var attr = $('<span />');
						var attrName = $('<span />');
						var attrValue = $('<span />');


						attr.addClass(this.structureItemAttributeClass).attr({'data-index': i}).append(attrName).append(' = ').append(attrValue);
						attrName.addClass(this.structureItemAttributeNameClass).text(json[e].attr[i].name.value);
						attrValue.addClass(this.structureItemAttributeValueClass).text(json[e].attr[i].value.value);
						attrbs.append('[').append(attr).append(']');

						if (typeof json[e].attr[i].name.explode == 'object') {
							attrName.append(this.rendreExplodes(json[e].attr[i].name.explode, thisPath+'/attr/'+i+'/name'));
						}

						if (typeof json[e].attr[i].value.explode == 'object') {
							attrValue.append(this.rendreExplodes(json[e].attr[i].value.explode, thisPath+'/attr/'+i+'/value'));
						}

						if (typeof json[e].attr[i].name.field != 'undefined') {
							attrName.append(this.renderField(json[e].attr[i].name.field));
						}

						if (typeof json[e].attr[i].value.field != 'undefined') {
							attrValue.append(this.rendreExplodes(json[e].attr[i].value.field));
						}
					}
				}

				if (typeof json[e].text.value.explode == 'object') {
					label.append(this.rendreExplodes(json[e].text.value.explode, thisPath+'/text'));
				}

				if (typeof json[e].text.field != 'undefined') {
					label.append(this.renderField(json[e].text.field));
				}

				if (typeof json[e].value == 'object' && typeof json[e].value.explode == 'object') {
					value.append(this.rendreExplodes(json[e].value.explode, thisPath+'/value'));
				}

				if (typeof json[e].value == 'object' && typeof json[e].value.field != 'undefined') {
					value.append(this.renderField(json[e].value.field));
				}

				if (typeof json[e].children == 'object') {
					this.renderHTML(li, json[e].children, thisPath);
				} else {

				}
				ul.append(li);
			}
			parent.append(ul);
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
			/*e.preventDefault();
			if (!confirm('Esti sigur ca vrei sa stergi?')) {
				return false;
			}

			var self = $(e.target);
			var t = this;
			var thisTR = self.parent().parent();
			var id = thisTR.attr('data-id');

			if (!thisTR.hasClass('new')) {
				$.get(config.domain+'feeds/delete/'+id, function(data) {
					if (data.status == 'ok') {
						thisTR.remove();
					} else if (data.status == 'error') {
						alert(data.msg);
					}
				}, 'json');
			} else {
				thisTR.remove();
			}*/
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

			if (this.json().structureFile.length > 0) {
				$.get(config.domain+'feeds/deleteTempFile/'+this.json().structureFile, function(data) {
					if (data.status == 'ok') {

					} else if (data.status == 'error') {
						alert(data.msg);
					}
				}, 'json');
			}
			this.clearJSON();
		},

		changeLoginType: function (e) {
			var self = $(e.target);
			this.loginDetalies.children().addClass('hide');
			this.loginDetalies.children('.'+self.val().toLowerCase()).removeClass('hide');
		},

		showMenu: function(e, json) {
			var menu = (json) ? json : this.menuItems;
			var ul = $('<ul />');
			var t = this;

			for (var i in menu) {
				var li = $('<li />');

				li.addClass(this.feedStructureMenuItemClass).text(menu[i].label);

				if (typeof menu[i].callbeck != 'undefined') {
					li.attr('data-callbeck', menu[i].callbeck).on('click', function(el) {
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

				ul.attr('id', this.feedStructureMenuID).css({top: offset.top+'px', left: offset.left+self.width()+'px'});
				docBody.append(ul);
				this.menuTarget = self;
			}
		},

		closeMenu: function() {
			// this.menuTarget = '';
			$('#'+this.feedStructureMenuID).remove();
		},

		explodeField: function(e) {
			var spliter = prompt('Caracterele la care vrei sa fie spart textul.');

			if (spliter) {
				var path = this.getPath(this.menuTarget);
				var json = this.goToJsonPath(path);
				var item = this.getItem(json);
				var t = this;

				if (item.json.value.indexOf(spliter) > -1) {
					this.askForPath(json.structurePath, function(np) {
						var itemArr = item.json.value.split(spliter);
						var newPath = np.split('/');
						var field = newPath.pop();
						var dbStructureJsonPath = t.getPathForDBstructureFromJSON(path, newPath);
						var settings = t.getSettings(dbStructureJsonPath, item.key);
						// console.log('dbStructureJsonPath', dbStructureJsonPath);
						settings.explode = {spliter: spliter, items: {}};

						for (var i in itemArr) {
							settings.explode.items['index_'+i] = {key: i, value: {value: $.trim(itemArr[i])}};
						}

						t.setSetting(newPath, field, settings);

						t.aplyHTMLToPath(np, function(e) {
							console.log(e);
							if (t.menuTarget.hasClass(t.structureIteExplodeValue)) {
								e.find('span.'+t.structureItemExplodeClass+'[data-path="'+t.menuTarget.parent().attr('data-path')+'"] > span.'+t.structureItemExplodeValueClass+'[data-index="'+t.menuTarget.attr('data-index')+'"]').append(t.rendreExplodes(settings.explode, np));
							} else {
								e.find('.'+t.menuTarget.attr('class')).append(t.rendreExplodes(settings.explode, np));
							}
						});
					});
				} else {
					alert('nu s-au gasit caracterele specificate pentru spargerea textului!');
				}
			}
			this.closeMenu();
		},

		rendreExplodes: function(json, path) {
			var explodesSpan = $('<span />');

			explodesSpan.addClass(this.structureItemExplodesClass).attr('data-path', path+'/explode');

			for (var i in json.items) {
				var explodeSpan = $('<span />');
				var keySpan = $('<span />');
				var valueSpan = $('<span />');

				explodeSpan.addClass(this.structureItemExplodeClass).attr('data-index', i).append(keySpan).append(' = ').append(valueSpan);
				keySpan.addClass(this.structureItemExplodeKeyClass).text(json.items[i].key);
				valueSpan.addClass(this.structureItemExplodeValueClass).text(json.items[i].value.value);
				explodesSpan.append(explodeSpan);

				if (typeof json.items[i].value.explode == 'object') {
					valueSpan.append(this.rendreExplodes(json.items.value.explode, path+'/explode/'+i));
				}

				if (typeof json.items[i].value.field != 'undefined') {
					valueSpan.append(this.renderField(json.items.value.field));
				}
			}
			return explodesSpan;
		},

		getItem: function(json) {
			var itemClass = this.menuTarget.attr('class');
			var item = {};

			switch (itemClass) {
				case this.structureItemValueClass:
				item.json = json.value;
				item.key = 'value';
				break;
				case this.structureItemAttributeNameClass:
				item.json = json.attr[this.menuTarget.parent().attr('data-index')].name;
				item.key = 'name';
				break;
				case this.structureItemAttributeValueClass:
				item.json = json.attr[this.menuTarget.parent().attr('data-index')].value;
				item.key = 'value';
				break;
				case this.structureItemLabelClass:
				item.json = json.text;
				item.key = 'text';
				break;
			}
			return item;
		},

		setField: function(e, field) {
			var path = this.getPath(this.menuTarget);
			var json = this.goToJsonPath(path);
			var item = this.getItem(json);
			var t =  this;
			var key;
			// console.log('path', path);
			// console.log('field', field);
			console.log('json', json);

			this.askForPath(json.structurePath, function(np) {
				var newPath = np.split('/');

				delete newPath[newPath.length - 1];

				var dbStructureJsonPath = t.getPathForDBstructureFromJSON(path, newPath);
				newPath = newPath.join('/');
				item.json.field = field;

				if (typeof json.repeat == 'undefined') {
					var keys = Object.keys(t.dbStructure().repeat);
					key = 'repeat_'+keys.length;
					t.dbStructure().repeat[key] = {path: newPath, items: {}};
					json.repeat = key;
				} else {
					key = json.repeat;
				}
				t.dbStructure().repeat[key].items[field] = item.key;
				t.setStructure(path, item.key, item.json);
				t.saveJSON();
				t.menuTarget.append(t.renderField(field));
			});
			this.closeMenu();
		},

		getPathForDBstructureFromJSON: function(path, newPath) {
			var returnPath = [];
			path = path.split('/');

			for (var i in newPath) {
				returnPath[i] = (newPath[i] == 'all') ? 'all' : path[i];
			}
			return returnPath;
		},

		renderField: function(field) {
			var span = $('<span />');
			span.addClass(this.structureItemFieldClass).text('Camp: '+field);
			return span;
		},

		aplyHTMLToPath: function(path, callbeck, elements, i) {
			var pathArr = path.split('/');
			var structure = (typeof elements != 'undefined') ? elements.children('.'+this.structureItemsClass).children('.'+this.structureItemClass) : this.feedStructure.children('.'+this.structureItemsClass).children('.'+this.structureItemClass);
			var i = (typeof i != 'undefined') ? i+1 : 0;
			var t = this;
			console.log('i', i);
			console.log('path', path);

			structure.each(function(x, e) {
				var el = $(e);
				var p = el.attr('data-structure-path').split('/');

				if (pathArr[i] == 'all' || pathArr[i] == p[i]) {

					if (i < (pathArr.length - 1) && pathArr[i] != 'explode') {
						t.aplyHTMLToPath(path, callbeck, el, i);
					} else {
						console.log('children', el);
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

				if (typeof json.children == 'object') {
					json = json.children;
				}
			}
			return json;
		},

		setStructure: function(path, key, json) {
			// console.log('path0', path);
			var path = path.replace('/', '.children.');
			eval('this.json().structure.'+path+'.'+key+' = json;');
		},

		setDbStructure: function(path, json) {
			var path = path.replace('/', '.children.');
			eval('this.dbStructure().'+path+' = json;');
			this.saveJSON();
		},

		setSetting: function(path, key, json) {
			var settings = this.dbStructure().settings;
			// console.log(settings);
			var index = 'item_'+Object.keys(settings).length;

			for (var i in settings) {
				if (settings[i].path == path) {
					index = i;
				}
			}

			this.dbStructure().settings[index] = {path: path};
			this.dbStructure().settings[index][key] = json;
			this.saveJSON();
		},

		getSettings: function(path, key) {
			var settings = this.dbStructure().settings;

			for (var i in settings) {
				if (settings[i].path == path) {
					return settings[i];
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
			}
		},

		askForPath: function(path, callbeck) {
			this.popup.pharagraf.text('Alege calea dorita.');
			this.popup.body.html('');

			var pathArr = path.split('/');
			var t = this;
			var newPath = '';

			for (var i in pathArr) {
				/*if (i == 'children') {
					continue;
				}*/

				var selectSegment = $('<select />');
				var selectOption1 = $('<option />');
				var selectOption2 = $('<option />');

				selectSegment.addClass(this.selectPathSegmentClass).append(selectOption1).append(selectOption2);
				selectOption1.attr('value', pathArr[i]).text(pathArr[i]);
				selectOption2.attr('value', 'all').text('Toate');

				this.popup.body.append(selectSegment).append('<span> / <span>');
			}

			this.popup.body.children('span').last().remove();

			this.popup.okButton.off('click');
			this.popup.cancelButton.off('click');
			docBody.on('click', '.'+this.popupOkButtonClass, function(e) {
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
					  ', .'+feeds.structureItemAttributeNameClass+
					  ', .'+feeds.structureItemAttributeValueClass+
					  ', .'+feeds.structureItemExplodeKeyClass+
					  ', .'+feeds.structureItemExplodeValueClass, function(e) {
		feeds.showMenu(e);
	});

	docBody.on('click', function(e) {
		var classes = [feeds.feedStructureMenuItemClass,
					   feeds.structureItemLabelClass,
						feeds.structureItemValueClass,
						feeds.structureItemAttributeNameClass,
						feeds.structureItemAttributeValueClass,
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

	feeds.init();
});