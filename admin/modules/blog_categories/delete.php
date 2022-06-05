<?php 
if(!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if(!empty($body['id'])){
    $blogCategorieId = $body['id'];
    $blogCategorieInfor = firstRaw("SELECT * FROM blog_categories WHERE id=$blogCategorieId"); // Tìm thấy trả về 1, không thì 0
      
    if(!empty($blogCategorieInfor)){
       // Kiểm tra trong danh mục này có dự án nào không (Nếu có không xóa và hiển thị thông báo)
       $checkBlogRow = getRow("SELECT id FROM blogs WHERE category_id=$blogCategorieId");
       if($checkBlogRow){
            setFlashData('msg', 'Danh mục '.$blogCategorieInfor['name'].' vẫn còn '.$checkBlogRow.' tin (blog), không thể xóa.');
            setFlashData('msg_type', 'danger');
       }else{
            $deletePortfolioCategorie = deleteData('blog_categories', "id=$blogCategorieId");
            if($deletePortfolioCategorie){
                setFlashData('msg', 'Xóa danh mục '.$blogCategorieInfor['name'].' thành công.');
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
redirect('admin?modules=blog_categories'); // Khi chuyển qua đây thì sẽ nhận được flashData đó
