<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");

if ($cmd == "move") {
    if ($conn->update("contract_room", array("status_id" => CANCEL_CONTRACT), array("contract_no" => getIsset('__delete_field')))) {
        redirectTo('contract-request.php');
    }
}
$filterDefault = " where stay_status='" . REQUEST_CHECK_OUT_STATUS . "' and status.status_id='" . ACTIVE_CONTRACT . "' ";

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

$select_all = $conn->queryRaw($sql . $filterDefault . " order by contract_no desc  limit " . $for_start . "," . $for_end);
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
                                        <h3 class="box-title">รายการแจ้งออก</h3>
                                    </div>

                                    <div class="box-body">
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                            </div>
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ค้นหา :
                                                </label>
                                            </div>
                                            <div class="col-sm-2">
                                                <select id="option" name="option" class="dropdown-toggle"
                                                        data-toggle="dropdown">
                                                    <option value="contract_no">เลขที่สัญญา</option>
                                                    <option value="room_name">ชื่อห้องพัก</option>
                                                    <option value="id_card">เลขบัตรประชาชน</option>
                                                    <option value="customer.first_name">ชื่อสมาชิก</option>
                                                    <option value="customer.last_name">นามสกุลสมาชิก</option>
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
                                                        <th width="10%">วันที่แจ้งออก</th>
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
                                                                nowrap><?php echo TODDMMYYYY($row['check_out_date']); ?></td>
                                                            <td class="active" nowrap align="center">
                                                                <div class="btn-group">
                                                                    <a class="btn btn-warning btn-xs"
                                                                       href="contract-request-update.php?__contract_no=<?php echo $row['contract_no']; ?>"><i
                                                                            class="fa fa-edit"></i> </a>
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
    $('#menu-contract-request,#menu-contract').addClass('active');
    function confirmCheckOut(contract_no) {
        if (confirm("ยืนยันการยกเลิกสัญญาหรือไม่")) {
            $("#__cmd").val("move");
            $("#__delete_field").val(contract_no);
            $("#form_data").submit();
        }
    }
</script>
</body>
</html>


