<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if (!empty($body['id'])) {
    $contactTypeId = $body['id'];
    $contactTypeInfor = firstRaw("SELECT * FROM contact_type WHERE id=$contactTypeId"); // Tìm thấy trả về 1, không thì 0
    if (!empty($contactTypeInfor)) {
        unset($contactTypeInfor['id']);
        unset($contactTypeInfor['update_at']);
        $contactTypeInfor['create_at'] = date('Y-m-d H:i:s');
        $duplicate = $contactTypeInfor['duplicate'];
        $duplicate++;
        $contactTypeInfor['name'] = $contactTypeInfor['name'] . ' ('.$duplicate.')';
        $addDataStatus = addData('contact_type', $contactTypeInfor);
        if ($addDataStatus) {
            setFlashData('msg', 'Nhân bản phòng ban thành công.');
            setFlashData('msg_type', 'success');
            // Khi nhân bản xong thì phải cập nhật lại duplicate của portfolioCategories gốc
            editData('contact_type', ['duplicate'=>$duplicate], "id=$contactTypeId");
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Không tồn tại phòng ban này trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?modules=contact_type'); // Khi chuyển qua đây thì sẽ nhận được flashData đó