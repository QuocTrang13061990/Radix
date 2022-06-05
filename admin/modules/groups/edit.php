<?php
if(!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$data = [
    'pageTitle' => 'Chỉnh sửa nhóm người dùng'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);
// Lấy dl qua method GET
$body = getBody('GET');
if (!empty($body['id'])) {
    $groupId = $body['id'];
    $groupDetail = firstRaw("SELECT * FROM groups WHERE id = $groupId");
    if (!empty($groupDetail)) {
        // Gán userDetail vào flashData
        setFlashData('groupDetail', $groupDetail);
    } else {
        redirect('admin?modules=groups');
    }
} else {
    redirect('admin?modules=groups');
}
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
        $dataEdit = [
            'name' => $body['name'],
            'update_at' => date('Y-m-d H:i:s'),
        ];
        $condition = 'id = '.$groupId;
        $editStatus = editData('groups', $dataEdit, $condition);
        if($editStatus){
            setFlashData('msg', 'Chỉnh sửa nhóm thành công.');
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
        setFlashData('old', $body);
        redirect('admin?modules=groups&action=edit&id='.$groupId); 
    }
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('groupDetail');
// echo '<pre>';
// print_r($oldData);
// echo '</pre>';
// exit;
$old = getFlashData('old');
if(empty($old) && !empty($oldData)){
    $old = $oldData;
}
?>
<section class="content">
    <div class="container-fluid">
        <?php getMsg($msg, $msgType); ?>
        <form action="" method="POST">
            <div class="form-group">
                <label for="">Tên nhóm</label>
                <input type="text" class="form-control" name="name" placeholder="Nhập tên nhóm..." value="<?php echo old('name', $old); ?>">
                <?php echo  form_error('name', $errors, '<span class="error">', '</span>');?>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="<?php echo getLinkAdmin('groups'); ?>" class="btn btn-success">Quay lại</a>
        </form>
    </div>
</section>

<?php
layout('footer', 'admin', $data);