<?php include __DIR__ . '/../shaders/header.php'; ?>

<div class="container mt-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/webbanhang/index.php?url=product" class="text-decoration-none text-danger">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product->name); ?></li>
        </ol>
    </nav>

    <div class="row bg-white rounded-4 shadow-sm p-4">
        <div class="col-md-5 text-center border-end">
            <?php
            // Ưu tiên ảnh từ DB, nếu rỗng thì dùng ảnh mặc định đẹp
            $imgSrc = !empty($product->image)
                ? "/webbanhang/" . $product->image
                : "https://www.istockphoto.com/vi/anh/lon-coca-cola-gm488805379-39822884";
            ?>
            <img src="<?php echo $imgSrc; ?>" class="img-fluid rounded-3" alt="<?php echo htmlspecialchars($product->name); ?>" style="max-height: 450px; object-fit: contain;">
        </div>

        <div class="col-md-7 ps-md-5">
            <h2 class="fw-bold mb-3"><?php echo htmlspecialchars($product->name); ?></h2>

            <div class="p-3 bg-light rounded-3 mb-4">
                <div class="d-flex align-items-center mb-2">
                    <span class="fs-2 fw-bold text-danger me-3"><?php echo number_format($product->price, 0, ',', '.'); ?>đ</span>
                    <span class="text-secondary text-decoration-line-through"><?php echo number_format($product->price * 1.2, 0, ',', '.'); ?>đ</span>
                </div>
                <p class="text-success small mb-0"><i class="fas fa-check-circle"></i> Miễn phí vận chuyển toàn quốc</p>
            </div>

            <div class="mb-4">
                <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i>Mô tả sản phẩm:</h6>
                <p class="text-muted" style="line-height: 1.6;">
                    <?php echo nl2br(htmlspecialchars($product->description ?? 'Chưa có mô tả chi tiết.')); ?>
                </p>
            </div>

            <div class="mb-4">
                <p><strong>Danh mục:</strong> <span class="badge bg-secondary"><?php echo htmlspecialchars($product->category_name ?? 'Đồ uống'); ?></span></p>
            </div>

            <div class="d-grid gap-2 d-md-flex mt-5">
                <a href="/webbanhang/index.php?url=product/addToCart/<?php echo $product->id; ?>" class="btn btn-danger btn-lg flex-grow-1 py-3 fw-bold shadow">
                    MUA NGAY <br> <span style="font-size: 0.7rem; font-weight: normal;">(Giao tận nơi hoặc nhận tại cửa hàng)</span>
                </a>
                <a href="/webbanhang/index.php?url=product/addToCart/<?php echo $product->id; ?>" class="btn btn-outline-danger btn-lg px-4 d-flex align-items-center justify-content-center">
                    <i class="fas fa-cart-plus fs-4"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="mt-5 p-4 bg-white rounded-4 shadow-sm mb-5">
        <h5 class="fw-bold border-bottom pb-3 mb-3">THÔNG TIN SẢN PHẨM</h5>
        <div class="row">
            <div class="col-md-12">
                <p>Sản phẩm <strong><?php echo htmlspecialchars($product->name); ?></strong> được bảo quản ở nhiệt độ lý tưởng, đảm bảo giữ nguyên hương vị tươi ngon khi đến tay khách hàng.</p>
                <ul>
                    <li>Dung tích: Xem trên bao bì</li>
                    <li>Hạn sử dụng: 12 tháng kể từ ngày sản xuất</li>
                    <li>Hướng dẫn bảo quản: Ngon hơn khi uống lạnh</li>
                </ul>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../shaders/footer.php'; ?>