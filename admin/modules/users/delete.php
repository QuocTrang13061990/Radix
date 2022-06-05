<?php
if(!defined('_INCODE')) die('access delined......');
/* file này dùng để xóa người dùng */
$body = getBody();
if(!empty($body['id'])){
    $userId = $body['id'];
    $userinfo = firstRaw("SELECT id, fullname FROM users WHERE id=$userId"); // Tìm thấy trả về 1, không thì 0
    if(!empty($userinfo)){
        //1. Xóa bên login_token trước
        $deleteLoginToken = deleteData('login_token', "user_id=$userId");
        if($deleteLoginToken){
            // 2. Xóa bên users
            $deleteUser = deleteData('users', "id=$userId");
            if($deleteUser){
                setFlashData('msg', 'Xóa người dùng '.$userinfo['fullname'].' thành công!');
                setFlashData('msg_type', 'success');
            }else {
                setFlashData('msg', 'Lỗi hệ thống. Không thể xóa người dùng lúc này. Vui lòng thử lại sau');
                setFlashData('msg_type', 'danger');
            }
        }else {
            setFlashData('msg', 'Lỗi hệ thống. Vui lòng thử lại sau.');
            setFlashData('msg_type', 'danger');
        }
    }else {
        setFlashData('msg', 'Không tồn tại người dùng này trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
}else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?modules=users'); 