<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if (!empty($body['id'])) {
    $portfolioId = $body['id'];
    $portfolioInfor = firstRaw("SELECT * FROM portfolios WHERE id=$portfolioId"); // Tìm thấy trả về 1, không thì 0
    if (!empty($portfolioInfor)) {
        unset($portfolioInfor['id']);
        unset($portfolioInfor['update_at']);
        $portfolioInfor['create_at'] = date('Y-m-d H:i:s');
        $duplicate = $portfolioInfor['duplicate'];
        $duplicate++;
        $portfolioInfor['name'] = $portfolioInfor['name'] . ' ('.$duplicate.')';
        $addDataStatus = addData('portfolios', $portfolioInfor);
        if ($addDataStatus) {
            setFlashData('msg', 'Nhân bản dịch vụ thành công.');
            setFlashData('msg_type', 'success');
            // Khi nhân bản xong thì phải cập nhật lại duplicate của portfolio gốc
            editData('portfolios', ['duplicate'=>$duplicate], "id=$portfolioId");
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
redirect('admin?modules=portfolios'); // Khi chuyển qua đây thì sẽ nhận được flashData đó
