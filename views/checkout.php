<section class="container mt-4">
    <h2>Thanh toán</h2>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div id="orderItems">
                        <!-- Order items will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Thông tin thanh toán</h5>
                </div>
                <div class="card-body">
                    <form id="checkoutForm">
                        <div class="mb-3">
                            <label for="paymentMethod" class="form-label">Phương thức thanh toán</label>
                            <select class="form-control" id="paymentMethod" required>
                                <option value="">Chọn phương thức thanh toán</option>
                                <option value="momo">MoMo</option>
                                <option value="bank">Chuyển khoản ngân hàng</option>
                                <option value="cash">Tiền mặt</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú (không bắt buộc)</label>
                            <textarea class="form-control" id="note" rows="3" placeholder="Nhập ghi chú nếu cần..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Đặt hàng ngay</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Load order items from cart
function loadOrderItems() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const orderContainer = document.getElementById('orderItems');
    
    if (cart.length === 0) {
        orderContainer.innerHTML = '<p class="text-center">Không có sản phẩm nào trong giỏ hàng</p>';
        return;
    }
    
    let total = 0;
    let itemsHTML = '';
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        itemsHTML += `
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <strong>${item.name}</strong>
                    <br><small class="text-muted">Số lượng: ${item.quantity}</small>
                </div>
                <span>${itemTotal.toLocaleString()} VND</span>
            </div>
        `;
    });
    
    itemsHTML += `<hr><div class="d-flex justify-content-between"><strong>Tổng cộng:</strong><strong>${total.toLocaleString()} VND</strong></div>`;
    
    orderContainer.innerHTML = itemsHTML;
}

// Handle form submission
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const paymentMethod = document.getElementById('paymentMethod').value;
    const note = document.getElementById('note').value;
    
    if (!paymentMethod) {
        alert('Vui lòng chọn phương thức thanh toán!');
        return;
    }
    
    const formData = {
        paymentMethod: paymentMethod,
        note: note,
        cart: JSON.parse(localStorage.getItem('cart')) || []
    };
    
    // Hiển thị thông báo thành công
    alert('Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.');
    
    // Clear cart
    localStorage.removeItem('cart');
    updateCartCount();
    
    // Redirect to home page
    window.location.href = '?page=home';
});

function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const count = cart.reduce((sum, item) => sum + item.quantity, 0);
    const cartCountElement = document.getElementById('cartCount');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}

// Load order items when page loads
document.addEventListener('DOMContentLoaded', loadOrderItems);
</script> 