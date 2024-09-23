<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** define tips **/
$tips = array();

$content = '<h3>'.wpl_esc::return_html_t('WPL Activities').'</h3><p>'.wpl_esc::return_html_t('Activity is an internal WPL widget used to show some parts of the WPL interface. For example, Google Maps, Agent info, Property gallery etc. are shown by using an activity in WPL. Activities are contained inside of WPL views (not WordPress sidebars).').'</p>';
$tips[] = array('id'=>1, 'selector'=>'.wrap.wpl-wp h2:first', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'))));

$content = '<h3>'.wpl_esc::return_html_t('Filter Activities').'</h3><p>'.wpl_esc::return_html_t('You can filter activities here. Lets search for "Google"!').'</p>';
$tips[] = array('id'=>2, 'selector'=>'#activity_manager_filter', 'content'=>$content, 'position'=>array('edge'=>'left', 'align'=>'center'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'), 'code'=>'wplj("#activity_manager_filter").val("Google");wplj("#activity_manager_filter").trigger("keyup");'), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Manage Activities').'</h3><p>'.wpl_esc::return_html_t('You can toggle activities simply by clicking on an action icon.').'</p>';
$tips[] = array('id'=>3, 'selector'=>'#wpl_actions_td_thead', 'content'=>$content, 'position'=>array('edge'=>'right', 'align'=>'center'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next Menu'), 'code'=>'window.location.href = "admin.php?page=wpl_admin_notifications&wpltour=1";'), 3=>array('label'=>wpl_esc::return_html_t('Previous'), 'code'=>'wplj("#activity_manager_filter").val("");wplj("#activity_manager_filter").trigger("keyup");')));

return $tips;