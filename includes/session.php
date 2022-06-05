<?php
if(!defined('_INCODE')) die('access delined......');
/* file này viết các hàm liên quan đến thao tác session */

// Thiết lập (gán, lưu) Session
function setSession($key, $value){
    if(!empty(session_id())){
        $_SESSION[$key] = $value;
        // var_dump($_SESSION[$key]);
        // exit;
        return true;
    }
    return false;
}
// Lấy Session (Lấy hết / lấy 1 session)
function getSession($key=''){
    if(empty($key)){
        return $_SESSION;
    }else {
        if (isset($_SESSION[$key])){
            return $_SESSION[$key];
        }
    }
    return  false;
}
// Xóa Session (Xóa hết / xóa 1 session)
function removeSession($key=''){
    if(empty($key)){
        session_destroy();
        return true;
    }else {
        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
            return true;
        }
    }
    return false;
}

// Hàm thiết lập (gán) flash data: 1 session đặc biệt
function setFlashData($key, $value){
    $key = 'flash-'.$key;
    return setSession($key, $value);
}
function getFlashData($key){
    $key = 'flash-'.$key;
    $data = getSession($key);
    removeSession($key);
    return $data;
}
// Hàm tạo thông báo
function getMsg($msg, $type = 'success'){
    if(!empty($msg)){
        echo '<div class = "alert alert-'.$type.'">';
        echo $msg;
        echo '</div>';
    }
}