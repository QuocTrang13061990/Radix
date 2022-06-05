<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$data = [
    'pageTitle' => 'Chỉnh sửa dịch vụ'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);
// Lấy dl qua method GET
$body = getBody('GET');

if (!empty($body['id'])) {
    $serviceId = $body['id'];
    $serviceDetail = firstRaw("SELECT * FROM services WHERE id = $serviceId");
    if (!empty($serviceDetail)) {
        // Gán userDetail vào flashData
        setFlashData('serviceDetail', $serviceDetail);
    } else {
        redirect('admin?modules=services');
    }
} else {
    redirect('admin?modules=services');
}

// echo '<pre>';
// print_r($serviceDetail);
// echo '</pre>';
// exit;
if (isPost()) {
    $body = getBody();

    $errors = [];
    if (empty(trim($body['name']))) {
        $errors['name']['required'] = 'Tên dịch vụ không được để trống';
    }
    if (empty(trim($body['slug']))) {
        $errors['slug']['required'] = 'Đường dẫn tĩnh không được để trống';
    }
    if (empty(trim($body['icon']))) {
        $errors['icon']['required'] = 'Icon không được để trống';
    }
    if (empty(trim($body['content']))) {
        $errors['content']['required'] = 'Nội dung không được để trống';
    }
    if (empty($errors)) {
        // Lưu thông tin vào DB
        // $activeToken = sha1(uniqid().time());
        $dataEdit = [
            'name' => $body['name'],
            'slug' => $body['slug'],
            'icon' => $body['icon'],
            'description' => $body['description'],
            'content' => $body['content'],
            'update_at' => date('Y-m-d H:i:s'),
        ];
        $condition = 'id = ' . $serviceId;
        $editStatus = editData('services', $dataEdit, $condition);
        if ($editStatus) {
            setFlashData('msg', 'Chỉnh sửa dịch vụ thành công.');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
        redirect('admin?modules=services');
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors); // khi load lại trang thì không còn lưu $errors này nữa, do đó cần lưu lại (session).
        setFlashData('old', $body);
        redirect('admin?modules=services&action=edit&id=' . $serviceId);
    }
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('serviceDetail');
// echo '<pre>';
// print_r($oldData);
// echo '</pre>';
// exit;
$old = getFlashData('old');
if (empty($old) && !empty($oldData)) {
    $old = $oldData;
}
?>
<section class="content">
    <div class="container-fluid">
        <?php getMsg($msg, $msgType); ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="">Tên dịch vụ</label>
                <input type="text" class="form-control module_title" name="name" placeholder="Nhập tên dịch vụ..." value="<?php echo old('name', $old); ?>">
                <?php echo  form_error('name', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Đường dẫn tĩnh</label>
                <input type="text" class="form-control module_slug" name="slug" placeholder="Nhập đường dẫn tĩnh..." value="<?php echo old('slug', $old); ?>">
                <?php echo  form_error('slug', $errors, '<span class="error">', '</span>'); ?>
                <p class="render-link"><b>Link</b>: <span></span></p>
            </div>
            <div class="form-group">
                <label for="">Icon</label>
                <div class="row ckfinder-group">
                    <div class="col-10">
                        <input type="text" class="form-control image-render" name="icon" placeholder="Nhập đường dẫn ảnh hoặc mã icon..." value="<?php echo old('icon', $old); ?>">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-success btn-block choose-image">Chọn ảnh</button>
                    </div>
                </div>
                <?php echo  form_error('icon', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Mô tả ngắn</label>
                <textarea name="description" placeholder="Nhập mô tả ngắn..." class="form-control editor"><?php echo old('description', $old); ?></textarea>
                <?php echo  form_error('description', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Nội dung</label>
                <textarea name="content" class="form-control editor"><?php echo old('content', $old); ?></textarea>
                <?php echo  form_error('content', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="<?php echo getLinkAdmin('services'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div>
</section>

<?php
layout('footer', 'admin', $data);
