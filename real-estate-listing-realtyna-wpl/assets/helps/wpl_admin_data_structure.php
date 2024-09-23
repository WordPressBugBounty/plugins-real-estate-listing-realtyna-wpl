<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Define Tabs **/
$tabs = array();
$tabs['tabs'] = array();

$content = '<h3>'.wpl_esc::return_html_t('WPL Data Structure').'</h3><p>'.wpl_esc::return_html_t("Use this menu to manage property types (i.e. home, villa, etc.), listing types (i.e. for rent, for sale, etc.), room types, sort options, and units of measurement (acre, mile, currency, etc.).").'</p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_int', 'content'=>$content, 'title'=>wpl_esc::return_html_t('Introduction'));

$articles  = '';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/542/" target="_blank">'.wpl_esc::return_html_t("How do I enable/disable WPL measuring units such as acre, mile, currency, etc?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/583/" target="_blank">'.wpl_esc::return_html_t("How to manage (Add/Edit/Delete) Listing Types or Property Types").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/687/" target="_blank">'.wpl_esc::return_html_t("How do I set WPL sort options?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/532/" target="_blank">'.wpl_esc::return_html_t("How to translate WPL Data structure (Property Types, Listing Types, Sort Options, Flex fields, etc) texts").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/629/" target="_blank">'.wpl_esc::return_html_t("How do I set categories for listing and property types?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/577/" target="_blank">'.wpl_esc::return_html_t("How do I add a new category for listing and property types?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/662/" target="_blank">'.wpl_esc::return_html_t("How do I add new units of measurement (acre, mile, currency, etc.) to WPL?").'</a></li>';

$content = '<h3>'.wpl_esc::return_html_t('Related KB Articles').'</h3><p>'.wpl_esc::return_html_t('Here you will find KB articles with information related to this page. You can come back to this section to find an answer to any questions that may come up.').'</p><p><ul>'.$articles.'</ul></p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_kb', 'content'=>$content, 'title'=>wpl_esc::return_html_t('KB Articles'));

return $tabs;