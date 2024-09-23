<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** define tips **/
$tips = array();
$index = 1;

$content = '<h3>'.wpl_esc::return_html_t('WPL Payments').'</h3><p>'.wpl_esc::return_html_t("Here you can find all payment configurations and transactions.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.wrap.wpl-wp h2:first', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'))));

$content = '<h3>'.wpl_esc::return_html_t('Payment gateways').'</h3><p>'.wpl_esc::return_html_t("Enable your desired payment gateways and set the credentials here.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'#wpl_payments_options_tab', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Payment Transactions').'</h3><p>'.wpl_esc::return_html_t("Also you can see all transactions here.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'#wpl_payments_transactions_tab', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next Menu'), 'code'=>'window.location.href = "admin.php?page=wpl_admin_user_manager&wpltour=1";'), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

return $tips;