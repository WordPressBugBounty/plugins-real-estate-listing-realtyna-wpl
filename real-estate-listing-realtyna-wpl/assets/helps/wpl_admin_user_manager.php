<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Define Tabs **/
$tabs = array();
$tabs['tabs'] = array();

$content = '<h3>'.wpl_esc::return_html_t('User Manager').'</h3><p>'.wpl_esc::return_html_t("Here you can manage WPL users. You can remove users, change their membership, and modify their access. ").'</p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_int', 'content'=>$content, 'title'=>wpl_esc::return_html_t('Introduction'));

$articles  = '';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/543/" target="_blank">'.wpl_esc::return_html_t("Adding new users/agents to WPL").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/690/" target="_blank">'.wpl_esc::return_html_t("How do I disable WPL notifications for a certain user/agent?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/672/" target="_blank">'.wpl_esc::return_html_t("How do I update an agent profile?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/565/" target="_blank">'.wpl_esc::return_html_t("What are the different access options for WPL users?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/701/" target="_blank">'.wpl_esc::return_html_t("After installing WPL, there is a error message: \"You don't have access to this menu!\" in the WPL menu.").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/545/" target="_blank">'.wpl_esc::return_html_t("No agent is appearing in the agent/profile page. How do I hide an agent/profile in listing pages?").'</a></li>';

$content = '<h3>'.wpl_esc::return_html_t('Related KB Articles').'</h3><p>'.wpl_esc::return_html_t('Here you will find KB articles with information related to this page. You can come back to this section to find an answer to any questions that may come up.').'</p><p><ul>'.$articles.'</ul></p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_kb', 'content'=>$content, 'title'=>wpl_esc::return_html_t('KB Articles'));

return $tabs;