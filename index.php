<?php
    include_once "./libs/login.php";
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <title>FamilyDecoration</title>
    <!-- <x-compile> -->
        <!-- <x-bootstrap> -->
            <link rel="stylesheet" href="bootstrap.css">
            <script src="ext/ext-dev.js"></script>
            <script src="bootstrap.js"></script>
        <!-- </x-bootstrap> -->
        <script src="app.js"></script>
    <!-- </x-compile> -->
    <link href="resources/css/global.css" rel="stylesheet" />
    <script type="text/javascript" src="resources/locale/ext-lang-zh_CN.js"></script>
</head>
<body>
    <div id="userInfo" class="x-hide-display">
        <span>用户信息:</span>
        <span name="account"></span>
        <span name="authority"></span>
        <a href="javascript:void(0);" id="logout">注销</a>
    </div>

    <div id="tipBox"
         style="position:absolute;height:0px;top:10px;width:100%;text-align: right;line-height: 24px;display: none;">
    <span class="text" style="position:absolute;right: 20px;display:inline-block;background-color:#99bce8;
        color:#000;border-radius:3px;padding:0 10px;margin-top: 4px;
        border: 1px solid #99bce8;z-index: 9999999;"></span>
    </div>
    <div id="topMask" style="display:none;position: absolute;width: 100%;height:100%;z-index: 999999999;cursor:wait;"></div>

    <script type="text/javascript">
        Ext.define('User', {
            singleton: true,

            name: '<?php echo $_SESSION["name"]; ?>',

            level: '<?php echo $_SESSION["level"]; ?>',

            isAdmin: function (){
                return this.level == 1;
            },

            isManager: function (){
                return this.level == 2;
            },

            isGeneral: function (){
                return this.level == 3;
            },

            isCurrent: function (name){
                if (name) {
                    return this.name == name;
                }
                else {
                    return undefined;
                }
            },

            role: [{
                name: '管理员',
                value: '1'
            }, {
                name: '设计师',
                value: '2'
            }, {
                name: '一般用户',
                value: '3'
            }],

            getStatus: function (){
                var level = this.level,
                    role = this.role,
                    status;
                Ext.each(role, function (rec, index){
                    if (level == rec.value) {
                        status = rec.name;
                    }
                });
                if (status) {
                    return status;
                }
                else {
                    return '未知';
                }
            },

            render: function (level){
                var role = this.role,
                    status;
                Ext.each(role, function (rec, index){
                    if (level == rec.value) {
                        status = rec.name;
                    }
                });
                if (status) {
                    return status;
                }
                else {
                    return '未知';
                }
            },

            getName: function (){
                return this.name;
            }
        });
        
        function heartBeat (){
            Ext.Ajax.request({
                url: './libs/sys.php?action=adminHeartBeat',
                method: 'GET'
            });
        }

        if (User.isAdmin()) {
            Ext.defer(heartBeat, 2000);
            // Heartbeat
            setInterval(heartBeat, 60000);
        }

        document.getElementById('logout').onclick = function (){
            logout();
        }
    </script>
</body>
</html>
