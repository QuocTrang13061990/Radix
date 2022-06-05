<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp
$data = [
    'pageTitle' => 'Cập nhật liên hệ'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);
$userId = isLogin()['user_id'];
// Lấy dl qua method GET
$body = getBody('GET');
if (!empty($body['id'])) {
    $contactId = $body['id'];
    $contactDetail = firstRaw("SELECT * FROM contacts WHERE id=$contactId");
    if (!empty($contactDetail)) {
        // Gán userDetail vào flashData
        setFlashData('contactDetail', $contactDetail);
    } else {
        redirect('admin?modules=contacts');
    }
} else {
    redirect('admin?modules=contacts');
}

if (isPost()) {
    $body = getBody();
    
    $errors = [];
    if (empty(trim($body['fullname']))) {
        $errors['fullname']['required'] = 'Tên liên hệ không được để trống';
    }
    // validate email: không được trống, đúng định dạng email
    if (empty(trim($body['email']))) {
        $errors['email']['required'] = 'Email không được để trống';
    } elseif (!isEmail(trim($body['email']))) {
        $errors['email']['isEmail'] = 'Email không hợp lệ';
    } 
    if (empty(trim($body['contact_type_id']))) {
        $errors['contact_type_id']['required'] = 'Phòng ban không được để trống';
    }
    if (empty(trim($body['message']))) {
        $errors['message']['required'] = 'Nội dung không được để trống';
    }

    if (empty($errors)) {
        $dataEdit = [
            'fullname' => $body['fullname'],
            'email' => $body['email'],
            'type_id' => $body['contact_type_id'],
            'status' => $body['status'],
            'message' => $body['message'],
            'note' => $body['note'],
            'update_at' => date('Y-m-d H:i:s'),
        ];
        $editStatus = editData('contacts', $dataEdit, "id=$contactId");
        if ($editStatus) {
            setFlashData('msg', 'Cập nhật liên hệ thành công.');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
        redirect('admin?modules=contacts');
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors); // khi load lại trang thì không còn lưu $errors này nữa, do đó cần lưu lại (session).
        setFlashData('old', $body);
        redirect('admin?modules=contacts&action=edit&id='.$contactId);
    }
}
// Truy vấn lất tất cả phòng ban để hiển thị option
$listAllContactType = getRaw("SELECT id, name FROM contact_type ORDER BY name");
// echo '<pre>';
// print_r($listAllCate);
// echo '</pre>';
// exit;
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
$oldData = getFlashData('contactDetail');
if (empty($old) && !empty($oldData)) {
    $old = $oldData;
}
?>
<section class="content">
    <div class="container-fluid">
        <?php getMsg($msg, $msgType); ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="">Họ tên</label>
                <input type="text" class="form-control" name="fullname" placeholder="Nhập họ tên..." value="<?php echo old('fullname', $old); ?>">
                <?php echo  form_error('fullname', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Email</label>
                <input type="text" class="form-control" name="email" placeholder="Nhập email..." value="<?php echo old('email', $old); ?>">
                <?php echo  form_error('email', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Phòng ban</label>
                <select name="contact_type_id" id="" class="form-control">
                    <option value="0">Chọn phòng ban</option>
                    <?php if (!empty($listAllContactType)) : foreach ($listAllContactType as $item) : ?>
                            <option value="<?php echo $item['id']; ?>" <?php echo (old('type_id', $old) == $item['id']) ? 'selected' : false; ?>><?php echo $item['name']; ?></option>
                    <?php endforeach;
                    endif; ?>
                </select>
                <?php echo  form_error('contact_type_id', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <select name="status" class="form-control">
                    <option value="0">Chọn trạng thái</option>
                    <option value="1" <?php echo !empty($status) && $status == 1 ? 'selected' : false; ?>>Đã xử lý</option>
                    <option value="2" <?php echo !empty($status) && $status == 2 ? 'selected' : false; ?>>Chưa xử lý</option>
                </select>
                <?php echo  form_error('status', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Nội dung</label>
                <textarea rows="10" name="message" class="form-control" placeholder="Nhập nội dung..."><?php echo old('message', $old); ?></textarea>
            </div>
            <div class="form-group">
                <label for="">Ghi chú</label>
                <textarea rows="10" name="note" class="form-control" placeholder="Nhập ghi chú..."><?php echo old('note', $old); ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="<?php echo getLinkAdmin('contacts'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div>
</section>

<?php
layout('footer', 'admin', $data);
