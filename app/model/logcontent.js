Ext.define('FamilyDecoration.model.LogContent', {
	extend: 'Ext.data.Model',
	fields: [
        'id',
        {name: 'standardPlan', type: 'string'},
        {name: 'practicalAccomplishment', type: 'string'},
        {name: 'difference', type: 'string'},
        {name: 'selfPlan', type: 'string'},
        {name: 'summarizedLog', type: 'string'},
        {name: 'comments', type: 'string'},
        {name: 'day', type: 'string', mapping: 'd'}
    ],
    idProperty: 'id'
});