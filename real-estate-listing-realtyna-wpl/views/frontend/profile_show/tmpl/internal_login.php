<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.login', true, ($this->wplraw ? false : true));

_wpl_import('libraries.addon_membership');
_wpl_import('libraries.vendors.hybridauth.src.autoload');

$config = wpl_addon_membership::social_login_config();
$hybridauth = new Hybridauth\Hybridauth($config);
?>

<div class="wpl-user-login-register" id="wpl_user_login_register_container">
    <div id="wpl_user_login_register_form_container">

        <form id="wpl_user_login_register_form" class="wpl-gen-form-wp wpl-login-register-form-wp" method="POST" onsubmit="wpl_user_logreg(); return false;">

            <div class="wpl-util-hidden" id="wpl_user_login_register_form_register">

                <div class="wpl-gen-form-row">
                    <label for="wpl_lr_email"><?php wpl_esc::html_t('Email'); ?>: </label>
                    <input type="email" name="email" id="wpl_lr_email" autocomplete="off" />
                </div>
                
                <div class="wpl-gen-form-row">
                    <label for="wpl_lr_first_name"><?php wpl_esc::html_t('First Name'); ?>: </label>
                    <input type="text" name="first_name" id="wpl_lr_first_name" autocomplete="off" />
                </div>
                
                <div class="wpl-gen-form-row">
                    <label for="wpl_lr_last_name"><?php wpl_esc::html_t('Last Name'); ?>: </label>
                    <input type="text" name="last_name" id="wpl_lr_last_name" autocomplete="off" />
                </div>
                
                <div class="wpl-gen-form-row">
                    <label for="wpl_lr_mobile"><?php wpl_esc::html_t('Mobile'); ?>: </label>
                    <input type="text" name="mobile" id="wpl_lr_mobile" autocomplete="off" />
                </div>

            </div>

            <div id="wpl_user_login_register_form_login" class="wpl-gen-form-wp">

                <div class="wpl-gen-form-row">
                    <label for="wpl_lr_username"><?php wpl_esc::html_t('Username'); ?>: </label>
                    <input type="text" name="username" id="wpl_lr_username" autocomplete="off" />
                </div>

                <div class="wpl-gen-form-row">
                    <label for="wpl_lr_password"><?php wpl_esc::html_t('Password'); ?>: </label>
                    <input type="password" name="password" id="wpl_lr_password" autocomplete="off" />
                </div>

            </div>
            <div class="wpl-gen-form-row last wpl-row wpl-expanded clearfix">
                <div id="wpl_user_login_register_toggle" class="wpl-toggle-btns wpl-small-6 wpl-medium-6 wpl-large-6 wpl-column">
                    <div class="wpl-util-hidden" id="wpl_user_login_register_toggle_register">
                        <?php wpl_esc::e(sprintf(wpl_esc::return_html_t('Already a member? %s'), '<a href="#" class="wpl-gen-link" onclick="wpl_user_logreg_toggle(\'login\');return false;">'.wpl_esc::return_html_t('Login').'</a>')); ?>
                    </div>
                    <div id="wpl_user_login_register_toggle_login">
                        <?php wpl_esc::e(sprintf(wpl_esc::return_html_t('Not a member? %s'), '<a href="#" class="wpl-gen-link" onclick="wpl_user_logreg_toggle(\'register\');return false;">'.wpl_esc::return_html_t('Register').'</a>')); ?>
                    </div>
                </div>
                <div class="wpl-util-right wpl-small-6 wpl-medium-6 wpl-large-6 wpl-column">
                    <button type="submit" class="wpl-gen-btn-1 wpl-util-hidden" id="wpl_user_login_register_register_submit"><?php wpl_esc::html_t('Register & Continue'); ?></button>
                    <button type="submit" class="wpl-gen-btn-1" id="wpl_user_login_register_login_submit"><?php wpl_esc::html_t('Login & Continue'); ?></button>
                </div>
            </div>

            <input type="hidden" name="wpl_function" value="login" id="wpl_user_logreg_guest_method" />
            <input type="hidden" name="token" id="wpl_user_login_register_token" value="<?php wpl_esc::attr($this->wpl_security->token()); ?>" />

        </form>

        <div class="wpl-social-login-container">
            <?php foreach ($hybridauth->getProviders() as $name) : ?>
                <?php
                $title = $name;
                $name = strtolower($name);
                if($name == 'linkedinopenid') $title = 'LinkedIn';
                ?>
                <div class="wpl-login-form-row">
                    <div class="wpl_<?php wpl_esc::attr($name); ?>_sign_in">
                        <a href="<?php wpl_esc::url($config['callback'] . "&provider=" . $name); ?>" class="wpl_<?php wpl_esc::attr($name); ?>_sign_in_btn">
                            <span class="wpl_<?php wpl_esc::attr($name); ?>_sign_in_inner"><?php wpl_esc::e(sprintf(wpl_esc::return_html_t('Login with %s'), $title)); ?></span>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="wpl_user_login_register_form_show_messages"></div>

    </div>
</div>