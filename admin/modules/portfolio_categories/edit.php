<?php 
// Lấy dl qua method GET
$body = getBody('GET');
if (!empty($body['id'])) {
    $portfolioCategorieId = $body['id'];
    $portfolioCategorieDetail = firstRaw("SELECT * FROM portfolio_categories WHERE id=$portfolioCategorieId");
    if (!empty($portfolioCategorieDetail)) {
        // Gán userDetail vào flashData
        setFlashData('portfolioCategorieDetail', $portfolioCategorieDetail);
    } else {
        redirect('admin?modules=portfolio_categories');
    }
} else {
    redirect('admin?modules=portfolio_categories');
}
if(isPost()){
    $body = getBody();
  
    $errors = [];
    // validate fullname: không được trống, >= 5 kí tự
    if(empty(trim($body['name']))){
        $errors['name']['required'] = 'Tên danh mục không được để trống';
    }else {
        if(strlen(trim($body['name'])) < 4){
            $errors['name']['min'] = 'Tên danh mục không ít hơn 4 ký tự';
        }
    }
    if(empty($errors)){
        // Lưu thông tin vào DB
        // $activeToken = sha1(uniqid().time());
        $dataEdit = [
            'name' => $body['name'],
            'update_at' => date('Y-m-d H:i:s'),
        ];
        $condition = 'id = '.$portfolioCategorieId;
        $editStatus = editData('portfolio_categories', $dataEdit, $condition);
        if($editStatus){
            setFlashData('msg', 'Chỉnh sửa danh mục dự án thành công.');
            setFlashData('msg_type', 'success');
        }else {
            setFlashData('msg', 'Hệ thống đang gặp sự cố, vui lòng thử lại sau');
            setFlashData('msg_type', 'danger');
        }
        redirect('admin?modules=portfolio_categories');
    }else {
        setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
        setFlashData('msg_type', 'danger');
        setFlashData('errors', $errors); // khi load lại trang thì không còn lưu $errors này nữa, do đó cần lưu lại (session).
        setFlashData('old', $body);
        redirect('admin?modules=portfolio_categories&id='.$portfolioCategorieId.'&view='.$view); 
    }
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$oldData = getFlashData('portfolioCategorieDetail');
// echo '<pre>';
// print_r($oldData);
// echo '</pre>';
// exit;
$old = getFlashData('old');
if(empty($old) && !empty($oldData)){
    $old = $oldData;
}
?>

<h4>Cập nhật danh mục</h4>
<hr>
<form action="" method="POST">
    <div class="form-group">
        <!-- <label for="">Tên danh mục</label> -->
        <input type="text" name="name" class="form-control" placeholder="Tên danh mục dự án..." value="<?php echo old('name', $old);?>">
        <?php echo  form_error('name', $errors, '<span class="error">', '</span>');?>
    </div>
    <button type="submit" class="btn btn-primary">Cập nhật</button>
    <a href="<?php echo getLinkAdmin('portfolio_categories'); ?>" class="btn btn-success">Quay lại</a>
</form>