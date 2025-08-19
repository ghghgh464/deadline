<!DOCTYPE html>
<html lang="vi" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MMO Services - Trang thương mại điện tử sản phẩm số</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1db954;
            --text-color: #333;
            --bg-color: #f8f9fa;
            --card-bg: #fff;
            --border-color: #dee2e6;
        }

        [data-bs-theme="dark"] {
            --primary-color: #1ed760;
            --text-color: #fff;
            --bg-color: #121212;
            --card-bg: #1a1a1a;
            --border-color: #2d2d2d;
        }

        body {
            font-family: 'Noto Sans', 'Inter', 'Roboto', Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
        }

        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('assets/images/banner.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            margin-bottom: 40px;
            color: white;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .hero-section p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .product-card {
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: var(--card-bg);
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .product-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin: 0 auto 20px;
            display: block;
            border-radius: 8px;
        }

        .product-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: var(--text-color);
        }

        .product-price {
            font-size: 1.1rem;
            color: var(--primary-color);
            font-weight: bold;
            margin-bottom: 15px;
        }

        .add-to-cart {
            width: 100%;
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-to-cart:hover {
            background-color: #1ed760;
        }

        .cart-badge {
            position: relative;
            display: inline-block;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .theme-switch {
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 5px;
            color: var(--text-color);
            transition: color 0.3s;
        }

        .theme-switch:hover {
            color: var(--primary-color);
        }

        .features-section {
            padding: 60px 0;
            background-color: var(--bg-color);
        }

        .feature-card {
            text-align: center;
            padding: 30px;
            border-radius: 10px;
            background-color: var(--card-bg);
            margin-bottom: 30px;
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .product-card {
                margin-bottom: 15px;
            }
            .hero-section {
                padding: 60px 0;
            }
            .hero-section h1 {
                font-size: 2rem;
            }
        }

        /* Messenger Button Style */
        .messenger-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #0084ff;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .messenger-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
        }

        .messenger-btn i {
            font-size: 30px;
        }

        /* Tooltip */
        .messenger-tooltip {
            position: absolute;
            right: 75px;
            background: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            color: #333;
            white-space: nowrap;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .messenger-btn:hover .messenger-tooltip {
            opacity: 1;
        }

        /* Contact Button Style */
        .contact-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
        }

        .contact-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #28a745;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: all 0.3s;
        }

        .contact-icon:hover {
            transform: scale(1.1);
        }

        .contact-menu {
            position: absolute;
            bottom: 80px;
            right: 0;
            background: white;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: none;
            min-width: 220px;
        }

        .contact-menu.show {
            display: block;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .contact-item {
            display: flex;
            align-items: center;
            padding: 10px;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 5px;
            transition: all 0.2s;
        }

        .contact-item:hover {
            background: #f8f9fa;
        }

        .contact-item i {
            width: 30px;
            font-size: 20px;
        }

        .contact-item.messenger i {
            color: #0084ff;
        }

        .contact-item.telegram i {
            color: #0088cc;
        }

        .contact-item.phone i {
            color: #28a745;
        }

        .contact-item.email i {
            color: #dc3545;
        }

        .contact-text {
            margin-left: 10px;
            font-size: 14px;
        }

        .copy-btn {
            margin-left: auto;
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 5px;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .copy-btn:hover {
            opacity: 1;
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>MMO Services - Trang thương mại điện tử sản phẩm số</h1>
            <p>Khám phá các sản phẩm chất lượng với giá cả hợp lý</p>
            <a href="#products" class="btn btn-success btn-lg">Xem sản phẩm</a>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <h2 class="text-center mb-5">Tại sao chọn chúng tôi?</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>An toàn & Bảo mật</h3>
                        <p>Bảo mật thông tin khách hàng tuyệt đối</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>Hỗ trợ 24/7</h3>
                        <p>Luôn sẵn sàng hỗ trợ khách hàng mọi lúc</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h3>Giá cả hợp lý</h3>
                        <p>Cam kết giá tốt nhất trên thị trường</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="container mt-4" id="products">
        <h2 class="text-center mb-4">Sản Phẩm Của Chúng Tôi</h2>
        <div class="row" id="productList">
            <?php if (isset($products) && is_array($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="product-card">
                            <?php if (!empty($product['image'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                            <?php else: ?>
                                <img src="assets/images/placeholder.jpg" alt="Placeholder" class="product-image">
                            <?php endif; ?>
                            <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <p class="product-price"><?php echo number_format($product['price']); ?> VND</p>
                            <p class="text-muted"><?php echo htmlspecialchars($product['description']); ?></p>
                            <a href="?page=cart&action=add&id=<?php echo $product['id']; ?>" class="add-to-cart btn btn-success">
                                <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">Không có sản phẩm nào để hiển thị.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Toast container for notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Contact Button -->
    <div class="contact-button">
        <div class="contact-icon" onclick="toggleContactMenu(event)">
            <i class="fas fa-comments"></i>
        </div>
        <div class="contact-menu" id="contactMenu">
            <a href="https://m.me/100064906453678" target="_blank" class="contact-item messenger">
                <i class="fab fa-facebook-messenger"></i>
                <span class="contact-text">Messenger</span>
            </a>
            <a href="https://t.me/huyceo999" target="_blank" class="contact-item telegram">
                <i class="fab fa-telegram"></i>
                <span class="contact-text">Telegram</span>
            </a>
            <div class="contact-item phone">
                <i class="fas fa-phone"></i>
                <span class="contact-text">0363254644</span>
                <button class="copy-btn" onclick="event.stopPropagation(); copyToClipboard('0363254644')">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
            <div class="contact-item email">
                <i class="fas fa-envelope"></i>
                <span class="contact-text">nhimhung412@gmail.com</span>
                <button class="copy-btn" onclick="event.stopPropagation(); copyToClipboard('nhimhung412@gmail.com')">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Messenger Button -->
    <a href="https://m.me/100064906453678" target="_blank" class="messenger-btn">
        <i class="fab fa-facebook-messenger"></i>
        <span class="messenger-tooltip">Nhắn tin với chúng tôi</span>
    </a>

    <!-- Messenger Chat Plugin -->
    <div id="fb-root"></div>
    <div id="fb-customer-chat" class="fb-customerchat"></div>
    <script>
        var chatbox = document.getElementById('fb-customer-chat');
        chatbox.setAttribute("page_id", "171849862823300"); // Thay YOUR_PAGE_ID bằng ID Facebook Page của bạn
        chatbox.setAttribute("attribution", "biz_inbox");

        window.fbAsyncInit = function() {
            FB.init({
                xfbml: true,
                version: 'v18.0'
            });
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

        // Product data
        const products = [
            {
                id: 1,
                name: "Netflix Premium",
                price: 79000,
                image: "assets/images/netflix.png",
                description: "Tài khoản Netflix Premium 1 tháng"
            },
            {
                id: 2,
                name: "Spotify Premium",
                price: 50000,
                image: "assets/images/spotify premium.png",
                description: "Tài khoản Spotify Premium 1 tháng"
            },
            {
                id: 3,
                name: "Xbox Game Pass Ultimate",
                price: 360000,
                image: "assets/images/xbox gamepass ultimate.png",
                description: "Tài khoản Xbox Game Pass Ultimate 1 tháng"
            },
            {
                id: 4,
                name: "Canva Pro",
                price: 350000,
                image: "assets/images/canva pro.png",
                description: "Tài khoản Canva Pro 1 năm"
            },
            {
                id: 5,
                name: "CapCut Pro",
                price: 250000,
                image: "assets/images/capcut pro.png",
                description: "Tài khoản CapCut Pro 1 năm"
            },
            {
                id: 6,
                name: "Google Drive",
                price: 0,
                image: "assets/images/google drive.png",
                description: "Giá cả inbox"
            },
            {
                id: 7,
                name: "Microsoft 365",
                price: 0,
                image: "assets/images/microsoft 365.png",
                description: "Crack và set up"
            },
            {
                id: 8,
                name: "Active Windows",
                price: 0,
                image: "assets/images/active windows.png",
                description: "Crack và set up"
            }
        ];

        // Display products
        function displayProducts() {
            const productList = document.getElementById('productList');
            productList.innerHTML = products.map(product => `
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="product-card">
                        <img src="${product.image}" alt="${product.name}" class="product-image">
                        <h3 class="product-title">${product.name}</h3>
                        <p class="product-price">${product.price.toLocaleString()} VND</p>
                        <p class="text-muted">${product.description}</p>
                        <button class="add-to-cart" onclick="addToCart(${product.id})">
                            <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                        </button>
                        </div>
                </div>
            `).join('');
        }

        // Add to cart function
        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            if (!product) return;

            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const existingItem = cart.find(item => item.id === productId);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    image: product.image,
                    quantity: 1
                });
            }
            
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
            showToast(`Đã thêm ${product.name} vào giỏ hàng`);
        }

        // Update cart count
        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cartCount').textContent = count;
        }

        // Show toast notification
        function showToast(message) {
            const toastContainer = document.querySelector('.toast-container');
            const toast = document.createElement('div');
            toast.className = 'toast';
            toast.innerHTML = `
                <div class="toast-header">
                    <strong class="me-auto">Thông báo</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            `;
            toastContainer.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        // Toggle theme
        function toggleTheme() {
            const html = document.querySelector('html');
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            const themeIcon = document.querySelector('.theme-switch i');
            themeIcon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }

        // Contact Menu Functions
        function toggleContactMenu(event) {
            event.stopPropagation();
            const menu = document.getElementById('contactMenu');
            menu.classList.toggle('show');
        }

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('Đã sao chép: ' + text);
            }).catch(err => {
                showToast('Không thể sao chép. Vui lòng thử lại.');
            });
        }

        // Close contact menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('contactMenu');
            const button = document.querySelector('.contact-button');
            if (!button.contains(event.target)) {
                menu.classList.remove('show');
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            displayProducts();
            updateCartCount();

            // Set initial theme
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme) {
                document.querySelector('html').setAttribute('data-bs-theme', currentTheme);
                const themeIcon = document.querySelector('.theme-switch i');
                themeIcon.className = currentTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }
        });
    </script>
</body>
</html>