<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp
$data = [
    'pageTitle' => 'Thêm người dùng'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);
// Truy vấn tất cả group để hiển thị trong select, option groups
$listAllGroup = getRaw("SELECT id, name FROM groups ORDER BY name");
if (isPost()) {
    $body = getBody();
    // echo '<pre>';
    // print_r($body);
    // echo '</pre>';
    // exit;
    $errors = [];
    // validate fullname: không được trống, >= 5 kí tự
    if (empty(trim($body['fullname']))) {
        $errors['fullname']['required'] = 'Họ tên không được để trống';
    } else {
        if (strlen(trim($body['fullname'])) < 5) {
            $errors['fullname']['min'] = 'Họ tên không ít hơn 5 ký tự';
        }
    }
    // validate group: không được trống, 
    if (empty(trim($body['group_id']))) {
        $errors['group_id']['required'] = 'Vui lòng chọn nhóm người dùng';
    }
    // validate email: không được trống, đúng định dạng email
    if (empty(trim($body['email']))) {
        $errors['email']['required'] = 'Email không được để trống';
    } else {
        if (!isEmail(trim($body['email']))) {
            $errors['email']['isEmail'] = 'Email không hợp lệ';
        } else {
            // kiểm tra email có tồn tại trong DB (đăng ký dựa vào email)
            $email = trim($body['email']);
            $sql = "SELECT id FROM users WHERE email = '$email'";
            if (getRow($sql) > 0) {
                $errors['email']['unique'] = 'Địa chỉ Email đã tồn tại';
            }
        }
    }
    // Validate password: không được trống, >= 8 kí tự
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
            $errors['confirm_password']['match'] = 'Không khớp với mật khẩu đã thiết lập';
        }
    }

    if (empty($errors)) {
        // Lưu thông tin vào DB
        // $activeToken = sha1(uniqid().time());
        $dataAdd = [
            'email' => $body['email'],
            'fullname' => $body['fullname'],
            'group_id' => $body['group_id'],
            'password' => password_hash($body['password'], PASSWORD_DEFAULT),
            'status' => $body['status'],
            'create_at' => date('Y-m-d H:i:s'),
        ];
        $addStatus = addData('users', $dataAdd);
        if ($addStatus) {
            setFlashData('msg', 'Thêm người dùng thành công.');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
        redirect('admin?modules=users&action=add');
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors); // khi load lại trang thì không còn lưu $errors này nữa, do đó cần lưu lại (session).
        setFlashData('old', $body);
        redirect('admin?modules=users&action=add');
    }
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
?>
<section class="content">
    <div class="container-fluid">
        <?php getMsg($msg, $msgType); ?>
        <form action="" method="POST">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="fullname">Họ và tên</label>
                        <input type="text" name="fullname" placeholder="Họ và tên..." class="form-control" id="fullname" value="<?php echo old('fullname', $old); ?>">
                        <?php echo  form_error('fullname', $errors, '<span class="error">', '</span>'); ?>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" placeholder="Email..." class="form-control" id="email" value="<?php echo old('email', $old); ?>">
                        <?php echo  form_error('email', $errors, '<span class="error">', '</span>'); ?>
                    </div>
                    <div class="form-group">
                        <label for="group">Nhóm người dùng</label>
                        <select name="group_id" class="form-control">
                            <option value="0">Chọn nhóm người dùng</option>
                            <?php if(!empty($listAllGroup)): foreach($listAllGroup as $group): ?>
                                <option value="<?php echo $group['id'] ;?>"><?php echo $group['name'] ;?></option>
                            <?php endforeach; endif;?>
                        </select>
                        <?php echo  form_error('group_id', $errors, '<span class="error">', '</span>'); ?>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" name="password" placeholder="Mật khẩu..." class="form-control" id="password">
                        <?php echo  form_error('password', $errors, '<span class="error">', '</span>'); ?>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Nhập lại mật khẩu</label>
                        <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu..." class="form-control" id="confirm-password">
                        <?php echo  form_error('confirm_password', $errors, '<span class="error">', '</span>'); ?>
                    </div>
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-control">
                            <option value="0">Chưa kích hoạt</option>
                            <option value="1">Kích hoạt</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="<?php echo getLinkAdmin('users'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div>
</section>

<?php
layout('footer', 'admin', $data);
