<?php
session_start();
require_once "../common.inc.php";
require_once '../connection.inc.php';
$cmd = getIsset('__cmd');
$error_type = false;
if ($cmd == "login") {
    $username = getIsset('__username');
    $password = (getIsset('__password'));
    $chk_login = $conn->select('employee', array('email' => $username, 'password' => $password), true);
    if ($chk_login != null) {
        $level = $conn->select("employee_type", array("employee_type_id" => $chk_login['employee_type_id']), true);
        $uprofile = array();
        $uprofile['id'] = $chk_login['employee_id'];
        $uprofile['name'] = $chk_login['first_name'] . ' ' . $chk_login['last_name'];
        $uprofile['level'] = $chk_login['employee_type_id'];
        $uprofile['email'] = $chk_login['email'];
        $uprofile['tel'] = $chk_login['phone'];
        $uprofile['picture'] = $chk_login['picture'];
        $uprofile['level_name'] = $level['employee_type_name'];
        $_SESSION['uprofile'] = $uprofile;
        redirectTo('index.php');
    } else {
        $message = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        $error_type = true;
    }
}

?>
<html>
<head>
    <title><?php echo TITLE_ENG; ?></title>

    <!-- Meta -->
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Insight Station Network">
    <meta name="author" content="Insight Station Network">

    <!-- Theme style -->
    <link href="../dist/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="../dist/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <link href="../dist/css/AdminLTE.css" rel="stylesheet" type="text/css"/>
    <link href="../dist/css/skins/_all-skins.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/css/custom.css" rel="stylesheet" type="text/css"/>
    <style>
        input, select {
            height: 28px !important;
            padding-top: 0px !important;
            padding-bottom: 0px !important;
            border-radius: 0px !important;
        }
    </style>
</head>
<body style="margin:0px">
<div
    style="background-image:url('../assets/img/home-background.jpg');background-size:cover;width:100%;height:100%;position:absolute">
    <div
        style="max-width:400px;min-width: 250px;height:auto;margin:0 auto;padding:20px;position:relative;top:25%;background-color:rgba(0,0,0,0.5);border-radius:10px">
        <div class="row">
            <div class="col-xs-12 text-center">
                <h2 style="color:white">ระบบจัดการหอพัก <br>ทวีทรัพย์ แมนชั่น</h2>
            </div>
        </div>

        <hr>

        <div id="UpdatePanel1">
            <form class="form-horizontal" method="post" id="form_login" name="form_login">
                <input type="hidden" name="__cmd" id="__cmd" value="login">
                <h3 style="color:white;margin-bottom:10px">Login</h3>

                <div class="input-group" style="margin-bottom:20px;">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input name="__username" type="text" maxlength="255" id="__username" autofocus
                           class=" form-control input-sm " placeholder="User Name" required>
                </div>

                <div class="input-group" style="margin-bottom:20px;">
                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                    <input name="__password" type="password" maxlength="20" id="__password"
                           class=" form-control input-sm " placeholder="Password" required>
                </div>

                <div class="row">
                    <div class="col-md-6">                    
                    </div>

                    <div class="col-md-6">
                        <button class="btn btn-info pull-right" type="submit">Login</button>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal flat" id="alertModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: white">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">x</span></button>
                <span>
                <span class="glyphicon glyphicon-info-sign" style="font-size:30px;color:#f2b712"></span>
                <span style="font-size: 20px;" id="alertTitle"></span></span>
            </div>
        </div>
    </div>
</div>
<?php require_once 'javascript.php'; ?>
<script>
    function showModal() {
        $('#alertTitle').text('<?php echo isset($message) ? $message : ""; ?>');
        $('#alertModal').modal();
    }
</script>
<?php if ($error_type) {
    echo '<script>showModal()</script>';
} ?>
</body>
</html>
