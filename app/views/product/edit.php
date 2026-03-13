<?php include __DIR__ . '/../shaders/header.php'; ?>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="fw-bold mb-0 text-warning text-uppercase">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa đồ uống
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="/webbanhang/index.php?url=product/update" method="POST" enctype="multipart/form-data">

                        <input type="hidden" name="id" value="<?php echo $product->id; ?>">
                        <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($product->image ?? ''); ?>">

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Tên đồ uống</label>
                            <input type="text" class="form-control rounded-3" id="name" name="name"
                                value="<?php echo htmlspecialchars($product->name); ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label fw-bold">Giá bán (VND)</label>
                                <input type="number" class="form-control rounded-3" id="price" name="price" min="10000"
                                    value="<?php echo htmlspecialchars($product->price); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label fw-bold">Loại đồ uống</label>
                                <select class="form-select rounded-3" id="category_id" name="category_id" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat->id; ?>" <?php echo ($cat->id == $product->category_id) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($cat->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold d-block">Hình ảnh hiện tại</label>
                            <?php if (!empty($product->image)): ?>
                                <img src="/webbanhang/<?php echo htmlspecialchars($product->image); ?>" class="img-thumbnail mb-2" style="max-width: 150px;">
                            <?php else: ?>
                                <p class="text-muted">Chưa có ảnh</p>
                            <?php endif; ?>

                            <input class="form-control rounded-3" type="file" id="image" name="image" accept="image/*">
                            <div class="form-text">Để trống nếu không muốn thay đổi ảnh.</div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea class="form-control rounded-3" id="description" name="description" rows="5"><?php echo htmlspecialchars($product->description ?? ''); ?></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning px-4 py-2 rounded-pill fw-bold">
                                <i class="fas fa-save me-2"></i>Cập nhật
                            </button>
                            <a href="/webbanhang/index.php?url=product" class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                                Hủy bỏ
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../shaders/footer.php'; ?>