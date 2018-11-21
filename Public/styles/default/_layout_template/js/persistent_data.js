const persistentData = function() {
	this.items = {};
	this.fieldClass = 'persistent-data';
	this.startNewItemID;
	this.fieldNameClass = 'persistent-data-name';
	this.fieldLanguageClass = 'persistent-data-language';
	this.persistentItemClass = 'persistent-item';
	this.persistentItemDeleteClass = 'persistent-item-delete';
	this.persistentItemsNewID = 'persistent-items-new';
	this.persistentItemsEditID = 'persistent-items-edit';
	this.fields;
	this.currentItemID = 0;
	this.newItemsCount = 0;
	this.languageID = false;
	this.defaultLanguageID = false;
	this.formLanguage;
	this.section;
	this.isNew = true;
	this.switchItemCB;
	this.deleteItemCB;

	this.init = function(formLanguage) {
		this.fields = $('.'+this.fieldClass);
		this.startNewItemID = $('#persistent-data-start-new-item');
		this.defaultLanguageID = config.system.languageID;
		this.languageID = this.defaultLanguageID;
		this.formLanguage = formLanguage;

		this.setItem();

		this.fields.each((i, el) => {
			this.set($(el));
		});

		if (this.defaultLanguageID) {
			formLanguage.on('click', (e) => {
				e.preventDefault();
				let self = $(e.target);
				this.languageID = self.data('id');
				self.parent().addClass('active').siblings().removeClass('active');
				this.populate(this.currentItemID);
			});
		}

		docBody.on('change', '.'+this.fieldClass, (e) => {
			let self = $(e.target);
			this.set(self);
			this.save(self);
		});

		docBody.on('click', '.'+this.persistentItemClass, (e) => {
			e.preventDefault();
			let self = $(e.target);
			this.currentItemID = self.parent().data('id');
			this.populate(this.currentItemID);

			if (typeof this.switchItemCB === 'function') {
				this.switchItemCB(this.items[this.currentItemID].itemID);
			}
		});

		docBody.on('click', '.'+this.persistentItemDeleteClass, (e) => {
			e.preventDefault();
			let self = $(e.target);
			let id = self.parent().data('id');
			let name = this.items[id].name;
			let body = $('<div />');
			let p = $('<p />');
			let deleteArticleRadio = $('<input />');

			body.append(p);
			p.addClass('lead text-center').text(language.translate('deletePersistentDataPharagraf', [name]));

			if (typeof this.deleteItemCB === 'function') {
				let row = $('<div />');
				let colLeft = $('<div />');
				let colRight = $('<div />');
				let dontDeleteArticleRadio = $('<input />');
				let deleteArticleLabel = $('<label />');
				let dontDeleteArticleLabel = $('<label />');

				body.append(row);
				p.text(language.translate('deletePersistentDataAndArticlePharagraf', [name]));

				deleteArticleRadio.addClass('form-check-input').attr({type: 'radio', name: 'deleteArticle', id: 'deleteArticle'}).val('true');
				dontDeleteArticleRadio.addClass('form-check-input').attr({type: 'radio', name: 'deleteArticle', id: 'dontDeleteArticle'}).val('false');
				deleteArticleLabel.addClass('form-check-label').attr('for', 'deleteArticle').text(language.translate('deletePersistentDataAndArticle'));
				dontDeleteArticleLabel.addClass('form-check-label').attr('for', 'dontDeleteArticle').text(language.translate('deletePersistentData'));

				colLeft.append(dontDeleteArticleLabel).append(dontDeleteArticleRadio);
				colRight.append(deleteArticleLabel).append(deleteArticleRadio);
				row.addClass('row').append(colLeft).append(colRight);
				body.append(row);
			}

			modal(language.translate('warning'), body, function(modalBody) {
				this.delete(id, (typeof this.deleteItemCB === 'function' && deleteArticleRadio.is(':checked')));
				return true;
			}, language.translate('delete'));
		});

		this.startNewItemID.on('click', e => {
			e.preventDefault();

			this.setCurrent(false, data => {
				this.setItem();
				this.populate();
				this.isNew = true;
			});

		});
	};

	this.load = function(data) {
		if (data.newData.length > 0) {
			this.setData(data.newData);
		} else if (data.editData.length > 0) {
			this.setData(data.editData);
		}
	};

	this.setData = function(data) {
		for (let i in data) {
			this.setItem(data[i].id, JSON.parse(data[i].data), data[i].name);

			if (data[i].current == '1') {
				if (data[i].languageID == this.languageID) {
					this.populate(data[i].id);
					this.isNew = false;
					this.currentItemID = data[i].id;
				} else {
					this.formLanguage.filter('[data-id="'+data[i].languageID+'"]').click();
				}
			}
		}
	};

	this.onItemChange = function(cb) {
		this.switchItemCB = cb
	};

	this.onItemDelete = function(cb) {
		this.deleteItemCB = cb
	};

	this.populate = function(id) {
		this.fields.each((index, el) => {
			let e = $(el);
			let self = this.getOrSetField(e);
			let value = typeof id === 'undefined' ? e.data('unchecked-value') || e.data('default-value') || '' : this.get(self.id, id);
			this.getOrSetField(e, value);
		});
	};

	this.setItem = function (id, data, name) {
		this.items[id || this.currentItemID] = {itemID: 0, name: name || language.translate('new'), data: data || {static: {}, languages: {}}};
	};

	this.setSection = function(section) {
		this.section = section;
	};

	this.getOrSetField = function(self, value) {
		let type = self.attr('type');
		let id = self.attr(type == 'radio' ? 'name' : 'id');
		let tag = self.prop('tagName').toLowerCase();

		if (type == 'checkbox' || type == 'radio') {
			if (typeof value == 'undefined') {
				value = self.prop('checked') ? (self.data('checked-value') || true) : (self.data('unchecked-value') || false);
			} else {
				self.prop('checked', (self.data('checked-value') == value || value));
			}
		} else {
			if (typeof value == 'undefined') {
				value = self[tag == 'input' || tag == 'select' || tag == 'textarea' ? 'val' : 'text']();
			} else {
				self[tag == 'input' || tag == 'select' || tag == 'textarea' ? 'val' : 'text'](value);
			}
		}

		return {id: id, value: value, type: type, tag: tag};
	}

	this.set = function (self) {
		let field = this.getOrSetField(self);

		if ((self.hasClass(this.fieldNameClass) || field.id == 'name') && this.languageID == this.defaultLanguageID) {
			this.items[this.currentItemID].name = field.value;
		}

		if (self.hasClass(this.fieldLanguageClass)) {
			this.items[this.currentItemID].data.languages[this.languageID] = this.items[this.currentItemID].data.languages[this.languageID] || {};
			this.items[this.currentItemID].data.languages[this.languageID][field.id] = field.value;
		} else {
			this.items[this.currentItemID].data.static[field.id] = field.value;
		}

		return field;
	};

	this.get = function (key, id, languageID) {
		let data =this.items[id || this.currentItemID].data;

		if (data.languages && data.languages[languageID || this.languageID] && data.languages[languageID || this.languageID][key]) {
			return data.languages[languageID || this.languageID][key];
		}
		return data[key] || data.static[key] || '';
	};

	this.addItemRow = function(id, name) {
		let li = $('<li />');
		let nameLink = $('<a />');
		let deleteLink = $('<a />');
		let deleteIcon = $('<i />');

		li.addClass('dropdown-item').attr('data-id', id).append(nameLink).append(deleteLink);
		nameLink.addClass(this.persistentItemClass).attr('href', '#').text(name);
		deleteLink.addClass(this.persistentItemDeleteClass).attr('href', '#').append(deleteIcon);
		deleteIcon.addClass('far fa-trash-alt');

		let dropdown = this.getDropdown();

		dropdown.ul.append(li);
		dropdown.counter.text(dropdown.ul.children().length);
	};

	this.getDropdown = function() {
		let dropdownID = this.isNew ? this.persistentItemsNewID : this.persistentItemsEditID;
		let itemsLI = rightNav.find('li#'+dropdownID);

		if (itemsLI.length == 1) {
			let li = itemsLI.find('ul.dropdown-menu li.dropdown-item[data-id="'+this.currentItemID+'"]');

			return {
				itemsLI: itemsLI,
				ul: itemsLI.find('ul.dropdown-menu'),
				li: this.isNew ? false : li,
				a: this.isNew ? false : li.find('a.'+this.persistentItemClass),
				counter: itemsLI.find('span.badge')
			};
		}

		itemsLI = $('<li />');
		let newItemsLI = rightNav.find('li#'+dropdownID);
		let dropdownLink = $('<a />');
		let dropdownIcon = $('<i />');
		let dropdownIconCounter = $('<span />');
		let dropdownUL = $('<ul />');

		itemsLI.addClass('pull-left dropdown').attr('id', dropdownID).append(dropdownLink).append(dropdownUL);
		dropdownLink.addClass('dropdown-toggle lead prtsistentDataIcon').attr({
			'data-toggle': 'dropdown',
			'aria-haspopup': 'true',
			'aria-expanded': 'false',
			role: 'button'
		}).append(dropdownIcon);
		dropdownIcon.addClass('far fa-'+(this.persistentItemsNewID == dropdownID ? 'file' : 'edit')).append(dropdownIconCounter);
		dropdownIconCounter.addClass('badge').text('1');
		dropdownUL.addClass('dropdown-menu');

		if (!this.isNew && newItemsLI.length == 1) {
			newItemsLI.after(itemsLI);
		} else {
			rightNav.prepend(itemsLI);
		}
		return {itemsLI: itemsLI, ul: dropdownUL, li: false, a: false, counter: dropdownIconCounter};
	};

	this.save = function (self) {
		ajax({
			method: 'POST',
			route: ['ajax.persitentData.save', {id: this.currentItemID}],
			fields: {
				itemID: this.items[this.currentItemID].itemID,
				name: this.items[this.currentItemID].name,
				languageID: this.languageID,
				data: JSON.stringify(this.items[this.currentItemID].data),
				section: this.section
			},
			success: data => {
				if (this.currentItemID == 0) {
					this.items[data.newData.id] = this.items[this.currentItemID];
					delete this.items[this.currentItemID];
					this.currentItemID = data.newData.id;
					this.addItemRow(data.newData.id, data.newData.name);
				} else if (self.hasClass(this.fieldLanguageClass) && this.languageID == this.defaultLanguageID) {
					this.getDropdown().a.text(this.items[this.currentItemID].name);
				}
			}
		});
	};

	this.delete = function(id, deleteArticle) {
		ajax({
			method: 'POST',
			route: ['ajax.persitentData.delete', {id: id}],
			success: data => {
				if (this.currentItemID == id) {
					this.setItem();
					this.populate();
				}

				if (deleteArticle) {
					this.deleteItemCB(this.items[id].id);
				}

				delete this.items[id];
			}
		});
	};

	this.setCurrent = function(current, cb) {
		ajax({
			method: 'POST',
			route: ['ajax.persitentData.setCurrent', {id: this.currentItemID}],
			fields: {
				current: current,
				section: this.section
			},
			success: data => {
				cb(data);
			}
		});
	};
};