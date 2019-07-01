<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");


$year = getIsset("__year");
$month = getIsset("__month");
if ($year == "")
    $year = date("Y");

if ($month == "")
    $month = date("m");

$filterDefault = " where status.status_id='" . ACTIVE_CONTRACT . "' 
and stay_status in (" . STAY_STATUS . "," . REQUEST_CHECK_OUT_STATUS .  ") 
and  STR_TO_DATE(
	'$year-$month',
	'%Y-%m') BETWEEN STR_TO_DATE(
	concat(
		YEAR (start_date),
		'-',
		MONTH (start_date)+1
	),
	'%Y-%m'
)
AND STR_TO_DATE(
	concat(
		YEAR (end_date),
		'-',
		MONTH (end_date)
	),
	'%Y-%m'
)
 
";


$sql = "select contract_room.*,status_name,room_name,concat(customer.first_name,' ',customer.last_name) as customer_name,id_card from contract_room
left join customer on customer.customer_id=contract_room.customer_id
left join status on status.status_id=contract_room.status_id
left join room on room.room_id=contract_room.room_id
";
$result_row = $conn->queryRaw($sql . $filterDefault);//คิวรี่ คำสั่ง
$total = sizeof($result_row);
$for_end = $limit;
$for_start = $start * $limit;

$select_all = $conn->queryRaw($sql . $filterDefault . " order by contract_no asc  limit " . $for_start . "," . $for_end);
$total_num = sizeof($select_all);

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
                                        <h3 class="box-title">ค้นหา</h3>
                                    </div>

                                    <div class="box-body">
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ปี :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <input type="text" name="__year"
                                                       id="__year" class="form-control"
                                                       value="<?php echo $year; ?>" required maxlength="4"
                                                       onkeypress="chkInteger(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    เดือน :
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                                <select name="__month" id="__month">
                                                    <?php
                                                    foreach ($strMonthCut as $index => $item) {
                                                        $id = str_pad($index + 1, 2, '0', STR_PAD_LEFT)
                                                        ?>
                                                        <option <?php echo $index +1 == $month ? "selected" : ""; ?>
                                                                value="<?php echo $index+1; ?>"><?php echo $item; ?></option>
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
                                                <a class="btn btn-success" href="javascript:goSave();">ค้นหา</a>
                                                <a class="btn btn-warning" href="save-payment.php">เคลียร์</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">บันทึกมิเตอร์</h3>
                                    </div>

                                    <div class="box-body">
                                        <div class="form-group">
                                            <div style="overflow-x: auto" class="col-sm-12">
                                                <table class="table table-hover tbgray" id="tbView">
                                                    <tr>
                                                        <th width="5%">ลำดับ</th>
                                                        <th width="10%">เลขที่สัญญา</th>
                                                        <th width="15%">ชื่อผู้เข้าพัก</th>
                                                        <th width="15%">เลขบัตรประชาชน</th>
                                                        <th width="15%">ชื่อห้องพัก</th>
                                                        <th width="10%">วันที่เริ่มสัญญา</th>
                                                        <th width="10%">วันที่หมดสัญญา</th>
                                                        <th width="10%">สถานะ</th>
                                                        <th width="10%">บันทึก</th>
                                                    </tr>
                                                    <tbody>
                                                    <?php
                                                    $index = $for_start;
                                                    foreach ($select_all as $row) {
                                                        $payment = $conn->queryRaw("select * from payment_room where 
                                                                                        YEAR(save_date) = '$year' and MONTH(save_date) = '$month'
                                                            and contract_no = '" . $row['contract_no'] . "' and is_check_out = 0", true);
                                                        $index++;
                                                        ?>
                                                        <tr>
                                                            <td class="active" align="center"
                                                                nowrap><?php echo $index; ?></td>
                                                            <td class="active text-center"
                                                                nowrap><?php echo $row['contract_no']; ?></td>
                                                            <td class="active"
                                                                nowrap><?php echo $row['customer_name']; ?></td>
                                                            <td class="active"
                                                                nowrap><?php echo $row['id_card']; ?></td>
                                                            <td class="active"
                                                                nowrap><?php echo $row['room_name']; ?></td>
                                                            <td class="active text-center"
                                                                nowrap><?php echo TODDMMYYYY($row['start_date']); ?></td>
                                                            <td class="active text-center"
                                                                nowrap><?php echo TODDMMYYYY($row['end_date']); ?></td>
                                                            <td class="active text-center"
                                                                nowrap>
                                                                <?php echo $payment['status_id'] == SAVE_PAYMENT ? "บันทึกมิเตอร์แล้ว" : ($payment['status_id'] == PAYMENT_SUCCESS ? "ยืนยันมิเตอร์แล้ว" : ($payment['status_id'] == WAITING_PAYMENT ? "ยืนยันมิเตอร์แล้ว" : "รอบันทึกมิเตอร์")); ?></td>
                                                            <td class="active" nowrap align="center">
                                                                <div class="btn-group">
                                                                    <?php if ($payment == null) { ?>
                                                                        <a class="btn btn-warning btn-xs"
                                                                           href="save-payment-update.php?__contract_no=<?php echo $row['contract_no']; ?>&__year=<?php echo $year; ?>&__month=<?php echo $month; ?>"><i
                                                                                    class="fa fa-edit"></i> </a>
                                                                    <?php } else { ?>
                                                                        <?php if ($payment['status_id'] == SAVE_PAYMENT) { ?>
                                                                            <a class="btn btn-warning btn-xs"
                                                                               href="save-payment-update.php?__contract_no=<?php echo $row['contract_no']; ?>&__year=<?php echo $year; ?>&__month=<?php echo $month; ?>&__payment_no=<?php echo $payment['payment_no']; ?>"><i
                                                                                        class="fa fa-edit"></i> </a>
                                                                        <?php } else { ?>
                                                                            <a class="btn btn-info btn-xs"
                                                                               target="_blank"
                                                                               href="save-payment-print.php?__payment_no=<?php echo $payment['payment_no']; ?>&__year=<?php echo $year; ?>&__month=<?php echo $month; ?>"><i
                                                                                        class="fa fa-print"></i> </a>
                                                                        <?php }
                                                                    } ?>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-sm-12">
                                                <?php include "pageindex.php"; ?>
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
    $('#menu-save-payment,#menu-payment').addClass('active');

</script>
</body>
</html>


