<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp
$data = [
    'pageTitle' => 'Thêm dự án'
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
    if (empty(trim($body['name']))) {
        $errors['name']['required'] = 'Tên dự án không được để trống';
    }
    if (empty(trim($body['slug']))) {
        $errors['slug']['required'] = 'Đường dẫn tĩnh không được để trống';
    }
    if (empty(trim($body['video']))) {
        $errors['video']['required'] = 'Link video không được để trống';
    }
    if (empty(trim($body['portfolio_category_id']))) {
        $errors['portfolio_category_id']['required'] = 'Danh mục dự án không được để trống';
    }
    if (empty(trim($body['thumbnail']))) {
        $errors['thumbnail']['required'] = 'Ảnh đại diện không được để trống';
    }
    if (empty(trim($body['content']))) {
        $errors['content']['required'] = 'Nội dung không được để trống';
    }
    // Validate ảnh thêm vào dự án: Khi bấm add thì trong input phải nhập
    if (!empty($body['gallery'])) {
        $galleryArr = $body['gallery'];
        foreach ($galleryArr as $key => $item) {
            if (empty(trim($item))) {
                $errors['gallery']['required'][$key] = 'Vui lòng chọn ảnh';
            }
        }
    }

    if (empty($errors)) {
        // Lưu thông tin vào DB
        // $activeToken = sha1(uniqid().time());
        $dataAdd = [
            'name' => $body['name'],
            'slug' => $body['slug'],
            'video' => $body['video'],
            'portfolio_category_id' => $body['portfolio_category_id'],
            'thumbnail' => $body['thumbnail'],
            'description' => $body['description'],
            'content' => $body['content'],
            'user_id' => $userId,
            'create_at' => date('Y-m-d H:i:s'),
        ];
        $addStatus = addData('portfolios', $dataAdd);

        if ($addStatus) {
            $currentId = insertId(); // lấy id vừa add vào 
            if(!empty($galleryArr)){
                foreach($galleryArr as $item){
                    $dataImgAdd = [
                        'portfolio_id' => $currentId,
                        'image' => $item,
                        'create_at' => date('Y-m-d H:i:s')
                    ];
                    // Thêm từng ảnh vào bảng portfolios_images kèm id dự án và create_at
                    addData('portfolios_images', $dataImgAdd);
                }
                
            }
            setFlashData('msg', 'Thêm dự án mới thành công.');
            setFlashData('msg_type', 'success');
        } else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
        redirect('admin?modules=portfolios');
    } else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors); // khi load lại trang thì không còn lưu $errors này nữa, do đó cần lưu lại (session).
        setFlashData('old', $body);
        redirect('admin?modules=portfolios&action=add');
    }
}
// Truy vấn lất tất cả danh mục để hiển thị option
$listAllCate = getRaw("SELECT * FROM portfolio_categories ORDER BY name");
// echo '<pre>';
// print_r($listAllCate);
// echo '</pre>';
// exit;
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
// echo '<pre>';
// print_r($old);
// echo '</pre>';
// exit;
?>
<section class="content">
    <div class="container-fluid">
        <?php getMsg($msg, $msgType); ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="">Tên dự án</label>
                <input type="text" class="form-control module_title" name="name" placeholder="Nhập tên dự án..." value="<?php echo old('name', $old); ?>">
                <?php echo  form_error('name', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Đường dẫn tĩnh</label>
                <input type="text" class="form-control module_slug" name="slug" placeholder="Nhập đường dẫn tĩnh..." value="<?php echo old('slug', $old); ?>">
                <?php echo  form_error('slug', $errors, '<span class="error">', '</span>'); ?>
                <p class="render-link"><b>Link</b>: <span></span></p>
            </div>
            <div class="form-group">
                <label for="">Link video</label>
                <input type="text" class="form-control" name="video" placeholder="Link youtube..." value="<?php echo old('video', $old); ?>">
                <?php echo  form_error('video', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Danh mục</label>
                <select name="portfolio_category_id" id="" class="form-control">
                    <option value="0">Chọn danh mục</option>
                    <?php if (!empty($listAllCate)) : foreach ($listAllCate as $item) : ?>
                            <option value="<?php echo $item['id']; ?>" <?php echo (old('portfolio_category_id', $old) == $item['id']) ? 'selected' : false; ?>><?php echo $item['name']; ?></option>
                    <?php endforeach;
                    endif; ?>
                </select>
                <?php echo  form_error('portfolio_category_id', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Ảnh đại diện</label>
                <div class="row ckfinder-group">
                    <div class="col-10">
                        <input type="text" class="form-control image-render" name="thumbnail" placeholder="Đường dẫn ảnh..." value="<?php echo old('thumbnail', $old); ?>">
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-success btn-block choose-image">Chọn ảnh</button>
                    </div>
                </div>
                <?php echo  form_error('thumbnail', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Ảnh dự án</label>
                <div class="gallery-image">
                    <!-- Begin: form chứa link ảnh, chọn ảnh, xóa ảnh mỗi item -->

                    <!-- End: form chứa link ảnh, chọn ảnh, xóa ảnh mỗi item-->
                    <!-- Nếu có lỗi ở gallery thì cần hiển thị form để hiển thị lỗi sau: -->
                    <?php
                    if (!empty(old('gallery', $old))) {
                        $oldGallery = old('gallery', $old);
                        $errorGallery = $errors['gallery'];
                        foreach ($oldGallery as $key => $item) {    
                    ?>
                            <div class="gallery-item">
                                <div class="row">
                                    <div class="col-11">
                                        <div class="row ckfinder-group">
                                            <div class="col-10">
                                                <input type="text" class="form-control image-render" name="gallery[]" placeholder="Đường dẫn ảnh..." value="<?php echo !empty($item)?$item:false; ?>">
                                                
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-success btn-block choose-image">Chọn ảnh</button>
                                            </div>
                                        </div>
                                        <?php echo !empty($errorGallery['required'][$key]) ? '<span class="error">'.$errorGallery['required'][$key].'</span>' : false; ?>
                                    </div>
                                    
                                    <div class="col-1">
                                        <button type="button" class="remove btn btn-danger btn-block"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                    <?php }
                    } ?>
                </div>
                <p style="margin-top: 10px">
                    <button type="button" class="add-gallery btn btn-sm btn-primary">Thêm ảnh</button>
                </p>
            </div>
            <div class="form-group">
                <label for="">Mô tả</label>
                <textarea name="description" placeholder="Nhập mô tả ngắn..." class="form-control editor"><?php echo old('description', $old); ?></textarea>
                <?php echo  form_error('description', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <div class="form-group">
                <label for="">Nội dung</label>
                <textarea name="content" class="form-control editor"><?php echo old('content', $old); ?></textarea>
                <?php echo  form_error('content', $errors, '<span class="error">', '</span>'); ?>
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="<?php echo getLinkAdmin('portfolios'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div>
</section>

<?php
layout('footer', 'admin', $data);
