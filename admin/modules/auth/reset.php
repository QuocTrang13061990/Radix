<?php
if(!defined('_INCODE')) die('access delined......');
// file nay chua chuc nang đặt lại mật khẩu
$data = array(
    'titlePage' => 'Đặt lại mật khẩu',
);
layout('header-login', 'admin', $data);
echo '<div class="container text-center"><br>';
$token = getBody()['token']; // lấy token theo method GET: cái này lấy từ link trong email
if(!empty($token)){
    $sql = "SELECT id, email FROM users WHERE forget_token='$token'";
    $queryStatus = firstRaw($sql);
    if(!empty($queryStatus)){
        if (isPost()){
            $body = getBody();
            $errors = [];
            // Validate password: không được trống, >= 8 kí tự
            if(empty(trim($body['password']))){
                $errors['password']['required'] = 'Mật khẩu không được để trống';
            }else {
                if(strlen(trim($body['password'])) < 8){
                    $errors['password']['min'] = 'Mật khẩu không ít hơn 8 kí tự';
                }
            }
            // validate confirm_password: không được trống, giống password
            if(empty(trim($body['confirm_password']))){
                $errors['confirm_password']['required'] = 'Xác nhận mật khẩu không được để trống';
            }else {
                if(trim($body['password']) !== trim($body['confirm_password'])){
                    $errors['confirm_password']['match'] = 'Không khớp với mật khẩu đã thiết lập';
                }
            }
            if(empty($errors)){
                $user_id = $queryStatus['id'];
                $email = $queryStatus['email'];
                // Xử lý update mật khẩu
                $passwordHash = password_hash($body['password'], PASSWORD_DEFAULT);
                $dataUpdate = [
                    'password' => $passwordHash,
                    'forget_token' => null,
                    'update_at' => date('Y-m-d H:i:s')
                ];
                $updateStatus = editData('users', $dataUpdate, "id=$user_id");
                if($updateStatus){
                    setFlashData('msg', 'Thay đổi mật khẩu thành công. Bạn có thể đăng nhập lại ngay bây giờ');
                    setFlashData('msg_type', 'success');
                    // Gửi email thông báo đã thay đổi mật khẩu
                    $subject = 'Bạn vừa thay đổi mật khẩu';
                    $content = 'Chúc mừng bạn đã thay đổi mật khẩu thành công';
                    sendMail($email, $subject, $content);
                    redirect('admin?modules=auth&action=login');
                }else {
                    setFlashData('msg', 'Lỗi hệ thống! Bạn không thể đặt lại mật khẩu vào lúc này');
                    setFlashData('msg_type', 'danger');
                    redirect('admin?modules=auth&action=reset&token='.$token);
                }
            }else {
                setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
                setFlashData('msg_type', 'danger');
                setFlashData('errors', $errors); // khi load lại trang thì không còn lưu $errors này nữa, do đó cần lưu lại (session).
                redirect('admin?modules=auth&action=reset&token='.$token);
            }
        }
        $msg = getFlashData('msg');
        $msgType = getFlashData('msg_type');
        $errors = getFlashData('errors');
        ?>
        <div class="row text-left">
            <div class="col-6" style="margin: 20px auto;">
                <h3 class="text-center text-uppercase">Đặt lại mật khẩu</h3>
                <?php echo getMsg($msg, $msgType); ?>
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="">Mật khẩu</label>
                        <input type="password" name="password" placeholder="Nhập mật khẩu..." class="form-control" value="">
                        <?php echo  form_error('password', $errors, '<span class="error">', '</span>');?>
                    </div>
                    <div class="form-group">
                        <label for="">Nhập lại mật khẩu</label>
                        <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu..." class="form-control" value="">
                        <?php echo  form_error('confirm_password', $errors, '<span class="error">', '</span>');?>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
                    <hr>
                    <p class="text-center"><a href="?modules=auth&action=login">Đăng nhập</a></p>
                    <p class="text-center"><a href="?modules=auth&action=register">Đăng ký</a></p>
                    <!-- lấy token theo method POST -->
                    <input type="hidden" name="token" id="" value="<?php echo $token; ?>">
                </form>
            </div>
        </div>
        <?php
    }else {
        getMsg('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
    }
}else {
    getMsg('Liên kết không tồn tại hoặc đã hết hạn', 'danger');
}
echo '</div>';
layout('footer-login', 'admin');