$(function() {
	var docBody = $('body');

	var aliases = {
		displayAliases: $('#displayAliases'),
		aliasesRows: $('#aliasesRows'),
		aliasRowClass: 'aliasRow',
		aliasIDClass: 'aliasID',
		aliasTypeClass: 'aliasType',
		aliasTypesClass: 'aliasTypes',
		aliasSupplierClass: 'aliasSupplier',
		aliasSupplierIDClass: 'aliasSupplierID',
		aliasManufacturerClass: 'aliasManufacturer',
		aliasManufacturerIDClass: 'aliasManufacturerID',
		aliasSearchClass: 'aliasSearch',
		aliasArrayClass: 'aliasArray',
		aliasIsArrayClass: 'aliasIsArray',
		aliasPrefixClass: 'aliasPrefix',
		aliasIsPrefixClass: 'aliasIsPrefix',
		aliasReplaceWithClass: 'aliasReplaceWith',
		aliasActiveClass: 'aliasActive',
		aliasIsActiveClass: 'aliasIsActive',
		aliasActionsClass: 'aliasActions',
		addAliasBtnID: $('#addAlias'),
		saveLinkClass: 'saveLink',
		cancelLinkClass: 'cancelLink',
		deleteLinkClass: 'deleteLink',
		removeTRClass: 'removeTR',
		saveAndCancelSpanClass: 'saveAndCancel',

		init: function() {
			var autosuggest = new Autosuggest({target: '.'+this.aliasReplaceWithClass});
			autosuggest.init();
		},

		addHTML: function() {
			var tr = $('<tr />');
			var idTD = $('<td />');
			var typeTD = $('<td />');
			var supplierTD = $('<td />');
			var manufacturerTD = $('<td />');
			var searchTD = $('<td />');
			var arrayTD = $('<td />');
			var prefixTD = $('<td />');
			var replaceWithTD = $('<td />');
			var activeTD = $('<td />');
			var actionsTD = $('<td />');
			var saveLink = $('<a />');
			var deleteLink = $('<a />');
			var cancelLink = $('<a />');
			var span = $('<span />');

			var typeSelect = $('<select />');
			var supplierID = $('<select />');
			var manufacturerID = $('<select />');

			var optionAllSuppliers = $('<option />');
			var optionAllManufactures = $('<option />');

			var arrayCheckbox = $('<input />');
			var prefixCheckbox = $('<input />');
			var activeCheckbox = $('<input />');

			idTD.addClass(this.aliasIDClass);
			typeTD.addClass(this.aliasTypeClass).append(typeSelect);
			supplierTD.addClass(this.aliasSupplierClass).append(supplierID);
			manufacturerTD.addClass(this.aliasManufacturerClass).append(manufacturerID);
			searchTD.addClass(this.aliasSearchClass).attr('contenteditable', true);
			arrayTD.addClass(this.aliasArrayClass).append(arrayCheckbox)
			prefixTD.addClass(this.aliasPrefixClass).append(prefixCheckbox)
			activeTD.addClass(this.aliasActiveClass).append(activeCheckbox)
			actionsTD.addClass(this.aliasActionsClass).append(span).append(deleteLink);;
			span.addClass(this.saveAndCancelSpanClass).append(saveLink).append(' / ').append(cancelLink).append(' / ');
			saveLink.addClass(this.saveLinkClass).attr('href', '#').text('Salveza');
			cancelLink.addClass(this.cancelLinkClass+' hide').attr('href', '#').text('Anuleaza');
			deleteLink.addClass(this.deleteLinkClass).attr('href', '#').text('Sterge');
			tr.addClass(this.aliasRowClass+' new').attr({'data-id': '0'}).append(idTD).append(typeTD).append(supplierTD).append(manufacturerTD).append(searchTD).append(arrayTD).append(prefixTD).append(replaceWithTD).append(activeTD).append(actionsTD);

			typeSelect.addClass(this.aliasTypesClass);
			supplierID.addClass(this.aliasSupplierIDClass).append(optionAllSuppliers);
			manufacturerID.addClass(this.aliasManufacturerIDClass).append(optionAllManufactures);

			optionAllSuppliers.attr('value', '0').text('Toti furnizorii');
			optionAllManufactures.attr('value', '0').text('Toti producatorii');

			arrayCheckbox.addClass(this.aliasIsArrayClass).attr('type', 'checkbox');
			prefixCheckbox.addClass(this.aliasIsPrefixClass).attr('type', 'checkbox');
			arrayCheckbox.addClass(this.aliasIsArrayClass).attr('type', 'checkbox');
			activeCheckbox.addClass(this.aliasIsActiveClass).attr({'type': 'checkbox', 'checked': true});

			for (var i in types) {
				var option = $('<option />');
				option.attr('value', i).text(types[i]);
				typeSelect.append(option);
			}

			for (var i in suppliers) {
				var option = $('<option />');
				option.attr('value', suppliers[i].id).text(suppliers[i].name);
				supplierID.append(option);
			}

			for (var i in manufacturers) {
				var option = $('<option />');
				option.attr('value', manufacturers[i].manufacturer_id).text(manufacturers[i].name);
				manufacturerID.append(option);
			}

			replaceWithTD.addClass(this.aliasReplaceWithClass).attr({
				'contenteditable': true,
				'data-itemid': 0,
				'data-table': typeToTable[typeSelect.val()].table,
				'data-column': typeToTable[typeSelect.val()].column,
				'data-columnid': typeToTable[typeSelect.val()].columnID
			});
			this.aliasesRows.append(tr);
			$('.'+this.removeTRClass).remove();
		},

		save: function(e) {
			e.preventDefault();
			var self = $(e.target);
			var t = this;
			var thisTR = self.parent().parent().parent();
			var type = thisTR.find('.'+this.aliasTypesClass);
			var supplierID = thisTR.find('.'+this.aliasSupplierIDClass);
			var manufacturerID = thisTR.find('.'+this.aliasManufacturerIDClass);
			var search = thisTR.find('.'+this.aliasSearchClass);
			var array = thisTR.find('.'+this.aliasIsArrayClass);
			var prefix = thisTR.find('.'+this.aliasIsPrefixClass);
			var replaceWith = thisTR.find('.'+this.aliasReplaceWithClass);
			var active = thisTR.find('.'+this.aliasIsActiveClass);
			var itemID = replaceWith.attr('data-itemid');

			// console.log('type', type.val());
			// console.log('supplierID', supplierID.val());
			// console.log('manufacturerID', manufacturerID.val());
			// console.log('search', search.text());
			// console.log('array', array.is(':checked'));
			// console.log('prefix', prefix.is(':checked'));
			// console.log('replaceWith', replaceWith.text());
			// console.log('active', active.is(':checked'));

			if (search.text().length == 0) {
				alert('N-ai completat campul "cauta"!');
				return false;
			}

			$.post(config.domain+'aliases/save/'+thisTR.attr('data-id'), {
					type: type.val(),
					supplierID: supplierID.val(),
					manufacturerID: manufacturerID.val(),
					itemID: itemID,
					search: search.text(),
					array: (array.is(':checked')) ? '1' : '0',
					prefix: (prefix.is(':checked')) ? '1' : '0',
					replaceWith: replaceWith.text(),
					active: (active.is(':checked')) ? '1' : '0'
				}, function(data) {
					if (data.status == 'ok') {
						type.removeAttr('data-old');
						supplierID.removeAttr('data-old');
						manufacturerID.removeAttr('data-old');
						search.removeAttr('data-old');
						array.removeAttr('data-old');
						prefix.removeAttr('data-old');
						replaceWith.removeAttr('data-old');
						active.removeAttr('data-old');
						thisTR.find('.'+t.saveAndCancelSpanClass).addClass('hide').children('.'+t.cancelLinkClass).removeClass('hide');
						thisTR.removeClass('new').attr('data-id', data.id).find('.'+t.aliasIDClass).text(data.id);
						self.remove();
					} else if (data.status == 'error') {
						alert(data.msg);
					}
				}, 'json');
		},

		changeType: function(e) {
			var self = $(e.target);
			self.parent().parent().find('.'+this.aliasReplaceWithClass).attr({
				'data-itemid': 0,
				'data-table': typeToTable[self.val()].table,
				'data-column': typeToTable[self.val()].column,
				'data-columnid': typeToTable[self.val()].columnID
			});
		},

		deleteAlias: function(e) {
			e.preventDefault();
			if (!confirm('Esti sigur ca vrei sa stergi?')) {
				return false;
			}

			var self = $(e.target);
			var t = this;
			var thisTR = self.parent().parent();
			var id = thisTR.attr('data-id');

			if (!thisTR.hasClass('new')) {
				$.get(config.domain+'aliases/delete/'+id, function(data) {
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

		cancelEdit: function(e) {
			e.preventDefault();
			var self = $(e.target);

			var thisTR = self.parent().parent().parent();
			var type = thisTR.find('.'+this.aliasTypesClass);
			var supplierID = thisTR.find('.'+this.aliasSupplierIDClass);
			var manufacturerID = thisTR.find('.'+this.aliasManufacturerIDClass);
			var search = thisTR.find('.'+this.aliasSearchClass);
			var array = thisTR.find('.'+this.aliasIsArrayClass);
			var prefix = thisTR.find('.'+this.aliasIsPrefixClass);
			var replaceWith = thisTR.find('.'+this.aliasReplaceWithClass);
			var active = thisTR.find('.'+this.aliasIsActiveClass);

			type.val(type.attr('data-old')).removeAttr('data-old');
			supplierID.val(supplierID.attr('data-old')).removeAttr('data-old');
			manufacturerID.val(manufacturerID.attr('data-old')).removeAttr('data-old');
			search.text(search.attr('data-old')).removeAttr('data-old');
			array.prop('checked', array.attr('data-old') == 'true')
			prefix.prop('checked', prefix.attr('data-old') == 'true')
			replaceWith.text(replaceWith.attr('data-old')).removeAttr('data-old');
			active.prop('checked', active.attr('data-old') == 'true')
			thisTR.find('.'+this.saveAndCancelSpanClass).addClass('hide');
		}
	};

	aliases.addAliasBtnID.on('click', function(e) {
		aliases.addHTML();
	});

	docBody.on('click', '.'+aliases.deleteLinkClass, function(e) {
		aliases.deleteAlias(e);
	});

	docBody.on('click', '.'+aliases.saveLinkClass, function(e) {
		aliases.save(e);
	});

	docBody.on('click', '.'+aliases.cancelLinkClass, function(e) {
		aliases.cancelEdit(e);
	});

	docBody.on('focus', '.'+aliases.aliasTypesClass+
						', .'+aliases.aliasSupplierIDClass+
						', .'+aliases.aliasManufacturerIDClass+
						', .'+aliases.aliasSearchClass+
						', .'+aliases.aliasIsArrayClass+
						', .'+aliases.aliasIsPrefixClass+
						', .'+aliases.aliasReplaceWithClass+
						', .'+aliases.aliasIsActiveClass, function(e) {
		var self = $(this);
		var tagName = self.prop('tagName').toLowerCase();

		if (self.attr('data-old') == undefined) {
			self.attr('data-old', (tagName == 'select') ? self.val() : ((tagName == 'input') ? self.is(':checked') : self.text()));
		}
	});

	docBody.on('blur',  '.'+aliases.aliasTypesClass+
						', .'+aliases.aliasSupplierIDClass+
						', .'+aliases.aliasManufacturerIDClass+
						', .'+aliases.aliasSearchClass+
						', .'+aliases.aliasIsArrayClass+
						', .'+aliases.aliasIsPrefixClass+
						', .'+aliases.aliasReplaceWithClass+
						', .'+aliases.aliasIsActiveClass, function(e) {
		var self = $(this);
		var tagName = self.prop('tagName').toLowerCase();
		var thisTR = (tagName == 'td') ? self.parent() : self.parent().parent();
		var value = (tagName == 'select') ? self.val() : self.text();

		if (!thisTR.hasClass('new') && value != self.attr('data-old')) {
			thisTR.find('.'+aliases.saveAndCancelSpanClass).removeClass('hide');
		} else {
			self.removeAttr('data-old');
		}
	});

	docBody.on('change', '.'+aliases.aliasTypesClass, function(e) {
		aliases.changeType(e);
	});

	aliases.init();
});