<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if (!empty($body['id'])) {
    $pageId = $body['id'];
    $pageInfor = firstRaw("SELECT * FROM pages WHERE id=$pageId"); // Tìm thấy trả về 1, không thì 0
    if (!empty($pageInfor)) {
        unset($pageInfor['id']);
        unset($pageInfor['update_at']);
        $pageInfor['create_at'] = date('Y-m-d H:i:s');
        $duplicate = $pageInfor['duplicate'];
        $duplicate++;
        $pageInfor['title'] = $pageInfor['title'] . ' ('.$duplicate.')';
        $addDataStatus = addData('pages', $pageInfor);
        if ($addDataStatus) {
            setFlashData('msg', 'Nhân bản trang thành công.');
            setFlashData('msg_type', 'success');
            // Khi nhân bản xong thì phải cập nhật lại duplicate của page gốc
            editData('pages', ['duplicate'=>$duplicate], "id=$pageId");
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Không tồn tại trang này trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?modules=pages'); // Khi chuyển qua đây thì sẽ nhận được flashData đó
