<?php
if(!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$data = [
    'pageTitle' => 'Danh sách nhóm'
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
}
// Xử lý phân trang
// 1. Lấy số lượng bản ghi nhóm người dùng với đk lọc
$allUserNum = getRow("SELECT id FROM groups $filter");
// 2. Số bản ghi trong 1 page
$perPage = _PER_PAGE;
// 3. Tổng số trang tìm được
$maxPage = ceil($allUserNum / $perPage);
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
    $queryString = (strpos($queryString, 'modules=groups') !== false) ? str_replace('modules=groups', '', $queryString) : $queryString;
    if (!empty($body['page']) && strpos($queryString, 'page=' . $body['page']) !== false) {
        $queryString = str_replace('page=' . $body['page'], '', $queryString);
    }
    $queryString = trim($queryString, '&');
    $queryString = '&' . $queryString;
}

// Truy vấn lấy tất cả bản ghi
$listGroups = getRaw("SELECT * FROM groups $filter ORDER BY create_at DESC LIMIT $offset, $perPage");
// echo '<pre>';
// print_r($listGroups);
// echo '</pre>'
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <a href="<?php echo getLinkAdmin('groups', 'add'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Thêm nhóm mới</a>
        <hr>
        <form action="" method="GET">
            <div class="row">
                <div class="col-9">
                    <input type="search" class="form-control" name="keyword" placeholder="Nhập tên nhóm..." value="<?php echo !empty($keyword) ? $keyword : false; ?>">
                </div>
                <div class="col-3">
                    <button type="submit" class="btn btn-primary btn-block">Tìm kiếm</button>
                </div>
            </div>
            <!-- Thêm thẻ input như sau để khi submit thì không chuyển về trang chủ -->
            <input type="hidden" name="modules" value="groups">
        </form>
        <hr>
        <?php getMsg($msg, $msgType); ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">STT</th>
                    <th class="text-center">Tên nhóm</th>
                    <th class="text-center">Thời gian</th>
                    <th class="text-center" width="15%">Phân quyền</th>
                    <th class="text-center" width="7%">Sửa</th>
                    <th class="text-center" width="7%">Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($listGroups)) :
                    $count = 0;
                    foreach ($listGroups as $k => $group) :
                        $count++;
                ?>
                        <tr>
                            <td><?php echo $offset + $count; ?></td>
                            <td><a href="<?php echo getLinkAdmin('groups', 'edit', ['id' => $group['id']]); ?>"><?php echo $group['name']; ?></a></td>
                            <td><?php echo getDateFormat($group['create_at'], 'd/m/Y H:i:s'); ?></td>
                            <td class="text-center"><a href="#" class="btn btn-primary">Phân quyền</a></td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('groups', 'edit', ['id' => $group['id']]); ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Sửa</a></td>
                            <td class="text-center"><a href="<?php echo getLinkAdmin('groups', 'delete', ['id' => $group['id']]); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa fa-trash"></i> Xóa</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="alert alert-danger text-center">Không có nhóm người dùng</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example" class="d-flex justify-content-end">
            <ul class="pagination pagination-sm">
                <?php
                if ($page > 1) {
                    $prevPage = $page - 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?modules=groups' . $queryString . '&page=' . $prevPage . '">Trang trước</a></li>';
                }
                ?>
                <?php
                $begin = $page - 2;
                $end = $page + 2;
                if ($begin < 1) $begin = 1;
                if ($end > $maxPage) $end = $maxPage;
                for ($i = $begin; $i <= $end; $i++) { ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : false; ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?modules=groups' . $queryString . '&page=' . $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
                <?php
                if ($page < $maxPage) {
                    $nextPage = $page + 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?modules=groups' . $queryString . '&page=' . $nextPage . '">Trang sau</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin', $data);
