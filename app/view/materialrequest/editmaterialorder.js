Ext.define('FamilyDecoration.view.materialrequest.EditMaterialOrder', {
    extend: 'Ext.window.Window',
    alias: 'widget.materialrequest-editmaterialorder',
    requires: [
        'FamilyDecoration.model.StatementBill'
    ],
    modal: true,
    title: '添加',
    width: 700,
    height: 400,
    bodyPadding: 5,
    maximizable: true,
    layout: 'fit',
    project: undefined,
    order: undefined,
    isEdit: false,
    closable: false,
    callback: Ext.emptyFn,

    initComponent: function () {
        var me = this;

        me.setTitle(me.order ? '编辑' : '添加');

        function operationBeforeClose() {
            var warnMsg = '取消会将当前材料申请单所有内容删除，<br />确定要取消吗？';
            Ext.Msg.warning(warnMsg, function (btnId) {
                if ('yes' == btnId) {
                    ajaxDel('StatementBill', {
                        id: me.order.getId()
                    }, function (obj) {
                        showMsg('申请单已删除！');
                        me.close();
                    });
                }
            });
        }

        me.items = [
            {
                xtype: 'materialrequest-materialorder',
                project: me.project,
                order: me.order
            }
        ];

        me.tbar = [
            {
                xtype: 'button',
                icon: 'resources/img/material_request_add_small_item.png',
                text: '添加小项',
                handler: function () {
                    ajaxGet('StatementBill', 'getWithSupplier', {
                        id: me.order.getId()
                    }, function (obj) {
                        var data = obj.data[0];
                        if (data.supplierId) {
                            me.order = Ext.create('FamilyDecoration.model.StatementBill', data);
                            var materialOrderCt = me.down('materialrequest-materialorder');
                            var st = Ext.create('FamilyDecoration.store.SupplierMaterial', {
                                autoLoad: true,
                                proxy: {
                                    type: 'rest',
                                    url: './libs/api.php',
                                    extraParams: {
                                        action: 'SupplierMaterial.get',
                                        supplierId: me.order.get('supplierId')
                                    },
                                    reader: {
                                        type: 'json',
                                        root: 'data',
                                        totalProperty: 'total'
                                    }
                                }
                            });
                            var win = Ext.create('Ext.window.Window', {
                                title: me.order.get('supplier') + '供应商',
                                width: 500,
                                height: 400,
                                layout: 'fit',
                                modal: true,
                                items: [
                                    {
                                        xtype: 'gridpanel',
                                        autoScroll: true,
                                        dockedItems: [
                                            {
                                                xtype: 'pagingtoolbar',
                                                store: st,
                                                dock: 'bottom',
                                                displayInfo: true
                                            }
                                        ],
                                        selModel: {
                                            mode: 'SIMPLE'
                                        },
                                        selType: 'checkboxmodel',
                                        columns: [
                                            {
                                                text: '材料',
                                                flex: 1,
                                                align: 'center',
                                                dataIndex: 'name'
                                            }
                                        ],
                                        store: st
                                    }
                                ],
                                buttons: [
                                    {
                                        text: '添加',
                                        handler: function () {
                                            var grid = win.down('gridpanel'),
                                                recs = grid.getSelectionModel().getSelection(),
                                                failedMembers = [];
                                            Ext.each(recs, function (rec, index, self) {
                                                self[index] = {
                                                    billItemName: rec.get('name'),
                                                    unit: rec.get('unit'),
                                                    unitPrice: rec.get('price'),
                                                    billId: me.order.getId(),
                                                    amount: 0
                                                };
                                            });
                                            function addItems(recs) {
                                                var func = arguments.callee;
                                                if (recs.length > 0) {
                                                    var item = recs[0];
                                                    ajaxAdd('StatementBillItem', item, function (obj){
                                                        recs.splice(0, 1);
                                                        func(recs);
                                                    }, function (obj){
                                                        failedMembers.push(obj.data[0].billItemName);
                                                        recs.splice(0, 1);
                                                        func(recs);
                                                    });
                                                }
                                                else {
                                                    if (failedMembers.length <= 0) {
                                                        showMsg('添加成功！');
                                                        materialOrderCt.halfRefresh(true);
                                                    }
                                                    else {
                                                        Ext.Msg.error('以下几项添加失败\n' + failedMembers.join('<br />'));
                                                    }
                                                    win.close();
                                                }
                                            }
                                            addItems(recs);
                                        }
                                    },
                                    {
                                        text: '取消',
                                        handler: function () {
                                            win.close();
                                        }
                                    }
                                ]
                            });
                            win.show();
                        }
                        else {
                            showMsg('请先选择供应商!');
                        }
                    });
                }
            }
        ];

        me.buttons = [
            {
                text: '确定',
                handler: function () {
                    var ct = me.down('materialrequest-materialorder'),
                        frm = ct.getFrm();
                    ajaxUpdate('StatementBill', {
                        id: me.order.getId(),
                        payedTimes: frm.payedTimes.getValue(),
                        projectProgress: frm.projectProgress.getValue()
                    }, ['id'], function (obj){
                        showMsg('申购单更新成功！');
                        me.close();
                        me.callback();
                    });
                }
            },
            {
                text: '取消',
                hidden: me.isEdit,
                handler: function () {
                    operationBeforeClose();
                }
            }
        ];

        me.addListener({
            show: function (win, opts){
                var ct = win.down('materialrequest-materialorder');
                ct.refresh();
            }
        });

        this.callParent();
    }
});