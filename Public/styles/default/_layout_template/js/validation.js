const validator = function() {
	this.multiLanguage = false;
	this.persistentData = false;
	this.errors = {};

	this.init = function({multiLanguage = false, persistentData = false}) {
		this.multiLanguage = multiLanguage;
		this.persistentData = persistentData;
	};

	this.validate = function() {};

	this.rules = {};
};
