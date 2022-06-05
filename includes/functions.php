<?php
if (!defined('_INCODE')) die('access delined......');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function layout($layoutName = 'header', $dir = '', $data = array())
{
    if (!empty($dir)) $dir = '/' . $dir;
    if (file_exists(_WEB_PATH_TEMPLATES . $dir . '/layouts/' . $layoutName . '.php')) {
        require_once _WEB_PATH_TEMPLATES . $dir . '/layouts/' . $layoutName . '.php';
    }
}

function sendMail($to, $subject, $content)
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'tqtwip@gmail.com';                     //SMTP username
        $mail->Password   = 'qicbazzoranxmwyg';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('trang.tran@greensystem.vn', 'Trần Quốc Tráng');
        $mail->addAddress($to);     //Add a recipient
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->CharSet = "UTF-8";
        $mail->Subject = $subject;
        $mail->Body    = $content;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        return $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Kiểm tra phương thức POST
function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return true;
    }
    return false;
}

// Kiểm tra phương thức GET
function isGet()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        return true;
    }
    return false;
}
// 1. Filter (Một số hàm hay dùng)
// Lấy giá tri phương thức POST, GET (lọc dữ liệu với hàm filter_input())
function getBody($method = '')
{
    $bodyArr = [];
    // Nếu không truyền $method thì lấy tự động
    if (empty($method)) {
        if (isGet()) {
            if (!empty($_GET)) {
                foreach ($_GET as $key => $value) {
                    $key = strip_tags($key); // loại bỏ thẻ HTML trong $key
                    if (is_array($value)) {
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
        if (isPost()) {
            if (!empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    $key = strip_tags($key); // loại bỏ thẻ HTML trong $key
                    if (is_array($value)) {
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
    } else {
        if ($method == 'GET') {
            if (!empty($_GET)) {
                foreach ($_GET as $key => $value) {
                    $key = strip_tags($key); // loại bỏ thẻ HTML trong $key
                    if (is_array($value)) {
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $bodyArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        } elseif ($method == 'POST') {
            if (!empty($_POST)) {
                foreach ($_POST as $key => $value) {
                    $key = strip_tags($key); // loại bỏ thẻ HTML trong $key
                    if (is_array($value)) {
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                    } else {
                        $bodyArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                    }
                }
            }
        }
    }

    return $bodyArr;
}
// 2. Validate (Một số hàm hay dùng)
// Kiểm tra có phải là email không
function isEmail($email)
{
    $checkEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $checkEmail;
}
// Kiểm tra có phải là số nguyên và nằm trong khoảng range nào đó không
function isNumberInt($number, $range = [])
{
    /* range = ['min_range' => 3, 'max_range' => 20]; */
    if (!empty($range)) {
        $options = ['options' => $range];
        $checkNumberInt = filter_var($number, FILTER_VALIDATE_INT, $options);
    } else {
        $checkNumberInt = filter_var($number, FILTER_VALIDATE_INT);
    }
    return $checkNumberInt;
}
// Kiểm tra có phải là số thực
function isNumberFloat($number, $range = [])
{
    /* range = ['min_range' => 3, 'max_range' => 20]; */
    if (!empty($range)) {
        $options = ['options' => $range];
        $checkNumberInt = filter_var($number, FILTER_VALIDATE_FLOAT, $options);
    } else {
        $checkNumberInt = filter_var($number, FILTER_VALIDATE_FLOAT);
    }
    return $checkNumberInt;
}
// Kiểm tra số điện thoại (10 chữ số, bắt đầu 0)
function isPhone($phone)
{
    $checkFirstPhone = false;
    if ($phone[0] == '0') {
        $checkFirstPhone = true;
        $phone = substr($phone, 1);
    }
    $checkLastPhone = false;
    if (isNumberInt($phone) && strlen($phone) == 9) {
        $checkLastPhone = true;
    }
    if ($checkFirstPhone && $checkLastPhone) {
        return true;
    }
    return false;
}
// Hàm chuyển hướng trang
function redirect($path = 'index.php')
{
    $url = _WEB_HOST_ROOT . '/' . $path;
    // echo $url;exit;
    header("Location: $url");
    exit();
}
// Hàm thông báo lỗi form
function form_error($fieldname, $errors, $beforeHtml, $afterHtml)
{
    return (!empty($errors[$fieldname])) ? $beforeHtml . reset($errors[$fieldname]) . $afterHtml : null;
}
// Hàm hiển thị dữ liệu cũ 
function old($fieldname, $oldData, $default = null)
{
    return (!empty($oldData[$fieldname])) ? $oldData[$fieldname] : $default;
}
// Hàm kiểm tra trạng thái đăng nhập
function isLogin()
{
    $checkLogin = false;
    if (getSession('tokenLogin')) {
        $tokenLogin = getSession('tokenLogin');
        $queryUser = firstRaw("SELECT user_id FROM login_token WHERE token='$tokenLogin'");
        if (!empty($queryUser)) {
            // $checkLogin = true;
            $checkLogin = $queryUser;
        } else {
            removeSession('tokenLogin');
        }
    }
    return $checkLogin;
}
// Khi bấm đăng xuất thì đã xóa được token trong login_token, Tuy nhiên khi tắt trình duyệt thì không xóa được token (mặc dù đã đăng xuất), do đó cần xóa token này, dưới đây là function để xóa những token này
// Khi vào những trang có gọi đến function này thì mới xóa được
function autoRemoveTokenLogin()
{
    $users = getRaw("SELECT * FROM users WHERE status = 1");
    foreach ($users as $user) {
        $now = date('Y-m-d H:i:s');
        $before = $user['lastActivity'];
        $diff = strtotime($now)  - strtotime($before);
        $diff = floor($diff / 60); // tinh theo minute
        if ($diff >= 2) {
            deleteData('login_token', "user_id=" . $user['id']);
        }
    }
}
// Lưu lại thời gian hoạt động cuối cùng của user
function saveActivity()
{
    if (isLogin()) {
        $user_id = isLogin()['user_id']; // lấy những user đang đăng nhập
        if (!empty($user_id)) {
            $dataEdit = [
                'lastActivity' => date('Y-m-d H:i:s')
            ];
            editData('users', $dataEdit, "id=$user_id"); // thêm thời gian hoạt động sau cùng 
        }
    }
}

// Active Menu Sidebar
function activeMenuSidebar($module)
{
    $body = getBody();
    if ($body['modules'] == $module) {
        return true;
    }
    return false;
}

// getLink
function getLinkAdmin($module = '', $action = '', $params = [])
{
    $url = _WEB_HOST_ROOT_ADMIN;
    if (!empty($module)) {
        $url = $url . '?modules=' . $module;
        if (!empty($action)) $url = $url . '&action=' . $action;
        // params = ['id'=>1, 'keyword'=>'unicode'] => paramString = id=1&keyword=unicode
        if (!empty($params)) {
            $paramString = http_build_query($params);
            $url = $url . '&' . $paramString;
        }
    }
    return $url;
}
// format Date
function getDateFormat($strDate, $format)
{
    $dateObject = date_create($strDate);
    if (!empty($dateObject)) {
        return date_format($dateObject, $format);
    }
    return false;
}
// kiểm tra là fontawesome: Nếu True thì hiển thị, false thì hiển thị thẻ img
function isFontIcon($input)
{
    if (strpos($input, '<i class="') !== false) {
        return true;
    }
    return false;
}
// Cập nhật lại chuỗi $_SERVER['QUERY_STRING']
function getLinkQueryString($key, $value)
{
    // $queryFinal = '';
    // if($_SERVER['QUERY_STRING'] == 'modules=services'){
    //     $queryFinal = "keyword=&$key=$value&modules=services";
    // }elseif($_SERVER['QUERY_STRING'] == 'modules=portfolios'){
    //     $queryFinal = "keyword=&$key=$value&modules=portfolios";
    // }
    // else {
    //     $queryArr = explode('&', $_SERVER['QUERY_STRING']);
    //     $queryArr = array_filter($queryArr);
    //     if(!empty($queryArr)){
    //         foreach($queryArr as $item){
    //             $itemArr = explode('=', $item);
    //             if(!empty($itemArr)){
    //                 if($itemArr[0] == $key) $itemArr[1] = $value;
    //                 $itemStr = implode('=', $itemArr);
    //                 $queryFinal .= $itemStr.'&';

    //             }
    //         }
    //         if(!empty($queryFinal)) {
    //             $queryFinal = rtrim($queryFinal, '&');
    //         }
    //     } 
    // }
    // return $queryFinal;
    $queryString = $_SERVER['QUERY_STRING'];
    $queryArr = explode('&', $queryString);
    $queryArr = array_filter($queryArr);

    $queryFinal = '';

    $check = false;

    if (!empty($queryArr)) {
        foreach ($queryArr as $item) {
            $itemArr = explode('=', $item);
            if (!empty($itemArr)) {
                if ($itemArr[0] == $key) {
                    $itemArr[1] = $value;
                    $check = true;
                }

                $item = implode('=', $itemArr);

                $queryFinal .= $item . '&';
            }
        }
    }

    if (!$check) {
        $queryFinal .= $key . '=' . $value;
    }

    if (!empty($queryFinal)) {
        $queryFinal = rtrim($queryFinal, '&');
    } else {
        $queryFinal = $queryString;
    }

    return $queryFinal;
}

// function xử lý lỗi DEBUG
// Hàm này hiển thị lỗi parse, FATAL
function showExceptionError($exception)
{
    if (!_DEBUG) {
        require_once _WEB_PATH_ROOT . '/modules/errors/500.php';
        die();
    }
    require_once _WEB_PATH_ROOT . '/modules/errors/exception.php';
    die();
}
// Hàm này hiển thị lỗi warning, notice
function showErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!_DEBUG) return; // Khi _DEBUG = false thì không hiển thị lỗi
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
function getPathAdmin()
{
    $path = 'admin';
    if (!empty($_SERVER['QUERY_STRING'])) {
        $path .= '?' . trim($_SERVER['QUERY_STRING']);
    }

    return $path;
}
/* Begin: Options */
function getOption($key, $type = '')
{
    $option = firstRaw("SELECT * FROM options WHERE opt_key='$key'");
    if (!empty($option)) {
        if ($type == 'label') {
            return $option['name'];
        }
        return $option['opt_value'];
    }
    return false;
}
function updateOption()
{
    if (isPost()) {
        if (!empty(getBody())) {
            $body = getBody();
            $countFieldEdit = 0;
            foreach ($body as $key => $item) {
                $dataEdit = [
                    'opt_value' => trim($item)
                ];
                $editDataStatus = editData('options', $dataEdit, "opt_key='$key'");
                if ($editDataStatus) {
                    $countFieldEdit++;
                }
            }
            if ($countFieldEdit > 0) {
                setFlashData('msg', 'Đã cập nhật ' . $countFieldEdit . ' bản ghi thành công');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'Cập nhật không thành công');
                setFlashData('msg_type', 'error');
            }
            redirect(getPathAdmin());
        }
    }
}
/* End: Options */

// Hàm đếm có bao nhiêu liên hệ chưa xử lý (status = 0)
function countContacts(){
    $sql = "SELECT id FROM contacts WHERE status = 0";
    $countContacts = getRow($sql);
    return $countContacts;
}