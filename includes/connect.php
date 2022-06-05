<?php 
if(!defined('_INCODE')) die('access delined......');

// (Nếu không đưa vào try, catch thì nếu không tìm thấy _DB (chẳng hạn) thì việc hiển thị lỗi sẽ khó nhìn)
try{
    // Khi sử dụng PDO thì trên webserver của mình cần bật extension PDO: 
    // Kiểm tra extension đã được bật chưa bằng việc kiểm tra: Nếu tồn tại class PDO thì coi như PDO đã bật lên
    if(class_exists('PDO')){
        $dsn = DRIVER.':dbname='._DB.';host='._HOST;
        $options = [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', // set utf8
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Đẩy lỗi vào ngoại lệ khi truy vấn 
        ];
        // Tạo đối tượng
        $conn = new PDO($dsn, _USER, _PASS, $options);
    }

}catch(Exception $exception){
    // file connect.php này đã được import bên index.php rồi, muốn kết nối đến file database.php thì tính từ index.php
    // index.php và thư mục modules là cùng cấp
    require_once 'modules/errors/database.php';
    die(); // kết nối thất bại thì die luôn
}


?>