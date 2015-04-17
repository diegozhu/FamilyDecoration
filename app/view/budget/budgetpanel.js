Ext.define('FamilyDecoration.view.budget.BudgetPanel', {
	extend: 'Ext.panel.Panel',
	alias: 'widget.budget-budgetpanel',
	width: '100%',
	height: '100%',
	layout: 'vbox',
	requires: ['Ext.form.FieldContainer', 'FamilyDecoration.store.BudgetItem', 'FamilyDecoration.view.budget.EditHeader',
			   'FamilyDecoration.view.budget.AddBasicItem', 'FamilyDecoration.view.budget.AddExistedItem',
			   'FamilyDecoration.view.budget.HistoryBudget'],

	title: '预算面板',
	header: false,
	html: '<iframe id="exportFrame"  src="javascript:void(0);" style="display:none"></iframe>',

	// indicator: tells us if this is for preview or not
	isForPreview: false,

	// indicator: tells us if there is an budget existed in current panel
	budgetId: undefined,

	// obj: budgetId, custName, projectName
	loadBudget: function (obj){
		var cmp = this,
			custNameField = cmp.down('[name="displayfield-custName"]'),
			projectNameField = cmp.down('[name="displayfield-projectName"]');
		obj.custName && custNameField.setValue(obj.custName);
		obj.projectName && projectNameField.setValue(obj.projectName);
		cmp.initBtn();
		if (obj.budgetId) {
			cmp.budgetId = obj.budgetId;
			cmp.refresh();
		}
	},

	initBtn: function (rec){
		var panel = this,
			addNewBtn = panel.down('[name="button-addNewItem"]'),
			addSmallBtn = panel.down('[name="button-addSmallItemToBigItem"]'),
			delItemBtn = panel.down('[name="button-deleteItem"]');
		addNewBtn.isHidden() && addNewBtn.show();
		addSmallBtn.isHidden() && addSmallBtn.show();
		delItemBtn.isHidden() && delItemBtn.show();
		if (rec) {
			if (rec.get('basicItemId') && !rec.get('basicSubItemId')) {
    			addSmallBtn.enable();
    		}
    		else {
    			addSmallBtn.disable();
    		}
    		delItemBtn.setDisabled(!rec.get('isEditable'));
		}
		else {
			addSmallBtn.disable();
			delItemBtn.disable();
		}
	},

	initialize: function (){
		var cmp = this,
			grid = cmp.getComponent('gridpanel-budgetContent'),
			st = grid.getStore(),
			custNameField = cmp.down('[name="displayfield-custName"]'),
			projectNameField = cmp.down('[name="displayfield-projectName"]'),
			addNewBtn = cmp.down('[name="button-addNewItem"]'),
			addSmallBtn = cmp.down('[name="button-addSmallItemToBigItem"]'),
			delItemBtn = cmp.down('[name="button-deleteItem"]');
		st.removeAll();
		cmp.budgetId = undefined;
		custNameField.setValue('');
		projectNameField.setValue('');
		addNewBtn.hide();
		addSmallBtn.hide();
		delItemBtn.hide();
	},

	refresh: function (){
		var panel = this,
			grid = panel.getComponent('gridpanel-budgetContent'),
			st = grid.getStore();
		st.load({
			params: {
				budgetId: panel.budgetId
			},
			callback: function (recs, ope, success){
				if (success) {
					
				}
			}
		});
	},

	initComponent: function (){
		var me = this;

		me.tbar = [
			{
				text: '预算头',
				tooltip: '新建或编辑预算头部信息',
				name: 'button-addBudget',
				handler: function (){
					if (me.budgetId) {
						Ext.Ajax.request({
							url: './libs/budget.php?action=view',
							method: 'GET',
							params: {
								budgetId: me.budgetId
							},
							callback: function (opts, success, res){
								if (success) {
									var obj = Ext.decode(res.responseText);
									var win = Ext.create('FamilyDecoration.view.budget.EditHeader', {
										budgetPanel: me,
										budget: obj[0]
									});
									win.show();
								}
							}
						})
					}
					else {
						var win = Ext.create('FamilyDecoration.view.budget.EditHeader', {
							budgetPanel: me
						});
						win.show();
					}
				}
			},
			{
				text: '新项',
				tooltip: '添加新的基础大项',
				name: 'button-addNewItem',
				hidden: true,
				handler: function (){
					var win = Ext.create('FamilyDecoration.view.budget.AddBasicItem', {
						grid: me.getComponent('gridpanel-budgetContent'),
						budgetId: me.budgetId
					});

					win.show();
				}
			},
			{
				text: '补充',
				tooltip: '为已有大项添加小项',
				name: 'button-addSmallItemToBigItem',
				disabled: true,
				hidden: true,
				handler: function (){
					var grid = me.getComponent('gridpanel-budgetContent'),
						rec = grid.getSelectionModel().getSelection()[0];
					if (rec) {
						if (rec.get('basicItemId') && !rec.get('basicSubItemId')) {
							var win = Ext.create('FamilyDecoration.view.budget.AddExistedItem', {
								grid: me.getComponent('gridpanel-budgetContent'),
								budgetId: me.budgetId,
								bigItem: rec
							});

							win.show();
						}
						else {
							showMsg('选择项不是大项！');
						}
					}
					else {
						showMsg('请选择大项！');
					}
				}
			},
			{
				text: '删除',
				tooltip: '删除预算项目',
				name: 'button-deleteItem',
				disabled: true,
				hidden: true,
				handler: function (){
					var grid = me.getComponent('gridpanel-budgetContent'),
						rec = grid.getSelectionModel().getSelection()[0];
					if (rec && rec.get('isEditable')) {
						Ext.Msg.warning('确定要删除选中项目吗？', function (btnId){
							if (btnId == 'yes') {
								Ext.Ajax.request({
									url: 'libs/budget.php?action=delItem',
									method: 'POST',
									params: {
										budgetItemId: rec.getId()
									},
									callback: function (opts, success, res){
										if (success) {
											var obj = Ext.decode(res.responseText);
											if (obj.status == 'successful') {
												showMsg('删除成功！');
												me.refresh();
											}
											else {
												showMsg(obj.errMsg);
											}
										}
									}
								})
							}
						})
					}
					else {
						showMsg('未选择项目或者项目不可编辑！');
					}
				}
			},
			{
				text: '历史',
				tooltip: '查看、加载历史预算',
				name: 'button-historyBudget',
				handler: function (){
					var win = Ext.create('FamilyDecoration.view.budget.HistoryBudget', {
						budgetPanel: me
					});
					win.show();
				}
			}
		];

		me.bbar = [
			{
				text: '初始化',
				tooltip: '清空当前预算信息，用于已经加载了预算情况后进行新建预算',
				name: 'button-initialize',
				handler: function (){
					me.initialize();
				}
			},
			{
				text: '打印预算',
				handler: function (){
					if (me.budgetId) {
						var win = window.open('./fpdf/index2.php?action=view&budgetId=' + me.budgetId,'打印','height=650,width=700,top=10,left=10,toolbar=no,menubar=no,scrollbars=no,resizable=yes,location=no,status=no');
						win.print();
					}
					else {
						showMsg('没有预算！');
					}
				}
			},
			{
				text: '导出预算',
				handler: function (){
					if (me.budgetId) {
						var exportFrame = document.getElementById('exportFrame');
						exportFrame.src = './fpdf/index2.php?budgetId=' + me.budgetId;
					}
					else {
						showMsg('没有预算！');
					}
				}
			},
			{
				text: '预览预算',
				handler: function (){
					if (me.budgetId) {
						var win = window.open('./fpdf/index2.php?action=view&budgetId=' + me.budgetId,'打印','height=650,width=700,top=10,left=10,toolbar=no,menubar=no,scrollbars=no,resizable=yes,location=no,status=no');
					}
					else {
						showMsg('没有预算！');
					}
				}
			}
		];

		me.columnRenderer = function (val, meta, rec, rowIndex, colIndex, st, view){
			// 小项
			if (rec.get('basicSubItemId') && !rec.get('basicItemId')) {
				return val;
			}
			// 大项
			else if (!rec.get('basicSubItemId') && rec.get('basicItemId')) {
				return '';
			}
			// 空项
			else if (rec.get('itemName') == '') {
				return '';
			}
			// 特殊项
			else if ('NOPQRS'.indexOf(rec.get('itemCode')) != -1 && rec.get('itemCode') != '') {
				switch (colIndex) {
					case 3:
					if ('NS'.indexOf(rec.get('itemCode')) != -1) {
						val = '';
					}
					break;
					// 主单
					case 4:
					// 辅单
					case 6:
					// 辅总
					case 7:
					// 人单
					case 8:
					// 人总
					case 9:
					// 机单
					case 10:
					// 机总
					case 11:
					// 损耗
					case 12:
					// 人成本
					case 14:
					// 主成本
					case 15:
					val = '';
					break;
				}
				return val;
			}
			// 小计
			else if (!rec.get('basicItemId') && !rec.get('basicSubItemId') && rec.get('itemCode') == '') {
				switch (colIndex) {
					// 数量
					case 3:
					// 主单
					case 4:
					// 辅单
					case 6:
					// 人单
					case 8:
					// 机单
					case 10:
					// 损耗
					case 12:
					// 人成本
					case 14:
					// 主成本
					case 15:
					val = '';
					break;
				}
				return val;
			}
			else {
				return '';
			}
		}

		me.items = [
			{
				xtype: 'fieldcontainer',
				layout: 'hbox',
				width: '100%',
				height: 60,
				items: [
					{
						width: 80,
						height: 60,
						xtype: 'image',
						margin: '0 0 0 250',
						src: './resources/img/logo.jpg'
					},
					{
						xtype: 'displayfield',
						margin: '0 0 0 20',
						value: '佳诚装饰室内装修装饰工程&nbsp;预算单',
						hideLabel: true,
						fieldStyle: {
							fontFamily: '黑体',
							fontSize: '24px',
							lineHeight: '60px'
						},
						width: 700
					}
				]
			},
			{
				xtype: 'fieldcontainer',
				layout: {
					type: 'hbox'
				},
				items: [{
					xtype: 'displayfield',
					fieldLabel: '客户名称',
					name: 'displayfield-custName',
					flex: 1
				}, {
					xtype: 'displayfield',
					fieldLabel: '工程地址',
					name: 'displayfield-projectName',
					flex: 1
				}],
				width: '100%',
				flex: 1
			},
			{
				xtype: 'gridpanel',
				header: false,
				autoScroll: true,
				itemId: 'gridpanel-budgetContent',
				cls: 'gridpanel-budgetContent',
				width: '100%',
				flex: 15,
				plugins: [
					Ext.create('Ext.grid.plugin.CellEditing', {
			            clicksToEdit: 1,
			            listeners: {
			            	beforeedit: function (editor, e) {
			            		var rec = e.record;
			            		if (e.field == 'itemAmount') {
									if (rec.get('basicSubItemId')) {
										return true;
									}
									// 效果图编辑数量
									else if (rec.get('itemCode') == 'P') {
										return true;
									}
									// 设计费百分比
									else if (rec.get('itemCode') == 'O') {
										return true;
									}
									// 税金百分比
									else if (rec.get('itemCode') == 'R') {
										return true;
									} 
									else {
										return false;
									}
			            		}
			            		else if (e.field == 'remark') {
			            			return false;
			            		}
			            	},
			            	edit: function (editor, e){
			            		Ext.suspendLayouts();

			            		var rec = e.record,
			            			field = e.field;

			            		if (field == 'itemAmount') {
			            			Ext.Ajax.request({
				            			url: './libs/budget.php?action=editItem',
				            			method: 'POST',
				            			params: {
				            				budgetItemId: rec.getId(),
				            				itemAmount: rec.get('itemAmount')
				            			},
				            			callback: function (opts, success, res){
				            				if (success) {
				            					var obj = Ext.decode(res.responseText);
				            					if (obj.status == 'successful') {
				            						showMsg('编辑成功！');
				            					}
				            					else {
				            						showMsg(obj.errMsg);
				            					}
				            				}
				            			}
				            		});
			            		}
			            		else if (field == 'remark') {
			            			// todo
			            		}

			            		rec.commit();

			            		me.refresh();

			            		Ext.resumeLayouts();
			            	},
			            	validateedit: function (editor, e, opts){
			            		var rec = e.record;
			            		if (isNaN(e.value) || !/^\d+(\.\d+)?$/.test(e.value) ){
			            			return false;
			            		}
			            		else if (e.value == e.originalValue) {
			            			return false;
			            		}
			            	}
			            }
			        })
				],
				store: Ext.create('FamilyDecoration.store.BudgetItem'),
				columns: [
			        {
			        	text: '编号',
			        	dataIndex: 'itemCode',
			        	flex: 0.5,
	                	draggable: false,
	                	align: 'center',
	                	sortable: false,
	                	menuDisabled: true
			        },
			        {
			        	text: '名称',
			        	dataIndex: 'itemName',
			        	flex: 0.8,
	                	draggable: false,
	                	align: 'center',
	                	sortable: false,
	                	menuDisabled: true
			        },
			        {
			        	text: '单位', 
			        	dataIndex: 'itemUnit',
			        	flex: 0.5,
	                	draggable: false,
	                	align: 'center',
	                	sortable: false,
	                	menuDisabled: true
			        },
			        {
			        	text: '数量',
			        	flex: 0.5, 
			        	dataIndex: 'itemAmount',
	                	draggable: false,
	                	align: 'center',
	                	editor: me.isForPreview ? null : {
	                		xtype: 'textfield',
	                		allowBlank: false,
	                		maskRe: /[\d\.]/
	                	},
	                	sortable: false,
	                	menuDisabled: true,
	                	renderer: me.columnRenderer
			        },
			        {
			        	text: '主材',
			        	menuDisabled: true,
			        	columns: [
			        		{
			        			text: '单价',
			        			dataIndex: 'mainMaterialPrice',
			        			// flex: 0.5,
			        			width: 60,
	                			draggable: false,
	                			align: 'center',
	                			sortable: false,
	                			menuDisabled: true,
	                			renderer: me.columnRenderer
			        		},
			        		{
			        			text: '总价',
			        			// flex: 0.5,
			        			width: 59,
	                			draggable: false,
	                			align: 'center',
	                			sortable: false,
	                			menuDisabled: true,
	                			dataIndex: 'mainMaterialTotalPrice',
	                			renderer: me.columnRenderer
			        		}
			        	],
	                	draggable: false,
	                	align: 'center'
			        },
			        {
			        	text: '辅材',
			        	menuDisabled: true,
			        	columns: [
			        		{
			        			text: '单价',
			        			dataIndex: 'auxiliaryMaterialPrice',
			        			// flex: 0.5,
			        			width: 60,
	                			draggable: false,
	                			align: 'center',
	                			sortable: false,
	                			menuDisabled: true,
	                			renderer: me.columnRenderer
			        		},
			        		{
			        			text: '总价',
			        			// flex: 0.5,
			        			width: 59,
	                			draggable: false,
	                			align: 'center',
	                			sortable: false,
	                			menuDisabled: true,
	                			dataIndex: 'auxiliaryMaterialTotalPrice',
	                			renderer: me.columnRenderer
			        		}
			        	],
	                	draggable: false,
	                	align: 'center'
			        },
			        {
			        	text: '人工',
			        	menuDisabled: true,
			        	columns: [
			        		{
			        			text: '单价',
			        			dataIndex: 'manpowerPrice',
			        			// flex: 0.5,
			        			width: 60,
	                			draggable: false,
	                			align: 'center',
	                			sortable: false,
	                			menuDisabled: true,
	                			renderer: me.columnRenderer
			        		},
			        		{
			        			text: '总价',
			        			// flex: 0.5,
			        			width: 59,
	                			draggable: false,
	                			align: 'center',
	                			sortable: false,
	                			menuDisabled: true,
	                			dataIndex: 'manpowerTotalPrice',
	                			renderer: me.columnRenderer
			        		}
			        	],
	                	draggable: false,
	                	align: 'center'
			        },
			        {
			        	text: '机械',
			        	menuDisabled: true,
			        	columns: [
			        		{
			        			text: '单价',
			        			dataIndex: 'machineryPrice',
			        			// flex: 0.5,
			        			width: 60,
	                			draggable: false,
	                			align: 'center',
	                			sortable: false,
	                			menuDisabled: true,
	                			renderer: me.columnRenderer
			        		},
			        		{
			        			text: '总价',
			        			// flex: 0.5,
			        			width: 59,
	                			draggable: false,
	                			align: 'center',
	                			sortable: false,
	                			menuDisabled: true,
	                			dataIndex: 'machineryTotalPrice',
	                			renderer: me.columnRenderer
			        		}
			        	],
	                	draggable: false,
	                	align: 'center'
			        },
			        {
			        	text: '损耗',
			        	menuDisabled: true,
			        	columns: [
			        		{
			        			text: '单价',
			        			dataIndex: 'lossPercent',
			        			// flex: 0.5,
			        			width: 60,
	                			draggable: false,
	                			align: 'center',
	                			sortable: false,
	                			menuDisabled: true,
	                			renderer: me.columnRenderer
			        		}
			        	],
	                	draggable: false,
	                	align: 'center'
			        },
			        {
			        	text: '备注',
			        	flex: 1.7,
			        	draggable: false,
			        	align: 'center',
			        	dataIndex: 'remark',
			        	sortable: false,
			        	editor: me.isForPreview ? null : {
	                		xtype: 'textarea'
	                	},
	                	menuDisabled: true
			        },
			        {
			        	text: '成本',
			        	hidden: User.isAdmin() ? false : true,
			        	draggable: false,
			        	align: 'center',
			        	menuDisabled: true,
			        	columns: [
			        		{
			        			text: '人工',
			        			dataIndex: 'manpowerCost',
			        			width: 60,
			        			draggable: false,
			        			align: 'center',
			        			sortable: false,
			        			menuDisabled: true,
			        			renderer: me.columnRenderer
			        		},
			        		{
			        			text: '主材',
			        			dataIndex: 'mainMaterialCost',
			        			width: 59,
			        			draggable: false,
			        			align: 'center',
			        			sortable: false,
			        			menuDisabled: true,
			        			renderer: me.columnRenderer
			        		}
			        	]
			        }
			    ],
			    listeners: {
			    	selectionchange: function (cmp, sels, opts){
			    		var rec = sels[0];
			    		me.initBtn(rec);
			    	},
			    	afterrender: function(grid, opts) {
						var view = grid.getView();
						var tip = Ext.create('Ext.tip.ToolTip', {
							target: view.el,
							delegate: view.cellSelector,
							trackMouse: true,
							renderTo: Ext.getBody(),
							listeners: {
								beforeshow: function(tip) {
									var gridColumns = view.getGridColumns();
									var column = gridColumns[tip.triggerElement.cellIndex];
									var val = view.getRecord(tip.triggerElement.parentNode).get(column.dataIndex);
									if (val) {
										val.replace && (val = val.replace(/\n/g, '<br />'));
										tip.update(val);
									} 
									else {
										return false;
									}
								}
							}
						});
                    }
			    }
			}
		];

		me.callParent();
	}
});