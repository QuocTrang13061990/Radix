<?php
if(!defined('_INCODE')) die('access delined......');
// var_dump($_SESSION['tokenLogin']);exit;
saveActivity(); // lưu lại thời gian hoạt động cuối cùng của userId
autoRemoveTokenLogin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo !empty($data['titlePage']) ? $data['titlePage'] : 'unicode' ;?></title>
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE_ADMIN ;?>/assets/css/adminlte.min.css">
    <link rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE_ADMIN ;?>/assets/plugins/fontawesome-free/css/all.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATE_ADMIN ;?>/assets/css/auth.css?ver=<?php echo rand(); ?>">
</head>
<body>
    
