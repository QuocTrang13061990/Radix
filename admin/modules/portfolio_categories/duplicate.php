<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if (!empty($body['id'])) {
    $portfolioCategoryId = $body['id'];
    $portfolioCategoryInfor = firstRaw("SELECT * FROM portfolio_categories WHERE id=$portfolioCategoryId"); // Tìm thấy trả về 1, không thì 0
    if (!empty($portfolioCategoryInfor)) {
        unset($portfolioCategoryInfor['id']);
        unset($portfolioCategoryInfor['update_at']);
        $portfolioCategoryInfor['create_at'] = date('Y-m-d H:i:s');
        $duplicate = $portfolioCategoryInfor['duplicate'];
        $duplicate++;
        $portfolioCategoryInfor['name'] = $portfolioCategoryInfor['name'] . ' ('.$duplicate.')';
        $addDataStatus = addData('portfolio_categories', $portfolioCategoryInfor);
        if ($addDataStatus) {
            setFlashData('msg', 'Nhân bản danh mục thành công.');
            setFlashData('msg_type', 'success');
            // Khi nhân bản xong thì phải cập nhật lại duplicate của portfolioCategories gốc
            editData('portfolio_categories', ['duplicate'=>$duplicate], "id=$portfolioCategoryId");
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Không tồn tại danh mục này trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?modules=portfolio_categories'); // Khi chuyển qua đây thì sẽ nhận được flashData đó