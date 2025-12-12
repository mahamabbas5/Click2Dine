// API Base URL - Update this to match your XAMPP setup
const API_BASE_URL = 'http://localhost/click2dine/api';

// ==================== AUTHENTICATION ====================

async function signup(name, email, password) {
    try {
        const response = await fetch(`${API_BASE_URL}/signup.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: name,
                email: email,
                password: password
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Store user info in localStorage
            localStorage.setItem('user', JSON.stringify({
                id: data.user_id,
                name: name,
                email: email
            }));
        }
        
        return data;
    } catch (error) {
        console.error('Signup error:', error);
        return { success: false, message: 'Network error. Please try again.' };
    }
}

async function login(email, password) {
    try {
        const response = await fetch(`${API_BASE_URL}/login.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: email,
                password: password
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Store user info in localStorage
            localStorage.setItem('user', JSON.stringify({
                id: data.user_id,
                name: data.name,
                email: email
            }));
        }
        
        return data;
    } catch (error) {
        console.error('Login error:', error);
        return { success: false, message: 'Network error. Please try again.' };
    }
}

function logout() {
    localStorage.removeItem('user');
    return { success: true, message: 'Logged out successfully' };
}

function getCurrentUser() {
    const userStr = localStorage.getItem('user');
    return userStr ? JSON.parse(userStr) : null;
}

function isLoggedIn() {
    return getCurrentUser() !== null;
}

// ==================== ORDERS ====================

async function placeOrder(customerName, customerPhone, deliveryAddress, total, cartItems) {
    try {
        const user = getCurrentUser();
        
        const response = await fetch(`${API_BASE_URL}/place_order.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                user_id: user ? user.id : null,
                customer_name: customerName,
                customer_phone: customerPhone,
                delivery_address: deliveryAddress,
                total: total,
                items: cartItems
            })
        });
        
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Order error:', error);
        return { success: false, message: 'Network error. Please try again.' };
    }
}

async function getUserOrders() {
    try {
        const user = getCurrentUser();
        if (!user) {
            return { success: false, message: 'Please login to view orders' };
        }
        
        const response = await fetch(`${API_BASE_URL}/get_orders.php?user_id=${user.id}`, {
            method: 'GET'
        });
        
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Get orders error:', error);
        return { success: false, message: 'Network error. Please try again.' };
    }
}

// ==================== RESTAURANTS ====================

async function getRestaurants() {
    try {
        const response = await fetch(`${API_BASE_URL}/get_restaurants.php`, {
            method: 'GET'
        });
        
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Get restaurants error:', error);
        return { success: false, message: 'Network error. Please try again.' };
    }
}

// ==================== MENU ====================

async function getMenuItems(restaurantId) {
    try {
        const response = await fetch(`${API_BASE_URL}/get_menu.php?restaurant_id=${restaurantId}`, {
            method: 'GET'
        });
        
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Get menu error:', error);
        return { success: false, message: 'Network error. Please try again.' };
    }
}

// ==================== UI UPDATES ====================

function updateAuthUI() {
    const user = getCurrentUser();
    const signupBtn = document.getElementById('open-signup');
    const loginBtn = document.getElementById('open-login');
    
    if (user && signupBtn && loginBtn) {
        // User is logged in - show name and logout
        signupBtn.textContent = user.name;
        signupBtn.style.cursor = 'default';
        signupBtn.style.pointerEvents = 'none';
        
        loginBtn.textContent = 'Logout';
        loginBtn.onclick = (e) => {
            e.preventDefault();
            logout();
            alert('Logged out successfully');
            window.location.reload();
        };
    }
}

// Initialize on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', updateAuthUI);
} else {
    updateAuthUI();
}