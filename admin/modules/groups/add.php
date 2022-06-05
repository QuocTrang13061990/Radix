<?php
if(!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp
$data = [
    'pageTitle' => 'Thêm nhóm người dùng'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

if(isPost()){
    $body = getBody();
    $errors = [];
    // validate fullname: không được trống, >= 5 kí tự
    if(empty(trim($body['name']))){
        $errors['name']['required'] = 'Tên nhóm không được để trống';
    }else {
        if(strlen(trim($body['name'])) < 4){
            $errors['name']['min'] = 'Tên nhóm không ít hơn 4 ký tự';
        }
    }
    if(empty($errors)){
        // Lưu thông tin vào DB
        // $activeToken = sha1(uniqid().time());
        $dataAdd = [
            'name' => $body['name'],
            'create_at' => date('Y-m-d H:i:s'),
        ];
        $addStatus = addData('groups', $dataAdd);
        if($addStatus){
            setFlashData('msg', 'Thêm nhóm mới thành công.');
            setFlashData('msg_type', 'success');
        }else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
        redirect('admin?modules=groups');
    }else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors); // khi load lại trang thì không còn lưu $errors này nữa, do đó cần lưu lại (session).
        // setFlashData('old', $body);
        redirect('admin?modules=groups&action=add'); 
    }
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
// $old = getFlashData('old');
?>
<section class="content">
    <div class="container-fluid">
        <?php getMsg($msg, $msgType); ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="">Tên nhóm</label>
                <input type="text" class="form-control" name="name" placeholder="Nhập tên nhóm...">
                <?php echo  form_error('name', $errors, '<span class="error">', '</span>');?>
            </div>
            <button type="submit" class="btn btn-primary">Thêm</button>
            <a href="<?php echo getLinkAdmin('groups'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div>
</section>

<?php
layout('footer', 'admin', $data);