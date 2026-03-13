<?php
// Sử dụng __DIR__ để đảm bảo nạp file chính xác tuyệt đối
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class ProductController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        // Khởi tạo session an toàn cho Giỏ hàng
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Khởi tạo kết nối DB (PDO)
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    // ==========================================
    // PHẦN 1: QUẢN LÝ SẢN PHẨM
    // ==========================================
    public function index()
    {
        $categories = (new CategoryModel($this->db))->getCategories();
        $category_id = $_GET['category_id'] ?? null;
        $keyword = $_GET['keyword'] ?? '';
        $products = [];

        try {
            if ($category_id) {
                $stmt = $this->db->prepare("SELECT * FROM product WHERE category_id = ? ORDER BY id DESC");
                $stmt->execute([$category_id]);
                $products = $stmt->fetchAll(PDO::FETCH_OBJ);
            } elseif (!empty($keyword)) {
                $stmt = $this->db->prepare("SELECT * FROM product WHERE name LIKE ? ORDER BY id DESC");
                $stmt->execute(["%$keyword%"]);
                $products = $stmt->fetchAll(PDO::FETCH_OBJ);
            } else {
                $stmt = $this->db->prepare("SELECT * FROM product ORDER BY id DESC");
                $stmt->execute();
                $products = $stmt->fetchAll(PDO::FETCH_OBJ);
            }
        } catch (Exception $e) {
            die("Lỗi truy vấn: " . $e->getMessage());
        }

        include __DIR__ . '/../views/product/list.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include __DIR__ . '/../views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function add()
    {
        $categories = (new CategoryModel($this->db))->getCategories();
        include __DIR__ . '/../views/product/add.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $image = "";

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                try {
                    $image = $this->uploadImage($_FILES['image']);
                } catch (Exception $e) {
                    die("Lỗi upload: " . $e->getMessage());
                }
            }

            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);
            if (is_array($result)) {
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include __DIR__ . '/../views/product/add.php';
            } else {
                header('Location: ' . BASE_URL . '/index.php?url=product');
                exit;
            }
        }
    }

    public function edit($id)
    {
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        if ($product) {
            include __DIR__ . '/../views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = $_POST['existing_image'];
            }

            $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
            if ($edit) {
                header('Location: ' . BASE_URL . '/index.php?url=product');
                exit;
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    public function delete($id)
    {
        if ($this->productModel->deleteProduct($id)) {
            header('Location: ' . BASE_URL . '/index.php?url=product');
            exit;
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    public function search()
    {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        if (!empty($keyword)) {
            $products = $this->productModel->searchProducts($keyword);
        } else {
            $products = [];
        }
        include __DIR__ . '/../views/product/list.php';
    }

    // ==================== HÀM UPLOAD ẢNH ĐÃ SỬA (QUAN TRỌNG) ====================
    private function uploadImage($file)
    {
        // Đường dẫn vật lý trên server
        $target_dir = __DIR__ . "/../../uploads/";

        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        // Tạo tên file mới (tránh trùng)
        $filename = time() . "_" . basename($file["name"]);
        $filename = preg_replace('/[^a-zA-Z0-9_.]/', '', $filename);
        $target_file = $target_dir . $filename;

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra file có phải hình ảnh không
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }

        // Kiểm tra kích thước file (max 5MB)
        if ($file["size"] > 5 * 1024 * 1024) {
            throw new Exception("Hình ảnh quá lớn (tối đa 5MB).");
        }

        // Kiểm tra định dạng
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif", "webp"])) {
            throw new Exception("Chỉ hỗ trợ jpg, jpeg, png, gif, webp.");
        }

        // Upload file
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            // === TRẢ VỀ PATH TƯƠNG ĐỐI (KHÔNG CÓ BASE_URL) ===
            // Vì các view sẽ tự xử lý BASE_URL
            return "uploads/" . $filename;
        }

        throw new Exception("Lỗi khi tải file lên.");
    }
    // =========================================================================

    // ==========================================
    // PHẦN 2: GIỎ HÀNG VÀ THANH TOÁN
    // ==========================================
    public function cart()
    {
        $cart = $_SESSION['cart'] ?? [];
        include __DIR__ . '/../views/product/cart.php';
    }

    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            header('Location: ' . BASE_URL . '/index.php?url=product&error=notfound');
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            // Lưu path ảnh từ database vào session
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image  // ← Path từ DB (uploads/xxx.jpg)
            ];
        }

        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: ' . BASE_URL . '/index.php?url=product');
        }
        exit;
    }

    public function updateCart()
    {
        $id = $_GET['id'] ?? null;
        $action = $_GET['action'] ?? null;

        if ($id && isset($_SESSION['cart'][$id])) {
            if ($action === 'increase') {
                $_SESSION['cart'][$id]['quantity']++;
            } elseif ($action === 'decrease') {
                $_SESSION['cart'][$id]['quantity']--;
                if ($_SESSION['cart'][$id]['quantity'] <= 0) {
                    unset($_SESSION['cart'][$id]);
                }
            }
        }

        header('Location: ' . BASE_URL . '/index.php?url=product/cart');
        exit;
    }

    public function removeFromCart()
    {
        $id = $_GET['id'] ?? null;
        if ($id && isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: ' . BASE_URL . '/index.php?url=product/cart');
        exit;
    }

    public function checkout()
    {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header('Location: ' . BASE_URL . '/index.php?url=product');
            return;
        }
        include __DIR__ . '/../views/product/checkout.php';
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $customer_name = $_POST['customer_name'] ?? '';
            $customer_email = $_POST['customer_email'] ?? '';
            $customer_phone = $_POST['customer_phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $cart = $_SESSION['cart'] ?? [];

            if (empty($cart)) {
                die("Giỏ hàng trống!");
            }

            $total_price = 0;
            foreach ($cart as $item) {
                $total_price += $item['price'] * $item['quantity'];
            }

            try {
                $this->db->beginTransaction();

                $stmt = $this->db->prepare("INSERT INTO orders (customer_name, customer_email, customer_phone, address, total_price) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$customer_name, $customer_email, $customer_phone, $address, $total_price]);
                $order_id = $this->db->lastInsertId();

                $stmt_detail = $this->db->prepare("INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                foreach ($cart as $product_id => $item) {
                    $stmt_detail->execute([$order_id, $product_id, $item['quantity'], $item['price']]);
                }

                $this->db->commit();
                unset($_SESSION['cart']);

                header('Location: ' . BASE_URL . '/index.php?url=product/orderSuccess&id=' . $order_id);
                exit;
            } catch (Exception $e) {
                $this->db->rollBack();
                echo "Lỗi khi đặt hàng: " . $e->getMessage();
            }
        }
    }

    public function orderSuccess()
    {
        $order_id = $_GET['id'] ?? null;
        include __DIR__ . '/../views/product/order_success.php';
    }
}
