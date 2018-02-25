var catID;
var docBody;
var domain;
var categories;


$(function() {
	setTimeout(function() {
		domain = config.domain;
		// categories.init();
	}, 200);

	docBody = $(document.body);

	categories = {
		id: 0,
		name: '',
		catLabelID: '#catLabel',
		catSelectIDClass: '.catSelectID',
		rowClass: '.catRow',
		catImageClass: '.catRowImage',
		subCatsClass: '.subCats',
		nameClass: '.catName',
		displayCatsClass: '.displayCats',
		displayCats: $(this.displayCatsClass),
		subCats: $(this.subCatsClass),
		callbeck: '',
		handle: this.rowClass,
		displayCats: '',
		catLabel: '',

		init: function() {
			// console.log('categories.init');
			this.displayCats = $(this.displayCatsClass);
			this.catLabel = $(this.catLabelID);

			docBody.on('click', categories.handle, function(e) {
				if (!$(e.target).hasClass(categories.catSelectIDClass.replace('.', ''))) {
					categories.toggleOpen(e);
				}
				// console.log('target', $(e.target));
				// console.log('handle', categories.handle);
			});

			docBody.on('change', categories.catSelectIDClass, function(e) {
				categories.change(e);
			});

			docBody.on('click', categories.catLabelID, function(e) {
				categories.displayCats.children().not(categories.catLabelID).toggleClass('hide');
			});
		},
		toggleOpen: function(e) {
			// console.log('toggleOpen');
			var self = $(e.target);
			self = (self.hasClass(this.nameClass.replace('.', ''))) ? self.parent() : self;
			var subCats = $('#subCats-'+self.data('id'));

			subCats.toggleClass('hide');
		},
		preopen: function(id) {
			var self = $(id);
			self = (self.prop('tagName').toLowerCase() == 'li') ? self.parent() : self;
			self.prev().addClass('selected');
			var radio = self.prev().children(this.catSelectIDClass);
			radio.attr('checked', true);
			self.parentsUntil('#subCats-0').removeClass('hide');
			self.removeClass('hide');
			this.change(radio);
		},
		change: function(e) {
			$(this.displayCatsClass+' .selected').removeClass('selected');
			var self = (typeof e.target != 'undefined') ? $(e.target) : e;
			this.id = self.val();
			this.name = self.next().text();
			this.catLabel.text('Categoria: '+this.name);
			self.parent().addClass('selected');
			this.displayCats.children().not(this.catLabelID).addClass('hide');

			if (typeof this.callbeck == 'function' && this.id !== undefined) {
				// console.log('callbeck');
				this.callbeck(this.id, self);
			}
		},
		setCallbeck: function(callbeck) {
			this.callbeck = callbeck;
		},
		setHandle: function(handle) {
			this.handle = handle;
		}
	};
	categories.setHandle(categories.rowClass);
});