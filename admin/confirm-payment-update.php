<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");

$contract_no = getIsset('__contract_no');
if ($cmd == "save") {

    $payment_no = getIsset('__payment_no');

    $value = array(
        "mulct_price" => str_replace(",", "", getIsset('__mulct_price')),
        "total_price" => str_replace(",", "", getIsset('__total_price')),
        "status_id" => PAYMENT_SUCCESS,
        "payment_date" => getIsset("__payment_date"),
    );

    if ($conn->update("payment_room", $value, array("payment_no" => $payment_no))) {
        $notifyUtils = NotifyUtils::$APPROVE_PAYMENT;
        $notify = new Notification($conn, $notifyUtils, $payment_no);
        $notify->process();
        redirectTo('confirm-payment.php');
    }
}

$year = getIsset("__year");
$month = getIsset("__month");

$payment = $conn->queryRaw("select * from payment_room where contract_no='$contract_no' and YEAR(save_date)='$year' and MONTH(save_date)='$month' ", true);
//$electric_bill = $conn->queryRaw("select * from service ", true);
$limit_date = date("$year-$month-" . PAYMENT_DATE);
$current_date = date("$year-$month-d");
$limit_date = strtotime($limit_date);
$current_date = strtotime($current_date);
$diff = ($current_date - $limit_date) / (60 * 60 * 24);
if ($diff < 0)
    $diff = 0;
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
                            <input id="__payment_no" name="__payment_no" type="hidden"
                                   value="<?php echo $payment['payment_no']; ?>">
                            <input id="__check_date" name="__check_date" type="hidden"
                                   value="<?php echo "$year-$month-" . PAYMENT_DATE; ?>">
                            <div class="col-md-12">
                                <label class="col-sm-3 control-label">
                                </label>
                            </div>
                            <div class="clr"></div>
                            <div class="col-sm-12">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">เก็บค่าเช้า</h3>
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
                                                       value="<?php echo number_format($payment['room_price'], 2); ?>"
                                                       readonly>
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
                                                <input type="text" name="__save_date"
                                                       id="__save_date"
                                                       class="form-control"
                                                       value="<?php echo $payment['save_date']; ?>"
                                                       readonly
                                                >
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
                                                       class="form-control"
                                                       value="<?php echo $payment['water_meter_before']; ?>"
                                                       readonly>
                                            </div>
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    มิเตอร์น้ำ :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__water_meter" id="__water_meter"
                                                       class="form-control"
                                                       value="<?php echo $payment['water_meter']; ?>" readonly>
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
                                                       class="form-control"
                                                       value="<?php echo $payment['electricity_meter_before']; ?>"
                                                       readonly required>
                                            </div>
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    มิเตอร์ไฟ :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__electricity_meter"
                                                       id="__electricity_meter" class="form-control"
                                                       value="<?php echo $payment['electricity_meter']; ?>" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ใช้น้ำไป :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text"
                                                       class="form-control"
                                                       value="<?php echo $payment['water_meter'] - $payment['water_meter_before']; ?>"
                                                       readonly>
                                            </div>
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ค่าน้ำ :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__water_price" id="__water_price"
                                                       class="form-control"
                                                       value="<?php echo number_format($payment['water_price'], 2); ?>"
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ใช้ไฟไป :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text"
                                                       class="form-control"
                                                       value="<?php echo $payment['electricity_meter'] - $payment['electricity_meter_before']; ?>"
                                                       readonly>
                                            </div>
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ค่าไฟ :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__electricity_price" id="__electricity_price"
                                                       class="form-control"
                                                       value="<?php echo number_format($payment['electricity_price'], 2); ?>"
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    วันที่จ่ายงาน :
                                                </label>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="input-group">
                                                    <input type="text" name="__payment_date"
                                                           id="__payment_date"
                                                           class="form-control"
                                                           value="<?php echo date("$year-$month-d"); ?>"
                                                           readonly required
                                                    ><a class="input-group-addon btn-clear-date" href="#"><i
                                                            class="fa fa-eraser"></i></a>
                                                </div>
                                            </div>
                                            <div align="right">
                                                <label class="col-sm-2 control-label">
                                                    จำนวนวันที่เกิน :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__date_diff"
                                                       id="__date_diff"
                                                       class="form-control"
                                                       value="<?php echo $diff; ?>"
                                                       readonly required
                                                >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ราคารวม :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__fee_price" id="__fee_price"
                                                       class="form-control"
                                                       value="<?php echo number_format(($payment['electricity_price'] + $payment['water_price'] + $payment['room_price']), 2); ?>"
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ค่าปรับ :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__mulct_price" id="__mulct_price"
                                                       class="form-control"
                                                       onkeyup="checkComma(this,2);"
                                                       onKeyPress="checkWordNumber(this.value);"
                                                       onblur="checkNumFloat(this,0,999999);setTotal()"
                                                       onfocus="callDelComma(this);"
                                                       value="<?php echo number_format(($diff * RATE_PAYMENT_PRICE), 2); ?>"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ยอดทั้งหมดที่ต้องจ่าย :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__total_price" id="__total_price"
                                                       class="form-control"
                                                       onkeyup="checkComma(this,2);"
                                                       onKeyPress="checkWordNumber(this.value);"
                                                       onblur="checkNumFloat(this,0,999999);"
                                                       onfocus="callDelComma(this);"
                                                       value="<?php echo number_format(($payment['electricity_price'] + $payment['water_price'] + $payment['room_price'] + ($diff * RATE_PAYMENT_PRICE)), 2); ?>"
                                                       required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                                <a class="btn btn-success" href="javascript:goConfirm();">ยืนยัน</a>
                                                <a class="btn btn-default" href="confirm-payment.php">ย้อนกลับ</a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                </label>
                                            </div>
                                            <div class="col-sm-9 form-control-static">
                                                <div class="text-maroon text-bold">ค่าไฟ
                                                    หน่วยละ <?php echo $payment['electricity_miter_unit']; ?> บาท
                                                </div>
                                                <div class="text-maroon text-bold">ค่าน้ำ
                                                    หน่วยละ <?php echo $payment['water_miter_unit']; ?> บาท
                                                </div>
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
    $('#menu-payment,#menu-confirm-payment').addClass('active');
    $('#__payment_date').datetimepicker({
        datepicker: true,
        timepicker: false,
        format: 'Y-m-d',
        closeOnDateSelect: true,
    }).on('change', function (ev) {
        var limit_date = new Date($("#__check_date").val());
        var payment_date = new Date($("#__payment_date").val());
        var diff = new Date(payment_date - limit_date);
        var day = Math.ceil(diff / 1000 / 60 / 60 / 24);
        if (day > 0) {
            $("#__mulct_price").val(parseFloatMoney(day * <?php echo RATE_PAYMENT_PRICE;?>));
            $("#__date_diff").val(day);
        } else {
            $("#__mulct_price").val("0.00");
            $("#__date_diff").val(0);
        }
        setTotal();
    });

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
            $("#__start_date").val(data.start_date);
            $("#__end_date").val(data.end_date);
            $("#__contract_datetime").val(data.contract_datetime);
            $("#__contract_no").val(data.contract_no);
        }
    }

    function setTotal() {
        var fee_price = getFolatValue("__fee_price");
        var mulct_price = getFolatValue("__mulct_price");
        $("#__total_price").val(parseFloatMoney(fee_price + mulct_price));
    }
</script>
<script>helpReturn('<?php echo $contract_no;?>', 'getContractRoomById')</script>
</body>
</html>


