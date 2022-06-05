<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if (!empty($body['id'])) {
    $serviceId = $body['id'];
    $serviceInfor = firstRaw("SELECT * FROM services WHERE id=$serviceId"); // Tìm thấy trả về 1, không thì 0
    if (!empty($serviceInfor)) {
        unset($serviceInfor['id']);
        unset($serviceInfor['update_at']);
        $serviceInfor['create_at'] = date('Y-m-d H:i:s');
        $duplicate = $serviceInfor['duplicate'];
        $duplicate++;
        $serviceInfor['name'] = $serviceInfor['name'] . ' ('.$duplicate.')';
        $addDataStatus = addData('services', $serviceInfor);
        if ($addDataStatus) {
            setFlashData('msg', 'Nhân bản dịch vụ thành công.');
            setFlashData('msg_type', 'success');
            // Khi nhân bản xong thì phải cập nhật lại duplicate của service gốc
            editData('services', ['duplicate'=>$duplicate], "id=$serviceId");
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Không tồn tại dịch vụ này trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?modules=services'); // Khi chuyển qua đây thì sẽ nhận được flashData đó
