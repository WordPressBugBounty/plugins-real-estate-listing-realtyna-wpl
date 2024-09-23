<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** define tips **/
$tips = array();
$index = 1;

$content = '<h3>'.wpl_esc::return_html_t('WPL Notifications').'</h3><p>'.wpl_esc::return_html_t("Here you can find WPL notifications. You can disable or enable the notification and edit the notifications' content and recipients.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.wrap.wpl-wp h2:first', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'))));

$next_page = 'wpl_admin_user_manager';
if(wpl_global::check_addon('pro')) $next_page = 'wpl_admin_log_manager';

$content = '<h3>'.wpl_esc::return_html_t('Edit Notifications').'</h3><p>'.wpl_esc::return_html_t("Click notification title to redirect to edit page of each notification.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'table.widefat.page tr:nth-child(1) td:nth-child(2) a', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next Menu'), 'code'=>'window.location.href = "admin.php?page='.$next_page.'&wpltour=1";'), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

return $tips;