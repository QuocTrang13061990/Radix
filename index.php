<?php 
session_start();
require_once 'config.php';
// import PHPMailer lib
require_once 'includes/phpmailer/PHPMailer.php';
require_once 'includes/phpmailer/SMTP.php';
require_once 'includes/phpmailer/Exception.php';

require_once 'includes/functions.php';
require_once 'includes/connect.php';
require_once 'includes/database.php';
require_once 'includes/session.php';

$modules = _MODULES_DEFAULT;
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
if(file_exists($path)){
    require_once $path;
}else{
    require_once 'modules/errors/404.php';
}

/* END ROUTER */
?>