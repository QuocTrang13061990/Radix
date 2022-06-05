<?php
$data = [
    'pageTitle' => 'Cập nhật người dùng'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);
// Lấy thông tin user trong DB khi đăng nhập vào
$userId = isLogin()['user_id'];
$userInfo = firstRaw("SELECT * FROM users WHERE id=$userId");
setFlashData('oldData', $userInfo);
// Thực hiện khi submit
if(isPost()){
    $body = getBody();
    $errors = [];
    // validate fullname: không được trống, >= 5 kí tự
    if(empty(trim($body['fullname']))){
        $errors['fullname']['required'] = 'Họ tên không được để trống';
    }else {
        if(strlen(trim($body['fullname'])) < 5){
            $errors['fullname']['min'] = 'Họ tên không ít hơn 5 ký tự';
        }
    }
    // validate email: không được trống, đúng định dạng email
    if(empty(trim($body['email']))){
        $errors['email']['required'] = 'Email không được để trống';
    }else {
        if(!isEmail(trim($body['email']))){
            $errors['email']['isEmail'] = 'Email không hợp lệ';
        }else{
            // kiểm tra email có tồn tại trong DB (đăng ký dựa vào email)
            $email = trim($body['email']);
            $sql = "SELECT id FROM users WHERE email = '$email' AND id <> $userId";
            if(getRow($sql) > 0){
                $errors['email']['unique'] = 'Địa chỉ Email đã tồn tại';
            }
        }
    }
    if(empty($errors)){
        $dataEdit = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'contact_facebook' => $body['contact_facebook'],
            'contact_twitter' => $body['contact_twitter'],
            'contact_linkedin' => $body['contact_linkedin'],
            'contact_printerest' => $body['contact_printerest'],
            'about_content' => $body['about_content'],
            'update_at' => date('Y-m-d H:i:s'),
        ];
        $condition = 'id = '.$userId;
        $addStatus = editData('users', $dataEdit, $condition);
        if($addStatus){
            setFlashData('msg', 'Cập nhật thông tin người dùng thành công.');
            setFlashData('msg_type', 'success');
        }else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
    }else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors); // khi load lại trang thì không còn lưu $errors này nữa, do đó cần lưu lại (session).
        setFlashData('old', $body);
    }
    redirect('admin?modules=users&action=profile'); 

}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
$oldData = getFlashData('oldData');

if(!empty($oldData) && empty($old)){
    $old = $oldData;
}
// echo '<pre>';
// print_r($old);
// echo '</pre>';
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <?php getMsg($msg, $msgType); ?>
        <form action="" method="POST">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="fullname">Họ tên</label>
                        <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Họ tên..." value="<?php echo old('fullname', $old); ?>">
                        <?php echo  form_error('fullname', $errors, '<span class="error">', '</span>');?>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="email">email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="email..." value="<?php echo old('email', $old); ?>">
                        <?php echo  form_error('email', $errors, '<span class="error">', '</span>');?>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="contact_facebook">Facebook</label>
                        <input type="text" name="contact_facebook" id="contact_facebook" class="form-control" placeholder="Facebook..." value="<?php echo old('contact_facebook', $old); ?>">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="contact_twitter">Twitter</label>
                        <input type="text" name="contact_twitter" id="contact_twitter" class="form-control" placeholder="Twitter..." value="<?php echo old('contact_twitter', $old); ?>">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="contact_linkedin">linkedIn</label>
                        <input type="text" name="contact_linkedin" id="contact_linkedin" class="form-control" placeholder="linkedIn..." value="<?php echo old('contact_linkedin', $old); ?>">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="contact_printerest">printerest</label>
                        <input type="text" name="contact_printerest" id="contact_printerest" class="form-control" placeholder="Printerest..." value="<?php echo old('contact_printerest', $old); ?>">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="">Nội dung giới thiệu</label>
                        <textarea name="about_content" class="form-control" placeholder="Nội dung giới thiệu..."><?php echo old('about_content', $old);?></textarea>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </div>
        </form>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin', $data);
