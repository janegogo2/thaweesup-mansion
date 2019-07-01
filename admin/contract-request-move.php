<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");

$contract_no = getIsset('__contract_no');
if ($cmd == "save") {
    $value = array(
        "check_out_date" => getIsset('__check_out_date'),
        "stay_status" => REQUEST_CHECK_OUT_STATUS,
    );

    if ($conn->update("contract_room", $value, array("contract_no" => $contract_no))) {
        redirectTo('contract.php');
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
                                        <h3 class="box-title">จัดการข้อมูลสัญญา</h3>
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
                                            <div class="col-sm-12">
                                                <hr>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    พนักงานที่สำสัญยา :
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="hidden" name="__employee_id" id="__employee_id"
                                                       class="form-control"
                                                       value="<?php echo $uprofile['id'] ?>"
                                                       readonly>
                                                <input type="text" name="__employee_name" id="__employee_name"
                                                       class="form-control"
                                                       value="<?php echo $uprofile['name'] ?>" required readonly>
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
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    วันที่ทำสัญญา :
                                                </label>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" name="__contract_datetime"
                                                       id="__contract_datetime"
                                                       class="form-control"
                                                       value="<?php echo date('Y-m-d H:i'); ?>"
                                                       readonly required
                                                >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    วันที่เริ่มสัญญา :
                                                </label>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" name="__start_date"
                                                       id="__start_date"
                                                       class="form-control"
                                                       value="<?php echo date('Y-m-d'); ?>"
                                                       readonly required
                                                >
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    วันที่หมดสัญญา :
                                                </label>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" name="__end_date"
                                                       id="__end_date"
                                                       class="form-control"
                                                       value="<?php echo date('Y-m-d'); ?>"
                                                       readonly required
                                                >
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
                                                       value="" readonly>
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
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    วันที่แจ้งออก :
                                                </label>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="input-group">
                                                    <input type="text" name="__check_out_date"
                                                           id="__check_out_date"
                                                           class="form-control"
                                                           value="<?php echo date('Y-m-d'); ?>"
                                                           readonly required
                                                    ><a class="input-group-addon btn-clear-date" href="#"><i
                                                            class="fa fa-eraser"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                                <a class="btn btn-success" href="javascript:goConfirm();">ยืนยัน</a>
                                                <a class="btn btn-default" href="contract.php">ย้อนกลับ</a>
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
    $('#menu-contract,#menu-contract-management').addClass('active');
    $('#__check_out_date').datetimepicker({
        datepicker: true,
        timepicker: false,
        format: 'Y-m-d',
        closeOnDateSelect: true
    });

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
                if (action == "getCustomerById") {
                    if (data.customer_id != null) {
                        setValueCustomer(data);
                    }
                }
                if (action == "getEmployeeById") {
                    if (data.employee_id != null) {
                        setValueEmployee(data);
                    }
                }
                if (action == "getContractRoomById") {
                    if (data.contract_no != null) {
                        setValueContractRoom(data);
                    }
                }
            }
        });
    }
    function setValueRoom(data) {
        with (document.form_data) {
            $("#__room_id").val(data.room_id);
            $("#__room_name").val(data.room_name);
            $("#__room_price").val(parseFloatMoney(data.room_price));
            $("#__deposit_price").val(parseFloatMoney(data.room_price * <?php echo DEPOSIT_PRICE;?>));
        }
    }
    function setValueCustomer(data) {
        with (document.form_data) {
            $("#__customer_id").val(data.customer_id);
            $("#__first_name").val(data.first_name);
            $("#__last_name").val(data.last_name);
            $("#__email").val(data.email);
            $("#__id_card").val(data.id_card);
        }
    }

    function setValueEmployee(data) {
        with (document.form_data) {
            $("#__employee_id").val(data.employee_id);
            $("#__employee_name").val(data.first_name + ' ' + data.last_name);
        }
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

