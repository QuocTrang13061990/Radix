<?php 
if(!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if(!empty($body['id'])){
    $serviceId = $body['id'];
    $serviceInfor = firstRaw("SELECT * FROM services WHERE id=$serviceId"); // Tìm thấy trả về 1, không thì 0
      
    if(!empty($serviceInfor)){
        $deleteService = deleteData('services', "id=$serviceId");
        if($deleteService){
            setFlashData('msg', 'Xóa '.$serviceInfor['name'].' thành công.');
            setFlashData('msg_type', 'success');
        }else{
            setFlashData('msg', 'Lỗi hệ thống. Vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    }else {
        setFlashData('msg', 'Không tồn tại dịch vụ này trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
}else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?modules=services'); // Khi chuyển qua đây thì sẽ nhận được flashData đó
