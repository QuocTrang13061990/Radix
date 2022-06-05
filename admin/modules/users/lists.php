<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$data = [
    'pageTitle' => 'Quản lý người dùng'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);
// Lấy userId của user đang login
$userId = isLogin()['user_id'];
// Xử lý lọc dữ liệu
$filter = '';
if (isGet()) {
    $body = getBody();
    if (!empty($body['status'])) { // !empty là không có số 0 nhé, như vậy trường hợp status = 0 là không được
        $status = $body['status'];
        $filter .= ($status == 2) ? "WHERE status = 0" : "WHERE status = 1";
    }
    if (!empty($body['keyword'])) {
        $keyword = $body['keyword'];
        $filter .= (!empty($filter) && strpos($filter, 'WHERE') >= 0) ? " AND fullname LIKE '%$keyword%'" : " WHERE fullname LIKE '%$keyword%'";
    }
    if(!empty($body['group_id'])){
        $groupId = $body['group_id'];
        $filter .= (!empty($filter) && strpos($filter, 'WHERE') >=0) ? " AND group_id = $groupId" : " WHERE group_id = $groupId";
    }
}

// Xử lý phân trang
$allUserNum = getRow("SELECT id FROM users $filter");
$perPage = _PER_PAGE; // số bản ghi trong 1 page
$maxPage = ceil($allUserNum / $perPage); // tổng số page
if (!empty(getBody()['page'])) {
    $page = getBody()['page'];
    if ($page < 1 || $page > $maxPage) $page = 1;
} else {
    $page = 1;
}
$offset = ($page - 1) * $perPage;
// Truy vấn lấy tất cả người dùng ở bảng users (Có lấy thêm tên groups ở bảng groups)
$listAllUser = getRaw("SELECT users.id, fullname, email, status, users.create_at, groups.name as group_name FROM users INNER JOIN groups ON users.group_id = groups.id $filter ORDER BY users.create_at DESC LIMIT $offset, $perPage");
// echo '<pre>';
// print_r($listAllUser);
// echo '</pre>';
$queryString = '';
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = (strpos($queryString, 'modules=users') !== false) ? str_replace('modules=users', '', $queryString) : $queryString;
    if (!empty($body['page']) && strpos($queryString, 'page=' . $body['page']) !== false) {
        $queryString = str_replace('page=' . $body['page'], '', $queryString);
    }
    $queryString = trim($queryString, '&');
    $queryString = '&' . $queryString;
}
// Truy vấn tất cả group để hiển thị trong select, option groups
$listAllGroup = getRaw("SELECT id, name FROM groups ORDER BY name");
// echo '<pre>';
// print_r($listAllGroup);
// echo '</pre>';

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <a href="<?php echo getLinkAdmin('users', 'add'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Thêm người dùng mới</a>
        <hr>
        <form action="">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <select name="status" class="form-control">
                            <option value="0">Chọn trạng thái</option>
                            <option value="1" <?php echo !empty($status) && $status == 1 ? 'selected' : false; ?>>Kích hoạt</option>
                            <option value="2" <?php echo !empty($status) && $status == 2 ? 'selected' : false; ?>>Chưa kích hoạt</option>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <input type="search" class="form-control" placeholder="Từ khóa tìm kiếm..." name="keyword" value="<?php echo (!empty($keyword)) ? $keyword : false; ?>">
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <select name="group_id" id="" class="form-control">
                            <option value="0">Chọn nhóm</option>
                            <?php if(!empty($listAllGroup)): foreach($listAllGroup as $group):?>
                                <option value="<?php echo $group['id'] ;?>" <?php echo !empty($groupId) && $groupId == $group['id'] ? 'selected' : false;?>><?php echo $group['name'] ;?></option>
                            <?php endforeach; endif;?>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary btn-block">Tìm kiếm</button>
                </div>
                <input type="hidden" name="modules" value="users">
            </div>
        </form>
        <hr>
        <?php getMsg($msg, $msgType); ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">STT</th>
                    <th class="text-center">Họ và tên</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Nhóm</th>
                    <th class="text-center">Ngày tạo</th>
                    <th class="text-center">Trạng thái</th>
                    <th class="text-center" width="7%">Sửa</th>
                    <th class="text-center" width="7%">Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($listAllUser)) :
                    $count = 0;
                    foreach ($listAllUser as $user) :
                        $count++;
                ?>
                        <tr>
                            <td class="text-center"><?php echo $offset + $count; ?></td>
                            <td><a href="<?php echo getLinkAdmin('users', 'edit', ['id' => $user['id']]); ?>"><?php echo $user['fullname']; ?></a></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['group_name']; ?></td>
                            <td><?php echo !empty($user['create_at']) ? getDateFormat($user['create_at'], 'd/m/Y H:i:s') : false; ?></td>
                            <td class="text-center">
                                <?php
                                echo ($user['status'] == 1) ? '<button type="button" class="btn btn-success btn-sm">Kích hoạt</button>' : '<button type="button" class="btn btn-warning btn-sm">Chưa kích hoạt</button>';
                                ?>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo getLinkAdmin('users', 'edit', ['id' => $user['id']]); ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                            </td>
                            <td class="text-center">
                                <?php if($user['id'] !== $userId): ;?>
                                <a href="<?php echo getLinkAdmin('users', 'delete', ['id' => $user['id']]); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa fa-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach;
                else : ?>
                    <tr>
                        <td class="text-center" colspan="8">
                            <div class="alert alert-danger text-center">Không có người dùng</div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example"  class="d-flex justify-content-end">
            <ul class="pagination pagination-sm">
                <?php
                if ($page > 1) {
                    $prevPage = $page - 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?modules=users' . $queryString . '&page=' . $prevPage . '">Trang trước</a></li>';
                }
                ?>
                <?php
                $begin = $page - 2;
                $end = $page + 2;
                if ($begin < 1) $begin = 1;
                if ($end > $maxPage) $end = $maxPage;
                for ($i = $begin; $i <= $end; $i++) { ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : false; ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?modules=users' . $queryString . '&page=' . $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
                <?php
                if ($page < $maxPage) {
                    $nextPage = $page + 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?modules=users' . $queryString . '&page=' . $nextPage . '">Trang sau</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?php
layout('footer', 'admin', $data);
