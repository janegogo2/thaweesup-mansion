<?php
session_start();
require_once '../mpdf/mpdf.php';
require_once '../connection.inc.php';

$payment = $conn->select("payment_room", array("payment_no" => getIsset("__payment_no")), true);
$customer = $conn->queryRaw("select customer.*,room_name from customer
left join contract_room on contract_room.customer_id=customer.customer_id
left join room on room.room_id=contract_room.room_id
 where contract_no='" . $payment['contract_no'] . "'", true);

ob_start();
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
    </head>
    <body
            style="position: relative;width: 21cm;height: 29.7cm;margin: 0 auto;color: #001028;background: #FFFFFF;font-family: Arial;font-size: 12px;">
    <header class="clearfix" style="padding: 10px 0;margin-bottom: 30px;">
        <div style="float: left;width: 60%;line-height: 12pt">
            <h2>ทวีทรัพย์ แมนชั่น</h2>
            <p>46/42 ม.2 ต.วังตะกู อ.เมือง จ.นครปฐม 73000</p>
            <p>Tel : 0869948809</p>
            <p>ผู้เช่า <?php echo $customer['first_name']; ?>  <?php echo $customer['last_name']; ?></p>
            <p>เช่าห้อง <?php echo $customer['room_name']; ?></p>
        </div>
        <div style="float: right;width: 40%;line-height: 12pt">
            <h2 style="text-align: right">ใบเสร็จรับเงิน</h2>
            <p style="text-align: right">ประจำเดือน <?php echo DateMonthThaiByDate($payment['save_date']); ?></p>
        </div>
    </header>
    <table style="width: 100%;border-collapse: collapse;border-spacing: 0;margin-bottom: 20px;line-height: 12pt">
        <thead>
        <tr>
            <th class="service"
                style="text-align: left;padding: 5px 20px;color: #5D6975;border-bottom: 1px solid #C1CED9;white-space: nowrap;font-weight: normal;">
                ลำดับ
            </th>
            <th class="service"
                style="text-align: left;padding: 5px 20px;color: #5D6975;border-bottom: 1px solid #C1CED9;white-space: nowrap;font-weight: normal;">
                รายการ
            </th>

            <th style="text-align: right;padding: 5px 20px;color: #5D6975;border-bottom: 1px solid #C1CED9;white-space: nowrap;font-weight: normal;">
                จำนวน
            </th>
            <th style="text-align: right;padding: 5px 20px;color: #5D6975;border-bottom: 1px solid #C1CED9;white-space: nowrap;font-weight: normal;">
                ราคา
            </th>
            <th style="text-align: right;padding: 5px 20px;color: #5D6975;border-bottom: 1px solid #C1CED9;white-space: nowrap;font-weight: normal;">
                ทั้งหมด
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="qty" style="text-align: center;padding: 20px;font-size: 1.2em;">1</td>
            <td class="service" style="text-align: left;padding: 20px;vertical-align: top;">ค่าห้องพัก</td>

            <td class="qty" style="text-align: right;padding: 20px;font-size: 1.2em;">1</td>
            <td class="unit"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo number_format($payment['room_price'], 2); ?></td>
            <td class="total"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo number_format($payment['room_price'], 2); ?></td>
        </tr>
        <tr>
            <td class="qty" style="text-align: center;padding: 20px;font-size: 1.2em;">2</td>
           <td class="service" style="text-align: left;padding: 20px;vertical-align: top;">ค่าน้ำ (เลขครั้งก่อน <?php echo $payment['water_meter_before'];  ?> เลขครั้งหลัง 
            <?php echo $payment['water_meter'];  ?>)</td>

            <td class="qty"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo($payment['water_meter'] - $payment['water_meter_before']); ?></td>
            <td class="unit"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo $payment['water_miter_unit']; ?></td>
            <td class="total"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo number_format($payment['water_price'], 2); ?></td>
        </tr>
        <tr>
            <td class="qty" style="text-align: center;padding: 20px;font-size: 1.2em;">3</td>
            <td class="service" style="text-align: left;padding: 20px;vertical-align: top;">ค่าไฟ (เลขครั้งก่อน <?php echo $payment['electricity_meter_before'];  ?> เลขครั้งหลัง
            <?php echo $payment['electricity_meter'];  ?>)</td>

            <td class="qty"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo($payment['electricity_meter'] - $payment['electricity_meter_before']); ?></td>
            <td class="unit"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo $payment['electricity_miter_unit']; ?></td>
            <td class="total"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo number_format($payment['electricity_price'], 2); ?></td>
        </tr>
        <tr>
            <td class="qty" style="text-align: center;padding: 20px;font-size: 1.2em;">4</td>
            <td class="service" style="text-align: left;padding: 20px;vertical-align: top;">ค่าปรับ</td>

            <td class="qty"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo($payment['mulct_price'] / RATE_PAYMENT_PRICE); ?></td>
            <td class="unit"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo number_format(RATE_PAYMENT_PRICE, 2); ?></td>
            <td class="total"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo number_format($payment['mulct_price'], 2); ?></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;border-top: 1px solid #5D6975;"> ( <?php echo toThaiBath($payment['total_price']); ?> )</td>
            <td style="text-align: right;padding: 20px;font-size: 1.2em;border-top: 1px solid #5D6975;">รวม</td>
            <td class="grand total"
                style="text-align: right;padding: 20px;font-size: 1.2em;border-top: 1px solid #5D6975;"><?php echo number_format($payment['total_price'], 2); ?>
            </td>
        </tr>
        </tbody>
    </table>
    <br>
    <div id="notices">
        <table width="100%">
                     
          
            <tr>
                <td width="50%" align="center">
                    <div>&nbsp;ลงชื่อ ______________________ ผู้จ่ายเงิน</div>
                </td>
                <td width="50%" align="center">
                    <div>&nbsp;ลงชื่อ ______________________ ผู้รับเงิน</div>
                </td>
            </tr>
            <tr>
                <td width="50%" align="center">
                    <div><?php echo $customer['first_name']; ?>  <?php echo $customer['last_name']; ?></div>
                </td>
                <td width="50%" align="center">
                    <div> จันทนา รองาม</div>
                </td>
            </tr>
        </table>
    </div>
    </body>
    </html>
<?php
$html = ob_get_contents();
ob_end_clean();
$pdf = new mPDF('th', 'A4-L', '0', 'TH SarabunPSK' );
$pdf->autoPageBreak = true;
$pdf->WriteHTML($html, 2);
$pdf->Output();

?>