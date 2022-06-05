<?php
// $userId = isLogin()['user_id'];
if (isPost()) {
    $body = getBody();
    // echo '<pre>';
    // print_r($body);
    // echo '</pre>';
    // exit;
    $errors = [];
    // validate fullname: không được trống, >= 5 kí tự
    if (empty(trim($body['name']))) {
        $errors['name']['required'] = 'Tên phòng ban không được để trống';
    }
    
    if (empty($errors)) {
        // Lưu thông tin vào DB
        // $activeToken = sha1(uniqid().time());
        $dataAdd = [
            'name' => $body['name'],
            'create_at' => date('Y-m-d H:i:s'),
        ];
        $addStatus = addData('contact_type', $dataAdd);
        if ($addStatus) {
            setFlashData('msg', 'Thêm phòng ban mới thành công.');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
        redirect('admin?modules=contact_type');
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors); // khi load lại trang thì không còn lưu $errors này nữa, do đó cần lưu lại (session).
        // setFlashData('old', $body);
        redirect('admin?modules=contact_type');
    }
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

?>

<h4>Thêm phòng ban</h4>
<hr>
<form action="" method="POST">
    <div class="form-group">
        <!-- <label for="">Tên danh mục</label> -->
        <input type="text" name="name" class="form-control" placeholder="Tên phòng ban..." value="<?php echo old('name', $old); ?>">
        <?php echo  form_error('name', $errors, '<span class="error">', '</span>'); ?>
    </div>
    <button type="submit" class="btn btn-primary">Thêm mới</button>
</form>