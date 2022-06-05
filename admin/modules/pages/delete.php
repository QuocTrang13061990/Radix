<?php 
if(!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if(!empty($body['id'])){
    $pageId = $body['id'];
    $pageInfor = firstRaw("SELECT * FROM pages WHERE id=$pageId"); // Tìm thấy trả về 1, không thì 0
      
    if(!empty($pageInfor)){
        $deletePage = deleteData('pages', "id=$pageId");
        if($deletePage){
            setFlashData('msg', 'Xóa '.$pageInfor['title'].' thành công.');
            setFlashData('msg_type', 'success');
        }else{
            setFlashData('msg', 'Lỗi hệ thống. Vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    }else {
        setFlashData('msg', 'Không tồn tại trang này trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
}else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?modules=pages'); // Khi chuyển qua đây thì sẽ nhận được flashData đó
