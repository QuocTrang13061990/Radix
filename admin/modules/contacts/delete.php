<?php 
if(!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if(!empty($body['id'])){
    $contactId = $body['id'];
    $contactInfor = firstRaw("SELECT * FROM contacts WHERE id=$contactId"); // Tìm thấy trả về 1, không thì 0
      
    if(!empty($contactInfor)){
        $deleteContact = deleteData('contacts', "id=$contactId");
        if($deleteContact){
            setFlashData('msg', 'Xóa liên hệ thành công.');
            setFlashData('msg_type', 'success');
        }else{
            setFlashData('msg', 'Lỗi hệ thống. Vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    }else {
        setFlashData('msg', 'Không tồn tại liên hệ này trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
}else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?modules=contacts'); // Khi chuyển qua đây thì sẽ nhận được flashData đó
