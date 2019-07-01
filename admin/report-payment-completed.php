<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");

$name = getIsset("__name");
$room = getIsset("__room");
$month = getIsset("__month");
$month2 = getIsset("__month2");
if ($name == "")
    $name = date("Y");

if ($month == "")
    $month = date("m");

if ($room == "")
    $room = date("Y");

if ($month2 == "")
    $month2 = date("m");

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
                                        <h3 class="box-title">รายงานการชำระเงิน</h3>
                                    </div>

                                    <div class="box-body">
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    เดือน :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <select name="__month" id="__month">
                                                    <?php
                                                    foreach ($strMonthCut as $index => $item) {
                                                        $id = str_pad($index + 1, 2, '0', STR_PAD_LEFT)
                                                        ?>
                                                        <option <?php echo $id == $month ? "selected" : ""; ?>
                                                                value="<?php echo $id; ?>"><?php echo $item; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div align="right">
                                                <label class="col-sm-1 control-label">
                                                    -
                                                </label>
                                            </div>
                                            <div class="col-sm-3">
                                                <select name="__month2" id="__month2">
                                                    <?php
                                                    foreach ($strMonthCut as $index => $item) {
                                                        $id = str_pad($index + 1, 2, '0', STR_PAD_LEFT)
                                                        ?>
                                                        <option <?php echo $id == $month2 ? "selected" : ""; ?>
                                                                value="<?php echo $id; ?>"><?php echo $item; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ชื่อลูกค้า :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <select name="__name" id="__name">
                                                    <option value="0">ทั้งหมด</option>
                                                <?php
                                                $level = $conn->select('customer');

                                                foreach ($level as $type) {
                                                    ?>
                                                    <option
                                                            value="<?php echo $type['customer_id']; ?>">คุณ <?php echo $type['first_name'].' '.$type['last_name']; ?></option>
                                                    <?php
                                                }
                                                ?>
                                                </select>
                                            </div>
                                            <div align="right">
                                                <label class="col-sm-1 control-label">
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
Payment completed
<?php require_once 'javascript.php'; ?>
<!-- Page script -->
<script>
    $('#menu-payment-completed,#menu-report2').addClass('active');

    function goPrint() {
        if (!required()) {
            if (confirm("ต้องการพิมพ์รายงานหรือไม่")) {
                var name = $("#__name").val();
                var month = $("#__month").val();
                var room = $("#__room").val();
                var month2 = $("#__month2").val();
                window.open("print-payment-completed.php?name=" + name + "&month=" + month + "&room=" + room + "&month2=" + month2, "_blank");
            }
        }
    }
</script>
</body>
</html>


