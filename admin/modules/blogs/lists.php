
<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$data = [
    'pageTitle' => 'Danh sách blog'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);
// Xử lý lọc dữ liệu
$filter = '';
if (isGet()) {
    $body = getBody();
    if (!empty($body['keyword'])) {
        $keyword = $body['keyword'];
        $filter .= (!empty($filter) && strpos($filter, 'WHERE') >= 0) ? " AND (fullname LIKE '%$keyword%')" : " WHERE blogs.title LIKE '%$keyword%'";
    }
    if (!empty($body['user_id'])) {
        $userId = $body['user_id'];
        $filter .= (!empty($filter) && strpos($filter, 'WHERE') >= 0) ? " AND (blogs.user_id = $userId)" : " WHERE blogs.user_id = $userId";
    }
    if (!empty($body['cate_id'])) {
        $cateId = $body['cate_id'];
        $filter .= (!empty($filter) && strpos($filter, 'WHERE') >= 0) ? " AND (category_id = $cateId)" : " WHERE category_id = $cateId";
    }
}
// Xử lý phân trang
$allBlogNum = getRow("SELECT id FROM blogs $filter");
$perPage = _PER_PAGE; // số bản ghi trong 1 page
$maxPage = ceil($allBlogNum / $perPage); // tổng số page
if (!empty(getBody()['page'])) {
    $page = getBody()['page'];
    if ($page < 1 || $page > $maxPage) $page = 1;
} else {
    $page = 1;
}
$offset = ($page - 1) * $perPage;
$listAllblogs = getRaw("SELECT blogs.id, blogs.title, blogs.create_at, blogs.view_count, category_id, blog_categories.name as blog_categories_name, users.id as user_id, users.fullname FROM blogs INNER JOIN blog_categories ON blog_categories.id = blogs.category_id INNER JOIN users ON users.id = blogs.user_id $filter ORDER BY blogs.create_at DESC LIMIT $offset, $perPage");
// echo '<pre>';
// print_r($listAllblogs);
// echo '</pre>';
$queryString = '';
if (!empty($_SERVER['QUERY_STRING'])) {
    $queryString = $_SERVER['QUERY_STRING'];
    $queryString = (strpos($queryString, 'modules=blogs') !== false) ? str_replace('modules=blogs', '', $queryString) : $queryString;
    if (!empty($body['page']) && strpos($queryString, 'page=' . $body['page']) !== false) {
        $queryString = str_replace('page=' . $body['page'], '', $queryString);
    }
    $queryString = trim($queryString, '&');
    $queryString = '&' . $queryString;
}
// Truy vấn tất cả cate để hiển thị trong select, option danh mục
$listAllCates = getRaw("SELECT id, name FROM blog_categories ORDER BY name");
// Truy vấn lấy cả users để hiển thị trong select, option Chọn người đăng
$listAllUsers = getRaw("SELECT id, fullname from users ORDER BY fullname DESC");
// echo '<pre>';
// print_r($listAllUsers);
// echo '</pre>';
// // exit;
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <a href="<?php echo getLinkAdmin('blogs', 'add'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Thêm blog mới</a>
        <hr>
        <form action="">
            <div class="row">
                <div class="col-3">
                    <div class="form-group">
                        <select class="form-control" name="user_id" id="">
                            <option value="0">Chọn người đăng</option>
                            <?php
                            if (!empty($listAllUsers)){
                                foreach ($listAllUsers as $item){
                                    ?>
                                    <option value="<?php echo $item['id']; ?>" <?php echo (!empty($userId) && $userId==$item['id'])?'selected':false; ?>><?php echo $item['fullname']; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <select name="cate_id" id="" class="form-control">
                            <option value="0">Chọn danh mục</option>
                            <?php if (!empty($listAllCates)) : foreach ($listAllCates as $v) : ?>
                                    <option value="<?php echo $v['id']; ?>" <?php echo !empty($cateId) && $cateId == $v['id'] ? 'selected' : false; ?>><?php echo $v['name']; ?></option>
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
                <input type="hidden" name="modules" value="blogs">
            </div>
        </form>
        <hr>
        <?php getMsg($msg, $msgType); ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center" width="5%">STT</th>
                    <th class="text-center">Tiêu đề</th>
                    <th class="text-center">Danh mục</th>
                    <th class="text-center">Đăng bởi</th>
                    <th class="text-center">Ngày tạo</th>
                    <th class="text-center" width="7%">Sửa</th>
                    <th class="text-center" width="7%">Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($listAllblogs)) :
                    $count = 0;
                    foreach ($listAllblogs as $item) :
                        $count++;
                ?>
                        <tr>
                            <td class="text-center"><?php echo $offset + $count; ?></td>
                            <td>
                                <a href="<?php echo getLinkAdmin('blogs', 'edit', ['id' => $item['id']]); ?>"><?php echo $item['title']; ?>
                                </a><br>
                                <a href="<?php echo getLinkAdmin('blogs', 'duplicate', ['id' => $item['id']]); ?>" class="btn btn-danger btn-sm" style="padding: 0 1px;">
                                    Nhân bản
                                </a>
                                <a href="#" class="btn btn-success btn-sm" style="padding: 0 5px;">
                                    <?php echo $item['view_count'] ;?> lượt xem
                                </a>
                                <a href="#" target="_blank" class="btn btn-primary btn-sm" style="padding: 0 5px;">
                                    Xem
                                </a>
                            </td>
                            <td><a href="?<?php echo getLinkQueryString('cate_id', $item['category_id']) ;?>"><?php echo $item['blog_categories_name']; ?></a></td>
                            <td>
                                <a href="?<?php echo getLinkQueryString('user_id', $item['user_id']) ;?>"><?php echo $item['fullname'] ;?></a>
                            </td>
                            <td><?php echo !empty($item['create_at']) ? getDateFormat($item['create_at'], 'd/m/Y H:i:s') : false; ?></td>
                            <td class="text-center">
                                <a href="<?php echo getLinkAdmin('blogs', 'edit', ['id' => $item['id']]); ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                            </td>
                            <td class="text-center">
                                <a href="<?php echo getLinkAdmin('blogs', 'delete', ['id' => $item['id']]); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach;
                else : ?>
                    <tr>
                        <td class="text-center" colspan="8">
                            <div class="alert alert-danger text-center">Không có blog</div>
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
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?modules=blogs' . $queryString . '&page=' . $prevPage . '">Trang trước</a></li>';
                }
                ?>
                <?php
                $begin = $page - 2;
                $end = $page + 2;
                if ($begin < 1) $begin = 1;
                if ($end > $maxPage) $end = $maxPage;
                for ($i = $begin; $i <= $end; $i++) { ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : false; ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?modules=blogs' . $queryString . '&page=' . $i; ?>"><?php echo $i; ?></a></li>
                <?php } ?>
                <?php
                if ($page < $maxPage) {
                    $nextPage = $page + 1;
                    echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?modules=blogs' . $queryString . '&page=' . $nextPage . '">Trang sau</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
<?php
layout('footer', 'admin', $data);
