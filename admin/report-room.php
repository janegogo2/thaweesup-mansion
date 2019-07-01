<?php
session_start();
require_once '../mpdf/mpdf.php';
require_once '../connection.inc.php';


$sql = "select room.*,room_type_name from room
left join room_type on room_type.room_type_id=room.room_type_id ";
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
    <div style="text-align: center;">รายงานห้องพัก</div>
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
    text-align: center;">ห้องพัก
            </th>
            <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">ประเภทห้องพัก
            </th>
            <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">ราคา
            </th>
            <th style="padding: 7px;
    font-size: 10px;
    width: 10%;
    background-color: #D5D5D5;
    text-align: center;">รายละเอียด
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
    text-align: left;"><?php echo $row['room_name']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: left;"><?php echo $row['room_type_name']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: left;"><?php echo $row['room_price']; ?></td>
                <td style="padding: 7px;
    vertical-align: top;
    font-size: 10px;
    background-color: white;
    text-align: left;"><?php echo $row['room_detail']; ?></td>
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