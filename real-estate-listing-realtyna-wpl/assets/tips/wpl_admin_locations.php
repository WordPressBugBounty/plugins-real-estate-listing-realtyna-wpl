<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** define tips **/
$tips = array();

$content = '<h3>'.wpl_esc::return_html_t('WPL Locations').'</h3><p>'.wpl_esc::return_html_t('WPL is designed for world wide usage so every user around the world can use and localize it per their needs. You can select your desired country using this menu and disable default country simply.').'</p>';
$tips[] = array('id'=>1, 'selector'=>'.wrap.wpl-wp h2:first', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'))));

$content = '<h3>'.wpl_esc::return_html_t('See All countries').'</h3><p>'.wpl_esc::return_html_t('You can see all WPL countries here.').'</p>';
$tips[] = array('id'=>2, 'selector'=>'.location_tools .button:first', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Search on countries').'</h3><p>'.wpl_esc::return_html_t('Also you can search on listed locations here.').'</p>';
$tips[] = array('id'=>3, 'selector'=>'#wpl_search_location', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Add new locations').'</h3><p>'.wpl_esc::return_html_t("Don't you see your desired location? Add it to here.").'</p>';
$tips[] = array('id'=>4, 'selector'=>'#wpl_add_location_item', 'content'=>$content, 'position'=>array('edge'=>'left', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Add new locations').'</h3><p>'.wpl_esc::return_html_t("WPL uses a hierarchy system for locations. You can go to the next level here.").'</p>';
$tips[] = array('id'=>5, 'selector'=>'table.widefat.page tr:nth-child(1) td:nth-child(3) a', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next Menu'), 'code'=>'window.location.href = "admin.php?page=wpl_admin_settings&wpltour=1";'), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

return $tips;