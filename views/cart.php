<section class="container mt-4">
    <h2>Giỏ hàng</h2>
    <div id="cartItems">
        <!-- Cart items will be loaded here via JavaScript -->
    </div>
    <div class="text-center mt-4">
        <a href="?page=home" class="btn btn-primary">Tiếp tục mua sắm</a>
        <a href="?page=checkout" class="btn btn-success">Thanh toán</a>
    </div>
</section>

<script>
// Load cart items from localStorage
function loadCart() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartContainer = document.getElementById('cartItems');
    
    if (cart.length === 0) {
        cartContainer.innerHTML = '<p class="text-center">Giỏ hàng trống</p>';
        return;
    }
    
    let total = 0;
    let cartHTML = '<div class="table-responsive"><table class="table"><thead><tr><th>Sản phẩm</th><th>Giá</th><th>Số lượng</th><th>Tổng</th><th>Thao tác</th></tr></thead><tbody>';
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        cartHTML += `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="${item.image}" alt="${item.name}" style="width: 50px; height: 50px; object-fit: contain;" class="me-3">
                        <span>${item.name}</span>
                    </div>
                </td>
                <td>${item.price.toLocaleString()} VND</td>
                <td>
                    <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.id}, -1)">-</button>
                    <span class="mx-2">${item.quantity}</span>
                    <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${item.id}, 1)">+</button>
                </td>
                <td>${itemTotal.toLocaleString()} VND</td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="removeFromCart(${item.id})">Xóa</button>
                </td>
            </tr>
        `;
    });
    
    cartHTML += '</tbody></table></div>';
    cartHTML += `<div class="text-end"><h4>Tổng cộng: ${total.toLocaleString()} VND</h4></div>`;
    
    cartContainer.innerHTML = cartHTML;
}

function updateQuantity(productId, change) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    const item = cart.find(item => item.id === productId);
    
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            cart = cart.filter(item => item.id !== productId);
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        loadCart();
        updateCartCount();
    }
}

function removeFromCart(productId) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cart = cart.filter(item => item.id !== productId);
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart();
    updateCartCount();
}

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const count = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountElement = document.getElementById('cartCount');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}

// Load cart when page loads
document.addEventListener('DOMContentLoaded', loadCart);
</script> 