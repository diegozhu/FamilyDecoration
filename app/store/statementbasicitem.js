Ext.define('FamilyDecoration.store.StatementBasicItem', {
	extend: 'Ext.data.Store',
	model: 'FamilyDecoration.model.StatementBasicItem',
	proxy: {
        type: 'rest',
        url: './libs/api.php',
        pageParam: false, //to remove param "page"
		startParam: false, //to remove param "start"
		limitParam: false, //to remove param "limit"
		noCache: false, //to remove param "_dc"
        reader: {
            type: 'json',
            root: 'data'
        },
        extraParams: {
        	action: 'StatementBasicItem.get',
        }
    }
});