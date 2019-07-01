<?php
session_start();
require_once '../connection.inc.php';
require_once '../common.inc.php';
require_once 'help_filter.inc.php';


$result = array();
$result_row = array();
$for_end = $limit;
$for_start = $start * $limit;
if (isset($filter['sql'])) {
    $result = $conn->queryRaw($filter['sql']." ". $filter['order_by']  . " limit " . $for_start . "," . $for_end);
    $result_row = $conn->queryRaw($filter['sql']);
}
$Qtotal = sizeof($result_row);
$total = $Qtotal;
$keyword = getIsset('keyword');
$option_val = getIsset('option');


?>

<html lang="th">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo $filter['title']; ?> :: Help</title>

    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"/>
    <!-- Font Awesome Icons -->
    <link href="../dist/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <!-- Ionicons -->
    <!--    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />-->
    <!-- Theme style -->
    <link href="../dist/css/AdminLTE.css" rel="stylesheet" type="text/css"/>

    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link href="../dist/css/skins/_all-skins.css" rel="stylesheet" type="text/css"/>
    <link href="../assets/dist/pagination.css" rel="stylesheet">
    <link href="../assets/css/custom.css" rel="stylesheet">
    <style>

        #demo, .paginationjs {
            display: flex;
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body class="bg-gray-custom">
<section class="content">
    <form class="form-horizontal" name="form_data" method="post">
        <input type="hidden" name="__filter" value="<?php echo getIsset('__filter'); ?>">
        <input type="hidden" name="__action" id="__action" value="<?php echo getIsset('__action'); ?>">
        <?php if (isset($filter['column'])) { ?>

            <div class="row">
                <div class="col-sm-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $filter['title']; ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label class="control-label col-sm-3">ค้นหาตาม : &nbsp;</label>
                                    <select name="option" class="col-sm-3" onchange="focusText()">
                                        <?php foreach ($filter['options'] as $key => $value) { ?>
                                            <option
                                                value="<?php echo $key ?>" <?php echo $key == $option_val ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                                        <?php } ?>
                                    </select>
                                    <div class="input-group  col-sm-6">
                                        <input type="text" class="form-control pull-right" name="keyword"
                                               id="keyword"
                                               onblur="trimValue(this)" value="<?php echo $keyword; ?>">
                                        <div class="input-group-btn">
                                            <input class="btn btn-default" type="submit" value="ค้นหา">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <table class="table table-hover tbgray">
                                        <?php foreach ($filter['column'] as $key => $value) { ?>
                                            <th><?php echo $value; ?></th>
                                        <?php } ?>
                                        <tbody>
                                        <?php foreach ($result as $row) { ?>
                                            <tr style="cursor: pointer;"
                                                onclick="javascript:returnToParent('<?php echo $row[$filter['key_id']]; ?>');">
                                                <?php foreach ($filter['column'] as $key => $value) { ?>
                                                    <td class="active" align="center">
                                                        <?php if ($key == $filter['key_id']) { ?>
                                                            <a href="#"
                                                               onclick="returnToParent('<?php echo $row[$key]; ?>')"><?php echo $row[$key]; ?></a>
                                                        <?php } else { ?>
                                                            <?php echo $row[$key]; ?>
                                                        <?php } ?>

                                                    </td>
                                                <?php } ?>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <?php include "pageindex.php"; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

    </form>
</section>
<input type="hidden" name="freturn" value="<?php echo getIsset('__freturn'); ?>">
<?php require_once 'javascript.php'; ?>
<script type="text/javascript">

    function returnToParent(value) {
        var action = document.getElementById('__action').value;
        window.opener.helpReturn(value, action);
        window.close();

    }
</script>
<script>focusText();</script>
</body>
</html>