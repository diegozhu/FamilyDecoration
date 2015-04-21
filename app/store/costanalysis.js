Ext.define('FamilyDecoration.store.CostAnalysis', {
	extend: 'Ext.data.Store',
	model: 'FamilyDecoration.model.CostAnalysis',
	proxy: {
		type: 'rest',
		url: './libs/costanalysis.php',
		reader: {
			type: 'json'
		},
		extraParams: {
			action: 'analysis'
		}
	}
});