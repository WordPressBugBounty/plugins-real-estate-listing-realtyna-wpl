<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** define tips **/
$tips = array();
$index = 1;

$content = '<h3>'.wpl_esc::return_html_t('WPL Logs').'</h3><p>'.wpl_esc::return_html_t("WPL uses an advanced log system that logs almost everything. If needed you can enable the logs from WPL Settings menu to see the logs here. It's not recommended to enable the logs if you don't need it.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.wrap.wpl-wp h2:first', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'))));

$content = '<h3>'.wpl_esc::return_html_t('Search Logs').'</h3><p>'.wpl_esc::return_html_t("You can search on logs using this complete search form.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.log_tools', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'middle'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$next_page = 'wpl_admin_user_manager';
if(wpl_global::check_addon('pro')) $next_page = 'wpl_admin_payments';

$content = '<h3>'.wpl_esc::return_html_t('Delete Logs').'</h3><p>'.wpl_esc::return_html_t("If you want to remove all logs, you can do it using this button simply.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.delete_button .button', 'content'=>$content, 'position'=>array('edge'=>'right', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next Menu'), 'code'=>'window.location.href = "admin.php?page='.$next_page.'&wpltour=1";'), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

return $tips;