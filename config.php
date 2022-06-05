<?php
/* file này chứa các hằng số config (cấu hình) */
// Múi giờ VN
date_default_timezone_set('Asia/Ho_Chi_Minh');
// Thiết lập hằng số cho client
const _MODULES_DEFAULT = 'home'; // modules mặc định
const _ACTION_DEFAULT = 'lists'; // action mặc định
// Thiết lập hằng số cho admin
const _MODULES_DEFAULT_ADMIN = 'dashboard'; // modules mặc định

const _INCODE = true; // ngăn chặn hành vi truy cập trực tiếp vào file

// Thiết lập host
define('_WEB_HOST_ROOT', 'http://'.$_SERVER['HTTP_HOST'].'/unicode/modules06/radix'); // địa chỉ trang chủ
define('_WEB_HOST_TEMPLATES', _WEB_HOST_ROOT.'/templates/client'); // template client

define('_WEB_HOST_ROOT_ADMIN', _WEB_HOST_ROOT.'/admin');
define('_WEB_HOST_TEMPLATE_ADMIN', _WEB_HOST_ROOT.'/templates/admin'); // template admin
// Thiết lập path
define('_WEB_PATH_ROOT', __DIR__);
define('_WEB_PATH_TEMPLATES', _WEB_PATH_ROOT.'/templates');

// Thông tin kết nối: khai báo hằng (2 cách) để sau này khi viết hàm thì không cần gọi biến toàn cục
const _HOST = 'localhost';
const _USER = 'root';
const _PASS = '';
const _DB = 'radix';
const DRIVER = 'mysql';
// Thiết lập Debug (Bài 178. note bên overview.notes)
const _DEBUG = false;
// Thiết lập số lượng bản ghi trên 1 trang
const _PER_PAGE = 5;