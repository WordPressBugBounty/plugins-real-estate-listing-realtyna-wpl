<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Define Tabs **/
$tabs = array();
$tabs['tabs'] = array();

$content = '<h3>'.wpl_esc::return_html_t('WPL Settings').'</h3><p>'.wpl_esc::return_html_t("With this menu, you can change most your website settings. If there is a setting you're unsure about, please check the KB Articles for more information.").'</p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_int', 'content'=>$content, 'title'=>wpl_esc::return_html_t('Introduction'));

$articles  = '';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/558/" target="_blank">'.wpl_esc::return_html_t("What is the difference between the image resize methods?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/582/" target="_blank">'.wpl_esc::return_html_t("How do I use the WPL Geo Meta Tag feature?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/643/" target="_blank">'.wpl_esc::return_html_t("How do I enable the WPL RSS feed feature?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/574/" target="_blank">'.wpl_esc::return_html_t("How do I adjust the WPL address pattern?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/593/" target="_blank">'.wpl_esc::return_html_t("How do I enable the WPL Watermark feature?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/708/" target="_blank">'.wpl_esc::return_html_t("How to change main color of the WPL frontend?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/573/" target="_blank">'.wpl_esc::return_html_t("What is the user auto add feature in WPL Membership settings?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/699/" target="_blank">'.wpl_esc::return_html_t("Why aren't my thumbnail photos changing in my listings?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/705/" target="_blank">'.wpl_esc::return_html_t("How do I add a print option on a listing page?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/568/" target="_blank">'.wpl_esc::return_html_t("How do I change the WPL date format?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/776/" target="_blank">'.wpl_esc::return_html_t("How to enable new WPL Cronjob system?").'</a></li>';

$content = '<h3>'.wpl_esc::return_html_t('Related KB Articles').'</h3><p>'.wpl_esc::return_html_t('Here you will find KB articles with information related to this page. You can come back to this section to find an answer to any questions that may come up.').'</p><p><ul>'.$articles.'</ul></p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_kb', 'content'=>$content, 'title'=>wpl_esc::return_html_t('KB Articles'));

return $tabs;