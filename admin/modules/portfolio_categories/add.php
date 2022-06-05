<?php 
$userId = isLogin()['user_id'];
if(isPost()){
    $body = getBody();
    // echo '<pre>';
    // print_r($body);
    // echo '</pre>';
    // exit;
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
        $dataAdd = [
            'name' => $body['name'],
            'user_id' => $userId,
            'create_at' => date('Y-m-d H:i:s'),
        ];
        $addStatus = addData('portfolio_categories', $dataAdd);
        if($addStatus){
            setFlashData('msg', 'Thêm danh mục mới thành công.');
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
        // setFlashData('old', $body);
        redirect('admin?modules=portfolio_categories'); 
    }
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

?>

<h4>Thêm danh mục</h4>
<hr>
<form action="" method="POST">
    <div class="form-group">
        <!-- <label for="">Tên danh mục</label> -->
        <input type="text" name="name" class="form-control" placeholder="Tên danh mục dự án..." value="<?php echo old('name', $old);?>">
        <?php echo  form_error('name', $errors, '<span class="error">', '</span>');?>
    </div>
    <button type="submit" class="btn btn-primary">Thêm mới</button>
</form>