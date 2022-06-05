<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp
$data = [
    'pageTitle' => 'Thêm trang'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);
$userId = isLogin()['user_id'];
if (isPost()) {
    $body = getBody();
    // echo '<pre>';
    // print_r($body);
    // echo '</pre>';
    // exit;
    $errors = [];
    if (empty(trim($body['title']))) {
        $errors['title']['required'] = 'Tiêu đề trang không được để trống';
    } 
    if (empty(trim($body['slug']))) {
        $errors['slug']['required'] = 'Đường dẫn tĩnh không được để trống';
    } 
    if (empty(trim($body['content']))) {
        $errors['content']['required'] = 'Nội dung không được để trống';
    } 
    if (empty($errors)) {
        // Lưu thông tin vào DB
        // $activeToken = sha1(uniqid().time());
        $dataAdd = [
            'title' => $body['title'],
            'slug' => $body['slug'],
            'content' => $body['content'],
            'user_id' => $userId,
            'create_at' => date('Y-m-d H:i:s'),
        ];
        $addStatus = addData('pages', $dataAdd);
        if ($addStatus) {
            setFlashData('msg', 'Thêm trang mới thành công.');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
        redirect('admin?modules=pages');
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors); // khi load lại trang thì không còn lưu $errors này nữa, do đó cần lưu lại (session).
        setFlashData('old', $body);
        redirect('admin?modules=pages&action=add');
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
            <div class="form-group">
                <label for="">Tiêu đề trang</label>
                <input type="text" class="form-control module_title" name="title" placeholder="Nhập tiêu đề trang..." value="<?php echo old('title', $old);?>">
                <?php echo  form_error('title', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Đường dẫn tĩnh</label>
                <input type="text" class="form-control module_slug" name="slug" placeholder="Nhập đường dẫn tĩnh..." value="<?php echo old('slug', $old);?>">
                <?php echo  form_error('slug', $errors, '<span class="error">', '</span>'); ?>
                <p class="render-link"><b>Link</b>: <span></span></p>
            </div>
            <div class="form-group">
                <label for="">Nội dung</label>
                <textarea name="content" class="form-control editor"><?php echo old('content', $old);?></textarea>
                <?php echo  form_error('content', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="<?php echo getLinkAdmin('pages'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div>
</section>

<?php
layout('footer', 'admin', $data);
