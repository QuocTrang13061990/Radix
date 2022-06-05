<?php 
if(!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if(!empty($body['id'])){
    $blogId = $body['id'];
    $blogInfor = firstRaw("SELECT * FROM blogs WHERE id=$blogId"); // Tìm thấy trả về 1, không thì 0
      
    if(!empty($blogInfor)){
        $deleteBlog = deleteData('blogs', "id=$blogId");
        if($deleteBlog){
            setFlashData('msg', 'Xóa blog thành công.');
            setFlashData('msg_type', 'success');
        }else{
            setFlashData('msg', 'Lỗi hệ thống. Vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    }else {
        setFlashData('msg', 'Không tồn tại dự án này trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
}else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?modules=blogs'); // Khi chuyển qua đây thì sẽ nhận được flashData đó
