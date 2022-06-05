<?php
if(!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$data = [
    'pageTitle' => 'Danh sách dịch vụ'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);
// Xử lý lọc dữ liệu tìm kiếm
$filter = '';
if (isGet()) {
    $body = getBody();
    if (!empty($body['keyword'])) {
        $keyword = $body['keyword'];
        $filter = "WHERE name LIKE '%$keyword%'";
    }
    if(!empty($body['user_id'])){
        $userId = $body['user_id'];
        $filter .= (!empty($filter) && strpos($filter, 'WHERE') >=0) ? " AND user_id = $userId" : " WHERE user_id = $userId";
    }
}
// Xử lý phân trang
// 1. Lấy số lượng bản ghi với đk lọc
$allServicesNum = getRow("SELECT id FROM services $filter");
// 2. Số bản ghi trong 1 page
$perPage = _PER_PAGE;
// 3. Tổng số trang tìm được
$maxPage = ceil($allServicesNum / $perPage);
// 4. Xử lý số trang dựa vào phương thức GET
if (!empty(getBody()['page'])) {
    $page = getBody()['page'];
    if ($page < 1 || $page > $maxPage) $page = 1;
} else {
    $page = 1;
}
$offset = ($page - 1) * $perPage;

$queryString = '';
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = (strpos($queryString, 'modules=services') !== false) ? str_replace('modules=services', '', $queryString) : $queryString;
    if (!empty($body['page']) && strpos($queryString, 'page=' . $body['page']) !== false) {
        $queryString = str_replace('page=' . $body['page'], '', $queryString);
    }
    $queryString = trim($queryString, '&');
    $queryString = '&' . $queryString;
}
// echo $queryString;
// Truy vấn lấy tất cả bản ghi
$listServices = getRaw("SELECT services.id, icon, name, services.create_at, users.fullname as user_name, users.id as user_id FROM services INNER JOIN users ON services.user_id = users.id $filter ORDER BY services.create_at DESC LIMIT $offset, $perPage");

// Lấy ds users => hiển thị trong select người đăng
$listAllUsers = getRaw("SELECT id, fullname FROM users ORDER BY fullname DESC");

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');



?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <a href="<?php echo getLinkAdmin('services', 'add'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Thêm dịch vụ mới</a>
        <hr>
        <form action="" method="GET">
            <div class="row">
                <div class="col-5">
                    <input type="search" class="form-control" name="keyword" placeholder="Nhập tên dịch vụ cần tìm ..." value="<?php echo !empty($keyword) ? $keyword : false; ?>">
                </div>
                <div class="col-5">
                    <select class="form-control" name="user_id" id="">
                        <option value="0">Chọn người đăng</option>
                        <?php if(!empty($listAllUsers)): foreach($listAllUsers as $user): ?>
                            <option value="<?php echo $user['id'] ;?>" <?php echo (!empty($userId) && $userId == $user['id']) ? "selected" : false ;?>><?php echo $user['fullname'] ;?></option>
                        <?php endforeach; endif;?>
                    </select>
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary btn-block">Tìm kiếm</button>
                </div>
            </div>
            <!-- Thêm thẻ input như sau để khi submit thì không chuyển về trang chủ -->
            <input type="hidden" name="modules" value="services">
        </form>
        <hr>
        <?php getMsg($msg, $msgType); ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">STT</th>
                    <th class="text-center">Ảnh</th>
                    <th class="text-center">Tên dịch vụ</th>
                    <th class="text-center">Đăng bởi</th>
                    <th class="text-center">Ngày tạo</th>
                    <th class="text-center" width="7%">Xem</th>
                    <th class="text-center" width="7%">Sửa</th>
                    <th class="text-center" width="7%">Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($listServices)) :
                    $count = 0;
                    foreach ($listServices as $k => $service) :
                        $count++;
                ?>
                        <tr>
                            <td><?php echo $offset + $count; ?></td>
                            <td class="text-center">
                                <?php echo (isFontIcon($service['icon'])) ? $service['icon'] : '<img src="'.$service['icon'].'" width=80>' ;?>
                            </td>
                            <td class="service-name">   
                                <a href="<?php echo getLinkAdmin('services', 'edit', ['id' => $service['id']]); ?>">
                                    <?php echo $service['name']; ?>
                                </a>
                                <a href="<?php echo getLinkAdmin('services', 'duplicate', ['id' => $service['id']]); ?>" class="btn btn-danger btn-sm" style="padding: 0 1px;">
                                    Nhân bản
                                </a>
                            </td>
                            <td><a href="?<?php echo getLinkQueryString('user_id', $service['user_id']) ;?>"><?php echo $service['user_name'] ;?></a></td>
                            <td><?php echo getDateFormat($service['create_at'], 'd/m/Y H:i:s'); ?></td>
                            <td class="text-center"><a href="#" class="btn btn-primary">Xem</a></td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('services', 'edit', ['id' => $service['id']]); ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a></td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('services', 'delete', ['id' => $service['id']]); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa fa-trash"></i></a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" class="alert alert-danger text-center">Không có dịch vụ này</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example" class="d-flex justify-content-end">
            <ul class="pagination pagination-sm">
                <?php
                if ($page > 1) {
                    $prevPage = $page - 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?modules=services' . $queryString . '&page=' . $prevPage . '">Trang trước</a></li>';
                }
                ?>
                <?php
                $begin = $page - 2;
                $end = $page + 2;
                if ($begin < 1) $begin = 1;
                if ($end > $maxPage) $end = $maxPage;
                for ($i = $begin; $i <= $end; $i++) { ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : false; ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?modules=services' . $queryString . '&page=' . $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
                <?php
                if ($page < $maxPage) {
                    $nextPage = $page + 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?modules=services' . $queryString . '&page=' . $nextPage . '">Trang sau</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin', $data);
