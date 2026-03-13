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
                $stmt = $this->db->prepare("SELECT * FROM product WHERE name LIKE ? OR description LIKE ? ORDER BY id DESC");
                $stmt->execute(["%$keyword%", "%$keyword%"]);
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /webbanhang/index.php?url=product');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $category_id = $_POST['category_id'] ?? null;

        if (empty($name) || $price <= 0 || empty($category_id)) {
            die("Vui lòng điền đầy đủ thông tin hợp lệ.");
        }

        $image = '';
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . '/../../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($ext, $allowed)) {
                $newName = 'prod_' . uniqid() . '.' . $ext;
                $targetPath = $uploadDir . $newName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $image = 'uploads/' . $newName;
                } else {
                    die("Lỗi upload ảnh.");
                }
            } else {
                die("Chỉ chấp nhận ảnh jpg, jpeg, png, gif.");
            }
        }

        if ($this->productModel->addProduct($name, $description, $price, $category_id, $image)) {
            header('Location: /webbanhang/index.php?url=product');
            exit;
        } else {
            die("Lỗi thêm sản phẩm.");
        }
    }

    public function edit($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            die("Không tìm thấy sản phẩm.");
        }
        $categories = (new CategoryModel($this->db))->getCategories();
        include __DIR__ . '/../views/product/edit.php';
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /webbanhang/index.php?url=product');
            exit;
        }

        $id = $_POST['id'] ?? null;
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $category_id = $_POST['category_id'] ?? null;
        $existing_image = $_POST['existing_image'] ?? '';

        if (empty($id) || empty($name) || $price <= 0 || empty($category_id)) {
            die("Thông tin không hợp lệ.");
        }

        $image = $existing_image;

        if (!empty($_FILES['image']['name'])) {
            $uploadDir = __DIR__ . '/../../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($ext, $allowed)) {
                $newName = 'prod_' . uniqid() . '.' . $ext;
                $target = $uploadDir . $newName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                    $image = 'uploads/' . $newName;
                    if ($existing_image && file_exists(__DIR__ . '/../../' . $existing_image)) {
                        @unlink(__DIR__ . '/../../' . $existing_image);
                    }
                }
            }
        }

        if ($this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image)) {
            header('Location: /webbanhang/index.php?url=product');
            exit;
        } else {
            die("Lỗi cập nhật sản phẩm.");
        }
    }

    public function delete($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            if (!empty($product->image) && file_exists(__DIR__ . '/../../' . $product->image)) {
                @unlink(__DIR__ . '/../../' . $product->image);
            }
            $this->productModel->deleteProduct($id);
        }
        header('Location: /webbanhang/index.php?url=product');
        exit;
    }

    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            die("Sản phẩm không tồn tại.");
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'price'    => (float)$product->price,
                'image'    => $product->image,
                'quantity' => 1
            ];
        }

        header('Location: /webbanhang/index.php?url=product/cart');
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
        header('Location: /webbanhang/index.php?url=product/cart');
        exit;
    }

    public function removeFromCart($id)
    {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: /webbanhang/index.php?url=product/cart');
        exit;
    }

    public function cart()
    {
        // Đảm bảo session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Lấy giỏ hàng
        $cart = $_SESSION['cart'] ?? [];

        // Tính tổng tiền
        $total = 0;
        foreach ($cart as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }

        include __DIR__ . '/../views/product/cart.php';
    }

    public function checkout()
    {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header('Location: /webbanhang/index.php?url=product');
            return;
        }

        // Tính tổng để truyền sang view nếu cần
        $total_price = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }

        include __DIR__ . '/../views/product/checkout.php';
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $customer_name  = trim(filter_var($_POST['customer_name']  ?? '', FILTER_SANITIZE_STRING));
        $customer_email = filter_var($_POST['customer_email'] ?? '', FILTER_VALIDATE_EMAIL);
        $customer_phone = trim(preg_replace('/[^0-9+ -]/', '', $_POST['customer_phone'] ?? ''));
        $address        = trim(filter_var($_POST['address']        ?? '', FILTER_SANITIZE_STRING));

        if (empty($customer_name) || !$customer_email || empty($customer_phone) || empty($address)) {
            die("Vui lòng điền đầy đủ và đúng thông tin.");
        }

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

            $sql_order = "INSERT INTO orders (customer_name, customer_email, customer_phone, address, total_price) 
                          VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql_order);
            $stmt->execute([$customer_name, $customer_email, $customer_phone, $address, $total_price]);

            $order_id = $this->db->lastInsertId();

            $sql_detail = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt_detail = $this->db->prepare($sql_detail);

            foreach ($cart as $product_id => $item) {
                $stmt_detail->execute([$order_id, $product_id, $item['quantity'], $item['price']]);
            }

            $this->db->commit();

            unset($_SESSION['cart']);

            header('Location: /webbanhang/index.php?url=product/orderSuccess&id=' . $order_id);
            exit;
        } catch (Exception $e) {
            $this->db->rollBack();
            die("Lỗi khi đặt hàng: " . $e->getMessage());
        }
    }

    public function orderSuccess()
    {
        $order_id = $_GET['id'] ?? null;
        include __DIR__ . '/../views/product/order_success.php';
    }
}
