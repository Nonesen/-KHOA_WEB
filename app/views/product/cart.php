<?php include __DIR__ . '/../shaders/header.php'; ?>

<style>
    body {
        background-color: #f4f6f8;
    }

    .cart-container {
        max-width: 600px;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .cart-header {
        border-bottom: 2px solid #f8f9fa;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .cart-item {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        position: relative;
    }

    .btn-remove {
        position: absolute;
        top: 10px;
        right: 15px;
        color: #adb5bd;
        font-size: 1.2rem;
        text-decoration: none;
    }

    .btn-remove:hover {
        color: #dc3545;
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ced4da;
        background: #fff;
        text-decoration: none;
        color: #333;
    }

    .qty-btn:hover {
        background: #e9ecef;
        color: #333;
    }

    .qty-input {
        width: 40px;
        height: 32px;
        text-align: center;
        border: 1px solid #ced4da;
        border-left: none;
        border-right: none;
        font-size: 0.9rem;
        pointer-events: none;
    }

    .promo-box {
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 10px;
        font-size: 0.85rem;
        margin-top: 10px;
    }

    .btn-checkout {
        background-color: #d70018;
        color: white;
        border-radius: 5px;
    }

    .btn-checkout:hover {
        background-color: #c50015;
        color: white;
    }

    .btn-continue {
        border: 1px solid #d70018;
        color: #d70018;
        background: white;
        border-radius: 5px;
    }

    .btn-continue:hover {
        background: #fff5f5;
        color: #d70018;
    }
</style>

<div class="container mt-4 mb-5">
    <div class="cart-container">
        <div class="d-flex justify-content-between align-items-center cart-header">
            <a href="/webbanhang/index.php?url=product" class="text-danger text-decoration-none fw-bold">
                <i class="fas fa-chevron-left"></i> Trở về
            </a>
            <h5 class="text-danger fw-bold m-0 text-center flex-grow-1">Giỏ hàng</h5>
            <div style="width: 70px;"></div>
        </div>

        <?php
        // Vì controller đã truyền $cart và $total, nhưng để an toàn vẫn fallback
        $cart = $cart ?? $_SESSION['cart'] ?? [];
        $total = $total ?? 0;
        ?>

        <?php if (empty($cart)): ?>
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h5 class="text-secondary">Giỏ hàng của bạn đang trống</h5>
                <p class="text-muted">Hãy thêm một vài đồ uống yêu thích nhé!</p>
                <a href="/webbanhang/index.php?url=product" class="btn btn-continue fw-bold mt-3 px-4 py-2">
                    <i class="fas fa-arrow-left me-2"></i>Chọn đồ uống
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($cart as $id => $item):
                $item_total = ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                $total += $item_total; // cập nhật lại nếu cần
            ?>
                <div class="cart-item position-relative">
                    <a href="/webbanhang/index.php?url=product/removeFromCart&id=<?php echo $id; ?>" class="btn-remove" title="Xóa sản phẩm">
                        <i class="fas fa-times"></i>
                    </a>

                    <div class="d-flex align-items-center">
                        <?php
                        $imgSrc = !empty($item['image']) ? '/webbanhang/' . $item['image'] : 'https://via.placeholder.com/80?text=Drink';
                        ?>
                        <img src="<?php echo $imgSrc; ?>" alt="<?php echo htmlspecialchars($item['name'] ?? ''); ?>" style="width:80px; height:80px; object-fit:cover; border-radius:8px;" class="me-3">

                        <div class="flex-grow-1">
                            <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($item['name'] ?? ''); ?></h6>
                            <div class="text-danger fw-bold">
                                <?php echo number_format($item['price'] ?? 0, 0, ',', '.'); ?> ₫
                            </div>

                            <div class="d-flex align-items-center mb-2 mt-2">
                                <span class="me-2 small">Số lượng:</span>
                                <div class="d-flex align-items-center">
                                    <a href="/webbanhang/index.php?url=product/updateCart&id=<?php echo $id; ?>&action=decrease" class="qty-btn">-</a>
                                    <input type="text" class="qty-input" value="<?php echo $item['quantity'] ?? 1; ?>" readonly>
                                    <a href="/webbanhang/index.php?url=product/updateCart&id=<?php echo $id; ?>&action=increase" class="qty-btn">+</a>
                                </div>
                            </div>

                            <div class="promo-box">
                                <div class="fw-bold mb-1">- Khuyến mãi:</div>
                                <div class="text-success small">• Mua 3 tặng 1 (áp dụng cho một số sản phẩm)</div>
                            </div>
                        </div>

                        <div class="text-end ms-3">
                            <div class="fw-bold text-danger fs-5">
                                <?php echo number_format($item_total, 0, ',', '.'); ?> ₫
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="mt-4 pt-3 border-top">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold fs-6">Tổng tiền tạm tính:</span>
                    <span class="text-danger fw-bold fs-5"><?php echo number_format($total, 0, ',', '.'); ?> ₫</span>
                </div>

                <a href="/webbanhang/index.php?url=product/checkout" class="btn btn-checkout w-100 fw-bold py-3 mb-2 text-uppercase d-block text-center text-decoration-none">
                    Tiến hành đặt hàng
                </a>
                <a href="/webbanhang/index.php?url=product" class="btn btn-continue w-100 fw-bold py-3 text-uppercase d-block text-center text-decoration-none">
                    Chọn thêm đồ uống
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../shaders/footer.php'; ?>