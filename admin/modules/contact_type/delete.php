<?php 
if(!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if(!empty($body['id'])){
    $contactTypeId = $body['id'];
    $contactTypeInfor = firstRaw("SELECT * FROM contact_type WHERE id=$contactTypeId"); // Tìm thấy trả về 1, không thì 0
      
    if(!empty($contactTypeInfor)){
       // Kiểm tra trong phòng ban này có contact nào không (Nếu có không xóa và hiển thị thông báo)
       $checkContactRow = getRow("SELECT id FROM contacts WHERE type_id=$contactTypeId");
       if($checkContactRow){
            setFlashData('msg', 'Phòng ban '.$contactTypeInfor['name'].' vẫn còn '.$checkContactRow.' liên hệ, không thể xóa.');
            setFlashData('msg_type', 'danger');
       }else{
            $deleteContactType = deleteData('contact_type', "id=$contactTypeId");
            if($deleteContactType){
                setFlashData('msg', 'Xóa phòng ban '.$contactTypeInfor['name'].' thành công.');
                setFlashData('msg_type', 'success');
            }else{
                setFlashData('msg', 'Không tồn tại phòng ban này trong hệ thống.');
                setFlashData('msg_type', 'danger');
            }
       }
    }else {
        setFlashData('msg', 'Không tồn tại phòng ban này trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
}else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?modules=contact_type'); // Khi chuyển qua đây thì sẽ nhận được flashData đó
