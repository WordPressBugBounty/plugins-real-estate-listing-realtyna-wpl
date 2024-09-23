<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** define tips **/
$tips = array();
$index = 1;

$content = '<h3>'.wpl_esc::return_html_t('Manage WPL Agents').'</h3><p>'.wpl_esc::return_html_t("Here you see all WPL agents. You can remove the agents or add new agents to WPL using this menu. Also you're able to manage the agent accesse by editing each agent.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.wrap.wpl-wp h2:first', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'))));

$content = '<h3>'.wpl_esc::return_html_t('User Data Structure').'</h3><p>'.wpl_esc::return_html_t("You can manage user profile fields here. You can manage existing fields or add new fields simply using WPL Flex system.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.setting-toolbar-btn', 'content'=>$content, 'position'=>array('edge'=>'right', 'align'=>'right'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Search Users').'</h3><p>'.wpl_esc::return_html_t("Use this form to perform a search on WPL agents.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'#wpl_users_search_form', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'middle'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Manage User Accesses').'</h3><p>'.wpl_esc::return_html_t("Click edit icon of each user to change the accesses of the user.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'table.widefat.page tr:nth-child(1) td.wpl_manager_td .icon-edit', 'content'=>$content, 'position'=>array('edge'=>'right', 'align'=>'middle'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Remove Agent').'</h3><p>'.wpl_esc::return_html_t("For removing agents from WPL, you can use this icon. This will remove the users only from WPL.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'table.widefat.page tr:nth-child(1) td.wpl_manager_td .icon-recycle', 'content'=>$content, 'position'=>array('edge'=>'right', 'align'=>'middle'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next Menu'), 'code'=>'window.location.href = "admin.php?page=wpl_admin_profile&wpltour=1";'), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

return $tips;