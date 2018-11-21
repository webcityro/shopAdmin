function languageObject () {
	this.languageJSON = {};

	this.set = function(json) {
		this.languageJSON = json;
	};

	this.translate = function(key, params) {
		if (params) {
			var i = 1;
			var ret = this.languageJSON[key];

			for (var x in params) {
				ret = ret.replace('{s'+i+'}', params[x]);
				i++;
			}

			return ret;
		}

		return (this.languageJSON[key]) ? this.languageJSON[key] : '';
	};
}