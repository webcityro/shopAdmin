function language () {
	this.translate = function(key, params) {
		if (params) {
			var i = 1;
			var ret = languageJSON[key];

			for (var x in params) {
				ret = ret.replace('{s'+i+'}', params[x]);
				i++;
			}

			return ret;
		}

		return (languageJSON[key]) ? languageJSON[key] : '';
	};
}