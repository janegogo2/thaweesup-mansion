<?php
session_start();
require_once('../connection.inc.php');
require_once('../common.inc.php');
$action = getIsset('action');
$result = array();

if ($action == 'getEmployeeById') {
    $id = getIsset('id');
    $result = $conn->queryRaw("select employee.*,employee_type_name from employee
left join employee_type on employee_type.employee_type_id=employee.employee_type_id  where employee_id='$id'", true);
}

if ($action == 'getRoomById') {
    $id = getIsset('id');
    $result = $conn->queryRaw("select room.*,room_type_name from room
left join room_type on room_type.room_type_id=room.room_type_id where room_id='$id'", true);
}
if ($action == 'getServiceById') {
    $id = getIsset('id');
    $result = $conn->queryRaw("select service.*,datemonth from service
 left join service_log on service_log.service_id = service.service_id
 where service.service_id='$id'", true);
}

if ($action == 'getContractRoomById') {
    $id = getIsset('id');
    $result = $conn->queryRaw("select contract_room.*,status_name,room_name
,concat(employee.first_name,' ',employee.last_name) as employee_name
,customer.id_card as id_card,customer.email as email,room_price,customer.first_name as first_name,customer.last_name as last_name
from contract_room
left join customer on customer.customer_id=contract_room.customer_id

left join employee on employee.employee_id=contract_room.employee_id
left join status on status.status_id=contract_room.status_id
left join room on room.room_id=contract_room.room_id where contract_no='$id'", true);
}

if ($action == 'getCustomerById') {
    $id = getIsset('id');
    $result = $conn->select('customer', array('customer_id' => $id), true);
}

if ($action == 'getRoomTypeById') {
    $id = getIsset('id');
    $result = $conn->select('room_type', array('room_type_id' => $id), true);
}

echo json_encode($result);