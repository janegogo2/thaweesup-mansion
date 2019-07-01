<?php
session_start();
require_once '../mpdf/mpdf.php';
require_once '../connection.inc.php';
$contract_no = getIsset("__contract_no");
$contract = $conn->select("contract_room", array("contract_no" => $contract_no), true);

$payment = $conn->queryRaw("select payment_room.*,MONTH(save_date) as month,YEAR(save_date) as year from payment_room where contract_no = '$contract_no' order by payment_no desc", true);
$customer = $conn->queryRaw("select customer.*,room_name from customer
left join contract_room on contract_room.customer_id=customer.customer_id
left join room on room.room_id=contract_room.room_id
 where contract_no='" . $payment['contract_no'] . "'", true);

//$electric_bill = $conn->queryRaw("select * from (
//select service.*,YEAR(service_log.datemonth) as bill_year,MONTH(service_log.datemonth) as bill_month from service 
//LEFT JOIN service_log on service_log.service_id = service.service_id
//) as ex
// where bill_year = '".$payment['year']."'  and  bill_month = '".$payment['month']."'", true);
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
            <p style="text-align: right">ประจำวันที่ <?php echo DateDayMonthThaiByDate($payment['save_date']); ?></p>
        </div>
    </header>
    <table style="width: 100%;border-collapse: collapse;border-spacing: 0;margin-bottom: 20px;">
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
                ราคา
            </th>
            <th style="text-align: right;padding: 5px 20px;color: #5D6975;border-bottom: 1px solid #C1CED9;white-space: nowrap;font-weight: normal;">
                จำนวน
            </th>
            <th style="text-align: right;padding: 5px 20px;color: #5D6975;border-bottom: 1px solid #C1CED9;white-space: nowrap;font-weight: normal;">
                ทั้งหมด
            </th>
        </tr>
        </thead>
        <tbody>

        <tr>
            <td class="qty" style="text-align: left;padding: 20px;font-size: 1.2em;">1</td>
            <td class="service" style="text-align: left;padding: 20px;vertical-align: top;">คืนค่าประกัน <br>(<?php echo toDDMMYYYY($contract['start_date']); ?> ถึง <?php echo toDDMMYYYY($contract['end_date']); ?>)</td>
            <td class="unit"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo number_format($contract['insurance_price'], 2); ?></td>
            <td class="qty" style="text-align: right;padding: 20px;font-size: 1.2em;">1</td>
            <td class="total"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php  
                                                       if(strtotime($contract['end_date']) > strtotime($contract['check_out_date'])){
                                                           echo number_format(0,2) ;
                                                       } else {
                                                           echo number_format($contract['insurance_price'], 2);
                                                       } ?>
            </td>
        </tr>
        <tr>
            <td colspan="5"><hr></td>
        </tr>
            <tr>
            <td class="qty" style="text-align: left;padding: 20px;font-size: 1.2em;">1</td>
            <td class="service" style="text-align: left;padding: 20px;vertical-align: top;">ค่าห้องพัก</td>
<td class="unit"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo number_format($payment['room_price_last'], 2); ?></td>
            <td class="qty" style="text-align: right;padding: 20px;font-size: 1.2em;">1</td>
            
            <td class="total"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo number_format($payment['room_price_last'], 2); ?></td>
        </tr>
        <tr>
            <td class="qty" style="text-align: left;padding: 20px;font-size: 1.2em;">2</td>
             <td class="service" style="text-align: left;padding: 20px;vertical-align: top;">ค่าน้ำ (เลขครั้งก่อน <?php echo $payment['water_meter_before'];  ?>  เลขครั้งหลัง 
            <?php echo $payment['water_meter'];  ?>)</td>
            <td class="unit"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo $payment['water_miter_unit']; ?></td>
            <td class="qty"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo($payment['water_meter'] - $payment['water_meter_before']); ?></td>
            <td class="total"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo number_format($payment['water_price'], 2); ?></td>
        </tr>
        <tr>
            <td class="qty" style="text-align: left;padding: 20px;font-size: 1.2em;">3</td>
          <td class="service" style="text-align: left;padding: 20px;vertical-align: top;">ค่าไฟ (เลขครั้งก่อน <?php echo $payment['electricity_meter_before'];  ?> เลขครั้งหลัง
            <?php echo $payment['electricity_meter'];  ?>)</td>
            <td class="unit"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo $payment['electricity_miter_unit']; ?></td>
            <td class="qty"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo($payment['electricity_meter'] - $payment['electricity_meter_before']); ?></td>
            <td class="total"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo number_format($payment['electricity_price'], 2); ?></td>
        </tr>
             <tr>
            <td class="qty" style="text-align: left;padding: 20px;font-size: 1.2em;">4</td>
            <td class="service" style="text-align: left;padding: 20px;vertical-align: top;">ค่าปรับ</td>

            <td class="qty"
                style="text-align: right;padding: 20px;font-size: 1.2em;"></td>
            <td class="unit"
                style="text-align: right;padding: 20px;font-size: 1.2em;"></td>
            <td class="total"
                style="text-align: right;padding: 20px;font-size: 1.2em;"><?php echo number_format($payment['mulct_price'], 2); ?></td>
        </tr>
      
        <tr>
            <td class="grand total" colspan="5"
                style="text-align: right;padding: 20px;font-size: 1.2em;border-top: 1px solid #5D6975;">
                <?php  
                                                       if(strtotime($contract['end_date']) > strtotime($contract['check_out_date'])){
                                                           ?> <p style="text-align:left;">ยอดเงินที่ต้องจ่าย</p><span style="float:right;"><br><?php echo number_format($payment['total_price'], 2);?><br><br> ( <?php echo toThaiBath($payment['total_price']); ?> )</span>
               
                <?php
                                                       } else {
                                                           
                    
                                                           if($payment['total_price']>$contract['insurance_price'])
                                                           {
                                                               $total_pricemoney = $payment['total_price']-$contract['insurance_price'];
                                                               ?> <p style="text-align:left;">ยอดเงินที่ต้องจ่าย</p><br><?php
                                                           }
                                                           else if($payment['total_price']<$contract['insurance_price'])
                                                           {
                                                               $total_pricemoney = $contract['insurance_price']-$payment['total_price'];
                                                               ?> <p style="text-align:left;">ยอดเงินที่ต้องคืน</p><br><?php
                                                           }
                                                           echo number_format($total_pricemoney, 2);
                                                           ?><br><br> ( <?php echo toThaiBath($total_pricemoney); ?> ) <?php
                                                       } ?>
                
                
              
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
                    <!--div><?php echo $customer['first_name']; ?>  <?php echo $customer['last_name']; ?></div!-->
                    <div>(&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;)</div>
                </td>
                <td width="50%" align="center">
                    <!--div> จันทนา รองาม</div!-->
                    <div>(&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;)</div>
                </td>
            </tr>
        </table>
    </div>
    </body>
    </html>
<?php
$html = ob_get_contents();
ob_end_clean();
$pdf = new mPDF('th', 'A4', '0', 'TH SarabunPSK');
$pdf->autoPageBreak = true;
$pdf->WriteHTML($html, 2);
$pdf->Output();
?>