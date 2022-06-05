<?php 
if(!defined('_INCODE')) die('access delined......');

// 0. Truy vấn dl (Dùng chung cho thêm, sửa, xóa, lấy dl)
function queryData($sql, $data=[], $statementStatus=false){
    global $conn;
    $queryStatus = false;
    try{
        $statement = $conn->prepare($sql);
        if(empty($data)){
            $queryStatus = $statement->execute();
        }else {
            $queryStatus = $statement->execute($data);
        }    

    }
    catch(Exception $exception){
        require_once 'modules/errors/database.php';
        die(); // kết nối thất bại thì die luôn
    }
    if($statementStatus && $queryStatus){
        return $statement;
    }
    return $queryStatus;
}
// 1. Thêm dl vào talbe
function addData($table, $dataAdd){
    $dataKey = array_keys($dataAdd);
    $fieldStr = implode(', ', $dataKey);   
    $valueStr = ':'.implode(', :', $dataKey);   
    $sql = 'INSERT INTO '.$table.'('.$fieldStr.') VALUES('.$valueStr.')';
  
    return queryData($sql, $dataAdd);
}
// 2. Sửa dl trong table
function editData($table, $dataEdit, $id=''){
    $editStr = '';
    foreach($dataEdit as $key=>$value){
        $editStr.=$key.'=:'.$key.', ';
    }
    $editStr = rtrim($editStr, ', ');
    // Sửa 1 row
    if(!empty($id)){
        $sql = 'UPDATE '.$table.' SET '.$editStr.' WHERE '.$id;
    }else {
        $sql = 'UPDATE '.$table.' SET '.$editStr;
    }
    // Sửa tất cả row
    return queryData($sql, $dataEdit);
}
// 3. Xóa dl trong table
function deleteData($table, $id=''){
    if(!empty($id)){
        $sql = "DELETE FROM $table WHERE $id";
    }else {
        $sql = "DELETE FROM $table";
    }
    return queryData($sql);
}
// 4. Lấy dữ liệu trong table
// 4.1. Lấy tất cả row / lấy 1 row bất kì (tất cả thì không truyền $condition)
function fetchData($table, $field='*', $condition=''){
    $sql = "SELECT $field FROM $table";
    
    if(!empty($condition)){
        $sql.=' WHERE '.$condition;
    }
    $statement = queryData($sql, [], true); // trả về object 
    var_dump($statement);
    if(is_object($statement)){
        $fetchAllDataStatus = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $fetchAllDataStatus;
    }
    return false;
}
// 4.2. Lấy row đầu tiên: Tìm $field trong $table (chỉ hiển thị $field đầu tiên trong $table)
function fetchFirstData($table, $field='*'){
    $sql = "SELECT $field FROM $table";
    $statement = queryData($sql, [], true);
    if(is_object($statement)){
        $fetchAllDataStatus = $statement->fetch(PDO::FETCH_ASSOC);
        return $fetchAllDataStatus;
    }
    return false;
}
// Lấy số dòng câu truy vấn (xem row nay co trong db chua -> getRow() > 0)
function getRow($sql){
    $statement = queryData($sql, [], true);
    if(!empty($statement)){
        return $statement->rowCount();
    }
}
// Thay thế cho hàm fetchData (ở đây viết hoàn chỉnh $sql luôn) => Nên dùng thay cho fetchData
function getRaw($sql){
    $statement = queryData($sql, [], true);
    if(is_object($statement)){
        $dataFetch = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $dataFetch;
    }
}
// Thay thế cho hàm fetchFirstData
function firstRaw($sql){
    $statement = queryData($sql, [], true);
    if(is_object($statement)){
        $dataFetch = $statement->fetch(PDO::FETCH_ASSOC);
        return $dataFetch;
    }
}
// Ví dụ: getRaw("SELECT password FROM users WHERE email='$email'");

// Hàm lấy id vừa add vào db (Bài 173)
function insertId(){
    global $conn;
    return $conn->lastInsertId();
}