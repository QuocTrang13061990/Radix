<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if (!empty($body['id'])) {
    $blogId = $body['id'];
    $blogInfor = firstRaw("SELECT * FROM blogs WHERE id=$blogId"); // Tìm thấy trả về 1, không thì 0
    if (!empty($blogInfor)) {
        unset($blogInfor['id']);
        unset($blogInfor['update_at']);
        $blogInfor['create_at'] = date('Y-m-d H:i:s');
        $duplicate = $blogInfor['duplicate'];
        $duplicate++;
        $blogInfor['title'] = $blogInfor['title'] . ' ('.$duplicate.')';
        $addDataStatus = addData('blogs', $blogInfor);
        if ($addDataStatus) {
            setFlashData('msg', 'Nhân bản blog thành công.');
            setFlashData('msg_type', 'success');
            // Khi nhân bản xong thì phải cập nhật lại duplicate của portfolio gốc
            editData('blogs', ['duplicate'=>$duplicate], "id=$blogId");
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Không tồn tại dự án này trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?modules=blogs'); // Khi chuyển qua đây thì sẽ nhận được flashData đó
