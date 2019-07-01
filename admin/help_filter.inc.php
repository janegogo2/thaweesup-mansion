<?php
$cmd = getIsset('__filter');
$freturn = getIsset('__freturn');
$filter = array();
$filter['title'] = 'DEFAULT';
$filter['order_by'] = "";

if ($cmd == 'customer') {
    $filter['column'] = array('customer_id' => 'รหัส', 'first_name' => 'ชื่อ', 'last_name' => 'นามสกุล', 'email' => 'อีเมล์', 'phone' => 'เบอร์โทรศัพท์');
    $filter['sql'] = "select * from customer where 1=1 ";
    $filter['key_id'] = 'customer_id';
    $filter['title'] = 'ข้อมูลลุกค้า';
    $filter['options'] = array('customer_id' => 'รหัส', 'first_name' => 'ชื่อ', 'last_name' => 'นามสกุล', 'email' => 'อีเมล์', 'phone' => 'เบอร์โทรศัพท์');
}

if ($cmd == 'employee') {
    $filter['column'] = array('employee_id' => 'รหัส', 'first_name' => 'ชื่อ', 'last_name' => 'นามสกุล', 'email' => 'อีเมล์', 'phone' => 'เบอร์โทรศัพท์');
    $filter['sql'] = "select * from employee where 1=1 ";
    $filter['key_id'] = 'employee_id';
    $filter['title'] = 'ข้อมูลพนักงาน';
    $filter['options'] = array('employee_id' => 'รหัส', 'first_name' => 'ชื่อ', 'last_name' => 'นามสกุล', 'email' => 'อีเมล์', 'phone' => 'เบอร์โทรศัพท์');
}
if ($cmd == 'room_type') {
    $filter['column'] = array('room_type_id' => 'รหัส', 'room_type_name' => 'ชื่อ');
    $filter['sql'] = "select * from room_type where 1=1 ";
    $filter['key_id'] = 'room_type_id';
    $filter['title'] = 'ข้อมูลประเภทห้องพัก';
    $filter['options'] = array('room_type_id' => 'รหัส', 'room_type_name' => 'ชื่อ');
}
if ($cmd == 'room') {
    $filter['column'] = array('room_id' => 'รหัส', 'room_name' => 'ห้องพัก', 'room_type_name' => 'ประเภท');
    $filter['sql'] = "select room.*,room_type_name from room
left join room_type on room_type.room_type_id=room.room_type_id where 1=1 ";
    $filter['key_id'] = 'room_id';
    $filter['title'] = 'ข้อมูลห้องพัก';
    $filter['options'] = array('room_id' => 'รหัส', 'room_name' => 'ห้องพัก', 'room_type_name' => 'ประเภท');
}

if ($cmd == 'room_reserve') {
    $filter['column'] = array('room_id' => 'รหัส', 'room_name' => 'ห้องพัก', 'room_type_name' => 'ประเภท');
    $filter['sql'] = "select room.*,room_type_name from room
left join room_type on room_type.room_type_id=room.room_type_id where room_id not in (
select room_id from contract_room where status_id='".ACTIVE_CONTRACT."'
) ";
    $filter['key_id'] = 'room_id';
    $filter['title'] = 'ข้อมูลห้องพัก';
    $filter['options'] = array('room_id' => 'รหัส', 'room_name' => 'ห้องพัก', 'room_type_name' => 'ประเภท');
}

if ($cmd == 'service') {
    $filter['column'] = array('service_id' => 'รหัส', 'service_name' => 'บริการเสริม');
    $filter['sql'] = "select * from service where 1=1 ";
    $filter['key_id'] = 'service_id';
    $filter['title'] = 'ข้อมูลบริการเสริม';
    $filter['options'] = array('service_id' => 'รหัส', 'service_name' => 'บริการเสริม');
}

if (getIsset('option') != "" && getIsset('keyword') != "") {
    $filter['sql'] = get_filter(getIsset('option'), getIsset('keyword'), $filter['sql']);
}

function get_filter($col, $value, $sql)
{
    return $sql . ' AND ' . $col . ' LIKE \'%' . $value . '%\' ';
}


?>