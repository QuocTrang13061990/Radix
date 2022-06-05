<?php 
if(!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if(!empty($body['id'])){
    $portfolioCategorieId = $body['id'];
    $portfolioCategorieInfor = firstRaw("SELECT * FROM portfolio_categories WHERE id=$portfolioCategorieId"); // Tìm thấy trả về 1, không thì 0
      
    if(!empty($portfolioCategorieInfor)){
       // Kiểm tra trong danh mục này có dự án nào không (Nếu có không xóa và hiển thị thông báo)
       $checkPortfolioRow = getRow("SELECT id FROM portfolios WHERE portfolio_category_id=$portfolioCategorieId");
       if($checkPortfolioRow){
            setFlashData('msg', 'Danh mục '.$portfolioCategorieInfor['name'].' vẫn còn '.$checkPortfolioRow.' dự án, không thể xóa.');
            setFlashData('msg_type', 'danger');
       }else{
            $deletePortfolioCategorie = deleteData('portfolio_categories', "id=$portfolioCategorieId");
            if($deletePortfolioCategorie){
                setFlashData('msg', 'Xóa danh mục thành công.');
                setFlashData('msg_type', 'success');
            }else{
                setFlashData('msg', 'Không tồn tại danh mục này trong hệ thống.');
                setFlashData('msg_type', 'danger');
            }
       }
    }else {
        setFlashData('msg', 'Không tồn tại danh mục này trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
}else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?modules=portfolio_categories'); // Khi chuyển qua đây thì sẽ nhận được flashData đó
