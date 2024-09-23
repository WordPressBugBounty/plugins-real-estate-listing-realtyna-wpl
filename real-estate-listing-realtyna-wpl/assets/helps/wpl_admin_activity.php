<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Define Tabs **/
$tabs = array();
$tabs['tabs'] = array();

$content = '<h3>'.wpl_esc::return_html_t('WPL Activity Manager').'</h3><p>'.wpl_esc::return_html_t('WPL is a modular system that runs certain activities to generate page outputs. You can change options on this page for the gallery, Google Maps, and other activities. ').'</p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_int', 'content'=>$content, 'title'=>wpl_esc::return_html_t('Introduction'));

$articles  = '';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/651/" target="_blank">'.wpl_esc::return_html_t("How do you modify and change settings for items in the WPL Activity Manager?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/611/" target="_blank">'.wpl_esc::return_html_t("How do I link images using the mailto option?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/584/" target="_blank">'.wpl_esc::return_html_t("How do I disable Google Maps in the listing pages?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/594/" target="_blank">'.wpl_esc::return_html_t("How to enable the Mortgage Calculator feature?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/598/" target="_blank">'.wpl_esc::return_html_t("How do I enable WPL contact forms/activities?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/688/" target="_blank">'.wpl_esc::return_html_t("How do I make the Walk Score responsive?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/547/" target="_blank">'.wpl_esc::return_html_t("Enabling/Disabling/Sorting WPL Activities.").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/567/" target="_blank">'.wpl_esc::return_html_t("How do I manage social media icons on the WPL front-end?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/571/" target="_blank">'.wpl_esc::return_html_t("How do I enable the Google Places feature?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/762/" target="_blank">'.wpl_esc::return_html_t("Widget areas and activity positions in the WPL.").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/758/" target="_blank">'.wpl_esc::return_html_t("How to show/hide property tags?").'</a></li>';

$content = '<h3>'.wpl_esc::return_html_t('Related KB Articles').'</h3><p>'.wpl_esc::return_html_t('Here you will find KB articles with information related to this page. You can come back to this section to find an answer to any questions that may come up.').'</p><p><ul>'.$articles.'</ul></p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_kb', 'content'=>$content, 'title'=>wpl_esc::return_html_t('KB Articles'));

return $tabs;