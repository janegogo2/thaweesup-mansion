<?php
session_start();
require_once "../common.inc.php";
if (!is_list_session(array(ADMIN_LEVEL)))
    redirect_to('index.php');

require_once "../connection.inc.php";

$cmd = getIsset("__cmd");

$customer_id = getIsset('__customer_id');
if ($cmd == "save") {
    $chk_id_card = $conn->queryRaw("select * from customer where id_card='" . getIsset('__id_card') . "' and customer_id<>'$customer_id' ");
    if ($chk_id_card != null) {
        alertMassage("เลขบัตรประชาชนมีอยู่ในระบบแล้ว");
    } else {
        $value = array(
            "first_name" => getIsset('__first_name'),
            "last_name" => getIsset('__last_name'),
            "email" => getIsset('__email'),
            "address" => getIsset('__address'),
            "phone" => getIsset('__phone'),
            "id_card" => getIsset('__id_card'),
            "picture" => uploadFile($_FILES['__file_upload'], getIsset('__file_name'), PATH_UPLOAD),
        );
        if ($customer_id == "0") {
            if ($conn->create("customer", $value)) {
                redirectTo('customer.php');
            }

        } else {
            if ($conn->update("customer", $value, array("customer_id" => $customer_id))) {
                redirectTo('customer.php');
            }
        }
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
                            <input id="__delete_field" name="__delete_field" type="hidden" value="__customer_id">
                            <input id="__cmd" name="__cmd" type="hidden" value="">
                            <div class="col-md-12">
                                <label class="col-sm-3 control-label">
                                </label>
                            </div>
                            <div class="clr"></div>
                            <div class="col-sm-12">
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">จัดการข้อมูลลูกค้า</h3>
                                    </div>

                                    <div class="box-body">
                                        <input type="hidden" name="__customer_id" id="__customer_id"
                                               class="form-control"
                                               value="0"
                                               required="true" readonly>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    รูปลูกค้า :
                                                </label>
                                            </div>
                                            <div class="col-sm-3">
                                                <img id="img-preview" src="" class="image img-thumbnail"
                                                     onerror="src='../dist/img/user2-160x160.jpg'">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">

                                            </label>

                                            <div class="col-sm-5">
                                                <input type="file" name="__file_upload" style="display: none">
                                                <input type="hidden" name="__file_name" id="__file_name">
                                                <a type="button" class="btn btn-success btn-xs upload-logo">อัพโหลด</a>
                                                <a type="button" class="btn btn-danger btn-xs del-logo">ลบไฟล์</a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ชื่อ :
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="text" name="__first_name" id="__first_name"
                                                       class="form-control"
                                                       value=""
                                                       onblur="trimValue(this);" required="true">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    นามสกุล :
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                                <input type="text" name="__last_name" id="__last_name"
                                                       class="form-control"
                                                       value=""
                                                       onblur="trimValue(this);" required="true">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    อีเมล์ :
                                                </label>
                                            </div>
                                            <div class="col-sm-4">
                                                <input type="email" name="__email" id="__email" class="form-control"
                                                       value=""
                                                       onblur="trimValue(this);"  required="true"
                                                       onkeypress="chkNotThaiChaOnly(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    เลขบัตรประชาชน :
                                                </label>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" name="__id_card" id="__id_card" class="form-control"
                                                       value=""
                                                       onblur="trimValue(this);" maxlength="13" required
                                                       onkeypress="chkInteger(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    เบอร์โทรศัพท์ :
                                                </label>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="text" name="__phone" id="__phone" class="form-control"
                                                       value=""
                                                       onblur="trimValue(this);" maxlength="10" required
                                                       onkeypress="chkInteger(event)">
                                                        
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    ที่อยู่ :
                                                </label>
                                            </div>
                                            <div class="col-sm-6">
                                                <textarea id="__address" name="__address" class="form-control"
                                                          onblur="trimValue(this);" rows="6"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group hidden">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                    รหัสผ่าน :
                                                </label>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="password" name="__password" id="__password"
                                                       class="form-control"
                                                       value="" required
                                                       onblur="trimValue(this);" maxlength="20"
                                                       onkeypress="chkNotThaiChaOnly(event)">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div align="right">
                                                <label class="col-sm-3 control-label">
                                                </label>
                                            </div>
                                            <div class="col-sm-5">
                                                <a class="btn btn-success" href="javascript:gogo();">บันทึก</a>
                                                <a class="btn btn-warning" href="javascript:goClear()">เคลียร์</a>
                                                <a class="btn btn-default" href="customer.php">ย้อนกลับ</a>
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
    $('#menu-customer').addClass('active');
    function helpReturn(value, action) {
        $.ajax({
            url: 'Allservice.php',
            data: {id: value, action: action},
            method: 'GET',
            success: function (result) {
                var data = JSON.parse(result);
                if (data.customer_id != null) {
                    setValueCustomer(data);
                }
            }
        });
    }
    function setValueCustomer(data) {
        with (document.form_data) {
            $("#__customer_id").val(data.customer_id);
            $("#__first_name").val(data.first_name);
            $("#__last_name").val(data.last_name);
            $("#__password").val(data.password);
            $("#__phone").val(data.phone);
            $("#__email").val(data.email);
            $("#__address").val(data.address);
            $("#__file_name").val(data.picture);
            $("#__id_card").val(data.id_card);
            $("#img-preview").attr('src', '<?php echo PATH_UPLOAD . '/';?>' + data.picture);
        }
    }

</script>
    <script type='text/javascript'>
    function gogo() {
        var email = $("#__email").val();
            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))
            { 
               var idmsg = 'โปรดกรอกหมายเลขบัตรประชาชนให้สมบูรณ์';
               var idcard = $("#__id_card").val(); 
               testid = new String(idcard);
                if ( testid.length != 13)
                {
                    alert(idmsg);                    
                }
                else
                {
               var msg = 'โปรดกรอกหมายเลขโทรศัพท์ 10 หลัก ด้วยรูปแบบดังนี้ 08XXXXXXXX ไม่ต้องใส่เครื่องหมายขีด (-) วงเล็บหรือเว้นวรรค';
               var phonenum = $("#__phone").val(); 
               testphone = new String(phonenum);
                if ( testphone.length != 10)
                    {
                        alert(msg);                    
                    }
                else
                    {
                        goSave();
                    }
                }   
            }
            else
            {
                alert("อีเมล์ของคุณไม่ถูกต้องโปรดตรวจสอบใหม่อีกครั้ง")
            }
     }
            
   

</script>
<script>helpReturn('<?php echo $customer_id;?>', 'getCustomerById')</script>
</body>
</html>


