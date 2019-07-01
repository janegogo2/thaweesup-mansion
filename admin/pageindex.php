
<input type="hidden" name="page" id="page" value="<?php echo $page_con; ?>">
<input type="hidden" name="sizeItem" id="sizeItem" value="<?php echo sizeof($result_row); ?>">

<div id="demo"></div>
<script src="../assets/jquery/jquery.min.js"></script>
<script src="../assets/dist/pagination.min.js"></script>
<script>
    var chk = false;
    var tmp = document.getElementById('sizeItem').value;
    var list = [];
    for (var i = 0; i < tmp; i++) {
        list.push(i);
    }
    $('#demo').pagination({
        dataSource: list,
        pageSize: <?php echo $limit;?>,
        pageNumber: <?php echo $page_con;?>,
        callback: function (data, pagination) {
            if (chk) {
                with (document.form_data) {
                    page.value = Math.ceil(pagination.pageNumber);
                    submit();
                }
            }
        }
    });
    chk = true;
</script>