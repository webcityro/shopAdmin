const unitsOfMeasurement = {
	name: $('#name'),
	abbreviation: $('#abbreviation'),
	value: $('#value'),
	type: $('#type'),

	init: () => {
		validation.init({
			multiLanguage: true,
			persistentData: true
		});

		validation.validate([
			{
				label: language.translate('formLabelTheName'),
				field: this.name
			}
		]);
	}
};

unitsOfMeasurement.init();