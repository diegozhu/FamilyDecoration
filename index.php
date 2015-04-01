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
    <?php
        function curPageURL() {
             $pageURL = 'http';
             if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
                $pageURL .= "s";
             }
             $pageURL .= "://";
             if ($_SERVER["SERVER_PORT"] != "80") {
              $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
             } 
             else {
              $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
             }
             return $pageURL;
        }
        $pageurl = curPageURL();
        $isLocal = preg_match("/localhost/i", $pageurl, $arr);
        if ($isLocal) {
            echo    '<script type="text/javascript" src="tools/jquery-1.11.1.min.js"></script>'.
                    '<script src="https://d26b395fwzu5fz.cloudfront.net/3.2.3/keen.min.js" type="text/javascript"></script>'.
                    '<script type="text/javascript" src="highchart/js/highcharts.js"></script>'.
                    '<script type="text/javascript" src="highchart/js/highcharts-3d.js"></script>'.
                    '<script type="text/javascript" src="highchart/js/exporting.js"></script>'.
                    '<script type="text/javascript" src="highchart/chart.js"></script>';
        }
    ?>
</head>
<body>
    <div id="userInfo" class="x-hide-display">
        <span>用户信息:</span>
        <span name="realname"></span>
        <span name="account"></span>
        <span name="authority"></span>
        <a href="javascript:void(0);" id="logout">注销</a>
        <?php
            if ($isLocal) {
                echo '<a href="javascript:void(0);" id="keen-io-chart">图表</a>';
            }
        ?>
        <a href="javascript:void(0);" id="feedback">反馈</a>
    </div>

    <div id="tipBox" style="position:absolute;height:0px;top:10px;width:100%;text-align: right;line-height: 24px;display: none;">
        <span class="text" style="position:absolute;right: 20px;display:inline-block;background-color:#99bce8;color:#000;border-radius:3px;padding:0 10px;margin-top: 4px;border: 1px solid #99bce8;z-index: 9999999;"></span>
    </div>

    <div id="topMask" style="display:none;position: absolute;width: 100%;height:100%;z-index: 999999999;cursor:wait;"></div>

    <div class="container-fluid x-hide-display" id="chartContainer">
        <div class="row">
            <div class="col-md-8">
                <div class="chart-wrapper">
                    <div class="chart-title">
                        Chart Title
                    </div>
                    <div class="chart-stage">
                        <div id="grid-1-1">
                            
                        </div>
                    </div>
                    <div class="chart-notes">
                        Notes about this chart (optional)
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="chart-wrapper">
                    <div class="chart-title">
                        Chart Title
                    </div>
                    <div class="chart-stage">
                        <div id="grid-1-2">
                            
                        </div>
                    </div>
                    <div class="chart-notes">
                        Notes about this chart (optional)
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="chart-wrapper">
                    <div class="chart-title">
                        Chart Title
                    </div>
                    <div class="chart-stage">
                        <div id="grid-2-1">
                            <!-- chart goes here! -->
                        </div>
                    </div>
                    <div class="chart-notes">
                        Notes about this chart (optional)
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="chart-wrapper">
                    <div class="chart-title">
                        Chart Title
                    </div>
                    <div class="chart-stage">
                        <div id="grid-2-2">
                            <!-- chart goes here! -->
                        </div>
                    </div>
                    <div class="chart-notes">
                        Notes about this chart (optional)
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="chart-wrapper">
                    <div class="chart-title">
                        Chart Title
                    </div>
                    <div class="chart-stage">
                        <div id="grid-3-1">
                            
                        </div>
                    </div>
                    <div class="chart-notes">
                        Notes about this chart (optional)
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="chart-wrapper">
                    <div class="chart-title">
                        Chart Title
                    </div>
                    <div class="chart-stage">
                        <div id="grid-4-1">
                            
                        </div>
                    </div>
                    <div class="chart-notes">
                        Notes about this chart (optional)
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        Ext.define('User', {
            singleton: true,

            name: '<?php echo $_SESSION["name"]; ?>',

            level: '<?php echo $_SESSION["level"]; ?>',

            realname: '<?php echo $_SESSION["realname"]; ?>',

            isAdmin: function (){
                return this.level == '001-001' || this.level == '001-002';
            },

            isManager: function (){
                var level = this.level;
                var flag = false;
                if (/^001-\d{3}$/i.test(level)) {
                    // admin
                    flag = false;
                }
                else if (/^00[2345]-001$/.test(level)) {
                    // manager
                    flag = true;
                }
                return flag;
            },

            isDesignManager: function (){
                return this.level == '002-001';
            },

            isProjectManager: function (){
                return this.level == '003-001';
            },

            isBusinessManager: function (){
                return this.level == '004-001';
            },

            isAdministrationManager: function (){
                return this.level == '005-001';
            },

            isSupervisor: function (){
                return this.level == '003-003';
            },

            isDesignStaff: function (){
                return this.level == '002-002';
            },

            isProjectStaff: function (){
                return this.level == '003-002';
            },

            isBusinessStaff: function (){
                return this.level == '004-002';
            },

            isAdministrationStaff: function (){
                return this.level == '005-002';
            },

            isGeneral: function (){
                return this.level == '006-001';
            },

            isPropagandaManager: function (){
                return this.level == '007-001';
            },

            isPropagandaStaff: function (){
                return this.level == '007-002';
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
                name: '总经理',
                value: '001-001'
            }, {
                name: '副总经理',
                value: '001-002'
            }, {
                name: '设计部主管',
                value: '002-001'
            }, {
                name: '设计师',
                value: '002-002'
            }, {
                name: '工程部主管',
                value: '003-001'
            }, {
                name: '项目经理',
                value: '003-002'
            }, {
                name: '项目监理',
                value: '003-003'
            }, {
                name: '业务部主管',
                value: '004-001'
            }, {
                name: '业务员',
                value: '004-002'
            }, {
                name: '行政部主管',
                value: '005-001'
            }, {
                name: '行政部员工',
                value: '005-002'
            }, {
                name: '游客',
                value: '006-001'
            }, {
                name: '宣传部主管',
                value: '007-001'
            }, {
                name: '宣传部员工',
                value: '007-002'
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

            // get department according to user level value
            renderDepartment: function (level){
                var department = '';
                if (/^001-\d{3}$/i.test(level)) {
                    department = '最高管理层';
                }
                else if (/^002-\d{3}$/i.test(level)) {
                    department = '设计部';
                }
                else if (/^003-\d{3}$/i.test(level)) {
                    department = '工程部';
                }
                else if (/^004-\d{3}$/i.test(level)) {
                    department = '业务部';
                }
                else if (/^005-\d{3}$/i.test(level)) {
                    department = '行政部';
                }
                else if (/^006-\d{3}$/i.test(level)) {
                    department = '游客';
                }
                else if (/^007-\d{3}$/i.test(level)) {
                    department = '宣传部';
                }
                else {
                    department = '非部门';
                }
                return department;
            },

            // get user role according to user level value
            renderRole: function (level){
                var role = '',
                    roleStr = level.split('-')[1];
                if (/^001-\d{3}$/i.test(level)) {
                    if (roleStr == '001') {
                        role = '总经理';
                    }
                    else if (roleStr == '002') {
                        role = '副总经理';
                    }
                }
                // design department
                else if (/^002-\d{3}$/i.test(level)) {
                    if (roleStr == '001') {
                        role = '主管';
                    }
                    else if (roleStr == '002') {
                        role = '设计师';
                    }
                }
                // project department
                else if (/^003-\d{3}$/i.test(level)) {
                    if (roleStr == '001') {
                        role = '主管';
                    }
                    else if (roleStr == '002') {
                        role = '项目经理';
                    }
                    else if (roleStr == '003') {
                        role = '项目监理';
                    }
                }
                // business department
                else if (/^004-\d{3}$/i.test(level)) {
                    if (roleStr == '001') {
                        role = '主管';
                    }
                    else if (roleStr == '002') {
                        role = '业务员';
                    }
                }
                // administration department
                else if (/^005-\d{3}$/i.test(level)) {
                    if (roleStr == '001') {
                        role = '主管';
                    }
                    else if (roleStr == '002') {
                        role = '员工';
                    }
                }
                // visitor
                else if (/^006-\d{3}$/i.test(level)) {
                    role = '游客';
                }
                // propaganda department
                else if (/^007-\d{3}$/i.test(level)) {
                    if (roleStr == '001') {
                        role = '主管';
                    }
                    else if (roleStr == '002') {
                        role = '员工';
                    }
                }
                else {
                    role = '未知角色';
                }
                return role;
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
            },

            getRealName: function (){
                return this.realname;
            }
        });

        document.getElementById('logout').onclick = function (){
            logout();
        }

        document.getElementById('feedback').onclick = function (){
            if (Ext) {
                var win = Ext.create('Ext.window.Window', {
                    title: '用户使用问题和意见反馈',
                    width: 500,
                    height: 300,
                    layout: 'fit',
                    modal: true,
                    items: [{
                        xtype: 'textarea',
                        autoScroll: true,
                        id: 'textarea-feedback',
                        name: 'textarea-feedback',
                        hideLabel: true
                    }],
                    buttons: [{
                        text: '确定',
                        handler: function (){
                            var area = Ext.getCmp('textarea-feedback');
                            if (!Ext.isEmpty(area.getValue())) {
                                Ext.Ajax.request({
                                    url: './libs/feedback.php?action=send',
                                    method: 'POST',
                                    params: {
                                        name: User.getName(),
                                        realname: User.getRealName(),
                                        level: User.level,
                                        content: area.getValue()
                                    },
                                    callback: function (opts, success, res){
                                        if (success) {
                                            var obj = Ext.decode(res.responseText);
                                            if (obj.status == 'successful') {
                                                showMsg('发送成功，谢谢您的反馈，我们会及时处理您的问题。');
                                                win.close();
                                            }
                                            else {
                                                showMsg(obj.errMsg);
                                            }
                                        }
                                    }
                                });
                            }
                            else {
                                showMsg('请输入内容！');
                            }
                        }
                    }, {
                        text: '取消',
                        handler: function (){
                            win.close();
                        }
                    }]
                });

                win.show();
            }
        }
    </script>
</body>
</html>
