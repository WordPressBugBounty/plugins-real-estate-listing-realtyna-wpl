<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** define tips **/
$tips = array();
$index = 1;

$content = '<h3>'.wpl_esc::return_html_t('Fill your own Profile').'</h3><p>'.wpl_esc::return_html_t("Here you can update your own profile information.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.wrap.wpl-wp h2:first', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'))));

$content = '<h3>'.wpl_esc::return_html_t('Finalize the Form').'</h3><p>'.wpl_esc::return_html_t("After filling your profile information, don't forget to finalize the form.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'#wpl_profile_finalize_button', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next Menu'), 'code'=>'window.location.href = "admin.php?page=wpl_admin_add_listing&wpltour=1";'), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

return $tips;