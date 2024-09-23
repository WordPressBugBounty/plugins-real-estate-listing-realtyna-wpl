<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Define Tabs **/
$tabs = array();
$tabs['tabs'] = array();

$content = '<h3>'.wpl_esc::return_html_t('WPL Flex').'</h3><p>'.wpl_esc::return_html_t('With this menu you can manage WPL fields. You can add new fields, manage existing ones, and sort based on your personal preferences. ').'</p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_int', 'content'=>$content, 'title'=>wpl_esc::return_html_t('Introduction'));

$articles  = '';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/541/" target="_blank">'.wpl_esc::return_html_t("How do I create new fields and edit current fields in WPL? How do I use the Flex menu?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/539/" target="_blank">'.wpl_esc::return_html_t("How do I create property type and listing type fields in WPL?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/597/" target="_blank">'.wpl_esc::return_html_t("How do I hide zero values in the property listing and other WPL pages?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/540/" target="_blank">'.wpl_esc::return_html_t("Bedrooms, rooms, and other fields are not appearing for new listing or property types.").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/587/" target="_blank">'.wpl_esc::return_html_t("How do I change the maximum file upload size for images, videos and attachments?").'</a></li>';
$articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/592/" target="_blank">'.wpl_esc::return_html_t('How do I add "call for price" in WPL PRO?').'</a></li>';

$content = '<h3>'.wpl_esc::return_html_t('Related KB Articles').'</h3><p>'.wpl_esc::return_html_t('Here you will find KB articles with information related to this page. You can come back to this section to find an answer to any questions that may come up.').'</p><p><ul>'.$articles.'</ul></p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_kb', 'content'=>$content, 'title'=>wpl_esc::return_html_t('KB Articles'));

return $tabs;