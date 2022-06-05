<?php
$data = [
    'pageTitle' => 'Đổi mật khẩu'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);
// Lấy thông tin user trong DB khi đăng nhập vào
$userId = isLogin()['user_id'];
$userInfo = firstRaw("SELECT * FROM users WHERE id=$userId");
// Thực hiện khi submit
if (isPost()) {
    $body = getBody();
    $errors = [];
    // validate old password
    if(empty(trim($body['old_password']))){
        $errors['old_password']['required'] = 'Vui lòng nhập mật khẩu cũ';
    }else {
        $passwordHash = $userInfo['password'];
        if(!password_verify(trim($body['old_password']), $passwordHash)){
            $errors['old_password']['match'] = 'Mật khẩu cũ không đúng.';
        }
    }
    // validate mật khẩu new
    if (empty(trim($body['password']))) {
        $errors['password']['required'] = 'Mật khẩu không được để trống';
    } else {
        if (strlen(trim($body['password'])) < 8) {
            $errors['password']['min'] = 'Mật khẩu không ít hơn 8 kí tự';
        }
    }
    // validate confirm_password: không được trống, giống password
    if (empty(trim($body['confirm_password']))) {
        $errors['confirm_password']['required'] = 'Xác nhận mật khẩu không được để trống';
    } else {
        if (trim($body['password']) !== trim($body['confirm_password'])) {
            $errors['confirm_password']['match'] = 'Không khớp với mật khẩu mới';
        }
    }
    if (empty($errors)) {
        $dataEdit = [
            'password' => password_hash($body['password'], PASSWORD_DEFAULT),
            'update_at' => date('Y-m-d H:i:s'),
        ];
        $condition = 'id = ' . $userId;
        $addStatus = editData('users', $dataEdit, $condition);
        if ($addStatus) {
            setFlashData('msg', 'Đổi mật khẩu thành công. Bạn có thể đăng xuất và đăng nhập lại với mật khẩu mới');
            setFlashData('msg_type', 'success');
            // redirect('admin?modules=auth&action=login');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors); // khi load lại trang thì không còn lưu $errors này nữa, do đó cần lưu lại (session).
    }
    redirect('admin?modules=auth&action=change_password');
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <?php getMsg($msg, $msgType); ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="old_password">Mật khẩu cũ</label>
                <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Nhập mật khẩu cũ..." value="">
                <?php echo  form_error('old_password', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu mới</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu mới..." value="">
                <?php echo  form_error('password', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="confirm_password">Nhập lại mật khẩu mới</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Nhập lại mật khẩu mới..." value="">
                <?php echo  form_error('confirm_password', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
        </form>
    </div>
</section>
<!-- /.content -->

<?php
layout('footer', 'admin', $data);
