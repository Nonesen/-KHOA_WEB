<?php
// Tính tổng số lượng sản phẩm trong giỏ hàng
$cart_count = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fresh Drink - Giải Nhiệt Mùa Hè</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Lexend', sans-serif;
            background-color: #f0f7ff;
        }

        .navbar {
            background: linear-gradient(90deg, #007bff 0%, #00c6ff 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .search-bar {
            border-radius: 25px;
            border: 2px solid #ffffff33;
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .search-bar:focus {
            background: white;
            color: #333;
            border-color: #00c6ff;
        }

        .logo-text {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -1px;
        }

        .badge-cart {
            background-color: #ffc107;
            color: #000;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/webbanhang/index.php?url=product">
                <i class="fas fa-glass-whiskey me-2 fs-3"></i>
                <span class="logo-text">Fresh<span class="text-warning">Drink</span></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <form action="/webbanhang/index.php" method="GET" class="mx-auto d-flex w-50 position-relative">
                    <input type="hidden" name="url" value="product/search">
                    <input type="text" name="keyword" class="search-bar form-control px-4"
                        placeholder="Tìm nước uống bạn thích..."
                        value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                    <button type="submit" class="btn position-absolute end-0 top-0 h-100 text-white pe-3">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a href="/webbanhang/index.php?url=product/cart" class="nav-link position-relative px-3">
                            <i class="fas fa-shopping-basket fs-4 text-white"></i>
                            <?php if ($cart_count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-cart">
                                    <?php echo $cart_count; ?>
                                </span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="py-4">