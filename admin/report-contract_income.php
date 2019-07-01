<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";
$filterDefault = " where 1=1 ";

$month = getIsset("__month");
$month2 = getIsset("__month2");


$header_list = $conn->queryRaw("select distinct payment_room.contract_no
from  payment_room
left join contract_room on payment_room.contract_no=contract_room.contract_no
left join customer on customer.customer_id=contract_room.customer_id
left join room on room.room_id=contract_room.room_id
where payment_room.status_id= 22 and  DATE(payment_room.payment_date) >= '$month' and DATE(payment_room.payment_date) <= '$month2'  ");


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
                                        <h3 class="box-title">รายงานรายได้</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    วันที่ :
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
                                        <div class="col-md-12">
                                            <label class="col-sm-2 control-label">เลขที่สัญญา</label>
                                            <div
                                                class="col-sm-10 form-control-static"><?php echo str_pad($item['contract_no'], 10, '0', STR_PAD_LEFT); ?></div>
                                        </div>
                                        <div class="col-md-12">
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
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">ชื่อผู้อยู่
                                                    </th>
                                                    <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">ห้อง
                                                    </th>
                                                    <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">วันรับเงิน
                                                    </th>
                                                    <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">ราคา
                                                    </th>
                                                    <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">ค่าน้ำ
                                                    </th>
                                                    <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">ค่าไฟ
                                                    </th>
                                                    <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">ค่าปรับ
                                                    </th>
                                                </tr>
                                                <?php
                                                $result_row = $conn->queryRaw("select room_name,customer.phone as phone
,concat(customer.first_name,' ',customer.last_name) as customer_name
,payment_room.room_price,payment_room.water_price,payment_room.electricity_price,payment_room.mulct_price
,payment_room.total_price,payment_room.payment_date
from  payment_room
left join contract_room on payment_room.contract_no=contract_room.contract_no
left join customer on customer.customer_id=contract_room.customer_id
left join room on room.room_id=contract_room.room_id
where payment_room.status_id= 22 and  DATE(payment_room.payment_date) >= '$month' and DATE(payment_room.payment_date) <= '$month2' and contract_room.contract_no='" . $item['contract_no'] . "'  ");
                                                $total_room = 0;
                                                $total_water = 0;
                                                $total_elect = 0;
                                                $total_mulct = 0;
                                                foreach ($result_row as $index => $row) {
                                                    $total_room += $row['room_price'];
                                                    $total_water += $row['water_price'];
                                                    $total_elect += $row['electricity_price'];
                                                    $total_mulct += $row['mulct_price'];
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
    text-align: left;"><?php echo $row['customer_name']; ?></td>
                                                        <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: center;"><?php echo $row['room_name']; ?></td>
                                                        <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: center;"><?php echo $row['payment_date']; ?></td>
                                                        <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo number_format($row['room_price'], 2); ?></td>
                                                        <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo number_format($row['water_price'], 2); ?></td>
                                                        <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo number_format($row['electricity_price'], 2); ?></td>
                                                        <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo number_format($row['mulct_price'], 2); ?></td>
                                                    </tr>
                                                <?php } ?>
                                                <tr>
                                                    <th style="padding: 7px;
    font-size: 10px;
    background-color: #D5D5D5;
    text-align: center;" colspan="4">รวม
                                                    </th>
                                                    <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo number_format($total_room, 2); ?></td>
                                                    <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo number_format($total_water, 2); ?></td>
                                                    <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo number_format($total_elect, 2); ?></td>
                                                    <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo number_format($total_mulct, 2); ?></td>
                                                </tr>
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
</script>
</body>
</html>


