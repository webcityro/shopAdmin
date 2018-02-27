function Autosuggest(param) {
	this.suggestionsULClass = 'astosugestResults';
	this.suggestionsLIClass = 'astosugestResult';
	this.target = param.target;
	this.docBody = $(document.body);
	this.self;
	this.tagName;
	this.value;
	this.table;
	this.column;
	this.columnID;

	this.init = function() {
		var t = this;

		this.docBody.on('keyup', this.target, function(e) {
			t.keyup(e);
		});

		this.docBody.on('click', '*', function(e) {
			if (!$(e.target).hasClass('.'+t.suggestionsLIClass)) {
				$('.'+t.suggestionsULClass).remove();
			}
		});
	};

	this.keyup = function(e) {
		this.self = $(e.target);
		this.tagName = this.self.prop('tagName').toLowerCase();
		this.table = this.self.attr('data-table');
		this.column = this.self.attr('data-column');
		this.columnID = this.self.attr('data-columnid');
		this.value = (this.tagName == 'input' || this.tagName == 'textarea') ? this.self.val() : this.self.text();
		var ul = $('.'+this.suggestionsULClass);
		var selected = ul.find('.selected');

		if (e.keyCode == 40 || e.keyCode == 38) {
			if (ul.length) {
				if (selected.length && selected !== ul.children().last()) {
					if (e.keyCode == 40) {
						selected.next().addClass('selected');
					} else {
						selected.prev().addClass('selected');
					}
					selected.removeClass('selected');
				} else if (selected.length == 0 || selected !== ul.children().fitst()) {
					if (e.keyCode == 40) {
						ul.children().first().addClass('selected');
					} else {
						ul.children().last().addClass('selected');
					}
				}
			}
		} else if (e.keyCode == 13 && selected.length) {
			selected.click();
		} else {
			this.ajax();
		}

	};

	this.ajax = function() {
		var t = this;

		$.ajax({
			url: config.domain+'ajax/autosuggest',
			type: 'POST',
			dataType: 'json',
			data: {table: this.table, column: this.column, columnID: this.columnID, value: this.value},
		})
		.done(function(data) {
			$('.'+t.suggestionsULClass).remove();

			if (data.status == 'ok') {
				var targetPosition = t.self.offset();
				var ul = $('<ul />');

				ul.addClass(t.suggestionsULClass).css({
					left: targetPosition.left+'px',
					top: (targetPosition.top+t.self.height())+'px'
				});;

				for (var i in data.rows) {
					var li = $('<li />');

					li.addClass(t.suggestionsLIClass).attr('data-id', data.rows[i][t.columnID]).text(data.rows[i][t.column]);
					ul.append(li);

					li.on('click', function(e) {
						var thisLI = $(this);

						t.self.attr('data-itemid', thisLI.attr('data-id'));

						if (this.tagName == 'input' || this.tagName == 'textarea') {
							t.self.val(thisLI.text());
						} else {
							t.self.text(thisLI.text());
						}
						$('.'+t.suggestionsULClass).remove();
					});
				}
			}
			t.docBody.append(ul);
		});

	};
}