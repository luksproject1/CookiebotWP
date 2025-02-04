document.addEventListener('DOMContentLoaded', function() {
    const signupForm = document.getElementById('signup-form');
    if (!signupForm) return;

    signupForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('action', 'cookiebot_create_account');
        formData.append('nonce', cookiebot_account.nonce);
        formData.append('email', document.getElementById('email').value);
        formData.append('password', document.getElementById('password').value);
        formData.append('domain', document.getElementById('domain').value);

        try {
            const response = await fetch(ajaxurl, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            console.log('API Response:', data);
            
            if (data.success) {
                alert('Account created successfully! Please connect your account.');
                window.openModal(loginModal);
            } else {
                console.error('API Error:', data);
                alert('Error creating account: ' + (data.data?.message || 'Please try again'));
            }
        } catch (error) {
            console.error('Fetch Error:', error);
            console.error('Error:', error);
            alert('Error creating account. Please try again.');
        }
    });
}); 