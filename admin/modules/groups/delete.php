<?php 
if(!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$body = getBody();

if(!empty($body['id'])){
    $groupId = $body['id'];
    $groupInfor = firstRaw("SELECT * FROM groups WHERE id=$groupId"); // Tìm thấy trả về 1, không thì 0
      
    if(!empty($groupInfor)){
       // Kiểm tra trong group này có user nào không (Nếu có không xóa và hiển thị thông báo)
       $checkUserRow = getRow("SELECT id FROM users WHERE group_id=$groupId");
       if($checkUserRow){
            setFlashData('msg', 'Nhóm '.$groupInfor['name'].' vẫn còn '.$checkUserRow.' người dùng, không thể xóa.');
            setFlashData('msg_type', 'danger');
       }else{
            $deleteGroup = deleteData('groups', "id=$groupId");
            if($deleteGroup){
                setFlashData('msg', 'Xóa nhóm thành công.');
                setFlashData('msg_type', 'success');
            }else{
                setFlashData('msg', 'Không tồn tại nhóm này trong hệ thống.');
                setFlashData('msg_type', 'danger');
            }
       }
    }else {
        setFlashData('msg', 'Không tồn tại nhóm này trong hệ thống.');
        setFlashData('msg_type', 'danger');
    }
}else {
    setFlashData('msg', 'Liên kết không tồn tại');
    setFlashData('msg_type', 'danger');
}
redirect('admin?modules=groups'); // Khi chuyển qua đây thì sẽ nhận được flashData đó
