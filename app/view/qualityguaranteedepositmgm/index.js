Ext.define('FamilyDecoration.view.qualityguaranteedepositmgm.Index', {
    extend: 'Ext.container.Container',
    alias: 'widget.qualityguaranteedepositmgm-index',
    requires: [
        'FamilyDecoration.store.User',
        'FamilyDecoration.view.qualityguaranteedepositmgm.ModifyQgd'
    ],
    layout: 'hbox',
    defaultType: 'gridpanel',
    defaults: {
        height: '100%',
        autoScroll: true
    },

    initComponent: function () {
        var me = this;

        function _getRes() {
            var captainList = me.getComponent('gridpanel-projectCaptainList'),
                captainSelModel = captainList.getSelectionModel(),
                captainSt = captainList.getStore(),
                captain = captainSelModel.getSelection()[0],

                qgdList = me.getComponent('gridpanel-qgdList'),
                qgdSelModel = qgdList.getSelectionModel(),
                qgdSt = qgdList.getStore(),
                qgd = qgdSelModel.getSelection()[0];

            return {
                captainList: captainList,
                captainSelModel: captainSelModel,
                captainSt: captainSt,
                captain: captain,
                qgdList: qgdList,
                qgdSelModel: qgdSelModel,
                qgdSt: qgdSt,
                qgd: qgd
            };
        }

        me.items = [
            {
                flex: 1,
                title: '项目经理',
                hideHeaders: true,
                itemId: 'gridpanel-projectCaptainList',
                style: {
                    borderRightWidth: '1px',
                    borderRightStyle: 'solid'
                },
                columns: {
                    defaults: {
                        flex: 1,
                        align: 'center'
                    },
                    items: [
                        {
                            text: '姓名',
                            dataIndex: 'realname'
                        }
                    ]
                },
                store: Ext.create('FamilyDecoration.store.User', {
                    autoLoad: true,
                    filters: [
                        function (rec){
                            var level = rec.get('level');
                            return /^003-\d{3}$/gi.test(level);
                        }
                    ]
                }),
                listeners: {
                    selectionchange: function (selModel, sels, opts){
                        var rec = sels[0],
                            resObj = _getRes();
                        resObj.qgdList.initBtn();
                    }
                }
            },
            {
                flex: 5,
                title: '质保金列表',
                itemId: 'gridpanel-qgdList',
                _getBtns: function (){
                    return {
                        apply: this.down('[name="button-applyQgd"]'),
                        modify: this.down('[name="button-modifyQgd"]'),
                        flat: this.down('[name="button-flatQgd"]'),
                        pass: this.down('[name="button-passQgd"]')
                    }
                },
                initBtn: function (){
                    var resObj = _getRes(),
                        btnObj = this._getBtns();
                    btnObj.apply.setDisabled(!resObj.captain);
                },
                tbar: [
                    {
                        xtype: 'button',
                        name: 'button-applyQgd',
                        text: '申付质保金',
                        disabled: true
                    },
                    {
                        xtype: 'button',
                        text: '调整质保金',
                        name: 'button-modifyQgd',
                        handler: function (){
                            var resObj = _getRes();
                            var win = Ext.create('FamilyDecoration.view.qualityguaranteedepositmgm.ModifyQgd', {
                                qgd: resObj.qgd
                            });
                            win.show();
                        }
                    },
                    {
                        xtype: 'button',
                        name: 'button-flatQgd',
                        text: '抹平质保金'
                    },
                    {
                        xtype: 'button',
                        name: 'button-passQgd',
                        text: '审核通过'
                    }
                ],
                columns: {
                    defaults: {
                        flex: 1,
                        align: 'center'
                    },
                    items: [
                        {
                            text: '单据名称',
                            dataIndex: 'billName'
                        },
                        {
                            text: '领款人',
                            dataIndex: 'payee'
                        },
                        {
                            text: '工程地址',
                            dataIndex: 'projectName'
                        },
                        {
                            text: '联系电话',
                            dataIndex: 'phoneNumber'
                        },
                        {
                            text: '单据',
                            dataIndex: ''
                        },
                        {
                            text: '总金额',
                            dataIndex: 'totalFee'
                        },
                        {
                            text: '已付金额',
                            dataIndex: 'paidFee'
                        },
                        {
                            text: '质保金',
                            dataIndex: 'qgdFee'
                        },
                        {
                            text: '质保金期限',
                            dataIndex: 'qgdDeadline'
                        },
                        {
                            text: '是否审核',
                            dataIndex: 'status'
                        }
                    ]
                }
            }
        ];

        this.callParent();
    }
});