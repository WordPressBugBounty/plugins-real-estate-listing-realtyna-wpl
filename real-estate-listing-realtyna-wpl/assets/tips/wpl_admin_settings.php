<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** define tips **/
$tips = array();
$index = 1;

$content = '<h3>'.wpl_esc::return_html_t('WPL Settings').'</h3><p>'.wpl_esc::return_html_t("Almost all of WPL options included in this menu so you can configure all WPL and its addons in one menu.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.wrap.wpl-wp h2:first', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'))));

$content = '<h3>'.wpl_esc::return_html_t('Categories').'</h3><p>'.wpl_esc::return_html_t("Use the categories to navigate between different sections.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'#wpl_slide_label_id1', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'), 'code'=>'jQuery("#wpl_slide_label_id5").trigger("click");'), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Notifications Options').'</h3><p>'.wpl_esc::return_html_t('For example you can change sender email and sender name of WPL emails here!').'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'#wpl_st_67', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

if(wpl_global::check_addon('pro'))
{
    $content = '<h3>'.wpl_esc::return_html_t('UI Customizer').'</h3><p>'.wpl_esc::return_html_t("Also you can customize frontend user interface of WPL here.").'</p>';
    $tips[] = array('id'=>$index++, 'selector'=>'#wpl_slide_label_id11', 'content'=>$content, 'position'=>array('edge'=>'left', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'), 'code'=>'jQuery("#wpl_slide_label_id5").trigger("click");')));
}

$content = '<h3>'.wpl_esc::return_html_t('Maintenance').'</h3><p>'.wpl_esc::return_html_t("You can clear the WPL cache data here! You may need to do it sometimes after changing some settings.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.wpl-maintenance-container', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Server Requirements').'</h3><p>'.wpl_esc::return_html_t("Please make sure that your server meets the requirements first.").'</p>';
$tips[] = array('id'=>$index++, 'selector'=>'.wpl-requirements-container', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next Menu'), 'code'=>'window.location.href = "admin.php?page=wpl_admin_activity&wpltour=1";'), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

return $tips;