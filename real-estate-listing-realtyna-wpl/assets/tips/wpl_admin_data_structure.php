<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** define tips **/
$tips = array();

$content = '<h3>'.wpl_esc::return_html_t('Manage Property Types').'</h3><p>'.wpl_esc::return_html_t('Here you can add new property types or manage existing property types.').'</p>';
$tips[] = array('id'=>1, 'selector'=>'#wpl_slide_label_id_property_types', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'), 'code'=>'wplj("#wpl_slide_label_id_listing_types").trigger("click");')));

$content = '<h3>'.wpl_esc::return_html_t('Manage Listing Types').'</h3><p>'.wpl_esc::return_html_t('Also you can manage listing types and Google Maps marker icons here.').'</p>';
$tips[] = array('id'=>2, 'selector'=>'#wpl_slide_label_id_listing_types', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'), 'code'=>'wplj("#wpl_slide_label_id_unit_manager").trigger("click");'), 3=>array('label'=>wpl_esc::return_html_t('Previous'), 'code'=>'wplj("#wpl_slide_label_id_property_types").trigger("click");')));

$content = '<h3>'.wpl_esc::return_html_t('Measuring Units').'</h3><p>'.wpl_esc::return_html_t('Here you can manage WPL currencies and other measuring units. You can enable your desired units or disable default units of WPL very simply.').'</p>';
$tips[] = array('id'=>3, 'selector'=>'#wpl_slide_label_id_unit_manager', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'), 'code'=>'wplj("#wpl_slide_label_id_sort_options").trigger("click");'), 3=>array('label'=>wpl_esc::return_html_t('Previous'), 'code'=>'wplj("#wpl_slide_label_id_listing_types").trigger("click");')));

$content = '<h3>'.wpl_esc::return_html_t('Sort Options').'</h3><p>'.wpl_esc::return_html_t('In this section, you can manage sort options on property listing and agent listing views. You can add more sort options to this list from WPL->Flex menu.').'</p>';
$tips[] = array('id'=>4, 'selector'=>'#wpl_slide_label_id_sort_options', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next'), 'code'=>'wplj("#wpl_slide_label_id_room_types").trigger("click");'), 3=>array('label'=>wpl_esc::return_html_t('Previous'), 'code'=>'wplj("#wpl_slide_label_id_unit_manager").trigger("click");')));

$content = '<h3>'.wpl_esc::return_html_t('Room Types').'</h3><p>'.wpl_esc::return_html_t('If you\'re using advanced room module of WPL, then you can manage the room types here.').'</p>';
$tips[] = array('id'=>5, 'selector'=>'#wpl_slide_label_id_room_types', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>wpl_esc::return_html_t('Next Menu'), 'code'=>'window.location.href = "admin.php?page=wpl_admin_flex&wpltour=1";'), 3=>array('label'=>wpl_esc::return_html_t('Previous'), 'code'=>'wplj("#wpl_slide_label_id_sort_options").trigger("click");')));

return $tips;