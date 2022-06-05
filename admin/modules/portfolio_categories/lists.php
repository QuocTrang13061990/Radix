<?php
if (!defined('_INCODE')) die('access delined......'); // Tránh việc truy cập trực tiếp

$data = [
    'pageTitle' => 'Danh mục dự án'
];
layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);
// Xử lý lọc dữ liệu tìm kiếm
$filter = '';
$view = 'add'; // view mac dinh
$id = 0; // id mac dinh
// if (isGet()) {
    $body = getBody('GET');
    if (!empty($body['keyword'])) {
        $keyword = $body['keyword'];
        $filter = "WHERE name LIKE '%$keyword%'";
    }
    if(!empty($body['view'])){
        $view = $body['view'];
    }
    if(!empty($body['id'])){
        $id = $body['id'];
    }
// }
// Xử lý phân trang
// 1. Lấy số lượng bản ghi với đk lọc
$allCateNum = getRow("SELECT id FROM portfolio_categories $filter");
// 2. Số bản ghi trong 1 page
$perPage = _PER_PAGE;
// 3. Tổng số trang tìm được
$maxPage = ceil($allCateNum / $perPage);
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
    $queryString = (strpos($queryString, 'modules=portfolio_categories') !== false) ? str_replace('modules=portfolio_categories', '', $queryString) : $queryString;
    if (!empty($body['page']) && strpos($queryString, 'page=' . $body['page']) !== false) {
        $queryString = str_replace('page=' . $body['page'], '', $queryString);
    }
    $queryString = trim($queryString, '&');
    $queryString = '&' . $queryString;
}

// Truy vấn lấy tất cả bản ghi
$listCates = getRaw("SELECT *, (SELECT count(portfolios.id) FROM portfolios WHERE portfolios.portfolio_category_id = portfolio_categories.id) as portfolios_count FROM portfolio_categories $filter ORDER BY create_at DESC LIMIT $offset, $perPage");


$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
?>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
    <?php getMsg($msg, $msgType); ?>
        <div class="row">
            <div class="col-4">
                <?php 
                    if(!empty($view) && !empty($id)){
                        require_once $view.'.php';
                    }else{
                        require_once 'add.php';
                    }
                ?>
            </div>
            <div class="col-8">
                <h4>Danh sách danh mục</h4>
                <hr>
                <form action="" method="GET">
                    <div class="row">
                        <div class="col-9">
                            <input type="search" class="form-control" name="keyword" placeholder="Nhập tên danh mục dự án..." value="<?php echo !empty($keyword) ? $keyword : false; ?>">
                        </div>
                        <div class="col-3">
                            <button type="submit" class="btn btn-primary btn-block">Tìm kiếm</button>
                        </div>
                    </div>
                    <!-- Thêm thẻ input như sau để khi submit thì không chuyển về trang chủ -->
                    <input type="hidden" name="modules" value="portfolio_categories">
                </form>
                <hr>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" width="5%">STT</th>
                            <th class="text-center">Tên danh mục</th>
                            <th class="text-center">Thời gian</th>
                            <th class="text-center" width="7%">Sửa</th>
                            <th class="text-center" width="7%">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($listCates)) :
                            $count = 0;
                            foreach ($listCates as $k => $v) :
                                $count++;
                        ?>
                                <tr>
                                    <td><?php echo $offset + $count; ?></td>
                                    <td>
                                        <a href="<?php echo getLinkAdmin('portfolio_categories', '', ['id' => $v['id'], 'view' => 'edit']); ?>">
                                            <?php echo $v['name']; ?>
                                        </a>
                                        <span>(<?php echo $v['portfolios_count'] ;?>) </span>
                                        <br>    
                                        <a href="<?php echo getLinkAdmin('portfolio_categories', 'duplicate', ['id' => $v['id']]); ?>" class="btn btn-danger btn-sm" style="padding: 0 1px;">
                                            Nhân bản
                                        </a>
                                    </td>
                                    <td><?php echo !empty($v['create_at']) ? getDateFormat($v['create_at'], 'd/m/Y H:i:s') : ''; ?></td>
                                    <td class="text-center"><a href="<?php echo getLinkAdmin('portfolio_categories', '', ['id' => $v['id'], 'view' => 'edit']); ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a></td>
                                    <td class="text-center"><a href="<?php echo getLinkAdmin('portfolio_categories', '', ['id' => $v['id'], 'view' => 'delete']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')"><i class="fa fa-trash"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="alert alert-danger text-center">Không có nhóm người dùng</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <nav aria-label="Page navigation example" class="d-flex justify-content-end">
                    <ul class="pagination pagination-sm">
                        <?php
                        if ($page > 1) {
                            $prevPage = $page - 1;
                            echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?modules=portfolio_categories' . $queryString . '&page=' . $prevPage . '">Trang trước</a></li>';
                        }
                        ?>
                        <?php
                        $begin = $page - 2;
                        $end = $page + 2;
                        if ($begin < 1) $begin = 1;
                        if ($end > $maxPage) $end = $maxPage;
                        for ($i = $begin; $i <= $end; $i++) { ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : false; ?>"><a class="page-link" href="<?php echo _WEB_HOST_ROOT_ADMIN . '?modules=portfolio_categories' . $queryString . '&page=' . $i; ?>"><?php echo $i; ?></a></li>
                        <?php } ?>
                        <?php
                        if ($page < $maxPage) {
                            $nextPage = $page + 1;
                            echo '<li class="page-item"><a class="page-link" href="' . _WEB_HOST_ROOT_ADMIN . '?modules=portfolio_categories' . $queryString . '&page=' . $nextPage . '">Trang sau</a></li>';
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
layout('footer', 'admin', $data);
