Ext.define('FamilyDecoration.view.totalpropertymanagement.DateFilter', {
    extend: 'Ext.toolbar.Toolbar',
    alias: 'widget.totalpropertymanagement-datefilter',
    requires: [
        'FamilyDecoration.store.Account'
    ],
    layout: 'vbox',
    defaults: {
        width: '100%'
    },
    needBankAccount: false,
    filterFunc: Ext.emptyFn,

    initComponent: function () {
        var me = this;

        me._getRes = function (){
            var dateCt = me.getComponent('dateCt');
            return {
                startTime: dateCt.getComponent('startTime'),
                endTime: dateCt.getComponent('endTime'),
                scale: dateCt.getComponent('scale'),
                account: me.down('[name="account"]')
            };
        }

        // expose this function for external use
        me.isFiltered = function () {
            var resObj = me._getRes();
            return resObj.startTime.getValue() && resObj.endTime.getValue() && resObj.account.getValue() && resObj.scale.getValue();
        }

        var generateAccount = function (cfgFlag){
            var cfg = {
                xtype: 'combobox',
                displayField: 'name',
                valueField: 'id',
                name: 'account',
                editable: false,
                store: Ext.create('FamilyDecoration.store.Account', {
                    autoLoad: true
                }),
                queryMode: 'local',
                flex: 1,
                listeners: {
                    change: function (cmp, newVal, oldVal, opts){
                        var resObj = me._getRes();
                        if (me.isFiltered()) {
                            me.filterFunc(
                                resObj.startTime.getValue(), 
                                resObj.endTime.getValue(), 
                                resObj.account.findRecord('id', resObj.account.getValue()),
                                resObj.scale.getSubmitValue()
                            );
                        }
                    }
                }
            };
            return cfgFlag ? cfg : Ext.create('Ext.form.FieldContainer', {
                layout: 'hbox',
                width: '100%',
                items: [
                    cfg,
                    {
                        xtype: 'button',
                        width: 50,
                        text: '清空',
                        handler: function (){
                            var fct = this.ownerCt,
                                combo = fct.down('combobox');
                            combo.clearValue();
                        }
                    },
                    {
                        xtype: 'button',
                        width: 50,
                        text: '删除',
                        handler: function (){
                            var fct = this.ownerCt,
                                toolbar = fct.ownerCt;
                            toolbar.remove(fct);
                        }
                    }
                ]
            });
        }

        me.items = [
            {
                xtype: 'fieldcontainer',
                flex: 1,
                layout: 'hbox',
                itemId: 'dateCt',
                items: [
                    {
                        xtype: 'datefield',
                        flex: 1,
                        editable: false,
                        name: 'startTime',
                        itemId: 'startTime',
                        emptyText: '开始时间',
                        format: 'Y-m-d',
                        submitFormat: 'Y-m-d',
                        validator: function (val) {
                            var resObj = me._getRes();
                            if (val && resObj.endTime.getValue()) {
                                return true;
                            }
                            else if (!val && resObj.endTime.getValue()) {
                                return '开始时间和结束时间不能有一个为空';
                            }
                            else if (val && !resObj.endTime.getValue()) {
                                resObj.endTime.isValid();
                                return true;
                            }
                            else {
                                return true;
                            }
                        },
                        listeners: {
                            change: function (cmp, newVal, oldVal, opts){
                                var resObj = me._getRes();
                                if (me.isFiltered()) {
                                    me.filterFunc(
                                        resObj.startTime.getValue(), 
                                        resObj.endTime.getValue(), 
                                        resObj.account.findRecord('id', resObj.account.getValue()),
                                        resObj.scale.getSubmitValue()
                                    );
                                }
                            }
                        }
                    },
                    {
                        xtype: 'datefield',
                        flex: 1,
                        editable: false,
                        emptyText: '结束时间',
                        name: 'endTime',
                        itemId: 'endTime',
                        format: 'Y-m-d',
                        submitFormat: 'Y-m-d',
                        validator: function (val) {
                            var resObj = me._getRes();
                            if (val && resObj.startTime.getValue()) {
                                return true;
                            }
                            else if (!val && resObj.startTime.getValue()) {
                                return '开始时间和结束时间不能有一个为空';
                            }
                            else if (val && !resObj.startTime.getValue()) {
                                resObj.startTime.isValid();
                                return true;
                            }
                            else {
                                return true;
                            }
                        },
                        listeners: {
                            change: function (cmp, newVal, oldVal, opts){
                                var resObj = me._getRes();
                                if (me.isFiltered()) {
                                    me.filterFunc(
                                        resObj.startTime.getValue(), 
                                        resObj.endTime.getValue(), 
                                        resObj.account.findRecord('id', resObj.account.getValue()),
                                        resObj.scale.getSubmitValue()
                                    );
                                }
                            }
                        }
                    },
                    {
                        xtype: 'combobox',
                        displayField: 'name',
                        valueField: 'value',
                        itemId: 'scale',
                        editable: false,
                        store: Ext.create('Ext.data.Store', {
                            fields: ['name', 'value'],
                            data: [
                                {
                                    name: '年查询',
                                    value: 'Y'
                                },
                                {
                                    name: '月查询',
                                    value: 'M'
                                },
                                {
                                    name: '日查询',
                                    value: 'D'
                                }
                            ],
                            proxy: {
                                type: 'memory',
                                reader: {
                                    type: 'json'
                                }
                            }
                        }),
                        queryMode: 'local',
                        labelWidth: 65,
                        width: 200,
                        fieldLabel: '查询力度',
                        listeners: {
                            change: function (cmp, newVal, oldVal, opts){
                                var resObj = me._getRes();
                                if (me.isFiltered()) {
                                    me.filterFunc(
                                        resObj.startTime.getValue(), 
                                        resObj.endTime.getValue(), 
                                        resObj.account.findRecord('id', resObj.account.getValue()),
                                        resObj.scale.getSubmitValue()
                                    );
                                }
                            }
                        }
                    }
                ]
            },
            me.needBankAccount ? {
                xtype: 'fieldcontainer',
                flex: 1,
                layout: 'hbox',
                items: [
                    generateAccount(true),
                    {
                        xtype: 'button',
                        text: '清空',
                        hidden: true,
                        width: 50,
                        handler: function (){
                            var fct = this.ownerCt,
                                combo = fct.down('combobox');
                            combo.clearValue();
                        }
                    },
                    {
                        xtype: 'button',
                        text: '添加',
                        width: 50,
                        hidden: true,
                        handler: function (){
                            me.add(generateAccount());
                        }
                    }
                ]
            } : undefined,
        ];
        
        this.callParent();
    }
});