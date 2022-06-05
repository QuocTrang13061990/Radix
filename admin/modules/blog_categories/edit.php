<?php 
// Lấy dl qua method GET
$body = getBody('GET');
if (!empty($body['id'])) {
    $blogCategorieId = $body['id'];
    $blogCategorieDetail = firstRaw("SELECT * FROM blog_categories WHERE id=$blogCategorieId");
    if (!empty($blogCategorieDetail)) {
        // Gán userDetail vào flashData
        setFlashData('blogCategorieDetail', $blogCategorieDetail);
    } else {
        redirect('admin?modules=blog_categories');
    }
} else {
    redirect('admin?modules=blog_categories');
}
if(isPost()){
    $body = getBody();
  
    $errors = [];
    // validate fullname: không được trống, >= 5 kí tự
    if(empty(trim($body['name']))){
        $errors['name']['required'] = 'Tên danh mục không được để trống';
    }
    if(empty(trim($body['slug']))){
        $errors['slug']['required'] = 'Tên danh mục không được để trống';
    }
    if(empty($errors)){
        // Lưu thông tin vào DB
        // $activeToken = sha1(uniqid().time());
        $dataEdit = [
            'name' => $body['name'],
            'slug' => $body['slug'],
            'update_at' => date('Y-m-d H:i:s'),
        ];
        $condition = 'id = '.$blogCategorieId;
        $editStatus = editData('blog_categories', $dataEdit, $condition);
        if($editStatus){
            setFlashData('msg', 'Chỉnh sửa danh mục blog thành công.');
            setFlashData('msg_type', 'success');
        }else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
        redirect('admin?modules=blog_categories');
    }else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors); // khi load lại trang thì không còn lưu $errors này nữa, do đó cần lưu lại (session).
        setFlashData('old', $body);
        redirect('admin?modules=blog_categories&id='.$blogCategorieId.'&view='.$view); 
    }
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('blogCategorieDetail');
// echo '<pre>';
// print_r($oldData);
// echo '</pre>';
// exit;
$old = getFlashData('old');
if(empty($old) && !empty($oldData)){
    $old = $oldData;
}
?>

<h4>Cập nhật danh mục</h4>
<hr>
<form action="" method="POST">
    <div class="form-group">
        <!-- <label for="">Tên danh mục</label> -->
        <input type="text" name="name" class="form-control module_title" placeholder="Tên danh mục blog..." value="<?php echo old('name', $old);?>">
        <?php echo  form_error('name', $errors, '<span class="error">', '</span>');?>
    </div>
    <div class="form-group">
        <label for="">Đường dẫn tĩnh</label>
        <input type="text" class="form-control module_slug" name="slug" placeholder="Đường dẫn tĩnh..." value="<?php echo old('slug', $old); ?>">
        <?php echo  form_error('slug', $errors, '<span class="error">', '</span>'); ?>
        <p class="render-link"><b>Link</b>: <span></span></p>
    </div>
    <button type="submit" class="btn btn-primary">Cập nhật</button>
    <a href="<?php echo getLinkAdmin('blog_categories'); ?>" class="btn btn-success">Quay lại</a>
</form>