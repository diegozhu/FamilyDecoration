Ext.define('FamilyDecoration.view.mylog.LogContent', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.mylog-logcontent',
    layout: 'vbox',
    title: '日志内容',
    defaults: {
        width: '100%'
    },
    requires: [
        'FamilyDecoration.store.LogContent'
    ],

    renderMode: undefined, // market, design, undefined
    checkMode: undefined,
    staff: undefined, // staff record, only needed when in checking mode.

    initComponent: function () {
        var me = this;

        me.rerenderIndicatorCt = function (mode) {
            var indicatorCt = null,
                items = me.items.items;
            if (mode == 'market') {
                indicatorCt = {
                    xtype: 'container',
                    height: 24,
                    layout: 'hbox',
                    defaults: {
                        xtype: 'fieldcontainer',
                        height: '100%',
                        flex: 1,
                        margin: '0 2 0 0'
                    },
                    items: [
                        {
                            layout: 'hbox',
                            name: 'fieldcontainer-marketPlan',
                            defaults: {
                                xtype: 'textfield',
                                labelWidth: 32,
                                width: 80,
                                margin: '0 2 0 0',
                                readOnly: true
                            },
                            items: [
                                {
                                    xtype: 'displayfield',
                                    hideLabel: true,
                                    value: '<strong>计划:</strong>',
                                    width: 40
                                },
                                {
                                    fieldLabel: '电销',
                                    name: 'telemarketing'
                                },
                                {
                                    fieldLabel: '到店',
                                    name: 'companyVisit'
                                },
                                {
                                    fieldLabel: '定金',
                                    name: 'deposit'
                                },
                                {
                                    fieldLabel: '扫楼',
                                    name: 'buildingSwiping'
                                }
                            ]
                        },
                        {
                            layout: 'hbox',
                            name: 'fieldcontainer-marketAccomplishment',
                            defaults: {
                                xtype: 'textfield',
                                labelWidth: 32,
                                width: 80,
                                margin: '0 4 0 0',
                                readOnly: true
                            },
                            items: [
                                {
                                    xtype: 'displayfield',
                                    hideLabel: true,
                                    value: '<strong>完成:</strong>',
                                    width: 40
                                },
                                {
                                    fieldLabel: '电销',
                                    name: 'telemarketing'
                                },
                                {
                                    fieldLabel: '到店',
                                    name: 'companyVisit'
                                },
                                {
                                    fieldLabel: '定金',
                                    name: 'deposit'
                                },
                                {
                                    fieldLabel: '扫楼',
                                    name: 'buildingSwiping'
                                }
                            ]
                        }
                    ]
                };
            }
            else if (mode == 'design') {
                indicatorCt = {
                    xtype: 'container',
                    height: 24,
                    layout: 'hbox',
                    defaults: {
                        xtype: 'fieldcontainer',
                        height: '100%',
                        flex: 1,
                        margin: '0 2 0 0'
                    },
                    items: [
                        {
                            layout: 'hbox',
                            name: 'fieldcontainer-designPlan',
                            defaults: {
                                xtype: 'textfield',
                                labelWidth: 45,
                                width: 90,
                                margin: '0 2 0 0',
                                readOnly: true
                            },
                            items: [
                                {
                                    xtype: 'displayfield',
                                    hideLabel: true,
                                    value: '<strong>计划:</strong>',
                                    width: 40
                                },
                                {
                                    fieldLabel: '签单额',
                                    name: 'signedBusinessNumber'
                                },
                                {
                                    fieldLabel: '定金率',
                                    name: 'depositRate'
                                }
                            ]
                        },
                        {
                            layout: 'hbox',
                            name: 'fieldcontainer-designAccomplishment',
                            defaults: {
                                xtype: 'textfield',
                                labelWidth: 45,
                                width: 90,
                                margin: '0 2 0 0',
                                readOnly: true
                            },
                            items: [
                                {
                                    xtype: 'displayfield',
                                    hideLabel: true,
                                    value: '<strong>完成:</strong>',
                                    width: 40
                                },
                                {
                                    fieldLabel: '签单额',
                                    name: 'signedBusinessNumber'
                                },
                                {
                                    fieldLabel: '定金率',
                                    name: 'depositRate'
                                }
                            ]
                        }
                    ]
                }
            }

            Ext.suspendLayouts();
            if (indicatorCt) {
                if (items[0].xtype == 'container') {
                    me.remove(items[0]);
                }
                me.insert(0, indicatorCt);
            }
            else {
                if (items[0].xtype == 'container') {
                    me.remove(items[0]);
                }
            }
            Ext.resumeLayouts(true);
        }

        me.rerenderGrid = function (mode) {
            var items = me.items.items,
                grid, cols,
                st = Ext.create('FamilyDecoration.store.LogContent', {
                    autoLoad: false
                });
            for (var i = 0; i < items.length; i++) {
                var item = items[i];
                if (item.xtype == 'gridpanel') {
                    grid = item;
                    break;
                }
            }
            if (mode == 'market') {
                cols = [
                    {
                        text: '日期',
                        dataIndex: 'day',
                        flex: 0.5
                    },
                    {
                        text: '规范计划',
                        flex: 1
                    },
                    {
                        text: '完成情况',
                        flex: 1
                    },
                    {
                        text: '相差',
                        flex: 1
                    },
                    {
                        text: '个人计划',
                        flex: 1
                    },
                    {
                        text: '总结日志',
                        flex: 1
                    },
                    {
                        text: '评价',
                        flex: 1
                    }
                ];
            }
            else {
                cols = [
                    {
                        text: '日期',
                        dataIndex: 'day',
                        flex: 0.5
                    },
                    {
                        text: '个人计划',
                        flex: 1
                    },
                    {
                        text: '总结日志',
                        flex: 1
                    },
                    {
                        text: '评价',
                        flex: 1
                    }
                ];
            }
            Ext.suspendLayouts();
            grid.reconfigure(st, cols);
            Ext.resumeLayouts(true);
        }

        function refreshIndicator(rec) {
            var planCt, accomplishmentCt;
            
            if (me.renderMode == 'market') {
                planCt = me.down('[name="fieldcontainer-marketPlan"]');
                accomplishmentCt = me.down('[name="fieldcontainer-marketAccomplishment"]');
            }
            else if (me.renderMode == 'design') {
                planCt = me.down('[name="fieldcontainer-designPlan"]');
                accomplishmentCt = me.down('[name="fieldcontainer-designAccomplishment"]');
            }

            function goThroughData(obj) {
                Ext.each(planCt.items.items, function (item, index, self) {
                    if (item.xtype == 'textfield') {
                        item.setValue(obj ? obj['plan'][item.name] : '');
                    }
                });
                Ext.each(accomplishmentCt.items.items, function (item, index, self) {
                    if (item.xtype == 'textfield') {
                        item.setValue(obj ? obj['accomplishment'][item.name] : '');
                    }
                });
            }

            if (rec) {
                ajaxGet('LogList', 'getIndicator', {
                    name: me.checkMode ? me.staff.get('name') : User.getName(),
                    year: rec.get('year'),
                    month: rec.get('month'),
                    mode: me.renderMode
                }, function (obj) {
                    goThroughData(obj);
                })
            }
            else {
                goThroughData();
            }
        }

        function refreshGrid (rec){
            var grid = me.getComponent('gridpanel-logContent'),
                st = grid.getStore();
            if (rec) {
                st.reload();
            }
            else {
                st.removeAll();
            }
        }

        me.refresh = function (rec) {
            if (me.renderMode == 'market' || me.renderMode == 'design') {
                refreshIndicator(rec);
            }
            refreshGrid(rec);
        }

        me.items = [
            {
                xtype: 'gridpanel',
                flex: 9,
                name: 'gridpanel-logContent',
                itemId: 'gridpanel-logContent',
                columns: {
                    defaults: {
                        flex: 1
                    },
                    items: [
                        {
                            text: '日期'
                        },
                        {
                            text: '个人计划'
                        },
                        {
                            text: '总结日志'
                        },
                        {
                            text: '评价'
                        }
                    ]
                },
                bbar: [
                    {
                        text: '个人计划',
                        icon: 'resources/img/sheet.png',
                        handler: function () {

                        }
                    },
                    {
                        text: '总结日志',
                        icon: 'resources/img/summary.png',
                        handler: function () {

                        }
                    },
                    {
                        text: '评价',
                        hidden: !me.checkMode,
                        icon: 'resources/img/comment-new.png',
                        handler: function () {

                        }
                    }
                ]
            }
        ];

        me.addListener('afterrender', function (cmp, opts) {
            me.rerenderIndicatorCt(me.renderMode);
            me.rerenderGrid(me.renderMode);
        })

        me.callParent();
    }
});