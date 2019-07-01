<?php
session_start();
require_once '../mpdf/mpdf.php';
require_once '../connection.inc.php';

$room = getIsset("room");
$year = getIsset("year");
$sql = "select contract_room.*,status_name,room_name,customer.phone as phone
,concat(employee.first_name,' ',employee.last_name) as employee_name
,customer.id_card as id_card,customer.email as email,room_price,customer.first_name as first_name,customer.last_name as last_name
from contract_room
left join customer on customer.customer_id=contract_room.customer_id
left join employee on employee.employee_id=contract_room.employee_id
left join status on status.status_id=contract_room.status_id
left join room on room.room_id=contract_room.room_id where  contract_no 
and contract_room.room_id='$room'or contract_no and $room='0'" ;
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
    <div style="text-align: center;">รายงานค่าเช่า</div>
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
    text-align: center;">เบอร์โทร
            </th>
            <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">อีเมล์
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
    text-align: center;">เริ่มสัญญา
            </th>
            <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">สิ้นสุด
            </th>
            <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">ราคาห้อง
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
    text-align: left;"><?php echo $row['first_name'].' '.$row['last_name']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: left;"><?php echo $row['phone']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: left;"><?php echo $row['email']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: left;"><?php echo $row['room_name']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: center;"><?php echo $row['start_date']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: center;"><?php echo $row['end_date']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo $row['room_price']; ?></td>
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