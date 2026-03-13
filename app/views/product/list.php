<?php include __DIR__ . '/../shaders/header.php'; ?>

<style>
    /* CSS cho Sidebar Danh mục */
    .sidebar-menu .list-group-item:hover {
        background-color: #e3f2fd;
        /* Màu xanh nhạt */
        color: #0d6efd !important;
        /* Xanh nước biển */
        transform: translateX(4px);
    }

    .product-card {
        transition: all 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(13, 110, 253, 0.15) !important;
    }

    /* CSS cho Card Sản phẩm */
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        /* ← SỬA WARNING VS CODE */
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .btn-add-cart {
        transition: background-color 0.2s, transform 0.2s;
    }

    .btn-add-cart:hover {
        background-color: #0056b3;
        transform: scale(1.02);
    }

    .btn-danger {
        background-color: #0d6efd;
        border: none;
    }

    /* Đổi nút đỏ sang xanh */
    .btn-danger:hover {
        background-color: #0a58ca;
    }

    /* ==========================================
       CSS CHO BANNER QUẢNG CÁO 2 BÊN (MỚI)
       ========================================== */
    .sticky-banner {
        position: fixed;
        top: 120px;
        /* Cân chỉnh khoảng cách từ trên xuống */
        width: 200px;
        /* Chiều rộng của banner */
        z-index: 99;
        transition: all 0.3s ease;
    }

    .sticky-banner img {
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .sticky-banner:hover {
        transform: translateY(-5px);
    }

    /* Banner Trái (Canh giữa màn hình trừ đi khoảng cách) */
    .banner-left {
        left: calc(40% - 710px);
    }

    /* Banner Phải (Canh giữa màn hình cộng thêm khoảng cách) */
    .banner-right {
        right: calc(40% - 710px);
    }

    /* Chỉ hiển thị banner trên màn hình RẤT LỚN (PC/Màn hình rộng) để tránh đè vào sản phẩm */
    @media (max-width: 1450px) {
        .sticky-banner {
            display: none !important;
        }
    }
</style>

<!-- Banner đã đổi sang chủ đề ĐỒ UỐNG -->
<a href="#" class="sticky-banner banner-right">
    <img src="https://via.placeholder.com/321x795/28a745/ffffff?text=ƯU+ĐÃI+NƯỚC+UỐNG+15%" alt="">
</a>

<a href="#" class="sticky-banner banner-left">
    <img src="https://via.placeholder.com/321x795/ff6600/ffffff?text=TRÀ+SỮA+SINH+TỐ+GIẢM+GIÁ" alt="">
</a>

<div class="container mt-4 mb-5">
    <div class="row">

        <div class="col-md-3 col-lg-2 mb-4 d-none d-md-block">
            <div class="list-group sidebar-menu shadow-sm border-0 rounded-3 sticky-top" style="top: 20px;">
                <div class="list-group-item border-0 bg-danger text-white fw-bold py-3 rounded-top-3">
                    <i class="fas fa-bars me-2 text-white"></i> DANH MỤC
                </div>

                <a href="/webbanhang/index.php?url=product"
                    class="list-group-item list-group-item-action border-0 py-3 d-flex align-items-center <?php echo !isset($_GET['category_id']) ? 'fw-bold text-danger bg-light' : ''; ?>">
                    <i class="fas fa-glass-water me-2 <?php echo !isset($_GET['category_id']) ? 'text-danger' : 'text-secondary'; ?>"></i>
                    Tất cả đồ uống
                </a>

                <?php if (isset($categories) && !empty($categories)): ?>
                    <?php foreach ($categories as $cat):
                        $name = is_array($cat) ? $cat['name'] : $cat->name;
                        $id = is_array($cat) ? $cat['id'] : $cat->id;
                        $nameLower = mb_strtolower($name, 'UTF-8');

                        $icon = 'fa-glass-water';
                        if (strpos($nameLower, 'trà') !== false) $icon = 'fa-leaf';
                        elseif (strpos($nameLower, 'cà phê') !== false) $icon = 'fa-coffee';
                        elseif (strpos($nameLower, 'sinh tố') !== false) $icon = 'fa-blender';
                        elseif (strpos($nameLower, 'bia') !== false) $icon = 'fa-beer-mug-empty';
                        elseif (strpos($nameLower, 'soda') !== false) $icon = 'fa-bottle-droplet';

                        $isActive = (isset($_GET['category_id']) && $_GET['category_id'] == $id);
                    ?>
                        <a href="/webbanhang/index.php?url=product&category_id=<?php echo $id; ?>"
                            class="list-group-item list-group-item-action border-0 py-3 d-flex align-items-center <?php echo $isActive ? 'fw-bold text-danger bg-light' : ''; ?>">
                            <i class="fas <?php echo $icon; ?> me-2 <?php echo $isActive ? 'text-danger' : 'text-secondary'; ?>"></i>
                            <?php echo htmlspecialchars($name); ?>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded-3 shadow-sm">
                <h5 class="fw-bold text-uppercase m-0 text-danger">
                    <?php
                    if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
                        echo "<i class='fas fa-search me-2'></i>Tìm kiếm: '" . htmlspecialchars($_GET['keyword']) . "'";
                    } elseif (isset($_GET['category_id'])) {
                        echo "<i class='fas fa-filter me-2'></i>Sản phẩm theo danh mục";
                    } else {
                        echo "<i class='fas fa-fire me-2'></i>Đồ uống nổi bật";
                    }
                    ?>
                </h5>
                <a href="/webbanhang/index.php?url=product/add" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm">
                    <i class="fas fa-plus-circle me-1"></i> Quản lý kho
                </a>
            </div>

            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative product-card">
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2 z-1" style="font-size: 0.7rem;">Giảm 15%</span>

                                <a href="/webbanhang/index.php?url=product/show/<?php echo is_array($product) ? $product['id'] : $product->id; ?>" class="text-decoration-none text-dark">
                                    <div class="p-3 text-center">
                                        <?php
                                        $image = is_array($product) ? $product['image'] : $product->image;
                                        $imgSrc = !empty($image) ? "/webbanhang/" . $image : "https://via.placeholder.com/200";
                                        ?>
                                        <img src="<?php echo $imgSrc; ?>" class="img-fluid rounded-3" style="height: 160px; object-fit: contain;">
                                    </div>

                                    <div class="card-body p-3 pt-0">
                                        <h6 class="fw-bold mb-2 text-truncate-2" style="height: 40px; font-size: 0.9rem; line-height: 1.2;">
                                            <?php echo htmlspecialchars(is_array($product) ? $product['name'] : $product->name, ENT_QUOTES, 'UTF-8'); ?>
                                        </h6>

                                        <div class="d-flex flex-column">
                                            <?php $price = is_array($product) ? $product['price'] : $product->price; ?>
                                            <span class="text-danger fw-bold fs-5"><?php echo number_format($price, 0, ',', '.'); ?>đ</span>
                                            <span class="text-muted text-decoration-line-through small"><?php echo number_format($price * 1.2, 0, ',', '.'); ?>đ</span>
                                        </div>

                                        <div class="mt-2 small text-warning">
                                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                </a>

                                <div class="card-footer bg-white border-0 p-2 pt-0">
                                    <a href="/webbanhang/index.php?url=product/addToCart&id=<?php echo is_array($product) ? $product['id'] : $product->id; ?>"
                                        class="btn btn-primary w-100 mb-2 rounded-3 fw-bold shadow-sm btn-add-cart">
                                        <i class="fas fa-cart-plus me-1"></i> Thêm vào giỏ
                                    </a>
                                    <div class="d-flex gap-1">
                                        <a href="/webbanhang/index.php?url=product/edit/<?php echo is_array($product) ? $product['id'] : $product->id; ?>" class="btn btn-outline-warning btn-sm flex-grow-1 py-1 rounded-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/webbanhang/index.php?url=product/delete/<?php echo is_array($product) ? $product['id'] : $product->id; ?>" class="btn btn-outline-danger btn-sm flex-grow-1 py-1 rounded-3" onclick="return confirm('Xóa sản phẩm này?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <div class="p-5 bg-white rounded-4 shadow-sm w-100">
                            <i class="fas fa-box-open fa-4x text-light mb-3"></i>
                            <h5 class="text-secondary">Chưa có sản phẩm nào trong danh mục này!</h5>
                            <a href="/webbanhang/index.php?url=product" class="btn btn-danger rounded-pill px-4 mt-2">Xem tất cả đồ uống</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php include __DIR__ . '/../shaders/footer.php'; ?>