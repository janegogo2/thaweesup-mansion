<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");

$contract_no = getIsset('__contract_no');
$payment_no = getIsset('__payment_no');
$electric_bill = $conn->queryRaw("select * from service where service_id=(SELECT MAX(service_id) from service)", true);

if ($cmd == "save") {
    $water_price = (getIsset('__water_meter') - getIsset('__water_meter_before')) * $electric_bill['water_meter_price'];
    $electricity_price = (getIsset('__electricity_meter') - getIsset('__electricity_meter_before')) * $electric_bill['electricity_meter_price'];
    $value = array(
        "water_meter_before" => getIsset('__water_meter_before'),
        "electricity_meter_before" => getIsset('__electricity_meter_before'),
        "water_meter" => getIsset('__water_meter'),
        "electricity_meter" => getIsset('__electricity_meter'),
        "save_date" => getIsset('__save_date'),
        "room_price" => str_replace(",", "", getIsset('__room_price')),
        "status_id" => getIsset("__status_id"),
        "water_price" => $water_price,
        "water_miter_unit" => $electric_bill['water_meter_price'],
        "electricity_miter_unit" => $electric_bill['electricity_meter_price'],
        "contract_no" => $contract_no,
        "is_check_out" => false,
        "electricity_price" => $electricity_price,
    );
    if ($payment_no == "") {
        if ($conn->create("payment_room", $value)) {
            $payment_no = $conn->getLastInsertId();

        }
    } else {
        if ($conn->update("payment_room", $value, array("payment_no" => $payment_no))) {
        }
    }
    if (getIsset("__status_id") == WAITING_PAYMENT) {
        $notifyUtils = NotifyUtils::$SUBMIT_UNIT_BY_MONTH;
        $notify = new Notification($conn, $notifyUtils, $payment_no);
        $notify->process();
    }

    redirectTo('save-payment.php');
}

$year = getIsset("__year");
$month = getIsset("__month");

$water_before = 0;
$elect_before = 0;
$before_payment = $conn->queryRaw("select * from payment_room where contract_no = '$contract_no' and status_id='" . PAYMENT_SUCCESS . "' order by save_date desc", true);
if ($before_payment == null) {
    $before_payment = $conn->queryRaw("select * from contract_room where contract_no = '$contract_no'", true);
    $water_before = $before_payment['water_meter_init'];
    $elect_before = $before_payment['electricity_meter_init'];
} else {
    $water_before = $before_payment['water_meter'];
    $elect_before = $before_payment['electricity_meter'];
}

$save_payment = $conn->select("payment_room", array("payment_no" => $payment_no), true);
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
                            <input id="__status_id" name="__status_id" type="hidden" value="">
                            <input id="__cmd" name="__cmd" type="hidden" value="">
                            <div class="col-md-12">
                                <label class="col-sm-3 control-label">
                                </label>
                            </div>
                            <div class="clr"></div>
                            <div class="col-sm-12">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">บันทึกมิเตอร์</h3>
                                    </div>

                                    <div class="box-body">
                                        <input type="hidden" name="__contract_no" id="__contract_no"
                                               class="form-control"
                                               value="0"
                                               required="true" readonly>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    เลขบัตรประชาชน :
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="hidden" name="__customer_id" id="__customer_id"
                                                       class="form-control"
                                                       value=""
                                                       readonly>
                                                <input type="text" name="__id_card" id="__id_card"
                                                       class="form-control"
                                                       value=""
                                                       onblur="trimValue(this);" maxlength="13" readonly
                                                       onkeypress="chkInteger(event)">
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
                                                       value="" onblur="trimValue(this);" readonly>
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
                                                       value="" onblur="trimValue(this);" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    อีเมล์ :
                                                </label>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="email" name="__email" id="__email" class="form-control"
                                                       value=""
                                                       onblur="trimValue(this);" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ห้องพัก :
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="hidden" name="__room_id" id="__room_id"
                                                       class="form-control"
                                                       value=""
                                                       readonly>
                                                <input type="text" name="__room_name" id="__room_name"
                                                       class="form-control"
                                                       value="" required readonly>
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
                                                       readonly required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <hr>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    วันที่บันทึก :
                                                </label>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="input-group">
                                                    <input type="text" name="__save_date"
                                                           id="__save_date"
                                                           class="form-control"
                                                           value="<?php echo date("$year-$month-01"); ?>"
                                                           readonly required
                                                    ><a class="input-group-addon btn-clear-date" href="#"><i
                                                            class="fa fa-eraser"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    มิเตอร์น้ำก่อนหน้า :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__water_meter_before" id="__water_meter_before"
                                                       class="form-control" value="<?php echo $water_before; ?>"
                                                       readonly required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    มิเตอร์ไฟก่อนหน้า :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__electricity_meter_before"
                                                       id="__electricity_meter_before"
                                                       class="form-control" value="<?php echo $elect_before; ?>"
                                                       readonly required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    มิเตอร์น้ำ :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__water_meter" id="__water_meter"
                                                       class="form-control"
                                                       value="<?php echo $save_payment['water_meter'];?>" required maxlength="10"
                                                       onkeypress="chkInteger(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    มิเตอร์ไฟ :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__electricity_meter"
                                                       id="__electricity_meter" class="form-control"
                                                       value="<?php echo $save_payment['electricity_meter'];?>" required maxlength="10"
                                                       onkeypress="chkInteger(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
