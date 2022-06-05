<?php
if(!defined('_INCODE')) die('access delined......');
/* file này chứa chức năng đăng xuất */

if(isLogin()){
    $token = getSession('tokenLogin');
    deleteData('login_token', "token='$token'");
    removeSession('tokenLogin');
    redirect('admin/?modules=auth&action=login');
}

