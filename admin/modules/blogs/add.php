<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp
$data = [
    'pageTitle' => 'Thêm blog'
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
        $errors['title']['required'] = 'Tiêu đề blog không được để trống';
    } 
    if (empty(trim($body['slug']))) {
        $errors['slug']['required'] = 'Đường dẫn tĩnh không được để trống';
    } 
    if (empty(trim($body['blog_category_id']))) {
        $errors['blog_category_id']['required'] = 'Danh mục blog không được để trống';
    } 
    if (empty(trim($body['thumbnail']))) {
        $errors['thumbnail']['required'] = 'Ảnh đại diện không được để trống';
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
            'category_id' => $body['blog_category_id'],
            'thumbnail' => $body['thumbnail'],
            'user_id' => $userId,
            'description' => $body['description'],
            'content' => $body['content'],
            'create_at' => date('Y-m-d H:i:s'),
        ];
        $addStatus = addData('blogs', $dataAdd);
        if ($addStatus) {
            setFlashData('msg', 'Thêm blog mới thành công.');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
        redirect('admin?modules=blogs');
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors); // khi load lại trang thì không còn lưu $errors này nữa, do đó cần lưu lại (session).
        setFlashData('old', $body);
        redirect('admin?modules=blogs&action=add');
    }
}
// Truy vấn lất tất cả danh mục để hiển thị option
$listAllCate = getRaw("SELECT * FROM blog_categories ORDER BY name");
// echo '<pre>';
// print_r($listAllCate);
// echo '</pre>';
// exit;
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
                <label for="">Tiêu đề blog</label>
                <input type="text" class="form-control module_title" name="title" placeholder="Nhập tên blog..." value="<?php echo old('title', $old);?>">
                <?php echo  form_error('title', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Đường dẫn tĩnh</label>
                <input type="text" class="form-control module_slug" name="slug" placeholder="Nhập đường dẫn tĩnh..." value="<?php echo old('slug', $old);?>">
                <?php echo  form_error('slug', $errors, '<span class="error">', '</span>'); ?>
                <p class="render-link"><b>Link</b>: <span></span></p>
            </div>

            <div class="form-group">
                <label for="">Danh mục</label>
                <select name="blog_category_id" id="" class="form-control">
                    <option value="0">Chọn danh mục</option>
                    <?php if(!empty($listAllCate)): foreach($listAllCate as $item): ?>
                        <option value="<?php echo $item['id']; ?>" <?php echo (old('category_id', $old) == $item['id']) ? 'selected' : false; ?>><?php echo $item['name']; ?></option>
                    <?php endforeach; endif; ?>
                </select>
                <?php echo  form_error('blog_category_id', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Ảnh đại diện</label>
                <div class="row ckfinder-group">
                    <div class="col-10">
                        <input type="text" class="form-control image-render" name="thumbnail" placeholder="Đường dẫn ảnh..." value="<?php echo old('thumbnail', $old);?>">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-success btn-block choose-image">Chọn ảnh</button>
                    </div>
                </div>
                <?php echo  form_error('thumbnail', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Mô tả</label>
                <textarea name="description" placeholder="Nhập mô tả ngắn..." class="form-control editor"><?php echo old('description', $old);?></textarea>
                <?php echo  form_error('description', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Nội dung</label>
                <textarea name="content" class="form-control editor"><?php echo old('content', $old);?></textarea>
                <?php echo  form_error('content', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="<?php echo getLinkAdmin('blogs'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div>
</section>

<?php
layout('footer', 'admin', $data);
