// Cart Integration for Click2Dine
// Add this script to cart.html after api-handler.js

document.addEventListener('DOMContentLoaded', function() {
    
    // ==================== PLACE ORDER HANDLER ====================
    const placeOrderBtn = document.querySelector('.place-order-btn');
    
    if (placeOrderBtn) {
        placeOrderBtn.addEventListener('click', async function() {
            // Get form values
            const nameInput = document.querySelector('input[placeholder="Enter your name"]');
            const phoneInput = document.querySelector('input[placeholder="03001234567"]');
            const addressInput = document.querySelector('input[placeholder="Street, House No., Area"]');
            
            if (!nameInput || !phoneInput || !addressInput) {
                alert('Form fields not found');
                return;
            }
            
            const name = nameInput.value.trim();
            const phone = phoneInput.value.trim();
            const address = addressInput.value.trim();
            
            // Validation
            if (!name || !phone || !address) {
                alert('Please fill in all delivery information');
                return;
            }
            
            if (phone.length < 11) {
                alert('Please enter a valid phone number (at least 11 digits)');
                return;
            }
            
            // Get cart items from sessionStorage
            const cart = JSON.parse(sessionStorage.getItem('cart')) || {};
            const cartItems = [];
            let total = 0;
            
            for (let itemName in cart) {
                const item = cart[itemName];
                cartItems.push({
                    name: item.name || itemName,
                    quantity: item.quantity,
                    unitPrice: item.unitPrice,
                    totalPrice: item.totalPrice,
                    restaurant: item.restaurant || 'Unknown',
                    image: item.img || item.image || ''
                });
                total += item.totalPrice;
            }
            
            if (cartItems.length === 0) {
                alert('Your cart is empty!');
                return;
            }
            
            // Disable button and show loading
            placeOrderBtn.disabled = true;
            placeOrderBtn.textContent = 'Placing Order...';
            
            try {
                // Call API to place order
                const result = await placeOrder(name, phone, address, total, cartItems);
                
                if (result.success) {
                    alert(`✅ Order placed successfully!\n\nOrder ID: ${result.order_id}\nTotal: Rs. ${total}\n\nYour order will be delivered soon!`);
                    
                    // Clear cart
                    sessionStorage.removeItem('cart');
                    
                    // Close checkout panel
                    const checkoutPanel = document.getElementById('checkout-panel');
                    if (checkoutPanel) {
                        checkoutPanel.classList.remove('open');
                    }
                    
                    // Reset form
                    nameInput.value = '';
                    phoneInput.value = '';
                    addressInput.value = '';
                    
                    // Reload page to show empty cart
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert('❌ Error placing order: ' + (result.message || 'Unknown error'));
                }
            } catch (error) {
                alert('❌ Network error. Please check your connection and try again.');
                console.error('Order placement error:', error);
            } finally {
                // Re-enable button
                placeOrderBtn.disabled = false;
                placeOrderBtn.textContent = 'Place Order';
            }
        });
    }
    
    // ==================== AUTO-FILL USER INFO ====================
    // If user is logged in, auto-fill their name
    const user = getCurrentUser();
    if (user) {
        const nameInput = document.querySelector('input[placeholder="Enter your name"]');
        if (nameInput && !nameInput.value) {
            nameInput.value = user.name;
        }
    }
});