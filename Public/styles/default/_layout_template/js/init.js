const errorsHandler = new errorHandler();
const persistData = new persistentData();
const validation = new validator();
const formLanguageClass = 'form-language';
var paginationWrapper,
	 formLanguage,
	 docBody,
	 rightNav;

$(function(e) {
	paginationWrapper = $('#paginationWrapper');
	formLanguage = $('.'+formLanguageClass);
	docBody = $('body');
	rightNav = $('#rightNav');

	$('#button-menu').on('click', function(e) {
		e.preventDefault();
		$('#column-left').toggleClass('active');
	});

	$('ul#menu li a.parent').on('click', function(e) {
		e.preventDefault();
		const thisUL = $(this).next('ul.collapse');
		thisUL.toggleClass('in').data('expanded', thisUL.hasClass('in'));
	});

	$('.showPassword').on('change', function(e) {
		const self = $(this);
		const password = $('#'+self.data('id'));
		password.attr('type', self.prop('checked') ? 'text' : 'password');
	});

	$('#myModal').on('shown.bs.modal', function () {
		$('#myInput').trigger('focus')
	});

	persistData.init(formLanguage);
});

function modal(title, body, callbeck, okLabel, cancelLabel) {
	let modalDiv = $('#sm-model');
	let modalBodyDiv;
	let modalSaveButton;
	let modalCancelButton;

	if (modalDiv.length == 0) {
		const modalDialogDiv = $('<div />');
		const modalContentDiv = $('<div />');
		const modalHeaderDiv = $('<div />');
		const modalTitleH5 = $('<h5 />');
		const modalFooterDiv = $('<div />');
		const modalCloseButton = $('<button />');
		const modalCloseSpan = $('<span />');
		modalDiv = $('<div />');
		modalBodyDiv = $('<div />');
		modalSaveButton = $('<button />');
		modalCancelButton = $('<button />');

		modalDiv.addClass('modal').attr({tabindex: -1, role: 'dialog', id: 'sm-model'}).append(modalDialogDiv);
		modalDialogDiv.addClass('modal-dialog').attr('role', 'document').append(modalContentDiv);
		modalContentDiv.addClass('modal-content').append(modalHeaderDiv).append(modalBodyDiv).append(modalFooterDiv);
		modalHeaderDiv.addClass('modal-header').append(modalTitleH5).append(modalCloseButton);
		modalTitleH5.addClass('modal-title').text(title);
		modalCloseButton.addClass('close').attr({type: 'button', 'data-dismiss': 'modal', 'area-label': language.translate('close')}).append(modalCloseSpan);
		modalCloseSpan.attr('aria-hidden', 'true').html('&times;');
		modalBodyDiv.addClass('modal-body').append(body);
		modalFooterDiv.addClass('modal-footer').append(modalSaveButton).append(modalCancelButton);
		modalSaveButton.addClass('btn btn-primary').attr({type: 'button', id: 'modalSaveBtn'}).text((typeof okLabel === 'string') ? okLabel : language.translate('ok'));
		modalCancelButton.addClass('btn btn-secondary').attr({type: 'button', id: 'modalCancelBtn', 'data-dismiss': 'modal'}).text((typeof cancelLabel === 'string') ? cancelLabel : language.translate('cancel'));

		$('body').append(modalDiv);
	} else {
		modalDiv.find('.modal-title').text(title);
		modalDiv.find('.modal-body').html(body);

		modalSaveButton = modalDiv.find('#modalSaveBtn');
		modalCancelButton = modalDiv.find('#modalCancelBtn');

		modalSaveButton.off('click').text((typeof okLabel === 'string') ? okLabel : language.translate('ok'));
		modalCancelButton.text((typeof cancelLabel === 'string') ? cancelLabel : language.translate('cancel'));
	}

	modalSaveButton.on('click', function(e) {
		e.preventDefault();
		if (callbeck(modalBodyDiv)) {
			modalDiv.modal('hide');
		}
	});

	modalDiv.modal('show');
}

function ajax(params) {
	let tokenKey = ((typeof params.route === 'object') ? params.route[0] : params.route).replace(/\./g, '-');
	tokenKey = tokenKey.split('-');
	tokenKey.pop();
	tokenKey = tokenKey.join('-');
	const idKey = tokenKey.charAt(0).toUpperCase() + tokenKey.slice(1);
	const tokenName = $('#tokenName'+idKey);
	const tokenValue = $('#tokenValue'+idKey);
	const ajaxObj = {
		url: (typeof params.route === 'object') ? Slim.Router.pathFor(params.route[0], params.route[1]) : Slim.Router.pathFor(params.route),
		type: params.method,
		dataType: 'json',
		data: (typeof params.fields === 'object') ? params.fields : {}
	};

	if (params.method != 'GET') {
		ajaxObj.data[tokenName.attr('name')] = tokenName.val();
		ajaxObj.data[tokenValue.attr('name')] = tokenValue.val();

		ajaxObj.data.ajaxRequestToken = tokenKey;
	}

	console.log('ajaxData', ajaxObj);

	if (typeof params.extend === 'object') {
		for (let i in params.extend) {
			ajaxObj[i] = params.extend[i];
		}
	}

	$.ajax(ajaxObj).done(function(data) {
		console.log(data.data);
		if (typeof data.data.access === 'boolean' && !data.data.access) {
			flashMessage('error', language.translate('dontHavePermissionForAction'));
			return false;
		}

		if (params.method != 'GET') {
			tokenName.attr('name', data.token[tokenKey].nameKey).val(data.token[tokenKey].name);
			tokenValue.attr('name', data.token[tokenKey].valueKey).val(data.token[tokenKey].value);
		}

		if (typeof data.data.pagination !== 'undefined') {
			paginationWrapper.html(data.data.pagination);
		}

		if (data.status) {
			params.success(data.data);
		} else {
			if (typeof params.error === 'function') {
				params.error(data.errors);
			}

			if (typeof data.errors[0] !== 'undefined') {
				flashMessage('error', data.errors[0]);
				return false;
			}

			for (let e in data.errors) {
				errorsHandler.setError((typeof data.errors[e] == 'array') ? data.errors[e][0] : data.errors[e], e);
			}
			errorsHandler.getErrors();
		}
	})
	.fail(function() {
		console.log("error");
	});

}

auth.hasPermission = function(secment, action) {
	return (this.isOwner) ? true : this.group.permissions[secment][action];
};

function flashMessage(type, msg, time) {
	const alertDiv = $('<div />');
	type = (type == 'error') ? 'danger' : type;

	alertDiv.addClass('text-center alert alert-'+type).attr('role', 'alert').text(msg);
	$('body').prepend(alertDiv);

	window.scrollTo(0, 0);

	if (time !== false) {
		setTimeout(function() {
			alertDiv.slideUp(500).remove();
		}, (typeof time === 'number') ? time : 10000);
	}
}

function cl(msg) {
	console.log(msg);
}