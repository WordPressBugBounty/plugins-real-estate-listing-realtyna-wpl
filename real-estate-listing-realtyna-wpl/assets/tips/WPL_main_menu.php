<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** define tips **/
$tips = array();

$content = '<h3>'.wpl_esc::return_html_t('WPL Dashboard').'</h3><p>'.wpl_esc::return_html_t('Welcome to WPL dashboard. Here, you will see information about WPL and its add-ons.').'</p>';
$tips[] = array('id'=>1, 'selector'=>'.wrap.wpl-wp h2:first', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'))));

$content = '<h3>'.wpl_esc::return_html_t('Optional add-ons').'</h3><p>'.wpl_esc::return_html_t('WPL has some optional add-ons for extending its functionality. You can download and install them, if needed.').'</p>';
$tips[] = array('id'=>2, 'selector'=>'#wpl_dashboard_ni_addons h3', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('WPL Change-log').'</h3><p>'.wpl_esc::return_html_t('Browse WPL change-log.').'</p>';
$tips[] = array('id'=>3, 'selector'=>'#wpl_dashboard_changelog h3', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Update WPL PRO and its add-ons').'</h3><p>'.wpl_esc::return_html_t('Here you can update WPL Pro and its add-ons.').'</p>';
$tips[] = array('id'=>4, 'selector'=>'#wpl_dashboard_side_addons h3', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('WPL manual and Support').'</h3><p>'.wpl_esc::return_html_t('Here you can download WPL manual and check its KB articles. You can find answer of your questions here. Please feel free to open a support ticket if you couldn\'t find an answer to your question in WPL manual and KB articles.').'</p>';
$tips[] = array('id'=>5, 'selector'=>'#wpl_dashboard_side_support h3', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Browse KB articles').'</h3><p>'.wpl_esc::return_html_t('If you have a question, take a look through the following KB articles (they are searchable). In most cases, you will find your answer in minutes.').'</p>';
$tips[] = array('id'=>6, 'selector'=>'#wpl_dashboard_side_announce h3', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next Menu'), 'code'=>'window.location.href = "admin.php?page=wpl_admin_data_structure&wpltour=1";'), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

return $tips;