<?php
if(!defined('_INCODE')) die('access delined......');    
// file nay chua chuc nang dang nhap
$data = array(
    'titlePage' => 'Đăng nhập hệ thống',
);
layout('header-login', 'admin', $data);  
// Kiểm tra trạng thái đăng nhập
if(isLogin()){
    redirect('admin');
}
// echo 'ok';
if(isPost()){
    $body = getBody();
    if(!empty(trim($body['email'])) && !empty(trim($body['password']))){
        $email = $body['email'];
        $password = $body['password'];
        $queryUser = firstRaw("SELECT id, password FROM users WHERE email='$email' AND status = 1");
        // echo '<pre>';
        // print_r($queryUser);
        // echo '</pre>';
        // exit;
        if(!empty($queryUser)){
            $passwordHash = $queryUser['password'];
            $user_id = $queryUser['id'];
            // Chú ý: $password đặt trước $passwordHash trong hàm password_verify
            if(password_verify($password, $passwordHash)){
                $tokenLogin = sha1(uniqid().time());
                $dataToken = [
                    'user_id' => $user_id,
                    'token' => $tokenLogin,
                    'create_at' => date('Y-m-d H:i:s')
                ];
                $addTokenStatus = addData('login_token', $dataToken);
                if($addTokenStatus){
                    // Lưu tokenLogin vào session
                    setSession('tokenLogin', $tokenLogin);
                    redirect('admin');
                }else {
                    setFlashData('msg', 'Lỗi hệ thống, bạn không thể đăng nhập vào lúc này');
                    setFlashData('msg_type', 'danger');
                    redirect('admin/?modules=auth&action=login'); 
                }
            }else {
                setFlashData('msg', 'Mật khẩu không đúng');
                setFlashData('msg_type', 'danger');
                redirect('admin/?modules=auth&action=login'); 
            }
        }else {
            setFlashData('msg', 'Email không tồn tại trong hệ thống hoặc chưa được kích hoạt.');
            setFlashData('msg_type', 'danger');
            redirect('admin/?modules=auth&action=login');
        }
        
    }else {
        setFlashData('msg', 'Vui lòng nhập email và mật khẩu');
        setFlashData('msg_type', 'danger');
        redirect('admin/?modules=auth&action=login');
    }
}

// Nhận thông báo từ active.php gửi sang và thông báo từ login.php
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
?>

<div class="row">
    <div class="col-6" style="margin: 20px auto;">
        <h3 class="text-center text-uppercase">đăng nhập hệ thống</h3>
        <?php echo getMsg($msg, $msgType); ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="">Email</label>
                <input type="email" name="email" placeholder="Địa chỉ Email..." class="form-control" value="">
            </div>
            <div class="form-group">
                <label for="">Mật khẩu</label>
                <input type="password" name="password" placeholder="Nhập mật khẩu..." class="form-control" value="">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
            <hr>
            <p class="text-center"><a href="?modules=auth&action=forgot">Quên mật khẩu?</a></p>
        </form>
    </div>

</div>

<?php
layout('footer-login', 'admin');

