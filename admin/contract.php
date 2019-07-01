<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");

if ($cmd == "delete") {
    if ($conn->update("contract_room", array("status_id" => CANCEL_CONTRACT), array("contract_no" => getIsset('__delete_field')))) {
        redirectTo('contract.php');
    }
}
$filterDefault = " where 1=1 ";

$keyword = getIsset("keyword");
$option_val = getIsset("option");
if ($keyword != "") {
    $filterDefault .= " and " . $option_val . " like '%" . $keyword . "%'";
}
$sql = "select contract_room.*,status_name,room_name,concat(customer.first_name,' ',customer.last_name) as customer_name,id_card from contract_room
left join customer on customer.customer_id=contract_room.customer_id
left join status on status.status_id=contract_room.status_id
left join room on room.room_id=contract_room.room_id";
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
                                        <h3 class="box-title">จัดการข้อมูลสัญญา</h3>
                                    </div>

                                    <div class="box-body">
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <a class="btn btn-primary" href="contract-update.php"><i
                                                        class="fa fa-plus"></i> เพิ่มข้อมูล</a>
                                            </div>
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ค้นหา :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <select id="option" name="option" class="dropdown-toggle"
                                                        data-toggle="dropdown">
                                                    <option value="contract_no"<?php if(isset($_POST['option']) && $_POST['option'] == "contract_no") echo 'selected="selected"';?>>เลขที่สัญญา</option>
                                                    <option value="room_name"<?php if(isset($_POST['option']) && $_POST['option'] == "room_name") echo 'selected="selected"';?>>ชื่อห้องพัก</option>
                                                    <option value="id_card"<?php if(isset($_POST['option']) && $_POST['option'] == "id_card") echo 'selected="selected"';?>>เลขบัตรประชาชน</option>
                                                    <option value="customer.first_name"<?php if(isset($_POST['option']) && $_POST['option'] == "customer.first_name") echo 'selected="selected"';?>>ชื่อสมาชิก</option>
                                                    <option value="customer.last_name"<?php if(isset($_POST['option']) && $_POST['option'] == "customer.last_name") echo 'selected="selected"';?>>นามสกุลสมาชิก</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="input-group">
                                                    <input class="form-control" type="text" id="keyword" name="keyword"
                                                           onblur="trimValue(this)"
                                                           value="<?php echo $keyword; ?>">
                                                    <a href="javascript:goSearch();"
                                                       class="btn btn-default input-group-addon"><i
                                                            class="fa fa-search"></i> </a>
                                                </div>
                                            </div>

                                        </div>
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
                                                        <th width="10%">จัดการ</th>
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
                                                                nowrap><?php echo $row['status_name']; ?></td>
                                                            <td class="active" nowrap align="center">
                                                                <div class="btn-group">
                                                                     <?php if ($row['status_id'] == ACTIVE_CONTRACT && $row['stay_status'] == NOTSTAY_STATUS) { ?>
                                                                        <a class="btn btn-info btn-xs" target="_blank"
                                                                           href="print-deposit.php?__contract_no=<?php echo $row['contract_no']; ?>"><i
                                                                                class="fa fa-print"></i> </a>
                                                                    <?php } ?>
                                                                    <?php if ($row['status_id'] == ACTIVE_CONTRACT && $row['stay_status'] == STAY_STATUS) { ?>
                                                                        <a class="btn btn-success btn-xs"
                                                                           data-toggle="tooltip" title="แจ้งออก"
                                                                           href="contract-request-move.php?__contract_no=<?php echo $row['contract_no']; ?>"><i
                                                                                class="fa fa-clock-o"></i> </a>
                                                                    <?php } ?>
                                                                    <?php if ($row['status_id'] == CANCEL_CONTRACT && $row['stay_status'] == MOVE_CHECK_OUT_STATUS) { ?>
                                                                        <a class="btn btn-info btn-xs" target="_blank"
                                                                           href="contract-request-print.php?__contract_no=<?php echo $row['contract_no']; ?>"><i
                                                                                class="fa fa-print"></i> </a>
                                                                    <?php } ?>
                                                                    <a class="btn btn-warning btn-xs"
                                                                       href="contract-update.php?__contract_no=<?php echo $row['contract_no']; ?>"><i
                                                                            class="fa fa-edit"></i> </a>
                                                                    <?php if ($row['status_id'] != CANCEL_CONTRACT) { ?>
                                                                    <a class="btn btn-danger btn-xs"
                                                                       onclick="deleteRowData('<?php echo $row['contract_no']; ?>')"><i
                                                                            class="fa fa-trash"></i> </a>
                                                                    <?php } ?>
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
    $('#menu-contract,#menu-contract-management').addClass('active');
    function deleteRowData(value) {
        if (confirm('ต้องการยกเลิกสัญญาหรือไม่ ?')) {
            with (document.form_data) {
                __delete_field.value = value;
                __cmd.value = "delete";
                submit();
            }
        }
    }
</script>
</body>
</html>


