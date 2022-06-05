<?php
if(!defined('_INCODE')) die('access delined......');
// file nay chua chuc nang quen mat khau

$data = array(
    'titlePage' => 'Đặt lại mật khẩu',
);
layout('header-login', 'admin', $data);

// Kiểm tra trạng thái đăng nhập
if(isLogin()){
    redirect('?modules=users');
}
if(isPost()){
    $body = getBody();
    if(!empty(trim($body['email']))){
        $email = trim($body['email']);
        $sql = "SELECT id, fullname FROM users WHERE email='$email'";
        $queryUser = firstRaw($sql);
        if($queryUser){
            $forgotToken = sha1(uniqid().time());
            $dataUpdate = [
                'forget_token' => $forgotToken
            ];
            $updateStatus = editData('users', $dataUpdate, 'id='.$queryUser['id']);
            if($updateStatus){
                // Tạo link khôi phục
                $linkReset = _WEB_HOST_ROOT_ADMIN.'/?modules=auth&action=reset&token='.$forgotToken;
                // Thiết lập gửi email
                $subject = 'Yêu cầu khôi phục mật khẩu';
                $content = 'Chào bạn '.$queryUser['fullname'].'<br>';
                $content.= 'Chúng tôi nhận được yêu cầu khôi phục mật khẩu từ bạn. Vui lòng click vào link dưới đây để khôi phục mật khẩu: <br>';
                $content.= $linkReset.'<br>';
                $content.= 'Trân trọng!';
                // Tiến hành gửi email
                $sendStatus = sendMail($email, $subject, $content);
                if($sendStatus){
                    setFlashData('msg', 'Vui lòng kiểm tra email để xem hướng dẫn đặt lại mật khẩu');
                    setFlashData('msg_type', 'success');
                }else {
                    setFlashData('msg', 'Lỗi hệ thống! Bạn không thể sử dụng chức năng này');
                    setFlashData('msg_type', 'danger');
                }
            }else {
                setFlashData('msg', 'Lỗi hệ thống! Bạn không thể sử dụng chức năng này');
                setFlashData('msg_type', 'danger');
            }
        }else {
            setFlashData('msg', 'Email không tồn tại trong hệ thống');
            setFlashData('msg_type', 'danger');
        }
    }else {
        setFlashData('msg', 'Vui lòng nhập email');
        setFlashData('msg_type', 'danger');
    }
    redirect('admin?modules=auth&action=forgot');
}

// Nhận thông báo từ active.php gửi sang và thông báo từ login.php
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
?>

<div class="row">
    <div class="col-6" style="margin: 20px auto;">
        <h3 class="text-center text-uppercase">Đặt lại mật khẩu</h3>
        <?php echo getMsg($msg, $msgType); ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" placeholder="Địa chỉ Email..." class="form-control" value="">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Xác nhận</button>
            <hr>
            <p class="text-center"><a href="admin?modules=auth&action=login">Đăng nhập</a></p>
        </form>
    </div>

</div>

<?php
layout('footer-login', 'admin');
