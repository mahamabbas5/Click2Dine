// Authentication Integration for Click2Dine
// Add this script after api-handler.js in index.html and other pages

document.addEventListener('DOMContentLoaded', function() {
    
    // ==================== SIGNUP FORM ====================
    const signupForm = document.querySelector('#signup-modal form');
    if (signupForm) {
        signupForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const fullName = this.querySelector('input[placeholder="Full Name"]').value.trim();
            const email = this.querySelector('input[type="email"]').value.trim();
            const password = this.querySelector('input[type="password"]').value;
            
            // Validation
            if (!fullName || !email || !password) {
                alert('Please fill in all fields');
                return;
            }
            
            if (password.length < 6) {
                alert('Password must be at least 6 characters');
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Please enter a valid email address');
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Creating Account...';
            submitBtn.disabled = true;
            
            try {
                const result = await signup(fullName, email, password);
                
                if (result.success) {
                    alert('Account created successfully! Welcome, ' + fullName + '!');
                    document.getElementById('signup-modal').style.display = 'none';
                    updateAuthUI();
                    this.reset();
                } else {
                    alert('Error: ' + (result.message || 'Failed to create account'));
                }
            } catch (error) {
                alert('Network error. Please check your connection and try again.');
                console.error(error);
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        });
    }
    
    // ==================== LOGIN FORM ====================
    const loginForm = document.querySelector('#login-modal form');
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = this.querySelector('input[type="email"]').value.trim();
            const password = this.querySelector('input[type="password"]').value;
            
            if (!email || !password) {
                alert('Please enter both email and password');
                return;
            }
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Logging In...';
            submitBtn.disabled = true;
            
            try {
                const result = await login(email, password);
                
                if (result.success) {
                    alert('Welcome back, ' + result.name + '!');
                    document.getElementById('login-modal').style.display = 'none';
                    updateAuthUI();
                    this.reset();
                } else {
                    alert('Error: ' + (result.message || 'Invalid credentials'));
                }
            } catch (error) {
                alert('Network error. Please check your connection and try again.');
                console.error(error);
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        });
    }
    
    // ==================== MODAL CONTROLS ====================
    // These might already exist in your HTML, but this ensures they work
    const signupModal = document.getElementById('signup-modal');
    const loginModal = document.getElementById('login-modal');
    
    // Open signup modal
    const openSignupBtn = document.getElementById('open-signup');
    if (openSignupBtn && !getCurrentUser()) {
        openSignupBtn.onclick = () => {
            if (signupModal) signupModal.style.display = 'flex';
        };
    }
    
    // Open login modal
    const openLoginBtn = document.getElementById('open-login');
    if (openLoginBtn && !getCurrentUser()) {
        openLoginBtn.onclick = () => {
            if (loginModal) loginModal.style.display = 'flex';
        };
    }
    
    // Close signup modal
    const closeSignupBtn = document.getElementById('close-signup');
    if (closeSignupBtn) {
        closeSignupBtn.onclick = () => {
            if (signupModal) signupModal.style.display = 'none';
        };
    }
    
    // Close login modal
    const closeLoginBtn = document.getElementById('close-login');
    if (closeLoginBtn) {
        closeLoginBtn.onclick = () => {
            if (loginModal) loginModal.style.display = 'none';
        };
    }
    
    // Close modal when clicking outside
    if (signupModal) {
        signupModal.onclick = (e) => {
            if (e.target === signupModal) {
                signupModal.style.display = 'none';
            }
        };
    }
    
    if (loginModal) {
        loginModal.onclick = (e) => {
            if (e.target === loginModal) {
                loginModal.style.display = 'none';
            }
        };
    }
});