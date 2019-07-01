<?php
session_start();
require_once '../mpdf/mpdf.php';
require_once '../connection.inc.php';

$sql = "select payment_room.*,room_name,contract_room.contract_no as contract_no
,concat(customer.first_name,' ',customer.last_name) as customer_name
from payment_room
left join contract_room on contract_room.contract_no=payment_room.contract_no
left join customer on customer.customer_id=contract_room.customer_id
left join employee on employee.employee_id=contract_room.employee_id
left join room on room.room_id=contract_room.room_id where  payment_room.status_id = '" . WAITING_PAYMENT . "'";
$list = $conn->queryRaw($sql);
ob_start();
?>
    <html>
    <head>
        <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">

    </head>
    <body>
    <span style="font-size: 0.6em">พิมพ์เมื่อ <?php echo DateThai() ?></span>

    <div style="font-size: 18px;
    font-weight: bold;
    text-align: center;">
        <?php echo TITLE_THAI; ?>
    </div>
    <div style="font-size: 14px;;
    text-align: center;">
        <?php echo ADDRESS; ?>
    </div>
    <div style="text-align: center;">รายงานยอดค้างชำระ</div>
    <br>
    <table width="100%" border="1" cellspacing="0" cellpadding="1" align="center">
        <tbody>
        <tr>
            <th style="padding: 7px;
    font-size: 10px;
    background-color: #D5D5D5;
    text-align: center;" width="50px">ลำดับ
            </th>
            <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">เลขที่สัญญา
            </th>
            <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">ผู้เช่า
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
    text-align: center;">ราคาห้อง
            </th>
            <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">ค่าน้ำ
            </th>
            <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">ค่าไฟ
            </th>
            <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">ยอดรวม
            </th>
        </tr>
        <?php
        foreach ($list as $index => $row) {
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
    text-align: center;"><?php echo str_pad($row['contract_no'], 10, '0', STR_PAD_LEFT); ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: left;"><?php echo $row['customer_name']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: left;"><?php echo $row['room_name']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo $row['room_price']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo $row['water_price']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo $row['electricity_price']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo $row['room_price'] + $row['water_price'] + $row['electricity_price']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <br>
    </body>
    </html>
<?php
$html = ob_get_contents();
ob_end_clean();
$pdf = new mPDF('th', 'A4-L', '0', 'TH SarabunPSK');
$pdf->autoPageBreak = true;
$pdf->WriteHTML($html, 2);
$pdf->Output();
?>