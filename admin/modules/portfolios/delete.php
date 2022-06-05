<?php 
if(!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if(!empty($body['id'])){
    $portfolioId = $body['id'];
    $portfolioInfor = firstRaw("SELECT * FROM portfolios WHERE id=$portfolioId"); // Tìm thấy trả về 1, không thì 0
      
    if(!empty($portfolioInfor)){
        // xóa ở bảng portfolios_images trước
        deleteData('portfolios_images', "portfolio_id=$portfolioId");
        $deletePortfolio = deleteData('portfolios', "id=$portfolioId");
        if($deletePortfolio){
            setFlashData('msg', 'Xóa '.$portfolioInfor['name'].' thành công.');
            setFlashData('msg_type', 'success');
        }else{
            setFlashData('msg', 'Lỗi hệ thống. Vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    }else {
        setFlashData('msg', 'Không tồn tại dự án này trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
}else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?modules=portfolios'); // Khi chuyển qua đây thì sẽ nhận được flashData đó
