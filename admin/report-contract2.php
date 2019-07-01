<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");
$year = getIsset("__year");

if ($year == "")
    $year = date("Y");

$room = getIsset("__room");
if ($room == "")
    $room = 'contract_room.room_id';

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
                            <input id="__delete_field" name="__delete_field" type="hidden" value="">
                            <input id="__cmd" name="__cmd" type="hidden" value="">
                            <div class="col-md-12">
                                <label class="col-sm-3 control-label">
                                </label>
                            </div>
                            <div class="clr"></div>
                            <div class="col-sm-12">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">รายงานค่าเช่า</h3>
                                    </div>

                                    <div class="box-body">
<!--                                        <div class="form-group">-->
<!--                                            <div align="right">-->
<!--                                                <label class="col-sm-3 control-label">-->
<!--                                                    ปี :-->
<!--                                                </label>-->
<!--                                            </div>-->
<!--                                            <div class="col-sm-2">-->
<!--                                                <input type="text" name="__year"-->
<!--                                                       id="__year" class="form-control"-->
<!--                                                       value="--><?php //echo $year; ?><!--" required maxlength="4"-->
<!--                                                       onkeypress="chkInteger(event)">-->
<!--                                            </div>-->
<!--                                        </div>-->
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ห้อง :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <select name="__room" id="__room">
                                                    <option value="0">ทั้งหมด</option>
                                                    <?php
                                                    $level = $conn->select('room');

                                                    foreach ($level as $type) {
                                                        ?>
                                                        <option
                                                                value="<?php echo $type['room_id']; ?>"><?php echo $type['room_name']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                                <a class="btn btn-success" href="javascript:goPrint();">พิมพ์รายงาน</a>
                                                <a class="btn btn-warning" href="save-payment.php">เคลียร์</a>
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
    $('#menu-report-contract,#menu-report2').addClass('active');

    function goPrint() {
        if (!required()) {
            if (confirm("ต้องการพิมพ์รายงานหรือไม่")) {
                var room = $("#__room").val();
                var year = $("#__year").val();
                window.open("print-contract2.php?room=" + room, "_blank");
            }
        }
    }
</script>
</body>
</html>


