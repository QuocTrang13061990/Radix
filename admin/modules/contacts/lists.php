<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$data = [
    'pageTitle' => 'Danh sách liên hệ'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);


// Xử lý lọc dữ liệu
$filter = '';
if (isGet()) {
    $body = getBody();
    if (!empty($body['status'])) {
        $status = $body['status'];
        $filter .= ($status == 2) ? "WHERE status = 0" : "WHERE status = 1";
    }
    if (!empty($body['keyword'])) {
        $keyword = $body['keyword'];
        // $filter .= (!empty($filter) && strpos($filter, 'WHERE') >= 0) ? " AND (fullname LIKE '%$keyword%')" : " WHERE blogs.title LIKE '%$keyword%'";
        $filter .= (!empty($filter) && strpos($filter, 'WHERE') >= 0) ? " AND (message LIKE '%$keyword%' OR fullname LIKE '%keyword%' OR email LIKE '%$keyword%')" : " WHERE (message LIKE '%$keyword%' OR fullname LIKE '%$keyword%' OR email LIKE '%$keyword%')";
    }
    if (!empty($body['type_id'])) {
        $typeId = $body['type_id'];
        $filter .= (!empty($filter) && strpos($filter, 'WHERE') >= 0) ? " AND contacts.type_id = $typeId" : " WHERE contacts.type_id = $typeId";
    }
}

// Xử lý phân trang
$allContactsNum = getRow("SELECT id FROM contacts $filter");
$perPage = _PER_PAGE; // số bản ghi trong 1 page
$maxPage = ceil($allContactsNum / $perPage); // tổng số page
if (!empty(getBody()['page'])) {
    $page = getBody()['page'];
    if ($page < 1 || $page > $maxPage) $page = 1;
} else {
    $page = 1;
}
$offset = ($page - 1) * $perPage;
$listAllContacts = getRaw("SELECT contacts.id, contacts.fullname, contacts.type_id, email, message, status, note, contacts.create_at, contact_type.name as contact_type_name FROM contacts INNER JOIN contact_type ON contacts.type_id = contact_type.id $filter ORDER BY contacts.create_at DESC LIMIT $offset, $perPage");

$queryString = '';
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = (strpos($queryString, 'modules=contacts') !== false) ? str_replace('modules=contacts', '', $queryString) : $queryString;
    if (!empty($body['page']) && strpos($queryString, 'page=' . $body['page']) !== false) {
        $queryString = str_replace('page=' . $body['page'], '', $queryString);
    }
    $queryString = trim($queryString, '&');
    $queryString = '&' . $queryString;
}
// Truy vấn tất cả cate để hiển thị trong select, option danh mục
$listAllContactType = getRaw("SELECT id, name FROM contact_type ORDER BY name");

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <form action="">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <select name="status" class="form-control">
                            <option value="0">Chọn trạng thái</option>
                            <option value="1" <?php echo !empty($status) && $status == 1 ? 'selected' : false; ?>>Đã xử lý</option>
                            <option value="2" <?php echo !empty($status) && $status == 2 ? 'selected' : false; ?>>Chưa xử lý</option>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <select name="type_id" id="" class="form-control">
                            <option value="0">Chọn phòng ban</option>
                            <?php if (!empty($listAllContactType)) : foreach ($listAllContactType as $v) : ?>
                                    <option value="<?php echo $v['id']; ?>" <?php echo !empty($typeId) && $typeId == $v['id'] ? 'selected' : false; ?>><?php echo $v['name']; ?></option>
                            <?php endforeach;
                            endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <input type="search" class="form-control" placeholder="Từ khóa tìm kiếm..." name="keyword" value="<?php echo (!empty($keyword)) ? $keyword : false; ?>">
                </div>
                <div class="col-2">
                    <button type="submit" class="btn btn-primary btn-block">Tìm kiếm</button>
                </div>
                <input type="hidden" name="modules" value="contacts">
            </div>
        </form>
        <hr>
        <?php getMsg($msg, $msgType); ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">STT</th>
                    <th class="text-center" width="20%">Thông tin</th>
                    <th class="text-center">Nội dung</th>
                    <th class="text-center" width="11%">Trạng thái</th>
                    <th class="text-center">Ghi chú</th>
                    <th class="text-center" width="10%">Thời gian</th>
                    <th class="text-center" width="7%">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($listAllContacts)) :
                    $count = 0;
                    foreach ($listAllContacts as $item) :
                        $count++;
                ?>
                        <tr>
                            <td class="text-center"><?php echo $offset + $count; ?></td>
                            <td>
                                Họ tên: <?php echo $item['fullname']; ?> <br>
                                Email: <?php echo $item['email']; ?> <br>
                                Phòng ban: <?php echo $item['contact_type_name']; ?>
                            </td>
                            <td>
                                <?php echo $item['message']; ?>
                            </td>

                            <td class="text-center">
                                <?php
                                echo ($item['status'] == 1) ? '<button type="button" class="btn btn-success btn-sm">Kích hoạt</button>' : '<button type="button" class="btn btn-warning btn-sm">Chưa kích hoạt</button>';
                                ?>
                            </td>

                            <td>
                                <?php echo $item['note']; ?>
                            </td>
                            <td>
                                <?php echo $item['create_at']; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo getLinkAdmin('contacts', 'edit', ['id' => $item['id']]); ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a> <br><br>
                                <a href="<?php echo getLinkAdmin('contacts', 'delete', ['id' => $item['id']]); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach;
                else : ?>
                    <tr>
                        <td class="text-center" colspan="8">
                            <div class="alert alert-danger text-center">Không có liên hệ</div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example" class="d-flex justify-content-end">
            <ul class="pagination pagination-sm">
                <?php
                if ($page > 1) {
                    $prevPage = $page - 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?modules=contacts' . $queryString . '&page=' . $prevPage . '">Trang trước</a></li>';
                }
                ?>
                <?php
                $begin = $page - 2;
                $end = $page + 2;
                if ($begin < 1) $begin = 1;
                if ($end > $maxPage) $end = $maxPage;
                for ($i = $begin; $i <= $end; $i++) { ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : false; ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?modules=contacts' . $queryString . '&page=' . $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
                <?php
                if ($page < $maxPage) {
                    $nextPage = $page + 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?modules=contacts' . $queryString . '&page=' . $nextPage . '">Trang sau</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?php
layout('footer', 'admin', $data);
