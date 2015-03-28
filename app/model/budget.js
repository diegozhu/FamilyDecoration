Ext.define('FamilyDecoration.model.Budget', {
	extend: 'Ext.data.Model',
	fields: [
		{name: 'budgetId', type: 'string'},
		{name: 'custName', type: 'string'},
		{name: 'projectName', type: 'string'},
		{name: 'areaSize', type: 'string'},
		{name: 'totalFee', type: 'string'},
		{name: 'comments', type: 'string'}
	],
	idProperty: 'budgetId'
});