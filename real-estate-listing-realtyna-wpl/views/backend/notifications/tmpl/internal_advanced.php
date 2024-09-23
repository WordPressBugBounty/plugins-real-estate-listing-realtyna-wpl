<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="noti-advance-wp">
    <?php if(wpl_global::check_addon('pro')): ?>
    <section class="wpl-outer">
        <header>
            <aside class="wpl-left">
                <?php wpl_esc::html_t('Available memberships'); ?>
            </aside>
            <aside class="wpl-center">
                <div id="loading_membership_recipients"></div>
            </aside>
            <aside class="wpl-right">
                <?php wpl_esc::html_t('Included memberships'); ?>
            </aside>
        </header>
        <section>
            <aside class="wpl-left">
                <select name="memberships" id="memberships" multiple="multiple">
                    <?php
                    foreach($this->memberships_array as $membership)
						wpl_esc::e( '<option value="'. wpl_esc::return_attr($membership->id).'">'. wpl_esc::return_html($membership->membership_name) .'</option>');
                    ?>
                </select>
            </aside>
            <aside class="wpl-center">
                <a id="add_memberships" class="button button-primary wpl-add" name="add_memberships" onclick="add_recipients('memberships','additional_memberships','');" />
                <?php wpl_esc::html_t('Add'); ?>
                </a>
                <a id="remove_memberships" class="button wpl-remove" name="remove_memberships" onclick="remove_recipients('memberships','additional_memberships','');" >
                    <?php wpl_esc::html_t('Remove'); ?>
                </a>
            </aside>
            <aside class="wpl-right">
                <select name="additional_memberships" id="additional_memberships" multiple>
                    <?php
                    foreach($this->additional_memberships as $membership_id)
                    {
                        if(trim($membership_id ?? '') == '') continue;
                        wpl_esc::e('<option value="'. wpl_esc::return_attr($this->memberships[$membership_id]->id) .'">'. wpl_esc::return_html($this->memberships[$membership_id]->membership_name) .'</option>');
                    }
                    ?>
                </select>
            </aside>
        </section>
    </section>
    <?php endif; ?>
    <section class="wpl-outer">
        <header>
            <aside class="wpl-left">
                <?php wpl_esc::html_t('Available users'); ?>
            </aside>
            <aside class="wpl-center">
                <div id="loading_additional_recipients"></div>
            </aside>
            <aside class="wpl-right">
                <?php wpl_esc::html_t('Included users'); ?>
            </aside>
        </header>
        <section>
            <aside class="wpl-left">
                <select name="users" id="users" multiple="multiple">
                    <?php
                    foreach($this->users_array as $user)
						wpl_esc::e( '<option value="'. wpl_esc::return_attr($user->id) .'">'. wpl_esc::return_html($user->user_login) .'</option>');
                    ?>
                </select>
            </aside>
            <aside class="wpl-center">
                <a id="add_recipient" class="button button-primary wpl-add" name="add_memberships" onclick="add_recipients('users','additional_users','');" />
                    <?php wpl_esc::html_t('Add'); ?>
                </a>
                <a id="remove_recipient" class="button wpl-remove" name="remove_memberships" onclick="remove_recipients('users','additional_users','');" >
                    <?php wpl_esc::html_t('Remove'); ?>
                </a>
            </aside>
            <aside class="wpl-right">
                <select name="additional_users" id="additional_users" multiple>
                    <?php
                    foreach($this->additional_users as $user_id)
                    {
                        if(trim($user_id ?? '') == '') continue;
						wpl_esc::e('<option value="'. wpl_esc::return_attr($this->users[$user_id]->id) .'">'. wpl_esc::return_html($this->users[$user_id]->user_login) .'</option>');
                    }
                    ?>
                </select>
            </aside>
        </section>
    </section>
    <section class="wpl-outer">
        <header>
            <aside class="wpl-left">
                <?php wpl_esc::html_t('Email address'); ?>
            </aside>
            <aside class="wpl-center">
                <div id="loading_email_recipients"></div>
            </aside>
            <aside class="wpl-right">
                <?php wpl_esc::html_t('Included emails'); ?>
            </aside>
        </header>
        <section>
            <aside class="wpl-left">
                <input type="text" name="email_address" id="email_address" />
            </aside>
            <aside class="wpl-center">
                <a id="add_email" class="button button-primary wpl-add" name="add_memberships" onclick="add_recipients('emails','additional_emails','email_recipients');" />
                    <?php wpl_esc::html_t('Add'); ?>
                </a>
                <a id="remove_email" class="button wpl-remove" name="remove_memberships" onclick="remove_recipients('emails','additional_emails','email_recipients');" >
                    <?php wpl_esc::html_t('Remove'); ?>
                </a>
            </aside>
            <aside class="wpl-right">
                <select name="additional_emails" id="additional_emails" multiple>
                    <?php
                    foreach($this->additional_emails as $email)
                    {
                        if(trim($email ?? '') == '') continue;
                        wpl_esc::e('<option value="'.wpl_esc::return_attr($email).'">'.wpl_esc::return_html($email).'</option>');
                    }
                    ?>
                </select>
            </aside>
        </section>
    </section>
</div>