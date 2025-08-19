<section class="products">
    <h2>Sản phẩm của chúng tôi</h2>
    <div class="product-grid">
        <?php if (empty($products)): ?>
            <p>Không tìm thấy sản phẩm nào.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="price"><?php echo number_format($product['price']); ?> VNĐ</p>
                    <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
                    <a href="?page=cart&action=add&id=<?php echo $product['id']; ?>" class="btn">Thêm vào giỏ hàng</a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (isset($totalPages) && $totalPages > 1): ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php
                $currentPage = $_GET['page_num'] ?? 1;
                $pageQuery = $_GET;
                unset($pageQuery['page_num']);
                $queryString = http_build_query($pageQuery);
                ?>
                
                <?php if ($currentPage > 1): ?>
                    <li class="page-item"><a class="page-link" href="?<?php echo $queryString; ?>&page_num=<?php echo ($currentPage - 1); ?>">Trước</a></li>
                <?php else: ?>
                    <li class="page-item disabled"><span class="page-link">Trước</span></li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <li class="page-item active"><span class="page-link"><?php echo $i; ?></span></li>
                    <?php else: ?>
                        <li class="page-item"><a class="page-link" href="?<?php echo $queryString; ?>&page_num=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <li class="page-item"><a class="page-link" href="?<?php echo $queryString; ?>&page_num=<?php echo ($currentPage + 1); ?>">Sau</a></li>
                <?php else: ?>
                    <li class="page-item disabled"><span class="page-link">Sau</span></li>
                <?php endif; ?>
            </ul>
        </nav>
    <?php endif; ?>
</section>  
