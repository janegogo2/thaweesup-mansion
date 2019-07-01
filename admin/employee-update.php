<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");
$test = array();
$employee_id = getIsset('__employee_id');
if ($cmd == "save") {
    $chk_admin = $conn->queryRaw("select * from employee where email='" . getIsset('__email') . "' and employee_id<>'$employee_id'");
    if ($chk_admin != null) {
        alertMassage("ชื่อผู้ใช้ซ้ำ");
    } else {
        $value = array(
            "first_name" => getIsset('__first_name'),
            "last_name" => getIsset('__last_name'),
            "employee_type_id" => getIsset('__employee_type_id'),
            "phone" => getIsset('__phone'),
            "email" => getIsset('__email'),
            "address" => getIsset('__address'),
            "password" => getIsset('__password'),
            "picture" => uploadFile($_FILES['__file_upload'], getIsset('__file_name'), PATH_UPLOAD),
        );
        if ($employee_id == "0") {
            if ($conn->create("employee", $value)) {
                redirectTo("employee.php");
            }

        } else {
            if ($conn->update("employee", $value, array("employee_id" => $employee_id))) {
                redirectTo("employee.php");
            }
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo TITLE_ENG; ?> </title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <?php include "css.php" ?>
</head>
<body class="skin-black sidebar-mini">
<div class="wrapper">
    <?php include "navbar.php" ?>
    <?php include "sidebar.php" ?>
    <div id="posContain" class="content-wrapper">
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" id="form_data" name="form_data" method="post"
                          enctype="multipart/form-data">
                        <input id="__cmd" name="__cmd" type="hidden" value="">

                        <div class="col-md-12">
                            <label class="col-sm-3 control-label">
                            </label>
                        </div>
                        <div class="clr"></div>
                        <div class="col-sm-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">จัดการข้อมูลผู้ดูแลระบบ </h3>
                                </div>
                                <div class="box-body">
                                    <input type="hidden" name="__employee_id" id="__employee_id" class="form-control"
                                           value="0"
                                           required="true" readonly>
                                    <div class="form-group">
                                        <div align="right">
                                            <label class="col-sm-3 control-label">
                                                รูปพนักงาน :
                                            </label>
                                        </div>
                                        <div class="col-sm-3">
                                            <img id="img-preview" src="" class="image img-thumbnail"
                                                 onerror="src='../dist/img/user2-160x160.jpg'">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">

                                        </label>

                                        <div class="col-sm-5">
                                            <input type="file" name="__file_upload" style="display: none">
                                            <input type="hidden" name="__file_name" id="__file_name">
                                            <a type="button" class="btn btn-success btn-xs upload-logo">อัพโหลด</a>
                                            <a type="button" class="btn btn-danger btn-xs del-logo">ลบไฟล์</a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div align="right">
                                            <label class="col-sm-3 control-label">
                                                ชื่อ :
                                            </label>
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" name="__first_name" id="__first_name"
                                                   class="form-control"
                                                   value=""
                                                   onblur="trimValue(this);" required="true">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div align="right">
                                            <label class="col-sm-3 control-label">
                                                นามสกุล :
                                            </label>
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" name="__last_name" id="__last_name"
                                                   class="form-control"
                                                   value=""
                                                   onblur="trimValue(this);" required="true">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div align="right">
                                            <label class="col-sm-3 control-label">
                                                เบอร์โทรศัพท์ :
                                            </label>
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="text" name="__phone" id="__phone"
                                                   class="form-control" maxlength="10"
                                                   value="" onblur="chkInteger(event)" required="true">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div align="right">
                                            <label class="col-sm-3 control-label">
                                                อีเมล์ :
                                            </label>
                                        </div>
                                        <div class="col-sm-5">
                                            <input type="email" name="__email" id="__email"
                                                   class="form-control"
                                                   value="" onkeypress="chkNotThaiChaOnly(event)"
                                                   onblur="trimValue(this);" required="true">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div align="right">
                                            <label class="col-sm-3 control-label">
                                                ที่อยู่ :
                                            </label>
                                        </div>
                                        <div class="col-sm-5">
                                            <textarea class="form-control" name="__address" id="__address" rows="6"
                                                      onblur="trimValue(this);"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div align="right">
                                            <label class="col-sm-3 control-label">
                                                ระดับการใช้งาน :
                                            </label>
                                        </div>
                                        <div class="col-sm-5">
                                            <select name="__employee_type_id" id="__employee_type_id">
                                                <?php
                                                $level = $conn->queryRaw('select * from employee_type where employee_type_id<>1');
                                                foreach ($level as $type) {
                                                    ?>
                                                    <option
                                                        value="<?php echo $type['employee_type_id']; ?>"><?php echo $type['employee_type_name']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div align="right">
                                            <label class="col-sm-3 control-label">
                                                รหัสผ่าน :
                                            </label>
                                        </div>
                                        <div class="col-sm-3">
                                            <input type="password" name="__password" id="__password"
                                                   class="form-control"
                                                   value="" required
                                                   onblur="trimValue(this);" maxlength="20"
                                                   onkeypress="chkNotThaiChaOnly(event)">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div align="right">
                                            <label class="col-sm-3 control-label">
                                            </label>
                                        </div>
                                        <div class="col-sm-5">
                                            <a class="btn btn-success" href="javascript:goSave();">บันทึก</a>
                                            <a class="btn btn-warning" href="javascript:goClear()">เคลียร์</a>
                                            <a class="btn btn-default" href="employee.php">ย้อนกลับ</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
<?php require_once 'javascript.php'; ?>
<!-- Page script -->
<script>
    $('#menu-user').addClass('active');
    $('#menu-employee').addClass('active');
    function helpReturn(value, action) {
        $.ajax({
            url: 'Allservice.php',
            data: {id: value, action: action},
            method: 'GET',
            success: function (result) {
                var data = JSON.parse(result);
                if (action == "getEmployeeById") {
                    if (data.employee_id != null) {
                        console.log(data);
                        setValueEmployee(data);
                    }
                }
            }
        });
    }
    function setValueEmployee(data) {
        with (document.form_data) {
            $("#__employee_id").val(data.employee_id);
            $("#__first_name").val(data.first_name);
            $("#__last_name").val(data.last_name);
            $("#__employee_type_id").val(data.employee_type_id);
            $("#__password").val(data.password);
            $("#__phone").val(data.phone);
            $("#__email").val(data.email);
            $("#__address").val(data.address);
            $("#__file_name").val(data.picture);
            $("#img-preview").attr('src', '<?php echo PATH_UPLOAD . '/';?>' + data.picture);
        }
    }
</script>
<script>helpReturn('<?php echo $employee_id;?>', 'getEmployeeById')</script>
</body>
</html>


