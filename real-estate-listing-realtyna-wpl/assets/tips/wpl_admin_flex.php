<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** define tips **/
$tips = array();

$content = '<h3>'.wpl_esc::return_html_t('WPL Flex').'</h3><p>'.wpl_esc::return_html_t('WPL is Flexibile, it means you can add your desired data fields into data categories simply or manage existing fields. Enjoy WPL Flex!').'</p>';
$tips[] = array('id'=>1, 'selector'=>'.wrap.wpl-wp h2:first', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'))));

$content = '<h3>'.wpl_esc::return_html_t('WPL Kind/Entities').'</h3><p>'.wpl_esc::return_html_t('Switch between WPL kind/entities to manage kind fields.').'</p>';
$tips[] = array('id'=>2, 'selector'=>'.wpl-tabs .wpl-selected-tab', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Data Categories').'</h3><p>'.wpl_esc::return_html_t('Each field in WPL has a data category. Choose a category to manage the related fields. ').'</p>';
$tips[] = array('id'=>3, 'selector'=>'.side-tabs-wp .active', 'content'=>$content, 'position'=>array('edge'=>'left', 'align'=>'center'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'), 'code'=>'wplj("#wpl_slide_label_id1").trigger("click");'), 3=>array('label'=>wpl_esc::return_html_t('Previous'), 'code'=>'')));

$content = '<h3>'.wpl_esc::return_html_t('Mandatory/Optional fields').'</h3><p>'.wpl_esc::return_html_t('Select or deselect the star icon to make a field mandatory or option.').'</p>';
$tips[] = array('id'=>4, 'selector'=>'table.widefat.page tr:nth-child(1) td:nth-child(7)', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'center'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Edit fields').'</h3><p>'.wpl_esc::return_html_t('Use the edit icon to modify field details.').'</p>';
$tips[] = array('id'=>5, 'selector'=>'table.widefat.page tr:nth-child(1) td:nth-child(8)', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'center'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Disable/Enable fields').'</h3><p>'.wpl_esc::return_html_t("If you don't need a field, disable it. You can enable it again, if needed.").'</p>';
$tips[] = array('id'=>6, 'selector'=>'table.widefat.page tr:nth-child(1) td:nth-child(10)', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'center'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Add new fields').'</h3><p>'.wpl_esc::return_html_t("Any field missed? Don't worry. You can add your desired fields in less than 1 minute.").'</p>';
$tips[] = array('id'=>7, 'selector'=>'.flex-right-panel .panel-wp:first h3', 'content'=>$content, 'position'=>array('edge'=>'right', 'align'=>'top'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next')), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

$content = '<h3>'.wpl_esc::return_html_t('Add new data categories').'</h3><p>'.wpl_esc::return_html_t("if you need new data categories, you can simply add them using this form.").'</p>';
$tips[] = array('id'=>8, 'selector'=>'.flex-right-panel .panel-wp:last h3', 'content'=>$content, 'position'=>array('edge'=>'right', 'align'=>'top'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next Menu'), 'code'=>'window.location.href = "admin.php?page=wpl_admin_locations&wpltour=1";'), 3=>array('label'=>wpl_esc::return_html_t('Previous'))));

return $tips;