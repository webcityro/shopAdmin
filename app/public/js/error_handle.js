function errorHandler() {
	this.errors = {};
	this.errorsContainer;
	this.fieldErrorClass = 'formError';
	this.displayErrorsClass = 'displayErrors';

	this.setContainer = function(container) {
		this.errorsContainer = (typeof container == 'object') ? container : $(container);
	};

	this.setError = function(error, key) {
		this.errors[(typeof key == 'undefined') ? 'error_'+Object.keys(this.errors).length : key] = error;
	};

	this.getFieldErrorClass = function() {
		return this.fieldErrorClass;
	};

	this.getDisplayErrorsClass = function() {
		return this.displayErrorsClass;
	};

	this.getErrors = function() {
		$('.'+this.fieldErrorClass+', .'+this.displayErrorsClass).remove();
		var errorsUL = $('<ul />');

		errorsUL.addClass(this.displayErrorsClass);

		for (var i in this.errors) {
			if (i.indexOf('error_') == -1) {
				var span = $('<span />');

				span.addClass(this.fieldErrorClass).text(this.errors[i]);
				$('#'+i).after(span);
			} else {
				var li = $('<li />');

				li.text(this.errors[i]);
				errorsUL.append(li);
			}
		}

		if (errorsUL.children().length > 0) {
			this.errorsContainer.append(errorsUL).removeClass('hide');
		}
		this.errors = {};
	};

	this.hasErrors = function() {
		return Object.keys(this.errors).length > 0;
	};
}