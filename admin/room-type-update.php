<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");

$room_type_id = getIsset('__room_type_id');
if ($cmd == "save") {
    $value = array(
        "room_type_name" => getIsset('__room_type_name'),
    );
    if ($room_type_id == "0") {
        if ($conn->create("room_type", $value)) {
            redirectTo('room-type.php');
        }

    } else {
        if ($conn->update("room_type", $value, array("room_type_id" => $room_type_id))) {
            redirectTo('room-type.php');
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
                    <form class="form-horizontal" id="form_data" name="form_data" enctype="multipart/form-data"
                          method="post">
                        <div class="">
                            <input id="__cmd" name="__cmd" type="hidden" value="">
                            <div class="col-md-12">
                                <label class="col-sm-3 control-label">
                                </label>
                            </div>
                            <div class="clr"></div>
                            <div class="col-sm-12">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">จัดการข้อมูลประเภท</h3>
                                    </div>

                                    <div class="box-body">
                                        <input type="hidden" name="__room_type_id" id="__room_type_id" class="form-control"
                                               value="0"
                                               required="true" readonly>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ประเภทห้องพัก :
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="text" name="__room_type_name" id="__room_type_name"
                                                       class="form-control"
                                                       value="" onblur="trimValue(this);" required="true">
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
                                                <a class="btn btn-default" href="room-type.php">ย้อนกลับ</a>
                                            </div>
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
    $('#menu-room-type,#menu-service').addClass('active');
    function helpReturn(value, action) {
        $.ajax({
            url: 'Allservice.php',
            data: {id: value, action: action},
            method: 'GET',
            success: function (result) {
                var data = JSON.parse(result);
                if (action == "getRoomTypeById") {
                    if (data.room_type_id != null) {
                        setValueRoomType(data);
                    }
                }
            }
        });
    }
    function setValueRoomType(data) {
        with (document.form_data) {
            $("#__room_type_id").val(data.room_type_id);
            $("#__room_type_name").val(data.room_type_name);
        }
    }

</script>
<script>helpReturn('<?php echo $room_type_id;?>', 'getRoomTypeById')</script>
</body>
</html>


