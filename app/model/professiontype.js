Ext.define('FamilyDecoration.model.ProfessionType', {
	extend: 'Ext.data.Model',
	fields: [
		'id',
		{name: 'name', type: 'string'}, // plaster
		{name: 'cname', type: 'string'}, // 泥工
		{name: 'value', type: 'string'}, // 0001
		{name: 'createTime', type: 'string'},
		{name: 'updateTime', type: 'string'},
		{name: 'isDeleted', type: 'string'}
	],
	idProperty: 'id'
});