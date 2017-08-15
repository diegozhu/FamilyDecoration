Ext.define('FamilyDecoration.view.contractmanagement.EditContract', {
    extend: 'Ext.window.Window',
    alias: 'widget.contractmanagement-editcontract',
    requires: [
        'FamilyDecoration.view.contractmanagement.ProjectContract'
    ],
    defaults: {
    },
    layout: 'fit',
    contract: undefined,
    width: 700,
    height: 500,
    maximizable: true,
    modal: true,

    business: undefined,
    type: undefined,
    project: undefined,

    initComponent: function (){
        var me = this;

        me.title = me.contract ? '编辑合同' : '添加合同';

        me.items = [
            {
                business: me.business,
                type: me.type,
                xtype: 'contractmanagement-projectcontract'
            }
        ];

        me.buttons = [
            {
                text: '确定',
                handler: function (){
                    var contract = me.down('contractmanagement-projectcontract');
                    ajaxAdd('ContractEngineering', contract.getValues(), function (){

                    }, function (){

                    });
                }
            },
            {
                text: '取消',
                handler: function (){
                    me.close();
                }
            }
        ];

        this.callParent();
    }
});