Ext.define('FamilyDecoration.view.suppliermanagement.EditSupplierMaterial', {
    extend: 'Ext.window.Window',
    alias: 'widget.suppliermanagement-editsuppliermaterial',
    requires: [
        'FamilyDecoration.store.SupplierMaterial',
        'FamilyDecoration.store.WorkCategory'
    ],
    modal: true,
    title: '编辑供应商材料',
    width: 600,
    height: 400,
    bodyPadding: 5,
    maximizable: true,
    layout: 'fit',
    closable: false,

    supplier: undefined,
    callback: Ext.emptyFn,
    isDirty: false,

    initComponent: function () {
        var me = this,
            st = Ext.create('FamilyDecoration.store.SupplierMaterial', {
                autoLoad: false,
                proxy: {
                    url: './libs/api.php',
                    type: 'rest',
                    extraParams: {
                        action: 'SupplierMaterial.get',
                        supplierId: me.supplier.getId()
                    },
                    reader: {
                        type: 'json',
                        root: 'data',
                        totalProperty: 'total'
                    }
                }
            });

        me.refresh = function () {
            var grid = this.down('gridpanel'),
                st = grid.getStore(),
                selModel = grid.getSelectionModel(),
                selRec = selModel.getSelection()[0],
                index = st.indexOf(selRec);
            st.reload({
                callback: function (recs, ope, success) {
                    if (success) {
                        if (index != -1) {
                            selModel.deselectAll();
                            selModel.select(index);
                        }
                    }
                }
            });
        };

        me.tbar = [
            {
                text: '添加',
                name: 'add',
                icon: 'resources/img/add.png',
                handler: function () {
                    var grid = me.down('gridpanel'),
                        st = grid.getStore();
                    ajaxAdd('SupplierMaterial', {
                        name: '',
                        supplierId: me.supplier.getId(),
                        price: 0
                    }, function (obj) {
                        showMsg('添加成功！');
                        me.refresh();
                        me.isDirty = true;
                    });
                }
            }
        ];

        me.items = [
            {
                xtype: 'gridpanel',
                autoScroll: true,
                store: st,
                dockedItems: [
                    {
                        xtype: 'pagingtoolbar',
                        store: st,
                        dock: 'bottom',
                        displayInfo: true
                    }
                ],
                plugins: [
                    Ext.create('Ext.grid.plugin.CellEditing', {
                        clicksToEdit: 1,
                        listeners: {
                            edit: function (editor, e) {
                                Ext.suspendLayouts();

                                e.record.commit();
                                editor.completeEdit();

                                var updateObj = {
                                    id: e.record.getId()
                                };
                                updateObj[e.field] = e.value;
                                ajaxUpdate('SupplierMaterial', updateObj, 'id', function (obj) {
                                    showMsg('更新成功！');
                                    me.isDirty = true;
                                    me.refresh();
                                });

                                Ext.resumeLayouts();
                            },
                            validateedit: function (editor, e, opts) {
                                var rec = e.record;
                                if (e.field == 'amount' || e.field == 'referenceNumber' || e.field == 'price') {
                                    if (isNaN(e.value) || !/^-?\d+(\.\d+)?$/.test(e.value)) {
                                        return false;
                                    }
                                    else if (e.value == e.originalValue) {
                                        return false;
                                    }
                                }
                            }
                        }
                    })
                ],
                columns: [
                    {
                        xtype: 'actioncolumn',
                        width: 25,
                        items: [
                            {
                                icon: 'resources/img/delete.png',
                                tooltip: '删除',
                                handler: function (grid, rowIndex, colIndex) {
                                    var rec = grid.getStore().getAt(rowIndex);
                                    Ext.Msg.warning('确定要删除当前材料吗？', function (btnId) {
                                        if ('yes' == btnId) {
                                            ajaxDel('SupplierMaterial', {
                                                id: rec.getId()
                                            }, function (obj) {
                                                showMsg('删除成功！');
                                                me.refresh();
                                                me.isDirty = true;
                                            });
                                        }
                                    });
                                }
                            }
                        ]
                    },
                    {
                        text: '序号',
                        dataIndex: 'id',
                        flex: 0.7,
                        align: 'center'
                    },
                    {
                        text: '项目',
                        dataIndex: 'name',
                        editor: {
                            xtype: 'textfield',
                            allowBlank: false
                        },
                        flex: 1,
                        align: 'center'
                    },
                    {
                        text: '单位',
                        dataIndex: 'unit',
                        editor: {
                            xtype: 'textfield',
                            allowBlank: false
                        },
                        flex: 1,
                        align: 'center'
                    },
                    {
                        text: '数量',
                        dataIndex: 'amount',
                        editor: {
                            xtype: 'numberfield',
                            allowBlank: false
                        },
                        flex: 1,
                        align: 'center',
                        hidden: true // hide amount temporarily, coz we don't know the amount of specific material of one supplier'
                    },
                    {
                        text: '参考量',
                        dataIndex: 'referenceNumber',
                        flex: 1,
                        align: 'center'
                    },
                    {
                        text: '单价(元)',
                        dataIndex: 'price',
                        editor: {
                            xtype: 'numberfield',
                            allowBlank: false
                        },
                        flex: 1,
                        align: 'center'
                    },
                    {
                        text: '工种',
                        dataIndex: 'professionType',
                        editor: {
                            xtype: 'combobox',
                            editable: false,
                            allowBlank: false,
                            store: FamilyDecoration.store.WorkCategory,
                            displayField: 'name',
                            valueField: 'value'
                        },
                        flex: 1,
                        align: 'center',
                        renderer: function (val, meta, rec) {
                            if (val) {
                                return FamilyDecoration.store.WorkCategory.renderer(val);
                            }
                            else {
                                return '';
                            }
                        }
                    }
                ]
            }
        ];

        me.buttons = [
            {
                text: '关闭',
                handler: function () {
                    me.close();
                    if (me.isDirty) {
                        me.callback();
                    }
                }
            }
        ];

        me.addListener({
            show: function (win, opts) {
                win.refresh();
            }
        });

        this.callParent();
    }
});