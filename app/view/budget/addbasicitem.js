Ext.define('FamilyDecoration.view.budget.AddBasicItem', {
	extend: 'Ext.window.Window',
	alias: 'widget.budget-addbasicitem',
	requires: ['FamilyDecoration.store.BasicItem', 'FamilyDecoration.store.BasicSubItem'],
	resizable: false,
	modal: true,

	title: '添加项目',
	width: 560,
	height: 360,
	layout: 'hbox',
	padding: 2,
	autoScroll: true,

	grid: null, // 预算表格

	initComponent: function () {
		var me = this;

		me.buttons = [{
			text: '添加',
			handler: function (){
				var mainGrid = me.items.items[0],
					mainSt = mainGrid.getStore(),
					subGrid = me.items.items[1],
					subSt = subGrid.getStore(),
					mainRec = mainGrid.getSelectionModel().getSelection()[0],
					subRecs = subGrid.getSelectionModel().getSelection(),
					grid = me.grid,
					budgets = grid.getStore().data.items,
					data = [], flag = false,
					// pos is used to record the position of basic main project in budget grid.
					pos = undefined,
					// number of basic main project
					numOfMain = 0,
					// number of basic sub project under corresponding main project
					numOfSub = 0,
					// itemCode of basic main project
					mainCode,
					// the base number used to wipe out decimal part.
					baseNumber = 100000000;

				if (mainRec && subRecs.length > 0) {

					for (var i = 0; i < budgets.length; i++) {
						// basic main project has itemId.
						if (budgets[i].raw.itemId) {
							numOfMain++;
							if (budgets[i].raw.itemId == mainRec.getId()) {
								flag = true;
								break;
							}
						}
					}

					if (flag) {
						// record position of basic main project in budget grid.
						pos = i;
						i++;
						while (budgets[i]) {
							if (budgets[i].get('parentId') == mainRec.getId() && budgets[i].get('itemUnit')) {
								numOfSub++;
							}
							i++;
						}
						for (i = 0; i < subRecs.length; i++) {
							data.push({
								itemCode: budgets[pos].get('itemCode') + '-' + (numOfSub+i+1),
								itemName: subRecs[i].get('subItemName'),
								itemUnit: subRecs[i].get('subItemUnit'),
								mainMaterialPrice: subRecs[i].get('mainMaterialPrice'),
								auxiliaryMaterialPrice: subRecs[i].get('auxiliaryMaterialPrice'),
								manpowerPrice: subRecs[i].get('manpowerPrice'),
								machineryPrice: subRecs[i].get('machineryPrice'),
								subItemId: subRecs[i].getId(),
								parentId: subRecs[i].get('parentId'),
								lossPercent: subRecs[i].get('mainMaterialPrice').add(subRecs[i].get('auxiliaryMaterialPrice')).mul(subRecs[i].get('lossPercent')),
								cost: subRecs[i].get('cost'),
								originalCost: subRecs[i].get('cost')
							});
						}
						grid.getStore().insert(pos + numOfSub + 1, data);
					}
					else {
						mainCode = getId(numOfMain + 1);
						// push data of basic main project if not finding it in budget grid.
						data.push(Ext.apply(mainRec.data, {
							itemCode: mainCode
						}));

						for (i = 0; i < subRecs.length; i++) {
							data.push({
								itemCode: mainCode + '-' + (i + 1),
								itemName: subRecs[i].get('subItemName'),
								itemUnit: subRecs[i].get('subItemUnit'),
								mainMaterialPrice: subRecs[i].get('mainMaterialPrice'),
								auxiliaryMaterialPrice: subRecs[i].get('auxiliaryMaterialPrice'),
								manpowerPrice: subRecs[i].get('manpowerPrice'),
								machineryPrice: subRecs[i].get('machineryPrice'),
								subItemId: subRecs[i].getId(),
								parentId: subRecs[i].get('parentId'),
								lossPercent: subRecs[i].get('mainMaterialPrice').add(subRecs[i].get('auxiliaryMaterialPrice')).mul(subRecs[i].get('lossPercent')),
								cost: subRecs[i].get('cost'),
								originalCost: subRecs[i].get('cost')
							});
						}

						data.push({
							itemName: '小计',
							itemUnit: undefined, // set undefined in order to distinguish from other records
							parentId: mainRec.getId()
						});

						var gridSt = grid.getStore(),
							lastRec = gridSt.last();
						if (lastRec && lastRec.get('itemCode') == 'S') {
							pos = gridSt.indexOf(lastRec) - 6;
							gridSt.insert(pos, data);
						}
						else {
							data.push({

							}, {
								itemCode: 'N',
								itemName: '工程直接费',
								itemUnit: '元',
								mainMaterialTotalPrice: ''
							}, {
								itemCode: 'O',
								itemName: '设计费3%',
								itemUnit: '元',
								mainMaterialTotalPrice: ''
							}, {
								itemCode: 'P',
								itemName: '效果图',
								itemUnit: '张',
								mainMaterialTotalPrice: ''
							}, {
								itemCode: 'Q',
								itemName: '5%管理费',
								itemUnit: '元',
								mainMaterialTotalPrice: ''
							}, {
								itemCode: 'R',
								itemName: '0%税金',
								itemUnit: '元',
								mainMaterialTotalPrice: 0.0
							}, {
								itemCode: 'S',
								itemName: '工程总造价',
								itemUnit: '元',
								mainMaterialTotalPrice: ''
							});
							gridSt.add(data);
						}

						showMsg('添加成功！');
					}

					me.close();
				}
				else {
					Ext.Msg.info('请选择要添加的项目！');
				}
			}
		}, {
			text: '取消',
			handler: function (){
				me.close();
			}
		}]

		me.items = [
			{
				xtype: 'gridpanel',
				title: '大类名称',
				header: false,
				height: 260,
				columns: [{
					text: '大类',
					dataIndex: 'itemName',
					flex: 1
				}],
				store: Ext.create('FamilyDecoration.store.BasicItem', {
					autoLoad: true
				}),
				flex: 1,
				autoScroll: true,
				listeners: {
					selectionchange: function (selModel, recs, opts){
						var mainGrid = this,
							mainSt = mainGrid.getStore(),
							subGrid = mainGrid.nextSibling(),
							subSt = subGrid.getStore(),
							rec = recs[0],
							mainId;
						if (rec) {
							mainId = rec.getId();
							subSt.getProxy().extraParams = {
								parentId: mainId
							};
							subSt.load();
						}
						else {
							subSt.removeAll();
						}
					}
				}
			}, 
			{
				xtype: 'gridpanel',
				title: '小类名称',
				header: false,
				autoScroll: true,
				height: 260,
				selType: 'checkboxmodel',
				columns: [{
					text: '项目',
					dataIndex: 'subItemName',
					flex: 1
				}],
				selModel: {
					mode: 'SIMPLE',
					allowDeselect: true
				},
				store: Ext.create('FamilyDecoration.store.BasicSubItem', {

				}),
				flex: 2
			}
		]

		this.callParent();
	}
})