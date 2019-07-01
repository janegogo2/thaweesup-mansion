<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";


$month = getIsset("__month");
$month2 = getIsset("__month2");


$header_list = $conn->queryRaw("select distinct DATE(contract_datetime) as contract_datetime from contract_room
 where DATE(contract_datetime) >= '$month' and DATE(contract_datetime) <= '$month2' ");


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
                                        <h3 class="box-title">รายงานทำสัญญา</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    เดือน :
                                                </label>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" name="__month" id="__month"
                                                       class="form-control" value="<?php echo getIsset("__month") ?>"
                                                       readonly>
                                            </div>
                                            <div align="right">
                                                <label class="col-sm-1 control-label">
                                                    -
                                                </label>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" name="__month2" id="__month2"
                                                       class="form-control" value="<?php echo getIsset("__month2") ?>"
                                                       readonly>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                                <a class="btn btn-success" href="javascript:goSearch();">ค้นหา</a>
                                                <a class="btn btn-success" href="javascript:goPrint();">พิมพ์รายงาน</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            foreach ($header_list as $item) {
                                ?>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-md-3">

                                            <table width="100%" border="1" cellspacing="0" cellpadding="1"
                                                   align="center">
                                                <tbody>
                                                <tr>
                                                    <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">วัน/เดือน/ปี
                                                    </th>
                                                </tr>
                                                </tbody>
                                                <tr>
                                                    <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: center;"><?php echo $item['contract_datetime']; ?></td>
                                                </tr>

                                            </table>
                                        </div>
                                        <div class="col-md-9">
                                            <table width="100%" border="1" cellspacing="0" cellpadding="1"
                                                   align="center">
                                                <tbody>
                                                <tr>
                                                    <th style="padding: 7px;
    font-size: 10px;
    background-color: #D5D5D5;
    text-align: center;" width="50px">ลำดับ
                                                    </th>
                                                    <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">เลขที่ห้อง
                                                    </th>
                                                    <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">ชื่อลูกค้า
                                                    </th>
                                                    <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">เข้า
                                                    </th>
                                                    <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">ออก
                                                    </th>
                                                    <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">มัดจำ
                                                    </th>
                                                    <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">ประกัน
                                                    </th>
                                                    <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">สถานะ
                                                    </th>
                                                </tr>
                                                <?php
                                                $result_row = $conn->queryRaw("select contract_room.*,status_name,room_name,customer.phone as phone
,concat(customer.first_name,' ',customer.last_name) as customer_name
,customer.id_card as id_card,customer.email as email,room_price,customer.first_name as first_name,customer.last_name as last_name
from contract_room
left join customer on customer.customer_id=contract_room.customer_id
left join employee on employee.employee_id=contract_room.employee_id
left join status on status.status_id=contract_room.status_id
left join room on room.room_id=contract_room.room_id
where  DATE(contract_datetime)  = '" . date("Y-m-d", strtotime($item['contract_datetime'])) . "'  ");
                                                foreach ($result_row as $index => $row) {
                                                    ?>
                                                    <tr>
                                                        <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo $index + 1; ?></td>
                                                        <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: left;"><?php echo $row['room_name']; ?></td>
                                                        <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: left;"><?php echo $row['customer_name']; ?></td>
                                                        <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: center;"><?php echo $row['start_date']; ?></td>
                                                        <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: center;"><?php echo $row['end_date']; ?></td>
                                                        <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo $row['deposit_price']; ?></td>
                                                        <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo $row['insurance_price']; ?></td>
                                                        <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: left;"><?php echo $row['status_name']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            <?php } ?>
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
    $('#menu-contract_room,#menu-report2').addClass('active');
    $("#__month,#__month2").datetimepicker({
        datepicker: true,
        timepicker: false,
        format: 'Y-m-d',
        closeOnDateSelect: true
    });
    function goPrint() {
        if (!required()) {
            if (confirm("ต้องการพิมพ์รายงานหรือไม่")) {
                var month = $("#__month").val();
                var month2 = $("#__month2").val();
                window.open("print-contract-completed.php?month2=" + month2 + "&month=" + month , "_blank");
            }
        }
    }
</script>
</body>
</html>


