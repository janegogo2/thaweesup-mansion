<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");

$service_id = getIsset('__service_id');
if ($cmd == "save") {
    $value = array(
        "electricity_meter_price" => str_replace(",", "", getIsset('__electricity_meter_price')),
        "water_meter_price" => str_replace(",", "", getIsset('__water_meter_price')),
    );
    if ($conn->create("service", $value))
        $service_id = $conn->getLastInsertId();

    $valuee = array(
        "service_id" => $service_id,
        "datemonth" => getIsset('__month'),

    );
    if ($conn->create("service_log", $valuee)) {
        redirectTo('service.php');
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
                            <input id="__delete_field" name="__delete_field" type="hidden" value="__service_id">
                            <input id="__cmd" name="__cmd" type="hidden" value="">
                            <div class="col-md-12">
                                <label class="col-sm-3 control-label">
                                </label>
                            </div>
                            <div class="clr"></div>
                            <div class="col-sm-12">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">จัดการข้อมูลค่าน้ำ-ค่าไฟ</h3>
                                    </div>

                                    <div class="box-body">
                                        <input type="hidden" name="__service_id" id="__service_id" class="form-control"
                                               value="0"
                                               required="true" readonly>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    เดือน :
                                                </label>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" name="__month" id="__month"
                                                       class="form-control"
                                                       value="" required readonly>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ค่าน้ำ :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__water_meter_price" id="__water_meter_price"
                                                       class="form-control"
                                                       onkeyup="checkComma(this,2);"
                                                       onKeyPress="checkWordNumber(this.value);"
                                                       onblur="checkNumFloat(this,0,999999);"
                                                       onfocus="callDelComma(this);"
                                                       value="" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ค่าไฟ :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__electricity_meter_price"
                                                       id="__electricity_meter_price"
                                                       class="form-control"
                                                       onkeyup="checkComma(this,2);"
                                                       onKeyPress="checkWordNumber(this.value);"
                                                       onblur="checkNumFloat(this,0,999999);"
                                                       onfocus="callDelComma(this);"
                                                       value="" required>
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
                                                <a class="btn btn-default" href="service.php">ย้อนกลับ</a>
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
    $('#menu-servicewater,#menu-service').addClass('active');
    $("#__month").datetimepicker({
        datepicker: true,
        timepicker: false,
        format: 'Y-m-d ',
        lang:'th',
        closeOnDateSelect: true
    });
    function helpReturn(value, action) {
        $.ajax({
            url: 'Allservice.php',
            data: {id: value, action: action},
            method: 'GET',
            success: function (result) {
                var data = JSON.parse(result);
                if (action == "getServiceById") {
                    if (data.service_id != null) {
                        setValueService(data);
                    }
                }

            }
        });
    }

    function setValueService(data) {
        with (document.form_data) {
            $("#__service_id").val(data.service_id);
            $("#__month").val(data.datemonth);
            $("#__electricity_meter_price").val(parseFloatMoney(data.electricity_meter_price));
            $("#__water_meter_price").val(parseFloatMoney(data.water_meter_price));

        }
    }

</script>
<script>helpReturn('<?php echo $service_id;?>', 'getServiceById')</script>
</body>
</html>


