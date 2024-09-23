<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Define Tabs **/
$tabs = array();
$tabs['tabs'] = array();

$content = '<h3>'.wpl_esc::return_html_t('Listing Manager').'</h3><p>'.wpl_esc::return_html_t("Here you can see and manage your listings. You can add a new listing, and modify and disable existing listings.").'</p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_int', 'content'=>$content, 'title'=>wpl_esc::return_html_t('Introduction'));

if(wpl_users::is_administrator())
{
    $articles  = '';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/546/" target="_blank">'.wpl_esc::return_html_t("How do I change the listing user/agent?").'</a></li>';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/753/" target="_blank">'.wpl_esc::return_html_t("How to purge all Unfinalized listings?").'</a></li>';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/727/" target="_blank">'.wpl_esc::return_html_t("How to clone a listing?").'</a></li>';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/701/" target="_blank">'.wpl_esc::return_html_t("After installing WPL, there is an error message: \"You don't have access to this part!\" in the WPL menu.").'</a></li>';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/615/" target="_blank">'.wpl_esc::return_html_t("My added listings are not showing in listing pages in frontend.").'</a></li>';

    $content = '<h3>'.wpl_esc::return_html_t('Related KB Articles').'</h3><p>'.wpl_esc::return_html_t('Here you will find KB articles with information related to this page. You can come back to this section to find an answer to any questions that may come up.').'</p><p><ul>'.$articles.'</ul></p>';
    $tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_kb', 'content'=>$content, 'title'=>wpl_esc::return_html_t('KB Articles'));
}

return $tabs;