<?php
$userId = isLogin()['user_id'];
if (isPost()) {
    $body = getBody();
    // echo '<pre>';
    // print_r($body);
    // echo '</pre>';
    // exit;
    $errors = [];
    // validate fullname: không được trống, >= 5 kí tự
    if (empty(trim($body['name']))) {
        $errors['name']['required'] = 'Tên danh mục không được để trống';
    }
    if (empty(trim($body['slug']))) {
        $errors['slug']['required'] = 'Đường dẫn tĩnh không được để trống';
    }
    if (empty($errors)) {
        // Lưu thông tin vào DB
        // $activeToken = sha1(uniqid().time());
        $dataAdd = [
            'name' => $body['name'],
            'slug' => $body['slug'],
            'user_id' => $userId,
            'create_at' => date('Y-m-d H:i:s'),
        ];
        $addStatus = addData('blog_categories', $dataAdd);
        if ($addStatus) {
            setFlashData('msg', 'Thêm danh mục mới thành công.');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
        redirect('admin?modules=blog_categories');
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors); // khi load lại trang thì không còn lưu $errors này nữa, do đó cần lưu lại (session).
        // setFlashData('old', $body);
        redirect('admin?modules=blog_categories');
    }
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

?>

<h4>Thêm danh mục</h4>
<hr>
<form action="" method="POST">
    <div class="form-group">
        <!-- <label for="">Tên danh mục</label> -->
        <input type="text" name="name" class="form-control module_title" placeholder="Tên danh mục blog..." value="<?php echo old('name', $old); ?>">
        <?php echo  form_error('name', $errors, '<span class="error">', '</span>'); ?>
    </div>
    <div class="form-group">
        <label for="">Đường dẫn tĩnh</label>
        <input type="text" class="form-control module_slug" name="slug" placeholder="Đường dẫn tĩnh..." value="<?php echo old('slug', $old); ?>">
        <?php echo  form_error('slug', $errors, '<span class="error">', '</span>'); ?>
        <p class="render-link"><b>Link</b>: <span></span></p>
    </div>
    <button type="submit" class="btn btn-primary">Thêm mới</button>
</form>