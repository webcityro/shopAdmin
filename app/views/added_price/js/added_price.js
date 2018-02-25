$(function() {
	var docBody = $('body');

	var addedPrice = {
		displayAddedPrice: $('#displayAddedPrice'),
		addedPriceRows: $('#addedPriceRows'),
		apRowClass: 'apRow',
		apIDClass: 'apID',
		apMinClass: 'apMin',
		apMaxClass: 'apMax',
		apPrecentClass: 'apPrecent',
		apActionsClass: 'apActions',
		addApBtnID: $('#addAP'),
		saveLinkClass: 'saveLink',
		cancelLinkClass: 'cancelLink',
		deleteLinkClass: 'deleteLink',
		removeTRClass: 'removeTR',
		saveAndCancelSpanClass: 'saveAndCancel',

		addHTML: function() {
			var tr = $('<tr />');
			var idTD = $('<td />');
			var minTD = $('<td />');
			var maxTD = $('<td />');
			var precentTD = $('<td />');
			var actionsTD = $('<td />');
			var saveLink = $('<a />');
			var deleteLink = $('<a />');
			var cancelLink = $('<a />');
			var span = $('<span />');

			idTD.addClass(this.apIDClass);
			minTD.addClass(this.apMinClass).attr('contenteditable', true);
			maxTD.addClass(this.apMaxClass).attr('contenteditable', true);
			precentTD.addClass(this.apPrecentClass).attr('contenteditable', true);
			actionsTD.addClass(this.apActionsClass).append(span).append(deleteLink);;
			span.addClass(this.saveAndCancelSpanClass).append(saveLink).append(' / ').append(cancelLink).append(' / ');
			saveLink.addClass(this.saveLinkClass).attr('href', '#').text('Salveza');
			cancelLink.addClass(this.cancelLinkClass+' hide').attr('href', '#').text('Anuleaza');
			deleteLink.addClass(this.deleteLinkClass).attr('href', '#').text('Sterge');
			tr.addClass(this.apRowClass+' new').append(idTD).append(minTD).append(maxTD).append(precentTD).append(actionsTD);
			this.addedPriceRows.append(tr);
			$('.'+this.removeTRClass).remove();
		},

		save: function(e) {
			e.preventDefault();
			var self = $(e.target);
			var t = this;
			var thisTR = self.parent().parent().parent();
			var min = thisTR.children('.'+this.apMinClass);
			var max = thisTR.children('.'+this.apMaxClass);
			var precent = thisTR.children('.'+this.apPrecentClass);

			console.log('min1', min);
			console.log('min', min.text());
			console.log('max', max.text());
			console.log('precent', precent.text());

			if (min.text().length == 0) {
				alert('N-ai completat pretul minim!');
				return false;
			}
			if (max.text().length == 0) {
				alert('N-ai completat pretul maxim!');
				return false;
			}
			if (precent.text().length == 0) {
				alert('N-ai completat procentul!');
				return false;
			}
			if (isNaN(min.text()) || isNaN(max.text()) || isNaN(precent.text())) {
				alert('Campurile pot contine doar caractere numerice');
				return false;
			}
			if (min.text() > max.text()) {
				alert('Pretul minim nu poate fi mai mare decat cel maxim!');
				return false;
			}

			$.post(config.domain+'addedPrice/save'+((thisTR.attr('data-id') != undefined) ? '/'+thisTR.attr('data-id') : ''), {
					min: min.text(),
					max: max.text(),
					precent: precent.text()
				}, function(data) {
					if (data.status == 'ok') {
						min.removeAttr('data-old');
						max.removeAttr('data-old');
						precent.removeAttr('data-old');
						thisTR.children('.'+t.apActionsClass).children('.'+t.saveAndCancelSpanClass).addClass('hide').children('.'+t.cancelLinkClass).removeClass('hide');
						thisTR.removeClass('new').attr('data-id', data.id).children('.'+t.apIDClass).text(data.id);
						self.remove();
					} else if (data.status == 'error') {
						alert(data.msg);
					}
				}, 'json');
		},

		deleteAP: function(e) {
			e.preventDefault();
			if (!confirm('Esti sigur ca vrei sa stergi?')) {
				return false;
			}

			var self = $(e.target);
			var t = this;
			var thisTR = self.parent().parent();
			var id = thisTR.attr('data-id');

			if (!thisTR.hasClass('new')) {
				$.get(config.domain+'addedPrice/delete/'+id, function(data) {
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
			var min = thisTR.children('.'+this.apMinClass);
			var max = thisTR.children('.'+this.apMaxClass);
			var precent = thisTR.children('.'+this.apPrecentClass);

			min.text(min.attr('data-old')).removeAttr('data-old');
			max.text(max.attr('data-old')).removeAttr('data-old');
			precent.text(precent.attr('data-old')).removeAttr('data-old');
			thisTR.children('.'+this.apActionsClass).children('.'+this.saveAndCancelSpanClass).addClass('hide');
		}
	};

	addedPrice.addApBtnID.on('click', function(e) {
		addedPrice.addHTML();
	});

	docBody.on('click', '.'+addedPrice.deleteLinkClass, function(e) {
		addedPrice.deleteAP(e);
	});

	docBody.on('click', '.'+addedPrice.saveLinkClass, function(e) {
		addedPrice.save(e);
	});

	docBody.on('click', '.'+addedPrice.cancelLinkClass, function(e) {
		addedPrice.cancelEdit(e);
	});

	docBody.on('focus', '.'+addedPrice.apMinClass+', .'+addedPrice.apPrecentClass+', .'+addedPrice.apMaxClass, function(e) {
		var self = $(this);

		if (self.attr('data-old') == undefined) {
			self.attr('data-old', self.text());
		}
	});

	docBody.on('blur', '.'+addedPrice.apMinClass+', .'+addedPrice.apPrecentClass+', '+addedPrice.apMaxClass, function(e) {
		var self = $(this);

		if (!self.parent().parent().parent().hasClass('new') && self.text() != self.attr('data-old')) {
			self.parent().children('.'+addedPrice.apActionsClass).children('.'+addedPrice.saveAndCancelSpanClass).removeClass('hide');
		} else {
			self.removeAttr('data-old');
		}
	});
});