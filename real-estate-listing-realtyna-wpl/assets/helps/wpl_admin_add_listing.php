<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Define Tabs **/
$tabs = array();
$tabs['tabs'] = array();

$content = '<h3>'.wpl_esc::return_html_t('Add/Edit Listing').'</h3><p>'.wpl_esc::return_html_t("With this menu you can add a new listing or modify an existing listing.").'</p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_int', 'content'=>$content, 'title'=>wpl_esc::return_html_t('Introduction'));

if(wpl_users::is_administrator())
{
    $articles  = '';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/556/" target="_blank">'.wpl_esc::return_html_t("How do I upload multiple videos for a listing and have them appear in a single property page?").'</a></li>';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/587/" target="_blank">'.wpl_esc::return_html_t("How do I change the maximum file upload size for images, videos and attachments?").'</a></li>';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/528/" target="_blank">'.wpl_esc::return_html_t('What Does the "Location Data is Mandatory" Error Mean in Add/Edit Listing Menu?').'</a></li>';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/538/" target="_blank">'.wpl_esc::return_html_t("Bedrooms, Rooms, Price, type etc. is not showing on Listing Wizard or Search Widget!").'</a></li>';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/539/" target="_blank">'.wpl_esc::return_html_t("How do I create property type and listing type specific fields in WPL?").'</a></li>';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/564/" target="_blank">'.wpl_esc::return_html_t("How do I adjust the property Geo point?").'</a></li>';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/734/" target="_blank">'.wpl_esc::return_html_t("How to receive a notification when a new listing submitted by an agent?").'</a></li>';
    $articles .= '<li><a href="https://support.realtyna.com/index.php?/Default/Knowledgebase/Article/View/649/" target="_blank">'.wpl_esc::return_html_t("How to hide property address?").'</a></li>';

    $content = '<h3>'.wpl_esc::return_html_t('Related KB Articles').'</h3><p>'.wpl_esc::return_html_t('Here you will find KB articles with information related to this page. You can come back to this section to find an answer to any questions that may come up.').'</p><p><ul>'.$articles.'</ul></p>';
    $tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab_kb', 'content'=>$content, 'title'=>wpl_esc::return_html_t('KB Articles'));
}

return $tabs;