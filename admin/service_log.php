<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");

if ($cmd == "delete") {
    if ($conn->delete("room", array("room_id" => getIsset('__delete_field')))) {
        redirectTo('room.php');
    }
}
$filterDefault = " where 1=1 ";

$keyword = getIsset("keyword");
$option_val = getIsset("option");
if ($keyword != "") {
    $filterDefault .= " and " . $option_val . " like '%" . $keyword . "%'";
}
$sql = "select service_log.*,water_meter_price,electricity_meter_price,datemonth from service_log
left  join service on service_log.service_id = service.service_id";
$result_row = $conn->queryRaw($sql . $filterDefault);//คิวรี่ คำสั่ง
$total = sizeof($result_row);
$for_end = $limit;
$for_start = $start * $limit;

$select_all = $conn->queryRaw($sql . $filterDefault . " order by service_id asc  limit " . $for_start . "," . $for_end);
$total_num = sizeof($select_all);
$service = $conn->select("service", array(), true);
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
                                        <h3 class="box-title">ประวัติข้อมูลค่าน้ำ-ค่าไฟ</h3>
                                    </div>

                                    <div class="box-body">
                                        <div class="form-group">
                                        </div>
                                        <div class="form-group">
                                            <div style="overflow-x: auto" class="col-sm-12">
                                                <table class="table table-hover tbgray" id="tbView">
                                                    <tr>
                                                        <th width="5%">ลำดับ</th>
                                                        <th width="5%">เดือนที่ใช้</th>
                                                        <th width="20%">ราคาค่าน้ำ</th>
                                                        <th width="20%">ราคาค่าไฟ</th>
<!--                                                        <th width="10%">จัดการ</th>-->
                                                    </tr>
                                                    <tbody>
                                                    <?php
                                                    $index = $for_start;
                                                    foreach ($select_all as $row) {
                                                        $index++;
                                                        ?>
                                                        <tr>

                                                            <td class="active" align="center"
                                                                nowrap><?php echo $index; ?></td>
                                                            <td class="active" align="center"
                                                                nowrap><?php echo toDDMMYYYY($row['datemonth']); ?></td>
                                                            <td class="active text-center"
                                                                nowrap><?php echo number_format($row['water_meter_price'],2); ?></td>
                                                            <td class="active text-center"
                                                                nowrap><?php echo number_format($row['electricity_meter_price'],2); ?></td>

<!--                                                            <td class="active" nowrap align="center">-->
<!--                                                                <div class="btn-group">-->
<!--                                                                    <a class="btn btn-warning btn-xs"-->
<!--                                                                       href="service-update.php?__service_id=--><?php //echo $row['service_id']; ?><!--"><i-->
<!--                                                                            class="fa fa-edit"></i> </a>-->
<!--                                                                </div>-->
<!--                                                            </td>-->
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
    $('#menu-servicewaterlog,#menu-service').addClass('active');

</script>
</body>
</html>


