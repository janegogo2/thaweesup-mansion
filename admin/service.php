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
$sql = "select * from service $filterDefault order by service_id desc limit 0,1
";
$result_row = $conn->queryRaw($sql );//คิวรี่ คำสั่ง
$total = sizeof($result_row);
$for_end = $limit;
$for_start = $start * $limit;

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
                                        <h3 class="box-title">จัดการข้อมูลค่าน้ำ-ค่าไฟ</h3>
                                    </div>

                                    <div class="box-body">
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <a class="btn btn-primary" href="service-update.php"><i
                                                            class="fa fa-plus"></i> เปลี่ยนค่าน้ำค่าไฟ</a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div style="overflow-x: auto" class="col-sm-12">
                                                <table class="table table-hover tbgray" id="tbView">
                                                    <tr>
                                                        
                                                        <th width="20%">ราคาค่าน้ำ</th>
                                                        <th width="20%">ราคาค่าไฟ</th>
<!--                                                        <th width="10%">จัดการ</th>-->
                                                    </tr>
                                                    <tbody>
                                                    <?php
                                                    $index = $for_start;
                                                    foreach ($result_row as $row) {
                                                        $index++;
                                                        ?>
                                                        <tr>
                                                          
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

</script>
</body>
</html>


