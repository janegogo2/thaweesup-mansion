<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");

$contract_no = getIsset('__contract_no');
$electric_bill = $conn->queryRaw("select * from service where service_id=(SELECT MAX(service_id) from service)", true);
if ($cmd == "save") {

    $payment_no = getIsset('__payment_no');

    $value = array(
        "status_id" => CANCEL_CONTRACT,
        "stay_status" => MOVE_CHECK_OUT_STATUS,
    );

    $conn->update("contract_room", $value, array("contract_no" => $contract_no));

    $value = array(
        "water_meter_before" => getIsset('__water_meter_before'),
        "electricity_meter_before" => getIsset('__electricity_meter_before'),
        "water_meter" => getIsset('__water_meter'),
        "electricity_meter" => getIsset('__electricity_meter'),
        "water_miter_unit" => $electric_bill['water_meter_price'],
        "electricity_miter_unit" => $electric_bill['electricity_meter_price'],
        "save_date" => date("Y-m-d"),
        "payment_date" => date("Y-m-d"),
        "room_price" => str_replace(",", "", getIsset('__room_price')),
        "room_price_last" => str_replace(",", "", getIsset('__room_price_last')),
        "mulct_price" => str_replace(",", "", getIsset('__mulct_price')),
        "total_price" => str_replace(",", "", getIsset('__total_price')),       
        "status_id" => PAYMENT_SUCCESS,
        "water_price" => str_replace(",", "", getIsset('__water_price')),
        "contract_no" => $contract_no,
        "is_check_out" => true,
        "electricity_price" => str_replace(",", "", getIsset('__electricity_price')),
    );
    $conn->create("payment_room", $value);
    $notifyUtils = NotifyUtils::$APPROVE_CHECK_OUT;
    $notify = new Notification($conn, $notifyUtils, $contract_no);
    $notify->process();
    redirectTo('contract.php');

}
$sql = "select contract_room.*,status_name,room_name
,concat(employee.first_name,' ',employee.last_name) as employee_name
,customer.id_card as id_card,customer.email as email,room_price,customer.first_name as first_name,customer.last_name as last_name
from contract_room
left join customer on customer.customer_id=contract_room.customer_id
left join employee on employee.employee_id=contract_room.employee_id
left join status on status.status_id=contract_room.status_id
left join room on room.room_id=contract_room.room_id";
$payment = $conn->queryRaw("select payment_room.*,MONTH(save_date) as month,YEAR(save_date) as year from payment_room where contract_no = '$contract_no' order by payment_no desc", true);
$contract = $conn->queryRaw($sql . " where contract_no ='$contract_no'", true);
$year = date("Y", strtotime($contract['check_out_date']));
$month = date("m", strtotime($contract['check_out_date']));
//$electric_bill = $conn->queryRaw("select * from (
//select service.*,YEAR(service_log.datemonth) as bill_year,MONTH(service_log.datemonth) as bill_month from service 
//LEFT JOIN service_log on service_log.service_id = service.service_id
//) as ex
// where bill_year = '".$payment['year']."'  and  bill_month = '".$payment['month']."'", true);

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
                                        <h3 class="box-title">เก็บเงินรอบสุดท้าย</h3>
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
                                                       value="<?php echo number_format($contract['room_price'], 2); ?>"
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    เงินมัดจำ :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__deposit_price" id="__deposit_price"
                                                       class="form-control"
                                                       value="<?php echo number_format($contract['deposit_price'], 2); ?>"
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    เงินประกัน :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__insurance_price" id="__insurance_price"
                                                       class="form-control"
                                                       value="<?php echo number_format($contract['insurance_price'], 2); ?>"
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
                                                    มิเตอร์น้ำก่อนหน้า :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__water_meter_before" id="__water_meter_before"
                                                       class="form-control"
                                                       value="<?php echo $water_before; ?>"
                                                       readonly>
                                            </div>
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    มิเตอร์น้ำ :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__water_meter" id="__water_meter"
                                                       class="form-control" onchange="setMeterUse()"
                                                       value="" required>
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
                                                       value="<?php echo $elect_before; ?>"
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
                                                       onchange="setMeterUse()"
                                                       value="" required>
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
                                                       value="0" id="__water_use" name="__water_use"
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
                                                       value="0.00"
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
                                                <input type="text" id="__electricity_use" name="__electricity_use"
                                                       class="form-control"
                                                       value="0"
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
                                                       value="0.00"
                                                       readonly>
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
                                                       value="0.00"
                                                       readonly>
                                            </div>
                                        </div>
                                          <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ค่าห้องครั้งสุดท้าย :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__room_price_last" id="__room_price_last"
                                                       class="form-control"
                                                       onkeyup="checkComma(this,2);"
                                                       onKeyPress="checkWordNumber(this.value);"
                                                       onblur="checkNumFloat(this,0,999999);setTotal()"
                                                       onfocus="callDelComma(this);"
                                                       value="0.00"
                                                       required>
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
                                                       value="0.00"
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
                                                       value="<?php

                                                      
                                                       if(strtotime($contract['end_date']) < strtotime($contract['check_out_date'])){
                                                           echo 0.00 ;
                                                       } else {
                                                           //echo number_format(($contract['deposit_price'] + $contract['insurance_price']), 2);
                                                           echo 0.00 ;
                                                       }
                                                       ?>"
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
                                                <a class="btn btn-default" href="contract-request.php">ย้อนกลับ</a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                </label>
                                            </div>
                                            <div class="col-sm-9 form-control-static">
                                                <div class="text-maroon text-bold">ค่าไฟ
                                                    หน่วยละ <?php echo $electric_bill['electricity_meter_price']; ?> บาท
                                                </div>
                                                <div class="text-maroon text-bold">ค่าน้ำ
                                                    หน่วยละ <?php echo $electric_bill['water_meter_price']; ?> บาท
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
    $('#menu-contract,#menu-contract-request').addClass('active');

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

    function setMeterUse() {
        var water_meter = parseInt($("#__water_meter").val()) - parseInt($("#__water_meter_before").val())
        var elec_meter = parseInt($("#__electricity_meter").val()) - parseInt($("#__electricity_meter_before").val())
        $("#__water_use").val(water_meter);
        $("#__electricity_use").val(elec_meter);
        var total_water = water_meter * <?php echo $electric_bill['water_meter_price'];;?>;
        var total_elec = elec_meter * <?php echo $electric_bill['electricity_meter_price'];;?>;
        $("#__water_price").val(parseFloatMoney(total_water));
        $("#__electricity_price").val(parseFloatMoney(total_elec));
        $("#__fee_price").val(parseFloatMoney(total_water + total_elec));
        setTotal();
    }

    function setTotal() {
        var deposit_price = getFolatValue("__deposit_price");
        var insurance_price = getFolatValue("__insurance_price");
        var fee_price = getFolatValue("__fee_price");
        var mulct_price = getFolatValue("__mulct_price");
        var room_price = getFolatValue("__room_price");
        var room_price_last = getFolatValue("__room_price_last");
        $enddate = <?php echo strtotime($contract['end_date']);?>;
        $datenow = <?php echo strtotime($contract['check_out_date']);?>;
        if($enddate > $datenow)
            {
                deposit_price = 0;
                insurance_price = 0;
                var total = parseFloatMoney((deposit_price + insurance_price) + (fee_price + mulct_price)+ room_price_last);
                $("#__total_price").val(total);
            }
        else if($enddate < $datenow)
            {
                //คืนค่าประกัน
                var total = parseFloatMoney(((fee_price + mulct_price) + room_price_last));
                $("#__total_price").val(total);
            }
        
    }
</script>
<script>helpReturn('<?php echo $contract_no;?>', 'getContractRoomById')</script>
</body>
</html>


