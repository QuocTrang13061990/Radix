<?php
if (!defined('_INCODE')) die('access delined......');
if(!isLogin()){
    redirect('?modules=auth&action=login');
}else {
    $userId = isLogin()['user_id'];
    $userInfo = firstRaw("SELECT * FROM users WHERE id=$userId");
    // echo '<pre>';
    // print_r($userInfo);
    // echo '</pre>';
}
saveActivity(); // lưu lại thời gian hoạt động cuối cùng của userId
autoRemoveTokenLogin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng</title>
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/bootstrap.min.css">
    <!-- <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/fontawesome.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link type="text/css" rel="stylesheet" href="<?php echo _WEB_HOST_TEMPLATES; ?>/css/style.css?ver=<?php rand(); ?>">
</head>

<body>
    <header>
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <a class="navbar-brand" href="<?php echo _WEB_HOST_ROOT.'?modules=users'; ?>">Unicode Academy</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="nav-item active">
                            <a class="nav-link" href="<?php echo _WEB_HOST_ROOT.'?modules=users'; ?>">Tổng quan<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item dropdown profile">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                                Hi, <?php echo $userInfo['fullname']; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-profile" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#">Thông tin cá nhân</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo _WEB_HOST_ROOT.'?modules=auth&action=logout'; ?>">Đăng xuất</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>