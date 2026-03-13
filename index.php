<?php
// 1. BẮT BUỘC: Bắt đầu session ở dòng đầu tiên để dùng biến $_SESSION cho giỏ hàng
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Định nghĩa BASE_URL - QUAN TRỌNG CHO ĐƯỜNG DẪN ẢNH
define('BASE_URL', '/webbanhang');

// Định nghĩa thư mục gốc để nạp file không bị lỗi đường dẫn
define('ROOT_PATH', __DIR__ . '/');

// Nạp Model (Tùy thuộc vào cấu trúc của bạn, nếu bạn đã require ở Controller thì có thể bỏ qua dòng này)
require_once ROOT_PATH . 'app/models/ProductModel.php';

// 2. Xử lý URL đầu vào ( ?url=product/addToCart/5)
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// 3. Xác định Controller
// Nếu người dùng không nhập gì, mặc định sẽ gọi ProductController
$controllerName = (!empty($url[0])) ? ucfirst($url[0]) . 'Controller' : 'ProductController';

// 4. Xác định Action (hàm bên trong Controller)
// Mặc định là hàm index()
$action = (!empty($url[1])) ? $url[1] : 'index';

// 5. Kiểm tra xem file Controller có tồn tại không
$controllerFile = ROOT_PATH . 'app/controllers/' . $controllerName . '.php';
if (!file_exists($controllerFile)) {
    die("Lỗi 404: Không tìm thấy Controller '$controllerName' tại đường dẫn: $controllerFile");
}

require_once $controllerFile;

// 6. Khởi tạo Controller
$controller = new $controllerName();

// Kiểm tra xem hàm (Action) có tồn tại trong Controller không
if (!method_exists($controller, $action)) {
    die("Lỗi 404: Không tìm thấy Action '$action' trong Controller '$controllerName'.");
}

// 7. Lấy các tham số truyền vào hàm (nếu có, ví dụ ID sản phẩm)
$params = array_slice($url, 2);
// Bổ sung xử lý: Nếu tham số truyền qua dạng ?url=product/addToCart&id=5
if (empty($params) && isset($_GET['id'])) {
    $params[] = $_GET['id'];
}

// 8. Gọi action với các tham số đã lấy được
call_user_func_array([$controller, $action], $params);
