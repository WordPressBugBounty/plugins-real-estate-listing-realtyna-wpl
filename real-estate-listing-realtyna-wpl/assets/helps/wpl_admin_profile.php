<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Define Tabs **/
$tabs = array();
$tabs['tabs'] = array();

$content = '<h3>'.wpl_esc::return_html_t('My Profile').'</h3><p>'.wpl_esc::return_html_t("With this menu, you can add your business and personal information to your profile. ").'</p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_int', 'content'=>$content, 'title'=>wpl_esc::return_html_t('Introduction'));

if(wpl_users::is_administrator())
{
    $articles  = '';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/672/" target="_blank">'.wpl_esc::return_html_t("How do I update an agent profile?").'</a></li>';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/543/" target="_blank">'.wpl_esc::return_html_t("Adding new users/agents to WPL").'</a></li>';

    $content = '<h3>'.wpl_esc::return_html_t('Related KB Articles').'</h3><p>'.wpl_esc::return_html_t('Here you will find KB articles with information related to this page. You can come back to this section to find an answer to any questions that may come up.').'</p><p><ul>'.$articles.'</ul></p>';
    $tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_kb', 'content'=>$content, 'title'=>wpl_esc::return_html_t('KB Articles'));
}

return $tabs;