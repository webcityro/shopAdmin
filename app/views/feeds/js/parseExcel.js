function parseExcel() {
	this.parse = function(data, callbeck, limit) {
		var dataArr = new Uint8Array(data);
		var binaryArr = [];
		var json = {};

		for (var i = 0; i < dataArr.length; i++) {
			binaryArr[i] = String.fromCharCode(dataArr[i]);
		}

		var binaryStr = binaryArr.join('');
		var excel = XLSX.read(binaryStr,  {type: 'binary', cellDates: true, cellStyles: true});
		console.log(excel);
		excel.SheetNames.forEach(function(e) {
			var worksheet = excel.Sheets[e];
			var thisPath = e;
			json['sheet_'+e] = {text: {value: e}, structurePath: e, children: {}};

			for (var i in worksheet) {
				if(i[0] === '!') continue;

				var column = i.substr(0, 1);
				var row = i.substr(1);
				var index = 'row_'+row;

				if (typeof json['sheet_'+e].children[index] == 'undefined') {
					json['sheet_'+e].children[index] = {text: {value: 'Randul '+row}, children: {}, structurePath: e+'/'+row};
				}

				json['sheet_'+e].children[index].children['col_'+column] = {text: {value: column}, value: {value: worksheet[i].v}, structurePath: e+'/'+row+'/'+column};

				if (limit <= row-1) {
					break;
				}
			}
		});
		callbeck(json);
	};
}