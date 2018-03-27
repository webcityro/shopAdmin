$(function(e) {
	var addProductsFormID	   = $('#addProductsForm');
	var editProductFormID	   = $('#editProductForm');
	var productsFormTitlaID	   = $('#addProductsFormTitla');
	var imagesSearchFormID	   = $('#imagesSearchForm');
	var groupsDivID			   = $('#groups');
	var imagesId			   = '#images';
	var imagesDivID			   = $(imagesId);
	var productNameID		   = $('#prdName');
	var productModelID		   = $('#prdModel');
	var productMakerID		   = $('#prdMakerID');
	var productCodeID		   = $('#prdCode');
	var productDescID		   = $('#prdDesc');
	var productMetaTitleID	   = $('#prdMetaTitle');
	var productMetaDescID	   = $('#prdMetaDesc');
	var productMetaKeywordsID  = $('#prdMetaKeywords');
	var productStocID		   = $('#prdStoc');
	var productPriceID		   = $('#prdPrice');
	var productStatusID		   = $('#prdStatus');
	var productWidthID		   = $('#prdWidth');
	var productLenghtID		   = $('#prdLenght');
	var productLenghtClassID   = $('#prdLengthClassID');
	var productHeightID		   = $('#prdHeight');
	var productWeightID		   = $('#prdWeight');
	var productWeighClasstID   = $('#prdWeightClassID');
	var sablonRowID			   = $('#sablonRow');
	var sablonSelectID		   = $('#sablonSelect');
	var addGroupsBtnID		   = $('#addGroupsBtn');
	/*var closeImageFormBtnID	   = $('#closeBtn');
	var imagesSearchQueryID	   = $('#searchQuery');
	var imagesSearchBtnID	   = $('#searchBtn');*/
	var attributeStatesID	   = $('#attributeStates');
	/*var searchResualts		   = '#searchResualts';
	var imagesSearchResualtsID = $(searchResualts);*/
	var addImagesBtnId		   = '#addImagesBtn';
	var addImagesBtnID		   = $(addImagesBtnId);
	// var aplyImagesBtnID		   = $('#aplyImagesBtn');
	var sablonId			   = '#sablonID';
	var sablonIdID			   = $(sablonId);
	var saveBtnID			   = $('#savePrdBtn');
	var cancelBtnID			   = $('#cancelPrdBtn');
	var formPart			   = $('.formPart');
	var formGroup			   = $('.formGroup');
	var editProductClass   	   = '.editPrd';
	var editProduct		   	   = $(editProductClass);
	var deleteProduct		   = $('.deletePrd');
	var dataInput			   = $('.dataInput');

	var product = {
		action: 'insert',
		id: 0,
		name: '',
		errors: [],
		errorDivID: 'displayErrors',
		groupFieldSetClass: 'group',
		groupLegendClass: 'groupNameBar',
		groupIdClass: 'groupID',
		groupNameClass: 'groupName',
		groupMenuClass: 'groupMenu',
		moveUpBtnClass: 'moveUp',
		moveDownBtnClass: 'moveDown',
		editabileTextClass: 'text',
		saveOrCancelClass: 'saveOrCancel',
		saveLinkClass: 'saveLink',
		cancelLinkClass: 'candelLink',
		deleteAttrClass: 'deleteAttrBtn',
		addAttrClass: 'addAttrBtn',
		attrIDTDClass: 'attributeID',
		attrNameTDClass: 'attributeName',
		attrValueTDClass: 'attributeValue',
		attrUMTDClass: 'attributeUM',
		attrDescTDClass: 'attributeDesc',
		attrExtraInfoTDClass: 'attributeExraInfo',
		attrActionsTDClass: 'attributeActions',
		deleteGroupBtnClass: 'deleteGroupBtn',
		imgDivClass: 'image',
		displayImageDivID: 'displayImage',
		closeImageLinkID: 'closeImageLink',
		imageTagID: 'bigImg',
		imageLinksDivID: 'imageLinks',
		setImageProduseLinkID: 'setImageProduseLink',
		deleteImageLinkID: 'deleteImageLink',
		productImageClass: 'productImage',
		editClass: 'edit',
		groupPrefix: 'group_',
		attributePrefix: 'attribute_',
		imagePrefix: 'image_',
		oldGroupName: '',
		oldAttribute: '',
		/*newGroupsCount: 0,
		newAttributesCount: 0,
		newImagesCount: 0,*/
		jsonObj: {},
		sablons: {},
		imageSearch: '',
		imageViewer: '',

		init: function() {
			var t = this;
			this.imageSearch = new googleImageSearch({
				button: addImagesBtnId
			});
			this.imageSearch.setCallbeck(function(link, src) {
				var newImg = t.addImage(link, src);
				t.pushImageToJSON(newImg);
			});
			this.imageSearch.init();

			this.imageViewer = new imageViewer();
			this.imageViewer.init();

			if (typeof localStorage.json == 'undefined') {
				this.json().data = {};
				this.json().attributes = {};
				this.json().images = {};
				this.json().sablons = {};
				this.json().action = this.action;
				this.json().id = this.id;
				this.json().newAttributesCount = 0;
				this.json().newGroupsCount = 0;
				this.json().newImagesCount = 0;
			} else {
				this.jsonObj = JSON.parse(localStorage.json);
				this.id = this.json().id;
				this.action = this.json().action;
				this.sablons = this.json().sablons;
				this.sablonID = this.json().sablonID;

				if (this.action == 'update') {
					productsFormTitlaID.text('Editeaza prodrusul '+this.data().prdName.value);
					saveBtnID.text('Salveaza!');
					attributeStatesID.removeClass('hide');
				}

				if (typeof this.json().catID != 'undefined') {
					categories.preopen('#subCats-'+this.json().catID);
					this.changeCategory(this.json().catID);
				}

				if (typeof this.json().formPart != 'undefined') {
					$('.formPart[data-part="'+this.json().formPart+'"]').click();
				}

				this.populateData();
				this.populateAttributes();
				this.populateImages();
			}

			console.log(this.json());
		},
		populateData: function() {
			var data = this.data();

			for (var i in data) {
				if (data[i].type == 'textarea') {
					$('#'+i).text(data[i].value);
					continue;
				} else if (data[i].type == 'select') {
					$('#'+i).children().each(function(x, e) {
						var self = $(e);

						if (self.attr('value') == data[i].value) {
							self.attr('selected', true);
							return;
						}
					});
					continue;
				} else if (data[i].type == 'checkbox' || data[i].type == 'radio') {
					$('#'+i).attr('checked', data[i].value);
				} else if (data[i].type == 'text') {
					$('#'+i).val(data[i].value);
				}
			}
		},
		populateAttributes: function(attributes) {
			var attributes = (attributes) ? attributes : this.attributes();
			for (var e in attributes) {
				var group = attributes[e];
				var groupStyle = (typeof group.style != 'undefined') ? group.style : '';
				var tbody = this.addGroup(group.name, e.replace(this.groupPrefix, ''), group.sort, group.sablon, groupStyle);

				/*if (e.substr(0, this.groupPrefix.length+4) == this.groupPrefix+'new_') {
					this.newGroupsCount++;
				}*/

				for (var i in group.attributes) {
					var attribute = group.attributes[i];
					var attributeStyle = (typeof attribute.style != 'undefined') ? attribute.style : '';
					this.addAttribute(tbody, attribute.name, attribute.value, attribute.um, attribute.desc, attribute.extraInfo, i.replace(this.attributePrefix, ''), attribute.sort, attribute.sablon, attributeStyle);

					/*if (i.substr(0, this.attributePrefix.length) == this.attributePrefix+'new_') {
						this.newAttributesCount++;
					}*/
				}
			}
		},
		populateImages: function(images, productImage, imagesDIR, imagesThumbDIR) {
			var images = (images) ? images : this.images();
			var t = this;

			for (var i in images) {
				var img = images[i];
				var id = (imagesDIR) ? img.product_image_id : i.replace(this.imagePrefix, '');

				img.src = (imagesThumbDIR) ? imagesThumbDIR+img.image : img.src;
				img.link = (imagesDIR) ? imagesDIR+img.image : img.link;
				img.productImage = (productImage) ? ((img.image == productImage) ? 'true' : 'false') : img.productImage;

				var addedeImage = this.addImage(img.link, img.src, id, img.productImage);

				this.imageViewer.add({
					target: addedeImage,
					attr: {
						src: img.link,
						data: {
							id: id
						}
					},
					links: {
						productImage: {
							attr: {
								href: '#',
								'data-id': id,
								id: t.setImageProduseLinkID
							},
							text: 'Seteaza ca imagine de produs'
						},
						deleteImage: {
							attr: {
								href: '#',
								'data-id': id,
								id: t.deleteImageLinkID
							},
							text: 'Sterge'
						}
					}
				});
				if (imagesDIR) {
					this.pushImageToJSON(addedeImage);
				}

				/*if (id.substr(0, 4) == 'new_') {
					this.newImagesCount++;
				}*/
			}
		},
		populateSablons: function() {
			if (!$.isEmptyObject(this.sablons)) {
				var select = $('<select />');
				var option0 = $('<option />');
				select.attr({id: sablonId.replace('#', '')}).append(option0);
				option0.attr('value', '0').text('Alege un sablon');

				for (var e in this.sablons) {
					var option = $('<option />');
					option.attr({value: this.sablons[e].id, selected: ((this.sablonID && this.sablonID == this.sablons[e].id) ? true : false)}).text(this.sablons[e].name);
					select.append(option);
				}
				sablonSelectID.html(select);
				sablonIdID = select;
			} else {
				sablonSelectID.text('Nu exista sabloane in acceasta caractere!');
			}
		},
		populateForEditing: function(e) {
			var self = $(e.target);
			var t = this;
			this.id = self.attr('data-id');

			if (this.checkformHasData()) {
				if (!confirm('Editarea unui alt produs inseamna pierderea modificarilor facute produsului curent. Vrei sa incepi editarea produsului fara sa salvezi produsul curent?')) {
					return false;
				} else {
					this.cancelEdit();
				}
			}

			categories.preopen('#article-'+this.id);

			$.get(domain+'getForEdit/'+this.id, function(data) {
				// console.log('edit data', data);
				sablonIdID.val(data.sablonID);
				t.populateDataForEditing(data.data);
				t.populateAttributes(data.attributes);
				t.populateImages(data.images, data.data.image, data.imagesDIR, data.imagesThumbDIR);

				$('.'+t.groupNameClass).each(function(i, e) {
					e.target = e;
					t.pushGroupToJSON(e, true);
				});
				$('.'+t.attrNameTDClass).each(function(i, e) {
					e.target = e;
					t.pushAttributeToJSON(e, true);
				});

				$('.'+t.attrValueTDClass).each(function(i, e) {
					e.target = e;
					t.pushAttributeToJSON(e);
				});

				$('.'+t.attrUMTDClass).each(function(i, e) {
					e.target = e;
					t.pushAttributeToJSON(e);
				});
				t.action = 'update';
				t.json().action = t.action;
				t.json().id = t.id;
				t.json().sablonID = data.sablonID;
				t.json().oldSablonID = data.sablonID;
				t.json().oldCatID = t.catID;
				t.json().updates = {
					data: {},
					groups: {},
					attributes: {},
					images: {}
				};

				t.saveJSON();
				productsFormTitlaID.text('Editeaza prodrusul '+data.data.name);
				saveBtnID.text('Salveaza!');
				attributeStatesID.removeClass('hide');
			}, 'json');
		},
		populateDataForEditing: function(data) {
			productNameID.val(data.name).change();
			productModelID.val(data.model).change();
			productMakerID.val(data.manufacturer_id).change();
			productCodeID.val(data.upc).change();
			productDescID.val(data.description).change();
			productMetaTitleID.val(data.meta_title).change();
			productMetaDescID.val(data.meta_description).change();
			productMetaKeywordsID.val(data.meta_keyword).change();
			productStocID.val(data.quantity).change();
			productPriceID.val(data.price).change();
			productStatusID.attr('checked', data.status == 1).change();
			productWidthID.val(data.width).change();
			productLenghtID.val(data.length).change();
			productLenghtClassID.val(data.length_class_id).change();
			productHeightID.val(data.height).change();
			productWeightID.val(data.weight).change();
			productWeighClasstID.val(data.weight_class_id).change();
		},

		json: function() {
			return this.jsonObj;
		},
		saveJSON: function() {
			localStorage.json = JSON.stringify(this.jsonObj);
		},
		data: function() {
			return this.json().data;
		},
		attributes: function() {
			return this.json().attributes;
		},
		images: function() {
			return this.json().images;
		},

		pushDataToJSON: function(e) {
			var self = $(e.target);
			var type = self.attr('type');
			var tagName = self.prop('tagName').toLowerCase();
			var id = self.attr('id');

			this.data()[id] = {type: ((tagName == 'input') ? type : tagName), value: ((type == 'checkbox') ? self.is(':checked') : self.val())};
			this.update('data', id, 'update', this.data()[id].value);
			this.saveJSON();
		},
		pushGroupToJSON: function(e, editing) {
			var self = $(e.target);
			var thisFieldSet = self.parent().parent();
			var name = self.text();
			var style = thisFieldSet.attr('data-style');
			var sort = thisFieldSet.attr('data-sort');
			var sablon = thisFieldSet.attr('data-sablon');
			var id = thisFieldSet.attr('data-id');
			var index = this.groupPrefix+id;
			var t = this;
			var newID;
			var jsonData;

			if (name != this.oldGroupName) {
				if (sablon == 'false' && !editing) {
					if (this.checkGroupExistsInThisProduct(name)) {
						alert('Grupul '+name+' exista deja in accest produs!');
						return false;
					}

					this.checkGroupExists(name, function(data) {
						if (self.hasClass('new')) {
							newID = (data.status == 'false') ? 'new_'+t.json().newGroupsCount : data.id;
							t.json().newGroupsCount++;
							jsonData = {name: name, sort: sort, sablon: sablon};

							if (id != newID) {
								jsonData.oldID = id;
							}
							t.update('groups', newID, 'add', jsonData);
						} else {
							newID = (data.status == 'false') ? id : data.id;
							jsonData = {name: name, sort: sort, sablon: sablon};
							// console.log('jsonData', jsonData);
							if (id != newID) {
								jsonData.oldID = id;
							}
							t.update('groups', newID, 'update', jsonData);
						}

						var newSort = (data.status == 'false') ? sort : data.sort;
						index = t.groupPrefix+newID;

						t.attributes()[index] = {
							name: name,
							sort: sort,
							sablon: sablon,
							// fieldset: thisFieldSet,
							attributes: (typeof t.attributes()[index] == 'undefined') ? {} : t.attributes()[index].attributes
						};


						t.saveJSON();
						thisFieldSet.attr({'data-sort': newSort, 'data-id': newID});
						self.removeClass('new').parent().prev().text((data.status == 'true') ? '#'+newID : '');
					});
				} else {
					this.attributes()[index] = {
						name: name,
						sort: sort,
						sablon: sablon,
						// fieldset: thisFieldSet,
						attributes: (typeof t.attributes()[index] == 'undefined') ? {} : t.attributes()[index].attributes
					};
					this.saveJSON();
					this.update('groups', id, 'add', {name: name, sort: sort, sablon: sablon});
				}

				if (typeof style != 'undefined') {
					this.attributes()[index].style = style;
					this.saveJSON();
				}
			}
		},
		pushAttributeToJSON: function(e, editing) {
			var self = $(e.target);
			var value = self.text();

			if (this.oldAttribute != value) {
				var thisTR = self.parent();
				var id = thisTR.attr('data-id');
				var sort = thisTR.attr('data-sort');
				var sablon = thisTR.attr('data-sablon');
				var groupID = thisTR.parent().parent().parent().attr('data-id');
				var index = this.attributePrefix+id;
				var groupIndex = this.groupPrefix+groupID;
				var t = this;
				var dataJson;
				var newID;
				if (self.hasClass(this.attrNameTDClass)) {

					if (sablon == 'false' && !editing) {
						if (this.checkAttributeExistsInThisProduct(value)) {
							alert('Atributul '+value+' exista deja in accest produs!');
							return false;
						}

						this.checkAttributeExists(value, function(data) {
							if (data.status == 'true' && data.groupID != groupID) {
								alert('Atributul '+value+' apartine altui grup!');
								return false;
							}

							if (self.parent().hasClass('new')) {
								newID = (data.status == 'false') ? 'new_'+t.json().newAttributesCount : data.id;
								t.json().newAttributesCount++;
								sort = (data.status == 'false') ? sort : data.sort;
								index = t.attributePrefix+id;
								dataJson = {
									sort: sort,
									sablon: sablon,
									groupID: groupID,
									desc: thisTR.children('.'+t.attrDescTDClass).text(),
									extraInfo:thisTR.children('.'+t.attrExtraInfoTDClass).text(),
									name: value
								};
								t.attributes()[groupIndex].attributes[index] = dataJson;
								thisTR.attr({'data-id': newID, 'data-sort': sort});
								t.update('attributes', newID, 'add', {name: value, sort: sort, sablon: sablon, groupID: groupID});
							} else {
								if (data.status == 'false') {
									newID = 'new_'+t.json().newAttributesCount;
									t.json().newAttributesCount++;
								} else {
									newID = data.id;
								}
								t.update('attributes', newID, 'update', {oldID: id, groupID: groupID, name: value, sort: sort, sablon: sablon});
							}
							index = t.attributePrefix+newID;

							if (id != newID) {
								var oldIndex = t.attributePrefix+id;
								t.attributes()[groupIndex].attributes[index] = t.attributes()[groupIndex].attributes[oldIndex];
								delete t.attributes()[groupIndex].attributes[oldIndex];
							}
							t.attributes()[groupIndex].attributes[index].name = value;
							thisTR.attr({'data-id': newID});
							t.saveJSON();
						});
					} else {
						dataJson = {
							sort: sort,
							sablon: sablon,
							desc: thisTR.children('.'+this.attrDescTDClass).text(),
							extraInfo:thisTR.children('.'+this.attrExtraInfoTDClass).text(),
							name: value
						};
						this.attributes()[groupIndex].attributes[index] = dataJson;
						this.update('attributes', id, 'add', {name: value, sort: sort, sablon: sablon});
					}
				} else {
					if (self.parent().children('.'+this.attrNameTDClass).text() == '') {
						alert('trebuie sa completezi numele atributului innainte de a completa textul!');
					} else {
						var field = (self.hasClass(this.attrValueTDClass)) ? 'value' : 'um';
						this.attributes()[groupIndex].attributes[index][field] = value;
						var data = {};
						if (field == 'value') {
							data['um'] = thisTR.children('.'+this.attrUMTDClass).text();
						} else if (field == 'um') {
							data['value'] = thisTR.children('.'+this.attrValueTDClass).text();
						}
						data[field] = value;
						t.update('attributes', id, 'update', data);
					}
				}
				if (typeof thisTR.attr('data-style') != 'undefined') {
					this.attributes()[groupIndex].attributes[index].style = thisTR.attr('data-style');
				}
			}
			this.saveJSON();
			this.oldAttribute = '';
		},
		pushImageToJSON: function(img) {
			var id = img.attr('data-id');
			var index = this.imagePrefix+id;
			var src = img.attr('src');
			var link = img.attr('data-link');
			var productImage = img.attr('data-product-image');
			var bigImg = $('<img />');
			bigImg.attr('src', link);
			this.images()[index] = {src: src,
									link: link,
									bigImg: bigImg,
									productImage: productImage};
			this.saveJSON();
			this.update('images', id, 'add', {src: src, link: link, productImage: productImage});
		},

		addOrEdit: function() {
			var t = this;
			$('#'+this.errorDivID).remove();

			this.validateData();
			this.validateGroups();
			this.validateAttriutes();

			if (!this.errorsCheck()) {
				if (this.action == 'insert') {
					var newJson = {
						data: {},
						groups: {},
						images: {}
					};

					for (var i in this.data()) {
						newJson.data[i] = this.data()[i].value;
					}
					for (var i in this.attributes()) {
						newJson.groups[i.replace(this.groupPrefix, '')] = {
							name: this.attributes()[i].name,
							sort: this.attributes()[i].sort,
							attributes: {}
						};

						for (var x in this.attributes()[i].attributes) {
							newJson.groups[i.replace(this.groupPrefix, '')].attributes[x.replace(this.attributePrefix, '')] = {
								name: this.attributes()[i].attributes[x].name,
								sort: this.attributes()[i].attributes[x].sort,
								value: (this.attributes()[i].attributes[x].value.length > 0) ? this.attributes()[i].attributes[x].value : '-'
							};
						}
					}
					for (var i in this.images()){
						newJson.images[i.replace(this.imagePrefix, '')] = {
							thumb: this.images()[i].src,
							url: this.images()[i].link,
							productImage: this.images()[i].productImage
						}
					}

					$.post(domain+'add', {
						catID: this.json().catID,
						sablonID: this.json().sablonID,
						data: JSON.stringify(newJson.data),
						attributes: JSON.stringify(newJson.groups),
						images: JSON.stringify(newJson.images)
					}, function(data) {
						if (data.status == 'ok') {
							localStorage.removeItem('json');
							location.reload();
						} else if (data.status == 'error') {
							alert(data.msg);
						}
					}, 'json');
				} else if (this.action == 'update' && this.checkUpdatesHasBeemMade()) {
					$.post(domain+'update/'+this.id, {
						catID: (this.json().catID != this.json().oldCatID) ? this.json().catID : '',
						sablonID: (this.json().sablonID != this.json().oldSablonID) ? this.json().sablonID : '',
						updates: JSON.stringify(this.json().updates)
					}, function(data) {
						if (data.status == 'ok') {
							localStorage.removeItem('json');
							location.reload();
						} else if (data.status == 'error') {
							for (var i in data.msg) {
								t.setError(data.msg[i]);
							}
							t.displayErrors();
						}
					}, 'json');
				} else {
					console.log('no updates', this.checkUpdatesHasBeemMade());
				}
			} else {
				console.log('else');
				this.displayErrors();
			}
		},
		addGroup: function(name, id, sort, sablon, style) {
			if (sablon == 'true' || sablon == true) {
				var group = $('.'+this.groupFieldSetClass+'[data-id="'+id+'"]');

				if (group.length > 0) {
					group.attr('data-sablon', 'true').addClass('addedToJson'+((style) ? ' '+style : ''));
					group.children('.'+this.groupLegendClass).children('.'+this.groupIdClass).text('#'+id);
					group.children('.'+this.groupLegendClass).children('.'+this.groupNameClass).attr('contenteditable', false);;
					this.attributes()[this.groupPrefix+id].sablon = 'true';
					return group.children('table').children('tbody');
				}
			}
			var groupFieldSet = $('<fieldset />');
			var groupLegend = $('<legend />');
			var groupNameSpan = $('<span />');
			var addAttributeButton = $('<button />');
			var deleteGroupButton = $('<button />');
			var groupMenuDiv = $('<div />');
			var moveUpBtn = $('<div />');
			var moveDownBtn = $('<div />');
			var spanID = $('<span />');

			var attrTable = $('<table />');
			var attrThead = $('<thead />');
			var attrTbody = $('<tbody />');
			var attrTR = $('<tr />');

			var attrIdTH = $('<th />');
			var attrNameTH = $('<th />');
			var attrValueTH = $('<th />');
			var attrUMTH = $('<th />');
			var attrDescTH = $('<th />');
			var attrExtraInfoTH = $('<th />');
			var attrActionsTH = $('<th />');

			attrIdTH.text('ID');
			attrNameTH.text('Atribut');
			attrValueTH.text('Text');
			attrUMTH.text('UM');
			attrDescTH.text('Descriere');
			attrExtraInfoTH.text('Extra info');
			attrActionsTH.text('Actiuni');

			spanID.addClass(this.groupIdClass).text((id && id.substr(0, 4) != 'new_') ? '#'+id : '');

			attrTR.append(attrIdTH).append(attrNameTH).append(attrValueTH).append(attrUMTH).append(attrDescTH).append(attrExtraInfoTH).append(attrActionsTH);

			attrThead.append(attrTR);
			attrTbody.addClass();
			attrTable.append(attrThead).append(attrTbody);

			groupFieldSet.addClass(this.groupFieldSetClass+((style) ? ' '+style : '')).attr({'data-id': ((id) ? id : ''), 'data-sort': ((sort) ? sort : groupsDivID.children().length+1), 'data-sablon': ((sablon && sablon == 'true') ? 'true' : 'false')}).append(groupLegend).append(attrTable).append(groupMenuDiv);
			if (style) {
				groupFieldSet.attr('data-style', style);
			}
			groupMenuDiv.addClass(this.groupMenuClass).append(addAttributeButton).append(deleteGroupButton);
			groupLegend.addClass(this.groupLegendClass).append(spanID).append(groupNameSpan);
			groupNameSpan.addClass(this.groupNameClass+((id) ? '' : ' new')).attr('contenteditable', ((sablon && sablon == 'true') ? false : true)).text((name) ? name : 'Numele grupului...');
			addAttributeButton.addClass('button buttonSmall '+this.addAttrClass).text('Adauga un atribut');
			deleteGroupButton.addClass('button buttonSmall '+this.deleteGroupBtnClass).text('Sterge grupul');
			moveUpBtn.addClass(this.moveUpBtnClass).attr('data-type', 'groups');
			moveDownBtn.addClass(this.moveDownBtnClass).attr('data-type', 'groups');

			groupsDivID.append(groupFieldSet);
			return attrTbody;
		},
		addAttribute: function(parent, name, value, um, desc, extraInfo, id, sort, sablon, style) {
			if (sablon && sablon == 'true') {
				var t = this;
				var ret = false;
				parent.children().each(function(i, e) {
					var tr = $(e);

					if (style) {
						tr.addClass(style).attr('data-style', style);
					}

					if (tr.attr('data-id') == id) {
						tr.attr({'data-sort': sort, 'data-sablon': 'true'});
						tr.children('.'+t.attrIDTDClass).addClass('bold').text('#'+id);
						tr.children('.'+t.attrNameTDClass).removeClass('text').addClass('bold').attr('contenteditable', false);
						tr.children('.'+t.attrUMTDClass).removeClass('text').addClass('bold').attr('contenteditable', false);
						tr.children('.'+t.attrDescTDClass).text(desc);
						tr.children('.'+t.attrExtraInfoTDClass).text(extraInfo);
						ret = tr;
					}
				});
				if (ret) {
					return ret;
				}
			}
			var attrTR = $('<tr />');

			var attrIdTD = $('<td />');
			var attrNameTD = $('<td />');
			var attrValueTD = $('<td />');
			var attrUMTD = $('<td />');
			var attrDescTD = $('<td />');
			var attrExtraInfoTD = $('<td />');
			var attrActionTD = $('<td />');
			var attrDeleteLink = $('<a />');

			var id = (id) ? id : '';
			var sort = (sort) ? sort : parent.children().length+1;

			attrIdTD.addClass(this.attrIDTDClass+' bold').text((id && id.substr(0, 4) != 'new_') ? '#'+id : '');
			attrNameTD.addClass(this.attrNameTDClass+((!sablon || sablon == 'false') ? ' '+this.editabileTextClass : ' bold')).attr('contenteditable', ((sablon == true || sablon == 'true') ? false : true)).text((name) ? name : '');
			attrValueTD.addClass(this.attrValueTDClass+' '+this.editabileTextClass).attr('contenteditable', true).text((value) ? value : '');
			attrUMTD.addClass(this.attrUMTDClass+((!sablon || sablon == 'false') ? ' '+this.editabileTextClass : '')).attr('contenteditable', ((sablon == true || sablon == 'true') ? false : true)).text((um) ? um : '');
			attrDescTD.addClass(this.attrDescTDClass).text((desc) ? desc : '');
			attrExtraInfoTD.addClass(this.attrExtraInfoTDClass).text((extraInfo) ? extraInfo : '');
			attrActionTD.addClass(this.attrActionsTDClass).append(attrDeleteLink);

			attrDeleteLink.addClass(this.deleteAttrClass).attr('href', '#').text('Sterge');

			attrTR.addClass((id) ? '' : 'new').attr({'data-id': id, 'data-sort': sort, 'data-sablon': (sablon == 'true') ? 'true' : 'false'}).append(attrIdTD).append(attrNameTD).append(attrValueTD).append(attrUMTD).append(attrDescTD).append(attrExtraInfoTD).append(attrActionTD);

			if (style) {
				attrTR.addClass(style).attr('data-style', style);
			}
			parent.append(attrTR);
			return attrTR;
		},
		addImage: function(link, thumb, id, productImage) {
			var imgDiv = $('<div />');
			var img = $('<img />');
			this.json().newImagesCount = (id) ? this.json().newImagesCount : this.json().newImagesCount+1;
			id = (id) ? id : 'new_'+this.json().newImagesCount;

			imgDiv.addClass(this.imgDivClass+((productImage == 'true' || productImage == true || productImage == 1) ? ' '+this.productImageClass : '')).attr('id', 'image-'+id).append(img);
			img.attr({src: thumb, 'data-link': link, 'data-id': id, 'data-product-image': ((productImage == 'true' || productImage == true || productImage == 1) ? 'true' : 'false')});

			imagesDivID.append(imgDiv);

			this.imageViewer.add({
				target: img,
				attr: {
					src: link,
					data: {
						id: id
					}
				},
				links: {
					productImage: {
						attr: {
							href: '#',
							'data-id': id,
							id: this.setImageProduseLinkID
						},
						text: 'Seteaza ca imagine de produs'
					},
					deleteImage: {
						attr: {
							href: '#',
							'data-id': id,
							id: this.deleteImageLinkID
						},
						text: 'Sterge'
					}
				}
			});
			return img;
		},

		update: function(type, id, action, value) {
			if (typeof this.json().updates == 'undefined') {
				return;
			}
			this.json().updates[type][id] = (typeof this.json().updates[type][id] == 'undefined') ? {} : this.json().updates[type][id];
			var action = (action == 'update' && typeof this.json().updates[type][id]['add'] == 'object') ? 'add' : action;

			if (action == 'delete' && id.substr(0, 4) == 'new_') {
				delete this.json().updates[type][id];
				this.saveJSON();
				return;
			} else if (action == 'delete' && id.substr(0, 4) != 'new_') {
				this.json().updates[type][id][action] = value;
				this.saveJSON();
				return;
			}
			if (typeof this.json().updates[type][id][action] == 'object') {
				for (var i in value) {
					this.json().updates[type][id][action][i] = value[i];
				}
			} else {
				this.json().updates[type][id][action] = value;
			}
			this.saveJSON();
		},

		getSablon: function(sablonID) {
			if (typeof this.json().sablonID != 'undefined') {
				this.removeSablon();
			}

			if (sablonID != '0') {
				var t = this;
				this.json().sablonID = sablonID;

				$.get(domain+'getSablonContents/'+sablonID, function(data) {
					// console.log('data', data);
					if (data.status == 'ok') {
						for (var i in data.rows) {
							var tbody = t.addGroup(data.rows[i].rows.name, data.rows[i].rows.groupID, data.rows[i].rows.sort_order, 'true');

							if (tbody.hasClass('addedToJson')) {
								tbody.removeClass('addedToJson');
							} else {
								var groupNameSpan = tbody.parent().prev().children('.'+t.groupNameClass);
								groupNameSpan.focus().blur();
							}

							for (var x in data.rows[i].attributes) {
								var attr = data.rows[i].attributes[x];
								if (attr) {
									var tr = t.addAttribute(tbody, attr.name, '', attr.um, attr.descriere, attr.info, attr.attributeID, attr.sort_order, 'true');
									tr.children('.'+t.attrNameTDClass).focus().blur();
								}
							}
						}
					} else if (data.status == 'error') {
						alert(data.msg);
					}
				}, 'json');
			} else {
				this.json().sablonID = undefined;
				this.removeSablon();
			}
			this.saveJSON();
		},
		removeSablon: function() {
			var t = this;

			$('.'+this.groupFieldSetClass+'[data-sablon="true"]').each(function(i, e) {
				var group = $(e);

				group.children('table').children('tbody').children().each(function(x, y) {
					var attr = $(y);

					if (attr.attr('data-sablon') == 'true') {
						attr.children('.'+t.attrActionsTDClass).children('.'+t.deleteAttrClass).addClass('delete').click();
					}
				});

				if (group.children('table').children('tbody').children().length == 0) {
					group.children('.'+t.groupMenuClass).children('.'+t.deleteGroupBtnClass).addClass('delete').click();
				} else {
					group.attr('data-sablon', 'false');
					group.children('.'+t.groupNameBar).children('.'+t.groupNameClass).attr('contenteditable', true);
				}

				t.json().sablonID = undefined;
			});
		},
		/*move: function(e) {
			var self = $(e.target);
			var type = self.data('type');
			var direction = self.attr('class').replace('move', '').toLowerCase();

			if (type == 'groups') {
				var thisFieldSet = self.parent().parent();
				var nrOfGroups = groupsDivID.children().length;
				var thisGroup = self.parent().parent();
				var sort = thisFieldSet.data('sort');
				var switchFieldSet = (direction == 'down') ? ((sort == nrOfGroups - 1) ? groupsDivID.children().first() : thisFieldSet.next()) :
				((sort == 0) ? groupsDivID.last() : thisFieldSet.prev());
				var newSort = (direction == 'down') ? ((sort == nrOfGroups - 1) ? 0 : sort+1) : ((sort == 0) ? nrOfGroups - 1 : sort-1);
				var count = nrOfGroups;
			} else {
				var thisFieldSet = self.parent().parent().parent();
				var thisGroup = thisFieldSet.parent();
				var nrOfCaracteristics = thisGroup.children().length;
				var sort = thisFieldSet.data('sort');
				var switchFieldSet = (direction == 'down') ?
				((sort == nrOfCaracteristics -1) ? thisGroup.children().first() : thisFieldSet.next()) :
				((sort == 0) ? thisGroup.children().last() : thisFieldSet.prev());
				var newSort = (direction == 'down') ? ((sort == nrOfCaracteristics - 1) ? 0 : sort+1) : ((sort == 0) ? nrOfCaracteristics - 1 : sort-1);
				var count = nrOfCaracteristics;
			}

			var id = thisFieldSet.data('id');
			var switchID = switchFieldSet.data('id');
			// console.log('self');
			// console.log('type', type);
			// console.log('thisGroup', thisGroup);
			// console.log('thisFieldSet', thisFieldSet);
			// console.log('id', id);
			console.log('sort', sort);
			// console.log('direction', direction);
			// console.log('nrOfGroups', nrOfGroups);
			console.log('newSort', newSort);
			// console.log('switchID', switchID);
			// console.log('switchFieldSet', switchFieldSet);
			$.get(domain+'/move/'+type+'/'+newSort+'/'+sort+'/'+id+'/'+switchID+'/'+count, function(data) {
				if (data.status == 'ok') {
					thisFieldSet.remove();
					thisFieldSet.attr('data-sort', newSort);
					switchFieldSet.attr('data-sort', sort);
					if (type == 'groups') {
						if (newSort == 0 && sort == nrOfGroups - 1) { // mut ultima innainte de prima
							for (var x = 1; x <= nrOfGroups; x++) {
								groupsDivID.children().eq(x-1).attr('data-sort', x);
							}
							groupsDivID.prepend(thisFieldSet);
						} else if (newSort == nrOfGroups - 1 && sort == 0) { // mut prima dupa ultima
							for (var x = 0; x < nrOfGroups; x++) {
								groupsDivID.children().eq(x).attr('data-sort', x);
							}
							groupsDivID.append(thisFieldSet);
						} else if (direction == 'up') {
							switchFieldSet.before(thisFieldSet);
						} else if (direction == 'down') {
							switchFieldSet.after(thisFieldSet);
						}
					} else {
						if (newSort == 0 && sort == nrOfCaracteristics - 1) { // mut ultima innainte de prima
							for (var x = 1; x <= nrOfCaracteristics; x++) {
								thisGroup.children().eq(x-1).attr('data-sort', x);
							}
							thisGroup.prepend(thisFieldSet);
						} else if (newSort == nrOfCaracteristics - 1 && sort == 0) { // mut prima dupa ultima
							for (var x = 0; x < nrOfCaracteristics; x++) {
								thisGroup.children().eq(x).attr('data-sort', x);
							}
							thisGroup.append(thisFieldSet);
						} else if (direction == 'up') {
							switchFieldSet.before(thisFieldSet);
						} else if (direction == 'down') {
							switchFieldSet.after(thisFieldSet);
						}
					}
				} else if (data.status == 'error') {
					alert(data.msg)
				}
			}, 'json');
		},*/
		deleteAttribute: function(e) {
			var self = $(e.target);
			var thisTR = self.parent().parent();
			var thisTbody = thisTR.parent();
			var id = thisTR.attr('data-id');
			var groupID = thisTR.parent().parent().parent().data('id');
			var groupIndex = this.groupPrefix+groupID;
			var index = this.attributePrefix+id;
			var sort = thisTR.data('sort');
			var label = thisTR.children('.'+this.attrNameTDClass).text();

			if (self.hasClass('delete') || confirm('Esti sigur ca vrei sa stergi atributu "'+label+'"?')) {
				delete this.attributes()[groupIndex].attributes[index];
				thisTR.remove();
				this.update('attributes', id, 'delete', true);
				this.saveJSON();
			}
		},
		deleteGroup: function(e) {
			var self = $(e.target);
			var thisFieldSet = self.parent().parent();
			var id = thisFieldSet.attr('data-id');
			var index = this.groupPrefix+id;
			var sort = thisFieldSet.data('sort');
			var label = thisFieldSet.children('.'+this.groupLegendClass).children('.'+this.groupNameClass).text();

			if (self.hasClass('delete') || confirm('Esti sigur ca vrei sa stergi grupul "'+label+'"?')) {
				delete this.attributes()[index];
				thisFieldSet.remove();
				this.update('groups', id, 'delete', true);
				this.saveJSON();
			}
		},
		deleteImage: function(e) {
			var self = $(e.target);
			var id = self.attr('data-id');
			var productImage = $('img[data-id="'+id+'"]').attr('data-product-image');
			var index = this.imagePrefix+id;

			if (self.hasClass('delete') || confirm('Esti sigur ca vrei sa stergi acceasta imagine?')) {
				delete this.images()[index];
				this.saveJSON();
				$('#image-'+id).remove();
				this.update('images', id, 'delete', {productImage: productImage});
				$('#'+product.closeImageLinkID).click();
			}
			this.imageViewer.remove(id);
		},
		delete: function(e) {
			var self = $(e.target);
			var thisLI = self.parent().parent();
			var id = self.data('id');
			var label = thisLI.children('span').text();

			if (confirm('Esti sigur ca vrei sa stergi produsul "'+label+'"?')) {
				$.get(domain+'delete/'+id, function(data) {
					if (data.status == 'ok') {
						thisLI.remove();
					} else if (data.status == 'error') {
						alert(data.msg);
					}
				}, 'json');
			}
		},

		/*showImage: function(e) {
			var self = $(e.target);
			var id = self.attr('data-id');
			var link = self.attr('data-link');
			var displayImageDiv = $('<div />');
			var imageLinksDiv = $('<div />');
			var closeImageLink = $('<a />');
			var deleteImageLink = $('<a />');
			var coverImageLink = $('<a />');
			var image = $('<img />');

			displayImageDiv.attr('id', this.displayImageDivID).append(closeImageLink).append(image).append(imageLinksDiv);
			closeImageLink.attr('id', this.closeImageLinkID).text('X');
			image.attr({id: this.imageTagID, src: link});
			imageLinksDiv.attr('id', this.imageLinksDivID).append(coverImageLink).append(' / ').append(deleteImageLink);
			coverImageLink.attr({href: '#', id: this.setImageProduseLinkID, 'data-id': id}).text('Stetaza ca imagine principala');
			deleteImageLink.attr({href: '#', id: this.deleteImageLinkID, 'data-id': id}).text('Sterge');

			imagesDivID.append(displayImageDiv);
		},*/
		setProduseImage: function(e) {
			var self = $(e.target);
			var id = self.attr('data-id');
			var index = this.imagePrefix+id;

			if (self.hasClass('delete') || confirm('Esti sigur ca vrei sa setezi acceasta imagine ca imaigine de produs?')) {
				this.images()[index].productImage = true;
				this.update('images', id, 'setProduseImage', true);
				var oldProductImage = $('.'+this.productImageClass);

				if (oldProductImage.length) {
					oldProductImage.removeClass(this.productImageClass);
					var oldImg = oldProductImage.children('img');
					var oldID = oldImg.attr('data-id');
					var oldIndex = this.imagePrefix+oldID;

					oldImg.attr('data-product-image', 'false');
					this.images()[oldIndex].productImage = false;
					this.update('images', oldID, 'setProduseImage', false);
				}
				this.saveJSON();
				$('#image-'+id).addClass(this.productImageClass).children('img').attr('data-product-image', 'true');
			}
		},

		checkGroupExists: function(name, callbeck) {
			$.get(domain+'checkGroupExists/'+name, function(data) {
				callbeck(data);
			}, 'json');
		},
		checkGroupExistsInThisProduct: function(name) {
			var count = 0;

			$('.'+this.groupNameClass).each(function(i, e) {
				if ($(e).text() == name) {
					count++;
				}
			});
			return (count > 1);
		},
		checkAttributeExists: function(name, callbeck) {
			$.get(domain+'checkAttributeExists/'+name, function(data) {
				callbeck(data);
			}, 'json');
		},
		checkAttributeExistsInThisProduct: function(name) {
			var count = 0;

			$('.'+this.attrNameTDClass).each(function(i, e) {
				if ($(e).text() == name) {
					count++;
				}
			});
			return (count > 1);
		},
		checkUpdatesHasBeemMade: function() {
			return (!$.isEmptyObject(this.json().updates.data) ||
				!$.isEmptyObject(this.json().updates.groups) ||
				!$.isEmptyObject(this.json().updates.attributes) ||
				!$.isEmptyObject(this.json().updates.images)) ? true : false;
		},
		checkformHasData: function() {
			var data = this.data();
			delete data.prdWeight;
			delete data.prdLenght;
			delete data.prdHeight;
			delete data.prdWidth;
			delete data.prdWeightClassID;
			delete data.prdLengthClassID;
			return (!$.isEmptyObject(data) ||
				!$.isEmptyObject(this.json().attributes) ||
				!$.isEmptyObject(this.json().images)) ? true : false;
		},

		validateData: function() {
			if ($.trim(productNameID.val()).length == 0) {
				this.setError('N-ai completat numele produsului!');
			}
			if ($.trim(productMakerID.val()).length == 0) {
				this.setError('N-ai ales producatorul produsului!');
			}
			if ($.trim(productMetaTitleID.val()).length == 0) {
				this.setError('N-ai completat titlu meta tag!');
			}
			if ($.trim(productStocID.val()).length == 0) {
				this.setError('N-ai completat stocul produsului!');
			}
			if ($.trim(productPriceID.val()).length == 0) {
				this.setError('N-ai completat pretul produsului!');
			}
			if ($.trim(productWidthID.val()).length == 0) {
				this.setError('N-ai completat latimea produsului!');
			}
			if ($.trim(productLenghtID.val()).length == 0) {
				this.setError('N-ai completat lungimea produsului!');
			}
			if ($.trim(productHeightID.val()).length == 0) {
				this.setError('N-ai completat inaltimea produsului!');
			}
			if ($.trim(productWeightID.val()).length == 0) {
				this.setError('N-ai completat greutatea produsului!');
			}
			if ($.trim(productWeighClasstID.val()).length == 0) {
				this.setError('N-ai ales clasa de greutatea a produsului!');
			}
			if ($.trim(productLenghtClassID.val()).length == 0) {
				this.setError('N-ai ales clasa de lungime a produsului!');
			}
		},
		validateGroups: function() {
			if (this.checkGroupExistsInThisProduct('')) {
				this.setError('N-ai denumit toate grupurile!');
			}
		},
		validateAttriutes: function() {
			if (this.checkAttributeExistsInThisProduct('')) {
				this.setError('N-ai denumit toate atributele!');
			}
		},

		setError: function(error) {
			this.errors.push(error);
		},
		displayErrors: function() {
			var errorsDiv = $('<div />');
			var errorsUL = $('<ul />');

			errorsDiv.addClass('error').attr('id', this.errorDivID).append(errorsUL);

			for (var e in this.errors) {
				var errorLI = $('<li />');
				errorLI.text(this.errors[e]);
				errorsUL.append(errorLI);
			}
			productsFormTitlaID.after(errorsDiv);
			this.errors = [];
		},
		errorsCheck: function() {
			return this.errors.length > 0;
		},

		cancelEdit: function() {
			dataInput.val('');
			sablonSelectID.val('');
			groupsDivID.html('');
			imagesDivID.html('');
			this.jsonObj.data = {};
			this.jsonObj.attributes = {};
			this.jsonObj.images = {};
			this.id = 0;
			this.jsonObj.id = 0;
			this.sablonID = 0;
			this.jsonObj.sablonID = 0;
			this.sablons = {};
			this.jsonObj.sablons = {};
			this.action = 'insert';
			this.jsonObj.action = 'insert';
			delete this.updates;
			this.saveJSON();
			productsFormTitlaID.text('Adauga un prodrus');
			saveBtnID.text('Adauga!');
			attributeStatesID.addClass('hide');
		},

		changeCategory: function(catID, e) {
			this.json().catID = catID;
			this.saveJSON();
			this.catID = catID;
			var t = this;

			if (catID == 0) {
				sablonSelectID.html('Alege o categorie!');
				return;
			}

			$.get(domain+'getCategoryPresetes/'+catID, function(data) {
				if (data.status == 'ok') {
					if (typeof data.sablons != 'undefined') {
						t.sablons = data.sablons;
						t.json().catID = t.catID;
						t.json().sablons = t.sablons;
						t.populateSablons(data.sablons);
					}
					if (typeof data.presetes != 'undefined') {
						productWidthID.val(data.presetes.width).change();
						productLenghtID.val(data.presetes.lenght).change();
						productLenghtClassID.val(data.presetes.lenghtClassID).change();
						productHeightID.val(data.presetes.height).change();
						productWeightID.val(data.presetes.weight).change();
						productWeighClasstID.val(data.presetes.weightClassID).change();
					}
					t.saveJSON();
				} else if (data.status == 'error') {
				}
			}, 'json');
		}
	};

	// categories.setHandle(categories.rowClass);

	setTimeout(function() {
		domain = domain+'produse/';
		categories.init();
		product.init();

		categories.setCallbeck(function(catID, e) {
			product.changeCategory(catID, e);
		});
	}, 300);

	formPart.on('click', function(e) {
		formPart.removeClass('selected');
		var self = $(this);
		var part = self.data('part');
		self.addClass('selected');
		formGroup.addClass('hide');
		$('#formGroup'+part.charAt(0).toUpperCase()+part.substr(1)).removeClass('hide');
		product.json().formPart = self.attr('data-part');
		product.saveJSON();
	});

	dataInput.on('change', function(e) {
		product.pushDataToJSON(e);
	});

	productNameID.on('change', function(e) {
		product.imageSearch.setInitialValue($(this).val());
	});

	addGroupsBtnID.on('click', function(e) {
		product.addGroup();
	});

	deleteProduct.on('click', function(e) {
		e.preventDefault();
		product.delete(e);
	});

	saveBtnID.on('click', function(e) {
		product.addOrEdit();
	});

	cancelBtnID.on('click', function(e) {
		if (confirm('Esti sigur ca vrei sa anulezi editarea produsului curent?')) {
			product.cancelEdit();
		}
	});

	docBody.on('click', editProductClass, function(e) {
		e.preventDefault();
		product.populateForEditing(e);
	});

	// sa schimb afisarea de imagini
	/*docBody.on('click', imagesId+' > .'+product.imgDivClass, function(e) {
		googleImageSearch.showImage(e);
	});*/

	docBody.on('click', '.'+product.addAttrClass, function(e) {
		var tbody = $(this).parent().prev().children('tbody');
		product.addAttribute(tbody);
	});

	docBody.on('click', '.'+product.deleteAttrClass, function(e) {
		e.preventDefault();
		product.deleteAttribute(e);
	});

	docBody.on('click', '.'+product.deleteGroupBtnClass, function(e) {
		product.deleteGroup(e);
	});

	docBody.on('click', '#'+product.deleteImageLinkID, function(e) {
		e.preventDefault();
		product.deleteImage(e);
	});

	docBody.on('click', '#'+product.setImageProduseLinkID, function(e) {
		e.preventDefault();
		product.setProduseImage(e);
	});

	docBody.on('click', '#'+product.closeImageLinkID, function(e) {
		$(this).parent().remove();
	});

	docBody.on('blur', '.'+product.groupNameClass, function(e) {
		// console.log('brul');
		product.pushGroupToJSON(e);
	});

	docBody.on('focus', '.'+product.groupNameClass, function(e) {
		// console.log('focus');
		product.oldGroupName = $(this).text();
	});

	docBody.on('focus', '.'+product.attrNameTDClass+', .'+product.attrValueTDClass+', .'+product.attrUMTDClass, function(e) {
		// console.log('focus');
		product.oldAttribute = $(this).text();
	});

	docBody.on('blur', '.'+product.attrNameTDClass+', .'+product.attrValueTDClass+', .'+product.attrUMTDClass, function(e) {
		// console.log('brul');
		product.pushAttributeToJSON(e);
	});

	docBody.on('change', sablonId, function(e) {
		// console.log('change');
		product.getSablon($(this).val());
	});
});