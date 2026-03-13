<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0 text-danger text-uppercase">
                        <i class="fas fa-plus-circle me-2"></i>Thêm đồ uống mới
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="/webbanhang/index.php?url=product/save" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Tên đồ uống</label>
                            <input type="text" class="form-control rounded-3" id="name" name="name" placeholder="Ví dụ: Trà Sữa Trân Châu Đường Đen" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label fw-bold">Giá bán (VND)</label>
                                <input type="number" class="form-control rounded-3" id="price" name="price" min="10000" placeholder="35000" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label fw-bold">Loại đồ uống</label>
                                <select class="form-select rounded-3" id="category_id" name="category_id" required>
                                    <?php if (isset($categories)): ?>
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat->id ?>"><?= htmlspecialchars($cat->name) ?></option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="">-- Chọn loại --</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">Hình ảnh đồ uống</label>
                            <input class="form-control rounded-3" type="file" id="image" name="image" accept="image/*">
                            <div class="form-text">Nên chọn ảnh đẹp, rõ nét, nền sáng để thu hút khách hàng.</div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea class="form-control rounded-3" id="description" name="description" rows="5" placeholder="Ví dụ: Trà sữa trân châu đường đen nguyên chất, topping đầy đặn, thơm ngon, uống lạnh cực đã..."></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger px-4 py-2 rounded-pill fw-bold">
                                <i class="fas fa-save me-2"></i>Lưu đồ uống
                            </button>
                            <a href="/webbanhang/index.php?url=product" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                                Quay lại
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../shaders/footer.php'; ?>