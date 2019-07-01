<?php
session_start();
require_once '../mpdf/mpdf.php';
require_once '../connection.inc.php';


$sql = "select * from customer ";
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
    <div style="text-align: center;">รายงานสมาชิก</div>
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
    text-align: center;">ชื่อ
            </th>
            <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">นามสกุล
            </th>
            <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">เลขบัตรประชาชน
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
    text-align: left;"><?php echo $row['first_name']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: left;"><?php echo $row['last_name']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: left;"><?php echo $row['id_card']; ?></td>
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
$pdf = new mPDF('th', 'A4', '0', 'TH SarabunPSK');
$pdf->autoPageBreak = true;
$pdf->WriteHTML($html, 2);
$pdf->Output();
?>