<!--                                                --><?php
//                                                $sql = "select contract_room.*,YEAR(start_date) as start_date_y,MONTH(start_date) as start_date_m from contract_room  where contract_no='$contract_no'
//";
//                                                $result_row = $conn->queryRaw($sql,true);//คิวรี่ คำสั่ง
//                                                ?>
<!--                                                --><?php //if($result_row['start_date_y'] <= $year ){?>
                                                <a class="btn btn-success" href="javascript:goSaveCustom();">บันทึก</a>
                                                <a class="btn btn-success"
                                                   href="javascript:goConfirmCustom();">ยืนยัน</a>
<!--                                                --><?php //} ?>
                                                <a class="btn btn-default" href="save-payment.php">ย้อนกลับ</a>
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
    $('#menu-payment,#menu-save-payment').addClass('active');
    $('#__save_date').datetimepicker({
        datepicker: true,
        timepicker: false,
        format: 'Y-m-d',
        closeOnDateSelect: true
    });
    function goSaveCustom() {
        if (!required()) {
            $('input[name=__cmd]').val("save");
            $('input[name=__status_id]').val("<?php echo SAVE_PAYMENT;?>");
            $('#form_data').submit();
        }
    }
    function goConfirmCustom() {
        if (!required()) {
            if (confirm("ยืนยันการดำเนินการหรือไม่")) {
                $('input[name=__cmd]').val("save");
                $('input[name=__status_id]').val("<?php echo WAITING_PAYMENT;?>");
                $('#form_data').submit();
            }
        }
    }
    function helpReturn(value, action) {
        $.ajax({
            url: 'Allservice.php',
            data: {id: value, action: action},
            method: 'GET',
            success: function (result) {
                var data = JSON.parse(result);
                if (action == "getContractRoomById") {
                    if (data.contract_no != null) {
                        setValueContractRoom(data);
                    }
                }
            }
        });
    }
    function setValueContractRoom(data) {
        with (document.form_data) {
            $("#__employee_id").val(data.employee_id);
            $("#__employee_name").val(data.employee_name);
            $("#__customer_id").val(data.customer_id);
            $("#__first_name").val(data.first_name);
            $("#__last_name").val(data.last_name);
            $("#__email").val(data.email);
            $("#__id_card").val(data.id_card);
            $("#__room_id").val(data.room_id);
            $("#__room_name").val(data.room_name);
            $("#__room_price").val(parseFloatMoney(data.room_price));
            $("#__deposit_price").val(parseFloatMoney(data.deposit_price));
            $("#__insurance_price").val(parseFloatMoney(data.insurance_price));
            $("#__start_date").val(data.start_date);
            $("#__end_date").val(data.end_date);
            $("#__contract_datetime").val(data.contract_datetime);
            $("#__contract_no").val(data.contract_no);
            $("#__water_meter_init").val(data.water_meter_init);
            $("#__electricity_meter_init").val(data.electricity_meter_init);
        }
    }

</script>
<script>helpReturn('<?php echo $contract_no;?>', 'getContractRoomById')</script>
</body>
</html>