<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");

$room_id = getIsset('__room_id');
if ($cmd == "save") {
    $value = array(
        "room_name" => getIsset('__room_name'),
        "room_detail" => getIsset('__room_detail'),
        "room_price" => str_replace(",", "", getIsset('__room_price')),
        "room_type_id" => getIsset('__room_type_id'),
    );
    if ($room_id == "0") {
        if ($conn->create("room", $value)) {
            redirectTo('room.php');
        }

    } else {
        if ($conn->update("room", $value, array("room_id" => $room_id))) {
            redirectTo('room.php');
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
                            <input id="__delete_field" name="__delete_field" type="hidden" value="__room_id">
                            <input id="__cmd" name="__cmd" type="hidden" value="">
                            <div class="col-md-12">
                                <label class="col-sm-3 control-label">
                                </label>
                            </div>
                            <div class="clr"></div>
                            <div class="col-sm-12">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">จัดการข้อมูลห้องพัก</h3>
                                    </div>

                                    <div class="box-body">
                                        <input type="hidden" name="__room_id" id="__room_id" class="form-control"
                                               value="0"
                                               required="true" readonly>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ชื่อห้องพัก :
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="text" name="__room_name" id="__room_name"
                                                       class="form-control"
                                                       value="" onblur="trimValue(this);" required="true">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ประเภทห้อง :
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                                <div class="input-group">
                                                    <input type="hidden" name="__room_type_id" id="__room_type_id"
                                                           class="form-control"
                                                           value=""
                                                           readonly>
                                                    <input type="text" name="__room_type_name" id="__room_type_name"
                                                           class="form-control"
                                                           value="" readonly required>
                                                    <a href="javascript:goPage('linkhelp.php?__filter=room_type&__action=getRoomTypeById');"
                                                       class="btn btn-default input-group-addon"><i
                                                            class="fa fa-search"></i> </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ราคา/เดือน :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__room_price" id="__room_price"
                                                       class="form-control"
                                                       onkeyup="checkComma(this,2);"
                                                       onKeyPress="checkWordNumber(this.value);"
                                                       onblur="checkNumFloat(this,0,999999);" onfocus="callDelComma(this);"
                                                       value=""  required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    รายละเอียด :
                                                </label>
                                            </div>
                                            <div class="col-sm-6">
                                                <textarea id="__room_detail" name="__room_detail" class="form-control"
                                                          onblur="trimValue(this);" rows="6"></textarea>
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
                                                <a class="btn btn-default" href="room.php">ย้อนกลับ</a>
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
    $('#menu-room,#menu-service').addClass('active');
    function helpReturn(value, action) {
        $.ajax({
            url: 'Allservice.php',
            data: {id: value, action: action},
            method: 'GET',
            success: function (result) {
                var data = JSON.parse(result);
                if (action == "getRoomById") {
                    if (data.room_id != null) {
                        setValueRoom(data);
                    }
                }
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
    function setValueRoom(data) {
        with (document.form_data) {
            $("#__room_id").val(data.room_id);
            $("#__room_name").val(data.room_name);
            $("#__room_price").val(parseFloatMoney(data.room_price));
            $("#__room_type_id").val(data.room_type_id);
            $("#__room_type_name").val(data.room_type_name);
            $("#__room_detail").val(data.room_detail);
        }
    }

</script>
<script>helpReturn('<?php echo $room_id;?>', 'getRoomById')</script>
</body>
</html>


