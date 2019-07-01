<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");
if ($cmd == "delete") {
    $conn->delete("employee", array("employee_id" => getIsset('__delete_field')));
    redirectTo('employee.php');
}
$filterDefault = " where 1=1 ";

$keyword = getIsset("keyword");
$option_val = getIsset("option");
if ($keyword != "") {
    $filterDefault .= " and " . $option_val . " like '%" . $keyword . "%'";
}
$sql = "select employee.*,employee_type_name from employee
left join employee_type on employee_type.employee_type_id=employee.employee_type_id ";
$result_row = $conn->queryRaw($sql . $filterDefault);//คิวรี่ คำสั่ง
$total = sizeof($result_row);
$for_end = $limit;
$for_start = $start * $limit;

$select_all = $conn->queryRaw($sql . $filterDefault . " order by employee_id asc  limit " . $for_start . "," . $for_end);
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
                    <form class="form-horizontal" id="form_data" name="form_data" method="post">

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
                                    <h3 class="box-title">จัดการข้อมูลพนักงาน </h3>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <a class="btn btn-primary" href="employee-update.php"><i
                                                    class="fa fa-plus"></i> เพิ่มข้อมูล</a>
                                        </div>
                                        <div align="right">
                                            <label class="col-sm-3 control-label">
                                                ค้นหา :
                                            </label>
                                        </div>
                                        <div class="col-sm-2 text-right">
                                            <select id="option" name="option" class="dropdown-toggle"
                                                    data-toggle="dropdown">
                                                <option value="first_name">ชื่อ</option>
                                                <option value="last_name">นามสกุล</option>
                                                <option value="email">อีเมล์</option>
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
                                        <div class="col-sm-12">
                                            <div class=" table-responsive">
                                                <table class="table table-hover tbgray" id="tbView">
                                                    <tr>
                                                        <th width="5%">ลำดับ</th>
                                                        <th width="20%">ชื่อผู้ดูแลระบบ</th>
                                                        <th width="10%">อีเมล์</th>
                                                        <th width="10%">เบอร์โทรศัพท์</th>
                                                        <th width="10%">ระดับผู้ใช้</th>
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
                                                            <td class="active"
                                                                nowrap><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                                            <td class="active"
                                                                nowrap><?php echo $row['email']; ?></td>
                                                            <td class="active"
                                                                nowrap><?php echo $row['phone']; ?></td>
                                                            <td class="active"
                                                                nowrap><?php echo $row['employee_type_name']; ?></td>
                                                            <td class="active" nowrap align="center">
                                                                <div class="btn-group">
                                                                    <a class="btn btn-warning btn-xs"
                                                                       href="employee-update.php?__employee_id=<?php echo $row['employee_id']; ?>"><i
                                                                            class="fa fa-edit"></i> </a>
                                                                    <?php if ($row['employee_id'] != $uprofile['id']) { ?>
                                                                        <a class="btn btn-danger btn-xs"
                                                                           onclick="deleteRowData('<?php echo $row['employee_id']; ?>')"><i
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
    $('#menu-user').addClass('active');
    $('#menu-employee').addClass('active');
</script>
</body>
</html>


