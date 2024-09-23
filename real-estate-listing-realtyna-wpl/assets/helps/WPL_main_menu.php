<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Define Tabs **/
$tabs = array();
$tabs['tabs'] = array();

$content = '<h3>'.wpl_esc::return_html_t('WPL Dashboard').'</h3><p>'.wpl_esc::return_html_t('Welcome to WPL dashboard. Here, you will see information about WPL and its add-ons, WPL manuals, KB articles and some statistics about your website. You can update WPL PRO and its add-ons from this menu too.').'</p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_int', 'content'=>$content, 'title'=>wpl_esc::return_html_t('Introduction'));

$content = '<h3>'.wpl_esc::return_html_t('Documentation').'</h3><p><ul><li><a href="http://wpl.realtyna.com/wassets/wpl-manual.pdf" target="_blank">'.wpl_esc::return_html_t('WPL Manual').'</a></li><li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/List/Index/28/wpl---wordpress-property-listing" target="_blank">'.wpl_esc::return_html_t('WPL KB articles').'</a></li></ul></p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_doc', 'content'=>$content, 'title'=>wpl_esc::return_html_t('Documentation'));

$articles  = '';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Knowledgebase/Article/View/557/28/how-to-update-wpl-pro" target="_blank">'.wpl_esc::return_html_t('How do I update WPL PRO?').'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/561/" target="_blank">'.wpl_esc::return_html_t('How do you upgrade WPL basic to WPL PRO?').'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/703/" target="_blank">'.wpl_esc::return_html_t('How do I download my purchased products?').'</a></li>';

$content = '<h3>'.wpl_esc::return_html_t('Related KB Articles').'</h3><p>'.wpl_esc::return_html_t('Here you will find KB articles with information related to this page. You can come back to this section to find an answer to any questions that may come up.').'</p><p><ul>'.$articles.'</ul></p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_kb', 'content'=>$content, 'title'=>wpl_esc::return_html_t('KB Articles'));

return $tabs;