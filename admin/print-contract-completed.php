<?php
session_start();
require_once '../mpdf/mpdf.php';
require_once '../connection.inc.php';


$month = getIsset("month");
$month2 = getIsset("month2");

$header_list = $conn->queryRaw("select distinct DATE(contract_datetime) as contract_datetime from contract_room
 where DATE(contract_datetime) >= '$month' and DATE(contract_datetime) <= '$month2' ");

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
    <div style="text-align: center;">รายงานการทำสัญญา</div>
    <br>

    <?php
    foreach ($header_list as $item) {
        ?>
        <div class="col-sm-12">
            <div class="form-group">

               วัน/เดือน/ปี :<?php echo $item['contract_datetime']; ?>
                <table width="100%" border="1" cellspacing="0" cellpadding="1"
                       align="center">
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
    text-align: center;">เลขที่ห้อง
                        </th>
                        <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">ชื่อลูกค้า
                        </th>
                        <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">เข้า
                        </th>
                        <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">ออก
                        </th>
                        <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">มัดจำ
                        </th>
                        <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">ประกัน
                        </th>
                        <th style="padding: 7px;
    font-size: 10px;
    width: 15%;
    background-color: #D5D5D5;
    text-align: center;">สถานะ
                        </th>
                    </tr>
                    <?php
                    $result_row = $conn->queryRaw("select contract_room.*,status_name,room_name,customer.phone as phone
,concat(customer.first_name,' ',customer.last_name) as customer_name
,customer.id_card as id_card,customer.email as email,room_price,customer.first_name as first_name,customer.last_name as last_name
from contract_room
left join customer on customer.customer_id=contract_room.customer_id
left join employee on employee.employee_id=contract_room.employee_id
left join status on status.status_id=contract_room.status_id
left join room on room.room_id=contract_room.room_id
where  DATE(contract_datetime)  = '" . date("Y-m-d", strtotime($item['contract_datetime'])) . "'  ");
                    foreach ($result_row as $index => $row) {
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
    text-align: left;"><?php echo $row['room_name']; ?></td>
                            <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: left;"><?php echo $row['customer_name']; ?></td>
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
    text-align: right;"><?php echo $row['deposit_price']; ?></td>
                            <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: right;"><?php echo $row['insurance_price']; ?></td>
                            <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: left;"><?php echo $row['status_name']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>
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