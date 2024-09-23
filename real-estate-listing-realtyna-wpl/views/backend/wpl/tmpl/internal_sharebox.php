<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="side-6 side-share" id="wpl_dashboard_side_share">
    <div class="panel-wp">
        <h3><?php wpl_esc::html_t('Share / Review'); ?></h3>
        <div class="panel-body">
            <div class="share-box-container">
                <a class="wpl_review_wp" href="https://wordpress.org/plugins/real-estate-listing-realtyna-wpl/" target="_blank"><?php wpl_esc::html_t('Inspire us by Rating at'); ?>, <span class="wpl_sharebox_icon-wp"></span><?php wpl_esc::html_t('community'); ?></a>
                <div class="wpl-dashboard-social-icons">
                    <p><?php wpl_esc::html_t('Share WPL with Others'); ?></p>
                    <ul>
                        <li>
                            <a class="wpl_dashboard_fb" href="<?php wpl_esc::url('https://www.facebook.com/sharer/sharer.php?u=https://wordpress.org/plugins/real-estate-listing-realtyna-wpl/'); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php wpl_esc::html_t('Share on Facebook', 'real-estate-listing-realtyna-wpl'); ?>"><?php wpl_esc::html_t('Share on Facebook', 'real-estate-listing-realtyna-wpl'); ?></a>
                        </li>
                        <li>
                            <a class="wpl_dashboard_google" href="<?php wpl_esc::url('https://plus.google.com/share?url=https://wordpress.org/plugins/real-estate-listing-realtyna-wpl/'); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php wpl_esc::html_t('Google+', 'real-estate-listing-realtyna-wpl'); ?>"><?php wpl_esc::html_t('Share on Google+', 'real-estate-listing-realtyna-wpl'); ?></a>
                        </li>
                        <li>
                            <a class="wpl_dashboard_twit" href="<?php wpl_esc::url('https://twitter.com/share?url=https://wordpress.org/plugins/real-estate-listing-realtyna-wpl/'); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php wpl_esc::html_t('Tweet', 'real-estate-listing-realtyna-wpl'); ?>"><?php wpl_esc::html_t('Share on Twitter', 'real-estate-listing-realtyna-wpl'); ?></a>
                        </li>
                        <li>
                            <a class="wpl_dashboard_in" href="<?php wpl_esc::url('https://www.linkedin.com/shareArticle?mini=true&url=https://wordpress.org/plugins/real-estate-listing-realtyna-wpl/'); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600'); return false;" title="<?php wpl_esc::html_t('Share on Linkedin', 'real-estate-listing-realtyna-wpl'); ?>"><?php wpl_esc::html_t('Share on Linkedin', 'real-estate-listing-realtyna-wpl'); ?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>