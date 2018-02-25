$(function() {
	var docBody = $('body');

	var errors = {
		errorShowLink: $('.errorShow'),
		errorDeleteLink: $('.errorDelete'),

		showHide: function(e) {
			var self = $(e.target);

			self.parent().parent().next().toggleClass('hide');
			self.text((self.text() == 'Vezi eroarea') ? 'Ascunde eroarea' : 'Vezi eroarea');
		},

		delete: function(e) {
			if (!confirm('Esti sigur ca vrei sa stergi aceasta eroare?')) {
				return false;
			}

			var self = $(e.target);

			$.get(config.domain+'errorHandle/delete/'+self.attr('data-id'), function(data) {
				if (data.status == 'ok') {
					self.parent().parent().next().remove();
					self.parent().parent().remove();
				} else if (data.status == 'error') {
					alert(data.msg);
				}

			}, 'json');
		}
	};

	errors.errorShowLink.on('click', function(e) {
		e.preventDefault();
		errors.showHide(e);
	});

	errors.errorDeleteLink.on('click', function(e) {
		e.preventDefault();
		errors.delete(e);
	});
});