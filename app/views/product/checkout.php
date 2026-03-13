<?php include __DIR__ . '/../shaders/header.php'; ?>
<style>
    body {
        background-color: #f4f6f8;
    }

    .checkout-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .checkout-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        padding: 25px;
        margin-bottom: 20px;
    }

    .checkout-header {
        font-size: 1.1rem;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #ced4da;
        padding: 12px 15px;
        font-size: 0.95rem;
    }

    .form-control:focus {
        border-color: #d70018;
        box-shadow: 0 0 0 0.2rem rgba(215, 0, 24, 0.15);
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #555;
        margin-bottom: 5px;
    }

    .summary-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #eee;
    }

    .summary-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .summary-img {
        width: 60px;
        height: 60px;
        object-fit: contain;
        border-radius: 8px;
        border: 1px solid #f0f0f0;
        padding: 2px;
    }

    .btn-checkout {
        background-color: #d70018;
        color: white;
        border-radius: 8px;
        font-weight: bold;
        font-size: 1.1rem;
        text-transform: uppercase;
        padding: 15px;
        border: none;
    }

    .btn-checkout:hover {
        background-color: #c50015;
        color: white;
    }

    .btn-back {
        border: 1px solid #d70018;
        color: #d70018;
        background: white;
        border-radius: 8px;
        font-weight: bold;
        padding: 12px;
    }

    .btn-back:hover {
        background: #fff5f5;
        color: #d70018;
    }
</style>
<div class="container mt-4 mb-5">
    <div class="checkout-container">
        <div class="d-flex align-items-center mb-4">
            <a href="<?php echo BASE_URL; ?>/index.php?url=product/cart" class="text-danger text-decoration-none fw-bold me-3">
                <i class="fas fa-chevron-left"></i> Trở về giỏ hàng
            </a>
            <h4 class="fw-bold m-0 flex-grow-1 text-center">Thanh toán</h4>
            <div style="width: 120px;"></div>
        </div>
        <form action="<?php echo BASE_URL; ?>/index.php?url=product/processCheckout" method="POST">
            <div class="checkout-card">
                <div class="checkout-header">
                    <span><i class="fas fa-user-circle text-danger me-2"></i> Thông tin khách hàng</span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" name="customer_name" class="form-control" required placeholder="Nhập họ và tên (bắt buộc)">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                        <input type="tel" name="customer_phone" class="form-control" required placeholder="Nhập số điện thoại">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="customer_email" class="form-control" required placeholder="Nhập email">
                    </div>
                </div>
            </div>
            <div class="checkout-card">
                <div class="checkout-header">
                    <span><i class="fas fa-truck text-danger me-2"></i> Thông tin nhận hàng</span>
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ nhận hàng chi tiết <span class="text-danger">*</span></label>
                    <textarea name="address" class="form-control" rows="3" required placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố..."></textarea>
                </div>
            </div>
            <div class="checkout-card">
                <div class="checkout-header">
                    <span><i class="fas fa-shopping-bag text-danger me-2"></i> Tóm tắt đơn hàng</span>
                </div>
                <?php
                $total_price = 0;
                $cart = $_SESSION['cart'] ?? [];
                foreach ($cart as $id => $item):
                    $subtotal = $item['price'] * $item['quantity'];
                    $total_price += $subtotal;
                    // ẢNH ĐÃ CÓ BASE_URL TỪ CONTROLLER
                    $imgSrc = !empty($item['image']) ? $item['image'] : "https://via.placeholder.com/60";
                ?>
                    <div class="summary-item">
                        <img src="<?php echo $imgSrc; ?>" class="summary-img me-3" alt="<?php echo $item['name']; ?>">
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold" style="font-size: 0.9rem;"><?php echo $item['name']; ?></h6>
                            <div class="text-muted small">Số lượng: <?php echo $item['quantity']; ?></div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-danger"><?php echo number_format($subtotal, 0, ',', '.'); ?> ₫</div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Tạm tính:</span>
                        <span class="fw-bold"><?php echo number_format($total_price, 0, ',', '.'); ?> ₫</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Phí vận chuyển:</span>
                        <span class="fw-bold text-success">Miễn phí</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5">Tổng tiền:</span>
                        <span class="text-danger fw-bold fs-4"><?php echo number_format($total_price, 0, ',', '.'); ?> ₫</span>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column gap-2 mb-4">
                <button type="submit" class="btn btn-checkout w-100 shadow-sm">
                    Xác nhận đặt hàng
                </button>
                <a href="<?php echo BASE_URL; ?>/index.php?url=product" class="btn btn-back w-100 text-center text-decoration-none">
                    Chọn thêm sản phẩm khác
                </a>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../shaders/footer.php'; ?>