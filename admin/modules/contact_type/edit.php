<?php 
// Lấy dl qua method GET
$body = getBody('GET');
if (!empty($body['id'])) {
    $contactTypeId = $body['id'];
    $contactTypeDetail = firstRaw("SELECT * FROM contact_type WHERE id=$contactTypeId");
    if (!empty($contactTypeDetail)) {
        // Gán userDetail vào flashData
        setFlashData('contactTypeDetail', $contactTypeDetail);
    } else {
        redirect('admin?modules=contact_type');
    }
} else {
    redirect('admin?modules=contact_type');
}
if(isPost()){
    $body = getBody();
  
    $errors = [];
    // validate fullname: không được trống, >= 5 kí tự
    if(empty(trim($body['name']))){
        $errors['name']['required'] = 'Tên danh mục không được để trống';
    }
   
    if(empty($errors)){
        // Lưu thông tin vào DB
        // $activeToken = sha1(uniqid().time());
        $dataEdit = [
            'name' => $body['name'],
            'update_at' => date('Y-m-d H:i:s'),
        ];
        $condition = 'id = '.$contactTypeId;
        $editStatus = editData('contact_type', $dataEdit, $condition);
        if($editStatus){
            setFlashData('msg', 'Chỉnh sửa phòng ban thành công.');
            setFlashData('msg_type', 'success');
        }else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
        redirect('admin?modules=contact_type');
    }else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors); // khi load lại trang thì không còn lưu $errors này nữa, do đó cần lưu lại (session).
        setFlashData('old', $body);
        redirect('admin?modules=contact_type&id='.$contactTypeId.'&view='.$view); 
    }
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('contactTypeDetail');
// echo '<pre>';
// print_r($oldData);
// echo '</pre>';
// exit;
$old = getFlashData('old');
if(empty($old) && !empty($oldData)){
    $old = $oldData;
}
?>

<h4>Cập nhật phòng ban</h4>
<hr>
<form action="" method="POST">
    <div class="form-group">
        <!-- <label for="">Tên danh mục</label> -->
        <input type="text" name="name" class="form-control" placeholder="Tên phòng ban..." value="<?php echo old('name', $old);?>">
        <?php echo  form_error('name', $errors, '<span class="error">', '</span>');?>
    </div>
    
    <button type="submit" class="btn btn-primary">Cập nhật</button>
    <a href="<?php echo getLinkAdmin('contact_type'); ?>" class="btn btn-success">Quay lại</a>
</form>