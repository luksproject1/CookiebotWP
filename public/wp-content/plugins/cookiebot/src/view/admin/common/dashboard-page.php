<?php

use cybot\cookiebot\settings\templates\Header;
use cybot\cookiebot\settings\templates\Main_Tabs;

use cybot\cookiebot\settings\pages\Settings_Page;
use cybot\cookiebot\lib\Cookiebot_WP;

/**
 * @var string $cbid
 * @var string $cb_wp
 * @var string $europe_icon
 * @var string $usa_icon
 * @var string $check_icon
 * @var string $link_icon
 */

$header    = new Header();
$main_tabs = new Main_Tabs();

$header->display();
?>
<div class="cb-body">
    <div class="cb-wrapper">
        <?php $main_tabs->display('dashboard'); ?>
    </div>
</div>

<!-- Add modals at the end of the file -->
<div id="login-modal" class="cb-modal">
    <div class="cb-modal-content">
        <div class="cb-modal-header">
            <img src="<?php echo esc_url(CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/images/cookiebot-logo.png'); ?>" alt="Cookiebot Logo" class="cb-modal-logo">
        </div>
        <div class="cb-modal-body split-layout">
            <!-- Left side - Form -->
            <div class="form-section">
                <h2><?php echo esc_html__('Connect your account', 'cookiebot'); ?></h2>
                <p class="cb-modal-description">
                    <?php echo esc_html__('Enter the ID of your account to quickly connect it with the plugin.', 'cookiebot'); ?>
                </p>
                <form id="connect-form" class="cb-form" method="post" action="options.php">
                    <?php settings_fields('cookiebot'); ?>
                    <div class="cb-form-group">
                        <input type="text" 
                               id="cookiebot-cbid" 
                               name="cookiebot-cbid"
                               placeholder="Settings ID or Domain Group ID" 
                               value="<?php echo esc_attr($cbid); ?>"
                               required>
                        <div class="cookiebot-cbid-check"></div>
                    </div>
                    <a href="https://support.usercentrics.com/hc/en-us/articles/18097606499100" target="_blank" class="help-link">
                        <?php echo esc_html__('How to find your Usercentrics Settings ID', 'cookiebot'); ?>
                    </a>
                    <a href="https://support.cookiebot.com/hc/en-us/articles/4405643234194" target="_blank" class="help-link">
                        <?php echo esc_html__('How to find your Cookiebot CMP Domain Group ID', 'cookiebot'); ?>
                    </a>
                    <button type="submit" id="connect-submit" class="cb-btn cb-main-btn cb-btn-full disabled">
                        <?php echo esc_html__('Continue', 'cookiebot'); ?>
                    </button>
                </form>
                <p class="cb-modal-footer">
                    <?php echo esc_html__("Don't have an account?", 'cookiebot'); ?>
                    <a href="#" id="switch-to-signup"><?php echo esc_html__('Create one here', 'cookiebot'); ?></a>
                </p>
            </div>
            <!-- Right side - Trial Info -->
            <div class="trial-section">
                <h2>Start a <span class="highlight">14-Day Free</span><br>Trial today!</h2>
                <p>Benefit from our premium trial period and get a consent banner in seconds. <a href="#" class="learn-more">Learn more</a></p>
                <div class="banner-preview">
                    <img src="<?php echo esc_url(CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/images/banner-preview.png'); ?>" alt="Banner Preview">
                </div>
                <div class="trial-features">
                    <h3>✨ Enjoy all premium features without risk ✨</h3>
                    <ul>
                        <li>💳 No credit card required</li>
                        <li>⏰ Ends automatically after 14 days</li>
                        <li>🔓 Access to all features</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="signup-modal" class="cb-modal">
    <div class="cb-modal-content">
        <div class="cb-modal-header">
            <img src="<?php echo esc_url(CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/images/cookiebot-logo.png'); ?>" alt="Cookiebot Logo" class="cb-modal-logo">
            <span class="cb-modal-close">&times;</span>
        </div>
        <div class="cb-modal-body split-layout">
            <!-- Left side - Form -->
            <div class="form-section">
                <h2><?php echo esc_html__('Create account', 'cookiebot'); ?></h2>
                <p class="cb-modal-description">
                    <?php echo esc_html__('Enter your domain, your email and set your password to sign up and have your banner live in seconds.', 'cookiebot'); ?>
                </p>
                <form id="signup-form" class="cb-form">
                    <div class="cb-form-group">
                        <label for="domain"><?php echo esc_html__('Domain', 'cookiebot'); ?></label>
                        <input type="url" id="domain" name="domain" value="<?php echo esc_url(get_site_url()); ?>" required>
                    </div>
                    <div class="cb-form-group">
                        <label for="email"><?php echo esc_html__('Email address', 'cookiebot'); ?></label>
                        <input type="email" id="email" name="email" value="<?php echo esc_attr(get_option('admin_email')); ?>" required>
                    </div>
                    <div class="cb-form-group">
                        <label for="password"><?php echo esc_html__('Password', 'cookiebot'); ?></label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="cb-btn cb-main-btn cb-btn-full">
                        <?php echo esc_html__('Continue', 'cookiebot'); ?>
                    </button>
                </form>
                <p class="cb-modal-footer">
                    <?php echo esc_html__('Already have an account?', 'cookiebot'); ?>
                    <a href="#" id="switch-to-login">
                        <?php echo esc_html__('Connect here', 'cookiebot'); ?>
                    </a>
                </p>
            </div>
            <!-- Right side - Trial Info -->
            <div class="trial-section">
                <h2>Start a <span class="highlight">14-Day Free</span><br>Trial today!</h2>
                <p>Benefit from our premium trial period and get a consent banner in seconds. <a href="#" class="learn-more">Learn more</a></p>
                <div class="banner-preview">
                    <img src="<?php echo esc_url(CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/images/banner-preview.png'); ?>" alt="Banner Preview">
                </div>
                <div class="trial-features">
                    <h3>✨ Enjoy all the premium features ✨</h3>
                    <ul>
                        <li>💳 No credit card required</li>
                        <li>⏰ Ends automatically after 14 days</li>
                        <li>🔓 Access to all features</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginModal = document.getElementById('login-modal');
    const signupModal = document.getElementById('signup-modal');
    const switchToSignup = document.getElementById('switch-to-signup');
    const switchToLogin = document.getElementById('switch-to-login');
    const closeButtons = document.getElementsByClassName('cb-modal-close');
    const mainContent = document.querySelector('.cb-body');

    function openModal(modal) {
        // Hide all modals first
        loginModal.style.display = 'none';
        signupModal.style.display = 'none';
        // Then show the requested modal
        modal.style.display = 'block';
        mainContent.classList.add('modal-open');
    }

    function closeModal(modal) {
        <?php if (empty($cbid)) : ?>
            return; // Prevent closing if no CBID
        <?php else : ?>
            modal.style.display = 'none';
            mainContent.classList.remove('modal-open');
        <?php endif; ?>
    }

    // Hide close buttons if no CBID
    <?php if (empty($cbid)) : ?>
        Array.from(closeButtons).forEach(button => {
            button.style.display = 'none';
        });
    <?php endif; ?>

    switchToSignup.addEventListener('click', (e) => {
        e.preventDefault();
        openModal(signupModal);
    });

    switchToLogin.addEventListener('click', (e) => {
        e.preventDefault();
        openModal(loginModal);
    });

    <?php if (!empty($cbid)) : ?>
        Array.from(closeButtons).forEach(button => {
            button.addEventListener('click', () => {
                closeModal(loginModal);
                closeModal(signupModal);
            });
        });

        window.addEventListener('click', (e) => {
            if (e.target === loginModal) closeModal(loginModal);
            if (e.target === signupModal) closeModal(signupModal);
        });
    <?php endif; ?>

    // Auto-show signup modal on page load if no CBID
    <?php if (empty($cbid)) : ?>
        setTimeout(() => {
            openModal(signupModal);
        }, 100);
    <?php endif; ?>

    // CBID validation
    const cbidInput = document.getElementById('cookiebot-cbid');
    echo
    const cbidCheck = document.querySelector('.cookiebot-cbid-check');
    const submitButton = document.getElementById('connect-submit');
});
</script>

<?php
wp_enqueue_script(
    'cookiebot-account',
    CYBOT_COOKIEBOT_PLUGIN_URL . 'assets/js/backend/account.js',
    array('jquery'),
    Cookiebot_WP::COOKIEBOT_PLUGIN_VERSION,
    true
);

wp_localize_script('cookiebot-account', 'cookiebot_account', array(
    'nonce' => wp_create_nonce('cookiebot_create_account')
));

//settings ID length XxxXXXxx