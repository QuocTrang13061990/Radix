<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if (!empty($body['id'])) {
    $blogCategoryId = $body['id'];
    $blogCategoryInfor = firstRaw("SELECT * FROM blog_categories WHERE id=$blogCategoryId"); // Tìm thấy trả về 1, không thì 0
    if (!empty($blogCategoryInfor)) {
        unset($blogCategoryInfor['id']);
        unset($blogCategoryInfor['update_at']);
        $blogCategoryInfor['create_at'] = date('Y-m-d H:i:s');
        $duplicate = $blogCategoryInfor['duplicate'];
        $duplicate++;
        $blogCategoryInfor['name'] = $blogCategoryInfor['name'] . ' ('.$duplicate.')';
        $addDataStatus = addData('blog_categories', $blogCategoryInfor);
        if ($addDataStatus) {
            setFlashData('msg', 'Nhân bản danh mục thành công.');
            setFlashData('msg_type', 'success');
            // Khi nhân bản xong thì phải cập nhật lại duplicate của portfolioCategories gốc
            editData('blog_categories', ['duplicate'=>$duplicate], "id=$blogCategoryId");
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
redirect('admin?modules=blog_categories'); // Khi chuyển qua đây thì sẽ nhận được flashData đó