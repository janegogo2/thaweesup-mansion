<?php
require_once("PHPMailer/PHPMailerAutoload.php");

class Notification
{
    private $utils;
    private $key_id;
    private $conn;

    public function __construct(MySQLDBConn $conn, $utils, $key_id)
    {
        $this->utils = $utils;
        $this->conn = $conn;
        $this->key_id = $key_id;
    }

    public function process()
    {
   /*     foreach ($this->prepare() as $model) {
            $this->send($model);
        }*/
    }

    private function send(array $model)
    {
        try {
            $mail = new PHPMailer;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->isSMTP();
            $mail->CharSet = "utf-8";
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->SMTPKeepAlive = true;
            $mail->Port = 587;
            $mail->Username = MAIL_SERVER_USERNAME;
            $mail->Password = MAIL_SERVER_PASSWORD;
            $mail->setFrom(MAIL_FORM, MAIL_FORM_NAME);
            $mail->Subject = $model['subject'];
            foreach ($model['to'] as $email) {
                $mail->addAddress($email['email'], $email['name']);
            }
            $mail->msgHTML($model['body']);
            if ($mail->send()) {
                $mail->clearAddresses();
                $mail->clearAttachments();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function prepare()
    {
        $model = array();
        switch ($this->utils) {
            case NotifyUtils::$SUBMIT_UNIT_BY_MONTH:
                $model[] = $this->submit_unit_by_month();
                break;
            case NotifyUtils::$APPROVE_PAYMENT:
                $model[] = $this->approve_payment();
                break;
            case NotifyUtils::$APPROVE_CHECK_OUT:
                $model[] = $this->check_out();
                break;
        }
        return $model;
    }

    private function submit_unit_by_month()
    {
        $payment = $this->conn->select("payment_room", array("payment_no" => $this->key_id), true);
        $customer = $this->conn->queryRaw("select customer.* from customer
left join contract_room on contract_room.customer_id=customer.customer_id where contract_no='" . $payment['contract_no'] . "'", true);
        $model = array();
        $model['to'] = $this->get_list_email_payment_customer();
        $model['subject'] = "แจ้งชำระเงิน";
        $body = $this->load_template_email(ROOTPATH . PATH_MAIL_TEMPLATE . "submit_unit_by_month.html");
        $body = str_replace("[DATE_DESCRIPTION]", DateMonthThaiByDate($payment['save_date']), $body);
        $body = str_replace("[CUSTOMER_NAME]", $customer['first_name'] . ' ' . $customer['last_name'], $body);
        $body = str_replace("[PHONE]", $customer['phone'], $body);
        $body = str_replace("[EMAIL]", $customer['email'], $body);
        $body = str_replace("[ADDRESS]", $customer['address'], $body);
        $body = str_replace("[ROOM_PRICE]", number_format($payment['room_price'], 2), $body);
        $body = str_replace("[WATER_PRICE]", number_format(WATER_UNIT_PRICE, 2), $body);
        $body = str_replace("[WATER_TOTAL_UNIT]", $payment['water_meter'] - $payment['water_meter_before'], $body);
        $body = str_replace("[WATER_TOTAL_PRICE]", number_format($payment['water_price'], 2), $body);
        $body = str_replace("[ELECTRICITY_PRICE]", WATER_UNIT_PRICE, $body);
        $body = str_replace("[ELECTRICITY_TOTAL_UNIT]", $payment['electricity_meter'] - $payment['electricity_meter_before'], $body);
        $body = str_replace("[ELECTRICITY_TOTAL_PRICE]", number_format($payment['electricity_price'], 2), $body);
        $body = str_replace("[TOTAL_PRICE]", number_format($payment['electricity_price'] + $payment['water_price'] + $payment['room_price'], 2), $body);
        $body = str_replace("[THAI_BATH_DESCRIPTION]", toThaiBath($payment['electricity_price'] + $payment['water_price'] + $payment['room_price']), $body);
        $model['body'] = $body;
        return $model;
    }

    private function check_out()
    {
        $contract = $this->conn->select("contract_room", array("contract_no" => $this->key_id), true);
        $payment = $this->conn->queryRaw("select * from payment_room where contract_no = '" . $this->key_id . "' order by payment_no desc", true);
        $customer = $this->conn->queryRaw("select customer.* from customer
left join contract_room on contract_room.customer_id=customer.customer_id where contract_no='" . $payment['contract_no'] . "'", true);
        $model = array();
        $model['to'] = $this->get_list_email_contract_customer();
        $model['subject'] = "ใบเสร็จรับเงิน";
        $body = $this->load_template_email(ROOTPATH . PATH_MAIL_TEMPLATE . "check_out.html");
        $body = str_replace("[DATE_DESCRIPTION]", DateMonthThaiByDate($payment['save_date']), $body);
        $body = str_replace("[CUSTOMER_NAME]", $customer['first_name'] . ' ' . $customer['last_name'], $body);
        $body = str_replace("[PHONE]", $customer['phone'], $body);
        $body = str_replace("[EMAIL]", $customer['email'], $body);
        $body = str_replace("[ADDRESS]", $customer['address'], $body);
        $body = str_replace("[DEPOSIT_PRICE]", number_format($contract['deposit_price'], 2), $body);
        $body = str_replace("[INSURANCE_PRICE]", number_format($contract['insurance_price'], 2), $body);
        $body = str_replace("[WATER_PRICE]", number_format(WATER_UNIT_PRICE, 2), $body);
        $body = str_replace("[WATER_TOTAL_UNIT]", $payment['water_meter'] - $payment['water_meter_before'], $body);
        $body = str_replace("[WATER_TOTAL_PRICE]", number_format($payment['water_price'], 2), $body);
        $body = str_replace("[ELECTRICITY_PRICE]", WATER_UNIT_PRICE, $body);
        $body = str_replace("[ELECTRICITY_TOTAL_UNIT]", $payment['electricity_meter'] - $payment['electricity_meter_before'], $body);
        $body = str_replace("[ELECTRICITY_TOTAL_PRICE]", number_format($payment['electricity_price'], 2), $body);
        $body = str_replace("[MULCT_PRICE]", RATE_PAYMENT_PRICE, $body);
        $body = str_replace("[MULCT_TOTAL_UNIT]", $payment['mulct_price'] / RATE_PAYMENT_PRICE, $body);
        $body = str_replace("[MULCT_TOTAL_PRICE]", number_format($payment['mulct_price'], 2), $body);
        $body = str_replace("[TOTAL_PRICE]", number_format($payment['total_price'], 2), $body);
        $body = str_replace("[THAI_BATH_DESCRIPTION]", toThaiBath($payment['total_price']), $body);
        $model['body'] = $body;
        return $model;
    }


    private function approve_payment()
    {
        $payment = $this->conn->select("payment_room", array("payment_no" => $this->key_id), true);
        $customer = $this->conn->queryRaw("select customer.* from customer
left join contract_room on contract_room.customer_id=customer.customer_id where contract_no='" . $payment['contract_no'] . "'", true);
        $model = array();
        $model['to'] = $this->get_list_email_payment_customer();
        $model['subject'] = "ใบเสร็จรับเงิน";
        $body = $this->load_template_email(ROOTPATH . PATH_MAIL_TEMPLATE . "approve_payment.html");
        $body = str_replace("[DATE_DESCRIPTION]", DateMonthThaiByDate($payment['save_date']), $body);
        $body = str_replace("[CUSTOMER_NAME]", $customer['first_name'] . ' ' . $customer['last_name'], $body);
        $body = str_replace("[PHONE]", $customer['phone'], $body);
        $body = str_replace("[EMAIL]", $customer['email'], $body);
        $body = str_replace("[ADDRESS]", $customer['address'], $body);
        $body = str_replace("[ROOM_PRICE]", number_format($payment['room_price'], 2), $body);
        $body = str_replace("[WATER_PRICE]", number_format(WATER_UNIT_PRICE, 2), $body);
        $body = str_replace("[WATER_TOTAL_UNIT]", $payment['water_meter'] - $payment['water_meter_before'], $body);
        $body = str_replace("[WATER_TOTAL_PRICE]", number_format($payment['water_price'], 2), $body);
        $body = str_replace("[ELECTRICITY_PRICE]", WATER_UNIT_PRICE, $body);
        $body = str_replace("[ELECTRICITY_TOTAL_UNIT]", $payment['electricity_meter'] - $payment['electricity_meter_before'], $body);
        $body = str_replace("[ELECTRICITY_TOTAL_PRICE]", number_format($payment['electricity_price'], 2), $body);
        $body = str_replace("[MULCT_PRICE]", RATE_PAYMENT_PRICE, $body);
        $body = str_replace("[MULCT_TOTAL_UNIT]", $payment['mulct_price'] / RATE_PAYMENT_PRICE, $body);
        $body = str_replace("[MULCT_TOTAL_PRICE]", number_format($payment['mulct_price'], 2), $body);
        $body = str_replace("[TOTAL_PRICE]", number_format($payment['total_price'], 2), $body);
        $body = str_replace("[THAI_BATH_DESCRIPTION]", toThaiBath($payment['total_price']), $body);
        $model['body'] = $body;
        return $model;
    }

    private function get_list_email_contract_customer()
    {
        return $this->conn->queryRaw("select customer.email as email,concat(customer.first_name,' ',customer.last_name) as name from customer
left join contract_room on contract_room.customer_id=customer.customer_id where contract_no='" . $this->key_id . "'");
    }

    private function get_list_email_payment_customer()
    {
        return $this->conn->queryRaw("select distinct customer.email as email,concat(customer.first_name,' ',customer.last_name) as name from customer
left join contract_room on contract_room.customer_id=customer.customer_id
left join payment_room on payment_room.contract_no=contract_room.contract_no
where payment_no='" . $this->key_id . "'");
    }

    private function load_template_email($path)
    {
        try {
            return file_get_contents($path);
        } catch (Exception $e) {
            return "";
        }
    }
}

class NotifyUtils
{
    public static $SUBMIT_UNIT_BY_MONTH = 1;

    public static $APPROVE_PAYMENT = 2;
    public static $APPROVE_CHECK_OUT = 3;
}