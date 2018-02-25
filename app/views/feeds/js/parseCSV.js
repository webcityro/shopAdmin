function parseCSV() {
	this.json = {};

	this.parse = function(data, callbeck, limit) {
		var csv = Papa.parse(data, {
			delimiter: "",	// auto-detect
			newline: "",	// auto-detect
			header: false,
			dynamicTyping: false,
			preview: 0,
			encoding: "",
			worker: false,
			comments: false,
			step: undefined,
			complete: undefined,
			error: undefined,
			download: false,
			skipEmptyLines: false,
			chunk: undefined,
			fastMode: undefined,
			beforeFirstChunk: undefined,
			withCredentials: undefined
		});

		this.json = this.walk(csv.data, limit);

		callbeck(this.json);
	};

	this.walk = function(csv, limit, path) {
		var json = {};
		var x = 0;

		for (var i in csv) {
			var index = 'item_'+i;
			var thisPath = (!path) ? i : path+'/'+i;

			json[index] = {text: {value: i}, value: {value: (typeof csv[i] == 'string') ? csv[i] : ''}, structurePath: thisPath};

			if (typeof csv[i] == 'array' || typeof csv[i] == 'object') {
				var noOfChildren = (typeof csv[i] == 'array') ? csv[i].length : Object.keys(csv[i]).length;
 				json[index].children = this.walk(csv[i], (noOfChildren > 30) ? limit : noOfChildren, thisPath);
 			}

 			if (typeof limit != 'undefined' && limit <= x) {
 				return json;
 			}

 			x++;
		}
		return json;
	}
}