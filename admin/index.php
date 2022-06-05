<?php 
session_start();
ob_start();
require_once '../config.php';
// import PHPMailer lib
require_once '../includes/phpmailer/PHPMailer.php';
require_once '../includes/phpmailer/SMTP.php';
require_once '../includes/phpmailer/Exception.php';

require_once '../includes/functions.php';
require_once '../includes/permalink.php';
require_once '../includes/connect.php';
require_once '../includes/database.php';
require_once '../includes/session.php';
/* Begin: Xử lý hiển thị thông báo lỗi */
// if(_DEBUG){
//     ini_set('display_errors', 1);
//     error_reporting(E_ALL);
// }else{
//     ini_set('display_errors', 0);
//     error_reporting(0);
// }
// set_exception_handler("showExceptionError"); // hiển thị lỗi lớn như parse, FATAL
// set_error_handler("showErrorHandler"); // Hiển thị lỗi nhỏ như warning, notice
/* End: Xử lý hiển thị thông báo lỗi */
$modules = _MODULES_DEFAULT_ADMIN;
$action = _ACTION_DEFAULT;
/* BEGIN ROUTER */
// lấy modules và action từ GET
if(!empty($_GET['modules'])){
    if(is_string($_GET['modules'])){
        $modules = trim($_GET['modules']);
    }
}
if(!empty($_GET['action'])){
    if(is_string($_GET['action'])){
        $action = trim($_GET['action']);
    }
}
$path = 'modules/'.$modules.'/'.$action.'.php';
// var_dump(isLogin());
// echo 'path la: '.$path;exit;
if(file_exists($path)){
    require_once $path;
}else{
    require_once 'modules/errors/404.php';
}

/* END ROUTER */
?>