<?php include __DIR__ . '/../shaders/header.php'; ?>

<style>
    body { background-color: #f4f6f8; }
    .success-container { max-width: 600px; margin: 40px auto; }
    .success-card { background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); padding: 40px 30px; text-align: center; }
    
    /* Vòng tròn chứa icon check */
    .icon-circle { width: 90px; height: 90px; background-color: #e8f5e9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 25px; }
    .icon-circle i { font-size: 45px; color: #28a745; }
    
    /* Khung hiển thị mã đơn hàng */
    .order-id-box { background-color: #fdfafb; border: 2px dashed #f5c6cb; border-radius: 8px; padding: 15px 30px; margin: 25px auto; display: inline-block; min-width: 250px; }
    .text-highlight { color: #d70018; font-weight: bold; }
    
    /* Nút bấm */
    .btn-home { background-color: #d70018; color: white; border-radius: 8px; font-weight: bold; padding: 14px 24px; text-decoration: none; text-transform: uppercase; display: inline-block; width: 100%; margin-bottom: 10px; transition: 0.3s; }
    .btn-home:hover { background-color: #c50015; color: white; transform: translateY(-2px); }
</style>

<div class="container mt-4 mb-5">
    <div class="success-container">
        <div class="success-card">
            <div class="icon-circle">
                <i class="fas fa-check"></i>
            </div>
            
            <h3 class="fw-bold mb-3" style="color: #333;">Đặt hàng thành công!</h3>
            <p class="text-muted mb-2">Cảm ơn bạn đã tin tưởng và mua sắm tại hệ thống của chúng tôi.</p>
            <p class="text-muted small">Đơn hàng của bạn đã được ghi nhận và nhân viên sẽ sớm liên hệ để xác nhận giao hàng.</p>

            <?php 
            // Lấy ID từ URL do Controller truyền sang
            $order_id = $_GET['id'] ?? ''; 
            if (!empty($order_id)): 
            ?>
                <div class="order-id-box">
                    <span class="d-block text-muted small mb-1">Mã đơn hàng của bạn là</span>
                    <span class="fs-3 text-highlight">#<?php echo htmlspecialchars($order_id); ?></span>
                </div>
            <?php endif; ?>

            <div class="mt-4 pt-2 border-top">
                <a href="/webbanhang/index.php?url=product" class="btn btn-home shadow-sm mt-3">
                    <i class="fas fa-shopping-bag me-2"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../shaders/footer.php'; ?>